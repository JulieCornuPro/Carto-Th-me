<?php
/**
 * CARTO Theme — template-parts/components/section-header.php
 * En-tête de section réutilisable : eyebrow + titre + divider + lead.
 *
 * Usage :
 *   get_template_part( 'template-parts/components/section-header', null, [
 *     'eyebrow'  => 'Fonctionnalités',
 *     'title'    => 'Analyse <span class="accent">intelligente</span>',
 *     'lead'     => 'Description optionnelle sous le titre.',
 *     'align'    => 'center',  // 'left' (défaut) ou 'center'
 *     'class'    => 'reveal-on-scroll',
 *   ] );
 */

$eyebrow = $args['eyebrow'] ?? '';
$title   = $args['title']   ?? '';
$lead    = $args['lead']    ?? '';
$align   = $args['align']   ?? 'left';
$class   = $args['class']   ?? 'reveal-on-scroll';

$style = $align === 'center' ? 'text-align:center' : '';
$divider_style = $align === 'center' ? 'margin-left:auto;margin-right:auto' : '';
$lead_style    = $align === 'center' ? 'margin-left:auto;margin-right:auto;text-align:center' : '';
?>

<?php if ( $eyebrow || $title || $lead ) : ?>
<div class="<?php echo esc_attr( $class ); ?>" <?php echo $style ? 'style="' . esc_attr($style) . '"' : ''; ?>>

    <?php if ( $eyebrow ) : ?>
        <div class="section__eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
    <?php endif; ?>

    <?php if ( $title ) : ?>
        <h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
    <?php endif; ?>

    <?php if ( $eyebrow || $title ) : ?>
        <div class="section__divider" aria-hidden="true" <?php echo $divider_style ? 'style="' . esc_attr($divider_style) . '"' : ''; ?>></div>
    <?php endif; ?>

    <?php if ( $lead ) : ?>
        <p class="section__lead" <?php echo $lead_style ? 'style="' . esc_attr($lead_style) . '"' : ''; ?>>
            <?php echo esc_html( $lead ); ?>
        </p>
    <?php endif; ?>

</div>
<?php endif; ?>
