<?php
/* 
 * Code du shortcode affichant un formulaire de demande de récupération de mot de passe
 */
?>

<div id="password-lost-form" class="widecolumn">
    <!-- shortcode attribute to indicate if a title is displayed or not -->
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php echo "Récupérer votre mot de passe." ?></h3>
    <?php endif; ?>

    <!-- Erreurs à la demande de récupération de mot de passe -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        <?php
            echo "Indiquer votre e-mail et un lien vous sera envoyé à cette adresse vous permettant de saisir un nouveau mot de passe.";
        ?>
    </p>

    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login">E-mail</label>
            <input type="text" name="user_login" id="user_login">
        </p>

        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button"
                   value="Réinitialiser mon mot de passe"/>
        </p>
    </form>
</div>