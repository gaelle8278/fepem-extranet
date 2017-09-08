<?php
/* 
 * Map custom capabilities
 */

add_filter( 'map_meta_cap', 'extranetecp_map_meta_cap', 10, 4 );

function extranetecp_map_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a movie, get the post and post type object. */
	if ( 'edit_commission' == $cap || 'delete_commission' == $cap || 'read_commission' == $cap
             || 'edit_fcommission' == $cap || 'delete_fcommission' == $cap || 'read_fcommission' == $cap
            || 'edit_oscommission' == $cap || 'delete_oscommission' == $cap || 'read_oscommission' == $cap
            || 'edit_cpcommission' == $cap || 'delete_cpcommission' == $cap || 'read_cpcommission' == $cap
            || 'edit_ecp_calendrier' == $cap || 'delete_ecp_calendrier' == $cap || 'read_ecp_calendrier' == $cap
            || 'edit_ecp_fcalendrier' == $cap || 'delete_ecp_fcalendrier' == $cap || 'read_ecp_fcalendrier' == $cap
            || 'edit_ecp_oscalendrier' == $cap || 'delete_ecp_oscalendrier' == $cap || 'read_ecp_oscalendrier' == $cap
            || 'edit_ecp_cpcalendrier' == $cap || 'delete_ecp_cpcalendrier' == $cap || 'read_ecp_cpcalendrier' == $cap
            || 'edit_ecp_event' == $cap || 'delete_ecp_event' == $cap || 'read_ecp_event' == $cap
            || 'edit_ecp_fevent' == $cap || 'delete_ecp_fevent' == $cap || 'read_ecp_fevent' == $cap
            || 'edit_ecp_osevent' == $cap || 'delete_ecp_osevent' == $cap || 'read_ecp_osevent' == $cap
            || 'edit_ecp_cpevent' == $cap || 'delete_ecp_cpevent' == $cap || 'read_ecp_cpevent' == $cap
            || 'edit_ecp_messagerie' == $cap || 'delete_ecp_messagerie' == $cap || 'read_ecp_messagerie' == $cap
            || 'edit_ecp_fmessagerie' == $cap || 'delete_ecp_fmessagerie' == $cap || 'read_ecp_fmessagerie' == $cap
            || 'edit_ecp_osmessagerie' == $cap || 'delete_ecp_osmessagerie' == $cap || 'read_ecp_osmessagerie' == $cap
            || 'edit_ecp_cpmessagerie' == $cap || 'delete_ecp_cpmessagerie' == $cap || 'read_ecp_cpmessagerie' == $cap
            || 'edit_ecp_message' == $cap || 'delete_ecp_message' == $cap || 'read_ecp_message' == $cap
            || 'edit_ecp_fmessage' == $cap || 'delete_ecp_fmessage' == $cap || 'read_ecp_fmessage' == $cap
            || 'edit_ecp_osmessage' == $cap || 'delete_ecp_osmessage' == $cap || 'read_ecp_osmessage' == $cap
            || 'edit_ecp_cpmessage' == $cap || 'delete_ecp_cpmessage' == $cap || 'read_ecp_cpmessage' == $cap
            || 'edit_ecp_ged' == $cap || 'delete_ecp_ged' == $cap || 'read_ecp_ged' == $cap
            || 'edit_ecp_fged' == $cap || 'delete_ecp_fged' == $cap || 'read_ecp_fged' == $cap
            || 'edit_ecp_osged' == $cap || 'delete_ecp_osged' == $cap || 'read_ecp_osged' == $cap
            || 'edit_ecp_cpged' == $cap || 'delete_ecp_cpged' == $cap || 'read_ecp_cpged' == $cap
            || 'edit_ecp_document' == $cap || 'delete_ecp_document' == $cap || 'read_ecp_document' == $cap
            || 'edit_ecp_fdocument' == $cap || 'delete_ecp_fdocument' == $cap || 'read_ecp_fdocument' == $cap
            || 'edit_ecp_osdocument' == $cap || 'delete_ecp_osdocument' == $cap || 'read_ecp_osdocument' == $cap
            || 'edit_ecp_cpdocument' == $cap || 'delete_ecp_cpdocument' == $cap || 'read_ecp_cpdocument' == $cap) {
            $post = get_post( $args[0] );
            $post_type = get_post_type_object( $post->post_type );

            /* Set an empty array for the caps. */
            $caps = array();
	}

	
	if ( 'edit_commission' == $cap || 'edit_fcommission' == $cap || 'edit_oscommission' == $cap || 'edit_cpcommission' == $cap
            || 'edit_ecp_calendrier' == $cap || 'edit_ecp_fcalendrier' == $cap || 'edit_ecp_oscalendrier' == $cap || 'edit_ecp_cpcalendrier' == $cap 
            || 'edit_ecp_event' == $cap || 'edit_ecp_fevent' == $cap || 'edit_ecp_osevent' == $cap || 'edit_ecp_cpevent' == $cap
            || 'edit_ecp_messagerie' == $cap || 'edit_ecp_fmessagerie' == $cap || 'edit_ecp_osmessagerie' == $cap || 'edit_ecp_cpmessagerie' == $cap
            || 'edit_ecp_message' == $cap || 'edit_ecp_fmessage' == $cap || 'edit_ecp_osmessage' == $cap || 'edit_ecp_cpmessage' == $cap
            || 'edit_ecp_ged' == $cap || 'edit_ecp_fged' == $cap || 'edit_ecp_osged' == $cap || 'edit_ecp_cpged' == $cap
            || 'edit_ecp_document' == $cap || 'edit_ecp_fdocument' == $cap || 'edit_ecp_osdocument' == $cap || 'edit_ecp_cpdocument' == $cap ) {
            /* If editing a movie, assign the required capability. */
            if ( $user_id == $post->post_author ) {
                $caps[] = $post_type->cap->edit_posts;
            } else {
                $caps[] = $post_type->cap->edit_others_posts;
            }
	} elseif ( 'delete_commission' == $cap || 'delete_fcommission' == $cap || 'delete_oscommission' == $cap || 'delete_cpcommission' == $cap
            || 'delete_ecp_calendrier' == $cap || 'delete_ecp_fcalendrier' == $cap || 'delete_ecp_oscalendrier' == $cap || 'delete_ecp_cpcalendrier' == $cap
            || 'delete_ecp_event' == $cap || 'delete_ecp_fevent' == $cap || 'delete_ecp_osevent' == $cap || 'delete_ecp_cpevent' == $cap
            || 'delete_ecp_messagerie' == $cap || 'delete_ecp_fmessagerie' == $cap || 'delete_ecp_osmessagerie' == $cap || 'delete_ecp_cpmessagerie' == $cap
            || 'delete_ecp_message' == $cap || 'delete_ecp_fmessage' == $cap || 'delete_ecp_osmessage' == $cap || 'delete_ecp_cpmessage' == $cap
            || 'delete_ecp_ged' == $cap || 'delete_ecp_fged' == $cap || 'delete_ecp_osged' == $cap || 'delete_ecp_cpged' == $cap
            || 'delete_ecp_document' == $cap || 'delete_ecp_fdocument' == $cap || 'delete_ecp_osdocument' == $cap || 'delete_ecp_cpdocument' == $cap ) {
            /* If deleting a movie, assign the required capability. */
            if ( $user_id == $post->post_author ) {
		$caps[] = $post_type->cap->delete_posts;
            } else {
		$caps[] = $post_type->cap->delete_others_posts;
            }
	} elseif ( 'read_commission' == $cap || 'read_fcommission' == $cap || 'read_oscommission' == $cap || 'read_cpcommission' == $cap
            || 'read_ecp_calendrier' == $cap || 'read_ecp_fcalendrier' == $cap || 'read_ecp_oscalendrier' == $cap || 'read_ecp_cpcalendrier' == $cap
            || 'read_ecp_event' == $cap || 'read_ecp_fevent' == $cap || 'read_ecp_osevent' == $cap || 'read_ecp_cpevent' == $cap
            || 'read_ecp_messagerie' == $cap || 'read_ecp_fmessagerie' == $cap || 'read_ecp_osmessagerie' == $cap || 'read_ecp_cpmessagerie' == $cap
            || 'read_ecp_message' == $cap || 'read_ecp_fmessage' == $cap || 'read_ecp_osmessage' == $cap || 'read_ecp_cpmessage' == $cap
            || 'read_ecp_ged' == $cap || 'read_ecp_fged' == $cap || 'read_ecp_osged' == $cap || 'read_ecp_cpged' == $cap
            || 'read_ecp_document' == $cap || 'read_ecp_fdocument' == $cap || 'read_ecp_osdocument' == $cap || 'read_ecp_cpdocument' == $cap) {
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