<?php
/**
 * Template Part: FAQ Accordion
 *
 * Frequently asked questions in collapsible accordion sections.
 * Pure HTML/CSS/JS — no plugin dependencies.
 * Content managed via ACF repeater field on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'have_rows' ) ) {
    return;
}

if ( ! have_rows( 'faq_items' ) ) {
    return;
}

// Group FAQs by category
$grouped = array();
while ( have_rows( 'faq_items' ) ) : the_row();
    $question  = get_sub_field( 'faq_question' );
    $answer    = get_sub_field( 'faq_answer' );
    $category  = get_sub_field( 'faq_category' ) ?: 'General';

    if ( ! $question || ! $answer ) {
        continue;
    }

    if ( ! isset( $grouped[ $category ] ) ) {
        $grouped[ $category ] = array();
    }

    $grouped[ $category ][] = array(
        'question' => $question,
        'answer'   => $answer,
    );
endwhile;
?>

<section class="dsd-faqs" id="faqs">
    <div class="container">

        <h2 class="dsd-section-heading"><span class="dsd-accent-word">FAQs</span></h2>

        <?php if ( ! empty( $grouped ) ) : ?>
            <div class="dsd-faqs-grid">
                <?php foreach ( $grouped as $category => $items ) : ?>
                    <div class="dsd-faq-group">
                        <h3 class="dsd-faq-group__title"><?php echo esc_html( $category ); ?></h3>

                        <div class="dsd-faq-accordion" data-accordion>
                            <?php foreach ( $items as $index => $item ) :
                                $id = 'faq-' . sanitize_title( $category ) . '-' . $index;
                            ?>
                                <div class="dsd-faq-item" data-accordion-item>
                                    <button
                                        class="dsd-faq-item__trigger"
                                        aria-expanded="false"
                                        aria-controls="<?php echo esc_attr( $id ); ?>"
                                        data-accordion-trigger
                                    >
                                        <span><?php echo esc_html( $item['question'] ); ?></span>
                                        <span class="dsd-faq-item__icon" aria-hidden="true">+</span>
                                    </button>

                                    <div
                                        class="dsd-faq-item__content"
                                        id="<?php echo esc_attr( $id ); ?>"
                                        role="region"
                                        data-accordion-content
                                    >
                                        <div class="dsd-faq-item__answer">
                                            <?php echo wp_kses_post( $item['answer'] ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
