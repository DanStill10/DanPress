<?php
/**
 * Template Part: CTA / Request a Quote
 *
 * Call-to-action section with heading, description, and button.
 * Content managed via Customizer fields (similar to hero pattern).
 *
 * @package Dan Press
 */

// TODO: Build in feature/cta-section branch

$cta_heading    = get_theme_mod( 'cta_heading', 'Get In Touch' );
$cta_subtitle   = get_theme_mod( 'cta_subtitle', 'Have a project in mind? Let\'s talk about it.' );
$cta_btn_text   = get_theme_mod( 'cta_btn_text', '' );
$cta_btn_url    = get_theme_mod( 'cta_btn_url', '#' );
?>

<section class="dsd-cta" id="contact">
    <div class="container dsd-cta-inner">
        <h2 class="dsd-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>

        <?php if ( $cta_subtitle ) : ?>
            <p class="dsd-cta-subtitle"><?php echo esc_html( $cta_subtitle ); ?></p>
        <?php endif; ?>

        <?php if ( $cta_btn_text ) : ?>
            <a href="<?php echo esc_url( $cta_btn_url ); ?>" class="dsd-btn dsd-btn--primary">
                <?php echo esc_html( $cta_btn_text ); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
