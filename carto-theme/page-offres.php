<?php
/**
 * Template Name: Page Offres NormaPrep
 *
 * Modèle dédié à la page des offres/tarifs.
 * Pour l'utiliser : créer une page dans WordPress, puis dans « Attributs de page »
 * → « Modèle » choisir « Page Offres NormaPrep ».
 *
 * Les prix et libellés sont modifiables directement dans ce fichier (section $offres ci-dessous).
 */
get_header();

/* ---- Données des offres : modifiez librement ici ---- */
$offres = [
    [
        'id'        => 'decouverte',
        'nom'       => 'Découverte',
        'prix'      => '0 €',
        'periode'   => '',
        'sous'      => 'pour toujours',
        'sous_color'=> 'muted',
        'features'  => [
            '1 examen blanc découverte',
            'Correction détaillée',
            'Aperçu du tableau de bord',
        ],
        'cta_label' => 'Commencer',
        'cta_url'   => '/inscription',
        'featured'  => false,
    ],
    [
        'id'        => 'annuel',
        'nom'       => 'Annuel',
        'prix'      => '149 €',
        'periode'   => '/an',
        'sous'      => 'soit ~12,40 €/mois · -35%',
        'sous_color'=> 'teal',
        'features'  => [
            'Tous les examens blancs',
            'Tous les scénarios d\'entreprise',
            'Correction détaillée',
            'Suivi de progression complet',
            '2 mois offerts vs mensuel',
        ],
        'cta_label' => 'S\'abonner à l\'année',
        'cta_url'   => '/inscription?offre=annuel',
        'featured'  => true,
        'badge'     => 'Meilleur rapport',
    ],
    [
        'id'        => 'mensuel',
        'nom'       => 'Mensuel',
        'prix'      => '19 €',
        'periode'   => '/mois',
        'sous'      => 'sans engagement',
        'sous_color'=> 'muted',
        'features'  => [
            'Tous les examens blancs',
            'Tous les scénarios d\'entreprise',
            'Correction détaillée',
            'Suivi de progression complet',
        ],
        'cta_label' => 'S\'abonner au mois',
        'cta_url'   => '/inscription?offre=mensuel',
        'featured'  => false,
    ],
];
?>

<section style="padding-top:clamp(32px,4vw,56px);padding-bottom:clamp(48px,6vw,88px)">
    <div class="carto-wrap">

        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

        <header style="text-align:center;margin-bottom:48px">
            <div class="section__eyebrow" style="justify-content:center">Nos offres</div>
            <h1 class="section__title" style="margin-bottom:16px">
                Choisissez votre <span class="accent">formule</span>
            </h1>
            <p style="max-width:520px;margin:0 auto;color:var(--carto-text-lo);
                      font-size:var(--carto-fs-lead);line-height:1.55">
                Commencez gratuitement, passez à l'accès complet quand vous êtes prêt.
                Sans engagement.
            </p>
        </header>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
                    gap:20px;align-items:stretch;max-width:1000px;margin:0 auto">

            <?php foreach ( $offres as $offre ) :
                $is_feat = ! empty( $offre['featured'] );
                $border  = $is_feat ? '2px solid var(--carto-teal)' : '1px solid var(--carto-border)';
                $sous_c  = $offre['sous_color'] === 'teal' ? 'var(--carto-teal)' : 'var(--carto-muted)';
            ?>
            <div style="background:var(--carto-surface);border:<?php echo $border; ?>;
                        border-radius:12px;padding:32px 26px;display:flex;flex-direction:column;
                        position:relative">

                <?php if ( $is_feat && ! empty( $offre['badge'] ) ) : ?>
                    <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);
                                background:var(--carto-teal);color:var(--carto-bg);
                                font-family:var(--carto-font-mono);font-size:11px;font-weight:700;
                                letter-spacing:0.1em;text-transform:uppercase;padding:5px 16px;
                                border-radius:4px;white-space:nowrap">
                        <?php echo esc_html( $offre['badge'] ); ?>
                    </div>
                <?php endif; ?>

                <div style="font-family:var(--carto-font-mono);font-size:12px;letter-spacing:0.12em;
                            text-transform:uppercase;color:<?php echo $is_feat ? 'var(--carto-teal)' : 'var(--carto-text-lo)'; ?>;
                            margin-bottom:16px">
                    <?php echo esc_html( $offre['nom'] ); ?>
                </div>

                <div style="display:flex;align-items:baseline;gap:5px;margin-bottom:4px">
                    <span style="font-family:var(--carto-font-display);font-size:36px;font-weight:700;
                                 color:var(--carto-white)"><?php echo esc_html( $offre['prix'] ); ?></span>
                    <?php if ( $offre['periode'] ) : ?>
                        <span style="font-size:15px;color:var(--carto-text-lo)"><?php echo esc_html( $offre['periode'] ); ?></span>
                    <?php endif; ?>
                </div>

                <div style="font-size:13px;color:<?php echo $sous_c; ?>;margin-bottom:20px;min-height:18px">
                    <?php echo esc_html( $offre['sous'] ); ?>
                </div>

                <div style="border-top:1px solid var(--carto-border);padding-top:18px;
                            display:flex;flex-direction:column;gap:11px;flex:1">
                    <?php foreach ( $offre['features'] as $feat ) : ?>
                        <div style="font-size:14px;color:var(--carto-text);display:flex;gap:9px;line-height:1.4">
                            <span style="color:var(--carto-teal);flex-shrink:0">✓</span>
                            <span><?php echo esc_html( $feat ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="<?php echo esc_url( $offre['cta_url'] ); ?>"
                   style="margin-top:22px;text-align:center;padding:13px;border-radius:4px;
                          font-family:var(--carto-font-mono);font-size:13px;font-weight:700;
                          letter-spacing:0.06em;text-transform:uppercase;transition:all .2s;
                          <?php echo $is_feat
                              ? 'background:var(--carto-teal);color:var(--carto-bg)'
                              : 'border:1px solid var(--carto-teal);color:var(--carto-teal)'; ?>">
                    <?php echo esc_html( $offre['cta_label'] ); ?>
                </a>

            </div>
            <?php endforeach; ?>

        </div>

        <p style="text-align:center;margin-top:40px;color:var(--carto-muted);font-size:13px">
            Paiement sécurisé · Résiliable à tout moment · Facture disponible
        </p>

    </div>
</section>

<?php get_footer(); ?>
