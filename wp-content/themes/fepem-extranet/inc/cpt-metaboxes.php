<?php
/* 
 * File to declare custom posts type and metaboxes
 */


/***************************************************
 * Ajout des custom post type et custom taxonomies
 *
 * CT :
 *  - catégorie d'événement
 *  - type de participant
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
 * 
 **************************************************/
function extranetcp_cpt_ct() {

    //taxonomie category pour les événements
    register_taxonomy(
        'ecp_tax_event_category',
        array('ecp_event','ecp_fevent'),
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
                'edit_item' => "Editer la catégorie d'événements",
                'update_item' =>  "Mettre à jour la catégorie d'événement",
                'add_new_item' => "Ajouter une catégorie d'événement",
                'new_item_name' =>  "Nouvelle catégorie d'événements",
                'separate_items_with_commas' => "Séparer les catégories d'évéments par des virgules",
                'add_or_remove_items' => "Ajouter ou supprimer une catégorie d'événement",
                'choose_from_most_used' =>  "Choisir parmi les catégories d'événements les plus utilisées",
            ),
            'capabilities' => array (
                    'manage_terms' => 'manage_options',
                    'edit_terms' => 'manage_options',
                    'delete_terms' => 'manage_options',
                    'assign_terms' => 'assign_tax_cat_event'
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'public' => true,
        )
    );

    //taxonomie type de public pour les instances, les messageries, les GED
    register_taxonomy(
        'ecp_tax_type_participant',
        array('commission','fcommission','ecp_messagerie','ecp_fmessagerie','ecp_ged','ecp_fged'),
        array(
            'label' => 'Type de participant',
            'labels' => array(
                'name' => "Types de participant",
                'singular_name' => "Type de participant",
                'search_items' =>  "Rechercher parmi les types de participant",
                'popular_items' => "Types de participant les plus utilisées",
                'all_items' => "Tous les types de participant",
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => "Editer le type de participant",
                'update_item' =>  "Mettre à jour le type de participant",
                'add_new_item' => "Ajouter un type de participant",
                'new_item_name' =>  "Nouveau type de participant",
                'separate_items_with_commas' => "Séparer les types de participant par des virgules",
                'add_or_remove_items' => "Ajouter ou supprimer un type de participant",
                'choose_from_most_used' =>  "Choisir parmi les types de participant les plus utilisées",
            ),
            'capabilities' => array (
                    'manage_terms' => 'manage_options',
                    'edit_terms' => 'manage_options',
                    'delete_terms' => 'manage_options',
                    'assign_terms' => 'assign_tax_public_type'
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'public' => true,
        )
    );

    //instances
    ////////////////////
    $labels_commission= array(
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
            );
    $labels_commission_fepem=$labels_commission;
    $labels_commission_fepem['name']="Commissions Fepem";
    $labels_commission_fepem['singular_name']="Commission Fepem";
    register_post_type(
        'commission',
        array(
            'label' => 'Commissions',
            'labels' => $labels_commission,
            'public' => true,
            'capability_type' => 'commission',
            'capabilities' => array(
				'publish_posts' => 'publish_commissions',
				'edit_posts' => 'edit_commissions',
				'edit_others_posts' => 'edit_others_commissions',
				'delete_posts' => 'delete_commissions',
				'delete_others_posts' => 'delete_others_commissions',
				'read_private_posts' => 'read_private_commissions',
				'edit_post' => 'edit_commission',
				'delete_post' => 'delete_commission',
				'read_post' => 'read_commission',
			),
            'supports' => array(
                'title',
                'editor',
                'author'
            ),
            'has_archive' => false
            //'menu_icon' => get_template_directory_uri() . "/images/balloon--pencil.png" --> trouver la bonne image
        )
    );
    register_post_type(
        'fcommission',
        array(
            'label' => 'Commissions',
            'labels' => $labels_commission_fepem,
            'public' => true,
            'capability_type' => 'fcommission',
            'capabilities' => array(
				'publish_posts' => 'publish_fcommissions',
				'edit_posts' => 'edit_fcommissions',
				'edit_others_posts' => 'edit_others_fcommissions',
				'delete_posts' => 'delete_fcommissions',
				'delete_others_posts' => 'delete_others_fcommissions',
				'read_private_posts' => 'read_private_fcommissions',
				'edit_post' => 'edit_fcommission',
				'delete_post' => 'delete_fcommission',
				'read_post' => 'read_fcommission',
			),
            'supports' => array(
                'title',
                'editor',
                'author'
            ),
            'has_archive' => false
            //'menu_icon' => get_template_directory_uri() . "/images/balloon--pencil.png" --> trouver la bonne image
        )
    );

    

    //calendriers
    //////////////
    $labels_calendrier=array(
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

            );
    $labels_calendrier_fepem=$labels_calendrier;
    $labels_calendrier_fepem['name']="Calendriers Fepem";
    $labels_calendrier_fepem['name']="Calendriers Fepem";
    register_post_type(
        'ecp_calendrier',
        array(
            'label' => 'calendriers',
            'labels' => $labels_calendrier,
            'public' => true,
            'capability_type' => 'ecp_calendrier',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_calendriers',
				'edit_posts' => 'edit_ecp_calendriers',
				'edit_others_posts' => 'edit_others_ecp_calendriers',
				'delete_posts' => 'delete_ecp_calendriers',
				'delete_others_posts' => 'delete_others_ecp_calendriers',
				'read_private_posts' => 'read_private_ecp_calendriers',
				'edit_post' => 'edit_ecp_calendrier',
				'delete_post' => 'delete_ecp_calendrier',
				'read_post' => 'read_ecp_calendrier',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    register_post_type(
        'ecp_fcalendrier',
        array(
            'label' => 'calendriers',
            'labels' => $labels_calendrier_fepem,
            'public' => true,
            'capability_type' => 'ecp_fcalendrier',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fcalendriers',
				'edit_posts' => 'edit_ecp_fcalendriers',
				'edit_others_posts' => 'edit_others_ecp_fcalendriers',
				'delete_posts' => 'delete_ecp_fcalendriers',
				'delete_others_posts' => 'delete_others_ecp_fcalendriers',
				'read_private_posts' => 'read_private_ecp_fcalendriers',
				'edit_post' => 'edit_ecp_fcalendrier',
				'delete_post' => 'delete_ecp_fcalendrier',
				'read_post' => 'read_ecp_fcalendrier',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );

    //événements
    /////////////////
    $labels_event=array(
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
            );
    $labels_event_fepem=$labels_event;
    $labels_event_fepem['name']="Evénements Fepem";
    $labels_event_fepem['singular_name']="Evénement Fepem";
    register_post_type(
        'ecp_event',
        array(
            'label' => 'événements',
            'labels' => $labels_event,
            'public' => true,
            'capability_type' => 'ecp_event',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_events',
				'edit_posts' => 'edit_ecp_events',
				'edit_others_posts' => 'edit_others_ecp_events',
				'delete_posts' => 'delete_ecp_events',
				'delete_others_posts' => 'delete_others_ecp_events',
				'read_private_posts' => 'read_private_ecp_events',
				'edit_post' => 'edit_ecp_event',
				'delete_post' => 'delete_ecp_event',
				'read_post' => 'read_ecp_event',
			),
            'supports'=> array('title', 'author', 'editor' ),
            'taxonomies' => array( 'ecp_tax_event_category')
            //'show_in_menu' => 'edit.php?post_type=ecp_calendrier'
        )
    );
    register_post_type(
        'ecp_fevent',
        array(
            'label' => 'événements',
            'labels' => $labels_event_fepem,
            'public' => true,
            'capability_type' => 'ecp_fevent',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fevents',
				'edit_posts' => 'edit_ecp_fevents',
				'edit_others_posts' => 'edit_others_ecp_fevents',
				'delete_posts' => 'delete_ecp_fevents',
				'delete_others_posts' => 'delete_others_ecp_fevents',
				'read_private_posts' => 'read_private_ecp_fevents',
				'edit_post' => 'edit_ecp_fevent',
				'delete_post' => 'delete_ecp_fevent',
				'read_post' => 'read_ecp_fevent',
			),
            'supports'=> array('title', 'author', 'editor' ),
            'taxonomies' => array( 'ecp_tax_event_category')
            //'show_in_menu' => 'edit.php?post_type=ecp_calendrier'
        )
    );

    //messageries
    ////////////////////////////////////
    $labels_messagerie=array(
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

            );
    $labels_messagerie_fepem=$labels_messagerie;
    $labels_messagerie_fepem['name']="Messageries Fepem";
    $labels_messagerie_fepem['singular_name']="Messgaerie Fepem";
    register_post_type(
        'ecp_messagerie',
        array(
            'label' => 'messageries',
            'labels' => $labels_messagerie,
            'public' => true,
            'capability_type' => 'ecp_messagerie',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_messageries',
				'edit_posts' => 'edit_ecp_messageries',
				'edit_others_posts' => 'edit_others_ecp_messageries',
				'delete_posts' => 'delete_ecp_messageries',
				'delete_others_posts' => 'delete_others_ecp_messageries',
				'read_private_posts' => 'read_private_ecp_messageries',
				'edit_post' => 'edit_ecp_messagerie',
				'delete_post' => 'delete_ecp_messagerie',
				'read_post' => 'read_ecp_messagerie',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    register_post_type(
        'ecp_fmessagerie',
        array(
            'label' => 'messageries',
            'labels' => $labels_messagerie_fepem,
            'public' => true,
            'capability_type' => 'ecp_fmessagerie',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fmessageries',
				'edit_posts' => 'edit_ecp_fmessageries',
				'edit_others_posts' => 'edit_others_ecp_fmessageries',
				'delete_posts' => 'delete_ecp_fmessageries',
				'delete_others_posts' => 'delete_others_ecp_fmessageries',
				'read_private_posts' => 'read_private_ecp_fmessageries',
				'edit_post' => 'edit_ecp_fmessagerie',
				'delete_post' => 'delete_ecp_fmessagerie',
				'read_post' => 'read_ecp_fmessagerie',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );

    //messages
    ///////////////////////
    $labels_message=array(
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

            );
    $labels_message_fepem=$labels_message;
    $labels_message_fepem['name']="Messages Fepem";
    $labels_message_fepem['singular_name']="Message Fepem";
    register_post_type(
        'ecp_message',
        array(
            'label' => 'messages',
            'labels' => $labels_message,
            'public' => true,
            'capability_type' => 'ecp_message',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_messages',
				'edit_posts' => 'edit_ecp_messages',
				'edit_others_posts' => 'edit_others_ecp_messages',
				'delete_posts' => 'delete_ecp_messages',
				'delete_others_posts' => 'delete_others_ecp_messages',
				'read_private_posts' => 'read_private_ecp_messages',
				'edit_post' => 'edit_ecp_message',
				'delete_post' => 'delete_ecp_message',
				'read_post' => 'read_ecp_message',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author', 'thumbnail','comments')
        )
    );
    register_post_type(
        'ecp_fmessage',
        array(
            'label' => 'messages',
            'labels' => $labels_message_fepem,
            'public' => true,
            'capability_type' => 'ecp_fmessage',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fmessages',
				'edit_posts' => 'edit_ecp_fmessages',
				'edit_others_posts' => 'edit_others_ecp_fmessages',
				'delete_posts' => 'delete_ecp_fmessages',
				'delete_others_posts' => 'delete_others_ecp_fmessages',
				'read_private_posts' => 'read_private_ecp_fmessages',
				'edit_post' => 'edit_ecp_fmessage',
				'delete_post' => 'delete_ecp_fmessage',
				'read_post' => 'read_ecp_fmessage',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author', 'thumbnail','comments')
        )
    );

    //ged
    //////////////////////////
    $labels_ged=array(
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
            );
    $labels_ged_fepem=$labels_ged;
    $labels_ged_fepem['name']="GED Fepem";
    $labels_ged_fepem['singular_name']="GED Fepem";
    register_post_type(
        'ecp_ged',
        array(
            'label' => 'ged',
            'labels' => $labels_ged,
            'public' => true,
            'capability_type' => 'ecp_ged',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_geds',
				'edit_posts' => 'edit_ecp_geds',
				'edit_others_posts' => 'edit_others_ecp_geds',
				'delete_posts' => 'delete_ecp_geds',
				'delete_others_posts' => 'delete_others_ecp_geds',
				'read_private_posts' => 'read_private_ecp_geds',
				'edit_post' => 'edit_ecp_ged',
				'delete_post' => 'delete_ecp_ged',
				'read_post' => 'read_ecp_ged',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    register_post_type(
        'ecp_fged',
        array(
            'label' => 'ged',
            'labels' => $labels_ged_fepem,
            'public' => true,
            'capability_type' => 'ecp_fged',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fgeds',
				'edit_posts' => 'edit_ecp_fgeds',
				'edit_others_posts' => 'edit_others_ecp_fgeds',
				'delete_posts' => 'delete_ecp_fgeds',
				'delete_others_posts' => 'delete_others_ecp_fgeds',
				'read_private_posts' => 'read_private_ecp_fgeds',
				'edit_post' => 'edit_ecp_fged',
				'delete_post' => 'delete_ecp_fged',
				'read_post' => 'read_ecp_fged',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );

    //document
    /////////////////////
    $labels_document=array(
                    'name' => 'Documents',
                    'singular_name' => 'Document',
                    'all_items' => 'Tous les documents',
                    'add_new' => "Ajouter un document",
                    'add_new_item' => 'Ajouter un document',
                    'edit_item' => 'Editer le document',
                    'new_item' => 'Nouveau document',
                    'view_item' => 'Voir le document',
                    'search_items' => 'Rechercher parmi les documents',
                    'not_found' => 'Pas de document trouvé',
                    'not_found_in_trash'=> 'Pas de document dans la corbeille'

            );
    $labels_document_fepem=$labels_document;
    $labels_document_fepem['name']='Documents Fepem';
    $labels_document_fepem['singular_name']='Document Fepem';
    register_post_type(
        'ecp_document',
        array(
            'label' => 'document',
            'labels' => $labels_document,
            'public' => true,
            'capability_type' => 'ecp_document',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_documents',
				'edit_posts' => 'edit_ecp_documents',
				'edit_others_posts' => 'edit_others_ecp_documents',
				'delete_posts' => 'delete_ecp_documents',
				'delete_others_posts' => 'delete_others_ecp_documents',
				'read_private_posts' => 'read_private_ecp_documents',
				'edit_post' => 'edit_ecp_document',
				'delete_post' => 'delete_ecp_document',
				'read_post' => 'read_ecp_document',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    register_post_type(
        'ecp_fdocument',
        array(
            'label' => 'document',
            'labels' => $labels_document_fepem,
            'public' => true,
            'capability_type' => 'ecp_fdocument',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_fdocuments',
				'edit_posts' => 'edit_ecp_fdocuments',
				'edit_others_posts' => 'edit_others_ecp_fdocuments',
				'delete_posts' => 'delete_ecp_fdocuments',
				'delete_others_posts' => 'delete_others_ecp_fdocuments',
				'read_private_posts' => 'read_private_ecp_fdocuments',
				'edit_post' => 'edit_ecp_fdocument',
				'delete_post' => 'delete_ecp_fdocument',
				'read_post' => 'read_ecp_fdocument',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );


    
}
add_action('init', 'extranetcp_cpt_ct');

/****************
 * Admin Menus
 *
 ****************/
function extranetcp_admin_menu() {

    //gestion des calendriers
    add_submenu_page('edit.php?post_type=ecp_calendrier', 'Liste des événements', 'Tous les événements', 'edit_others_ecp_events', 'edit.php?post_type=ecp_event');
    add_submenu_page('edit.php?post_type=ecp_calendrier', "Ajout d'un événement", 'Ajouter un événement', 'edit_others_ecp_events', 'post-new.php?post_type=ecp_event');
    add_submenu_page('edit.php?post_type=ecp_calendrier', "Catégorie d'événements", "Catégorie d'événements", 'manage_options', 'edit-tags.php?taxonomy=ecp_tax_event_category&post_type=ecp_event');
    remove_menu_page('edit.php?post_type=ecp_event');
    add_submenu_page('edit.php?post_type=ecp_fcalendrier', 'Liste des événements', 'Tous les événements', 'edit_others_ecp_fevents', 'edit.php?post_type=ecp_fevent');
    add_submenu_page('edit.php?post_type=ecp_fcalendrier', "Ajout d'un événement", 'Ajouter un événement', 'edit_others_ecp_fevents', 'post-new.php?post_type=ecp_fevent');
    add_submenu_page('edit.php?post_type=ecp_fcalendrier', "Catégorie d'événements", "Catégorie d'événements", 'manage_options', 'edit-tags.php?taxonomy=ecp_tax_event_category&post_type=ecp_fevent');
    remove_menu_page('edit.php?post_type=ecp_fevent');

    //gestion des messageries
    add_submenu_page('edit.php?post_type=ecp_messagerie', 'Liste des messages', 'Tous les messages', 'edit_others_ecp_messages', 'edit.php?post_type=ecp_message');
    add_submenu_page('edit.php?post_type=ecp_messagerie', "Ajout d'un message", 'Ajouter un message', 'edit_others_ecp_messages', 'post-new.php?post_type=ecp_message');
    add_submenu_page('edit.php?post_type=ecp_messagerie', "Liste des commentaires", 'Tous les commentaires', 'edit_others_ecp_messages', 'edit-comments.php');
    remove_menu_page('edit.php?post_type=ecp_message');
    add_submenu_page('edit.php?post_type=ecp_fmessagerie', 'Liste des messages', 'Tous les messages', 'edit_others_ecp_fmessages', 'edit.php?post_type=ecp_fmessage');
    add_submenu_page('edit.php?post_type=ecp_fmessagerie', "Ajout d'un message", 'Ajouter un message', 'edit_others_ecp_fmessages', 'post-new.php?post_type=ecp_fmessage');
    add_submenu_page('edit.php?post_type=ecp_fmessagerie', "Liste des commentaires", 'Tous les commentaires', 'edit_others_ecp_fmessages', 'edit-comments.php');
    remove_menu_page('edit.php?post_type=ecp_fmessage');

    //gestion des documents
    add_submenu_page('edit.php?post_type=ecp_ged', 'Liste des documents', 'Tous les documents', 'edit_others_ecp_documents', 'edit.php?post_type=ecp_document');
    add_submenu_page('edit.php?post_type=ecp_ged', "Ajout d'un document", 'Ajouter un document', 'edit_others_ecp_documents', 'post-new.php?post_type=ecp_document');
    remove_menu_page('edit.php?post_type=ecp_document');
    add_submenu_page('edit.php?post_type=ecp_fged', 'Liste des documents', 'Tous les documents', 'edit_others_ecp_fdocuments', 'edit.php?post_type=ecp_fdocument');
    add_submenu_page('edit.php?post_type=ecp_fged', "Ajout d'un document", 'Ajouter un document', 'edit_others_ecp_fdocuments', 'post-new.php?post_type=ecp_fdocument');
    remove_menu_page('edit.php?post_type=ecp_fdocument');

}
add_action('admin_menu', 'extranetcp_admin_menu');

/**************************************************
 * Déclaration des metabox utilisées avec les CPT
 *
 *************************************************/
function extranetcp_cpt_metaboxes() {
    // metabox pour indiquer un email de contact pour les cpt instance
    add_meta_box('meta_email_contact_commission', 'Email de contact de la commission', 'build_metabox_email_contact_commission', array('commission','fcommission'), 'side','default');
    //metabox membres pour les cpt commission
    add_meta_box('meta_members_commission', 'Membres de la commission', 'build_metabox_members_commission', array('commission','fcommission'), 'side', 'default');
    //metabox calendrier pour les cpt commission
    add_meta_box('meta_calendar_commission', 'Calendrier de la commission', 'build_metabox_calendar_commission', array('commission','fcommission'), 'side');
    //metabox messagerie pour les cpt commission
    add_meta_box('meta_messagerie_commission', 'Messagerie de la commission', 'build_metabox_messagerie_commission', array('commission','fcommission'), 'side');
    //metabox ged pour les cpt commission
    add_meta_box('meta_ged_commission', 'GED de la commission', 'build_metabox_ged_commission', array('commission','fcommission'), 'side');


    //metabox calendrier pour les cpt événement
    add_meta_box( 'metabox_event_parent_calendar', 'Calendrier de rattachement', 'build_metabox_event_calendar', array('ecp_event','ecp_fevent'), 'side' );
    //metabox pour les données du cpt événement
    add_meta_box('metabox_event_data', "Données de l'événement", 'build_metabox_event_data', array('ecp_event','ecp_fevent'));
    //metabox notification pour les cpt événement
    add_meta_box('metabox_event_notification', "Notification", 'build_metabox_notification_event', array('ecp_event','ecp_fevent'), 'normal','high');

    //metabox messagerie pour les cpt messages
    add_meta_box('metabox_messagerie_message', "Messagerie", 'build_metabox_messagerie_message', array('ecp_message','ecp_fmessage'), 'side');
    //metabox notification pour les cpt messages
    add_meta_box('metabox_notification_message', "Notification", 'build_metabox_notification_message', array('ecp_message','ecp_fmessage'),'normal','high');

    //metabox ged pour les cpt document
    add_meta_box('metabox_ged_document', "GED", 'build_metabox_ged_document', array('ecp_document','ecp_fdocument'), 'side');
    //metabox champ d'upload pour les cpt document
    add_meta_box('metabox_upload_document', "Fichier", 'build_metabox_upload_document', array('ecp_document','ecp_fdocument'), 'normal');
    // metabox notification pour les cpt document
    add_meta_box('metabox_notification_document',"Notification","build_metabox_notification_document",array("ecp_document",'ecp_fdocument'),'normal');

    //metabox pour afficher les documents associés pour les cpt GED
    add_meta_box('metabox_documents_ged',"Documents de la GED","build_metabox_documents_ged",array("ecp_ged","ecp_fged"),'normal');


}
add_action('add_meta_boxes','extranetcp_cpt_metaboxes');

require_once get_template_directory() ."/inc/cpt-metaboxes-commission.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-calendrier.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-messagerie.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-ged.php";

/**********************************************************
 * Fonctions qui se chargent d'envoyer un mail aux utilisateurs sélectionnés lorsque
 * le message/l'événement/le document est nouvellement publié ou mis à jour
 * 
 ***************************************************************************************/
add_action('transition_post_status','extranetcp_msg_notification',10,3);
function extranetcp_msg_notification($new_status, $old_status, $post) {
    //si on est à la publication du message on ajoute l'envoi de la notification lors de l'enregistrement du message
    // l'envoi est fait après l'enregistrement de la metabox permettant de choisir la messagerie à laquelle liée le message
    $authorized_post_type=["ecp_message","ecp_fmessage","ecp_event","ecp_fevent","ecp_document","ecp_fdocument"];
    if ( in_array(get_post_type( $post->ID ), $authorized_post_type ) ) {
        if ( 'publish' == $new_status ) { //&& $old_status !== 'publish'
            add_action('save_post','composant_published_notification',12);
        }
    }
}
function composant_published_notification( $post_id ) {
    //check post type
    $authorized_post_type=["ecp_message","ecp_fmessage","ecp_event","ecp_fevent","ecp_document","ecp_fdocument"];
    if ( !in_array(get_post_type( $post->ID ), $authorized_post_type ) ) {
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

        if( 'ecp_message' == get_post_type( $post_id ) || 'ecp_fmessage' == get_post_type( $post_id ) ) {
            $subject="Extranet - un nouveau message a été déposé";
        } elseif ( 'ecp_event' == get_post_type( $post_id ) || 'ecp_fevent' == get_post_type( $post_id ) ) {
            $subject="Extranet - un nouvel événement a été déposé";
        } elseif ( 'ecp_document' == get_post_type( $post_id ) || 'ecp_fdocument' == get_post_type( $post_id ) ) {
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



