<?php
/**
 * Block: Services Grid
 *
 * Image-background cards with gradient overlay, pill CTA buttons.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'dsd_render_sv_card' ) ) {
    function dsd_render_sv_card( $card, $category, $btn_text ) {
        $title       = $card['sv_title'] ?? '';
        $description = $card['sv_description'] ?? '';
        $link        = $card['sv_link'] ?? '#';
        $bg_image    = $card['sv_bg_image'] ?? null;
        $icon        = $card['sv_icon'] ?? null;

        if ( ! $title ) {
            return '';
        }

        $bg_url = $bg_image ? esc_url( $bg_image['url'] ?? '' ) : '';

        $output = '<a href="' . esc_url( $link ) . '" class="dsd-sv-card" aria-label="' . esc_attr( $title ) . '">';

        // Background image.
        if ( $bg_url ) {
            $output .= '<div class="dsd-sv-card-bg" style="background-image: url(\'' . $bg_url . '\')"></div>';
        }

        // Gradient overlay.
        $output .= '<div class="dsd-sv-card-overlay"></div>';

        // Content.
        $output .= '<div class="dsd-sv-card-content">';

        if ( $category ) {
            $output .= '<span class="dsd-sv-card-label">' . esc_html( $category ) . '</span>';
        }

        $output .= '<h3 class="dsd-sv-card-title">' . esc_html( $title ) . '</h3>';

        if ( $description ) {
            $output .= '<div class="dsd-sv-card-desc">' . wpautop( esc_html( $description ) ) . '</div>';
        }

        $output .= '</div>';

        // Pill button.
        if ( $btn_text ) {
            $output .= '<span class="dsd-sv-card-btn">' . esc_html( $btn_text ) . '</span>';
        }

        $output .= '</a>';

        return $output;
    }
}

$heading     = get_field( 'sv_heading' ) ?: 'Solutions We [accent]Provide[/accent]';
$category    = get_field( 'sv_category' ) ?: '';
$btn_text    = get_field( 'sv_btn_text' ) ?: 'Learn More';
$cards       = get_field( 'sv_cards' );
$scheme      = get_field( 'color_scheme' ) ?: 'default';
$custom_bg   = get_field( 'custom_bg_color' ) ?: '';
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
                <?php echo dsd_render_sv_card( $card, $category, $btn_text ); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
