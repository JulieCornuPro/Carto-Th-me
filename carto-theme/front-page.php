<?php
/**
 * CARTO Theme — front-page.php
 * Page d'accueil vitrine avec hero, KPI animés, features, graphes, CTA.
 */
get_header();
?>

<!-- ════════════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════════ -->
<section class="hero">
    <span class="hero__corner-bl" aria-hidden="true"></span>
    <span class="hero__corner-br" aria-hidden="true"></span>

    <div class="carto-wrap">

        <div class="hero__eyebrow">
            Certification ISO/IEC 27001
        </div>

        <h1 class="hero__title">
            Réussissez votre
            <br>
            <span class="accent">Lead Implementer</span>
        </h1>

        <div class="hero__divider" aria-hidden="true"></div>

        <p class="hero__lead">
            Des examens blancs réalistes, calqués sur le véritable examen, avec correction détaillée et suivi de votre progression domaine par domaine. Arrivez le jour J en confiance.
        </p>

        <div class="hero__actions">
            <a href="/inscription" class="btn btn--primary">
                Commencer gratuitement
            </a>
            <a href="/comment-ca-marche" class="btn btn--outline">
                Comment ça marche
            </a>
        </div>

        <!-- KPI animés sous le hero -->
        <div class="hero__kpi" aria-label="<?php esc_attr_e( 'Chiffres clés', 'carto' ); ?>">
            <div class="hero__kpi-item">
                <div id="carto-hero-kpi1"></div>
            </div>
            <div class="hero__kpi-item">
                <div id="carto-hero-kpi2"></div>
            </div>
            <div class="hero__kpi-item">
                <div id="carto-hero-kpi3"></div>
            </div>
        </div>

    </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     SECTION FEATURES
════════════════════════════════════════════════════════════ -->
<section class="section" id="features">
    <div class="carto-wrap">

        <div class="reveal-on-scroll">
            <div class="section__eyebrow">
                Votre entraînement
            </div>
            <h2 class="section__title">
                Un entraînement
                <span class="accent">réaliste</span>
            </h2>
            <div class="section__divider" aria-hidden="true"></div>
            <p class="section__lead">
                Chaque examen blanc reproduit les conditions réelles : mises en situation d'entreprise, questions à choix multiples exigeantes, et une correction qui explique chaque réponse.
            </p>
        </div>

        <!-- Feature row 1 : texte + bar chart -->
        <div class="feature-row reveal-on-scroll" style="margin-bottom:72px">
            <div class="feature-row__text">
                <div class="section__eyebrow">Suivi de progression</div>
                <h3 class="section__title" style="font-size:var(--carto-fs-h2)">
                    Progression par
                    <span class="accent">domaine</span>
                </h3>
                <p style="font-size:var(--carto-fs-body);color:var(--carto-text-lo);line-height:1.6;margin-bottom:28px">
                    Visualisez instantanément vos points forts et vos lacunes sur les sept domaines de l'examen Lead Implementer. Concentrez vos révisions là où ça compte vraiment.
                </p>
                <div class="carto-list">
                    <?php
                    $features = [
                        [ 'num' => '01', 'title' => 'Vos lacunes en évidence', 'desc' => 'Les domaines les plus fragiles remontent automatiquement, pour cibler vos révisions.' ],
                        [ 'num' => '02', 'title' => 'Score par domaine', 'desc' => 'Un pourcentage de maîtrise clair pour chacun des sept domaines.' ],
                        [ 'num' => '03', 'title' => 'Évolution dans le temps', 'desc' => 'Suivez vos progrès d\'un examen blanc à l\'autre.' ],
                    ];
                    foreach ( $features as $f ) : ?>
                        <div class="carto-list__item">
                            <span class="carto-list__num"><?php echo esc_html($f['num']); ?></span>
                            <div class="carto-list__content">
                                <h3><?php echo esc_html($f['title']); ?></h3>
                                <p><?php echo esc_html($f['desc']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="feature-row__visual">
                <div class="carto-stage">
                    <div class="carto-stage__grid"></div>
                    <div class="carto-stage__label">maîtrise par domaine (%)</div>
                    <div class="carto-stage__inner">
                        <div id="carto-barchart-main"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature row 2 : gauge + texte -->
        <div class="feature-row feature-row--reverse reveal-on-scroll">
            <div class="feature-row__text">
                <div class="section__eyebrow">Résultat final</div>
                <h3 class="section__title" style="font-size:var(--carto-fs-h2)">
                    Votre score
                    <span class="accent">blanc</span>
                </h3>
                <p style="font-size:var(--carto-fs-body);color:var(--carto-text-lo);line-height:1.6;margin-bottom:28px">
                    À la fin de chaque examen, un score global clair, calculé comme le vrai examen, avec le seuil de réussite. Vous savez exactement où vous en êtes avant de vous présenter.
                </p>
                <a href="/comment-ca-marche" class="btn btn--outline">
                    Voir un exemple d'examen →
                </a>
            </div>
            <div class="feature-row__visual">
                <div class="carto-stage">
                    <div class="carto-stage__grid"></div>
                    <div class="carto-stage__label">score simulé</div>
                    <div class="carto-stage__inner" style="display:flex;justify-content:center">
                        <div id="carto-gauge-main" style="width:300px"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     SECTION TENDANCES — Sparkline + Heatmap
════════════════════════════════════════════════════════════ -->
<section class="section section--alt">
    <div class="carto-wrap">

        <div class="reveal-on-scroll">
            <div class="section__eyebrow">Tableau de bord</div>
            <h2 class="section__title">
                Votre courbe de
                <span class="accent">progrès</span>
            </h2>
            <div class="section__divider" aria-hidden="true"></div>
        </div>

        <div class="grid-2 reveal-on-scroll">

            <!-- Sparkline -->
            <div>
                <div class="card__label" style="margin-bottom:16px">
                    Score moyen / examen blanc
                </div>
                <div class="carto-stage">
                    <div class="carto-stage__grid"></div>
                    <div class="carto-stage__inner">
                        <div id="carto-sparkline-main"></div>
                    </div>
                </div>
            </div>

            <!-- Heatmap -->
            <div>
                <div class="card__label" style="margin-bottom:16px">
                    Maîtrise par domaine et par thème
                </div>
                <div class="carto-stage">
                    <div class="carto-stage__grid"></div>
                    <div class="carto-stage__inner">
                        <div id="carto-heatmap-main"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     SECTION CTA FINAL
════════════════════════════════════════════════════════════ -->
<section class="section" id="contact">
    <div class="carto-wrap" style="text-align:center;max-width:720px;margin:0 auto">

        <div class="reveal-on-scroll">
            <div class="section__eyebrow" style="text-align:center">
                Prêt à vous lancer ?
            </div>
            <h2 class="section__title" style="text-align:center">
                Réussissez votre
                <span class="accent">certification</span>
            </h2>
            <div class="section__divider" aria-hidden="true" style="margin-left:auto;margin-right:auto"></div>
            <p class="section__lead" style="margin:0 auto 48px;text-align:center">
                Commencez gratuitement avec un premier examen blanc. Sans carte bancaire, sans engagement.
            </p>
            <div class="hero__actions" style="justify-content:center">
                <a href="/inscription" class="btn btn--primary">
                    Commencer gratuitement
                </a>
                <a href="/offres" class="btn btn--outline">
                    Découvrir les offres
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     DERNIERS ARTICLES (si le blog est actif)
════════════════════════════════════════════════════════════ -->
<?php
$latest_posts = new WP_Query( [
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'ignore_sticky_posts' => true,
] );

if ( $latest_posts->have_posts() ) : ?>

<section class="section section--dark">
    <div class="carto-wrap">

        <div class="section__eyebrow reveal-on-scroll">
            <?php _e( 'Ressources', 'carto' ); ?>
        </div>
        <h2 class="section__title reveal-on-scroll">
            <?php _e( 'Derniers', 'carto' ); ?>
            <span class="accent"><?php _e( 'articles', 'carto' ); ?></span>
        </h2>
        <div class="section__divider reveal-on-scroll" aria-hidden="true"></div>

        <div class="grid-3">
            <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                <article class="post-card reveal-on-scroll">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-card__thumb">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'carto-card', [ 'alt' => get_the_title() ] ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="post-card__meta">
                        <span><?php echo get_the_date( 'd M Y' ); ?></span>
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) {
                            echo '<span class="badge badge--teal">' . esc_html( $cats[0]->name ) . '</span>';
                        }
                        ?>
                    </div>
                    <h3 class="post-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-card__excerpt"><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="post-card__link">
                        <?php _e( 'Lire l\'article', 'carto' ); ?>
                    </a>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>

