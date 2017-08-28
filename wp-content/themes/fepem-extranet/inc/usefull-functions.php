<?php
/**
* Function to get connected member Extranet
*/
function get_member_connected() {
    $member=null;

    if ( is_user_logged_in() ) {
        if( current_user_can( 'member-extranet' ) ) {
            $member = wp_get_current_user();
        }
    }

    return $member;

}
/**
 * Fonction qui redirige l'utilisateur selon son état de connexion et son role
 */
function redirect_user_if_no_access_extranet() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        //@TODO ajouter accès aux administrateurs et administrateurs d'instance
        if( ! current_user_can( 'member-extranet' ) ) {
            wp_redirect(admin_url());
            exit;
        }
    } else {
        auth_redirect();
    }
}
/**
 * Fonction pour vérifier si utilisateur connecté à accès à la commission
 *
 * @param   int $id_commission  identifier of commission
 * @param   int $id_user        identifier of user
 *
 * @return  bool                access or not
 */
function check_user_access_instance($id_commission, $id_user=0) {
    $access=false;

    $list_members = get_post_meta($id_commission,"_meta_members_commission", false);


    if(in_array($id_user, $list_members)) {
        $access=true;
    }

    return $access;
}
/**
 * Fonction qui retrouve l'instance à laquelle appartient le message
 *
 * @param int $id_message
 */
function get_parent_instance_of_message($id_message) {
    $instance="";

    if(empty( $id_message )) {
        return $instance;
    }

    $message=get_post($id_message);
    
    if(!empty($message->post_parent)) {
        //récupération de la messagerie
        $messagerie = get_post($message->post_parent);
        $instances = get_posts(array(
                'post_type' => 'commission',
                'meta_key' => '_meta_messagerie_commission',
                'meta_value' => $messagerie->ID,
                'no_found_rows' => true
        ));
        if(!empty($instances)) {
            //récupération de l'instance
            $instance=$instances[0];
        }
        
    }
    
    return $instance;
}

/**
 * Fonction qui retrouve l'instance à laquelle appartient le calendrier
 *
 * @param int $id_calendar
 */
function get_parent_instance_of_calendar($id_calendar) {
    $instance="";

    if(empty( $id_calendar )) {
        return $instance;
    }
    
    $instances = get_posts(array(
        'post_type' => 'commission',
        'meta_key' => '_meta_calendar_commission',
        'meta_value' => $id_calendar,
        'no_found_rows' => true
    ));

    if(!empty($instances)) {
        $instance=$instances[0];
    }

    return $instance;
}

/**
 * Fonction pour retrouver l'instance à laquelle appartient la messagerie
 *
 * @param   int     $id_messgaerie      id de la messagerie dont il faut retrouver l'instance parente
 * @return  objet                       instance
 */
function get_parent_instance_of_messagerie($id_messagerie) {
    $instance="";
    
    if(empty( $id_messagerie )) {
        return $instance;
    }
    
    $instances = get_posts(array(
        'post_type' => 'commission',
        'meta_key' => '_meta_messagerie_commission',
        'meta_value' => $id_messagerie,
        'no_found_rows' => true
    ));

    if(!empty($instances)) {
        $instance=$instances[0];
    }

    return $instance;
}
/**
 * Fonction pour retrouver l'instance à laquelle appartient la ged
 * 
 * @param   int     $id_ged      id de la ged dont il faut retrouver l'instance parente
 * @return  objet                       instance
 */
function get_parent_instance_of_ged($id_ged) {
    $instance="";

    if(empty( $id_ged )){
        return $instance;
    }

    $instances = get_posts(array(
        'post_type' => 'commission',
        'meta_key' => '_meta_ged_commission',
        'meta_value' => $id_ged,
        'no_found_rows' => true
    ));

    if(!empty($instances)) {
        $instance=$instances[0];
    }

    return $instance;
}

/**
 * Fonction pour retrouver l'instance à laquelle appartient un document
 * 
 * @param int $id_doc   id du document dont n veut retrouver l'instance à laquelle il apparient
 */
function get_parent_instance_of_doc($id_doc){
    $instance="";

    if(empty( $id_doc )){
        return $instance;
    }

    $id_parent_ged=wp_get_post_parent_id( $id_doc );

    if( $id_parent_ged != false ) {
        $instance=get_parent_instance_of_ged( $id_parent_ged );
    }

    return $instance;

}

