<?php
/**
 * Block: Client Logos
 *
 * Infinite-scroll marquee. Fields managed via ACF.
 *
 * @package Dan Press
 */

$heading = get_field( 'cl_heading' ) ?: 'Our Clients';
$logos   = get_field( 'cl_logos' );

if ( empty( $logos ) ) {
    return;
}

// Split logos into two rows.
$mid     = (int) ceil( count( $logos ) / 2 );
$row_one = array_slice( $logos, 0, $mid );
$row_two = array_slice( $logos, $mid );

// Parse [accent]word[/accent] tags.
$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);

// Color scheme.
$scheme   = get_field( 'color_scheme' ) ?: 'default';
$custom_bg = get_field( 'custom_bg_color' ) ?: '';
$custom_text = get_field( 'custom_text_color' ) ?: '';

$classes = array( 'dsd-cl', 'dsd-block' );
if ( $scheme !== 'default' ) {
    $classes[] = 'dsd-block--' . $scheme;
}
if ( $custom_bg ) {
    $classes[] = 'dsd-block--custom-bg';
}

$style = '';
if ( $custom_bg ) {
    $style .= '--dsd-section-bg: ' . esc_attr( $custom_bg ) . ';';
}
if ( $custom_text ) {
    $style .= '--dsd-section-text: ' . esc_attr( $custom_text ) . ';';
}

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class' => implode( ' ', $classes ),
    'style' => $style,
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

<?php
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