<?php endif; ?>

<!-- ════════════════════════════════════════════════════════════
     INIT COMPOSANTS ANIMÉS
════════════════════════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Carto === 'undefined') return;
    var T = Carto.colors.TEAL, O = Carto.colors.ORANGE, A = Carto.colors.AMBER;

    /* KPI hero */
    /* KPI hero */
    Carto.counter(document.getElementById('carto-hero-kpi1'), {
        value: 300, suffix: '+', label: 'Questions d\'entraînement', color: T
    });
    Carto.counter(document.getElementById('carto-hero-kpi2'), {
        value: 7, label: 'Domaines couverts', color: T
    });
    Carto.counter(document.getElementById('carto-hero-kpi3'), {
        value: 7, label: 'Scénarios d\'entreprise', color: O
    });

    /* Bar chart — maîtrise par domaine */
    Carto.barChart(document.getElementById('carto-barchart-main'), {
        data: [
            { label: 'D1', value: 65, color: T },
            { label: 'D2', value: 70, color: T },
            { label: 'D3', value: 55, color: A },
            { label: 'D4', value: 60, color: A },
            { label: 'D5', value: 50, color: O },
            { label: 'D6', value: 72, color: T },
            { label: 'D7', value: 58, color: A }
        ]
    });

    /* Gauge */
    Carto.gauge(document.getElementById('carto-gauge-main'), {
        value: 72, label: 'Score', suffix: '/100', color: T
    });

    /* Sparkline */
    Carto.sparkline(document.getElementById('carto-sparkline-main'), {
        points: [11, 18, 15, 26, 22, 33, 30, 40, 45, 57, 64, 72], color: T
    });

    /* Heatmap */
    Carto.heatmap(document.getElementById('carto-heatmap-main'), {
        yLabels:  ['Contexte', 'Risques', 'Support', 'Audit'],
        xLabels:  ['Clauses', 'Annexe A', 'Mesure', 'Amélior.', 'Revue'],
        values: [
            [0.8, 0.6, 0.7, 0.5, 0.6],
            [0.7, 0.5, 0.6, 0.4, 0.5],
            [0.9, 0.7, 0.8, 0.6, 0.7],
            [0.6, 0.4, 0.5, 0.7, 0.8]
        ]
    });
});
</script>

<?php get_footer(); ?>
