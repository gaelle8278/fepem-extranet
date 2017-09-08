<?php
/* 
 * File to manage CPT GED et CPT Document
 */

/********************************************
 * Définition du CPT GED
 *
 *******************************************/
/**
 * Fonction qui gère l'affichage de la metabox pour lister les documents de la GED
 *
 * @param type $post
 */
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
$list_cpt_ged=get_cpt_ged();
foreach ( $list_cpt_ged as $cpt_ged ) {
    add_filter ("manage_edit-".$cpt_ged."_columns", "ecp_ged_edit_columns");
}

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
$list_cpt_ged=get_cpt_ged();
foreach ( $list_cpt_ged as $cpt_ged ) {
    add_action ("manage_".$cpt_ged."_posts_custom_column", "ecp_ged_custom_columns", 10, 2);
}

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
    $list_cpt_ged_of_document=get_cpt_ged_of_document();

    $selected_ged= wp_get_post_parent_id($post->ID);

    $ged_object="";
    if( array_key_exists( get_post_type($post), $list_cpt_ged_of_document) ) {
        $ged_object=$list_cpt_ged_of_document[get_post_type($post)];
    }

    $all_ged=[];
    if( !empty( $ged_object ) ) {
        $all_ged = get_posts( array(
            'post_type' => $ged_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ) );
    }
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
    $list_cpt_document=get_cpt_document();

    // only run this for event
    if ( ! in_array( get_post_type( $post_id ), $list_cpt_document ) ) {
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
    $list_cpt_document=get_cpt_document();

    // only run this for document
    if ( ! in_array( get_post_type( $post_id ), $list_cpt_document ) ) {
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
                $prev_path_file=WP_CONTENT_DIR . '/ged/'.$subdir."/".$file_name;
                $new_dir = WP_CONTENT_DIR . '/ged/'.$doc_ged_id;
                $new_path_file=$new_dir."/".$file_name;
                if( !is_dir( $new_dir) ) {
                    mkdir( $new_dir,  0777);
                }
                $new_url=WP_CONTENT_URL . '/ged/'.$doc_ged_id."/".$file_name;
                if( is_dir( $new_dir) ) {
                    $status_copy=rename($prev_path_file,$new_path_file);
                    if($status_copy==true){
                        //si déplacement de fichier ok, mise à jour de la meta avec la nouvelle url
                        update_post_meta($post_id,'_ecp_document_file_attached',esc_url($new_url));
                    }
                }
            }
        }
    }

    //custom upload dir removed
    remove_filter('upload_dir','ecp_document_upload_dir' );
}
//sauvagerde après asauvegarde de la GED de rattachement car le dossier d'upload se base sur cette donnée
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
    $list_cpt_document=get_cpt_document();

    if ( ! in_array (get_post_type( $post->ID ),$list_cpt_document ) ) {
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
$list_cpt_document=get_cpt_document();
foreach ( $list_cpt_document as $cpt_document ) {
    add_filter ("manage_edit-".$cpt_document."_columns", "ecp_document_edit_columns");
}

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

