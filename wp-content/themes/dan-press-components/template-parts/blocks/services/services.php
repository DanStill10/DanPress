<?php
/**
 * Block: Services Grid
 *
 * Responsive card grid with icon, title, description, and link.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'dsd_render_sv_card' ) ) {
    function dsd_render_sv_card( $card, $category ) {
        $icon        = $card['sv_icon'] ?? null;
        $title       = $card['sv_title'] ?? '';
        $description = $card['sv_description'] ?? '';
        $link        = $card['sv_link'] ?? '';

        if ( ! $title ) {
            return '';
        }

        $output = '<div class="dsd-sv-card">';
        $output .= '<div class="dsd-sv-card-inner">';

        if ( $category ) {
            $output .= '<span class="dsd-sv-card-label">' . esc_html( $category ) . '</span>';
        }

        $output .= '<h3 class="dsd-sv-card-title">' . esc_html( $title ) . '</h3>';

        if ( $description ) {
            $output .= '<div class="dsd-sv-card-desc">' . wpautop( esc_html( $description ) ) . '</div>';
        }

        if ( $icon ) {
            $img_url = esc_url( $icon['url'] ?? '' );
            $img_alt = esc_attr( $title );
            $output .= '<div class="dsd-sv-card-icon">';
            if ( $link ) {
                $output .= '<a href="' . esc_url( $link ) . '" aria-label="' . $img_alt . '">';
            }
            $output .= '<img class="dsd-sv-icon-img" src="' . $img_url . '" alt="' . $img_alt . '" loading="lazy">';
            if ( $link ) {
                $output .= '</a>';
            }
            $output .= '</div>';
        }

        $output .= '</div></div>';

        return $output;
    }
}

$heading    = get_field( 'sv_heading' ) ?: 'Solutions We [accent]Provide[/accent]';
$category   = get_field( 'sv_category' ) ?: '';
$cards      = get_field( 'sv_cards' );
$scheme     = get_field( 'color_scheme' ) ?: 'default';
$custom_bg  = get_field( 'custom_bg_color' ) ?: '';
$custom_text = get_field( 'custom_text_color' ) ?: '';

if ( empty( $cards ) ) {
    return;
}

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);

$classes = array( 'dsd-sv', 'dsd-block' );
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

        <div class="dsd-sv-grid">
            <?php foreach ( $cards as $card ) : ?>
                <?php echo dsd_render_sv_card( $card, $category ); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
