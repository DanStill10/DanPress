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
        $title = $card['sv_title'] ?? '';
        if ( ! $title ) {
            return '';
        }

        $link = $card['sv_link'] ?? '#';
        $bg   = $card['sv_bg_image']['url'] ?? '';
        $desc = $card['sv_description'] ?? '';

        $bg_style = $bg ? ' style="background-image: url(\'' . esc_url( $bg ) . '\')"' : '';
        $card_classes = 'dsd-sv-card';
        if ( ! $bg ) {
            $card_classes .= ' dsd-sv-card--no-image';
        }

        $output  = '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $card_classes ) . '" aria-label="' . esc_attr( $title ) . '">';
        $output .= '<div class="dsd-sv-card-bg"' . $bg_style . '></div>';
        $output .= '<div class="dsd-sv-card-overlay"></div>';

        $output .= '<div class="dsd-sv-card-content">';
        if ( $category ) {
            $output .= '<span class="dsd-sv-card-label">' . esc_html( $category ) . '</span>';
        }
        $output .= '<h3 class="dsd-sv-card-title">' . esc_html( $title ) . '</h3>';
        if ( $desc ) {
            $output .= '<div class="dsd-sv-card-desc">' . wp_kses_post( $desc ) . '</div>';
        }
        $output .= '</div>';

        $output .= '<div class="dsd-sv-card-footer">';
        if ( $btn_text ) {
            $output .= '<span class="dsd-sv-card-btn">' . esc_html( $btn_text ) . '</span>';
        }
        $output .= '</div>';

        $output .= '</a>';

        return $output;
    }
}

$heading  = get_field( 'sv_heading' ) ?: 'Solutions We [accent]Provide[/accent]';
$category = get_field( 'sv_category' ) ?: '';
$btn_text = get_field( 'sv_btn_text' ) ?: 'Learn More';
$cards    = get_field( 'sv_cards' );

if ( ! is_array( $cards ) || empty( $cards ) ) {
    return;
}

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-sv dsd-block' ) ); ?>>
    <div class="container">
        <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>

        <div class="dsd-sv-grid">
            <?php foreach ( $cards as $card ) : ?>
                <?php echo dsd_render_sv_card( $card, $category, $btn_text ); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
