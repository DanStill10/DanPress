<?php
/**
 * Template Part: Value Propositions (3-Column)
 *
 * Displays key value props in equal columns.
 * Content managed via ACF repeater field on the front page.
 *
 * @package Dan Press
 */

if ( ! function_exists( 'have_rows' ) ) {
    return;
}

if ( ! have_rows( 'value_props' ) ) {
    return;
}
?>

<section class="dsd-value-props" id="value-props">
    <div class="container">
        <div class="dsd-value-props-grid">
            <?php while ( have_rows( 'value_props' ) ) : the_row();

                $title       = get_sub_field( 'value_prop_title' );
                $subtitle    = get_sub_field( 'value_prop_subtitle' );
                $description = get_sub_field( 'value_prop_description' );
            ?>
                <div class="dsd-value-prop">
                    <h3 class="dsd-value-prop__title"><?php echo esc_html( $title ); ?></h3>

                    <?php if ( $subtitle ) : ?>
                        <p class="dsd-value-prop__subtitle"><?php echo esc_html( $subtitle ); ?></p>
                    <?php endif; ?>

                    <?php if ( $description ) : ?>
                        <p class="dsd-value-prop__desc"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