/**
 * Fonction pour retrouver l'instance à laquelle appartient un événement
 *
 * @param int $id_event   id de l'événement dont on veut retrouver l'instance à laquelle il apparient
 */
function get_parent_instance_of_event($id_event){
    $instance="";

    if(empty( $id_event )){
        return $instance;
    }

    $id_parent_calendar=wp_get_post_parent_id( $id_event );

    if( $id_parent_calendar != false ) {
        $instance= get_parent_instance_of_calendar( $id_parent_calendar );
    }

    return $instance;

}

/**
 * Fonction pour convertir la date en français d'un événement en timestamp
 *
 * @global  array       $monthsfrtonb   tableau de correspondance entre mois en français et en mois en chiffre
 * @param   string      $date           date à convertir
 * @param   string      $time           horaire à convertir
 * @return  string                      Stimestamp
 */
function dateEventfr2timestamp( $date, $time ) {
    global $monthsfrtonb;

    list($jour,$mois,$annee)=explode(' ',$date);
    list($heure,$minute)=explode('h',$time);
    
    $mois = isset($monthsfrtonb[ucfirst($mois)]) ? $monthsfrtonb[ucfirst($mois)] : date('m');


    //$enformatdate= date('Y-m-d H:i', mktime($heure,$minute,0,$mois,$jour,$annee));
    $timestamp=mktime($heure,$minute,0,$mois,$jour,$annee);

    return $timestamp;
}

/**
 * Fonction pour trier les composants de l'instance par date
 */
function sort_instance_composants_by_date($composants_date, $composants_data) {
    $composants_sorted=[];

    

    uasort($composants_date, "cmp");

    foreach($composants_date as $id => $date_composant) {
        $composants_sorted[$id]=$composants_data[$id];
    }

    return $composants_sorted;
}

/**
 * Fonction pour trier un tableau de date
 *
 * @param type $a
 * @param type $b
 * @return type
 */
function cmp($a, $b) {
    return DateTime::createFromFormat('Y-m-d H:i:s', $a) < DateTime::createFromFormat('Y-m-d H:i:s', $b);

}

/**
 * Fonction qui récupère les différents éléments des instances fournies en paramètre
 *
 * @param   int     $list_id_instances  liste des ids des instances dont il faut récuprérer les composants
 * @param   bool    $messagerie         pour indiquer si les messages doivent être récupérés
 * @param   bool    $event              pour indiquer si les événements doivent être récupérés
 * @param   bool    $doc                pour indiquer si les documents doivent être récupérés
 * @return  array                       liste des composants
 */
