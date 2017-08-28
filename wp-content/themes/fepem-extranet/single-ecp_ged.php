<?php
/**
 * Template des CPT ecp_ged
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//fetch object
$ged= get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_ged($ged->ID);

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

                $all_documents = getdocuments_of_ged($ged->ID);
                if( !empty( $all_documents ) ) {
                    display_view_documents($all_documents);
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
