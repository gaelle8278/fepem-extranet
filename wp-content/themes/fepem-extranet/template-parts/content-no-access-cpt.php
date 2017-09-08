<?php
/* 
 * Content called when a user can't access an element inside an instance
 */

if( is_singular( $list_cpt_document ) ) {
    ?>
    <p>Vous n'avez pas accès à ce document.</p>
    <?php
} elseif(is_singular( $list_cpt_message ) ) {
    ?>
    <p>Vous n'avez pas accès à ce message.</p>
    <?php
} elseif ( is_singular( $list_cpt_messagerie ) ) {
    ?>
    <p>Vous n'avez pas accès à cette messagerie.</p>
    <?php
} elseif ( is_singular( $list_cpt_ged ) ) {
    ?>
    <p>Vous n'avez pas accès à cette GED.</p>
    <?php
} else {
    ?>
    <p>Vous n'avez pas accès à cet élément.</p>
    <?php
}
?>



