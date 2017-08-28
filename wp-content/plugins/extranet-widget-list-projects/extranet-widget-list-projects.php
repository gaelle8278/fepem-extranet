<?php
/**
 * Plugin Name: Extranet Widget List Projects
 * Description: Widget permettant d'afficher la liste des projets auxquels est rattaché l'utilisateur
 * Version: 1.0
 * Auhtor: Gaëlle Rauffet
 */

class Extranet_List_Projects_Widget extends WP_Widget {
    /**
    * widget constructor
    *
    * Register the widget in Wordpress
    */
    public function __construct(){
        parent::__construct(
                'extranetcp_widget_list_projects',
                'Extranet Widget List Projects',
                array(
                    'classname'   => 'ecp_widget_list_projects',
                    'description' => "Widget listant les projets dont l'utilisteur est membre"
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
        //instances de l'utilisateur
        $instances = get_instances_of_user( get_current_user_id() );

        extract( $args );

        $title = apply_filters( 'ecp_sidebar_widget_title', $instance['title'] );


        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        if(!empty($instances)) {
            ?>
            <ul class="ecp-list">
                <?php
                foreach($instances as $instance) {
                    ?>
                    <li><a href="<?php echo get_permalink($instance->ID); ?>"><?php echo $instance->post_title; ?></a></li>
                    <?php
                }
                ?>
            </ul>
            <?php
        } else {
            ?>
            <p>Vous n'êtes rattaché à aucun projet</p>
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
            "title" => "Vos instances",
        );
        $datas=wp_parse_args($instance,$default);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titre</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $datas['title']; ?>" />
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
    register_widget( 'Extranet_List_Projects_Widget' );
});

