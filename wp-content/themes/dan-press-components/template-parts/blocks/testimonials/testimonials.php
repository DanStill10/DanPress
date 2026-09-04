<?php
/**
 * Block: Testimonials
 *
 * Client testimonials with rating cards.
 * Content managed via ACF.
 *
 * @package Dan Press
 */

$btn_text = get_field( 'testimonials_btn_text' );
$btn_url  = get_field( 'testimonials_btn_url' );
$heading  = get_field( 'testimonials_heading' ) ?: 'What Our [accent]Clients Say[/accent]';

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-testimonials dsd-block' ) ); ?>>
    <div class="container">

        <div class="dsd-testimonials-header">
            <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>

            <?php if ( $btn_text ) : ?>
                <a <?php echo dsd_block_href( $btn_url, $is_preview ); ?> class="dsd-btn dsd-btn--ghost">
                    <?php echo esc_html( $btn_text ); ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if ( have_rows( 'testimonials_list' ) ) : ?>
            <div class="dsd-testimonials-grid">
                <?php while ( have_rows( 'testimonials_list' ) ) : the_row();

                    $rating    = get_sub_field( 'testimonial_rating' );
                    $platform  = get_sub_field( 'testimonial_platform' );
                    $quote     = get_sub_field( 'testimonial_quote' );
                    $author    = get_sub_field( 'testimonial_author' );
                    $role      = get_sub_field( 'testimonial_role' );
                ?>
                    <div class="dsd-testimonial-card">
                        <?php if ( ! empty( $rating ) && (float) $rating > 0 ) : ?>
                            <div class="dsd-testimonial-card__rating">
                                <span class="dsd-testimonial-card__number"><?php echo esc_html( number_format( (float) $rating, 1 ) ); ?></span>
                                <div class="dsd-testimonial-stars" aria-label="Rating: <?php echo esc_attr( $rating ); ?> out of 5 stars">
                                    <div class="dsd-stars-empty">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                    <div class="dsd-stars-filled" style="width: <?php echo ( ( (float) $rating ) / 5 ) * 100; ?>%;">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ( $platform ) : ?>
                            <span class="dsd-testimonial-card__platform"><?php echo esc_html( $platform ); ?></span>
                        <?php endif; ?>

                        <?php if ( $quote ) : ?>
                            <blockquote class="dsd-testimonial-card__quote">
                                &ldquo;<?php echo esc_html( $quote ); ?>&rdquo;
                            </blockquote>
                        <?php endif; ?>

                        <?php if ( $author ) : ?>
                            <div class="dsd-testimonial-card__author">
                                <strong><?php echo esc_html( $author ); ?></strong>
                                <?php if ( $role ) : ?>
                                    <span><?php echo esc_html( $role ); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
