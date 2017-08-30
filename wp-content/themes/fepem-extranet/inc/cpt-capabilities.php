<?php
/* 
 * Fichier pour gérer les droits BO vis-à-vis des CPT.
 */

add_filter( 'map_meta_cap', 'extranetecp_commission_meta_cap', 10, 4 );

function extranetecp_commission_meta_cap( $caps, $cap, $user_id, $args ) {
        /* If editing, deleting, or reading a movie, get the post and post type object. */
	if ( 'edit_commission' == $cap ) {
            $post = get_post( $args[0] );
            $post_type = get_post_type_object( $post->post_type );

            /* Set an empty array for the caps. */
            $caps = array();

            $edit=false;
            $type_participants= get_the_terms($post->ID, 'ecp_tax_type_participant');
            if( $type_participants != false ) {
                foreach($type_participants as $type) {
                    if($type->slug == 'fepem-executif' && user_can($user_id, 'admin-fepem-exec' )) {
                        $edit=true;
                    }
                }
            }

            if($edit==true) {
                $caps[]=$post_type->cap->edit_posts;
            }
                /*$user_info = get_userdata($user_id);
                $user_roles=$user_info->roles;*/
        }
	/* Return the capabilities required by the user. */
	return $caps;
    
}