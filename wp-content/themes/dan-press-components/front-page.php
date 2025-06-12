<?php
// -----------------------------------------------------------------------------
// Template: front-page.php
// -----------------------------------------------------------------------------
// This is the most specific template file for the site's front page.
// If it exists, WordPress will use it instead of page.php or index.php.
// It's often highly customized.

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php
    // The main WordPress Loop
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();

            // You would typically put custom layout code here instead of just the_content().
            // For example, you might call custom blocks or specific template parts.
            the_content();

        endwhile;
    else :
        // Content to display if no content is found.
        echo '<p>No content found.</p>';
    endif;
    ?>

</main>

<?php get_footer(); ?>