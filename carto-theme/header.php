<?php
/**
 * CARTO Theme — header.php
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="sr-only" href="#main-content"><?php _e( 'Aller au contenu', 'carto' ); ?></a>

<header class="site-header" role="banner">
    <div class="site-header__inner">

        <!-- Branding -->
        <div class="site-branding">
            <span class="site-branding__dot" aria-hidden="true"></span>
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <span class="site-branding__name">
                        <?php
                        $name = get_bloginfo( 'name' );
                        // Séparer les deux premiers mots pour l'effet CARTO//NOM
                        $parts = explode( ' ', $name, 2 );
                        if ( count($parts) === 2 ) {
                            echo esc_html( $parts[0] ) . '<span> // ' . esc_html( $parts[1] ) . '</span>';
                        } else {
                            echo esc_html( $name );
                        }
                        ?>
                    </span>
                </a>
            <?php endif; ?>
            <?php if ( get_bloginfo( 'description' ) ) : ?>
                <span class="site-branding__tag" aria-hidden="true">
                    <?php bloginfo( 'description' ); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Nav principale (desktop) -->
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav class="main-navigation" id="site-navigation" role="navigation"
                 aria-label="<?php esc_attr_e( 'Menu principal', 'carto' ); ?>">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'depth'          => 2,
                    'fallback_cb'    => false,
                    // Le walker produit les panneaux déroulants : intitulé de
                    // rubrique, chevrons et compteurs. Sans lui, les sous-menus
                    // s'afficheraient à plat dans la barre.
                    'walker'         => new Carto_Nav_Walker(),
                ] );
                ?>
                <?php if ( class_exists( 'NPQ_Espace' ) ) echo NPQ_Espace::bloc_compte( 'menu' ); ?>
            </nav>
        <?php endif; ?>

        <!-- Ressort : pousse le compte et le panier contre le bord droit -->
        <span class="site-header__spacer" aria-hidden="true"></span>

        <?php if ( class_exists( 'NPQ_Espace' ) ) echo NPQ_Espace::bloc_compte(); ?>

        <!-- Panier (rien si WooCommerce est absent) -->
        <?php get_template_part( 'template-parts/global/header-cart' ); ?>

        <!-- Burger mobile -->
        <button class="nav-toggle" id="nav-toggle" aria-controls="site-navigation" aria-expanded="false">
            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <line x1="0" y1="1" x2="16" y2="1"/>
                <line x1="0" y1="6" x2="16" y2="6"/>
                <line x1="0" y1="11" x2="16" y2="11"/>
            </svg>
            <span>Menu</span>
        </button>

    </div>
</header>

<div class="site-main" id="main-content">
