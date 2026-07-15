<?php
/**
 * CARTO Theme — template-parts/global/nav-primary.php
 * Markup de la navigation principale, extrait de header.php.
 *
 * Usage : get_template_part( 'template-parts/global/nav-primary' );
 */

if ( ! has_nav_menu( 'primary' ) ) return;
?>

<nav class="main-navigation"
     id="site-navigation"
     role="navigation"
     aria-label="<?php esc_attr_e( 'Menu principal', 'carto' ); ?>">

    <?php
    wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'container'      => false,
        'depth'          => 2,
        'fallback_cb'    => false,
        'walker'         => class_exists('Carto_Nav_Walker') ? new Carto_Nav_Walker() : null,
    ] );
    ?>

</nav>
