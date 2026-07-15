<?php
/**
 * CARTO Theme — template-parts/components/post-navigation.php
 * Navigation précédent / suivant pour les articles.
 *
 * Usage : get_template_part( 'template-parts/components/post-navigation' );
 */

$prev = get_previous_post();
$next = get_next_post();

if ( ! $prev && ! $next ) return;
?>

<nav class="post-navigation" aria-label="<?php esc_attr_e( 'Navigation entre articles', 'carto' ); ?>"
     style="margin-top:64px;display:grid;grid-template-columns:1fr 1fr;gap:24px;padding-top:40px;border-top:1px solid var(--carto-border)">

    <?php if ( $prev ) : ?>
        <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>"
           class="card card--accent"
           style="text-decoration:none"
           rel="prev">
            <div class="card__label">← <?php _e( 'Article précédent', 'carto' ); ?></div>
            <div class="card__title" style="font-size:16px">
                <?php echo esc_html( get_the_title( $prev ) ); ?>
            </div>
            <?php
            $prev_cats = get_the_category( $prev->ID );
            if ( $prev_cats ) {
                echo '<div class="card__footer"><span class="badge badge--muted">' . esc_html( $prev_cats[0]->name ) . '</span></div>';
            }
            ?>
        </a>
    <?php else : ?>
        <div></div>
    <?php endif; ?>

    <?php if ( $next ) : ?>
        <a href="<?php echo esc_url( get_permalink( $next ) ); ?>"
           class="card card--accent"
           style="text-align:right;text-decoration:none"
           rel="next">
            <div class="card__label"><?php _e( 'Article suivant', 'carto' ); ?> →</div>
            <div class="card__title" style="font-size:16px">
                <?php echo esc_html( get_the_title( $next ) ); ?>
            </div>
            <?php
            $next_cats = get_the_category( $next->ID );
            if ( $next_cats ) {
                echo '<div class="card__footer" style="justify-content:flex-end"><span class="badge badge--muted">' . esc_html( $next_cats[0]->name ) . '</span></div>';
            }
            ?>
        </a>
    <?php endif; ?>

</nav>