function getcomposants_of_instances($list_id_instances, $messagerie=true, $comment=true, $event=true, $doc=true) {
    $list_composants_instance_sorted=[];

    //array to sort
    $list_composants_instance=[];
    $instance_composants_date=[];
    foreach ($list_id_instances as $id_instance) {
        //nom de l'instance
        $instance_title = get_the_title($id_instance);
        //récupération des messagerie/messages
        if($messagerie == true) {
            $messageries=get_post_meta($id_instance,'_meta_messagerie_commission');

            //@TODO : ajouter le filtre sur le type de public pour ne prendre en compte que la bonne messagerie
            if (!empty($messageries)) {
                foreach($messageries as $messagerie) {

                    $messages = get_posts(
                                    array(
                                        'post_type' => 'ecp_message',
                                        'post_parent' => $messagerie,
                                        'posts_per_page'   => -1
                                    )
                                );
                    if(!empty($messages)) {
                        foreach($messages as $message) {
                            $instance_composants_date['message_'.$message->ID]=$message->post_date;

                            $list_composants_instance['message_'.$message->ID]['tag'] ='message';
                            $list_composants_instance['message_'.$message->ID]['tag-class'] ='tag-message';
                            $list_composants_instance['message_'.$message->ID]['titre'] = $message->post_title;
                            $list_composants_instance['message_'.$message->ID]['lien'] = get_permalink($message->ID);
                            $list_composants_instance['message_'.$message->ID]['date'] = $message->post_date;
                            $list_composants_instance['message_'.$message->ID]['parent-instance-title'] =  $instance_title;

                            if($comment==true) {
                                // récupération des commentaires du message
                                $list_comments = get_comments(
                                                        array('post_id' => $message->ID,
                                                              'post_type' => 'ecp_message' )
                                                        );
                                //var_dump($list_comments);
                                if(!empty($list_comments)) {
                                    foreach($list_comments as $comment) {
                                        $instance_composants_date['comment_'.$comment->comment_ID]=$comment->comment_date;

                                        $list_composants_instance['comment_'.$comment->comment_ID]['tag'] ='commentaire';
                                        $list_composants_instance['comment_'.$comment->comment_ID]['tag-class'] ='tag-comment';
                                        $list_composants_instance['comment_'.$comment->comment_ID]['titre'] = substr($comment->comment_content,0, 20);
                                        $list_composants_instance['comment_'.$comment->comment_ID]['lien'] = get_permalink($message->ID);
                                        $list_composants_instance['comment_'.$comment->comment_ID]['date'] = $comment->comment_date;
                                        $list_composants_instance['comment_'.$comment->comment_ID]['parent-instance-title'] = $instance_title;
                                    }
                                } // fin liste des commentaires
                            }
                        }//fin liste des messages
                    }
                } // fin liste des messgaeries
            }
        }

        if($event == true) {
            //récupération des événements
            $calendriers= get_post_meta($id_instance, '_meta_calendar_commission');
            if (!empty($calendriers)) {
                foreach($calendriers as $cal) {
                    $events = get_posts(
                                    array(
                                        'post_type' => 'ecp_event',
                                        'post_parent' => $cal,
                                        'posts_per_page'   => -1
                                )
                            );
                    if(!empty($events)) {
                        foreach($events as $event) {
                            $instance_composants_date['event_'.$event->ID]=$event->post_date;

                            $list_composants_instance['event_'.$event->ID]['tag'] ='événement';
                            $list_composants_instance['event_'.$event->ID]['tag-class'] ='tag-event';
                            $list_composants_instance['event_'.$event->ID]['titre'] = $event->post_title;
                            $list_composants_instance['event_'.$event->ID]['lien'] = get_permalink($event->ID);
                            $list_composants_instance['event_'.$event->ID]['date'] = $event->post_date;
                            $list_composants_instance['event_'.$event->ID]['parent-instance-title'] = $instance_title;
                        }
                    }
                }
            }
        }
        if($doc==true) {
            //récupération des documents
            $geds= get_post_meta($id_instance, '_meta_ged_commission');

            //@TODO : ajouter le filtre sur le type de public pour ne prendre en compte que la bonne GED
            if (!empty($geds)) {
                foreach($geds as $ged) {
                    $docs = get_posts(
                                    array(
                                        'post_type' => 'ecp_document',
                                        'post_parent' => $ged,
                                        'posts_per_page'   => -1
                                )
                            );
                    if(!empty($docs)) {
                        foreach($docs as $doc) {
                            $instance_composants_date['doc_'.$doc->ID]=$doc->post_date;

                            $list_composants_instance['doc_'.$doc->ID]['tag'] ='document';
                            $list_composants_instance['doc_'.$doc->ID]['tag-class'] ='tag-doc';
                            $list_composants_instance['doc_'.$doc->ID]['titre'] = $doc->post_title;
                            $list_composants_instance['doc_'.$doc->ID]['lien'] = get_permalink($doc->ID);
                            $list_composants_instance['doc_'.$doc->ID]['date'] = $doc->post_date;
                            $list_composants_instance['doc_'.$doc->ID]['parent-instance-title'] = $instance_title;
                        }
                    }
                }
            }
        }
    }

    //tri des composants de l'instance
    if(!empty($list_composants_instance)) {
        $list_composants_instance_sorted=sort_instance_composants_by_date($instance_composants_date, $list_composants_instance);
    }

    return $list_composants_instance_sorted;
}

/**
 * Fonction qui récupère les messages d'une messagerie
 * 
 * @param int $id_messagerie    id de la messagerie dont il faut récupérer les messages
 */
