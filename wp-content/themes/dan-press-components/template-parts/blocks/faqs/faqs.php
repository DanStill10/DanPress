<?php
/**
 * Block: FAQ Accordion
 *
 * Expandable FAQ pairs grouped by category.
 * Content managed via ACF.
 *
 * @package Dan Press
 */

$faq_items = get_field( 'faq_items' );

if ( ! is_array( $faq_items ) || empty( $faq_items ) ) {
    return;
}

// Group items by category.
$grouped = array();
foreach ( $faq_items as $item ) {
    $cat = $item['faq_category'] ?: 'General';
    $grouped[ $cat ][] = $item;
}
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-faqs dsd-block' ) ); ?>>
    <div class="container">
        <div class="dsd-faqs-grid">
            <?php foreach ( $grouped as $category => $items ) : ?>
                <div class="dsd-faq-group">
                    <h3 class="dsd-faq-group__title"><?php echo esc_html( $category ); ?></h3>

                    <?php foreach ( $items as $item ) : ?>
                        <div class="dsd-faq-item">
                            <button
                                class="dsd-faq-item__trigger"
                                data-accordion-trigger
                                aria-expanded="false"
                                aria-controls="faq-<?php echo esc_attr( sanitize_title( $item['faq_question'] ) ); ?>"
                            >
                                <span><?php echo esc_html( $item['faq_question'] ); ?></span>
                                <span class="dsd-faq-item__icon" aria-hidden="true">+</span>
                            </button>

                            <div
                                class="dsd-faq-item__content"
                                id="faq-<?php echo esc_attr( sanitize_title( $item['faq_question'] ) ); ?>"
                                role="region"
                            >
                                <div class="dsd-faq-item__answer">
                                    <?php echo wp_kses_post( $item['faq_answer'] ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
