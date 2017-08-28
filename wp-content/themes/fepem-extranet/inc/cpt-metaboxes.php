<?php
/* 
 * File to define custom posts type
 */


/***************************************************
 * Ajout des custom post type et custom taxonomies
 *
 * CPT :
 *  - Commission
 *  - Calendrier
 *  - Evenements
 *  - Messagerie
 *  - Messages
 *  - GED
 *  - Documents
 *
 * CT :
 *  - catégorie d'événement
 * 
 **************************************************/
function extranetcp_cpt() {

    //taxonomie category pour les àƒÂ©vàƒÂ©nements
    register_taxonomy(
        'ecp_eventcategory',
        'ecp_events',
        array(
            'label' => 'Catégorie',
            'labels' => array(
                'name' => "Categories d'événements",
                'singular_name' => "Catégorie d'événement",
                'search_items' =>  "Rechercher parmi les catégorie d'événements",
                'popular_items' => "Catégories d'évéments les plus utilisées",
                'all_items' => "Toutes les catégories d'évéments",
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => "Editer la catétgorie d'événements",
                'update_item' =>  "Mettre à  jour la catégorie d'événement",
                'add_new_item' => "Ajouter une catégorie d'événement",
                'new_item_name' =>  "Nouvelle catégorie d'événements",
                'separate_items_with_commas' => "Séparer les catégories d'évéments par des virgules",
                'add_or_remove_items' => "Ajouter ou supprimer une catégorie d'événement",
                'choose_from_most_used' =>  "Choisir parmi les catégories d'événements les plus utilisées",
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'query_var' => true,
            'public' => true,
        )
    );

    //instances projets
    register_post_type(
        'commission',
        array(
            'label' => 'Commissions',
            'labels' => array(
                'name' => 'Commissions',
                'singular_name' => 'Commission',
                'all_items' => 'Toutes les commissions',
                'add_new' => "Ajouter une commission",
                'add_new_item' => 'Ajouter une commission',
                'edit_item' => 'Editer la commission',
                'new_item' => 'Nouvelle commission',
                'view_item' => 'Voir la commission',
                'search_items' => 'Rechercher parmi les commissions',
                'not_found' => 'Pas de commission trouvée',
                'not_found_in_trash'=> 'Pas de commission dans la corbeille'
            ),
            'public' => true,
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'editor',
                'author'
            ),
            'has_archive' => true,
            //'menu_icon' => get_template_directory_uri() . "/images/balloon--pencil.png" --> trouver la bonne image
        )
    );

    //calendriers
    register_post_type(
        'ecp_calendrier',
        array(
            'label' => 'calendriers',
            'labels' => array(
                    'name' => 'Calendriers',
                    'singular_name' => 'Calendrier',
                    'all_items' => 'Tous les calendriers',
                    'add_new' => "Ajouter un calendrier",
                    'add_new_item' => 'Ajouter un calendrier',
                    'edit_item' => 'Editer le calendrier',
                    'new_item' => 'Nouveau calendrier',
                    'view_item' => 'Voir le calendrier',
                    'search_items' => 'Rechercher parmi les calendriers',
                    'not_found' => 'Pas de calendrier trouvé',
                    'not_found_in_trash'=> 'Pas de calendrier dans la corbeille'
                    
            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports'=> array('title', 'excerpt', 'author')
        )
    );

    //événements
    register_post_type(
        'ecp_event',
        array(
            'label' => 'événements',
            'labels' => array(
                    'name' => 'Evénements',
                    'singular_name' => 'Evénement',
                    'all_items' => 'Tous les événements',
                    'add_new' => "Ajouter un événement",
                    'add_new_item' => 'Ajouter un événement',
                    'edit_item' => "Editer l'événement",
                    'new_item' => 'Nouvel événement',
                    'view_item' => "Voir l'événement",
                    'search_items' => 'Rechercher parmi les événements',
                    'not_found' => "Pas d'événement trouvé",
                    'not_found_in_trash'=> "Pas d'événement dans la corbeille"
            ),
            'public' => true,
            'capability_type' => 'post',
            'supports'=> array('title', 'author', 'editor' ),
            'taxonomies' => array( 'ecp_eventcategory')
            //'show_in_menu' => 'edit.php?post_type=ecp_calendrier'
        )
    );

    //messagerie
    register_post_type(
        'ecp_messagerie',
        array(
            'label' => 'messageries',
            'labels' => array(
                    'name' => 'Messageries',
                    'singular_name' => 'Messagerie',
                    'all_items' => 'Toutes les messageries',
                    'add_new' => "Ajouter une messagerie",
                    'add_new_item' => 'Ajouter une messagerie',
                    'edit_item' => 'Editer la messagerie',
                    'new_item' => 'Nouvelle messagerie',
                    'view_item' => 'Voir la messagerie',
                    'search_items' => 'Rechercher parmi les messageries',
                    'not_found' => 'Pas de messagerie trouvée',
                    'not_found_in_trash'=> 'Pas de messagerie dans la corbeille'
                    
            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports'=> array('title', 'excerpt', 'author')
        )
    );

    //messages
    register_post_type(
        'ecp_message',
        array(
            'label' => 'messages',
            'labels' => array(
                    'name' => 'Messages',
                    'singular_name' => 'Message',
                    'all_items' => 'Tous les messages',
                    'add_new' => "Ajouter un message",
                    'add_new_item' => 'Ajouter un message',
                    'edit_item' => 'Editer le message',
                    'new_item' => 'Nouveau message',
                    'view_item' => 'Voir le message',
                    'search_items' => 'Rechercher parmi les messages',
                    'not_found' => 'Pas de message trouvé',
                    'not_found_in_trash'=> 'Pas de message dans la corbeille'

            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports'=> array('title', 'excerpt', 'editor', 'author', 'thumbnail','comments')
        )
    );

    //ged
    register_post_type(
        'ecp_ged',
        array(
            'label' => 'ged',
            'labels' => array(
                    'name' => 'GED',
                    'singular_name' => 'GED',
                    'all_items' => 'Toutes les GED',
                    'add_new' => "Ajouter une GED",
                    'add_new_item' => 'Ajouter une GED',
                    'edit_item' => 'Editer la GED',
                    'new_item' => 'Nouvelle GED',
                    'view_item' => 'Voir la GED',
                    'search_items' => 'Rechercher parmi les GED',
                    'not_found' => 'Pas de GED trouvée',
                    'not_found_in_trash'=> 'Pas de GED dans la corbeille'

            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports'=> array('title', 'excerpt', 'author')
        )
    );

    //ged
    register_post_type(
        'ecp_document',
        array(
            'label' => 'document',
            'labels' => array(
                    'name' => 'Document',
                    'singular_name' => 'Documents',
                    'all_items' => 'Tous les documents',
                    'add_new' => "Ajouter un document",
                    'add_new_item' => 'Ajouter un document',
                    'edit_item' => 'Editer le document',
                    'new_item' => 'Nouveau document',
                    'view_item' => 'Voir le document',
                    'search_items' => 'Rechercher parmi les documents',
                    'not_found' => 'Pas de document trouvé',
                    'not_found_in_trash'=> 'Pas de document dans la corbeille'

            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports'=> array('title', 'excerpt', 'author')
        )
    );
}
add_action('init', 'extranetcp_cpt');

/****************
 * Admin Menus
 *
 ****************/
function extranetcp_admin_menu() {
    //add_submenu_page('edit.php?post_type=ecp_calendrier', "Test", "test",'manage_options','toto', 'mytotopage');

    //gestion des calendriers
    add_submenu_page('edit.php?post_type=ecp_calendrier', 'Liste des événements', 'Tous les événements', 'manage_options', 'edit.php?post_type=ecp_event');
    add_submenu_page('edit.php?post_type=ecp_calendrier', "Ajout d'un événement", 'Ajouter un événement', 'manage_options', 'post-new.php?post_type=ecp_event');
    remove_menu_page('edit.php?post_type=ecp_event');

    //gestion des messageries
    add_submenu_page('edit.php?post_type=ecp_messagerie', 'Liste des messages', 'Tous les messages', 'manage_options', 'edit.php?post_type=ecp_message');
    add_submenu_page('edit.php?post_type=ecp_messagerie', "Ajout d'un message", 'Ajouter un message', 'manage_options', 'post-new.php?post_type=ecp_message');
    remove_menu_page('edit.php?post_type=ecp_message');

    //gestion des documents
    add_submenu_page('edit.php?post_type=ecp_ged', 'Liste des documents', 'Tous les documents', 'manage_options', 'edit.php?post_type=ecp_document');
    add_submenu_page('edit.php?post_type=ecp_ged', "Ajout d'un document", 'Ajouter un document', 'manage_options', 'post-new.php?post_type=ecp_document');
    remove_menu_page('edit.php?post_type=ecp_document');

}
add_action('admin_menu', 'extranetcp_admin_menu');

/**************************************************
 * Déclaration des metabox utilisées avec les CPT
 *
 *************************************************/
function extranetcp_cpt_metaboxes() {
    // metabox pour indiquer un email de contact pour les cpt instance
    add_meta_box('meta_email_contact_commission', 'Email de contact de la commission', 'build_metabox_email_contact_commission', 'commission', 'side','default');
    //metabox membres pour les cpt commission
    add_meta_box('meta_members_commission', 'Membres de la commission', 'build_metabox_members_commission', 'commission', 'side', 'default');
    //metabox calendrier pour les cpt commission
    add_meta_box('meta_calendar_commission', 'Calendrier de la commission', 'build_metabox_calendar_commission', 'commission', 'side');
    //metabox messagerie pour les cpt commission
    add_meta_box('meta_messagerie_commission', 'Messagerie de la commission', 'build_metabox_messagerie_commission', 'commission', 'side');
    //metabox ged pour les cpt commission
    add_meta_box('meta_ged_commission', 'GED de la commission', 'build_metabox_ged_commission', 'commission', 'side');


    //metabox calendrier pour les cpt événement
    add_meta_box( 'metabox_event_parent_calendar', 'Calendrier de rattachement', 'build_metabox_event_calendar', 'ecp_event', 'side' );
    //metabox pour les données du cpt événement
    add_meta_box('metabox_event_data', "Données de l'événement", 'build_metabox_event_data', 'ecp_event');
    //metabox notification pour les cpt événement
    add_meta_box('metabox_event_notification', "Notification", 'build_metabox_notification_event', 'ecp_event', 'normal','high');

    //metabox messagerie pour les cpt messages
    add_meta_box('metabox_messagerie_message', "Messagerie", 'build_metabox_messagerie_message', 'ecp_message', 'side');
    //metabox notification pour les cpt messages
    add_meta_box('metabox_notification_message', "Notification", 'build_metabox_notification_message', 'ecp_message','normal','high');

    //metabox ged pour les cpt document
    add_meta_box('metabox_ged_document', "GED", 'build_metabox_ged_document', 'ecp_document', 'side');
    //metabox champ d'upload pour les cpt document
    add_meta_box('metabox_upload_document', "Fichier", 'build_metabox_upload_document', 'ecp_document', 'normal');
    // metabox notification pour les cpt document
    add_meta_box('metabox_notification_document',"Notification","build_metabox_notification_document","ecp_document",'normal');

    //metabox pour afficher les documents associés pour les cpt GED
    add_meta_box('metabox_documents_ged',"Documents de la GED","build_metabox_documents_ged","ecp_ged",'normal');


}
add_action('add_meta_boxes','extranetcp_cpt_metaboxes');

/***********************************
 * Définition du CPT Commission
 *
 ***********************************/
/**
 * Fonction qui affiche la métabox pour ajouter des membres à la commission
 */
function build_metabox_members_commission( $post ) {
    //récupération la meta potentiellement sauvegardée
    $members_commission = get_post_meta($post->ID,'_meta_members_commission',false);

    // nonce
    wp_nonce_field('update-members-commission_'.$post->ID, '_wpnonce_update_members-commission');

    //metabox
    ?>
    <div class="ui-widget">
        <div>
            <p>Ajouter un membre</p>
            <label for="nom">Nom : </label><input id="nom" type="text" />
        </div>
    
        <div>
            <p>Liste des membres</p>
            <ul id="list-members">
                <?php
                if( ! empty( $members_commission ) ) {
                    foreach( $members_commission as $idmember ) {
                        $user=get_user_by('ID',$idmember);
                        ?>
                        <li data-id="<?php echo  $idmember; ?>"><span class="erase">x</span><?php echo $user->first_name.' '.$user->last_name; ?></li>
                        <?php
                    }
        
                }
                ?>
            </ul>
        </div>

        <input id="selected_members_commission" type="hidden" name="selected_members_commission" value="<?php echo implode(',',$members_commission); ?>" />
    </div>
   
    <script type="text/javascript">// <![CDATA[
        jQuery(function($) {

            // un tableau avec tous les utilisateurs que l'on peut sélectionner
            var availableTags = [<?php
            $users=get_users( '' );
            foreach($users  as $u){
                echo '{value:"'.$u->ID.'",label:"'.esc_js($u->first_name." ".$u->last_name).'"},'."\n";
            }
            ?>];

            //autocomplete sur le champ #nom
            $("#nom").autocomplete({
              source: availableTags,
              select: function(event,ui){
                var li = '<li data-id="' + ui.item.value + '"><span class="erase">x</span> ' + ui.item.label + '</li>';
                var all_members_commission = new Array();
                all_members_commission =($('#selected_members_commission').val()!='') ? $('#selected_members_commission').val().split(',') : [];
                if($.inArray(ui.item.value,all_members_commission)!="-1"){
                    //si valeur sélectionné déjà  présente dans le tableau des membres enregistrés/choisis
                    //=> elle n'est pas prise en compte
                    $(this).val('');
                } else {
                    //=> sinon ajout de la valeur sélectionnée au tableau des membres
                    all_members_commission.push(ui.item.value);
                    //mise à  jour du champs enregistré
                    $('#selected_members_commission').val(all_members_commission);
                    //mise à  jour de l'affichage
                    var $mc= $( "#list-members" );
                    $mc.append(li);
                    $(this).val('');
                    listenerremove();
                }

                return false;
              }
            });

            //function pour supprimer un élément
            function removeByElement(arrayName,arrayElement){
                for(var i=0; i < arrayName.length; i++) {
                    if(arrayName[i]==arrayElement) {
                        arrayName.splice(i,1);
                    }
                }
            }

            //événement de suppression de membre
            function listenerremove(){
              $("#meta_members_commission").find('li .erase').on('click',function(){
                var $elem = $(this).parent('li');
                var all_members_commission = new Array();
                all_members_commission =$('#selected_members_commission').val().split(',');
                var dataval = $elem.attr('data-id');
                removeByElement(all_members_commission,dataval);
                $elem.remove();
                $('#selected_members_commission').val(all_members_commission);
              });
            }

            listenerremove();
        });
    // ]]>
    </script>
<?php
}

/**
 * Fonction qui sauvegarde la metabox d'ajout de membres à la commission
 *
 */
function save_metabox_members_commission( $post_id ){

    if ( 'commission' != get_post_type( $post_id ) ) {
        return $post_id;
    }


    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    
    //s'il s'agit bien d'une sauvegarde volontaire
    if( ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && isset($_POST['selected_members_commission'])){
        //test du nonce
        check_admin_referer( 'update-members-commission_'.$post_id,'_wpnonce_update_members-commission' );

        // suppression des anciennes valeurs
        delete_post_meta($post_id,"_meta_members_commission");
        // ajout de snouvelles valeurs
        if(!empty($_POST['selected_members_commission'])) {
            $members = explode(',',$_POST['selected_members_commission']);
            foreach($members as $c){
                //pour chaque entràƒÂ©e j'ajoute une meta
                add_post_meta($post_id, "_meta_members_commission", intval($c));
            }
        }
    }
}
add_action('save_post','save_metabox_members_commission');

/**
 * Fonction qui affiche la metabox pour ajouter un calendrier à la commission
 *
 * @param type $post
 */
function build_metabox_calendar_commission( $post ) {
    $all_calendar = get_posts( array(
        'post_type' => 'ecp_calendrier',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );

    $selected_cal= get_post_meta($post->ID,'_meta_calendar_commission',true);

   
    ?>
    <div class="metabox-commision-calendar">
        <div>
            <input type="hidden" name="commission-calendar-nonce" id="commission-calendar-nonce" value="<?php echo wp_create_nonce( 'commission-calendar-nonce' ); ?>" />
            <select name="commission-ecpcalendrier">
                <option value="0">-- Sélectionner un calendrier -- </option>
                <?php
                foreach ( $all_calendar as $cal ) : ?>
                    <option value="<?php echo $cal->ID; ?>"<?php echo  $cal->ID == $selected_cal  ? ' selected="selected"' : ''; ?>><?php echo $cal->post_title; ?></option>
                <?php endforeach; ?>
           </select>

        </div>
    </div>
    <?php
}

/**
 * Fonction qui gère la sauvegarde du calendrier associé à la commission
 * @param type $post_id
 */
function save_metabox_calendar_commission( $post_id ) {
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    //nonce verification
    if ( empty( $_POST['commission-calendar-nonce'] ) || !wp_verify_nonce( $_POST['commission-calendar-nonce'], 'commission-calendar-nonce' )) {
        return $post_id;
    }

    // save meta
    if( isset($_POST["commission-ecpcalendrier"]) && $_POST["commission-ecpcalendrier"] != "0" ) {
        update_post_meta($post_id, "_meta_calendar_commission", intval($_POST["commission-ecpcalendrier"]) );
    }
    
}
add_action('save_post','save_metabox_calendar_commission');

/**
 * Fonction qui affiche la metabox pour ajouter une messagerie à la commission
 *
 * @param type $post
 */
function build_metabox_messagerie_commission( $post ) {
    $all_messagerie = get_posts( array(
        'post_type' => 'ecp_messagerie',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC',
    ) );

    $selected_messageries= get_post_meta($post->ID,'_meta_messagerie_commission',false);


    ?>
    <div class="metabox-commission-messagerie">
        <div>
            <input type="hidden" name="commission-messagerie-nonce" id="commission-messagerie-nonce" value="<?php echo wp_create_nonce( 'wpnonce-commission-messagerie-nonce' ); ?>" />
            <select name="commission-ecpmessagerie[]" multiple>
                <?php
                foreach ( $all_messagerie as $messagerie ) : ?>
                    <option value="<?php echo $messagerie->ID; ?>"<?php echo  in_array($messagerie->ID, $selected_messageries)  ? ' selected="selected"' : ''; ?>><?php echo $messagerie->post_title; ?></option>
                <?php endforeach; ?>
           </select>

        </div>
    </div>
    <?php

}

/**
 * Fonction qui gère la sauvegarde de la messagerie associée à la commission
 * @param type $post_id
 */
function save_metabox_messagerie_commission( $post_id ) {

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    //nonce verification
    if ( empty( $_POST['commission-messagerie-nonce'] ) || !wp_verify_nonce( $_POST['commission-messagerie-nonce'], 'wpnonce-commission-messagerie-nonce' )) {
        return $post_id;
    }

    // save meta
    // suppression des anciennes valeurs
    delete_post_meta($post_id,"_meta_messagerie_commission");
    //ajout des nouvelles valeurs saisies
    if(isset($_POST["commission-ecpmessagerie"]) && !empty($_POST["commission-ecpmessagerie"])) {
        foreach ($_POST["commission-ecpmessagerie"] as $id_messagerie) {
            if( $id_messagerie != "0" ) {
                add_post_meta($post_id, "_meta_messagerie_commission", intval($id_messagerie) );
            }
        }
    }

}
add_action('save_post','save_metabox_messagerie_commission');

/**
 * Fonction qui gère l'affichage de la metabox pour renseigner un email de contact
 */
function build_metabox_email_contact_commission( $post ) {
    $email_contact=get_post_meta($post->ID, '_ecp_commission_email_contact', true);
    ?>
    <div class="ecp-commission-email-contact">
        <input type="hidden" name="ecp-commission-email-contact-data-nonce" id="ecp-commission-email-contact-data-nonce" value="<?php echo wp_create_nonce( 'ecp-commission-email-contact-data-nonce' ); ?>" />
        <label>E-mail<input name="ecp_commission_email_contact" value="<?php echo $email_contact; ?>" /></label>
    </div>
    <?php

}

/**
 * Fontion qui sauvegarde la metabox email de contact
 */
function save_metabox_email_contact_commission( $post_id ) {
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    //nonce verification
    if ( empty( $_POST['ecp-commission-email-contact-data-nonce'] ) || !wp_verify_nonce( $_POST['ecp-commission-email-contact-data-nonce'], 'ecp-commission-email-contact-data-nonce' )) {
        return $post_id;
    }

    // save meta
    if( isset($_POST["ecp_commission_email_contact"]) && $_POST["ecp_commission_email_contact"] != "0" ) {
        update_post_meta( $post_id, "_ecp_commission_email_contact", esc_html($_POST["ecp_commission_email_contact"]) );
    }
}
add_action('save_post','save_metabox_email_contact_commission');

/**
 * Fonction qui affiche la metabox pour ajouter une GED à la commission
 *
 * @param type $post
 */
function build_metabox_ged_commission( $post ) {
    $all_ged = get_posts( array(
        'post_type' => 'ecp_ged',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC',
    ) );

    $selected_ged= get_post_meta($post->ID,'_meta_ged_commission',false);


    ?>
    <div class="metabox-commission-ged">
        <div>
            <input type="hidden" name="commission-ged-nonce" id="commission-ged-nonce" value="<?php echo wp_create_nonce( 'wpnonce-commission-ged-nonce' ); ?>" />
            <select name="commission-ecpged[]" multiple>
                <?php
                foreach ( $all_ged as $ged ) : ?>
                    <option value="<?php echo $ged->ID; ?>"<?php echo  in_array($ged->ID, $selected_ged)  ? ' selected="selected"' : ''; ?>><?php echo $ged->post_title; ?></option>
                <?php endforeach; ?>
           </select>

        </div>
    </div>
    <?php

}

/**
 * Fonction qui gà¨re la sauvegarde de la messagerie associée à  la commission
 * @param type $post_id
 */
function save_metabox_ged_commission( $post_id ) {

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    //nonce verification
    if ( empty( $_POST['commission-ged-nonce'] ) || !wp_verify_nonce( $_POST['commission-ged-nonce'], 'wpnonce-commission-ged-nonce' )) {
        return $post_id;
    }

    // save meta
    // suppression des anciennes valeurs
    delete_post_meta($post_id,"_meta_ged_commission");
    //ajout des nouvelles valeurs saisies
    if(isset($_POST["commission-ecpged"]) && !empty($_POST["commission-ecpged"])) {
        foreach ($_POST["commission-ecpged"] as $id_ged) {
            if( $id_ged != "0" ) {
                add_post_meta($post_id, "_meta_ged_commission", intval($id_ged) );
            }
        }
    }

}
add_action('save_post','save_metabox_ged_commission');

/************************************
 * Définition du CPT événement
 *
 ***********************************/
/**
 * Fonction qui gà¨re l'affichage de la metabox listant les calendriers disponibles
 * 
 */
function build_metabox_event_calendar( $post ) {

    $selected_cal= wp_get_post_parent_id($post->ID);

    $all_calendar = get_posts( array(
        'post_type' => 'ecp_calendrier',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
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
                        $('#list-members-notif').empty().append("<p>Le calendrier n'est lié à  aucune instance</p>");
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
    if ( 'ecp_event' != get_post_type( $post_id ) ) {
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
        //to avoid to saveevent meta data twice
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

    <p>Liste des membres à  notifier</p>
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
                        <p>Le calendrier <?php echo $calendar->post_title; ?> n'est lié à  aucune instance</p>
                    <?php
                }
            }
        ?>

        </div>
    </div>
    <?php
}

/**
 * Fonction qui définit les colonnes à  afficher dans la liste des événements
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
            $eventcats = get_the_terms($post->ID, "ecp_eventcategory");
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
 * Fonction qui met à  jour les messages de mise à  jour spécifique à  l'événement
 *
 * @global type $post
 * @global type $post_ID
 * @param type $messages
 * @return type
 */
function ecp_event_updated_messages( $messages ) {

  global $post, $post_ID;

  $messages['ecp_event'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( "Evénement mis à  jour. <a href='%s'>Voir l'événement</a>", esc_url( get_permalink($post_ID) ) ),
    2 => 'Champ mis à  jour',
    3 => 'Champ mis à  jour',
    4 => 'Evenément mis à  jour',
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

  return $messages;
}
add_filter('post_updated_messages', 'ecp_event_updated_messages');

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
 * Fonction qui gà¨re la sauvegarde des données de l'événement
 * 
 * @param type $post_id
 * @return type
 */
function save_metabox_event_data( $post_id ) {
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

/********************************************
 * Définition du CPT Message
 *
 *******************************************/
/**
 * Fonction qui gère l'affichage de la metabox pour rattaché le message à  une messagerie
 *
 * @param type $post
 */
function build_metabox_messagerie_message( $post ) {
    $selected_messagerie= wp_get_post_parent_id($post->ID);

    $all_messagerie = get_posts( array(
        'post_type' => 'ecp_messagerie',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
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
 * Fonction qui gà¨re la sauvegarde de la metabox indiquant la messegerie à  laquelle est lié le message
 *
 * @param int $post_id
 * @return void
 */
function save_metabox_messagerie_message( $post_id ) {

    // only run this for event
    if ( 'ecp_message' != get_post_type( $post_id ) ) {
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
 * Fonction pour sélectionner les membres à  notifier pour un message
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

/********************************************
 * Définition du CPT GED
 *
 *******************************************/
function build_metabox_documents_ged( $post ) {
    $list_documents=getdocuments_of_ged($post->ID);
    ?>

    <div class="metabox-list-doc-ged">
        <?php
        if(empty($list_documents)) {
            echo "Aucun document";
        } else {
            $currentday = null;
            $nb_docs=count($list_documents);
            $counter=0;

            foreach($list_documents as $doc) {
                $docdate= get_the_time('F Y', $doc->ID);
                $author = get_user_by('ID',$doc->post_author);

                //init
                if($currentday == null ) {
                    ?>
                    <div class="item-docs-block">
                    <?php
                }
                //date change mais pas init
                if($docdate != $currentday && $currentday != null) {
                    ?>
                    </div>
                    <div class="item-docs-block">
                    <?php
                }
                //init ou date change
                if($currentday == null || $docdate != $currentday) {
                    ?>
                    <div class="item-doc-date">
                        <span><?php echo $docdate; ?></span>
                    </div>
                    <?php
                }
                ?>

                <div class="item-doc">
                    <span><a href="<?php echo get_edit_post_link( $doc->ID ); ?>"><?php echo $doc->post_title; ?></a></span>
                    <span>ajouté par <?php echo $author->last_name." ".$author->first_name; ?> </span>
                    <span>le <?php echo $doc->post_date; ?></span>
                </div>

                <?php
                if($counter == $nb_docs) {
                    ?>
                    </div>
                    <?php
                }

                $currentday=$docdate;
                $counter++;
            }
        }


    ?>
    </div>
    <?php
}
/**
 * Fonction qui définit les colonnes à afficher dans la liste des GED
 *
 * @param array $columns
 * @return string
 */
function ecp_ged_edit_columns( $columns ) {

    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Nom de la GED",
        "ecp_col_doc_pcommission" => "Liée à la commission",
        "date" => "Date"
    );

    return $columns;
}
add_filter ("manage_edit-ecp_ged_columns", "ecp_ged_edit_columns");

/**
 * Fonction qui définit le contenu des colonnes affichées dans la liste des GED
 *
 * @global type $post
 * @param type $column
 */
function ecp_ged_custom_columns( $column, $post_id ) {

    $parent_instance=get_parent_instance_of_ged($post_id);

    switch ($column)
    {
        case "ecp_col_doc_pcommission" :
            if(!empty($parent_instance)) {
                echo $parent_instance->post_title;
            } else {
                echo "Aucune Commission";
            }
        break;
    }
}
add_action ("manage_posts_custom_column", "ecp_ged_custom_columns", 10, 2);

/********************************************
 * Définition du CPT Document
 *
 *******************************************/
/**
 * Fonction qui gère l'affichage de la metabox pour rattacher le document à une ged
 *
 * @param WP_Post $post
 */
function build_metabox_ged_document( $post ) {
    $selected_ged= wp_get_post_parent_id($post->ID);

    $all_ged = get_posts( array(
        'post_type' => 'ecp_ged',
        'posts_per_page' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
    ?>

    <div class="metabox-ged-document">
        <div>
            <input type="hidden" name="ged_document_nonce" value="<?php echo wp_create_nonce( 'wp_ged_document_nonce' ); ?>" />
            <p class="error-field-required hidden-element">Vous devez sélectionner une GED.</p>
            <select name="ecpged" id="ecpged" class="required">
                <option value="0">-- Sélectionner une GED -- </option>
                <?php
                foreach ( $all_ged as $ged ) {
                    $instance=get_parent_instance_of_ged($ged->ID);
                    $id_instance="0";
                    if($instance != "") {
                        $id_instance = $instance->ID;
                    }
                    ?>
                    <option data-instance="<?php echo $id_instance; ?>" value="<?php echo $ged->ID; ?>" <?php echo  $ged->ID == $selected_ged  ? ' selected="selected"' : ''; ?>><?php echo $ged->post_title; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <?php $ajax_nonce = wp_create_nonce( 'nonce_change_select' ); ?>
        <script type="text/javascript">// <![CDATA[
            jQuery(function($) {
                $('#ecpged').change(function(){
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
                        $('#list-members-notif').empty().append("<p>La GED n'est liée à aucune instance</p>");
                    }
                });
            });
            // ]]>
        </script>
    </div>
    <?php
}

/**
 * Fonction qui gère la sauvegarde de la metabox indiquant la ged à laquelle est lié le document
 *
 * @param int $post_id
 * @return void
 */
function save_metabox_ged_document( $post_id ) {

    // only run this for event
    if ( 'ecp_document' != get_post_type( $post_id ) ) {
        return $post_id;
    }

    // verify nonce
    if ( empty( $_POST['ged_document_nonce'] ) || !wp_verify_nonce( $_POST['ged_document_nonce'], 'wp_ged_document_nonce' ) ) {
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
    remove_action('save_post', 'save_metabox_ged_document',10);
    //to avoid duplicate send notification
    remove_action('save_post', 'composant_published_notification',12);
    //to avoid duplicate file upload process
    remove_action( 'save_post', 'save_metabox_upload_document', 11 );

    // call wp_update_post update to save value, which calls save_post again
    if(isset($_POST['ecpged']) && $_POST['ecpged'] != "0") {
        wp_update_post(
            array(
                'ID' => $post_id,
                'post_parent' => intval($_POST['ecpged'])
            )
        );
    }

    // re-hook the save_post action
    add_action('save_post', 'save_metabox_ged_document',10);
    //???
    remove_action('save_post', 'composant_published_notification',12);
    // re-hook file upload process
    add_action( 'save_post', 'save_metabox_upload_document', 11 );

}
add_action( 'save_post', 'save_metabox_ged_document', 10 );

/**
 * Fonction gérant l'affichage de la metabox permettant l'upload de fichier
 * 
 * @param type $post
 */
function build_metabox_upload_document( $post ){
    
    // See if there's an existing file. (We're associating file with CPT Document by saving the file's 'attachment id' as a post meta value)
    // Incidentally, this is also how you'd find any uploaded files for display on the frontend.
    $existing_file = get_post_meta($post->ID,'_ecp_document_file_attached', true);

    ?>
    <input type="hidden" name="upload_document_nonce" value="<?php echo wp_create_nonce( 'wp_upload_document_nonce' ); ?>" />
    <?php

    // If there is an existing file, show it
    if( !empty($existing_file) ) {
        ?>
        <div>
            <?php
            //$arr_existing_file = wp_get_attachment_image_src($existing_file_id, 'large');
            //$existing_file_url = $arr_existing_file[0];
            ?>
            <!-- <img src="<?php echo $existing_file_url; ?>" /> -->
            Fichier : <?php echo $existing_file; ?>
        </div>
    <?php

    }

    //affichage de l'id du document
   /* if($existing_file_id) {
        ?>
        <div>Attached Image ID: <?php echo $existing_file_id; ?></div>
        <?php
    }*/

    //envoi d'un fichier
    if( !empty($existing_file) ) {
        $label="Changer le fichier";
    } else {
        $label="Ajouter un fichier";
    }
    echo $label;
    ?>
    <input type="file" name="ecp_document_file" id="ecp_document_file" />

    <?php
    // See if there's a status message to display (we're using this to show errors during the upload process, though we should probably be using the WP_error class)
    $status_message = get_post_meta($post->ID,'_ecp_document_file_upload_feedback', true);

    // Show an error message if there is one
    if($status_message) {
        ?>
        <div class="upload_status_message">
            <?php echo $status_message; ?>
        </div>
    <?php
    }

}

/**
 * Fonction gérant le traitement et la sauvegarde de la métabox permettant l'upload de fichier
 *
 * @param int $post_id
 */
function save_metabox_upload_document($post_id) {

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // only run this for document
    if ( 'ecp_document' != get_post_type( $post_id ) ) {
        return $post_id;
    }

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    // verify nonce
    if ( empty( $_POST['upload_document_nonce'] ) || !wp_verify_nonce( $_POST['upload_document_nonce'], 'wp_upload_document_nonce' ) ) {
        return $post_id;
    }

    //set custom upload dir (used for wp_handle_ipload)
    add_filter('upload_dir','ecp_document_upload_dir' );


    // If the upload field has a file in it
    if(isset($_FILES['ecp_document_file']) && ($_FILES['ecp_document_file']['size'] > 0)) {

        // Get the type of the uploaded file. This is returned as "type/extension"
        $arr_file_type = wp_check_filetype(basename($_FILES['ecp_document_file']['name']));
        $uploaded_file_type = $arr_file_type['type'];

        // Set an array containing a list of acceptable formats : jpg,jpeg,gif,png,bmp,doc,docx,pdf,pptx,ppt,xls,xlsx,csv,odt,odp,ods
        $allowed_file_types = array('image/jpeg','image/gif','image/png','image/bmp',
            'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.ms-powerpoint',
            'application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'application/vnd.oasis.opendocument.text','application/vnd.oasis.opendocument.presentation','application/vnd.oasis.opendocument.spreadsheet');

        // If the uploaded file is the right format
        if( in_array($uploaded_file_type, $allowed_file_types) ) {

            // Options array for the wp_handle_upload function. 'test_upload' => false
            $upload_overrides = array( 'test_form' => false );

            // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
            $uploaded_file = wp_handle_upload($_FILES['ecp_document_file'], $upload_overrides);

            // If the wp_handle_upload call returned a local path for the image
            if(isset($uploaded_file['file'])) {

                // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
                //$file_name_and_location = $uploaded_file['file'];

                // Generate a title for the image that'll be used in the media library
                //$file_title_for_media_library = 'uploaded-document';

                // Set up options array to add this file as an attachment
                /*$attachment = array(
                                'post_mime_type' => $uploaded_file_type,
                                'post_title' => 'Document ' . addslashes(basename($file_name_and_location)),
                                'post_content' => '',
                                'post_status' => 'inherit'
                                );*/

                // Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails.
                // If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
                /*$attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                wp_update_attachment_metadata($attach_id,  $attach_data);*/

                // Before we update the post meta, trash any previously uploaded file for this post.
                // You might not want this behavior, depending on how you're using the uploaded files.
                /*$existing_uploaded_file = (int) get_post_meta($post_id,'_ecp_document_file_attached', true);
                if(is_numeric($existing_uploaded_file)) {
                    wp_delete_attachment($existing_uploaded_file);
                }*/

                // Now, update the post meta to associate the new image with the post
                update_post_meta($post_id,'_ecp_document_file_attached',esc_url($uploaded_file['url']));

                // delete the feedback meta, since the upload was successful
                delete_post_meta($post_id,'_ecp_document_file_upload_feedback');
            } else {
                // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
                $upload_feedback = "Il y a eu un problème lors de l'upload";
                update_post_meta($post_id,'_ecp_document_file_upload_feedback',$upload_feedback);
            }

        } else { 
            // wrong file type
            $upload_feedback = 'Les fichiers autorisés sont : jpg, jpeg, gif, png, bmp, doc, docx, ppt, pptx, xls, xlsx, pdf, csv, odt, odp, ods';
            update_post_meta($post_id,'_ecp_document_file_upload_feedback',$upload_feedback);

        }

    } else {
        //si pas de fichier à uploader vérification du fichier existant
        $existing_file = get_post_meta($post_id,'_ecp_document_file_attached', true);
        if(!empty($existing_file)) {
            //s'il y a un fichier existant => récupération de ses infos
            $file_name=basename($existing_file);
            $subdir=basename(dirname($existing_file));
            //NB : un document doit etre lié à une GED (champs requis dans la metabox)
            $doc_ged_id=wp_get_post_parent_id($post_id);

            //si le fichier existant n'est pas dans le dossier de la GED actuelle du CPT document
            //il est déplacé dans le dossier de la GED actuelle
            if($subdir != $doc_ged_id) {
                $prev_dir=WP_CONTENT_DIR . '/ged/'.$subdir."/".$file_name;
                $new_dir=WP_CONTENT_DIR . '/ged/'.$doc_ged_id."/".$file_name;
                $new_url=WP_CONTENT_URL . '/ged/'.$doc_ged_id."/".$file_name;
                $status_copy=rename($prev_dir,$new_dir);
                if($status_copy==true){
                    //si déplacement de fichier ok, mise à jour de la meta avec la nouvelle url
                    update_post_meta($post_id,'_ecp_document_file_attached',esc_url($new_url));
                }
            }
        }
    }

    //custom upload dir removed
    remove_filter('upload_dir','ecp_document_upload_dir' );
}
add_action( 'save_post', 'save_metabox_upload_document', 11 );


/**
 * Fonction permettant de définir un upload dir perso pour les documents
 *
 * @param type $default_dir
 * @return string
 */
function ecp_document_upload_dir( $default_dir ) {
    //!!!!!!!!!!!PB objet post absent avec un custom media uploader
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    global $post;

    /*if ( ! isset( $_POST['post_id'] ) || $_POST['post_id'] < 0 ) {
        return $default_dir;
    }*/

    if ( 'ecp_document' != get_post_type( $post->ID ) ) {
        return $default_dir;
    }

    //récupération de la ged du document
    $ged = wp_get_post_parent_id($post->ID);


    $dir = WP_CONTENT_DIR . '/ged';
    $url = WP_CONTENT_URL . '/ged';

    $bdir = $dir;
    $burl = $url;

    $subdir='/default';
    if( !empty($ged) ) {
        $subdir = '/'.$ged;
    }
    

    $dir .= $subdir;
    $url .= $subdir;

    $custom_dir = array(
        'path' => $dir,
        'url' => $url,
        'subdir' => $subdir,
        'basedir' => $bdir,
        'baseurl' => $burl,
        'error' => false,
    );

    return $custom_dir;
}

/**
 * Fonction pour sélectionner les membres à notifier lors de l'ajout/mise à jour d'un document
 *
 */
function build_metabox_notification_document( $post ) {
    //récupération de l'instance à laquelle est lié le document (via la ged)
    $instance="";
    ?>

    <p>Liste des membres à notifier</p>
    <div class="ecp-document-notification" >
        <div id="list-members-notif">
            <?php
            if( empty($post->post_parent) ) {
                ?>
                <p>Le document n'est lié à aucune GED</p>
                <?php
            } else {
                $instance = get_parent_instance_of_ged($post->post_parent);
                $ged = get_post($post->post_parent);
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
                        <p>La GED <?php echo $ged->post_title; ?> n'est liée à aucune instance</p>
                    <?php
                }
            }
        ?>

        </div>
    </div>
    <?php
}

/**
 * Fonction qui définit les colonnes à afficher dans la liste des documents
 *
 * @param array $columns
 * @return string
 */
function ecp_document_edit_columns( $columns ) {

    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Titre du document",
        "ecp_col_doc_pged" => "Lié à la GED",
        "ecp_col_doc_url" => "Chemin du fichier",
        "ecp_col_doc_type" => "Type",
        "author" => "Auteur",
        "date" => "Date"
    );

    return $columns;
}
add_filter ("manage_edit-ecp_document_columns", "ecp_document_edit_columns");

/**
 * Fonction qui définit le contenu des colonnes affichées dans la liste des événements
 *
 * @global type $post
 * @param type $column
 */
function ecp_document_custom_columns( $column, $post_id ) {

    $custom = get_post_custom($post_id);
    $post=get_post($post_id);

    switch ($column)
    {
        case "ecp_col_doc_type":
            $file_infos=wp_check_filetype($custom["_ecp_document_file_attached"][0]);
            echo $file_infos['ext'];
        break;
        case "ecp_col_doc_url":
            $url=$custom["_ecp_document_file_attached"][0];
            echo $url;
        break;
        case "ecp_col_doc_pged" :
            if(!empty($post->post_parent)) {
                $ged = get_post($post->post_parent);
                echo $ged->post_title;
            } else {
                echo "Aucune GED";
            }
        break;
    }
}
add_action ("manage_posts_custom_column", "ecp_document_custom_columns", 10, 2);

/**********************************************************
 * Fonctions qui se chargent d'envoyer un mail aux utilisateurs sélectionnés lorsque
 * le message/l'événement/le document est nouvellement publié ou mis à jour
 * 
 ***************************************************************************************/
add_action('transition_post_status','extranetcp_msg_notification',10,3);
function extranetcp_msg_notification($new_status, $old_status, $post) {
    //si on est à la publication du message on ajoute l'envoi de la notification lors de l'enregistrement du message
    // l'envoi est fait après l'enregistrement de la metabox permettant de choisir la messagerie à laquelle liée le message
    if ( 'ecp_message' == get_post_type( $post->ID ) || 'ecp_event' == get_post_type( $post->ID ) || 'ecp_document' == get_post_type( $post->ID )) {
        if ( 'publish' == $new_status ) { //&& $old_status !== 'publish'
            add_action('save_post','composant_published_notification',12);
        }
    }
}
function composant_published_notification( $post_id ) {
    //check post type
    if ( 'ecp_document' != get_post_type( $post_id ) && 'ecp_message' == get_post_type( $post_id ) && 'ecp_event' == get_post_type( $post_id )) {
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

    $mail_users_to_notify=[];
    if(!empty($_POST['id_members_notif'])) {
        foreach($_POST['id_members_notif'] as $id_user) {
            $user=get_userdata($id_user);
            $mail_users_to_notify[]=$user->user_email;
        }

        if( 'ecp_message' == get_post_type( $post_id ) ) {
            $subject="Extranet - un nouveau message a été déposé";
        } elseif ( 'ecp_event' == get_post_type( $post_id ) ) {
            $subject="Extranet - un nouvel événement a été déposé";
        } elseif ( 'ecp_document' == get_post_type( $post_id ) ) {
            $subject="Extranet - un nouveau document a été déposé";
        }
        $message = get_message_notification_content($post_id);
        //add_post_meta($post_id, '_event_user_test',implode(',',$mail_users_to_notify));
        //if message and recipients => sending
        if( !empty($message) && !empty($mail_users_to_notify) ) {
            add_filter( 'wp_mail_content_type', 'extranetecp_change_mail_type' );
            foreach ($mail_users_to_notify as $recipient) {
                wp_mail($recipient,$subject,$message);
            }
            remove_filter( 'wp_mail_content_type', 'extranetecp_change_mail_type' );
        }
    }
}



