<?php
/**
 * Template Part: About / Why Partner With Us
 *
 * Two-column layout: company description + staggered portfolio image carousels.
 * Content managed via ACF fields on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'get_field' ) ) {
    return;
}

$heading       = get_field( 'about_heading' ) ?: 'Why Partner With Us';
$btn_text      = get_field( 'about_btn_text' );
$btn_url       = get_field( 'about_btn_url' );
$subtitle      = get_field( 'about_subtitle' );
$description   = get_field( 'about_description' );
$images        = get_field( 'about_portfolio_images' );

// Split heading to accent the last word
$heading_words = explode( ' ', $heading );
$last_word     = array_pop( $heading_words );
$prefix        = implode( ' ', $heading_words );
?>

<section class="dsd-about" id="about">
    <div class="container dsd-about-inner">

        <div class="dsd-about-content">
            <h2 class="dsd-section-heading">
                <?php echo esc_html( $prefix ); ?>
                <span class="dsd-accent-word"><?php echo esc_html( $last_word ); ?></span>
            </h2>

            <?php if ( $btn_text ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="dsd-btn dsd-btn--ghost dsd-about-btn">
                    <?php echo esc_html( $btn_text ); ?>
                </a>
            <?php endif; ?>

            <?php if ( $subtitle ) : ?>
                <h3 class="dsd-about-subtitle"><?php echo esc_html( $subtitle ); ?></h3>
            <?php endif; ?>

            <?php if ( $description ) : ?>
                <div class="dsd-about-desc">
                    <?php echo wp_kses_post( $description ); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( $images && count( $images ) > 0 ) : ?>
            <div class="dsd-about-portfolio">
                <div class="dsd-about-portfolio__track-wrap dsd-about-portfolio__track-wrap--1">
                    <div class="dsd-about-portfolio__track dsd-about-portfolio__track--scroll">
                        <?php
                        // Render images twice for seamless loop
                        for ( $i = 0; $i < 2; $i++ ) :
                            foreach ( $images as $image ) :
                                $img_url = esc_url( $image['url'] );
                                $img_alt = esc_attr( $image['alt'] ? $image['alt'] : 'Portfolio screenshot' );
                        ?>
                                <div class="dsd-about-portfolio__item">
                                    <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                                </div>
                        <?php
                            endforeach;
                        endfor;
                        ?>
                    </div>
                </div>

                <div class="dsd-about-portfolio__track-wrap dsd-about-portfolio__track-wrap--2">
                    <div class="dsd-about-portfolio__track dsd-about-portfolio__track--scroll dsd-about-portfolio__track--reverse">
                        <?php
                        for ( $i = 0; $i < 2; $i++ ) :
                            foreach ( $images as $image ) :
                                $img_url = esc_url( $image['url'] );
                                $img_alt = esc_attr( $image['alt'] ? $image['alt'] : 'Portfolio screenshot' );
                        ?>
                                <div class="dsd-about-portfolio__item">
                                    <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                                </div>
                        <?php
                            endforeach;
                        endfor;
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
