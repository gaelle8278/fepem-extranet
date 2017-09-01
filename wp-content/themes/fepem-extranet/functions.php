<?php
/*
 * Fichier contenant les fonctions de modifications du comportement par défaut de WP.
 */

/**
 * Inclusion des fonctions utiles
 */
require_once get_template_directory()."/inc/usefull-functions.php";
/**
 * Inclusion des constantes utiles
 */
require_once get_template_directory()."/inc/usefull-constants.php";

/**
 * Configuration à l'initialisation
 */
function extranetcp_setup() {
        /*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
        /*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
        /**
         * add of custom sizes
         */
	//add_image_size( 'article-list', 480, 350, true );
}
add_action( 'after_setup_theme', 'extranetcp_setup' );

/**
 * Inclusion des fichiers js
 */
function extranetcp_enqueue_scripts() {
    $js_directory = get_template_directory_uri() . '/js/';

    //enregistrment des scripts persos/tiers
    //wp_register_script( 'fullcalendarfr', $js_directory . 'locale/fullcalendar/fr.js');
    wp_register_script( 'moment', $js_directory . 'moment.min.js', array('jquery'));
    wp_register_script( 'fullcalendar', $js_directory . 'fullcalendar.min.js', array('jquery','moment'));
    

    //chargement des scripts nécessaires
    wp_enqueue_script( 'fullcalendarfr', $js_directory . 'locale/fullcalendar/fr.js', array('jquery','moment','fullcalendar'));
    wp_enqueue_script( 'main',$js_directory . 'main.js', array('jquery', 'jquery-ui-core', 'jquery-ui-tabs') );
    
}
add_action( 'wp_enqueue_scripts', 'extranetcp_enqueue_scripts' );

/**
 * Inclusion des fichiers css
 */
