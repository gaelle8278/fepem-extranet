<?php
/* 
 * File to manage CPT Calendrier et CPT Event
 */

/********************************************
 * Définition du CPT Event
 *
 *******************************************/
/**
 * Fonction qui gère l'affichage de la metabox listant les calendriers disponibles
 *
 */
function build_metabox_event_calendar( $post ) {

    $selected_cal= wp_get_post_parent_id($post->ID);

    $calendar_object="";
    if( "ecp_event" == get_post_type($post) ) {
        $calendar_object = "ecp_calendrier";
    } elseif ( "ecp_fevent" == get_post_type($post) ) {
        $calendar_object = "ecp_fcalendrier";
    }

    $all_calendar=[];
    if( !empty( $calendar_object ) ) {
        $all_calendar = get_posts( array(
            'post_type' => $calendar_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ) );
    }
    ?>

    <div class="metabox-calendar-event">
        <div>
            <input type="hidden" name="event_calendar_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
            <select name="ecpcalendrier" id="ecpcalendrier">
                <option value="0">-- Sélectionner un calendrier --</option>
                <?php
                foreach ( $all_calendar as $cal ) {
                    $instance= get_parent_instance_of_calendar($cal->ID );
                    $id_instance="0";
                    if($instance != "") {
                        $id_instance = $instance->ID;
                    }
                    ?>
                    <option data-instance="<?php echo $id_instance; ?>" value="<?php echo $cal->ID; ?>"<?php echo  $cal->ID == $selected_cal  ? ' selected="selected"' : ''; ?>><?php echo $cal->post_title; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
         <?php $ajax_nonce = wp_create_nonce( 'nonce_change_select' ); ?>
        <script type="text/javascript">// <![CDATA[
            jQuery(function($) {
                $('#ecpcalendrier').change(function(){
                    var selected=$(this).find('option:selected');
                    var instanceid = selected.data('instance');

                    if(instanceid != "0") {
                        var data = {
                            action: 'update_members_notification',
                            id_instance: instanceid,
                            security: '<?php echo $ajax_nonce; ?>'

                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        $.post( ajaxurl, data, function( data )  {
                            //console.log(data);
                            $('#list-members-notif').empty();
                            var element="";
                            if(data.length == 0) {
                                element="<p>L'instance ne contient aucun membre</p>";
                            } else {
                                $.each(data, function(i, obj) {
                                    element += '<p><label><input type="checkbox" name="id_members_notif[]" value="'+obj.id+'">'+obj.prenom+' '+obj.nom+'</label></p>';
                                });
                            }
                            $('#list-members-notif').append(element);

                        }, "json");
                    } else {
                        //$('#list-members-notif').empty();
                        $('#list-members-notif').empty().append("<p>Le calendrier n'est lié à aucune instance</p>");
                    }
                });
            });
            // ]]>
        </script>
    </div>
    <?php
}

/**
 * Fonction pour sauvegarder le calendrier auquel est lié l'événement
 *
 * @param type $post_id
 * @return void
 */
function save_metabox_event_calendar( $post_id ) {

    // only run this for event
    $authorized_post_type=["ecp_event","ecp_fevent"];
    if ( !in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // verify nonce
    if ( empty( $_POST['event_calendar_nonce'] ) || !wp_verify_nonce( $_POST['event_calendar_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    if(isset($_POST['ecpcalendrier']) && $_POST['ecpcalendrier'] != "0") {
        // to avoid infinite loop
        remove_action('save_post', 'save_metabox_event_calendar', 10);
        //to avoid duplicate send notification
        remove_action('save_post', 'composant_published_notification',12);
        //to avoid to save event meta data twice
        remove_action('save_post', 'save_metabox_event_data');

        // call wp_update_post update to save value, which calls save_post again
        wp_update_post(
            array(
                'ID' => $post_id,
                'post_parent' => $_POST['ecpcalendrier']
            )
        );

        // re-hook the save_post action
        add_action('save_post', 'save_metabox_event_calendar',10);
        remove_action('save_post', 'composant_published_notification',12);
        //re-hook save of event metat data
        add_action('save_post', 'save_metabox_event_data');
    }

}
add_action( 'save_post', 'save_metabox_event_calendar', 10 );

/**
 * Fonction pour sélectionner les membres à  notifier pour un événement
 *
 *
 */
function build_metabox_notification_event( $post ) {
    //récupération de l'instance à  laquelle est lié le message
    $instance="";
    ?>

    <p>Liste des membres à notifier</p>
    <div class="ecp-event-notification" >
        <div id="list-members-notif">
            <?php
            if( empty($post->post_parent) ) {
                ?>
                <p>L'événement n'est lié à aucun calendrier</p>
                <?php
            } else {
                $instance = get_parent_instance_of_calendar($post->post_parent);
                $calendar = get_post($post->post_parent);
                if( $instance != "" ) {
                    //get users of instance
                    $members_commission = get_post_meta($instance->ID,'_meta_members_commission',false);
                    if(!empty($members_commission)) {
                        foreach($members_commission as $id_member) {
                            $member=get_user_by('ID',$id_member);
                            ?>
                            <p>
                                <label>
                                    <input type="checkbox" name="id_members_notif[]" value="<?php echo $member->ID; ?>"><?php echo $member->first_name." ".$member->last_name; ?>
                                </label>
                            </p>
                            <?php
                        }
                    } else {
                        ?>
                        <p>L'instance ne contient aucun membre</p>
                        <?php
                    }
                } else {
                    ?>
                        <p>Le calendrier <?php echo $calendar->post_title; ?> n'est lié à aucune instance</p>
                    <?php
                }
            }
        ?>

        </div>
    </div>
    <?php
}

/**
 * Fonction qui gère l'affichage de la metabox permettant de définir
 * les données du CPT Evénement
 *
 */
function build_metabox_event_data( $post ) {
    //global $post;

    // current cpt data
    $meta_sd = get_post_meta($post->ID, '_ecp_event_startdate', true);
    $meta_ed = get_post_meta($post->ID, '_ecp_event_enddate', true);
    $meta_st = $meta_sd;
    $meta_et = $meta_ed;
        // populate today if empty, 00:00 for time
    if ($meta_sd == null) {
        $meta_sd = time();
        $meta_ed = $meta_sd;
        $meta_st = 0;
        $meta_et = 0;
    }
    //lieu
    $meta_place = get_post_meta($post->ID, '_ecp_event_place', true);

    // convert to pretty format
    //$date_format = get_option('date_format'); // Not use
    $date_format = "j F Y";
    //$time_format = get_option('time_format');
    $time_format = "H\hi";
    $clean_sd = date_i18n($date_format, $meta_sd);
    $clean_ed = date_i18n($date_format, $meta_ed);
    $clean_st = date_i18n($time_format, $meta_st);
    $clean_et = date_i18n($time_format, $meta_et);


    //form
    ?>
    <div class="ecp-event-data">
        <input type="hidden" name="ecp-event-data-nonce" id="ecp-event-data-nonce" value="<?php echo wp_create_nonce( 'ecp-event-data-nonce' ); ?>" />
        <ul>
            <li><label>Lieu</label><input name="ecp_event_place" value="<?php echo $meta_place; ?>" /></li>
            <li><label>Date de début</label><input name="ecp_event_startdate" class="ecpdate" value="<?php echo $clean_sd; ?>" readonly='true' /></li>
            <li><label>Date de fin</label><input name="ecp_event_starttime" value="<?php echo $clean_st; ?>" /><em>24h format (ex 19h00)</em></li>
            <li><label>Heure de début</label><input name="ecp_event_enddate" class="ecpdate" value="<?php echo $clean_ed; ?>" readonly='true' /></li>
            <li><label>Heure de fin</label><input name="ecp_event_endtime" value="<?php echo $clean_et; ?>" /><em>24h format (ex 19h00)</em></li>
        </ul>
    </div>
    <?php
}

/**
 * Fonction qui gère la sauvegarde des données de l'événement
 *
 * @param type $post_id
 * @return type
 */
function save_metabox_event_data( $post_id ) {
    // only run this for event
    $authorized_post_type=["ecp_event","ecp_fevent"];
    if ( !in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    //nonce verification
    if ( empty( $_POST['ecp-event-data-nonce'] ) || !wp_verify_nonce( $_POST['ecp-event-data-nonce'], 'ecp-event-data-nonce' )) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }


    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    // convert date to unix & update post
    if( isset($_POST["ecp_event_startdate"]) ) {
        $updatestartd = dateEventfr2timestamp( $_POST["ecp_event_startdate"], $_POST["ecp_event_starttime"] );
        update_post_meta($post_id, "_ecp_event_startdate", $updatestartd );
    }

    if( isset($_POST["ecp_event_enddate"]) ){
        $updateendd =dateEventfr2timestamp( $_POST["ecp_event_enddate"], $_POST["ecp_event_endtime"] );
        update_post_meta($post_id, "_ecp_event_enddate", $updateendd );
    }

    if( isset($_POST["ecp_event_place"]) ) {
        update_post_meta($post_id, "_ecp_event_place", strip_tags($_POST["ecp_event_place"]) );
    }


}
add_action ('save_post', 'save_metabox_event_data');

/**
 * Fonction qui définit les colonnes à afficher dans la liste des événements
 *
 * @param array $columns
 * @return string
 */
function ecp_events_edit_columns( $columns ) {

    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Evénement",
        "ecp_col_ev_pcal" => "Lié au calendrier",
        "ecp_col_ev_cat" => "Catégorie",
        "ecp_col_ev_date" => "Dates",
        "ecp_col_ev_times" => "Horaires",
        "ecp_col_ev_desc" => "Description"
    );

    return $columns;
}
add_filter ("manage_edit-ecp_event_columns", "ecp_events_edit_columns");
add_filter ("manage_edit-ecp_fevent_columns", "ecp_events_edit_columns");

/**
 * Fonction qui définit le contenu des colonnes affichées dans la liste des événements
 *
 * @global type $post
 * @param type $column
 */
function ecp_event_custom_columns( $column, $post_id ) {

    $custom = get_post_custom($post_id);
    $post=get_post($post_id);

    switch ($column)
    {
        case "ecp_col_ev_cat":
            // - show taxonomy terms -
            $eventcats = get_the_terms($post->ID, "ecp_tax_event_category");
            $eventcats_html = array();
            if ($eventcats) {
                foreach ($eventcats as $eventcat) {
                    array_push($eventcats_html, $eventcat->name);
                }
                echo implode($eventcats_html, ", ");
            } else {
                echo "Aucune catégorie";
            }
        break;
        case "ecp_col_ev_date":
            // - show dates -
            $startd = $custom["_ecp_event_startdate"][0];
            $endd = $custom["_ecp_event_enddate"][0];
            $startdate = date_i18n("j F Y", $startd);
            $enddate = date_i18n("j F Y", $endd);
            echo $startdate . '<br />' . $enddate;
        break;
        case "ecp_col_ev_times":
            // - show times -
            $startt = $custom["_ecp_event_startdate"][0];
            $endt = $custom["_ecp_event_enddate"][0];
            //$time_format = get_option('time_format');
            $time_format = "H\hi";
            $starttime = date_i18n($time_format, $startt);
            $endtime = date_i18n($time_format, $endt);
            echo $starttime . ' - ' .$endtime;
        break;
        case "ecp_col_ev_desc";
            the_excerpt();
        break;
        case "ecp_col_ev_pcal" :
            if(!empty($post->post_parent)) {
                $cal = get_post($post->post_parent);
                echo $cal->post_title;
            } else {
                echo "Aucun calendrier";
            }
        break;

    }
}
add_action ("manage_posts_custom_column", "ecp_event_custom_columns", 10, 2);

/**
 * Fonction qui met à jour les messages de mise à jour spécifique à l'événement
 *
 * @global type $post
 * @global type $post_ID
 * @param type $messages
 * @return type
 */
function ecp_event_updated_messages( $messages ) {

    global $post, $post_ID;

    $labels = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( "Evénement mis à jour. <a href='%s'>Voir l'événement</a>", esc_url( get_permalink($post_ID) ) ),
        2 => 'Champ mis à jour',
        3 => 'Champ mis à jour',
        4 => 'Evenément mis à jour',
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf( 'Evenement restauré depuis la révision %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( "Evénement publié. <a href='%s'>Voir l'événement</a>", esc_url( get_permalink($post_ID) ) ),
        7 => "Evénement sauvegardé",
        8 => sprintf( "Evénement envoyé. <a target='_blank' href='%s'>Prévisualiser l'événment</a>", esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( "Evénément programmé pour: <strong>%1$s</strong>. <a target='_blank' href='%2$s'>Prévisualiser l'événment</a>",
          // translators: Publish box date format, see http://php.net/date
          date_i18n('M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( "Brouillon de l'événement. <a target='_blank' href='%s'>Prévisualiser l'événment</a>", esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
      );
    $messages['ecp_event']=$labels;
    $messages['ecp_fevent']=$labels;

    return $messages;
}
add_filter('post_updated_messages', 'ecp_event_updated_messages');


