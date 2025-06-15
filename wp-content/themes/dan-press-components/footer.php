<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Dan Press
 */
?>
</div><!-- #content -->

<footer id="colophon" class="site-footer">
    <div class="container site-footer-inner">

        <div class="footer-widgets">
            <!-- This is a great spot for social media links or a small menu -->
            <ul class="social-links">
                <li><a href="https://github.com/DanStill10" target="_blank" rel="noopener noreferrer">GitHub</a></li>
                <li><a href="#" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                <li><a href="#" target="_blank" rel="noopener noreferrer">Contact</a></li>
            </ul>
        </div>

        <div class="site-info">
            <!-- Dynamically displays the current year and your site name -->
            &copy; <?php echo date( 'Y' ); ?> Stillbuilt. All Rights Reserved.
        </div><!-- .site-info -->

    </div><!-- .site-footer-inner -->
</footer><!-- #colophon -->

<?php wp_footer();?>
</body>
</html>