function extranetcp_enqueue_style() {
    $css_directory = get_template_directory_uri() . '/css/';

    wp_enqueue_style( 'jqueryui', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css', false);
    wp_enqueue_style( 'fullcalendar',  $css_directory  . 'fullcalendar.min.css', false);
    wp_enqueue_style( 'core',  get_template_directory_uri()  . '/style.css', false);
   //wp_enqueue_style( 'extranetcp-google-fonts', 'https://fonts.googleapis.com/css?family=PT+Serif:400,700', false );
}
add_action( 'wp_enqueue_scripts', 'extranetcp_enqueue_style' );

/**
 * Chargement des fichiers css et js pour le BO
 */
function extranetcp_admin_scripts_css() {
    //to use media Javascript API
    //wp_enqueue_media();

    wp_enqueue_style( 'jqueryui', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_style( 'admincss', get_template_directory_uri() . '/css/admin.css' );

    wp_enqueue_script( 'adminjs', get_template_directory_uri() . '/js/admin.js', array( 'jquery','jquery-ui-datepicker', 'jquery-ui-autocomplete') );
}
add_action( 'admin_enqueue_scripts', 'extranetcp_admin_scripts_css' );


// ajout de la feuille de style de l'éditeur back
/*add_action( 'init', 'extranetcp_custom_editor_style' );
function extrafepem_custom_editor_style() {
    add_editor_style( 'editor-style.css' );
}*/

/**
 * enregistrement de menus
 */

/*
 * Enregistrement des sidebars
 */
add_action( 'widgets_init', 'extranetcp_register_sidebars' );
function extranetcp_register_sidebars() {
    register_sidebar(
        array(
            'id' => 'sidebar-supervision',
            'name' => "Barre latérale supervision",
            'description'   => "Barre latérale pour la page d'accueil de l'Extranet",
            'before_widget' => '<div id="%1$s" class="sidebar_widget %2$s"><div class="wrapper">',
            'after_widget' => '</div></div> ',
            'before_title' => '<h4 class="sidebar_widget_title">',
            'after_title' => '</h4>'
        )
    );

    register_sidebar(
        array(
            'id' => 'sidebar-tdb-project',
            'name' => "Barre latérale tableau de bord",
            'description'   => "Barre latérale pour la page tableau de bord projet",
            'before_widget' => '<div id="%1$s" class="sidebar_widget %2$s"><div class="wrapper">',
            'after_widget' => '</div></div> ',
            'before_title' => '<h4 class="sidebar_widget_title">',
            'after_title' => '</h4>'
        )
    );
    register_sidebar(
        array(
            'id' => 'sidebar-internal-project',
            'name' => "Barre latérale projet",
            'description'   => "Barre latérale pour les pages internes projet",
            'before_widget' => '<div id="%1$s" class="sidebar_widget %2$s"><div class="wrapper">',
            'after_widget' => '</div></div> ',
            'before_title' => '<h4 class="sidebar_widget_title">',
            'after_title' => '</h4>'
        )
    );
}

/*
 * Image responsive
 */
function extranetcp_images( $html ) {
    $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
    return $html;
}
add_filter( 'post_thumbnail_html', 'extranetcp_images', 10 );
add_filter( 'image_send_to_editor', 'extranetcp_images', 10 );
add_filter( 'wp_get_attachment_link', 'extranetcp_images', 10 );

/**
 * Ajout de la possibilité d'uploader des fichiers avec des input file custom
 * dans les formulaires en admin
 */
function extranetcp_add_edit_form_multipart_encoding() {

    echo ' enctype="multipart/form-data"';

}
add_action('post_edit_form_tag', 'extranetcp_add_edit_form_multipart_encoding');

/**
 * Récupération du paramètre GET pour les pages vues du calendrier
 * 
 * Les calendriers sont affichés grace au template single-ecp_calendrier.php
 *
 */
function extranetcp_add_rewrite_calendar_pages() {
  global $wp_rewrite;
  add_rewrite_tag('%vue%','([^&]+)');
  $wp_rewrite->add_rule('ecp_calendrier/([^/]+)/([^/]+)','index.php?ecp_calendrier=$matches[1]&vue=$matches[2]','top');
  $wp_rewrite->add_rule('ecp_fcalendrier/([^/]+)/([^/]+)','index.php?ecp_fcalendrier=$matches[1]&vue=$matches[2]','top');

  $wp_rewrite->flush_rules();
}
add_action('init', 'extranetcp_add_rewrite_calendar_pages');

/**
 * Récupération des paramètres get pour la page gestion de la présence aux événements
 *
 */
function extranetcp_add_rewrite_gestion_event_presence_page() {
  global $wp_rewrite;
  add_rewrite_tag('%gestion_presence%','([^&]+)');
  add_rewrite_tag('%id_event%','([^&]+)');
  $wp_rewrite->add_rule('gestion-presence-evenement/([^/]+)/([^/]+)','index.php?pagename=gestion-presence-evenement&gestion_presence=$matches[1]&id_event=$matches[2]','top');

  $wp_rewrite->flush_rules();
}
add_action('init', 'extranetcp_add_rewrite_gestion_event_presence_page');

// Remove the logout link in comment form
add_filter( 'comment_form_logged_in', '__return_empty_string' );

//adaptation du format de la date affiché dans les commentaires
add_filter( 'get_comment_date', 'extranetcp_change_comment_date_format' );
function extranetcp_change_comment_date_format( $d ) {
    $d = date_i18n("l d F Y");
    return $d;
}

/**
 * Fonction appelée par requete Ajax qui met à jour la liste des membres dans les CPT messages et event
 *
 * @global type $wpdb
 */
function get_members_of_instance_byajaxcall() {
    global $wpdb;

    check_ajax_referer( 'nonce_change_select', 'security' );

    $id_instance = intval( $_POST['id_instance'] );
    $list_members=[];
    $members=get_post_meta($id_instance,'_meta_members_commission',false);
    if(!empty($members)) {
        foreach( $members as $id_member ) {
            $user_data=[];
            $user=get_user_by('ID',$id_member);
            $user_data['id']=$id_member;
            $user_data['prenom']=$user->first_name;
            $user_data['nom']=$user->last_name;
            $list_members[]=$user_data;

        }

    }

    echo json_encode($list_members);
    die(); // this is required to return a proper result
}
add_action( 'wp_ajax_update_members_notification', 'get_members_of_instance_byajaxcall' );

/**
 * Create custom filter for list admin comments
 */
function extranetecp_add_filer_comments() {

    // we are only getting the none-default post types here (no post, no page, no attachment)
    // you can also change this, just take a look at the get_post_types() function
    /*$args = array(
        'public'   => true,
        '_builtin' => false
    );*/
    //$post_types = get_post_types( $args, 'objects' ); // we get the post types as objects
    $post_types=[
        [
            'label'=>"Messages",
            'slug'=>"ecp_message"
        ],
        [
            'label'=>"Messages Fepem",
            'slug'=>"ecp_fmessage"
        ],

    ];

    if ($post_types) { // only start if there are custom post types

        // make sure the name of the select field is called "post_types"
        echo '<select name="post_type" id="filter-by-post-type">';

        // I also add an empty option to reset the filtering and show all comments of all post-types
        echo '<option value="">Tous les commentaires</option>';

        // for each post-type that is found, we will create a new <option>
        foreach ($post_types as $post_type) {

            $label = $post_type['label']; // get the label of the post-type
            $name = $post_type['slug']; // get the name(slug) of the post-type

            // value of the optionsfield is the name(slug) of the post-type
            echo '<option value="'.$name.'">'.$label.'</option>';

        }
        echo '</select>';
    }

}

if( in_array('administrator', get_current_user_roles()) ) {
    add_action( 'restrict_manage_comments', 'extranetecp_add_filer_comments' );
}

/**
 * Filtre les commentaires affichés dans la liste admin des commentaires
 * selon role utilisateur et type du post auquel est lié le commentaire
 */
function extranetecp_comment_list_by_role($clauses) {
    global $pagenow;
    
    if ('edit-comments.php' == $pagenow && current_user_can('manage_instances')) {
        $user_roles=get_current_user_roles();

        if( in_array('admin-fepem',$user_roles) ) {
            $post_type="ecp_fmessage";
        } elseif ( in_array('admin-fepem-exec',$user_roles) ) {
            $post_type="ecp_message";
        }
        if( isset( $post_type ) ) {
            $clauses['join'] = ", wp_posts";
            $clauses['where'] .= " AND wp_posts.post_type = '".$post_type."' AND wp_comments.comment_post_ID = wp_posts.ID";
        }
        
    }
        
    return $clauses;
}
add_filter('comments_clauses', 'extranetecp_comment_list_by_role');

/**
 * Mets à jour les stats dans le menu de la liste des commentaires en admin
 * La liste des commentaires étant filtrée selon role de l'utilisateur et le type de post
 */
function extranetecp_upate_list_comments_count( ) {
    global $pagenow;

    $stats_object=null;
    
    if ('edit-comments.php' == $pagenow && current_user_can('manage_instances') ) {
        //mise à jour si user à un role custom
        $user_roles=get_current_user_roles();
        if( in_array('admin-fepem',$user_roles) ) {
            $post_type="ecp_fmessage";
        } elseif ( in_array('admin-fepem-exec',$user_roles) ) {
            $post_type="ecp_message";
        }

        if( isset( $post_type )) {
            $count = wp_cache_get( "comments-{$post_type}", 'counts' );
            if ( false !== $count ) {
                return $count;
            }

            $stats = array('moderated'=>0,'approved'=>0,'post-trashed'=>0,'trash'=>0,'total_comments'=>0,'spam'=>0, 'all'=>0);
            $the_query = new WP_Query( array('post_type' => $post_type,'posts_per_page' => -1) );
            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    $comments = get_comment_count(get_the_id());
                    $stats['moderated'] = $stats['moderated'] + $comments['awaiting_moderation'];
                    $stats['approved'] = $stats['approved'] + $comments['approved'];
                    $stats['post-trashed'] = $stats['post-trashed'] + $comments['post-trashed'];
                    $stats['trash'] = $stats['trash'] + $comments['trash'];
                    $stats['total_comments'] = $stats['total_comments'] + $comments['total_comments'];
                    $stats['spam'] = $stats['spam'] + $comments['spam'];
                    $stats['all'] = $stats['all'] + $comments['all'];
                    

		endwhile;
            endif;
            wp_reset_postdata();


            $stats_object = (object) $stats;
            wp_cache_set( "comments-{$post_type}", $stats_object, 'counts' );
        }
    }
    return $stats_object;

}
add_filter('wp_count_comments','extranetecp_upate_list_comments_count');

/**
 * Enlève aux administrateurs d'intances les entrées de menu affichées grâce à la capacité edit_posts 
 * 
 * La capacité edit_posts est ajouté aux administrateurs d'instance pour qu'ils puissent modérer les commentaires
 * 
 */
function extranetecp_remove_edit_post_pages() {
    
    if ( current_user_can('manage_instances') ) {
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit.php?post_type=page');
    }
}
add_action( 'admin_menu', 'extranetecp_remove_edit_post_pages' );
/**
 * Empeche les administrateurs d'instance d'exécuter certaines actions directes autorisées par la capacité edit_posts
 *
 * La capacité edit_posts est ajouté aux administrateurs d'instance pour qu'ils puissent modérer les commentaires
 *
 */
function extranetcp_prevent_admin_access() {
    $screen=get_current_screen();
    if ( ($screen->post_type=="post" || $screen->post_type=="page" || empty($screen->post_type)) && current_user_can('manage_instances') ) {
        wp_die("Désolé vous n'avez pas accès à cette page.");
        exit();
    }
}
add_action( 'load-edit.php', 'extranetcp_prevent_admin_access' );
add_action( 'load-tools.php', 'extranetcp_prevent_admin_access' );
add_action( 'load-post.php', 'extranetcp_prevent_admin_access' );
add_action( 'load-post-new.php', 'extranetcp_prevent_admin_access' );
/**
 * Personnalise la page dashboard
 * @TODO add a custom dashboard welcome panel
 */
function extranetcp_customize_dashboard() {
    global $wp_meta_boxes;

    if (current_user_can('manage_instances')) {
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

        wp_add_dashboard_widget('custom_welcome_widget', 'Accueil', 'custom_dashboard_welcome');
        add_meta_box('custom_guide_widget', "Guide d'utilisation", 'custom_dashboard_guide', 'dashboard', 'side', 'high');
    }
}
add_action('wp_dashboard_setup', 'extranetcp_customize_dashboard' );

function custom_dashboard_welcome() {
    echo "<p>Page d'acceuil du backoffice d'administration</p>";
}
function custom_dashboard_guide() {
    //@TODO ajouter le guide d'utilisation
    echo "<p>Décrire le guide d'utilisation. Le découper en plusieurs parties</p>";
}

/**
 * Custom post type et metabox
 */
require_once get_template_directory() . '/inc/cpt-metaboxes.php';

/**
 * Custom post type map custom capabilities
 */
require_once get_template_directory() . '/inc/cpt-capabilites.php';

/**
 * Custom tables
 */
require_once get_template_directory() . '/inc/custom-tables.php';