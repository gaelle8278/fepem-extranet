<?php
/* 
 * Default content
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
