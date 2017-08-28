<?php
/* 
 * Template des CPT ecp_calendrier
 */


//check if user can access Extranet
redirect_user_if_no_access_extranet();

//get view calendar parameter
global $wp_query;
$vue = $wp_query->query_vars['vue'];
if(empty($vue)) {
    $vue="agenda";
}

//fetch instance
$calendrier= get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_calendar($calendrier->ID);

$page_active="calendrier";

if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
 get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$page_active);
                display_views_menu_calendar($calendrier->ID, $vue)
                ?>
                <div>
                    <?php
                    if($vue=="list") {
                        $all_events = getevents_of_calendar($calendrier->ID);
                        
                        if(!empty($all_events)) {
                            display_view_list_events($all_events);
                        }
                    } elseif( $vue=="agenda" ) {
                    ?>
                        <div id='ecp-calendrier'>
                        </div>

                        <script>
                            jQuery(document).ready( function($) {

                                $('#ecp-calendrier').fullCalendar({
                                    header: {
                                        left: 'prev today next',
                                        center: 'title',
                                        right: 'month agendaWeek agendaDay'
                                    },
                                    timezone: 'local',
                                    locale: 'fr',
                                    timeFormat: 'H:mm',
                                    displayEventEnd : true,
                                    events: {
                                        url: '/wp-content/themes/fepem-extranet/inc/feed-calendar-events.php',
                                        type: 'POST',
                                        data: {
                                            instance_id: <?php echo $instance->ID ?>
                                        },
                                        error: function() {
                                            alert('Il ya eu une erreur dans la récupération des événements.');
                                        },
                                        allDayDefault: false
                                    },
                                    eventRender: function(event, element, view) {
                                        if(view.name == 'month') {
                                            element.find('.fc-time').after('<br/>');
                                        }
                                    }

                                });
                            });
                        </script>
                    <?php
                    }
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