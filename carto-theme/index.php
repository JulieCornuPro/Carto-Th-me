<?php
/**
 * CARTO Theme — index.php (refactorisé)
 */
get_header();
?>

<section style="padding-top:clamp(80px,10vw,140px);padding-bottom:clamp(48px,6vw,88px)">
    <div class="carto-wrap">

        <header style="margin-bottom:56px">
            <?php get_template_part( 'template-parts/components/section-header', null, [
                'eyebrow' => get_bloginfo('name'),
                'title'   => __( 'Tous les <span class="accent">articles</span>', 'carto' ),
                'class'   => '',
            ] ); ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid-3">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>
            <nav class="pagination" role="navigation">
                <?php echo paginate_links( [ 'prev_text' => '←', 'next_text' => '→', 'type' => 'list' ] ); ?>
            </nav>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
