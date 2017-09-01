<?php
/*
 * Content of template for CPT document
 */

while (have_posts()) : the_post();
    ?>
    <h2><?php the_title(); ?></h2>
    <p>
        Déposé le <?php the_time('j F Y'); ?> à <?php the_time('H\hi'); ?> par <?php the_author_meta('first_name');?> <?php the_author_meta('last_name'); ?>
    </p>
    <p>
        <a href="<?php  echo get_post_meta(get_the_ID(), '_ecp_document_file_attached', true); ?>" >Voir le document</a>
    </p>
    <?php
endwhile;