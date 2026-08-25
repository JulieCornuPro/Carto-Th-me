<?php
/**
 * CARTO Theme — template-parts/global/breadcrumb.php
 * Fil d'Ariane natif (sans plugin).
 * Compatible Yoast SEO et Rank Math si installés.
 *
 * Usage : get_template_part( 'template-parts/global/breadcrumb' );
 */

/*
 * Les pages de la boutique ont leur propre fil d'Ariane : le plugin NormaPrep
 * en pose un, pleine largeur, dans un bandeau sous l'en-tête. Deux fils sur la
 * même page diraient la même chose de deux façons différentes.
 *
 * On ne se retire que si ce plugin est bien là pour prendre le relais : sans
 * lui, mieux vaut ce fil-ci que pas de fil du tout.
 */
if ( class_exists( 'NPQ_WooCommerce' ) && function_exists( 'is_woocommerce' )
     && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
    return;
}

// Yoast SEO
if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb(
        '<nav class="carto-breadcrumb" aria-label="' . esc_attr__( 'Fil d\'Ariane', 'carto' ) . '">',
        '</nav>'
    );
    return;
}

// Rank Math
if ( function_exists('rank_math_the_breadcrumbs') ) {
    echo '<nav class="carto-breadcrumb" aria-label="' . esc_attr__( 'Fil d\'Ariane', 'carto' ) . '">';
    rank_math_the_breadcrumbs();
    echo '</nav>';
    return;
}

// Natif — uniquement sur pages intérieures
if ( is_front_page() || is_home() ) return;

$crumbs   = [];
$crumbs[] = '<a href="' . esc_url( home_url('/') ) . '">' . __( 'Accueil', 'carto' ) . '</a>';

if ( is_category() || is_single() ) {
    $cats = is_single() ? get_the_category() : null;
    if ( $cats ) {
        $crumbs[] = '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">'
            . esc_html( $cats[0]->name ) . '</a>';
    }
    if ( is_single() ) {
        $crumbs[] = '<span aria-current="page" style="color:var(--carto-orange);font-weight:600">' . esc_html( get_the_title() ) . '</span>';
    }
} elseif ( is_page() ) {
    $ancestors = array_reverse( get_ancestors( get_the_ID(), 'page' ) );
    foreach ( $ancestors as $ancestor_id ) {
        $crumbs[] = '<a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">'
            . esc_html( get_the_title( $ancestor_id ) ) . '</a>';
    }
    $crumbs[] = '<span aria-current="page" style="color:var(--carto-orange);font-weight:600">' . esc_html( get_the_title() ) . '</span>';
} elseif ( is_tag() ) {
    $crumbs[] = '<span aria-current="page" style="color:var(--carto-orange);font-weight:600">' . single_tag_title( '', false ) . '</span>';
} elseif ( is_search() ) {
    $crumbs[] = '<span aria-current="page" style="color:var(--carto-orange);font-weight:600">'
        . sprintf( __( 'Recherche : %s', 'carto' ), get_search_query() )
        . '</span>';
} elseif ( is_404() ) {
    $crumbs[] = '<span aria-current="page" style="color:var(--carto-orange);font-weight:600">' . __( '404 — Page introuvable', 'carto' ) . '</span>';
}

if ( count($crumbs) <= 1 ) return;
?>

<nav class="carto-breadcrumb" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'carto' ); ?>"
     style="font-family:var(--carto-font-mono);font-size:12px;color:var(--carto-muted);
            letter-spacing:0.12em;text-transform:uppercase;padding:16px 0;
            display:flex;align-items:center;flex-wrap:wrap;gap:8px">
    <?php
    $sep = '<span aria-hidden="true" style="color:var(--carto-border)">/</span>';
    echo implode( ' ' . $sep . ' ', $crumbs );
    ?>
</nav>
