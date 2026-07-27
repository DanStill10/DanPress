<?php
/**
 * Block: Client Logos
 *
 * Infinite-scroll marquee. Fields managed via ACF.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'dsd_render_cl_logo' ) ) {
    function dsd_render_cl_logo( $logo ) {
        $image = $logo['cl_logo_image'] ?? null;
        $link  = $logo['cl_logo_link'] ?? '';
        $alt   = $logo['cl_logo_alt'] ?? '';

        if ( ! $image ) {
            return '';
        }

        $img_url = esc_url( $image['url'] ?? '' );
        $img_alt = esc_attr( $alt ?: ( $image['alt'] ?? '' ) );
        $width   = esc_attr( $image['sizes']['thumbnail-width'] ?? 150 );
        $height  = esc_attr( $image['sizes']['thumbnail-height'] ?? 150 );

        $output = '<figure class="dsd-cl-item">';
        if ( $link ) {
            $output .= '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener noreferrer">';
        }
        $output .= '<img class="dsd-cl-img" src="' . $img_url . '" alt="' . $img_alt . '" width="' . $width . '" height="' . $height . '" loading="lazy">';
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

$mid     = (int) ceil( count( $logos ) / 2 );
$row_one = array_slice( $logos, 0, $mid );
$row_two = array_slice( $logos, $mid );

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);

$classes = array( 'dsd-cl', 'dsd-block' );

$is_centered = count( $logos ) <= 6;
if ( $is_centered ) {
    $classes[] = 'dsd-cl--centered';
}

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class' => implode( ' ', $classes ),
) );
?>

<section <?php echo $wrapper_attrs; ?>>
    <div class="container">
        <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>
    </div>

    <div class="dsd-cl-marquee">
        <div class="dsd-cl-track dsd-cl-track--left" aria-label="Client logos">
            <div class="dsd-cl-slide">
                <?php foreach ( $row_one as $logo ) : ?>
                    <?php echo dsd_render_cl_logo( $logo ); ?>
                <?php endforeach; ?>
            </div>
            <div class="dsd-cl-slide" aria-hidden="true">
                <?php foreach ( $row_one as $logo ) : ?>
                    <?php echo dsd_render_cl_logo( $logo ); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if ( ! empty( $row_two ) ) : ?>
        <div class="dsd-cl-marquee">
            <div class="dsd-cl-track dsd-cl-track--right" aria-label="Client logos">
                <div class="dsd-cl-slide">
                    <?php foreach ( $row_two as $logo ) : ?>
                        <?php echo dsd_render_cl_logo( $logo ); ?>
                    <?php endforeach; ?>
                </div>
                <div class="dsd-cl-slide" aria-hidden="true">
                    <?php foreach ( $row_two as $logo ) : ?>
                        <?php echo dsd_render_cl_logo( $logo ); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
