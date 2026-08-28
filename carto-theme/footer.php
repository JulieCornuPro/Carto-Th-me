<?php
/**
 * CARTO Theme — footer.php
 */
?>
</div><!-- .site-main -->

<footer class="site-footer" role="contentinfo">
    <div class="carto-wrap">

        <div class="site-footer__grid">

            <!-- Colonne brand -->
            <div class="site-footer__brand">
                <div class="site-branding" style="margin-bottom:20px">
                    <span class="site-branding__dot" aria-hidden="true"></span>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <span class="site-branding__name">
                            <?php
                            $name  = get_bloginfo( 'name' );
                            $parts = explode( ' ', $name, 2 );
                            if ( count($parts) === 2 ) {
                                echo esc_html( $parts[0] ) . '<span>//' . esc_html( $parts[1] ) . '</span>';
                            } else {
                                echo esc_html( $name );
                            }
                            ?>
                        </span>
                    </a>
                </div>
                <?php if ( get_theme_mod( 'footer_description', get_bloginfo('description') ) ) : ?>
                    <p class="site-footer__desc">
                        <?php echo esc_html( get_theme_mod( 'footer_description', get_bloginfo('description') ) ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Navigation footer -->
            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <div class="site-footer__col">
                    <div class="site-footer__col-title">
                        <?php _e( 'Navigation', 'carto' ); ?>
                    </div>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'site-footer__links',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ] );
                    ?>
                </div>
            <?php endif; ?>

            <!-- Colonnes de liens (widgets) -->
            <?php
            /*
             * Chaque colonne est une zone de widgets distincte. Une colonne
             * sans widget n'imprime rien du tout : la grille se referme
             * dessus, plutôt que de laisser un vide qu'on prendrait pour un
             * oubli.
             */
            foreach ( [ 'footer-2', 'footer-3', 'footer-4' ] as $zone ) :
                if ( ! is_active_sidebar( $zone ) ) {
                    continue;
                }
                ?>
                <div class="site-footer__col">
                    <?php dynamic_sidebar( $zone ); ?>
                </div>
            <?php endforeach; ?>

            <!-- Contact / infos -->
            <?php if ( get_theme_mod( 'footer_contact' ) ) : ?>
                <div class="site-footer__col">
                    <div class="site-footer__col-title"><?php _e( 'Contact', 'carto' ); ?></div>
                    <div class="site-footer__links">
                        <?php echo wp_kses_post( get_theme_mod( 'footer_contact' ) ); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- .site-footer__grid -->

        <!-- Barre basse -->
        <div class="site-footer__bottom">
            <span class="site-branding__dot" aria-hidden="true" style="width:6px;height:6px;border-radius:50%;background:var(--carto-teal);box-shadow:0 0 6px var(--carto-teal);flex-shrink:0"></span>

            <span>
                <?php
                printf(
                    '© %s <strong>%s</strong>',
                    esc_html( date_i18n( 'Y' ) ),
                    esc_html( get_bloginfo( 'name' ) )
                );
                ?>
            </span>

            <?php if ( function_exists( 'the_privacy_policy_link' ) ) : ?>
                <?php the_privacy_policy_link( '<span class="text-muted">', '</span>' ); ?>
            <?php endif; ?>

            <span class="sp"></span>

            <span style="font-family:var(--carto-font-mono);font-size:11px;color:var(--carto-muted);letter-spacing:0.14em">
                <?php printf( __( 'Propulsé par %s', 'carto' ), '<a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a>' ); ?>
            </span>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
