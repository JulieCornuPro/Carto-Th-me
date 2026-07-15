<?php
/**
 * CARTO Theme — template-parts/components/cta-banner.php
 * Bandeau CTA centré, réutilisable en bas de n'importe quelle page.
 *
 * Usage :
 *   get_template_part( 'template-parts/components/cta-banner', null, [
 *     'eyebrow'    => 'Prêt à cartographier ?',
 *     'title'      => 'Lancez votre <span class="accent">audit</span>',
 *     'lead'       => 'Démarrez avec une analyse gratuite.',
 *     'cta_label'  => 'Demander une démo',
 *     'cta_url'    => '#contact',
 *     'cta2_label' => 'Documentation',
 *     'cta2_url'   => '#docs',
 *     'section_id' => 'contact',
 *   ] );
 */

$eyebrow   = $args['eyebrow']   ?? __( 'Prêt à cartographier ?', 'carto' );
$title     = $args['title']     ?? __( 'Lancez votre <span class="accent">audit</span>', 'carto' );
$lead      = $args['lead']      ?? __( 'Démarrez avec une analyse gratuite de votre code. Résultats en moins de 24h.', 'carto' );
$cta_label = $args['cta_label'] ?? get_theme_mod( 'hero_cta_label', __( 'Demander une démo', 'carto' ) );
$cta_url   = $args['cta_url']   ?? get_theme_mod( 'hero_cta_url', '#' );
$cta2_label = $args['cta2_label'] ?? __( 'Lire la documentation', 'carto' );
$cta2_url   = $args['cta2_url']   ?? get_theme_mod( 'docs_url', '#' );
$section_id = $args['section_id'] ?? 'cta';
?>

<section class="section" id="<?php echo esc_attr( $section_id ); ?>">
    <div class="carto-wrap" style="max-width:720px;margin:0 auto;text-align:center">

        <div class="reveal-on-scroll">

            <?php get_template_part( 'template-parts/components/section-header', null, [
                'eyebrow' => $eyebrow,
                'title'   => $title,
                'lead'    => $lead,
                'align'   => 'center',
                'class'   => '',
            ] ); ?>

            <div class="hero__actions" style="justify-content:center;margin-top:0">
                <?php if ( $cta_label ) : ?>
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
                        <?php echo esc_html( $cta_label ); ?>
                    </a>
                <?php endif; ?>
                <?php if ( $cta2_label ) : ?>
                    <a href="<?php echo esc_url( $cta2_url ); ?>" class="btn btn--outline">
                        <?php echo esc_html( $cta2_label ); ?>
                    </a>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
