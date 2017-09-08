<?php
/**
 * Plugin Name: Extranet Widget List Project Members
 * Description: Widget permettant d'afficher La liste des membres du projet
 * Version: 1.0
 * Auhtor: Gaëlle Rauffet
 */

class Extranet_List_Project_Members_Widget extends WP_Widget {
    /**
    * widget constructor
    *
    * Register the widget in Wordpress
    */
    public function __construct(){
        parent::__construct(
                'extranetcp_widget_list_project_members',
                'Extranet Widget List Project Members',
                array(
                    'classname'   => 'ecp_widget_list_project_members',
                    'description' => "Widget affichant les membres du projet"
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
        $list_cpt_messagerie=get_cpt_messagerie();
        $list_cpt_message=get_cpt_message();
        $list_cpt_ged=get_cpt_ged();
        $list_cpt_document=get_cpt_document();
        $list_cpt_calendrier=get_cpt_calendrier();
        $list_cpt_event=get_cpt_event();
        $list_cpt_instances=get_cpt_instances();

        extract( $args );

        $list_members=[];
        $composant=get_queried_object();
        if( is_singular( $list_cpt_messagerie ) ) {
            $queried_instance = get_parent_instance_of_messagerie($composant->ID);
        } elseif( is_singular( $list_cpt_message ) ) {
            $queried_instance = get_parent_instance_of_message($composant->ID);
        } elseif( is_singular( $list_cpt_ged ) ) {
            $queried_instance = get_parent_instance_of_ged($composant->ID);
        } elseif( is_singular( $list_cpt_document ) ) {
            $queried_instance = get_parent_instance_of_doc($composant->ID);
        } elseif( is_singular( $list_cpt_calendrier ) ) {
            $queried_instance = get_parent_instance_of_calendar($composant->ID);
        } elseif( is_singular( $list_cpt_event ) ) {
            $queried_instance = get_parent_instance_of_event($composant->ID);
        } elseif( is_singular( $list_cpt_instances ) ) {
            $queried_instance = $composant;
        }
        
        //si on est dans une instance et que l'utilisateur y a accès => récupération des membres de l'instance
        if(!empty($queried_instance) && check_user_access_instance($queried_instance->ID, get_current_user_id())) {
            $list_members = get_post_meta($queried_instance->ID,'_meta_members_commission',false);
        }
        
        $title = apply_filters( 'ecp_sidebar_widget_title', $instance['title'] );

        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        //affichage des événements
        if(!empty( $list_members )) {
            ?>
            <ul class="ecp_list">
                <?php
                foreach($list_members as $id_member ) {
                    $user=get_user_by('ID',$id_member);
                    ?>
                    <li>
                        <?php echo $user->first_name.' '.$user->last_name; ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
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
            "title" => "Participants à cette instance"
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

        return $instance;
    }
}

/* Register the widget */
add_action( 'widgets_init', function() {
    register_widget( 'Extranet_List_Project_Members_Widget' );
});





