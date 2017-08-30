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
        $add_supplier = add_role( 'member-extranet', 'Membre Extranet',
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
        $add_supplier = add_role( 'admin-fepem-exec', 'Administrateur Fepem Exécutif',
            array(
                'read' => true,
                'edit_pages' => true,
                'create_posts' => true,
                'manage_categories' => false,
                'edit_commissions' => true,
                'edit_others_commissions' => true,
                'delete_commissions' => true,
                'delete_others_commissions' => true,
                'read_private_commissions' => true,
                'publish_commissions' => true
            ));
    }

    /**
     * Fonction qui enlève les roles BO
     *
     * Appelée à la désactivation du plugin
     */
    public function remove_bo_roles() {
        remove_role( 'admin-fepem-exec' );
    }

    /**
     * Fonction qui gère l'ajout des capacités liées aux CPT au role Administrateur
     */
    public function add_admin_cap() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'read_private_commissions' );
        $role->add_cap( 'publish_commissions' );
        $role->add_cap( 'edit_commissions' );
        $role->add_cap( 'edit_others_commissions' );
        $role->add_cap( 'delete_commissions' );
        $role->add_cap( 'delete_others_commissions' );
    }

    /**
     * Fonction qui gère la suppression des capacités liées aux CPT au role Administrateur
     */
    public function remove_admin_cap() {
        $role = get_role( 'administrator' );
        $role->remove_cap( 'read_private_commissions' );
        $role->remove_cap( 'publish_commissions' );
        $role->remove_cap( 'edit_commissions' );
        $role->remove_cap( 'edit_others_commissions' );
        $role->remove_cap( 'delete_commissions' );
        $role->remove_cap( 'delete_others_commissions' );
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
        <h3>Informations supplémentaires</h3>

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

        /* Make sure the user can assign terms of the profession taxonomy before proceeding. */
	if ( !current_user_can( $tax->cap->assign_terms ) )
		return;

        /* Get the terms of the 'profession' taxonomy. */
	$terms = get_terms( 'ecp_tax_type_membre', array( 'hide_empty' => false ) );

        ?>
        <h3>Type de membre</h3>

	<table class="form-table">
            <tr>
                <th><label for="profession">Choisir le type de membre</label></th>
                <td><?php
                    /* If there are any profession terms, loop through them and display checkboxes. */
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
                            <label for="type-memebre-<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></label> <br />
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
				'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
				'edit_terms'   => 'edit_users',
				'delete_terms' => 'edit_users',
				'assign_terms' => 'read',
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
    * Unsets the 'posts' column and adds a 'users' column on the manage profession admin page.
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


