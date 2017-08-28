<?php
/**
 * Plugin Name: Extranet Widget Agenda
 * Description: Widget permettant d'afficher des événements de l'agenda
 * Version: 1.0
 * Auhtor: Gaëlle Rauffet
 */

class Extranet_Agenda_Widget extends WP_Widget {
    /**
    * widget constructor
    *
    * Register the widget in Wordpress
    */
    public function __construct(){
        parent::__construct(
                'extranetcp_widget_agenda',
                'Extranet Widget Agenda',
                array(
                    'classname'   => 'ecp_widget_agenda',
                    'description' => "Widget affichant les événements de l'agenda"
                )
        );
    }

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
        $user_instances = get_instances_of_user( get_current_user_id() );


        extract( $args );

        $list_events=[];
        if($instance['type_agenda'] == "all") {
            //affichage des événements de toutes les instances auxquelles appartient l'utilisateur
            $list_user_instances = get_instances_of_user( get_current_user_id() );
            if(!empty( $list_user_instances )) {
                $list_calendars=[];
                foreach( $list_user_instances as $user_instance ) {
                    $list_calendars[]= get_post_meta($user_instance->ID, '_meta_calendar_commission',true);
                }
                $list_events = getevents_of_list_calendar( $list_calendars, $instance['nb_event']);
            }
        } elseif ( $instance['type_agenda'] == "project" ) {
            //affichage des événements de l'instance en cours d'affichage
            $composant=get_queried_object();
            if( $composant->post_type == "ecp_messagerie" ) {
                $queried_instance = get_parent_instance_of_messagerie($composant->ID);
            } elseif( $composant->post_type == "ecp_message" ) {
                $queried_instance = get_parent_instance_of_message($composant->ID);
            } elseif( $composant->post_type == "ecp_ged" ) {
                $queried_instance = get_parent_instance_of_ged($composant->ID);
            } elseif( $composant->post_type == "ecp_document" ) {
                $queried_instance = get_parent_instance_of_doc($composant->ID);
            } elseif( $composant->post_type == "ecp_calendrier" ) {
                $queried_instance = get_parent_instance_of_calendar($composant->ID);
            } elseif( $composant->post_type == "ecp_event" ) {
                $queried_instance = get_parent_instance_of_event($composant->ID);
            } elseif( $composant->post_type == "commission" ) {
                $queried_instance = $composant;
            }

            //si on est dans une instance et que l'utilisateur y a accès => récupération des événements du calendrier de l'instance
            if(!empty($queried_instance) && check_user_access_instance($queried_instance->ID, get_current_user_id())) {
                $list_calendars = get_post_meta($queried_instance->ID, '_meta_calendar_commission');
                $list_events = getevents_of_list_calendar( $list_calendars, $instance['nb_event']);
            }
        }

        $title = apply_filters( 'ecp_sidebar_widget_title', $instance['title'] );

        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        //affichage des événements
        if(!empty( $list_events )) {
            foreach($list_events as $event ) {
                $sd=get_post_meta($event->ID,'_ecp_event_startdate', true);
                $eventplace=get_post_meta($event->ID,'_ecp_event_place', true);
                $eventdate= date_i18n("j F Y", $sd);
                $eventst= date_i18n("H\hi", $sd);
                ?>
                <p>
                    <?php
                        echo $eventdate;
                        if( $eventst != "00h00" ) {
                            echo " à ".$eventst;
                        }
                        if( !empty($eventplace) ) {
                            echo " - ".$eventplace;
                        }
                    ?>
                </p>
                <p><?php echo $event->post_title; ?></p>
                <?php
            }
        }

        echo $after_widget;

    }

    /**
     * creates the back-end form
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
     public function form( $instance ) {
        $default = array (
            "title" => "Agenda",
            "nb_event" => "3",
            "type_agenda" => "project"
        );
        $datas=wp_parse_args($instance,$default);

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titre</label>
            <input class="widefat" 
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text"
                   value="<?php echo $datas['title']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("nb_event"); ?>">Nombre d'événements à afficher</label>
            <input class="widefat"
                        name="<?php echo $this->get_field_name("nb_event"); ?> "
                        type="text"
                        id="<?php echo $this->get_field_id("nb_event"); ?>"
                        value="<?php echo $datas['nb_event']; ?>" />
        </p>
        <p> 
            <label>Type d'agenda</label>
            <label>Projet
                <input class="widefat"
                        name="<?php echo $this->get_field_name("type_agenda"); ?>"
                        type="radio"
                        value="project" 
                        <?php echo $datas['type_agenda']=="project"?"checked":""; ?>/>
            </label>
            <label>Global
                <input class="widefat"
                        name="<?php echo $this->get_field_name("type_agenda"); ?>"
                        type="radio"
                        value="all" 
                        <?php echo $datas['type_agenda']=="all"?"checked":""; ?>/>
            </label>
        </p>

        <?php
     }

    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $nbevent= intval($new_instance['nb_event']);
        $instance['nb_event'] = empty($nbevent) ? "3" : $nbevent;

        $typeagenda=strip_tags( $new_instance['type_agenda'] );
        $instance['type_agenda'] = empty($typeagenda) ? "project" : $typeagenda;
        
        return $instance;
    }
}

/* Register the widget */
add_action( 'widgets_init', function() {
    register_widget( 'Extranet_Agenda_Widget' );
});



