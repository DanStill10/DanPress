<?php
/**
 * Template Part: Client Logos Marquee
 *
 * Displays a scrolling row of client/partner logos in a CSS-only infinite marquee.
 * Content managed via ACF repeater field on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'have_rows' ) ) {
    return;
}

if ( ! have_rows( 'client_logos' ) ) {
    return;
}
?>

<section class="dsd-client-logos" id="clients">
    <div class="container">
        <h2 class="dsd-section-heading">Our <span class="dsd-accent-word">Clients</span></h2>
    </div>

    <div class="dsd-client-logos__track-wrap">
        <div class="dsd-client-logos__track dsd-client-logos__track--scroll">
            <?php
            // Render logos twice for seamless loop
            for ( $i = 0; $i < 2; $i++ ) :
                while ( have_rows( 'client_logos' ) ) :
                    the_row();
                    $image  = get_sub_field( 'logo_image' );
                    $name   = get_sub_field( 'client_name' );
                    $url    = get_sub_field( 'client_url' );

                    if ( ! $image ) {
                        continue;
                    }

                    $img_url  = esc_url( $image['url'] );
                    $img_alt  = esc_attr( $image['alt'] ? $image['alt'] : $name );
                    $img_width  = isset( $image['width'] ) ? intval( $image['width'] ) : 200;
                    $img_height = isset( $image['height'] ) ? intval( $image['height'] ) : 80;
                    ?>
                    <div class="dsd-client-logos__item">
                        <?php if ( $url ) : ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="dsd-client-logos__link">
                        <?php endif; ?>

                            <img
                                src="<?php echo $img_url; ?>"
                                alt="<?php echo $img_alt; ?>"
                                width="<?php echo $img_width; ?>"
                                height="<?php echo $img_height; ?>"
                                loading="lazy"
                                class="dsd-client-logos__img"
                            >

                        <?php if ( $url ) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile;
            endfor;
            ?>
        </div>
    </div>
</section>
