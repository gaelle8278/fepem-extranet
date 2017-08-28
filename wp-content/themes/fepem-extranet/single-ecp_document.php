<?php
/**
 * Template des CPT ecp_document
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//fetch object
$doc= get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_doc($doc->ID);

$page_active="ged";

if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
    get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$page_active);

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
                ?>
            </div>
        </section><!--@whitespace
        --><aside>
            <?php get_sidebar(); ?>
        </aside>
    </div>
    <?php
    get_footer();

} else {
    wp_redirect(home_url('page-non-accessible'));
    exit;
}


