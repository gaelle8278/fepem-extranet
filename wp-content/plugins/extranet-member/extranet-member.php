<?php
/**
 * Plugin Name:       Extranet Member
 * Description:       Plugin pour gérer les membres de l'Extranet
 * Version:           1.0.0
 * Author:            Gaëlle Rauffet
 */

class Extranet_Member_Plugin {
    public function __construct() {
        //chargement du js nécessaire
        add_action('admin_enqueue_scripts', array( $this, 'include_js_file') );

        // cache l'admin bar si l'utilisateur est un membre extranet
        add_action('init',array( $this, 'disable_admin_bar' ) );

        //empeche l'accès au BO aux membres extranet
        add_action('admin_init', array( $this, 'restrict_access_administration'));

        //ajout de champs supplémentaires comme un champ d'upload d'image au profil utilisateur
        // lors de son affichage, de son edition, et de son ajout
        add_action( 'show_user_profile', array( $this, 'additional_user_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'additional_user_fields' ) );
        add_action( 'user_new_form', array( $this, 'additional_user_fields' ) );
        //sauvegarde des champs supplémentaires du profil utilisateur
        add_action( 'profile_update', array( $this, 'save_additional_user_meta' ));
        add_action( 'edit_user_profile_update', array( $this, 'save_additional_user_meta' ));
        add_action( 'user_register', array( $this, 'save_additional_user_meta' ) );

        /* Ajout d'une custom taxonomy aux utilisateurs */
        add_action( 'init', array($this, 'add_user_taxonomy'));
        /* Ajout de la page gérant la custom user taxonomy dans le BO. */
        add_action( 'admin_menu', array( $this, 'add_type_de_membre_admin_page' ) );
        /* gestion du placement de la page d'administration de la user taxomnomy dans le menu */
        add_filter( 'parent_file', array( $this, 'fix_user_tax_page' ) );
        /* Ajout d'une colonne "Utilisateurs" sur la page admin de la custom user taxonomy. */
        add_filter( 'manage_edit-ecp_tax_type_membre_columns', array( $this, 'manage_type_membre_user_column' ) );
        /* Contenu de la colonne "Utilisateurs" sur la page admin de la custom user taxonomy. */
        add_action( 'manage_ecp_tax_type_membre_custom_column', array( $this, 'display_type_membre_user_column'), 10, 3 );
    }

    /**
     * Fonction qui crée le role Membre Extranet
     *
     * Appelée à l'activation du plugin
     */
    public function create_member_role() {
        add_role( 'member-extranet', 'Membre Extranet',
            array(
                'read' => true,
                'edit_posts' => false,
                'edit_pages' => false,
                'edit_others_posts' => false,
                'create_posts' => false,
                'manage_categories' => false,
                'publish_posts' => false
            ));
    }

    /**
     * Fonction qui enlève le role Membre Extranet
     *
     * Appelée à la désactivation du plugin
     */
    public function remove_member_role() {
        remove_role( 'member-extranet' );
    }

    /**
     * Fonction qui crée les roles nécessaires à la gestion en BO
     *
     * Appelée à l'activation du plugin
     */
    public function create_bo_roles() {
        add_role( 'admin-fepem-exec', 'Administrateur Fepem Exécutif',
            array(
                'read' => true,
                'edit_pages' => true,
                'create_posts' => true,
                'manage_categories' => false,
                'edit_posts' => true,      // nécessaire pour accéder aux commentaires et les modérer
                'moderate_comments' => true, //utiliser pour certaines fonctionnalités liées à la modération des commentaire @see http://shinephp.com/moderate_comments-wordpress-user-capability/
                'edit_commissions' => true,
                'edit_others_commissions' => true,
                'delete_commissions' => true,
                'delete_others_commissions' => true,
                'read_private_commissions' => true,
                'publish_commissions' => true,
                'edit_ecp_calendriers' => true,
                'edit_others_ecp_calendriers' => true,
                'delete_ecp_calendriers' => true,
                'delete_others_ecp_calendriers' => true,
                'read_private_ecp_calendriers' => true,
                'publish_ecp_calendriers' => true,
                'edit_ecp_events' => true,
                'edit_others_ecp_events' => true,
                'delete_ecp_events' => true,
                'delete_others_ecp_events' => true,
                'read_private_ecp_events' => true,
                'publish_ecp_events' => true,
                'assign_tax_cat_event' => true, //capability pour pouvoir assigner la taxonomy catégorie d'événement
                'edit_ecp_messageries' => true,
                'edit_others_ecp_messageries' => true,
                'delete_ecp_messageries' => true,
                'delete_others_ecp_messageries' => true,
                'read_private_ecp_messageries' => true,
                'publish_ecp_messageries' => true,
                'edit_ecp_messages' => true,
                'edit_others_ecp_messages' => true,
                'delete_ecp_messages' => true,
                'delete_others_ecp_messages' => true,
                'read_private_ecp_messages' => true,
                'publish_ecp_messages' => true,
                'publish_ecp_geds' => true,
                'edit_ecp_geds' => true,
                'edit_others_ecp_geds' => true,
                'delete_ecp_geds' => true,
                'delete_others_ecp_geds' => true,
                'read_private_ecp_geds' => true,
                'publish_ecp_geds' => true,
                'edit_ecp_documents' => true,
                'edit_others_ecp_documents' => true,
                'delete_ecp_documents' => true,
                'delete_others_ecp_documents' => true,
                'read_private_ecp_documents' => true,
                'publish_ecp_documents' => true,
                'assign_tax_public_type' => true, //capability pour pouvoir assigner la taxonomy type de public des CPT
                'manage_instances' => true, //capacité pour indiquer que c'est un admnistrateur d'instances : filtre sur entrées de menu à afficher, filtre sur les commentaires, custom dashboard, filtre sur la création d'utilisateurs ...
                'list_users' => true, 
                'edit_users' => true,
                'create_users' => true,
                'delete_users' => true

                ));
        add_role( 'admin-fepem', 'Administrateur Fepem',
            array(
                'read' => true,
                'edit_pages' => true,
                'create_posts' => true,
                'manage_categories' => false,
                'edit_posts' => true,
                'moderate_comments' => true,
                'edit_fcommissions' => true,
                'edit_others_fcommissions' => true,
                'delete_fcommissions' => true,
                'delete_others_fcommissions' => true,
                'read_private_fcommissions' => true,
                'publish_fcommissions' => true,
                'edit_ecp_fcalendriers' => true,
                'edit_others_ecp_fcalendriers' => true,
                'delete_ecp_fcalendriers' => true,
                'delete_others_ecp_fcalendriers' => true,
                'read_private_ecp_fcalendriers' => true,
                'publish_ecp_fcalendriers' => true,
                'edit_ecp_fevents' => true,
                'edit_others_ecp_fevents' => true,
                'delete_ecp_fevents' => true,
                'delete_others_ecp_fevents' => true,
                'read_private_ecp_fevents' => true,
                'publish_ecp_fevents' => true,
                'assign_tax_cat_event' => true, 
                'edit_ecp_fmessageries' => true,
                'edit_others_ecp_fmessageries' => true,
                'delete_ecp_fmessageries' => true,
                'delete_others_ecp_fmessageries' => true,
                'read_private_ecp_fmessageries' => true,
                'publish_ecp_fmessageries' => true,
                'edit_ecp_fmessages' => true,
                'edit_others_ecp_fmessages' => true,
                'delete_ecp_fmessages' => true,
                'delete_others_ecp_fmessages' => true,
                'read_private_ecp_fmessages' => true,
                'publish_ecp_fmessages' => true,
                'edit_ecp_fgeds' => true,
                'edit_others_ecp_fgeds' => true,
                'delete_ecp_fgeds' => true,
                'delete_others_ecp_fgeds' => true,
                'read_private_ecp_fgeds' => true,
                'publish_ecp_fgeds' => true,
                'edit_ecp_fdocuments' => true,
                'edit_others_ecp_fdocuments' => true,
                'delete_ecp_fdocuments' => true,
                'delete_others_ecp_fdocuments' => true,
                'read_private_ecp_fdocuments' => true,
                'publish_ecp_fdocuments' => true,
                'assign_tax_public_type' => true ,
                'manage_instances' => true,
                'list_users' => true,
                'edit_users' => true,
                'create_users' => true,
                'delete_users' => true
        ));
        add_role( 'admin-os', 'Administrateur OS',
            array(
                'read' => true,
                'edit_pages' => true,
                'create_posts' => true,
                'manage_categories' => false,
                'edit_posts' => true,
                'moderate_comments' => true,
                'edit_oscommissions' => true,
                'edit_others_oscommissions' => true,
                'delete_oscommissions' => true,
                'delete_others_oscommissions' => true,
                'read_private_oscommissions' => true,
                'publish_oscommissions' => true,
                'edit_ecp_oscalendriers' => true,
                'edit_others_ecp_oscalendriers' => true,
                'delete_ecp_oscalendriers' => true,
                'delete_others_ecp_oscalendriers' => true,
                'read_private_ecp_oscalendriers' => true,
                'publish_ecp_oscalendriers' => true,
                'edit_ecp_osevents' => true,
                'edit_others_ecp_osevents' => true,
                'delete_ecp_osevents' => true,
                'delete_others_ecp_osevents' => true,
                'read_private_ecp_osevents' => true,
                'publish_ecp_osevents' => true,
                'assign_tax_cat_event' => true,
                'edit_ecp_osmessageries' => true,
                'edit_others_ecp_osmessageries' => true,
                'delete_ecp_osmessageries' => true,
                'delete_others_ecp_osmessageries' => true,
                'read_private_ecp_osmessageries' => true,
                'publish_ecp_osmessageries' => true,
                'edit_ecp_osmessages' => true,
                'edit_others_ecp_osmessages' => true,
                'delete_ecp_osmessages' => true,
                'delete_others_ecp_osmessages' => true,
                'read_private_ecp_osmessages' => true,
                'publish_ecp_osmessages' => true,
                'edit_ecp_osgeds' => true,
                'edit_others_ecp_osgeds' => true,
                'delete_ecp_osgeds' => true,
                'delete_others_ecp_osgeds' => true,
                'read_private_ecp_osgeds' => true,
                'publish_ecp_osgeds' => true,
                'edit_ecp_osdocuments' => true,
                'edit_others_ecp_osdocuments' => true,
                'delete_ecp_osdocuments' => true,
                'delete_others_ecp_osdocuments' => true,
                'read_private_ecp_osdocuments' => true,
                'publish_ecp_osdocuments' => true,
                'assign_tax_public_type' => true ,
                'manage_instances' => true,
                'list_users' => true,
                'edit_users' => true,
                'create_users' => true,
                'delete_users' => true
        ));
        add_role( 'admin-cp', 'Administrateur CP',
            array(
                'read' => true,
                'edit_pages' => true,
                'create_posts' => true,
                'manage_categories' => false,
                'edit_posts' => true,
                'moderate_comments' => true,
                'edit_cpcommissions' => true,
                'edit_others_cpcommissions' => true,
                'delete_cpcommissions' => true,
                'delete_others_cpcommissions' => true,
                'read_private_cpcommissions' => true,
                'publish_cpcommissions' => true,
                'edit_ecp_cpcalendriers' => true,
                'edit_others_ecp_cpcalendriers' => true,
                'delete_ecp_cpcalendriers' => true,
                'delete_others_ecp_cpcalendriers' => true,
                'read_private_ecp_cpcalendriers' => true,
                'publish_ecp_cpcalendriers' => true,
                'edit_ecp_cpevents' => true,
                'edit_others_ecp_cpevents' => true,
                'delete_ecp_cpevents' => true,
                'delete_others_ecp_cpevents' => true,
                'read_private_ecp_cpevents' => true,
                'publish_ecp_cpevents' => true,
                'assign_tax_cat_event' => true,
                'edit_ecp_cpmessageries' => true,
                'edit_others_ecp_cpmessageries' => true,
                'delete_ecp_cpmessageries' => true,
                'delete_others_ecp_cpmessageries' => true,
                'read_private_ecp_cpmessageries' => true,
                'publish_ecp_cpmessageries' => true,
                'edit_ecp_cpmessages' => true,
                'edit_others_ecp_cpmessages' => true,
                'delete_ecp_cpmessages' => true,
                'delete_others_ecp_cpmessages' => true,
                'read_private_ecp_cpmessages' => true,
                'publish_ecp_cpmessages' => true,
                'edit_ecp_cpgeds' => true,
                'edit_others_ecp_cpgeds' => true,
                'delete_ecp_cpgeds' => true,
                'delete_others_ecp_cpgeds' => true,
                'read_private_ecp_cpgeds' => true,
                'publish_ecp_cpgeds' => true,
                'edit_ecp_cpdocuments' => true,
                'edit_others_ecp_cpdocuments' => true,
                'delete_ecp_cpdocuments' => true,
                'delete_others_ecp_cpdocuments' => true,
                'read_private_ecp_cpdocuments' => true,
                'publish_ecp_cpdocuments' => true,
                'assign_tax_public_type' => true ,
                'manage_instances' => true,
                'list_users' => true,
                'edit_users' => true,
                'create_users' => true,
                'delete_users' => true

        ));
    }

    /**
     * Fonction qui enlève les roles BO
     *
     * Appelée à la désactivation du plugin
     */
    public function remove_bo_roles() {
        remove_role( 'admin-fepem-exec' );
        remove_role( 'admin-fepem' );
        remove_role( 'admin-os' );
        remove_role( 'admin-cp' );
    }

    /**
     * Fonction qui gère l'ajout des capacités liées aux CPT au role Administrateur
     */
    public function add_admin_cap() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'assign_tax_public_type');
        $role->add_cap( 'assign_tax_cat_event');
        //commission fepem executif
        $role->add_cap( 'read_private_commissions' );
        $role->add_cap( 'publish_commissions' );
        $role->add_cap( 'edit_commissions' );
        $role->add_cap( 'edit_others_commissions' );
        $role->add_cap( 'delete_commissions' );
        $role->add_cap( 'delete_others_commissions' );
        //commission fepem
        $role->add_cap( 'read_private_fcommissions' );
        $role->add_cap( 'publish_fcommissions' );
        $role->add_cap( 'edit_fcommissions' );
        $role->add_cap( 'edit_others_fcommissions' );
        $role->add_cap( 'delete_fcommissions' );
        $role->add_cap( 'delete_others_fcommissions' );
        //commission os
        $role->add_cap( 'read_private_oscommissions' );
        $role->add_cap( 'publish_oscommissions' );
        $role->add_cap( 'edit_oscommissions' );
        $role->add_cap( 'edit_others_oscommissions' );
        $role->add_cap( 'delete_oscommissions' );
        $role->add_cap( 'delete_others_oscommissions' );
        //commission cp
        $role->add_cap( 'read_private_cpcommissions' );
        $role->add_cap( 'publish_cpcommissions' );
        $role->add_cap( 'edit_cpcommissions' );
        $role->add_cap( 'edit_others_cpcommissions' );
        $role->add_cap( 'delete_cpcommissions' );
        $role->add_cap( 'delete_others_cpcommissions' );
        //calendrier fepem executif
        $role->add_cap( 'read_private_ecp_calendriers' );
        $role->add_cap( 'publish_ecp_calendriers' );
        $role->add_cap( 'edit_ecp_calendriers' );
        $role->add_cap( 'edit_others_ecp_calendriers' );
        $role->add_cap( 'delete_ecp_calendriers' );
        $role->add_cap( 'delete_others_ecp_calendriers' );
        //calendrier fepem
        $role->add_cap( 'read_private_ecp_fcalendriers' );
        $role->add_cap( 'publish_ecp_fcalendriers' );
        $role->add_cap( 'edit_ecp_fcalendriers' );
        $role->add_cap( 'edit_others_ecp_fcalendriers' );
        $role->add_cap( 'delete_ecp_fcalendriers' );
        $role->add_cap( 'delete_others_ecp_fcalendriers' );
        //calendrier os
        $role->add_cap( 'read_private_ecp_oscalendriers' );
        $role->add_cap( 'publish_ecp_oscalendriers' );
        $role->add_cap( 'edit_ecp_oscalendriers' );
        $role->add_cap( 'edit_others_ecp_oscalendriers' );
        $role->add_cap( 'delete_ecp_oscalendriers' );
        $role->add_cap( 'delete_others_ecp_oscalendriers' );
        //calendrier cp
        $role->add_cap( 'read_private_ecp_cpcalendriers' );
        $role->add_cap( 'publish_ecp_cpcalendriers' );
        $role->add_cap( 'edit_ecp_cpcalendriers' );
        $role->add_cap( 'edit_others_ecp_cpcalendriers' );
        $role->add_cap( 'delete_ecp_cpcalendriers' );
        $role->add_cap( 'delete_others_ecp_cpcalendriers' );
        //event fepem executif
        $role->add_cap( 'read_private_ecp_events' );
        $role->add_cap( 'publish_ecp_events' );
        $role->add_cap( 'edit_ecp_events' );
        $role->add_cap( 'edit_others_ecp_events' );
        $role->add_cap( 'delete_ecp_events' );
        $role->add_cap( 'delete_others_ecp_events' );
        //event fepem
        $role->add_cap( 'read_private_ecp_fevents' );
        $role->add_cap( 'publish_ecp_fevents' );
        $role->add_cap( 'edit_ecp_fevents' );
        $role->add_cap( 'edit_others_ecp_fevents' );
        $role->add_cap( 'delete_ecp_fevents' );
        $role->add_cap( 'delete_others_ecp_fevents' );
        //event os
        $role->add_cap( 'read_private_ecp_osevents' );
        $role->add_cap( 'publish_ecp_osevents' );
        $role->add_cap( 'edit_ecp_osevents' );
        $role->add_cap( 'edit_others_ecp_osevents' );
        $role->add_cap( 'delete_ecp_osevents' );
        $role->add_cap( 'delete_others_ecp_osevents' );
        //event cp
        $role->add_cap( 'read_private_ecp_cpevents' );
        $role->add_cap( 'publish_ecp_cpevents' );
        $role->add_cap( 'edit_ecp_cpevents' );
        $role->add_cap( 'edit_others_ecp_cpevents' );
        $role->add_cap( 'delete_ecp_cpevents' );
        $role->add_cap( 'delete_others_ecp_cpevents' );
        //messagerie fepem executif
        $role->add_cap( 'read_private_ecp_messageries' );
        $role->add_cap( 'publish_ecp_messageries' );
        $role->add_cap( 'edit_ecp_messageries' );
        $role->add_cap( 'edit_others_ecp_messageries' );
        $role->add_cap( 'delete_ecp_messageries' );
        $role->add_cap( 'delete_others_ecp_messageries' );
        //messagerie fepem
        $role->add_cap( 'read_private_ecp_fmessageries' );
        $role->add_cap( 'publish_ecp_fmessageries' );
        $role->add_cap( 'edit_ecp_fmessageries' );
        $role->add_cap( 'edit_others_ecp_fmessageries' );
        $role->add_cap( 'delete_ecp_fmessageries' );
        $role->add_cap( 'delete_others_ecp_fmessageries' );
        //messagerie os
        $role->add_cap( 'read_private_ecp_osmessageries' );
        $role->add_cap( 'publish_ecp_osmessageries' );
        $role->add_cap( 'edit_ecp_osmessageries' );
        $role->add_cap( 'edit_others_ecp_osmessageries' );
        $role->add_cap( 'delete_ecp_osmessageries' );
        $role->add_cap( 'delete_others_ecp_osmessageries' );
        //messagerie cp
        $role->add_cap( 'read_private_ecp_cpmessageries' );
        $role->add_cap( 'publish_ecp_cpmessageries' );
        $role->add_cap( 'edit_ecp_cpmessageries' );
        $role->add_cap( 'edit_others_ecp_cpmessageries' );
        $role->add_cap( 'delete_ecp_cpmessageries' );
        $role->add_cap( 'delete_others_ecp_cpmessageries' );
        //message fepem executif
        $role->add_cap( 'read_private_ecp_messages' );
        $role->add_cap( 'publish_ecp_messages' );
        $role->add_cap( 'edit_ecp_messages' );
        $role->add_cap( 'edit_others_ecp_messages' );
        $role->add_cap( 'delete_ecp_messages' );
        $role->add_cap( 'delete_others_ecp_messages' );
        //message fepem
        $role->add_cap( 'read_private_ecp_fmessages' );
        $role->add_cap( 'publish_ecp_fmessages' );
        $role->add_cap( 'edit_ecp_fmessages' );
        $role->add_cap( 'edit_others_ecp_fmessages' );
        $role->add_cap( 'delete_ecp_fmessages' );
        $role->add_cap( 'delete_others_ecp_fmessages' );
        //messages os
        $role->add_cap( 'read_private_ecp_osmessages' );
        $role->add_cap( 'publish_ecp_osmessages' );
        $role->add_cap( 'edit_ecp_osmessages' );
        $role->add_cap( 'edit_others_ecp_osmessages' );
        $role->add_cap( 'delete_ecp_osmessages' );
        $role->add_cap( 'delete_others_ecp_osmessages' );
        //messages cp
        $role->add_cap( 'read_private_ecp_cpmessages' );
        $role->add_cap( 'publish_ecp_cpmessages' );
        $role->add_cap( 'edit_ecp_cpmessages' );
        $role->add_cap( 'edit_others_ecp_cpmessages' );
        $role->add_cap( 'delete_ecp_cpmessages' );
        $role->add_cap( 'delete_others_ecp_cpmessages' );
        //ged fepem executif
        $role->add_cap( 'read_private_ecp_geds' );
        $role->add_cap( 'publish_ecp_geds' );
        $role->add_cap( 'edit_ecp_geds' );
        $role->add_cap( 'edit_others_ecp_geds' );
        $role->add_cap( 'delete_ecp_geds' );
        $role->add_cap( 'delete_others_ecp_geds' );
        //ged fepem
        $role->add_cap( 'read_private_ecp_fgeds' );
        $role->add_cap( 'publish_ecp_fgeds' );
        $role->add_cap( 'edit_ecp_fgeds' );
        $role->add_cap( 'edit_others_ecp_fgeds' );
        $role->add_cap( 'delete_ecp_fgeds' );
        $role->add_cap( 'delete_others_ecp_fgeds' );
        //ged os
        $role->add_cap( 'read_private_ecp_osgeds' );
        $role->add_cap( 'publish_ecp_osgeds' );
        $role->add_cap( 'edit_ecp_osgeds' );
        $role->add_cap( 'edit_others_ecp_osgeds' );
        $role->add_cap( 'delete_ecp_osgeds' );
        $role->add_cap( 'delete_others_ecp_osgeds' );
        //ged cp
        $role->add_cap( 'read_private_ecp_cpgeds' );
        $role->add_cap( 'publish_ecp_cpgeds' );
        $role->add_cap( 'edit_ecp_cpgeds' );
        $role->add_cap( 'edit_others_ecp_cpgeds' );
        $role->add_cap( 'delete_ecp_cpgeds' );
        $role->add_cap( 'delete_others_ecp_cpgeds' );
        //document fepem executif
        $role->add_cap( 'read_private_ecp_documents' );
        $role->add_cap( 'publish_ecp_documents' );
        $role->add_cap( 'edit_ecp_documents' );
        $role->add_cap( 'edit_others_ecp_documents' );
        $role->add_cap( 'delete_ecp_documents' );
        $role->add_cap( 'delete_others_ecp_documents' );
        //document fepem
        $role->add_cap( 'read_private_ecp_fdocuments' );
        $role->add_cap( 'publish_ecp_fdocuments' );
        $role->add_cap( 'edit_ecp_fdocuments' );
        $role->add_cap( 'edit_others_ecp_fdocuments' );
        $role->add_cap( 'delete_ecp_fdocuments' );
        $role->add_cap( 'delete_others_ecp_fdocuments' );
        //document os
        $role->add_cap( 'read_private_ecp_osdocuments' );
        $role->add_cap( 'publish_ecp_osdocuments' );
        $role->add_cap( 'edit_ecp_osdocuments' );
        $role->add_cap( 'edit_others_ecp_osdocuments' );
        $role->add_cap( 'delete_ecp_osdocuments' );
        $role->add_cap( 'delete_others_ecp_osdocuments' );
        //document cp
        $role->add_cap( 'read_private_ecp_cpdocuments' );
        $role->add_cap( 'publish_ecp_cpdocuments' );
        $role->add_cap( 'edit_ecp_cpdocuments' );
        $role->add_cap( 'edit_others_ecp_cpdocuments' );
        $role->add_cap( 'delete_ecp_cpdocuments' );
        $role->add_cap( 'delete_others_ecp_cpdocuments' );

    }

