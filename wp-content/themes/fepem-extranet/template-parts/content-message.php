<?php
/**
 * Template part pour afficher un message
 *
 *
 * @package fepem-extranet
 */

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
