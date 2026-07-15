<?php
/**
 * CARTO Theme — template-parts/content/content-single.php
 * Corps complet d'un article de blog.
 *
 * Usage : get_template_part( 'template-parts/content/content', 'single' );
 */
?>
<article <?php post_class(); ?>>

    <!-- En-tête -->
    <header class="entry-header" style="max-width:800px;margin-bottom:48px">

        <div class="post-card__meta" style="margin-bottom:16px">
            <?php
            $cats = get_the_category();
            if ( $cats ) {
                echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="badge badge--teal">'
                    . esc_html( $cats[0]->name ) . '</a>';
            }
            ?>
            <span><?php echo get_the_date( 'd M Y' ); ?></span>
            <span><?php printf( __( '%s min de lecture', 'carto' ), ceil( str_word_count( get_the_content() ) / 200 ) ); ?></span>
        </div>

        <h1 class="section__title" style="font-size:var(--carto-fs-display)">
            <?php the_title(); ?>
        </h1>

        <div class="section__divider" aria-hidden="true"></div>

        <?php if ( has_excerpt() ) : ?>
            <p class="entry-excerpt" style="font-size:var(--carto-fs-lead);color:var(--carto-text-lo);line-height:1.55">
                <?php the_excerpt(); ?>
            </p>
        <?php endif; ?>

        <!-- Auteur -->
        <div class="entry-author" style="display:flex;align-items:center;gap:16px;margin-top:32px;padding-top:24px;border-top:1px solid var(--carto-border)">
            <?php echo get_avatar( get_the_author_meta('ID'), 48, '', '', [
                'style' => 'border-radius:50%;border:2px solid var(--carto-border);flex-shrink:0'
            ] ); ?>
            <div>
                <div style="font-family:var(--carto-font-display);font-size:14px;font-weight:700;text-transform:uppercase;color:var(--carto-white)">
                    <?php the_author(); ?>
                </div>
                <div style="font-family:var(--carto-font-mono);font-size:11px;color:var(--carto-muted);letter-spacing:0.12em;text-transform:uppercase">
                    <?php echo esc_html( get_the_author_meta('description') ?: __( 'Auteur', 'carto' ) ); ?>
                </div>
            </div>
        </div>

    </header>

    <!-- Image mise en avant -->
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="entry-thumbnail" style="margin-bottom:56px;border:1px solid var(--carto-border)">
            <?php the_post_thumbnail( 'carto-hero', [ 'style' => 'width:100%;height:auto;display:block', 'alt' => get_the_title() ] ); ?>
        </div>
    <?php endif; ?>

    <!-- Contenu -->
    <div class="entry-content" style="max-width:800px">
        <?php
        the_content( sprintf(
            wp_kses( __( 'Lire la suite<span class="sr-only"> "%s"</span>', 'carto' ), [ 'span' => [ 'class' => [] ] ] ),
            get_the_title()
        ) );

        wp_link_pages( [
            'before' => '<nav class="pagination" style="margin-top:40px">',
            'after'  => '</nav>',
        ] );
        ?>
    </div>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ( $tags ) : ?>
        <footer class="entry-footer" style="margin-top:48px;padding-top:24px;border-top:1px solid var(--carto-border);display:flex;flex-wrap:wrap;gap:8px;align-items:center">
            <span style="font-family:var(--carto-font-mono);font-size:11px;color:var(--carto-muted);letter-spacing:0.14em;text-transform:uppercase">
                <?php _e( 'Tags :', 'carto' ); ?>
            </span>
            <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge badge--muted">
                    <?php echo esc_html( $tag->name ); ?>
                </a>
            <?php endforeach; ?>
        </footer>
    <?php endif; ?>

</article>