function getmessages_of_messagerie($id_messagerie) {
    $list_messages_messagerie=[];

    if(!empty($id_messagerie)) {
        $messages = get_posts(
                        array(
                            'post_type' => 'ecp_message',
                            'post_parent' => $id_messagerie,
                            'posts_per_page'   => -1,
                            'orderby'          => 'date',
                            'order'            => 'DESC',
                        )
                    );
        if(!empty($messages)) {
            foreach($messages as $message) {
                $list_messages_messagerie['message_'.$message->ID]['tag'] ='message';
                $list_messages_messagerie['message_'.$message->ID]['tag-class'] ='tag-message';
                $list_messages_messagerie['message_'.$message->ID]['titre'] = $message->post_title;
                $list_messages_messagerie['message_'.$message->ID]['lien'] = get_permalink($message->ID);
                $list_messages_messagerie['message_'.$message->ID]['date'] = $message->post_date;
            } //fin liste des messgaes
        }
    }


    return $list_messages_messagerie;

}
/**
 * Fonction qui récupère les différents éléments d'une instance
 *
 * @param type $list_id_instances   liste des ids de instances dont il faut récuprérer les composants
 */
/*function getmessages_of_instance($list_id_instances) {
    $list_messages_instance_sorted=[];

    //array to sort
    $list_messages_instance=[];
    $instance_messages_date=[];

    foreach ($list_id_instances as $id_instance) {
        $messageries=get_post_meta($id_instance,'_meta_messagerie_commission');
        if (!empty($messageries)) {
            foreach($messageries as $messagerie) {

                $messages = get_posts(
                            array(
                                'post_type' => 'ecp_message',
                                'post_parent' => $messagerie,
                                'posts_per_page'   => -1
                            )
                        );
                if(!empty($messages)) {
                    foreach($messages as $message) {
                        $instance_messages_date['message_'.$message->ID]=$message->post_date;
                        
                        $list_messages_instance['message_'.$message->ID]['tag'] ='message';
                        $list_messages_instance['message_'.$message->ID]['tag-class'] ='tag-message';
                        $list_messages_instance['message_'.$message->ID]['titre'] = $message->post_title;
                        $list_messages_instance['message_'.$message->ID]['lien'] = get_permalink($message->ID);
                        $list_messages_instance['message_'.$message->ID]['date'] = $message->post_date;
                    }//fin liste des messgaes
                }
            } // fin liste des messgaeries
        }
    }

    //tri des messages de l'instance
    if(!empty($list_messages_instance)) {
        $list_messages_instance_sorted=sort_instance_composants_by_date($instance_messages_date, $list_messages_instance);
    }

    return $list_messages_instance_sorted;
}*/

/**
 * Fonction qui récupère les différents événements d'un calendrier
 *
 * @param int $id_calendar   id du calendrier dont il faut récupérer les événements
 */
function getevents_of_calendar($id_calendar) {
    if(empty($id_calendar)) {
        return [];
    }

    //récupération des événements
    $events = get_posts(
                    array(
                        'post_type' => 'ecp_event',
                        'post_parent' => $id_calendar,
                        'posts_per_page'   => -1,
                        'orderby' => 'meta_value_num',
                        'meta_key' => '_ecp_event_startdate',
                        'order' => 'DESC'
                        )
                    );
          
    return $events;
}

/**
 * Fonction qui récupère les différents événements d'un ensemble de calendrier
 *
 * @param array $list_id_calendar   liste des id des calendriers dont il faut récupérer les événements
 * @param int   $nb_event           nombre d'événement à récupérer
 * 
 */
function getevents_of_list_calendar($list_id_calendar, $nb_event=3) {
    if(empty( $list_id_calendar )) {
        return [];
    }

    //récupération des événements
    $events = get_posts(
                    array(
                        'post_type' => 'ecp_event',
                        'post_parent__in' => $list_id_calendar,
                        'posts_per_page'   => $nb_event,
                        'orderby' => 'meta_value_num',
                        'meta_key' => '_ecp_event_startdate',
                        'order' => 'DESC'
                        )
                    );
          
    return $events;
}



/**
 * Fonction qui récupère les documents d'une ged
 *
 * @param int $id_ged    id de la ged dont il faut récupérer les documents
 */
function getdocuments_of_ged($id_ged) {
    if(empty($id_ged)) {
        return [];
    }

    $documents = get_posts(
                    array(
                        'post_type' => 'ecp_document',
                        'post_parent' => $id_ged,
                        'posts_per_page'   => -1,
                        'orderby'          => 'date',
                        'order'            => 'DESC',
                    )
                );
    return $documents;
}

