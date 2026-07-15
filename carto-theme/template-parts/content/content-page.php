<?php
/**
 * CARTO Theme — template-parts/content/content-page.php
 * Corps d'une page WordPress statique.
 *
 * Usage : get_template_part( 'template-parts/content/content', 'page' );
 */
?>
<article <?php post_class(); ?>>

    <header class="entry-header" style="margin-bottom:16px">
        <?php
        $ancestors = get_ancestors( get_the_ID(), 'page' );
        if ( $ancestors ) :
        ?>
        <div class="section__eyebrow">
            <?php echo esc_html( get_the_title( end( $ancestors ) ) ); ?>
        </div>
        <?php endif; ?>
        <h1 class="section__title"><?php the_title(); ?></h1>
        <div class="section__divider" aria-hidden="true"></div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-thumbnail" style="margin-bottom:48px;border:1px solid var(--carto-border)">
            <?php the_post_thumbnail( 'carto-hero', [
                'style' => 'width:100%;height:auto;display:block',
                'alt'   => get_the_title()
            ] ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages( [
            'before' => '<nav class="pagination" style="margin-top:40px">',
            'after'  => '</nav>',
        ] );
        ?>
    </div>

</article>
