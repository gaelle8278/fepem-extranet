<?php
/* 
 * Page traitant l'enregistrement/la suppression en BDD de la confirmation présence à un événement
 */

global $wp_query;

$tache="";
$id_event="";

if(isset($_POST) && !empty($_POST)) {
    $id_event=filter_input(INPUT_POST, 'id_event', FILTER_VALIDATE_INT, array('options'=>array('min_range'=>0)));
    $message=filter_input(INPUT_POST, 'message_confirmation', FILTER_SANITIZE_STRING);
    $tache="confirmation";
} else {
    $id_event = $wp_query->query_vars['id_event'];
    $tache = $wp_query->query_vars['gestion_presence'];
}

if( empty($id_event) ) {
    ?>
    <p>Un événement doit être indiqué</p>
    <?php
} else {
    //user
    $user = get_member_connected();
    if( !empty($user) ) {
        //event
        $event=get_post($id_event);
        //calendrier auquel appartient l'événement pour lequel on confirme sa présence
        $calendar=get_post($event->post_parent);
        //instance à laquelle appartient le calendrier de l'événement
        $instance=get_parent_instance_of_calendar($calendar->ID);

        //verification des accès de l'utilisateur à l'événement concerné
        if(check_user_access_instance($instance->ID, $user->ID)) {
            //get contact email of instance to send message
            $email_contact_instance = get_email_contact_of_instance($instance->ID);

            if($tache=="confirmation") {
                //traitement de la confirmation
                //enregistrement en BDD
                $confirmation_id= extranetcp_insert_confirmation(array(
                                'user_id' => $user->ID,
                                'event_id' => $id_event,
                                'confirmation_message' => $message
                            ));

                //envoi du mail si l'instance comporte un email de contact
                if(!empty($email_contact_instance)) {
                    $send_mail=send_email_confirmation_presence_event($email_contact_instance, nl2br($message), $event, $user);
                    // mise à jour confirmation avec email destinataire si envoi email ok
                    if( $send_mail == true ) {
                        extranetcp_update_confirmation($confirmation_id, array(
                                                                    'confirmation_dest_email' => $email_contact_instance
                                                                    ));
                    }
                }
            } elseif ($tache == "annulation") {
                //traitement de l'annulation
                //supression en BDD
                extranetcp_delete_confirmation($user->ID, $id_event);

            }

            //retour au calendrier
            wp_redirect(get_permalink($calendar)."list");
            exit();


        } else {
            ?>
            <p>Vous n'êtes pas autorisé à accéder à cet événement</p>
            <?php
        }
    } else {
        ?>
        <p>Vous n'êtes pas connecté</p>
        <?php
    }
}


