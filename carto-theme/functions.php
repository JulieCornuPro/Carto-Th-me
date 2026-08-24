<?php
/**
 * CARTO Theme — functions.php
 * Enqueue styles/scripts, supports WordPress, menus, shortcodes composants animés.
 */

defined( 'ABSPATH' ) || exit;

/* ─── Fichiers du thème ───────────────────────────────────────────────── */
// Le walker du menu principal : il produit le balisage des panneaux
// déroulants (intitulé de rubrique, chevrons, compteurs).
require_once get_template_directory() . '/inc/class-carto-nav-walker.php';

/* ─── Supports WordPress ──────────────────────────────────────────────── */
function carto_setup() {
    load_theme_textdomain( 'carto', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // Tailles d'images
    add_image_size( 'carto-hero',   1920, 960,  true );
    add_image_size( 'carto-card',   800,  500,  true );
    add_image_size( 'carto-thumb',  400,  250,  true );

    // Menus
    register_nav_menus( [
        'primary' => __( 'Menu principal', 'carto' ),
        'footer'  => __( 'Menu footer',   'carto' ),
    ] );
}
add_action( 'after_setup_theme', 'carto_setup' );

/* ─── Enqueue styles & scripts ────────────────────────────────────────── */
function carto_enqueue_assets() {
    $ver = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    // CSS principal
    wp_enqueue_style(
        'carto-theme',
        $uri . '/assets/css/carto-theme.css',
        [],
        $ver
    );

    // Composants animés Carto
    wp_enqueue_script(
        'carto-components',
        $uri . '/assets/js/carto-components.js',
        [],
        $ver,
        true
    );

    // JS principal du thème
    wp_enqueue_script(
        'carto-theme',
        $uri . '/assets/js/carto-theme.js',
        [ 'carto-components' ],
        $ver,
        true
    );

    // Variables PHP → JS
    wp_localize_script( 'carto-theme', 'cartoData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'carto-nonce' ),
        'homeUrl' => home_url(),
    ] );
}
add_action( 'wp_enqueue_scripts', 'carto_enqueue_assets' );

/* ─── Panier dans l'en-tête ───────────────────────────────────────────── */

/**
 * Le bouton panier de l'en-tête.
 *
 * Rendu par une fonction plutôt qu'écrit dans le gabarit, parce que
 * WooCommerce le redemande tel quel en AJAX après chaque ajout au panier
 * (filtre « fragments » plus bas). Deux balisages qui doivent rester
 * identiques valent mieux écrits une seule fois.
 *
 * La classe .carto-cart sert de point d'ancrage au remplacement AJAX : c'est
 * elle que WooCommerce cherche dans la page pour y injecter la version à jour.
 *
 * @return string HTML du bouton, chaîne vide sans WooCommerce.
 */
function carto_header_cart_html() {
    // WC()->cart n'existe pas partout (administration, requêtes REST, tout
    // début du chargement) : sans ce garde-fou, l'en-tête casse la page.
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return '';
    }

    $nombre = (int) WC()->cart->get_cart_contents_count();

    // wc_get_cart_url() retombe silencieusement sur la RACINE du site quand la
    // page « Panier » n'est pas assignée dans WooCommerce → Réglages →
    // Avancé. Le bouton semble alors fonctionner, et ramène à l'accueil.
    //
    // On préfère la boutique comme repli : à défaut d'un panier, on atterrit
    // au moins sur des produits. Le vrai remède reste d'assigner la page dans
    // les réglages — ce repli ne fait que rendre la panne moins déroutante.
    $url = ( wc_get_page_id( 'cart' ) > 0 )
        ? wc_get_cart_url()
        : wc_get_page_permalink( 'shop', home_url( '/' ) );

    // Le libellé lu à voix haute doit dire ce que le chiffre signifie : « 3 »
    // seul ne veut rien dire hors du contexte visuel.
    $aria = sprintf(
        _n( 'Panier, %s article', 'Panier, %s articles', $nombre, 'carto' ),
        number_format_i18n( $nombre )
    );

    ob_start();
    ?>
    <a class="carto-cart<?php echo $nombre ? '' : ' carto-cart--vide'; ?>"
       href="<?php echo esc_url( $url ); ?>"
       aria-label="<?php echo esc_attr( $aria ); ?>">
        <span class="carto-cart__label" aria-hidden="true"><?php esc_html_e( 'Panier', 'carto' ); ?></span>
        <span class="carto-cart__count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $nombre ) ); ?></span>
    </a>
    <?php
    return ob_get_clean();
}

