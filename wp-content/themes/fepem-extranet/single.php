<?php
/* 
 * Template file for single object
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

$list_cpt=get_cpt();
//si on affiche un cpt
if( is_singular( $list_cpt ) ) {
    //fetch displayed CPT
    $post= get_queried_object();
    //connected user
    $id_user= get_current_user_id();
    //fetch cpt to test
    $list_cpt_instances= get_cpt_instances();
    $list_cpt_event=get_cpt_event();
    $list_cpt_message= get_cpt_message();
    $list_cpt_document=get_cpt_document();
    $list_cpt_calendrier=get_cpt_calendrier();
    $list_cpt_messagerie=get_cpt_messagerie();
    $list_cpt_ged=get_cpt_ged();

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
    if(!empty($instance) && check_user_access_instance($instance->ID, $id_user)) {
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
                            if( check_user_type_can_access_cpt($post->ID,$id_user) ) {
                                include( locate_template( 'template-parts/content-message.php') );
                            } else {
                                include( locate_template( 'template-parts/content-no-access-cpt.php') );
                            }
                        }
                        elseif ( is_singular( $list_cpt_document ) ) {
                            if( check_user_type_can_access_cpt($post->ID,$id_user) ) {
                                include( locate_template( 'template-parts/content-document.php') );
                            } else {
                                include( locate_template( 'template-parts/content-no-access-cpt.php') );
                            }
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
                        elseif ( is_singular( $list_cpt_messagerie ) ) {
                            if( check_user_type_can_access_cpt($post->ID,$id_user) ) {
                                $all_messages = getmessages_of_messagerie($post->ID);
                                include( locate_template( 'template-parts/content-messagerie.php') );
                            } else {
                                include( locate_template( 'template-parts/content-no-access-cpt.php') );
                            }
                        }
                        elseif ( is_singular( $list_cpt_ged ) ) {
                            if( check_user_type_can_access_cpt($post->ID,$id_user) ) {
                                $all_documents = getdocuments_of_ged($post->ID);
                                include( locate_template( 'template-parts/content-ged.php') );
                            } else {
                                include( locate_template( 'template-parts/content-no-access-cpt.php') );
                            }
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