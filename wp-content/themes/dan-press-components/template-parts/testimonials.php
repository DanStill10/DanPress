<?php
/**
 * Template Part: Testimonials / Ratings
 *
 * Client testimonials with rating cards.
 * Content managed via ACF repeater field on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'have_rows' ) ) {
    return;
}

$btn_text = get_field( 'testimonials_btn_text' );
$btn_url  = get_field( 'testimonials_btn_url' );
?>

<section class="dsd-testimonials" id="testimonials">
    <div class="container">

        <div class="dsd-testimonials-header">
            <h2 class="dsd-section-heading">What Our <span class="dsd-accent-word">Clients Say</span></h2>

            <?php if ( $btn_text ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="dsd-btn dsd-btn--ghost">
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
                        <div class="dsd-testimonial-card__rating">
                            <span class="dsd-testimonial-card__number"><?php echo esc_html( number_format( (float) $rating, 1 ) ); ?></span>
                            <div class="dsd-testimonial-card__stars">
                                <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                                    <span class="dsd-star <?php echo $s <= round( (float) $rating ) ? 'dsd-star--filled' : ''; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <span class="dsd-testimonial-card__platform"><?php echo esc_html( $platform ); ?></span>

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
