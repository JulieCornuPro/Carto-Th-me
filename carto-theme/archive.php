<?php
/**
 * CARTO Theme — archive.php (refactorisé)
 */
get_header();
?>

<section style="padding-top:clamp(80px,10vw,140px)">
    <div class="carto-wrap">

        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

        <header style="margin-bottom:56px;padding-bottom:40px;border-bottom:1px solid var(--carto-border)">
            <?php
            get_template_part( 'template-parts/components/section-header', null, [
                'eyebrow' => is_category() ? __( 'Catégorie', 'carto' )
                           : ( is_tag()    ? __( 'Tag', 'carto' )
                           : ( is_author() ? __( 'Auteur', 'carto' )
                           :                 __( 'Archives', 'carto' ) ) ),
                'title'   => is_author()
                             ? '<span class="accent">' . esc_html( get_the_author() ) . '</span>'
                             : wp_kses_post( single_cat_title('',false) ?: single_tag_title('',false) ?: get_the_archive_title() ),
                'lead'    => get_the_archive_description(),
                'class'   => '',
            ] );
            ?>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="grid-3">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" role="navigation" aria-label="<?php esc_attr_e( 'Navigation entre pages', 'carto' ); ?>">
                <?php echo paginate_links( [ 'prev_text' => '←', 'next_text' => '→', 'type' => 'list' ] ); ?>
            </nav>

        <?php else : ?>
            <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