    /**
     * Fonction qui gère la suppression des capacités liées aux CPT au role Administrateur
     */
    public function remove_admin_cap() {
        $role = get_role( 'administrator' );
        $role->remove_cap( 'assign_tax_public_type');
        $role->remove_cap( 'assign_tax_cat_event');
        //commission
        $role->remove_cap( 'read_private_commissions' );
        $role->remove_cap( 'publish_commissions' );
        $role->remove_cap( 'edit_commissions' );
        $role->remove_cap( 'edit_others_commissions' );
        $role->remove_cap( 'delete_commissions' );
        $role->remove_cap( 'delete_others_commissions' );
        //commission fepem
        $role->remove_cap( 'read_private_fcommissions' );
        $role->remove_cap( 'publish_fcommissions' );
        $role->remove_cap( 'edit_fcommissions' );
        $role->remove_cap( 'edit_others_fcommissions' );
        $role->remove_cap( 'delete_fcommissions' );
        $role->remove_cap( 'delete_others_fcommissions' );
        //commission os
        $role->remove_cap( 'read_private_oscommissions' );
        $role->remove_cap( 'publish_oscommissions' );
        $role->remove_cap( 'edit_oscommissions' );
        $role->remove_cap( 'edit_others_oscommissions' );
        $role->remove_cap( 'delete_oscommissions' );
        $role->remove_cap( 'delete_others_oscommissions' );
        //commission cp
        $role->remove_cap( 'read_private_cpcommissions' );
        $role->remove_cap( 'publish_cpcommissions' );
        $role->remove_cap( 'edit_cpcommissions' );
        $role->remove_cap( 'edit_others_cpcommissions' );
        $role->remove_cap( 'delete_cpcommissions' );
        $role->remove_cap( 'delete_others_cpcommissions' );
        //calendrier fepem executif
        $role->remove_cap( 'read_private_ecp_calendriers' );
        $role->remove_cap( 'publish_ecp_calendriers' );
        $role->remove_cap( 'edit_ecp_calendriers' );
        $role->remove_cap( 'edit_others_ecp_calendriers' );
        $role->remove_cap( 'delete_ecp_calendriers' );
        $role->remove_cap( 'delete_others_ecp_calendriers' );
        //calendrier fepem
        $role->remove_cap( 'read_private_ecp_fcalendriers' );
        $role->remove_cap( 'publish_ecp_fcalendriers' );
        $role->remove_cap( 'edit_ecp_fcalendriers' );
        $role->remove_cap( 'edit_others_ecp_fcalendriers' );
        $role->remove_cap( 'delete_ecp_fcalendriers' );
        $role->remove_cap( 'delete_others_ecp_fcalendriers' );
        //calendrier os
        $role->remove_cap( 'read_private_ecp_oscalendriers' );
        $role->remove_cap( 'publish_ecp_oscalendriers' );
        $role->remove_cap( 'edit_ecp_oscalendriers' );
        $role->remove_cap( 'edit_others_ecp_oscalendriers' );
        $role->remove_cap( 'delete_ecp_oscalendriers' );
        $role->remove_cap( 'delete_others_ecp_oscalendriers' );
        //calendrier cp
        $role->remove_cap( 'read_private_ecp_cpcalendriers' );
        $role->remove_cap( 'publish_ecp_cpcalendriers' );
        $role->remove_cap( 'edit_ecp_cpcalendriers' );
        $role->remove_cap( 'edit_others_ecp_cpcalendriers' );
        $role->remove_cap( 'delete_ecp_cpcalendriers' );
        $role->remove_cap( 'delete_others_ecp_cpcalendriers' );
        //event fepem executif
        $role->remove_cap( 'read_private_ecp_events' );
        $role->remove_cap( 'publish_ecp_events' );
        $role->remove_cap( 'edit_ecp_events' );
        $role->remove_cap( 'edit_others_ecp_events' );
        $role->remove_cap( 'delete_ecp_events' );
        $role->remove_cap( 'delete_others_ecp_events' );
        //event fepem
        $role->remove_cap( 'read_private_ecp_fevents' );
        $role->remove_cap( 'publish_ecp_fevents' );
        $role->remove_cap( 'edit_ecp_fevents' );
        $role->remove_cap( 'edit_others_ecp_fevents' );
        $role->remove_cap( 'delete_ecp_fevents' );
        $role->remove_cap( 'delete_others_ecp_fevents' );
        //event os
        $role->remove_cap( 'read_private_ecp_osevents' );
        $role->remove_cap( 'publish_ecp_osevents' );
        $role->remove_cap( 'edit_ecp_osevents' );
        $role->remove_cap( 'edit_others_ecp_osevents' );
        $role->remove_cap( 'delete_ecp_osevents' );
        $role->remove_cap( 'delete_others_ecp_osevents' );
        //event cp
        $role->remove_cap( 'read_private_ecp_cpevents' );
        $role->remove_cap( 'publish_ecp_cpevents' );
        $role->remove_cap( 'edit_ecp_cpevents' );
        $role->remove_cap( 'edit_others_ecp_cpevents' );
        $role->remove_cap( 'delete_ecp_cpevents' );
        $role->remove_cap( 'delete_others_ecp_cpevents' );
        //messagerie fepem executif
        $role->remove_cap( 'read_private_ecp_messageries' );
        $role->remove_cap( 'publish_ecp_messageries' );
        $role->remove_cap( 'edit_ecp_messageries' );
        $role->remove_cap( 'edit_others_ecp_messageries' );
        $role->remove_cap( 'delete_ecp_messageries' );
        $role->remove_cap( 'delete_others_ecp_messageries' );
        //messagerie fepem
        $role->remove_cap( 'read_private_ecp_fmessageries' );
        $role->remove_cap( 'publish_ecp_fmessageries' );
        $role->remove_cap( 'edit_ecp_fmessageries' );
        $role->remove_cap( 'edit_others_ecp_fmessageries' );
        $role->remove_cap( 'delete_ecp_fmessageries' );
        $role->remove_cap( 'delete_others_ecp_fmessageries' );
        //messagerie os
        $role->remove_cap( 'read_private_ecp_osmessageries' );
        $role->remove_cap( 'publish_ecp_osmessageries' );
        $role->remove_cap( 'edit_ecp_osmessageries' );
        $role->remove_cap( 'edit_others_ecp_osmessageries' );
        $role->remove_cap( 'delete_ecp_osmessageries' );
        $role->remove_cap( 'delete_others_ecp_osmessageries' );
        //messagerie cp
        $role->remove_cap( 'read_private_ecp_cpmessageries' );
        $role->remove_cap( 'publish_ecp_cpmessageries' );
        $role->remove_cap( 'edit_ecp_cpmessageries' );
        $role->remove_cap( 'edit_others_ecp_cpmessageries' );
        $role->remove_cap( 'delete_ecp_cpmessageries' );
        $role->remove_cap( 'delete_others_ecp_cpmessageries' );
        //message fepem executif
        $role->remove_cap( 'read_private_ecp_messages' );
        $role->remove_cap( 'publish_ecp_messages' );
        $role->remove_cap( 'edit_ecp_messages' );
        $role->remove_cap( 'edit_others_ecp_messages' );
        $role->remove_cap( 'delete_ecp_messages' );
        $role->remove_cap( 'delete_others_ecp_messages' );
        //message fepem
        $role->remove_cap( 'read_private_ecp_fmessages' );
        $role->remove_cap( 'publish_ecp_fmessages' );
        $role->remove_cap( 'edit_ecp_fmessages' );
        $role->remove_cap( 'edit_others_ecp_fmessages' );
        $role->remove_cap( 'delete_ecp_fmessages' );
        $role->remove_cap( 'delete_others_ecp_fmessages' );
        //messages os
        $role->remove_cap( 'read_private_ecp_osmessages' );
        $role->remove_cap( 'publish_ecp_osmessages' );
        $role->remove_cap( 'edit_ecp_osmessages' );
        $role->remove_cap( 'edit_others_ecp_osmessages' );
        $role->remove_cap( 'delete_ecp_osmessages' );
        $role->remove_cap( 'delete_others_ecp_osmessages' );
        //messages cp
        $role->remove_cap( 'read_private_ecp_cpmessages' );
        $role->remove_cap( 'publish_ecp_cpmessages' );
        $role->remove_cap( 'edit_ecp_cpmessages' );
        $role->remove_cap( 'edit_others_ecp_cpmessages' );
        $role->remove_cap( 'delete_ecp_cpmessages' );
        $role->remove_cap( 'delete_others_ecp_cpmessages' );
        //ged fepem executif
        $role->remove_cap( 'read_private_ecp_geds' );
        $role->remove_cap( 'publish_ecp_geds' );
        $role->remove_cap( 'edit_ecp_geds' );
        $role->remove_cap( 'edit_others_ecp_geds' );
        $role->remove_cap( 'delete_ecp_geds' );
        $role->remove_cap( 'delete_others_ecp_geds' );
        //ged fepem
        $role->remove_cap( 'read_private_ecp_fgeds' );
        $role->remove_cap( 'publish_ecp_fgeds' );
        $role->remove_cap( 'edit_ecp_fgeds' );
        $role->remove_cap( 'edit_others_ecp_fgeds' );
        $role->remove_cap( 'delete_ecp_fgeds' );
        $role->remove_cap( 'delete_others_ecp_fgeds' );
        //ged os
        $role->remove_cap( 'read_private_ecp_osgeds' );
        $role->remove_cap( 'publish_ecp_osgeds' );
        $role->remove_cap( 'edit_ecp_osgeds' );
        $role->remove_cap( 'edit_others_ecp_osgeds' );
        $role->remove_cap( 'delete_ecp_osgeds' );
        $role->remove_cap( 'delete_others_ecp_osgeds' );
        //ged cp
        $role->remove_cap( 'read_private_ecp_cpgeds' );
        $role->remove_cap( 'publish_ecp_cpgeds' );
        $role->remove_cap( 'edit_ecp_cpgeds' );
        $role->remove_cap( 'edit_others_ecp_cpgeds' );
        $role->remove_cap( 'delete_ecp_cpgeds' );
        $role->remove_cap( 'delete_others_ecp_cpgeds' );
        //document fepem executif
        $role->remove_cap( 'read_private_ecp_documents' );
        $role->remove_cap( 'publish_ecp_documents' );
        $role->remove_cap( 'edit_ecp_documents' );
        $role->remove_cap( 'edit_others_ecp_documents' );
        $role->remove_cap( 'delete_ecp_documents' );
        $role->remove_cap( 'delete_others_ecp_documents' );
        //document fepem
        $role->remove_cap( 'read_private_ecp_fdocuments' );
        $role->remove_cap( 'publish_ecp_fdocuments' );
        $role->remove_cap( 'edit_ecp_fdocuments' );
        $role->remove_cap( 'edit_others_ecp_fdocuments' );
        $role->remove_cap( 'delete_ecp_fdocuments' );
        $role->remove_cap( 'delete_others_ecp_fdocuments' );
        //document os
        $role->remove_cap( 'read_private_ecp_osdocuments' );
        $role->remove_cap( 'publish_ecp_osdocuments' );
        $role->remove_cap( 'edit_ecp_osdocuments' );
        $role->remove_cap( 'edit_others_ecp_osdocuments' );
        $role->remove_cap( 'delete_ecp_osdocuments' );
        $role->remove_cap( 'delete_others_ecp_osdocuments' );
        //document cp
        $role->remove_cap( 'read_private_ecp_cpdocuments' );
        $role->remove_cap( 'publish_ecp_cpdocuments' );
        $role->remove_cap( 'edit_ecp_cpdocuments' );
        $role->remove_cap( 'edit_others_ecp_cpdocuments' );
        $role->remove_cap( 'delete_ecp_cpdocuments' );
        $role->remove_cap( 'delete_others_ecp_cpdocuments' );
    }

