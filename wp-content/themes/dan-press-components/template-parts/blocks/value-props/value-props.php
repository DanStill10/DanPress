<?php
/**
 * Block: Value Propositions (3-Column)
 *
 * Displays key value props in equal columns with glassmorphism design.
 * Content managed via ACF.
 *
 * @package Dan Press
 */

if ( ! have_rows( 'value_props' ) ) {
    return;
}

$section_title = get_field( 'value_props_title' ) ?: ( get_field( 'section_title' ) ?: 'Why Choose Us' );
$section_intro = get_field( 'value_props_intro' ) ?: get_field( 'section_intro' );
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-value-props dsd-block' ) ); ?>>
    <div class="container">

        <header class="dsd-value-props-header">
            <h2 class="dsd-section-heading"><?php echo esc_html( $section_title ); ?></h2>
            <?php if ( $section_intro ) : ?>
                <p class="dsd-value-props-intro"><?php echo esc_html( $section_intro ); ?></p>
            <?php endif; ?>
        </header>

        <div class="dsd-value-props-grid">
            <?php
            while ( have_rows( 'value_props' ) ) : the_row();
                $title       = get_sub_field( 'value_prop_title' );
                $subtitle    = get_sub_field( 'value_prop_subtitle' ) ?: get_sub_field( 'value_proposition_subtitle' );
                $description = get_sub_field( 'value_prop_description' );
            ?>
                <div class="dsd-value-prop-card">
                    <div class="dsd-value-prop-card__body">
                        <h3 class="dsd-value-prop-card__title"><?php echo esc_html( $title ); ?></h3>
                        <?php if ( $subtitle ) : ?>
                            <span class="dsd-value-prop-card__badge"><?php echo esc_html( $subtitle ); ?></span>
                        <?php endif; ?>
                        <?php if ( $description ) : ?>
                            <p class="dsd-value-prop-card__desc"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endwhile;
            ?>
        </div>
    </div>
</section>

