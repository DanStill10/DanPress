<?php
/**
 * Block: Value Propositions (3-Column)
 *
 * Displays key value props in equal columns.
 * Content managed via ACF.
 *
 * @package Dan Press
 */

if ( ! have_rows( 'value_props' ) ) {
    return;
}
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-value-props dsd-block' ) ); ?>>
    <div class="container">
        <div class="dsd-value-props-grid">
            <?php
            $count = 1;
            while ( have_rows( 'value_props' ) ) : the_row();
                $title       = get_sub_field( 'value_prop_title' );
                $subtitle    = get_sub_field( 'value_prop_subtitle' );
                $description = get_sub_field( 'value_prop_description' );
                $index       = str_pad( $count, 2, '0', STR_PAD_LEFT );
            ?>
                <div class="dsd-value-prop-card">
                    <div class="dsd-value-prop-card__header">
                        <span class="dsd-value-prop-card__number">// <?php echo esc_html( $index ); ?></span>
                    </div>
                    <div class="dsd-value-prop-card__body">
                        <h3 class="dsd-value-prop-card__title"><?php echo esc_html( $title ); ?></h3>
                        <?php if ( $subtitle ) : ?>
                            <span class="dsd-value-prop-card__subtitle"><?php echo esc_html( $subtitle ); ?></span>
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <p class="dsd-value-prop-card__desc"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
                $count++;
            endwhile;
            ?>
        </div>
    </div>
</section>
