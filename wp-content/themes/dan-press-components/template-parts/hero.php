<?php
/**
 * Template Part: Hero Section
 *
 * Full-viewport hero with animated cycling headline, CTA buttons,
 * optional video background, and dark overlay.
 *
 * @package Dan Press
 */

$hero_words      = get_theme_mod( 'hero_words', 'Solve, Build, Ship' );
$hero_subtitle   = get_theme_mod( 'hero_subtitle', 'From complex problems to elegant solutions.' );
$cta_primary     = get_theme_mod( 'hero_cta_primary_text', '' );
$cta_primary_url = get_theme_mod( 'hero_cta_primary_url', '#' );
$cta_secondary     = get_theme_mod( 'hero_cta_secondary_text', '' );
$cta_secondary_url = get_theme_mod( 'hero_cta_secondary_url', '#' );
$hero_bg_video  = get_theme_mod( 'hero_bg_video', '' );
$hero_bg_image  = get_theme_mod( 'hero_bg_image', '' );
$hero_accent    = get_theme_mod( 'hero_accent_color', '' );

$words_array = array_map( 'trim', explode( ',', $hero_words ) );

$style = '';
if ( $hero_accent ) {
    $style = sprintf( 'style="--dsd-hero-accent: %s;"', esc_attr( $hero_accent ) );
}
?>

<section class="dsd-hero" data-words="<?php echo esc_attr( $hero_words ); ?>" <?php echo $style; ?>>
    <?php if ( $hero_bg_video || $hero_bg_image ) : ?>
        <div class="dsd-hero-media">
            <?php if ( $hero_bg_video ) : ?>
                <video
                    class="dsd-hero-video"
                    muted
                    loop
                    playsinline
                    preload="none"
                    data-hero-video
                    <?php echo $hero_bg_image ? 'poster="' . esc_url( $hero_bg_image ) . '"' : ''; ?>
                >
                    <source src="<?php echo esc_url( $hero_bg_video ); ?>" type="video/mp4">
                </video>
            <?php elseif ( $hero_bg_image ) : ?>
                <div class="dsd-hero-image" style="background-image: url('<?php echo esc_url( $hero_bg_image ); ?>');"></div>
            <?php endif; ?>
            <div class="dsd-hero-overlay"></div>
        </div>
    <?php endif; ?>

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
