<?php
/**
 * Block: Client Logos
 *
 * Infinite-scroll marquee. Fields managed via ACF.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'dsd_render_cl_logo' ) ) {
    function dsd_render_cl_logo( $logo, $is_preview ) {
        $img = $logo['cl_logo_image'] ?? null;
        if ( ! $img ) {
            return '';
        }

        $url  = esc_url( $img['url'] ?? '' );
        $alt  = esc_attr( $logo['cl_logo_alt'] ?? $img['alt'] ?? '' );
        $link = $logo['cl_logo_link'] ?? '';

        $width  = $img['width'] ?? '';
        $height = $img['height'] ?? '';

        $output = '<figure class="dsd-cl-item">';
        if ( $link ) {
            $output .= '<a ' . dsd_block_href( $link, $is_preview ) . ' target="_blank" rel="noopener noreferrer">';
        }
        $output .= '<img class="dsd-cl-img" src="' . $url . '" alt="' . $alt . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" loading="lazy">';
        if ( $link ) {
            $output .= '</a>';
        }
        $output .= '</figure>';

        return $output;
    }
}

$heading = get_field( 'cl_heading' ) ?: 'Our Clients';
$logos   = get_field( 'clients' );

if ( ! is_array( $logos ) || empty( $logos ) ) {
    return;
}

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);

$classes = array( 'dsd-cl', 'dsd-block' );
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => implode( ' ', $classes ) ) ); ?>>
    <div class="container">
        <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>
        <div class="dsd-cl-marquee">
            <div class="dsd-cl-track dsd-cl-track--left" aria-label="Client logos">
                <div class="dsd-cl-slide">
                    <?php foreach ( $logos as $logo ) : ?>
                        <?php echo dsd_render_cl_logo( $logo, $is_preview ); ?>
                    <?php endforeach; ?>
                </div>
                <div class="dsd-cl-slide" aria-hidden="true">
                    <?php foreach ( $logos as $logo ) : ?>
                        <?php echo dsd_render_cl_logo( $logo, $is_preview ); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
