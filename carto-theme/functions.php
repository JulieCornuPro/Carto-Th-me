<?php
/**
 * CARTO Theme — functions.php
 * Enqueue styles/scripts, supports WordPress, menus, shortcodes composants animés.
 */

defined( 'ABSPATH' ) || exit;

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
