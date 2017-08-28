<?php
/**
 * Plugin Name:       Extranet Login
 * Description:       A plugin that replaces the WordPress login flow with a custom page.
 * Version:           1.0.0
 * Author:            GaÃ«lle Rauffet
 */

class Extranet_Login_Plugin {

    /**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'add_config') );

        // shortcode pour afficher formulaire de connexion
        add_shortcode( 'extranet-login-form', array( $this, 'render_login_form' ) );
        //shortcode pour afficher formulaire de demande de récupération de mot de passe
        add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
        //shortcode pour afficher formulaire de réinitialisation du mot de passe
        add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
        // shortcode to render user profil infos
        add_shortcode( 'infos-compte', array( $this, 'render_user_infos_view' ) );

        //redirection vers la page de login perso lorsque la page de login par défaut est appelée
        add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
        //interception du processus d'authentifcation pour rediriger vers la page de login perso si erreurs :
        // priorité haute (101) pour que la fonction soit appelée à la fin de la chaine de filtre et qu'elle récolte les erreurs éventuelles
        add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
        // redirection après déconnexion
        add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
        //redirection après connexion réussie
        add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
        //redirection vers la page de demande de récupération de mot de passe perso
        add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
        // traitement du formulaire de demande de récupération de mot de passe
        add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
        //personnalisation du contenu du mail envoyé suite à une demande de récupération de mot de passe

        //personnalisation du titre du mail envoyé suite à une demande de récupération de mot de passe
        add_filter( 'retrieve_password_title', array( $this, 'replace_retrieve_password_title' ), 10, 4 );
        //redirection vers la page de réinitialisation de mot de passe perso
        add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
        //traitement du formulaire de réinitialisation de mot de passe
        add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

    }

    /**
    * Plugin activation hook.
    *
    * Creates all WordPress pages needed by the plugin.
    */
    public static function plugin_activated() {
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'connexion' => array(
                'title' => "Connexion",
                'content' => '[extranet-login-form]'
            ),
            'profil' => array(
                'title' => "Mon profil",
                'content' => '[infos-compte]'
            )
            /*'mot-de-passe-perdu' => array(
                'title' => "Récupérer votre mot de passe.",
                'content' => '[custom-password-lost-form]'
            ),
            'recuperation-mot-de-passe' => array(
                'title' => "Définir un nouveau mot de passe",
                'content' => '[custom-password-reset-form]'
            )*/
        );

        foreach ( $page_definitions as $slug => $page ) {
            // Check that the page doesn't exist already
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add the page using the data from the array above
                wp_insert_post(
                    array(
                        'post_content'   => $page['content'],
                        'post_name'      => $slug,
                        'post_title'     => $page['title'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }

    /**
     * Fonction pour ajouter des configs
     */
    public function add_config() {
        add_image_size( 'profile-picture-sd', 150, 150, true );
        add_image_size( 'profile-picture-md', 250, 250, true );
    }

    /**
    * A shortcode for rendering the login form.
    *
    * @param  array   $attributes  Shortcode attributes.
    * @param  string  $content     The text content for shortcode. Not used.
    *
    * @return string  The shortcode output
    */
    public function render_login_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        if ( is_user_logged_in() ) {
            //in shortcode so redirection not possible because some page html already displayed
            return $this->get_template_html('logout');

        }

        // Pass the redirect parameter to the WordPress login functionality: by default,
        // don't specify a redirect, but if a valid redirect URL has been passed as
        // request parameter, use it.
        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        // erreur(s) à la connexion
        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );

            foreach ( $error_codes as $code ) {
                $errors []= $this->get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;

        // message redirection page connexion après déconnexion
        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

        // message redirection page connexion après demande de mot de passe
        $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

        // message redirection page connexion après changemet de mot de passe
        $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

        // Render the login form using an external template
        return $this->get_template_html( 'login_form', $attributes );
    }

    /**
    * Hook to redirect the user to the custom login when default wp-login.php is called.
    */
    public function redirect_to_custom_login() {
        // redirection uniquement si la page de login est appelée
        // l'authentification (POST) doit être gérée par wp-login.php
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user( $redirect_to );
                exit;
            }

            // The rest are redirected to the login page
            $login_url = home_url( 'connexion' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    /**
    * Hook to redirect the user after authentication if there were any errors.
    *
    * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
    * @param string            $username   The user name used to log in.
    * @param string            $password   The password used to log in.
    *
    * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
    */
    public function maybe_redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter (most likely,
        // the default WordPress authentication) functions have found errors
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );

                $login_url = home_url( 'connexion' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );

                wp_redirect( $login_url );
                exit;
            }
        }

        return $user;
    }