/**
 * Fonction afficher le menu de l'Extranet
 *
 * @param object    $id_instance     id de l'instance pour laquelle affichée le menu
 * @param type      $active_page    page active dans le menu
 */
function display_extranet_menu($id_instance, $active_page) {
    ?>
    <div class="nav-instance">
       <ul>
            <li <?php echo $active_page=="tdb"?"class='item-active'":""; ?>>
                <a href="<?php echo get_permalink($id_instance); ?>">Tableau de bord</a>
            </li>
            <?php
            $link_messagerie=get_link_composant_of_instance($id_instance, 'messagerie');
            if($link_messagerie !== false) {
                ?>
                <li <?php echo $active_page=="messages"?"class='item-active'":""; ?>>
                    <a href="<?php echo  $link_messagerie; ?>">Messages</a>
                </li>
                <?php
            }
            $link_calendar=get_link_composant_of_instance($id_instance, 'calendrier');
            if($link_calendar !== false) {
                ?>
                <li <?php echo $active_page=="calendrier"?"class='item-active'":""; ?>>
                    <a href="<?php echo $link_calendar; ?>">Calendrier</a>
                </li>
                <?php
            }
            $link_ged=get_link_composant_of_instance($id_instance, 'ged');
            if($link_ged !== false) {
                ?>
                <li <?php echo $active_page=="ged"?"class='item-active'":""; ?>>
                    <a href="<?php echo $link_ged; ?>">Documents</a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php
}

/**
 * Fonction qui retourne le lien d'un objet composant les instances
 * 
 * @param type $id_instance
 * @param type $type
 * @return type
 */
function get_link_composant_of_instance($id_instance, $type) {
    $permalink=false;
    if($type=='calendrier') {
        $calendar_id=get_post_meta($id_instance, '_meta_calendar_commission',true);
        if(!empty($calendar_id)){
            $permalink=get_permalink($calendar_id);
        }
    } elseif($type=='messagerie') {
        //@TODO ajout du filtre selon type de public pour afficher la bonne messagerie
        $messagerie_id=get_post_meta($id_instance, '_meta_messagerie_commission',true);
        if(!empty($messagerie_id)) {
            $permalink=get_permalink($messagerie_id);
        }
    } elseif($type=='ged') {
        //@TODO ajout du filtre selon type de public pour afficher la bonne ged
        $ged_id=get_post_meta($id_instance, '_meta_ged_commission',true);
        if(!empty($ged_id)) {
            $permalink=get_permalink($ged_id);
        }
    }

    return $permalink;
}

/**
 * Fonction pour générer la vue qui affiche les messages
 *
 * @param   array     $list_message liste de messages à afficher
 */
function display_view_message($list_message) {

    foreach($list_message as $message) {
    ?>
        <div class="item-composant item-2-col">
            <span class="title-composant">
                <span>
                    <a href="<?php echo $message['lien']; ?>"><?php echo $message['titre']; ?></a>
                </span>
            </span><!-- @whitespace
            --><span class="date-composant">
                    <span><?php echo $message['date']; ?></span>
                </span>
        </div>
    <?php
    }

}

/**
 * Fonction pour générer la vue qui affiche les événements en liste
 * 
 * @param array $list_events liste des événéments à afficher
 */
function display_view_list_events($list_events) {
    $currentday = null;
    $nb_events=count($list_events);
    $counter=0;

    foreach($list_events as $event) {
        $sd=get_post_meta($event->ID,'_ecp_event_startdate', true);
        $ed=get_post_meta($event->ID,'_ecp_event_enddate', true);

        $eventdate= date_i18n("l j F Y", $sd);
        $st=date("H\hi", $sd);
        $et=date("H\hi", $ed);

        //lien qui gère la confirmation de présence
        //@TODO traitement non implémenté pour l'instant => c'est un substitut à l'absence de javascript (formulaire soumis via popup)
        //faire le traitement ou mettre une balise no script pour indiquer js requis pour cette fonctionalité
        $link_confirmation= home_url('gestion-presence-evenement')."/confirmation/".$event->ID;
        //lien qui gère l'aannulation de présence
        $link_annulation= home_url('gestion-presence-evenement')."/annulation/".$event->ID;

        $confirmation= extranetcp_get_confirmations(array(
                                                    'fields' => 'count',
                                                    'user_id'=> get_current_user_id(),
                                                    'event_id' =>  $event->ID
                                                    )
                                                );

        if($currentday == null ) {
            ?>
            <div class="list-item-events">
            <?php
        }
        if($eventdate != $currentday && $currentday != null) {
            ?>
            </div>
            <div class="list-item-events">
            <?php
        }
        if($currentday == null || $eventdate != $currentday) {
            ?>
            <div class="list-item-events-date">
                <span><?php echo $eventdate; ?></span>
            </div>
            <?php
        }
        ?>
        <div class="item-event">
            <div class="item-event-hour"><?php echo $st." - ".$et; ?></div><!--@whitespace
            --><div class="item-event-desc">
                <div class="item-event-title"><?php echo $event->post_title; ?></div>
                <div class="item-event-content"><?php echo $event->post_content; ?></div>
                <div>
                    <?php
                    //Si l'utilisateur a confirmé sa présence il peut l'annuler
                    if($confirmation > 0 ) {
                        ?>
                        Ma présence est confirmée | <a href="<?php echo $link_annulation; ?>">J'annule ma présence</a>
                        <?php
                    } else {
                        //sinon il peut confirmer sa présence
                        ?>
                        <a class="custom_popup-button" href="#">Je confirme ma présence</a>
                        <div class="custom-popup">
                            <?php
                            display_popup_presence_content($event);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
          
        if($counter == $nb_events) {
            ?>
            </div>
            <?php
        }

        $currentday=$eventdate;
        $counter++;
    }

}

/**
 * Fonction pour générer la vue qui affiche les documents
 *
 * @param   array     $list_message liste de messages à afficher
 */
function display_view_documents($list_documents) {

    foreach($list_documents as $document) {
        $url_file=get_post_meta($document->ID,'_ecp_document_file_attached', true);
        $file_infos=wp_check_filetype($url_file);
        $file_ext=$file_infos['ext'];
        $author = get_user_by('ID',$document->post_author);
        ?>
        <div class="item-composant">
            <span class="tag-document">
                <span>
                   <?php
                   if($file_ext=="doc" || $file_ext=="docx") {
                        ?>
                        <img src="<?php echo get_template_directory_uri()."/images/icon-doc.png"; ?>" alt="icone fichier doc"/>
                        <?php
                   } elseif($file_ext=="xls" || $file_ext=="xlsx") {
                        ?>
                        <img src="<?php echo get_template_directory_uri()."/images/icon-excel.png"; ?>" alt="icone fichier excel"/>
                        <?php
                   } elseif($file_ext=="pdf") {
                        ?>
                        <img src="<?php echo get_template_directory_uri()."/images/icon-pdf.png"; ?>" alt="icone fichier pdf"/>
                        <?php
                   } else {
                        ?>
                        <img src="<?php echo get_template_directory_uri()."/images/icon-file.png"; ?>" alt="icone fichier"/>
                        <?php
                   }
                   ?>
                </span>
            </span><!-- @whitespace
            --><span class="title-document">
                <span>
                    <a href="<?php echo $url_file; ?>"><?php echo $document->post_title; ?></a><br>
                    par <?php echo $author->last_name." ".$author->first_name; ?> le <?php echo $document->post_date; ?>
                </span>
            </span>
        </div>
    <?php
    }

}

/**
 * Fonction qui affiche le contenu de la popup permettant de confirmer sa présence à un événement
 *
 * @param int $id_event     id de l'événement pour lequel on affiche une popup
 */
function display_popup_presence_content($event) {
    ?>
    <div class="popup-title">Agenda</div>
    <p>
        Nous vous remercions de confirmer votre présence à l'événement <?php echo $event->post_title; ?>
        en cliquant sur le lien « Confirmer ma présence ». <br />
        Vous avez la possibilité d’adresser un message à l’assistante en charge de l’organisation de
        cette commission dans le formulaire ci-après :
    </p>
    <form action='<?php echo home_url('gestion-presence-evenement'); ?>' method='POST'>
        <input type="hidden" name="id_event" value="<?php echo $event->ID; ?>">
        <textarea name="message_confirmation" rows="10" ></textarea>
        <div class="popup-buttons">
            <input type="submit" name="confirmation" value="Confirmer ma présence" />
            <input type="button" value="Annuler" class="ok_button">
        </div>
    </form>
    <div class="close_button"></div>
    <?php
}

/**
 * Fonction pour afficher les commentaires
 *
 * @param type $comment le commentaire
 * @param type $args    les arguments de la fonction d'affichage wp_list_comments
 * @param type $depth   profondeur d'affichage des commentaire
 */
function extranetcp_comment($comment, $args, $depth) {

    ?>
    <div <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    
        <div class="comment-author">
            <?php //if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php echo get_comment_author(); ?> le <?php echo get_comment_date(); ?> à <?php echo get_comment_time(); ?>
        </div>
        <?php if ( $comment->comment_approved == '0' ) : ?>
             <em class="comment-awaiting-moderation">Le commentaire est en attente de modération</em>
              <br />
        <?php endif; ?>


        <div class='comment-body'>
            <?php comment_text(); ?>
        </div>
    </div>
    
    <?php
}

/**
* Fonction pour afficher le menu des différentes vue du calendrier
*
* @param int       $id_instance        id de du calendrier
* @param string    $active_view        vue active
*/
function display_views_menu_calendar($id_calendar, $active_view) {
    ?>
    <div class="nav-tab">
        <ul>
            <li <?php echo $active_view=="agenda"?"class='nav-tab-active'":""; ?>>
                <a href="<?php echo get_permalink($id_calendar)."agenda"; ?>">Vue agenda</a>
            </li>
            <li class="gutter"></li>
            <li <?php echo $active_view=="list"?"class='nav-tab-active'":""; ?>>
                <a href="<?php echo get_permalink($id_calendar)."list"; ?>">Vue liste</a>
            </li>

        </ul>
    </div>
    <?php
}

/**
 * Fonction pour envoyer des mails en HTML
 *
 * @return string
 */
function extranetecp_change_mail_type() {
    return "text/html";
}

/**
* Funtion to get email content of the message notification
*/
function get_message_notification_content($composant_id) {

    $post=get_post($composant_id);

    ob_start();
    if( 'ecp_message' == get_post_type( $post->ID ) ) {
        require_once get_template_directory() . '/mails/notification-message.php';
    } elseif ( 'ecp_event' == get_post_type( $post->ID ) ) {
        require_once get_template_directory() . '/mails/notification-event.php';
    } elseif ( 'ecp_document' == get_post_type( $post->ID ) ) {
        require_once get_template_directory() . '/mails/notification-document.php';
    }
    $email = ob_get_contents();
    ob_end_clean();

    return $email;
}
/**
 * Fonction qui retourne l'email de contact d'une instance
 *
 * @param int $instance_id  identifiant de l'instance
 */
function get_email_contact_of_instance($instance_id) {
    $email_contact = get_post_meta($instance_id,"_ecp_commission_email_contact", true);

    return $email_contact;
}

/**
 * Fonction qui envoie l'email confirmant la présence d'un membre à un événement
 *
 * @param   string      $email_dest         destinataire du mail
 * @param   string      $message            message à envoyer dans l'email
 * @param   object      $event              événement pour esuale l'utilisateur confirme sa présence
 * @param   WP_user      $user              utilisateur qui confirme sa présence
 */
function send_email_confirmation_presence_event($email_dest,$message,$event, $user) {

    //sujet du mail
    $subject = "Extranet - Confirmation de présence à l'événement : ".$event->post_title;

    //contenu du mail
    ob_start();
    require get_template_directory() . '/mails/notification-presence-evenement.php';
    $email = ob_get_contents();
    ob_end_clean();
    
    //envoi de l'email
    add_filter( 'wp_mail_content_type', 'extranetecp_change_mail_type' );
    $send=wp_mail($email_dest,$subject,$email);
    remove_filter( 'wp_mail_content_type', 'extranetecp_change_mail_type' );

    return $send;
}

/**
 * Fonction pour récupérer les instances d'un utilisateur
 * 
 * @param int $id_user  id de l'utilisateur dont on veut récupérer les instances
 * @return array        tableau contenant le sobjets instances
 */
function get_instances_of_user( $id_user ) {
    if(empty($id_user)) {
        return [];
    }
    $instances = get_posts(
                    array(
                        'post_type' => 'commission',
                        'meta_key' => '_meta_members_commission',
                        'meta_value' => $id_user,
                        'posts_per_page'   => -1
                    )
                );

    return $instances;
}
