<?php
/**
 * Block: Call to Action
 *
 * Centered CTA section with heading, subtitle, and button.
 * Content managed via ACF.
 *
 * @package Dan Press
 */

$heading  = get_field( 'cta_heading' ) ?: 'Get In Touch';
$subtitle = get_field( 'cta_subtitle' ) ?: 'Have a project in mind? Let\'s talk about it.';
$btn_text = get_field( 'cta_btn_text' );
$btn_url  = get_field( 'cta_btn_url' ) ?: '#';

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-cta dsd-block' ) ); ?>>
    <div class="container">
        <div class="dsd-cta-inner">
            <h2 class="dsd-cta-heading"><?php echo $heading_html; ?></h2>

            <?php if ( $subtitle ) : ?>
                <p class="dsd-cta-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>

            <?php if ( $btn_text ) : ?>
                <a <?php echo dsd_block_href( $btn_url, $is_preview ); ?> class="dsd-btn dsd-btn--primary">
                    <?php echo esc_html( $btn_text ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
