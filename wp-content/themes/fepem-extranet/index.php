<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage extranet
 * @since Extranet 1.0
 */


get_header();
?>
<div class="site-content">
    <section class="fullwidth">
        <div class="wrapper">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
        </div>
    </section>
</div>
<?php

get_footer();