    /**
    * Hook to redirect to custom login page after the user has been logged out.
    */
    public function redirect_after_logout() {
        $redirect_url = home_url( 'connexion?logged_out=true' );
        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
    * Hook to returns the URL to which the user should be redirected after the (successful) login.
    *
    * @param string           $redirect_to           The redirect destination URL.
    * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
    * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
    *
    * @return string Redirect URL
    */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
        $redirect_url = home_url('connexion');

        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }
        
        if( in_array( 'member-extranet', $user->roles ) ) {
            // les membres de l'Extranet sont redirigés vers la page d'accueil
            $redirect_url = home_url();
        } else {
            //si administrateur et si le paramètre requested_redirect_to est défini, requested_redirect_to est utilisé
            if ( user_can( $user, 'manage_options' ) && $requested_redirect_to != '') {
                $redirect_url = $requested_redirect_to;
            } else {
                //sinon redirection vers le tabelau de bord
                $redirect_url = admin_url();
            }
        }

        return wp_validate_redirect( $redirect_url, home_url() );
    }

    /**
    * Hook to redirect the user to the custom "Forgot your password?" page instead of
    * wp-login.php?action=lostpassword.
    */
    public function redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
                exit;
            }

            wp_redirect( home_url( 'mot-de-passe-perdu' ) );
            exit;
        }
    }

    /*
     * A shortcode for rendering the form used to initiate the password reset.
    *
    * @param  array   $attributes  Shortcode attributes.
    * @param  string  $content     The text content for shortcode. Not used.
    *
    * @return string  The shortcode output
    */
    public function render_password_lost_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return "Vous êtes déjà connecté";
        } else {
            // Retrieve possible errors from request parameters
            $attributes['errors'] = array();
            if ( isset( $_REQUEST['errors'] ) ) {
                $error_codes = explode( ',', $_REQUEST['errors'] );
                foreach ( $error_codes as $error_code ) {
                    $attributes['errors'] []= $this->get_error_message( $error_code );
                }
            }
            return $this->get_template_html( 'password_lost_form', $attributes );
        }
    }

    /**
    * Hook to initiate password reset.
    */
    public function do_password_lost() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();
            if ( is_wp_error( $errors ) ) {
                // Errors found
                $redirect_url = home_url( 'mot-de-passe-perdu' );
                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
            } else {
                // Email sent
                $redirect_url = home_url( 'connexion' );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
            }

            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
    * Hook to return the message body for the password reset mail.
    * Called through the retrieve_password_message filter.
    *
    * @param string  $message    Default mail message.
    * @param string  $key        The activation key.
    * @param string  $user_login The username for the user.
    * @param WP_User $user_data  WP_User object.
    *
    * @return string   The mail message to send.
    */
    public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg  = "Bonjour,\r\n\r\n";
        $msg .= "Vous avez fait une demande de récupération de mot de passe.\r\n\r\n";
        $msg .= "Si c'est une erreur et que vous n'avez pas fait cette demande, ignorez simplement ce message.\r\n\r\n";
        $msg .= "Pour réinitialiser votre mot de passe veuillez vous rendre à l'adresse suivante :\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= "Merci\r\n";

        return $msg;
    }

    /**
     * Hook to return the title for the password reset mail.
     * Called through the retrieve_password_title filter.
     *
     * @param type $title   Default mail title
     * @return string   The title of mail to send
     */
    public function replace_retrieve_password_title( $title ) {
        $title= "Extranet - Récupération mot de passe";
        
        return $title;
    }

    /*
    * Hook to redirect to the custom password reset page, or the login page
    * if there are errors.
    */
    public function redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            // Verify key / login combo
            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'connexion?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'connexion?login=invalidkey' ) );
                }
                exit;
            }

            $redirect_url = home_url( 'recuperation-mot-de-passe' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
    * A shortcode for rendering the form used to reset a user's password.
    *
    * @param  array   $attributes  Shortcode attributes.
    * @param  string  $content     The text content for shortcode. Not used.
    *
    * @return string  The shortcode output
    */
    public function render_password_reset_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return "Vous êtes déjà connecté";
        } else {
            if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
                $attributes['login'] = $_REQUEST['login'];
                $attributes['key'] = $_REQUEST['key'];

                // Error messages
                $errors = array();
                if ( isset( $_REQUEST['error'] ) ) {
                    $error_codes = explode( ',', $_REQUEST['error'] );

                    foreach ( $error_codes as $code ) {
                        $errors []= $this->get_error_message( $code );
                    }
                }
                $attributes['errors'] = $errors;

                return $this->get_template_html( 'password_reset_form', $attributes );
            } else {
                return "Lien de récupération de mot de passe invalide";
            }
        }
    }

    /**
     * Hook to reset the user's password if the password reset form was submitted.
    */
    public function do_password_reset() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];

            $user = check_password_reset_key( $rp_key, $rp_login );

            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'connexion?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'connexion?login=invalidkey' ) );
                }
                exit;
            }

            if ( isset( $_POST['pass1'] ) ) {
                if ( $_POST['pass1'] != $_POST['pass2'] ) {
                    // Passwords don't match
                    $redirect_url = home_url( 'recuperation-mot-de-passe' );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                if ( empty( $_POST['pass1'] ) ) {
                    // Password is empty
                    $redirect_url = home_url( 'recuperation-mot-de-passe' );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                // Parameter checks OK, reset password
                reset_password( $user, $_POST['pass1'] );
                wp_redirect( home_url( 'connexion?password=changed' ) );
            } else {
                echo "Demande invalide.";
            }

            exit;
        }
    }

    /**
     * A shortcode for display the informations of connected user
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_user_infos_view ( $attributes, $content = null ) {
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( ! is_user_logged_in() ) {
            return $this->get_template_html('login_form');
        }

        $id_user=get_current_user_id();
        $user_info = get_userdata($id_user);
        $attributes['identifiant'] = $user_info->user_login;
        $attributes['lastname'] = $user_info->last_name;
        $attributes['firstname'] = $user_info->first_name;
        $attributes['email']=$user_info->user_email;
        //additional user fields provided by plugin extranet-memeber
        $user_image = esc_url( get_user_meta(  $id_user, 'user_meta_image', true ) );
        // make sure the field is set
        if ( isset( $user_image ) && $user_image ) {
            $attributes['picture'] = $this->get_additional_user_meta_thumb($id_user);
        }
        $attributes['region']=get_user_meta(  $id_user, 'user_meta_region', true );
        $attributes['tel']=get_user_meta(  $id_user, 'user_meta_tel', true );



        return $this->get_template_html( 'user_info', $attributes );

    }

    /**
    * Finds and returns a matching error message for the given error code.
    *
    * @param string $error_code    The error code to look up.
    *
    * @return string               An error message.
    */
    private function get_error_message( $error_code ) {
        switch ( $error_code ) {
            case 'empty_username':
                return "Vous devez indiquer une adresse e-mail.";

            case 'empty_password':
                return "Vous devez indiquer un mot de passe.";

            case 'invalid_username':
                return "L'adresse email n'existe pas.";

            case 'incorrect_password':
                $err = "Le mot de passe est incorrect. <a href='%s'>L'avez-vous oublié ?</a>?";
                return sprintf( $err, wp_lostpassword_url() );

            case 'invalid_email':
            case 'invalidcombo':
                return "Aucun compte n'existe avec cette adresse e-mail.";

            case 'expiredkey':
            case 'invalidkey':
                return "Le lien de récupération de mot de passe n'est plus valide.";

            case 'password_reset_mismatch':
                return "Les 2 mots de passe ne correspondent pas.";

            case 'password_reset_empty':
                return "Les mots de passe ne doivent pas être vides.";

            default:
                break;
        }

        return "Une erreur innatendue est survenue. Veuillez réessayer ultérieurement.";
    }

    /**
     * Manages redirection of the connected user to the correct page depending
     * on whether he / she is an admin or not.
     *
     * @param string $redirect_to   An optional redirect_to URL for admin users
     */
    private function redirect_logged_in_user( $redirect_to = null ) {
        
        $user = wp_get_current_user();
        if( in_array( 'member-extranet', $user->roles ) ) {
            //si c'est un membre de l'Extranet => redirection vers la page d'accueil
            wp_redirect( home_url() );
        } else {
            //autres roles accèdent au BO
            if ( user_can( $user, 'manage_options' ) && $redirect_to ) {
                //si c'est un administrateur prise en compte du paramètre redirect_to
                wp_safe_redirect( $redirect_to );
            } else {
                //sinon redirection vers l'url admin par défaut
                wp_redirect( admin_url() );
            }
        }
    }

    /**
    * Renders the contents of the given template to a string and returns it.
    *
    * @param string $template_name The name of the template to render (without .php)
    * @param array  $attributes    The PHP variables for the template
    *
    * @return string               The contents of the template.
    */
    private function get_template_html( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'custom_login_before_' . $template_name );

        require( 'templates/' . $template_name . '.php');

        do_action( 'custom_login_after_' . $template_name );

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Return an ID of an attachment by searching the database with the file URL.
     *
     * First checks to see if the $url is pointing to a file that exists in
     * the wp-content directory. If so, then we search the database for a
     * partial match consisting of the remaining path AFTER the wp-content
     * directory. Finally, if a match is found the attachment ID will be
     * returned.
     *
     * http://frankiejarrett.com/get-an-attachment-id-by-url-in-wordpress/
     *
     * @return {int} $attachment
     */
    private function get_attachment_image_by_url( $url ) {

        // Split the $url into two parts with the wp-content directory as the separator.
        $parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

        // Get the host of the current site and the host of the $url, ignoring www.
        $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
        $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

        // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
        if ( !isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
            return;
        }

        // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
        // Example: /uploads/2013/05/test-image.jpg
        global $wpdb;

        $prefix     = $wpdb->prefix;
        $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );

        // Returns null if no attachment is found.
        return $attachment[0];
    }

    /*
     * Retrieve the appropriate image size
     */
    private function get_additional_user_meta_thumb( $id_user ) {

        $attachment_url = esc_url( get_user_meta(  $id_user, 'user_meta_image', true ) );

         // grabs the id from the URL using Frankie Jarretts function
        $attachment_id = $this->get_attachment_image_by_url( $attachment_url );

        // retrieve the thumbnail size of our image
        $image_thumb = wp_get_attachment_image_src( $attachment_id, 'profile-picture-md' );

        // return the image thumbnail
        return $image_thumb[0];

    }

}

// Initialize the plugin
$personalize_login_pages_plugin = new Extranet_Login_Plugin ();

// Create the custom pages at plugin activatio
register_activation_hook( __FILE__, array( 'Extranet_Login_Plugin', 'plugin_activated' ) );