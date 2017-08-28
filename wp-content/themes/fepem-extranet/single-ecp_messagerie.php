<?php
/**
 * Template des CPT ecp_messagerie
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//fetch instance
$messagerie= get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_messagerie($messagerie->ID);

$page_active="messages";

if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
    get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$page_active);

                $all_messages = getmessages_of_messagerie($messagerie->ID);
                if( !empty( $all_messages) ) {
                    display_view_message($all_messages);
                }
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