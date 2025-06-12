<?php
/**
 * Project Showcase Block Template.
 *
 * @param   array $attributes - A clean array of block attributes.
 * @param   string $content - The HTML content that was saved in the editor.
 * @param   WP_Block $block - The full block instance.
 * @package Dan Press
 */

$project_title = isset( $attributes['projectTitle'] ) ? $attributes['projectTitle'] : 'Default Title';
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
    <section class="project-showcase">
        <h2><?php echo esc_html( $project_title ); ?></h2>
        <p>More project details will go here!</p>
    </section>
</div>