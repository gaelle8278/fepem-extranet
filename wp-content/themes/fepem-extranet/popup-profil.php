<?php
/**
 * Code de la popup
 *
 * @package WordPress
 */
?>
<div class="popup-modal blur-effect" id="popup-profil">
    <div class="popup-content">
        <div class="popup-header">
            <p>Header</p>
        </div>
        <div class="popup-body">
            <?php echo do_shortcode("[infos-compte]"); ?>
        </div>
        <div>
            <a href='#' class="popup-close button-close">Fermer</a>
        </div>
        <div class="popup-close cross-popup-close"></div>
    </div>
</div>
