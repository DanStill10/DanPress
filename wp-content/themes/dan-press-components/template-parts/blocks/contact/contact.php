<?php
/**
 * Block: Contact
 *
 * Heading, intro, and an embedded WPForms form. Hardcoded id="contact" —
 * anchor support is disabled for this block (see functions.php) so this
 * can't collide with a user-set anchor — is what the header button
 * scrolls to on the homepage.
 *
 * @package Dan Press
 */

$heading   = get_field( 'contact_heading' ) ?: 'Let\'s Build Something [accent]Great[/accent]';
$subtitle  = get_field( 'contact_subtitle' ) ?: 'Have a project in mind? Send us a message and we\'ll get back to you shortly.';
$shortcode = trim( (string) get_field( 'contact_form_shortcode' ) );

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);
?>

<section id="contact" <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-contact dsd-block dsd-block--secondary' ) ); ?>>
    <div class="container dsd-contact-inner">
        <div class="dsd-contact-header">
            <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>

            <?php if ( $subtitle ) : ?>
                <p class="dsd-contact-intro"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>

        <div class="dsd-contact-form">
            <?php if ( $shortcode && shortcode_exists( 'wpforms' ) ) : ?>
                <?php echo do_shortcode( $shortcode ); ?>
            <?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
                <p class="dsd-contact-form__notice">
                    <?php esc_html_e( 'Paste a WPForms embed shortcode (e.g. [wpforms id="12"]) into this block\'s settings to display the form here.', 'dan-press' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>
