<?php
/**
 * File to manage CPT commission
 */

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

    $authorized_post_type=["commission","fcommission"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    //s'il s'agit bien d'une sauvegarde volontaire
    if( ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && isset($_POST['selected_members_commission'])) {
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

    $calendar_object="";
    if( "commission" == get_post_type($post) ) {
        $calendar_object = "ecp_calendrier";
    } elseif ( "fcommission" == get_post_type($post) ) {
        $calendar_object = "ecp_fcalendrier";
    }

    $all_calendar=[];
    if( !empty( $calendar_object ) ) {
        $all_calendar = get_posts( array(
            'post_type' => $calendar_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ) );
    }

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
    $authorized_post_type=["commission","fcommission"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
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

    $messagerie_object="";
    if( "commission" == get_post_type($post) ) {
        $messagerie_object = "ecp_messagerie";
    } elseif ( "fcommission" == get_post_type($post) ) {
        $messagerie_object = "ecp_fmessagerie";
    }

    $all_messagerie=[];
    if( !empty( $messagerie_object ) ) {
        $all_messagerie = get_posts( array(
            'post_type' => $messagerie_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC',
        ) );
    }

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
    $authorized_post_type=["commission","fcommission"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    
    // nonce verification
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
    $authorized_post_type=["commission","fcommission"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
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

    $ged_object="";
    if( "commission" == get_post_type($post) ) {
        $ged_object = "ecp_ged";
    } elseif ( "fcommission" == get_post_type($post) ) {
        $ged_object = "ecp_fged";
    }

    $all_ged=[];
    if( !empty( $ged_object ) ) {
        $all_ged = get_posts( array(
            'post_type' => $ged_object,
            'posts_per_page' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC',
        ) );
    }

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
 * Fonction qui gère la sauvegarde de la ged associée à la commission
 * @param type $post_id
 */
function save_metabox_ged_commission( $post_id ) {
    $authorized_post_type=["commission","fcommission"];
    if ( ! in_array( get_post_type( $post_id ), $authorized_post_type ) ) {
        return $post_id;
    }

    // check user permission
    if ( !current_user_can( 'edit_post', $post_id )) {
        return $post_id;
    }

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
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