    /**
     * Fonction qui masque l'admin bar aux membres extranet
     */
    function disable_admin_bar() {
        $user = wp_get_current_user();
        if( in_array( 'member-extranet', $user->roles ) ) {
            // for the front-end
            remove_action('wp_footer', 'wp_admin_bar_render', 1000);

            // css override for the frontend
            function remove_admin_bar_style_frontend() {
      		echo '<style type="text/css" media="screen">
      			html { margin-top: 0px !important; }
      			* html body { margin-top: 0px !important; }
      			</style>';
            }
            add_filter('wp_head','remove_admin_bar_style_frontend', 99);
    	}
    }

    /**
     * Fonction pour empecher l'accès au BO
     */
    public function restrict_access_administration() {
        if (current_user_can('member-extranet')) {
            wp_redirect( home_url() );
            exit;
        }
    }

    /**
     * Fonction pour inclure le js nécessaire à la gestion des champs utilisateurs supplémentaire
     */
    public function include_js_file() {
        //to use media Javascript API
        wp_enqueue_media();
        //chargement du fichier javascript
        wp_enqueue_script( 'pem', plugins_url('/js/extranet-member.js', __FILE__), array( 'jquery' ) );
    }

    /**
     * Fonction pour ajouter des champs au profil utilisateur
     */
    public function additional_user_fields( $user ) {

        $tax = get_taxonomy( 'ecp_tax_type_membre' );

        if(is_object($user)) {
            $picture= esc_url( get_user_meta( $user->ID, 'user_meta_image', true ) );
            $region = get_user_meta( $user->ID, 'user_meta_region', true );
            $tel = get_user_meta( $user->ID, 'user_meta_tel', true );
        } else {
            $picture=null;
            $region=null;
            $tel=null;
        }

        $listRegions=['Auvergne-Rhône-Alpes','Bourgogne-Franche-Comté','Bretagne','Centre-Val de Loire','Corse','Grand Est','Guadeloupe','Guyane',
            'Hauts-de-France','Île-de-France','La Réunion','Martinique','Mayotte','Normandie','Nouvelle-Aquitaine','Occitanie','Pays de la Loire',"Provence-Alpes-Côte d'Azur"];
        ?>
        <h3>Informations supplémentaires pour les membres Extranet</h3>

        <table class="form-table">

            <tr>
                <th><label for="user_meta_image">Votre photo de profil</label></th>
                <td>
                    <?php
                    if( !empty($picture) ) {
                        ?>
                        <!-- Outputs the image after save -->
                        <img src="<?php echo $picture; ?>" style="width:150px;"><br />
                    <?php
                    }
                    ?>
                    <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                    <input type="text" name="user_meta_image" id="user_meta_image" value="<?php echo $picture; ?>" class="regular-text" />
                    <!-- Outputs the save button -->
                    <input type='button' class="additional-user-image button-primary" value="Sélectionner une image" id="uploadimage"/><br />
                    <span class="description">Image pour votre photo de profil</span>
                </td>
            </tr>
            <tr>
                <th><label for="user_meta_region">Région</label></th>
               <td>
                    <select name="user_meta_region" id="user_meta_region">
                        <option value="0" >--Sélectionner une région--</option>
                        <?php 
                        foreach ($listRegions as $name_region) {
                            ?>
                            <option value="<?php echo $name_region; ?>" <?php echo $region==$name_region ? 'selected=\'selected\'':''; ?> ><?php echo $name_region; ?></option>
                            <?php
                        }
                        ?>
                    </select>
               </td>
            </tr>
            <tr>
               <th><label for="user_meta_tel">Numéro de téléphone</label></th>
               <td>
                    <input type="text" name="user_meta_tel" id="user_meta_tel" value="<?php echo $tel; ?>" />
               </td>
            </tr>

        </table>
        <?php

        /* Make sure the user can assign terms of the type de membre taxonomy before proceeding. */
	if ( !current_user_can( $tax->cap->assign_terms ) )
		return;

        /* Get the terms of the 'type de membre' taxonomy. */
	$terms = get_terms( 'ecp_tax_type_membre', array( 'hide_empty' => false ) );

        ?>
        <h3>Type de membre</h3>

	<table class="form-table">
            <tr>
                <th><label for="profession">Choisir le type de membre</label></th>
                <td><?php
                    /* If there are any type de membre terms, loop through them and display checkboxes. */
                    if ( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
                            ?>
                            <input type="radio" name="type-membre" id="type-membre-<?php echo esc_attr( $term->slug ); ?>"
                                   value="<?php echo esc_attr( $term->slug ); ?>"
                                       <?php
                                       if( is_object($user) ) {
                                            checked( true, is_object_in_term( $user->ID, 'ecp_tax_type_membre', $term ) );
                                       }
                                       ?> />
                            <label for="type-membre-<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></label> <br />
                            <?php

                            }
                    } else {
			echo "Il n'y pas de type de membre disponible";
                    }
                    ?>
                </td>
            </tr>
	</table>
        <?php
    }

    /**
    * Saves additional user fields to the database
    */
    function save_additional_user_meta( $user_id ) {
        $tax = get_taxonomy( 'profession' );

        /* Make sure the current user can edit the user and assign terms before proceeding. */
	if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
		return false;

        update_user_meta( $user_id, 'user_meta_image', esc_attr($_POST['user_meta_image']) );

        if(isset($_POST['user_meta_region'])) {
            update_user_meta($user_id, 'user_meta_region', esc_attr($_POST['user_meta_region']) );
        }

        if(isset($_POST['user_meta_tel'])) {
            update_user_meta($user_id, 'user_meta_tel', esc_attr($_POST['user_meta_tel']) ) ;
        }

        if(isset($_POST['type-membre'])) {
            $term = esc_attr( $_POST['type-membre'] );
            
            /* Sets the terms (we're just using a single term) for the user. */
            wp_set_object_terms( $user_id, array( $term ), 'ecp_tax_type_membre', false);

            clean_object_term_cache( $user_id, 'ecp_tax_type_membre' );
        }



    }

    /**
    * Ajout de la custom taxonomy aux objets Utilisateurs
    */
    public function add_user_taxonomy() {
	register_taxonomy(
            'ecp_tax_type_membre',
       	    'user',
	    array(
                'public' => true,
                'labels' => array(
				'name' => "Types de membre",
				'singular_name' => "Type de membre",
				'menu_name' => "Types de membre",
				'search_items' => "Rechercher parmi les types de membre",
				'popular_items' => "Les types de membre les plus utilisés",
				'all_items' => "Tous les types de membre",
				'edit_item' => "Editer le type de membre",
				'update_item' => "Mettre à jour le type de membre",
				'add_new_item' => "Ajouter un type de membre",
				'new_item_name' => "Nouveau type de membre",
				'separate_items_with_commas' => "Séparer les types de membre par des virgules",
				'add_or_remove_items' => "Ajouter ou supprimer un type de membre",
				'choose_from_most_used' => "Choisir parmi les types de membre les plus utilisés",
                ),
		'rewrite' => array(
				'with_front' => true,
				'slug' => 'type-de-membre'
		),
		'capabilities' => array(
				'manage_terms' => 'manage_options', 
				'edit_terms'   => 'manage_options',
				'delete_terms' => 'manage_options',
				'assign_terms' => 'assign_tax_public_type',
		),
		'update_count_callback' => 'update_type_de_membre_count' // Use a custom function to update the count.
            )
	);
    }


    /**
    * Creates the admin page for the '' taxonomy under the 'Users' menu.
    * It works the same as any other taxonomy page in the admin.  However, this is kind of hacky and is meant as a quick solution.
    * When clicking on the menu item in the admin, WordPress' menu system thinks you're viewing something under 'Posts'
    * instead of 'Users' => see fix_user_tax_page on hoo parent_file
    */
    public function add_type_de_membre_admin_page() {

        $tax = get_taxonomy( 'ecp_tax_type_membre' );

        add_users_page(
                   esc_attr( $tax->labels->menu_name ),
                   esc_attr( $tax->labels->menu_name ),
                   $tax->cap->manage_terms,
                   'edit-tags.php?taxonomy=' . $tax->name
        );
    }

    /**
     * To set user taxonomy page admin under Users menu
     * 
     * @global type $pagenow
     * @param string $parent_file
     * @return string
     */
    public function fix_user_tax_page( $parent_file = '' ) {
	global $pagenow;

	if ( ! empty( $_GET[ 'taxonomy' ] ) && $_GET[ 'taxonomy' ] == 'ecp_tax_type_membre' && $pagenow == 'edit-tags.php' ) {
            $parent_file = 'users.php';
	}

	return $parent_file;
    }

    /**
    * Unsets the 'posts' column and adds a 'users' column on the manage ecp_tax_type_membre admin page.
    *
    * @param array $columns An array of columns to be shown in the manage terms table.
    */
    public function manage_type_membre_user_column($columns) {
        unset( $columns['posts'] );

	$columns['users'] = "Nombre d'utilisateurs";

	return $columns;
    }

    /**
    * Displays content for custom columns on the manage ecp_tax_type_membre taxonomy page in the admin.
    *
    * @param string $display WP just passes an empty string here.
    * @param string $column The name of the custom column.
    * @param int $term_id The ID of the term being displayed in the table.
    */
    public function display_type_membre_user_column( $display, $column, $term_id ) {
        if ( 'users' === $column ) {
            $term = get_term( $term_id, 'ecp_tax_type_membre' );
            echo $term->count;
	}
    }
    

}

new Extranet_Member_Plugin();

/**
* Function for updating the 'type-de-membre' taxonomy count.  What this does is update the count of a specific term
* by the number of users that have been given the term.  We're not doing any checks for users specifically here.
* We're just updating the count with no specifics for simplicity.

* See the _update_post_term_count() function in WordPress for more info.
*
* @param array $terms List of Term taxonomy IDs
* @param object $taxonomy Current taxonomy object of terms
*/
function update_type_de_membre_count( $terms, $taxonomy ) {
    global $wpdb;

    foreach ( (array) $terms as $term ) {

	$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

	do_action( 'edit_term_taxonomy', $term, $taxonomy );
	$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
	do_action( 'edited_term_taxonomy', $term, $taxonomy );
    }
}

//gestion des données l'activation/désactivation du plugin
register_activation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'create_member_role' ) );
register_activation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'create_bo_roles' ) );
register_activation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'add_admin_cap' ) );
register_deactivation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'remove_member_role' ) );
register_deactivation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'remove_bo_roles' ) );
register_deactivation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'remove_admin_cap' ) );


