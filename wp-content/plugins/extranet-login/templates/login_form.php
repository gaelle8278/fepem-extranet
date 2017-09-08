<?php
/* 
 * Code du shortcode affichant un formulaire de connexion
 *
 */
?>
<div class="login-form-container">
    <!-- shortcode attribute to indicate if a title is displayed or not -->
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php echo "Connexion" ?></h2>
    <?php endif; ?>

    <!-- Erreurs à la connexion -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Message après déconnexion -->
    <?php if ( $attributes['logged_out'] ) : ?>
        <p class="login-info">
            <?php echo "Vous êtes déconnecté."; ?>
        </p>
    <?php endif; ?>

    <!-- Message après demande de mot de passe -->
    <?php if ( $attributes['lost_password_sent'] ) : ?>
    <p class="login-info">
        <?php echo "Un lien pour réinitialiser votre mot de passe vous a été envoyé."; ?>
    </p>
    <?php endif; ?>

    <!-- messgae après changement de mot de passe -->
    <?php if ( $attributes['password_updated'] ) : ?>
        <p class="login-info">
            <?php echo "Le mot de passe a été changé."; ?>
        </p>
    <?php endif; ?>

    <div class="login-form-container">
        <form method="post" action="<?php echo wp_login_url(); ?>">
            <p class="login-username">
                <label for="user_login">Votre e-mail FEPEM</label>
                <input type="text" name="log" id="user_login">
            </p>
            <p class="login-password">
                <label for="user_pass">Mot de passe renseigné lors de l’inscription</label>
                <input type="password" name="pwd" id="user_pass">
            </p>
            <p><?php echo sprintf( "<p><a href='%s'>Oubli de vos identifiants</a></p>", wp_lostpassword_url() ); ?>
            <p class="login-submit">
                <input type="submit" value="Se connecter">
            </p>
        </form>
    </div>

    
</div>