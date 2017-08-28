<?php
/* 
 * Fichier gérant l'affichage des barres latérales
 *
 */

if( is_page_template('page-templates/tableau-de-bord.php') ) {
    //page supervision
    dynamic_sidebar( 'sidebar-supervision' );
} elseif( is_singular('commission' ) ) {
    //page tableau de bord projet
    dynamic_sidebar( 'sidebar-tdb-project' );
} elseif ( is_singular( ['ecp_calendrier', 'ecp_event', 'ecp_messagerie', 'ecp_message', 'ecp_ged', 'ecp_document'] ) ) {
    //page internes projet
    dynamic_sidebar( 'sidebar-internal-project' );

}