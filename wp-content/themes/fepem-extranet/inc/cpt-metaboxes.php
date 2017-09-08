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
 *  - Commission x4
 *  - Calendrier x4
 *  - Evenements x4
 *  - Messagerie x4
 *  - Messages x4
 *  - GED x4
 *  - Documents x4
 *
 * 
 **************************************************/
function extranetcp_cpt_ct() {
    $list_cpt_event=get_cpt_event();
    $list_cpt_tax_type_public=get_cpt_tax_type_public();

    //taxonomie category pour les événements
    register_taxonomy(
        'ecp_tax_event_category',
        $list_cpt_event,
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
        $list_cpt_tax_type_public,
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
    $labels_commission_fepem=$labels_commission;
    $labels_commission_fepem['name']="Commissions Fepem";
    $labels_commission_fepem['singular_name']="Commission Fepem";
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
        )
    );
    $labels_commission_os=$labels_commission;
    $labels_commission_os['name']="Commissions OS";
    $labels_commission_os['singular_name']="Commission OS";
    register_post_type(
        'oscommission',
        array(
            'label' => 'Commissions',
            'labels' => $labels_commission_os,
            'public' => true,
            'capability_type' => 'oscommission',
            'capabilities' => array(
				'publish_posts' => 'publish_oscommissions',
				'edit_posts' => 'edit_oscommissions',
				'edit_others_posts' => 'edit_others_oscommissions',
				'delete_posts' => 'delete_oscommissions',
				'delete_others_posts' => 'delete_others_oscommissions',
				'read_private_posts' => 'read_private_oscommissions',
				'edit_post' => 'edit_oscommission',
				'delete_post' => 'delete_oscommission',
				'read_post' => 'read_oscommission',
			),
            'supports' => array(
                'title',
                'editor',
                'author'
            ),
            'has_archive' => false
        )
    );
    $labels_commission_cp=$labels_commission;
    $labels_commission_cp['name']="Commissions CP";
    $labels_commission_cp['singular_name']="Commission CP";
    register_post_type(
        'cpcommission',
        array(
            'label' => 'Commissions',
            'labels' => $labels_commission_cp,
            'public' => true,
            'capability_type' => 'oscommission',
            'capabilities' => array(
				'publish_posts' => 'publish_cpcommissions',
				'edit_posts' => 'edit_cpcommissions',
				'edit_others_posts' => 'edit_others_cpcommissions',
				'delete_posts' => 'delete_cpcommissions',
				'delete_others_posts' => 'delete_others_cpcommissions',
				'read_private_posts' => 'read_private_cpcommissions',
				'edit_post' => 'edit_cpcommission',
				'delete_post' => 'delete_cpcommission',
				'read_post' => 'read_cpcommission',
			),
            'supports' => array(
                'title',
                'editor',
                'author'
            ),
            'has_archive' => false
        )
    );
    /////////////////////////////////////////////////////////////////////////////////////////////

    //////////////
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
    $labels_calendrier_fepem=$labels_calendrier;
    $labels_calendrier_fepem['name']="Calendriers Fepem";
    $labels_calendrier_fepem['name']="Calendriers Fepem";
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
    $labels_calendrier_os=$labels_calendrier;
    $labels_calendrier_os['name']="Calendriers OS";
    $labels_calendrier_os['name']="Calendriers OS";
    register_post_type(
        'ecp_oscalendrier',
        array(
            'label' => 'calendriers',
            'labels' => $labels_calendrier_os,
            'public' => true,
            'capability_type' => 'ecp_oscalendrier',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_oscalendriers',
				'edit_posts' => 'edit_ecp_oscalendriers',
				'edit_others_posts' => 'edit_others_ecp_oscalendriers',
				'delete_posts' => 'delete_ecp_oscalendriers',
				'delete_others_posts' => 'delete_others_ecp_oscalendriers',
				'read_private_posts' => 'read_private_ecp_oscalendriers',
				'edit_post' => 'edit_ecp_oscalendrier',
				'delete_post' => 'delete_ecp_oscalendrier',
				'read_post' => 'read_ecp_oscalendrier',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    $labels_calendrier_cp=$labels_calendrier;
    $labels_calendrier_cp['name']="Calendriers CP";
    $labels_calendrier_cp['name']="Calendriers CP";
    register_post_type(
        'ecp_cpcalendrier',
        array(
            'label' => 'calendriers',
            'labels' => $labels_calendrier_cp,
            'public' => true,
            'capability_type' => 'ecp_cpcalendrier',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpcalendriers',
				'edit_posts' => 'edit_ecp_cpcalendriers',
				'edit_others_posts' => 'edit_others_ecp_cpcalendriers',
				'delete_posts' => 'delete_ecp_cpcalendriers',
				'delete_others_posts' => 'delete_others_ecp_cpcalendriers',
				'read_private_posts' => 'read_private_ecp_cpcalendriers',
				'edit_post' => 'edit_ecp_cpcalendrier',
				'delete_post' => 'delete_ecp_cpcalendrier',
				'read_post' => 'read_ecp_cpcalendrier',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    ///////////////////////////////////////////////////////////////////////////////

    /////////////////
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
    $labels_event_fepem=$labels_event;
    $labels_event_fepem['name']="Evénements Fepem";
    $labels_event_fepem['singular_name']="Evénement Fepem";
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
        )
    );
    $labels_event_os=$labels_event;
    $labels_event_os['name']="Evénements OS";
    $labels_event_os['singular_name']="Evénement OS";
    register_post_type(
        'ecp_osevent',
        array(
            'label' => 'événements',
            'labels' => $labels_event_os,
            'public' => true,
            'capability_type' => 'ecp_osevent',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_osevents',
				'edit_posts' => 'edit_ecp_osevents',
				'edit_others_posts' => 'edit_others_ecp_osevents',
				'delete_posts' => 'delete_ecp_osevents',
				'delete_others_posts' => 'delete_others_ecp_osevents',
				'read_private_posts' => 'read_private_ecp_osevents',
				'edit_post' => 'edit_ecp_osevent',
				'delete_post' => 'delete_ecp_osevent',
				'read_post' => 'read_ecp_osevent',
			),
            'supports'=> array('title', 'author', 'editor' ),
            'taxonomies' => array( 'ecp_tax_event_category')
        )
    );
    $labels_event_cp=$labels_event;
    $labels_event_cp['name']="Evénements CP";
    $labels_event_cp['singular_name']="Evénement CP";
    register_post_type(
        'ecp_cpevent',
        array(
            'label' => 'événements',
            'labels' => $labels_event_cp,
            'public' => true,
            'capability_type' => 'ecp_cpevent',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpevents',
				'edit_posts' => 'edit_ecp_cpevents',
				'edit_others_posts' => 'edit_others_ecp_cpevents',
				'delete_posts' => 'delete_ecp_cpevents',
				'delete_others_posts' => 'delete_others_ecp_cpevents',
				'read_private_posts' => 'read_private_ecp_cpevents',
				'edit_post' => 'edit_ecp_cpevent',
				'delete_post' => 'delete_ecp_cpevent',
				'read_post' => 'read_ecp_cpevent',
			),
            'supports'=> array('title', 'author', 'editor' ),
            'taxonomies' => array( 'ecp_tax_event_category')
        )
    );
    //////////////////////////////////////////////////////////////////////////////

    ///////////////////////
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
    $labels_messagerie_fepem=$labels_messagerie;
    $labels_messagerie_fepem['name']="Messageries Fepem";
    $labels_messagerie_fepem['singular_name']="Messgaerie Fepem";
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
    $labels_messagerie_os=$labels_messagerie;
    $labels_messagerie_os['name']="Messageries OS";
    $labels_messagerie_os['singular_name']="Messgaerie OS";
    register_post_type(
        'ecp_osmessagerie',
        array(
            'label' => 'messageries',
            'labels' => $labels_messagerie_os,
            'public' => true,
            'capability_type' => 'ecp_osmessagerie',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_osmessageries',
				'edit_posts' => 'edit_ecp_osmessageries',
				'edit_others_posts' => 'edit_others_ecp_osmessageries',
				'delete_posts' => 'delete_ecp_osmessageries',
				'delete_others_posts' => 'delete_others_ecp_osmessageries',
				'read_private_posts' => 'read_private_ecp_osmessageries',
				'edit_post' => 'edit_ecp_osmessagerie',
				'delete_post' => 'delete_ecp_osmessagerie',
				'read_post' => 'read_ecp_osmessagerie',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    $labels_messagerie_cp=$labels_messagerie;
    $labels_messagerie_cp['name']="Messageries CP";
    $labels_messagerie_cp['singular_name']="Messgaerie CP";
    register_post_type(
        'ecp_cpmessagerie',
        array(
            'label' => 'messageries',
            'labels' => $labels_messagerie_cp,
            'public' => true,
            'capability_type' => 'ecp_cpmessagerie',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpmessageries',
				'edit_posts' => 'edit_ecp_cpmessageries',
				'edit_others_posts' => 'edit_others_ecp_cpmessageries',
				'delete_posts' => 'delete_ecp_cpmessageries',
				'delete_others_posts' => 'delete_others_ecp_cpmessageries',
				'read_private_posts' => 'read_private_ecp_cpmessageries',
				'edit_post' => 'edit_ecp_cpmessagerie',
				'delete_post' => 'delete_ecp_cpmessagerie',
				'read_post' => 'read_ecp_cpmessagerie',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    //////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////
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
    $labels_message_fepem=$labels_message;
    $labels_message_fepem['name']="Messages Fepem";
    $labels_message_fepem['singular_name']="Message Fepem";
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
    $labels_message_os=$labels_message;
    $labels_message_os['name']="Messages OS";
    $labels_message_os['singular_name']="Message OS";
    register_post_type(
        'ecp_osmessage',
        array(
            'label' => 'messages',
            'labels' => $labels_message_os,
            'public' => true,
            'capability_type' => 'ecp_osmessage',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_osmessages',
				'edit_posts' => 'edit_ecp_osmessages',
				'edit_others_posts' => 'edit_others_ecp_osmessages',
				'delete_posts' => 'delete_ecp_osmessages',
				'delete_others_posts' => 'delete_others_ecp_osmessages',
				'read_private_posts' => 'read_private_ecp_osmessages',
				'edit_post' => 'edit_ecp_osmessage',
				'delete_post' => 'delete_ecp_osmessage',
				'read_post' => 'read_ecp_osmessage',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author', 'thumbnail','comments')
        )
    );
    $labels_message_cp=$labels_message;
    $labels_message_cp['name']="Messages CP";
    $labels_message_cp['singular_name']="Message CP";
    register_post_type(
        'ecp_cpmessage',
        array(
            'label' => 'messages',
            'labels' => $labels_message_cp,
            'public' => true,
            'capability_type' => 'ecp_cpmessage',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpmessages',
				'edit_posts' => 'edit_ecp_cpmessages',
				'edit_others_posts' => 'edit_others_ecp_cpmessages',
				'delete_posts' => 'delete_ecp_cpmessages',
				'delete_others_posts' => 'delete_others_ecp_cpmessages',
				'read_private_posts' => 'read_private_ecp_cpmessages',
				'edit_post' => 'edit_ecp_cpmessage',
				'delete_post' => 'delete_ecp_cpmessage',
				'read_post' => 'read_ecp_cpmessage',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author', 'thumbnail','comments')
        )
    );
    ///////////////////////////////////////////////////////////////////////////////

    ////////////////////////
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
    $labels_ged_fepem=$labels_ged;
    $labels_ged_fepem['name']="GED Fepem";
    $labels_ged_fepem['singular_name']="GED Fepem";
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
    $labels_ged_os=$labels_ged;
    $labels_ged_os['name']="GED OS";
    $labels_ged_os['singular_name']="GED OS";
    register_post_type(
        'ecp_osged',
        array(
            'label' => 'ged',
            'labels' => $labels_ged_os,
            'public' => true,
            'capability_type' => 'ecp_osged',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_osgeds',
				'edit_posts' => 'edit_ecp_osgeds',
				'edit_others_posts' => 'edit_others_ecp_osgeds',
				'delete_posts' => 'delete_ecp_osgeds',
				'delete_others_posts' => 'delete_others_ecp_osgeds',
				'read_private_posts' => 'read_private_ecp_osgeds',
				'edit_post' => 'edit_ecp_osged',
				'delete_post' => 'delete_ecp_osged',
				'read_post' => 'read_ecp_osged',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    $labels_ged_cp=$labels_ged;
    $labels_ged_cp['name']="GED CP";
    $labels_ged_cp['singular_name']="GED CP";
    register_post_type(
        'ecp_cpged',
        array(
            'label' => 'ged',
            'labels' => $labels_ged_cp,
            'public' => true,
            'capability_type' => 'ecp_cpged',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpgeds',
				'edit_posts' => 'edit_ecp_cpgeds',
				'edit_others_posts' => 'edit_others_ecp_cpgeds',
				'delete_posts' => 'delete_ecp_cpgeds',
				'delete_others_posts' => 'delete_others_ecp_cpgeds',
				'read_private_posts' => 'read_private_ecp_cpgeds',
				'edit_post' => 'edit_ecp_cpged',
				'delete_post' => 'delete_ecp_cpged',
				'read_post' => 'read_ecp_cpged',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    ///////////////////////////////////////////////////////////////////////////////////

    ////////////////////
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
    $labels_document_fepem=$labels_document;
    $labels_document_fepem['name']='Documents Fepem';
    $labels_document_fepem['singular_name']='Document Fepem';
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
    $labels_document_os=$labels_document;
    $labels_document_os['name']='Documents OS';
    $labels_document_os['singular_name']='Document OS';
    register_post_type(
        'ecp_osdocument',
        array(
            'label' => 'document',
            'labels' => $labels_document_os,
            'public' => true,
            'capability_type' => 'ecp_osdocument',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_osdocuments',
				'edit_posts' => 'edit_ecp_osdocuments',
				'edit_others_posts' => 'edit_others_ecp_osdocuments',
				'delete_posts' => 'delete_ecp_osdocuments',
				'delete_others_posts' => 'delete_others_ecp_osdocuments',
				'read_private_posts' => 'read_private_ecp_osdocuments',
				'edit_post' => 'edit_ecp_osdocument',
				'delete_post' => 'delete_ecp_osdocument',
				'read_post' => 'read_ecp_osdocument',
			),
            'hierarchical' => false,
            'supports'=> array('title', 'editor', 'author')
        )
    );
    $labels_document_cp=$labels_document;
    $labels_document_cp['name']='Documents GED';
    $labels_document_cp['singular_name']='Document GED';
    register_post_type(
        'ecp_cpdocument',
        array(
            'label' => 'document',
            'labels' => $labels_document_cp,
            'public' => true,
            'capability_type' => 'ecp_cpdocument',
            'capabilities' => array(
				'publish_posts' => 'publish_ecp_cpdocuments',
				'edit_posts' => 'edit_ecp_cpdocuments',
				'edit_others_posts' => 'edit_others_ecp_cpdocuments',
				'delete_posts' => 'delete_ecp_cpdocuments',
				'delete_others_posts' => 'delete_others_ecp_cpdocuments',
				'read_private_posts' => 'read_private_ecp_cpdocuments',
				'edit_post' => 'edit_ecp_cpdocument',
				'delete_post' => 'delete_ecp_cpdocument',
				'read_post' => 'read_ecp_cpdocument',
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
    $list_cpt_calendriers_menu=get_cpt_calendriers_menu();
    $list_cpt_messageries_menu=get_cpt_messageries_menu();
    $list_cpt_geds_menu=get_cpt_geds_menu();

    //gestion des calendriers
    foreach ( $list_cpt_calendriers_menu as $cpt_calendrier => $cpt_event ) {
        add_submenu_page('edit.php?post_type='.$cpt_calendrier, 'Liste des événements', 'Tous les événements', 'edit_others_'.$cpt_event.'s', 'edit.php?post_type='.$cpt_event);
        add_submenu_page('edit.php?post_type='.$cpt_calendrier, "Ajout d'un événement", 'Ajouter un événement', 'edit_others_'.$cpt_event.'s', 'post-new.php?post_type='.$cpt_event);
        add_submenu_page('edit.php?post_type='.$cpt_calendrier, "Catégorie d'événements", "Catégorie d'événements", 'manage_options', 'edit-tags.php?taxonomy=ecp_tax_event_category&post_type='.$cpt_event);
        remove_menu_page('edit.php?post_type='.$cpt_event);
    }

    //gestion des messageries
    foreach ( $list_cpt_messageries_menu as $cpt_messagerie => $cpt_message ) {
        add_submenu_page('edit.php?post_type='.$cpt_messagerie, 'Liste des messages', 'Tous les messages', 'edit_others_'.$cpt_message.'s', 'edit.php?post_type='.$cpt_message);
        add_submenu_page('edit.php?post_type='.$cpt_messagerie, "Ajout d'un message", 'Ajouter un message', 'edit_others_'.$cpt_message.'s', 'post-new.php?post_type='.$cpt_message);
        add_submenu_page('edit.php?post_type='.$cpt_messagerie, "Liste des commentaires", 'Tous les commentaires', 'edit_others_'.$cpt_message.'s', 'edit-comments.php');
        remove_menu_page('edit.php?post_type='.$cpt_message);
    }
    
    //gestion des documents
    foreach( $list_cpt_geds_menu as $cpt_ged => $cpt_doc ) {
        add_submenu_page('edit.php?post_type='.$cpt_ged, 'Liste des documents', 'Tous les documents', 'edit_others_'.$cpt_doc.'s', 'edit.php?post_type='.$cpt_doc);
        add_submenu_page('edit.php?post_type='.$cpt_ged, "Ajout d'un document", 'Ajouter un document', 'edit_others_'.$cpt_doc.'s', 'post-new.php?post_type='.$cpt_doc);
        remove_menu_page('edit.php?post_type='.$cpt_doc);
    }

}
add_action('admin_menu', 'extranetcp_admin_menu');

/**************************************************
 * Déclaration des metabox utilisées avec les CPT
 *
 *************************************************/
function extranetcp_cpt_metaboxes() {
    $list_cpt_event=get_cpt_event();
    $list_cpt_message=get_cpt_message();
    $list_cpt_document=get_cpt_document();
    $list_cpt_instances=get_cpt_instances();
    $list_cpt_ged=get_cpt_ged();

    // metabox pour indiquer un email de contact pour les cpt instance
    add_meta_box('meta_email_contact_commission', 'Email de contact de la commission', 'build_metabox_email_contact_commission', $list_cpt_instances, 'side','default');
    //metabox membres pour les cpt commission
    add_meta_box('meta_members_commission', 'Membres de la commission', 'build_metabox_members_commission', $list_cpt_instances, 'side', 'default');
    //metabox calendrier pour les cpt commission
    add_meta_box('meta_calendar_commission', 'Calendrier de la commission', 'build_metabox_calendar_commission', $list_cpt_instances, 'side');
    //metabox messagerie pour les cpt commission
    add_meta_box('meta_messagerie_commission', 'Messagerie de la commission', 'build_metabox_messagerie_commission', $list_cpt_instances, 'side');
    //metabox ged pour les cpt commission
    add_meta_box('meta_ged_commission', 'GED de la commission', 'build_metabox_ged_commission', $list_cpt_instances, 'side');


    //metabox calendrier pour les cpt événement
    add_meta_box( 'metabox_event_parent_calendar', 'Calendrier de rattachement', 'build_metabox_event_calendar', $list_cpt_event, 'side' );
    //metabox pour les données du cpt événement
    add_meta_box('metabox_event_data', "Données de l'événement", 'build_metabox_event_data', $list_cpt_event);
    //metabox notification pour les cpt événement
    add_meta_box('metabox_event_notification', "Notification", 'build_metabox_notification_event', $list_cpt_event, 'normal','high');

    //metabox messagerie pour les cpt messages
    add_meta_box('metabox_messagerie_message', "Messagerie", 'build_metabox_messagerie_message', $list_cpt_message, 'side');
    //metabox notification pour les cpt messages
    add_meta_box('metabox_notification_message', "Notification", 'build_metabox_notification_message', $list_cpt_message,'normal','high');

    //metabox ged pour les cpt document
    add_meta_box('metabox_ged_document', "GED", 'build_metabox_ged_document', $list_cpt_document, 'side');
    //metabox champ d'upload pour les cpt document
    add_meta_box('metabox_upload_document', "Fichier", 'build_metabox_upload_document', $list_cpt_document, 'normal');
    // metabox notification pour les cpt document
    add_meta_box('metabox_notification_document',"Notification","build_metabox_notification_document",$list_cpt_document,'normal');

    //metabox pour afficher les documents associés pour les cpt GED
    add_meta_box('metabox_documents_ged',"Documents de la GED","build_metabox_documents_ged",$list_cpt_ged,'normal');

}
add_action('add_meta_boxes','extranetcp_cpt_metaboxes');

/**
 * Metaboxes definition
 */
require_once get_template_directory() ."/inc/cpt-metaboxes-commission.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-calendrier.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-messagerie.php";
require_once get_template_directory() ."/inc/cpt-metaboxes-ged.php";

/***************************************************************************************
 * Fonctions qui se chargent d'envoyer un mail aux utilisateurs sélectionnés lorsque
 * le message/l'événement/le document est nouvellement publié ou mis à jour
 * 
 ***************************************************************************************/
add_action('transition_post_status','extranetcp_msg_notification',10,3);
function extranetcp_msg_notification($new_status, $old_status, $post) {
    $list_cpt_notifications=get_cpt_notifications();
    //si on est à la publication du message on ajoute l'envoi de la notification lors de l'enregistrement du message
    // l'envoi est fait après l'enregistrement de la metabox permettant de choisir la messagerie à laquelle liée le message
    if ( in_array(get_post_type( $post->ID ), $list_cpt_notifications ) ) {
        if ( 'publish' == $new_status ) { //&& $old_status !== 'publish'
            add_action('save_post','composant_published_notification',12);
        }
    }
}
function composant_published_notification( $post_id ) {

    $list_cpt_message=get_cpt_message();
    $list_cpt_event=get_cpt_event();
    $list_cpt_document=get_cpt_document();
    $list_cpt_notifications=get_cpt_notifications();

    //check post type
    if ( !in_array(get_post_type( $post->ID ), $list_cpt_notifications ) ) {
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

        if( in_array( get_post_type( $post_id ), $list_cpt_message ) ) {
            $subject="Extranet - un nouveau message a été déposé";
        } elseif ( in_array( get_post_type( $post_id ), $list_cpt_event ) ) {
            $subject="Extranet - un nouvel événement a été déposé";
        } elseif ( in_array( get_post_type( $post_id ), $list_cpt_document ) ) {
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



