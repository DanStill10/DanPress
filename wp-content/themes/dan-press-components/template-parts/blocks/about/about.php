<?php
$heading   = get_field( 'ab_heading' ) ?: 'Why [accent]Partner With Us[/accent]';
$content   = get_field( 'ab_content' );
$link_url  = get_field( 'ab_portfolio_link' ) ?: '#';
$link_text = get_field( 'ab_portfolio_link_text' ) ?: 'Browse Our Portfolio';
$images    = get_field( 'ab_gallery' );
$stats     = array();

for ( $i = 1; $i <= 3; $i++ ) {
    $num   = get_field( "ab_stat_{$i}_num" );
    $label = get_field( "ab_stat_{$i}_label" );
    if ( $num && $label ) {
        $stats[] = array( 'num' => $num, 'label' => $label );
    }
}

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-ab dsd-block' ) ); ?>>
    <div class="container">
        <div class="dsd-ab__inner<?php echo empty( $images ) ? ' dsd-ab__inner--no-gallery' : ''; ?>">

            <div class="dsd-ab__content">
                <div class="dsd-ab__top-row">
                    <div class="dsd-ab__cta-col">
                        <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>
                        <a href="<?php echo esc_url( $link_url ); ?>" class="dsd-ab__portfolio-link"><?php echo esc_html( $link_text ); ?></a>
                    </div>
                    <?php if ( $content ) : ?>
                        <div class="dsd-ab__text-col">
                            <div class="dsd-ab__text"><?php echo wp_kses_post( $content ); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $stats ) ) : ?>
                    <div class="dsd-ab__stats">
                        <?php foreach ( $stats as $stat ) : ?>
                            <div class="dsd-ab__stat">
                                <span class="dsd-ab__stat-num"><?php echo esc_html( $stat['num'] ); ?></span>
                                <span class="dsd-ab__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $images ) ) : ?>
            <div class="dsd-ab__gallery">
                <div class="dsd-ab-carousel" role="region" aria-label="Project showcase">
                    <div class="dsd-ab-carousel__track">
                        <?php $first = true; ?>
                        <?php foreach ( $images as $image ) : ?>
                            <div class="dsd-ab-carousel__slide<?php echo $first ? ' is-active' : ''; ?>">
                                <?php echo wp_get_attachment_image( $image['id'], 'large', false, array( 'class' => 'dsd-ab-carousel__img', 'alt' => esc_attr( $image['alt'] ) ) ); ?>
                            </div>
                            <?php $first = false; ?>
                        <?php endforeach; ?>
                    </div>

                    <button class="dsd-ab-carousel__btn dsd-ab-carousel__btn--prev" aria-label="Previous image">
                        <svg width="24" height="24" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                            <circle cx="24" cy="24" r="23" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M28 16l-8 8 8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <button class="dsd-ab-carousel__btn dsd-ab-carousel__btn--next" aria-label="Next image">
                        <svg width="24" height="24" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                            <circle cx="24" cy="24" r="23" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M20 16l8 8-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="dsd-ab-carousel__dots" role="tablist" aria-label="Slide navigation">
                        <?php foreach ( $images as $i => $image ) : ?>
                            <button class="dsd-ab-carousel__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" role="tab" aria-label="Slide <?php echo $i + 1; ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" data-index="<?php echo $i; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
