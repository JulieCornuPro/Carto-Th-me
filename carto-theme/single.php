<?php
/**
 * CARTO Theme — single.php (refactorisé)
 */
get_header();
?>

<section style="padding-top:clamp(80px,10vw,140px)">
    <div class="carto-wrap">

        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'template-parts/content/content', 'single' ); ?>
            <?php get_template_part( 'template-parts/components/post-navigation' ); ?>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <div style="margin-top:64px">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</section>

<?php get_template_part( 'template-parts/components/cta-banner' ); ?>

<?php get_footer(); ?>
