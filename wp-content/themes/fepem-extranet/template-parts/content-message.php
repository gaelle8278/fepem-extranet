<?php
/*
 * Content of template for CPT message
 */

while ( have_posts() ) : the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="entry-title">
                <?php the_title(); ?>
            </h1>

            <div class="entry-content">
                <div>De : <?php the_author(); ?></div>
                <div>Date : le <?php the_date(); ?>  à <?php the_time('H\hi'); ?></div>
                <div><?php the_content(); ?></div>
            </div>

    </article>
    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
	comments_template();
    endif;

endwhile;