<?php
/* 
 * Map custom capabilities
 */

add_filter( 'map_meta_cap', 'extranetecp_map_meta_cap', 10, 4 );

function extranetecp_map_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a movie, get the post and post type object. */
	if ( 'edit_commission' == $cap || 'delete_commission' == $cap || 'read_commission' == $cap
             || 'edit_fcommission' == $cap || 'delete_fcommission' == $cap || 'read_fcommission' == $cap 
            || 'edit_ecp_calendrier' == $cap || 'delete_ecp_calendrier' == $cap || 'read_ecp_calendrier' == $cap
            || 'edit_ecp_fcalendrier' == $cap || 'delete_ecp_fcalendrier' == $cap || 'read_ecp_fcalendrier' == $cap
            || 'edit_ecp_event' == $cap || 'delete_ecp_event' == $cap || 'read_ecp_event' == $cap
            || 'edit_ecp_fevent' == $cap || 'delete_ecp_fevent' == $cap || 'read_ecp_fevent' == $cap
            || 'edit_ecp_messagerie' == $cap || 'delete_ecp_messagerie' == $cap || 'read_ecp_messagerie' == $cap
            || 'edit_ecp_fmessagerie' == $cap || 'delete_ecp_fmessagerie' == $cap || 'read_ecp_fmessagerie' == $cap
            || 'edit_ecp_message' == $cap || 'delete_ecp_message' == $cap || 'read_ecp_message' == $cap
            || 'edit_ecp_fmessage' == $cap || 'delete_ecp_fmessage' == $cap || 'read_ecp_fmessage' == $cap
            || 'edit_ecp_ged' == $cap || 'delete_ecp_ged' == $cap || 'read_ecp_ged' == $cap
            || 'edit_ecp_fged' == $cap || 'delete_ecp_fged' == $cap || 'read_ecp_fged' == $cap
            || 'edit_ecp_document' == $cap || 'delete_ecp_document' == $cap || 'read_ecp_document' == $cap
            || 'edit_ecp_fdocument' == $cap || 'delete_ecp_fdocument' == $cap || 'read_ecp_fdocument' == $cap) {
            $post = get_post( $args[0] );
            $post_type = get_post_type_object( $post->post_type );

            /* Set an empty array for the caps. */
            $caps = array();
	}

	
	if ( 'edit_commission' == $cap || 'edit_fcommission' == $cap 
            || 'edit_ecp_calendrier' == $cap || 'edit_ecp_fcalendrier' == $cap || 'edit_ecp_event' == $cap || 'edit_ecp_fevent' == $cap
            || 'edit_ecp_messagerie' == $cap || 'edit_ecp_fmessagerie' == $cap || 'edit_ecp_message' == $cap || 'edit_ecp_fmessage' == $cap
            || 'edit_ecp_ged' == $cap || 'edit_ecp_fged' == $cap || 'edit_ecp_document' == $cap || 'edit_ecp_fdocument' == $cap ) {
            /* If editing a movie, assign the required capability. */
            if ( $user_id == $post->post_author ) {
                $caps[] = $post_type->cap->edit_posts;
            } else {
                $caps[] = $post_type->cap->edit_others_posts;
            }
	} elseif ( 'delete_commission' == $cap || 'delete_fcommission' == $cap 
            || 'delete_ecp_calendrier' == $cap || 'delete_ecp_fcalendrier' == $cap || 'delete_ecp_event' == $cap || 'delete_ecp_fevent' == $cap
            || 'delete_ecp_messagerie' == $cap || 'delete_ecp_fmessagerie' == $cap || 'delete_ecp_message' == $cap || 'delete_ecp_fmessage' == $cap
            || 'delete_ecp_ged' == $cap || 'delete_ecp_fged' == $cap || 'delete_ecp_document' == $cap || 'delete_ecp_fdocument' == $cap ) {
            /* If deleting a movie, assign the required capability. */
            if ( $user_id == $post->post_author ) {
		$caps[] = $post_type->cap->delete_posts;
            } else {
		$caps[] = $post_type->cap->delete_others_posts;
            }
	} elseif ( 'read_commission' == $cap || 'read_fcommission' == $cap 
            || 'read_ecp_calendrier' == $cap || 'read_ecp_fcalendrier' == $cap || 'read_ecp_event' == $cap || 'read_ecp_fevent' == $cap
            || 'read_ecp_messagerie' == $cap || 'read_ecp_fmessagerie' == $cap || 'read_ecp_message' == $cap || 'read_ecp_fmessage' == $cap
            || 'read_ecp_ged' == $cap || 'read_ecp_fged' == $cap || 'read_ecp_document' == $cap || 'read_ecp_fdocument' == $cap) {
            /* If reading a private movie, assign the required capability. */
            if ( 'private' != $post->post_status ) {
		$caps[] = 'read';
            } elseif ( $user_id == $post->post_author ) {
		$caps[] = 'read';
            } else {
		$caps[] = $post_type->cap->read_private_posts;
            }
	}

	/* Return the capabilities required by the user. */
	return $caps;
}