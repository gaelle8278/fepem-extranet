<?php
/* 
 * Template file for single object
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//si on affiche un post
if( is_single() ) {
    //fetch displayed CPT
    $post= get_queried_object();

    //get parent instance of CPT
    $instance="";
    $page_active="";
    
    if ( is_singular( $list_cpt_instances ) ) {
        $instance = $post;
        $page_active="tdb";
    } elseif ( is_singular( $list_cpt_event ) ) {
        //fetch parent instance
        $instance = get_parent_instance_of_event($post->ID);
        $page_active="calendrier";
    } elseif ( is_singular( $list_cpt_message ) ) {
        $instance = get_parent_instance_of_message($post->ID);
        $page_active="messages";
    }
    elseif ( is_singular( $list_cpt_document ) ) {
        $instance = get_parent_instance_of_doc($post->ID);
        $page_active="ged";
    }
    elseif ( is_singular( $list_cpt_calendrier ) ) {
        $instance = get_parent_instance_of_calendar($post->ID);
        $page_active="calendrier";

        //get view calendar parameter
        global $wp_query;
        $vue = $wp_query->query_vars['vue'];
        if(empty($vue)) {
            $vue="agenda";
        }
    }
    elseif ( is_singular( $list_cpt_messagerie ) ) {
        $instance = get_parent_instance_of_messagerie($post->ID);
        $page_active="messages";
    }
    elseif ( is_singular( $list_cpt_ged ) ) {
        $instance = get_parent_instance_of_ged($post->ID);
        $page_active="ged";
    }

    //if user can access instance
    if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
        get_header();
        ?>
        <div class="site-content">
            <section>
                <div class="wrapper">
                    <h1><?php echo $instance->post_title; ?></h1>
                    <?php
                        display_extranet_menu($instance->ID,$page_active);

                        //content
                        if ( is_singular( $list_cpt_instances ) ) {
                            $all_composants = getcomposants_of_instances([$instance->ID]);
                            include( locate_template( 'template-parts/content-commission.php' ) );
                        }
                        elseif ( is_singular( $list_cpt_event ) ) {
                            include( locate_template( 'template-parts/content-event.php' ) );
                        }
                        elseif ( is_singular( $list_cpt_message ) ) {
                            include( locate_template( 'template-parts/content-message.php') );
                        }
                        elseif ( is_singular( $list_cpt_document ) ) {
                            include( locate_template( 'template-parts/content-document.php') );
                        }
                        elseif ( is_singular( $list_cpt_calendrier ) ) {
                            display_views_menu_calendar($post->ID, $vue);
                            if($vue=="list") {
                                $all_events = getevents_of_calendar($post->ID);
                                include( locate_template( 'template-parts/content-calendrier-list.php') );
                            } elseif ($vue=="agenda" ) {
                                include( locate_template( 'template-parts/content-calendrier-agenda.php') );
                            }
                        }
                        elseif ( is_singular( $list_cpt_messagerie ) ){
                            $all_messages = getmessages_of_messagerie($post->ID);
                            include( locate_template( 'template-parts/content-messagerie.php') );
                        }
                        elseif ( is_singular( $list_cpt_ged ) ){
                            $all_documents = getdocuments_of_ged($post->ID);
                            include( locate_template( 'template-parts/content-ged.php') );
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
} else {
    get_template_part("template-parts/content-default");
}