/**
 * Rafraîchit le compteur après un ajout au panier.
 *
 * WooCommerce ajoute au panier en AJAX : sans ce filtre, le chiffre de
 * l'en-tête resterait celui du chargement de la page jusqu'à la navigation
 * suivante — un panier qui ne bouge pas quand on y met quelque chose donne
 * l'impression que le clic n'a pas fonctionné.
 *
 * @param array $fragments Morceaux de page à remplacer, indexés par sélecteur CSS.
 * @return array
 */
function carto_cart_fragment( $fragments ) {
    $html = carto_header_cart_html();
    if ( '' !== $html ) {
        $fragments['a.carto-cart'] = $html;
    }
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'carto_cart_fragment' );

/* ─── Widgets / Sidebars ──────────────────────────────────────────────── */
function carto_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Sidebar Blog', 'carto' ),
        'id'            => 'sidebar-blog',
        'description'   => __( 'Widgets affichés dans la sidebar du blog.', 'carto' ),
        'before_widget' => '<div class="widget card card--accent">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="card__label">',
        'after_title'   => '</div>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Col 2', 'carto' ),
        'id'            => 'footer-2',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="site-footer__col-title">',
        'after_title'   => '</div>',
    ] );
}
add_action( 'widgets_init', 'carto_widgets_init' );

/* ─── Titre de page custom ────────────────────────────────────────────── */
function carto_wp_title( $title, $sep ) {
    if ( is_feed() ) return $title;
    global $paged, $page;
    if ( is_home() || is_front_page() ) {
        $site_name = get_bloginfo( 'name' );
        $site_desc = get_bloginfo( 'description' );
        if ( $site_desc && ( is_home() || is_front_page() ) ) {
            $title = "$site_name $sep $site_desc";
        }
        return $title;
    }
    $title .= get_bloginfo( 'name' );
    if ( $paged >= 2 || $page >= 2 ) {
        $title .= " $sep " . sprintf( __( 'Page %s', 'carto' ), max( $paged, $page ) );
    }
    return $title;
}
add_filter( 'wp_title', 'carto_wp_title', 10, 2 );

/* ─── Excerpt ──────────────────────────────────────────────────────────── */
add_filter( 'excerpt_length', fn() => 28 );
add_filter( 'excerpt_more',   fn() => '…' );

/* ─── Shortcodes composants animés Carto ─────────────────────────────── */

/**
 * [carto_kpi value="72" suffix="%" label="Score de sécurité" color="teal"]
 */
