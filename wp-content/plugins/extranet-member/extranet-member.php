<?php
/**
 * Plugin Name:       Membre Extranet
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

        </table><!-- end form-table -->
        <?php
    }

    /**
    * Saves additional user fields to the database
    */
    function save_additional_user_meta( $user_id ) {
        // only saves if the current user can edit user profiles
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;

        update_user_meta( $user_id, 'user_meta_image', $_POST['user_meta_image'] );

        if(isset($_POST['user_meta_region'])) {
            update_user_meta($user_id, 'user_meta_region', $_POST['user_meta_region']);
        }

        if(isset($_POST['user_meta_tel'])) {
            update_user_meta($user_id, 'user_meta_tel', $_POST['user_meta_tel']);
        }
    }

}

new Extranet_Member_Plugin();
//gestion du role à l'activation/désactivation du plugin
register_activation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'create_member_role' ) );
register_deactivation_hook( __FILE__, array( 'Extranet_Member_Plugin', 'remove_member_role' ) );

