<?php
/**
 * CARTO Theme — template-parts/content/content-card.php
 * Post-card réutilisable : utilisée dans archive.php, index.php, front-page.php
 *
 * Usage : get_template_part( 'template-parts/content/content', 'card' );
 */
?>
<article <?php post_class('post-card reveal-on-scroll'); ?> aria-label="<?php the_title_attribute(); ?>">

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-card__thumb">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'carto-card', [ 'alt' => '' ] ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-card__meta">
        <?php
        $cats = get_the_category();
        if ( $cats ) {
            echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="badge badge--teal">'
                . esc_html( $cats[0]->name ) . '</a>';
        }
        ?>
        <span><?php echo get_the_date( 'd M Y' ); ?></span>
        <span><?php printf( __( '%s min', 'carto' ), ceil( str_word_count( get_the_content() ) / 200 ) ); ?></span>
    </div>

    <h2 class="post-card__title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>

    <p class="post-card__excerpt"><?php the_excerpt(); ?></p>

    <a href="<?php the_permalink(); ?>" class="post-card__link" aria-label="<?php printf( esc_attr__( 'Lire : %s', 'carto' ), get_the_title() ); ?>">
        <?php _e( "Lire l'article", 'carto' ); ?>
    </a>

</article>
