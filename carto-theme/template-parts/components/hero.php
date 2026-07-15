<?php
/**
 * CARTO Theme — template-parts/components/hero.php
 * Bloc hero réutilisable sur n'importe quelle page.
 *
 * Passer des données via $args :
 *   get_template_part( 'template-parts/components/hero', null, [
 *     'eyebrow'     => 'Sécurité applicative',
 *     'title_line1' => 'Cartographiez',
 *     'title_line2' => 'vos risques',
 *     'lead'        => 'Description…',
 *     'cta_label'   => 'Demander une démo',
 *     'cta_url'     => '#contact',
 *     'cta2_label'  => 'En savoir plus',
 *     'cta2_url'    => '#features',
 *     'show_kpi'    => true,   // afficher les KPI animés
 *     'show_corners'=> true,   // coins décoratifs
 *   ] );
 */

// Valeurs par défaut
$eyebrow     = $args['eyebrow']     ?? get_theme_mod( 'hero_eyebrow',    __( 'Sécurité applicative', 'carto' ) );
$title1      = $args['title_line1'] ?? get_theme_mod( 'hero_title_line1', __( 'Cartographiez', 'carto' ) );
$title2      = $args['title_line2'] ?? get_theme_mod( 'hero_title_line2', __( 'vos risques', 'carto' ) );
$lead        = $args['lead']        ?? get_theme_mod( 'hero_lead',        __( 'Une plateforme d\'audit de code AppSec qui cartographie vos vulnérabilités, réduit le bruit et priorise les chemins critiques.', 'carto' ) );
$cta_label   = $args['cta_label']   ?? get_theme_mod( 'hero_cta_label',   __( 'Demander une démo', 'carto' ) );
$cta_url     = $args['cta_url']     ?? get_theme_mod( 'hero_cta_url',     '#contact' );
$cta2_label  = $args['cta2_label']  ?? get_theme_mod( 'hero_cta2_label',  __( 'Voir les fonctionnalités', 'carto' ) );
$cta2_url    = $args['cta2_url']    ?? get_theme_mod( 'hero_cta2_url',    '#features' );
$show_kpi    = $args['show_kpi']    ?? true;
$show_corners = $args['show_corners'] ?? true;

$kpi_id_prefix = 'hero-kpi-' . wp_unique_id();
?>

<section class="hero" <?php echo $show_corners ? '' : 'style="padding-top:clamp(80px,10vw,140px)"'; ?>>

    <?php if ( $show_corners ) : ?>
        <span class="hero__corner-bl" aria-hidden="true"></span>
        <span class="hero__corner-br" aria-hidden="true"></span>
    <?php endif; ?>

    <div class="carto-wrap">

        <?php if ( $eyebrow ) : ?>
            <div class="hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
        <?php endif; ?>

        <h1 class="hero__title">
            <?php echo esc_html( $title1 ); ?><br>
            <span class="accent"><?php echo esc_html( $title2 ); ?></span>
        </h1>

        <div class="hero__divider" aria-hidden="true"></div>

        <?php if ( $lead ) : ?>
            <p class="hero__lead"><?php echo esc_html( $lead ); ?></p>
        <?php endif; ?>

        <?php if ( $cta_label || $cta2_label ) : ?>
            <div class="hero__actions">
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
        <?php endif; ?>

        <?php if ( $show_kpi ) : ?>
            <div class="hero__kpi" aria-label="<?php esc_attr_e( 'Chiffres clés', 'carto' ); ?>">
                <div class="hero__kpi-item">
                    <div id="<?php echo esc_attr( $kpi_id_prefix . '-1' ); ?>"></div>
                </div>
                <div class="hero__kpi-item">
                    <div id="<?php echo esc_attr( $kpi_id_prefix . '-2' ); ?>"></div>
                </div>
                <div class="hero__kpi-item">
                    <div id="<?php echo esc_attr( $kpi_id_prefix . '-3' ); ?>"></div>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Carto === 'undefined') return;
                var T = Carto.colors.TEAL, O = Carto.colors.ORANGE;
                var p = <?php echo json_encode( $kpi_id_prefix ); ?>;
                Carto.counter(document.getElementById(p+'-1'), { value:1.8, decimals:1, prefix:'×', label:'Surface couverte', color:T });
                Carto.counter(document.getElementById(p+'-2'), { value:63, suffix:'%', label:'Bruit en moins', color:O });
                Carto.counter(document.getElementById(p+'-3'), { value:4, label:'Chemins critiques', color:O });
            });
            </script>
        <?php endif; ?>

    </div>
</section>
