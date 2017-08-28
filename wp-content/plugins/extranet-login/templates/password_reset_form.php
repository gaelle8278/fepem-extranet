<?php
/* 
 * Code du shortcode affichant le formulaire de réinitialisation du mot de passe
 */
?>

<div id="password-reset-form" class="widecolumn">
    <!-- shortcode attribute to indicate if a title is displayed or not -->
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Pick a New Password', 'personalize-login' ); ?></h3>
    <?php endif; ?>

    <!-- Erreurs à la soumission du formulaire de de réinitialisation de mot de mot de passe -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
        <p>
            <label for="pass1">Nouveau mot de passe</label>
            <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
        </p>
        <p>
            <label for="pass2">Confirmer nouveau mot de passe</label>
            <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
        </p>

        <p class="description"><?php echo wp_get_password_hint(); ?></p>

        <p class="resetpass-submit">
            <input type="submit" name="submit" id="resetpass-button"
                   class="button" value="Changer mot de passe" />
        </p>
    </form>
</div>