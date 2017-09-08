<?php
/* 
 * Fichier gérant l'affichage des barres latérales
 *
 */

$list_cpt_instances = get_cpt_instances();
$list_cpt_composants = get_cpt_composants();
if( is_page_template('page-templates/tableau-de-bord.php') ) {
    //page supervision
    dynamic_sidebar( 'sidebar-supervision' );
} elseif ( is_singular( $list_cpt_instances ) ) {
    //page tableau de bord projet
    dynamic_sidebar( 'sidebar-tdb-project' );
} elseif ( is_singular( $list_cpt_composants ) ) {
    //page internes projet
    dynamic_sidebar( 'sidebar-internal-project' );
}