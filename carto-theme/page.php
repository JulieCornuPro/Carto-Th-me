<?php
/**
 * CARTO Theme — page.php (refactorisé)
 */
get_header();
?>

<section style="padding-top:clamp(32px,4vw,56px)">
    <div class="carto-wrap">

        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

        <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template-parts/content/content', 'page' ); ?>
        <?php endwhile; ?>

    </div>
</section>

<?php get_footer(); ?>
