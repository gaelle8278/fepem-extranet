<?php
/* 
 * Fiel to manage CPT Messagerie et CPT Message
 */

/********************************************
 * Définition du CPT Message
 *
 *******************************************/
/**
 * Fonction qui gère l'affichage de la metabox pour rattaché le message à une messagerie
 *
 * @param type $post
 */
function build_metabox_messagerie_message( $post ) {
    $selected_messagerie= wp_get_post_parent_id($post->ID);

    $messagerie_object="";
    if( "ecp_message" == get_post_type($post) ) {
        $messagerie_object = "ecp_messagerie";
    } elseif ( "ecp_fmessage" == get_post_type($post) ) {
        $messagerie_object = "ecp_fmessagerie";
    }

    $all_messagerie=[];
    if( !empty( $messagerie_object ) ) {
        $all_messagerie = get_posts( array(
            'post_type' => $messagerie_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ) );
    }
    ?>

    <div class="metabox-message-messagerie">
        <div>
            <input type="hidden" name="messagerie_message_nonce" value="<?php echo wp_create_nonce( 'messagerie_message_nonce' ); ?>" />
            <select name="ecpmessagerie" id="ecpmessagerie">
                <option value="0">-- Sélectionner une messagerie -- </option>
                <?php
                foreach ( $all_messagerie as $messagerie ) {
                    $instance=get_parent_instance_of_messagerie($messagerie->ID);
                    $id_instance="0";
                    if($instance != "") {
                        $id_instance = $instance->ID;
                    }
                    ?>
                    <option data-instance="<?php echo $id_instance; ?>" value="<?php echo $messagerie->ID; ?>" <?php echo  $messagerie->ID == $selected_messagerie  ? ' selected="selected"' : ''; ?>><?php echo $messagerie->post_title; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <?php $ajax_nonce = wp_create_nonce( 'nonce_change_select' ); ?>
        <script type="text/javascript">// <![CDATA[
            jQuery(function($) {
                $('#ecpmessagerie').change(function(){
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
                        $('#list-members-notif').empty().append("<p>La messagerie n'est lié à aucune instance</p>");
                    }
                });
            });
            // ]]>
        </script>
    </div>
    <?php
}

/**
 * Fonction qui gère la sauvegarde de la metabox indiquant la messagerie à laquelle est lié le message
 *
 * @param int $post_id
 * @return void
 */
function save_metabox_messagerie_message( $post_id ) {

    // only run this for event
    $authorized_post_type=["ecp_message","ecp_fmessage"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // verify nonce
    if ( empty( $_POST['messagerie_message_nonce'] ) || !wp_verify_nonce( $_POST['messagerie_message_nonce'], 'messagerie_message_nonce' ) ) {
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

    // to avoid infinite loop
    remove_action('save_post', 'save_metabox_messagerie_message',10);
    //to avoid duplicate send notification
    remove_action('save_post', 'composant_published_notification',12);

    // call wp_update_post update to save value, which calls save_post again
    if(isset($_POST['ecpmessagerie']) && $_POST['ecpmessagerie'] != "0") {
        wp_update_post(
            array(
                'ID' => $post_id,
                'post_parent' => intval($_POST['ecpmessagerie'])
            )
        );
    }

    // re-hook the save_post action
    add_action('save_post', 'save_metabox_messagerie_message',10);
    remove_action('save_post', 'composant_published_notification',12);

}
add_action( 'save_post', 'save_metabox_messagerie_message', 10 );

/**
 * Fonction pour sélectionner les membres à notifier pour un message
 *
 */
function build_metabox_notification_message( $post ) {
    //récupération de l'instance à  laquelle est lié le message
    $instance="";
    ?>

    <p>Liste des membres à notifier</p>
    <div class="ecp-message-notification" >
        <div id="list-members-notif">
            <?php
            if( empty($post->post_parent) ) {
                ?>
                <p>Le message n'est lié à  aucune messagerie</p>
                <?php
            } else {
                $instance = get_parent_instance_of_messagerie($post->post_parent);
                $messagerie = get_post($post->post_parent);
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
                        <p>La messagerie <?php echo $messagerie->post_title; ?> n'est liée à  aucune instance</p>
                    <?php
                }
            }
        ?>

        </div>
    </div>
    <?php
}