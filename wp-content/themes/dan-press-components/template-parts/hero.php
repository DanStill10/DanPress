<?php
/**
 * Template Part: Hero Section
 *
 * Full-viewport hero with animated cycling headline and CTA buttons.
 * Content is driven by Customizer fields registered in functions.php.
 *
 * @package Dan Press
 */

$hero_words      = get_theme_mod( 'hero_words', 'Solve, Build, Ship' );
$hero_subtitle   = get_theme_mod( 'hero_subtitle', 'From complex problems to elegant solutions.' );
$cta_primary     = get_theme_mod( 'hero_cta_primary_text', '' );
$cta_primary_url = get_theme_mod( 'hero_cta_primary_url', '#' );
$cta_secondary     = get_theme_mod( 'hero_cta_secondary_text', '' );
$cta_secondary_url = get_theme_mod( 'hero_cta_secondary_url', '#' );

$words_array = array_map( 'trim', explode( ',', $hero_words ) );
?>

<section class="dsd-hero" data-words="<?php echo esc_attr( $hero_words ); ?>">
    <div class="container dsd-hero-inner">

        <h1 class="dsd-hero-headline">
            <span class="dsd-hero-word dsd-hero-word--active"><?php echo esc_html( $words_array[0] ); ?></span><span class="dsd-hero-cursor">_</span>
        </h1>

        <?php if ( $hero_subtitle ) : ?>
            <p class="dsd-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
        <?php endif; ?>

        <div class="dsd-hero-actions">
            <?php if ( $cta_primary ) : ?>
                <a href="<?php echo esc_url( $cta_primary_url ); ?>" class="dsd-btn dsd-btn--primary">
                    <?php echo esc_html( $cta_primary ); ?>
                </a>
            <?php endif; ?>

            <?php if ( $cta_secondary ) : ?>
                <a href="<?php echo esc_url( $cta_secondary_url ); ?>" class="dsd-btn dsd-btn--ghost">
                    <?php echo esc_html( $cta_secondary ); ?>
                </a>
            <?php endif; ?>
        </div>

    </div>
</section>
