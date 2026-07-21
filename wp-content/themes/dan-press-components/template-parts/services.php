<?php
/**
 * Template Part: Services / Solutions Grid
 *
 * Displays a grid of services offered.
 * Content managed via ACF repeater field on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'have_rows' ) ) {
    return;
}
?>

<section class="dsd-services" id="services">
    <div class="container">

        <h2 class="dsd-section-heading">Solutions We <span class="dsd-accent-word">Provide</span></h2>

        <?php if ( have_rows( 'services' ) ) : ?>
            <div class="dsd-services-grid">
                <?php while ( have_rows( 'services' ) ) : the_row();

                    $icon        = get_sub_field( 'service_icon' );
                    $title       = get_sub_field( 'service_title' );
                    $description = get_sub_field( 'service_description' );
                    $link        = get_sub_field( 'service_link' );
                    $category    = get_sub_field( 'service_category' ) ?: 'OUR SOLUTIONS';

                    if ( ! $icon ) {
                        $icon = 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5';
                    }

                    $tag = $link ? 'a' : 'div';
                    $href_attrs = $link ? sprintf( ' href="%s" target="_blank" rel="noopener noreferrer"', esc_url( $link ) ) : '';
                ?>
                    <<?php echo $tag; ?> class="dsd-services-grid__card"<?php echo $href_attrs; ?>>
                        <span class="dsd-services-grid__category"><?php echo esc_html( $category ); ?></span>

                        <h3 class="dsd-services-grid__title"><?php echo esc_html( $title ); ?></h3>

                        <p class="dsd-services-grid__desc"><?php echo esc_html( $description ); ?></p>

                        <div class="dsd-services-grid__icon">
                            <svg viewBox="0 0 24 24">
                                <path d="<?php echo esc_attr( $icon ); ?>"></path>
                            </svg>
                        </div>
                    </<?php echo $tag; ?>>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