function carto_shortcode_kpi( $atts ) {
    $a = shortcode_atts( [
        'value'    => '0',
        'suffix'   => '',
        'prefix'   => '',
        'label'    => '',
        'decimals' => '0',
        'color'    => 'teal',
    ], $atts );

    $color_map = [
        'teal'   => '#00CFCF',
        'orange' => '#FF6B35',
        'amber'  => '#E8B84B',
    ];
    $color = $color_map[ $a['color'] ] ?? '#00CFCF';

    $id = 'carto-kpi-' . wp_unique_id();
    ob_start();
    ?>
    <div class="carto-stage" style="max-width:320px">
        <div class="carto-stage__grid"></div>
        <div class="carto-stage__inner">
            <div id="<?php echo esc_attr($id); ?>"></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Carto !== 'undefined') {
            Carto.counter(document.getElementById(<?php echo json_encode($id); ?>), {
                value:    <?php echo (float) $a['value']; ?>,
                suffix:   <?php echo json_encode($a['suffix']); ?>,
                prefix:   <?php echo json_encode($a['prefix']); ?>,
                label:    <?php echo json_encode($a['label']); ?>,
                decimals: <?php echo (int) $a['decimals']; ?>,
                color:    <?php echo json_encode($color); ?>
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'carto_kpi', 'carto_shortcode_kpi' );

/**
 * [carto_gauge value="72" max="100" label="Posture" color="teal"]
 */
function carto_shortcode_gauge( $atts ) {
    $a = shortcode_atts( [
        'value' => '72',
        'max'   => '100',
        'label' => 'Score',
        'color' => 'teal',
    ], $atts );

    $color_map = [ 'teal' => '#00CFCF', 'orange' => '#FF6B35', 'amber' => '#E8B84B' ];
    $color = $color_map[ $a['color'] ] ?? '#00CFCF';
    $id = 'carto-gauge-' . wp_unique_id();
    ob_start();
    ?>
    <div class="carto-stage" style="max-width:340px">
        <div class="carto-stage__grid"></div>
        <div class="carto-stage__label"><?php echo esc_html($a['label']); ?></div>
        <div class="carto-stage__inner" style="display:flex;justify-content:center">
            <div id="<?php echo esc_attr($id); ?>" style="width:280px"></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Carto !== 'undefined') {
            Carto.gauge(document.getElementById(<?php echo json_encode($id); ?>), {
                value: <?php echo (float) $a['value']; ?>,
                max:   <?php echo (float) $a['max']; ?>,
                label: <?php echo json_encode($a['label']); ?>,
                color: <?php echo json_encode($color); ?>
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'carto_gauge', 'carto_shortcode_gauge' );

/**
 * [carto_sparkline points="42,38,45,40,33,30,22,18,15,11" color="teal"]
 */
function carto_shortcode_sparkline( $atts ) {
    $a = shortcode_atts( [
        'points' => '10,20,15,30,25,40',
        'color'  => 'teal',
        'label'  => '',
    ], $atts );

    $color_map = [ 'teal' => '#00CFCF', 'orange' => '#FF6B35', 'amber' => '#E8B84B' ];
    $color  = $color_map[ $a['color'] ] ?? '#00CFCF';
    $points = array_map( 'floatval', explode( ',', $a['points'] ) );
    $id     = 'carto-spark-' . wp_unique_id();
    ob_start();
    ?>
    <div class="carto-stage">
        <div class="carto-stage__grid"></div>
        <?php if ( $a['label'] ) : ?>
            <div class="carto-stage__label"><?php echo esc_html($a['label']); ?></div>
        <?php endif; ?>
        <div class="carto-stage__inner">
            <div id="<?php echo esc_attr($id); ?>"></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Carto !== 'undefined') {
            Carto.sparkline(document.getElementById(<?php echo json_encode($id); ?>), {
                points: <?php echo json_encode($points); ?>,
                color:  <?php echo json_encode($color); ?>
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'carto_sparkline', 'carto_shortcode_sparkline' );

/**
 * [carto_barchart data="Critique:4:orange,Élevé:11:orange-lt,Moyen:23:amber,Faible:38:teal"]
 */
function carto_shortcode_barchart( $atts ) {
    $a = shortcode_atts( [
        'data'  => 'A:10:teal,B:20:orange',
        'label' => '',
    ], $atts );

    $color_map = [
        'teal'      => '#00CFCF',
        'orange'    => '#FF6B35',
        'orange-lt' => '#FF9466',
        'amber'     => '#E8B84B',
    ];

    $bars = [];
    foreach ( explode( ',', $a['data'] ) as $item ) {
        $parts = explode( ':', trim($item) );
        if ( count($parts) >= 2 ) {
            $bars[] = [
                'label' => $parts[0],
                'value' => (float) $parts[1],
                'color' => $color_map[ $parts[2] ?? 'teal' ] ?? '#00CFCF',
            ];
        }
    }
    $id = 'carto-bar-' . wp_unique_id();
    ob_start();
    ?>
    <div class="carto-stage">
        <div class="carto-stage__grid"></div>
        <?php if ( $a['label'] ) : ?>
            <div class="carto-stage__label"><?php echo esc_html($a['label']); ?></div>
        <?php endif; ?>
        <div class="carto-stage__inner">
            <div id="<?php echo esc_attr($id); ?>"></div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Carto !== 'undefined') {
            Carto.barChart(document.getElementById(<?php echo json_encode($id); ?>), {
                data: <?php echo json_encode($bars); ?>
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'carto_barchart', 'carto_shortcode_barchart' );

/* ─── Gutenberg : enregistrer les variables CSS pour l'éditeur ─────────── */
function carto_editor_styles() {
    add_editor_style( 'assets/css/carto-theme.css' );
}
add_action( 'admin_init', 'carto_editor_styles' );

/* ─── Sécurité de base ─────────────────────────────────────────────────── */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
