<?php
/**
 * CARTO Theme — template-parts/content/content-none.php
 * Affiché quand aucun article n'est trouvé.
 *
 * Usage : get_template_part( 'template-parts/content/content', 'none' );
 */
?>
<div class="card card--accent" style="max-width:540px">

    <div class="card__label">
        <?php _e( '// 404 — Aucun résultat', 'carto' ); ?>
    </div>

    <h2 class="card__title">
        <?php
        if ( is_search() ) {
            _e( 'Aucun résultat pour cette recherche', 'carto' );
        } else {
            _e( 'Aucun contenu trouvé', 'carto' );
        }
        ?>
    </h2>

    <p class="card__body">
        <?php
        if ( is_search() ) {
            _e( 'Essayez avec des mots-clés différents ou parcourez les catégories.', 'carto' );
        } else {
            _e( 'Il n\'y a pas encore de contenu dans cette section. Revenez bientôt.', 'carto' );
        }
        ?>
    </p>

    <?php if ( is_search() ) : ?>
        <div class="card__footer">
            <?php get_search_form(); ?>
        </div>
    <?php else : ?>
        <div class="card__footer">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline">
                <?php _e( '← Retour à l\'accueil', 'carto' ); ?>
            </a>
        </div>
    <?php endif; ?>

</div>
