<?php
/**
/* Page template des CPT messages
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//fetch instance
$message = get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_message($message->ID);

$page_active="messages";

//check if user can access instance
if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
    get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$page_active);
                ?>
                <div>
                    <?php
                    while ( have_posts() ) : the_post();
			get_template_part('template-parts/content-message');

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

                    endwhile;
                    ?>
                </div>
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
