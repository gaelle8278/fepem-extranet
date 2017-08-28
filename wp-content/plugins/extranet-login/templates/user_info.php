<?php
/**
* Template to display informations about connected user
*/
?>

<div id="user-infos">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2>Votre compte</h2>
    <?php endif; ?>

    <div>
        <?php
        if ( isset($attributes['picture']) ) {
            ?>
            <img alt="picture" src="<?php echo $attributes['picture']; ?>" />
            <?php
        }
        ?>
    </div>
    <div>
        <p>Nom: <?php echo $attributes['firstname']; ?></p>
        <p>Prénom: <?php echo $attributes['lastname']; ?></p>
        <p>E-mail: <?php echo $attributes['email']; ?></p>
        <p>Région: <?php echo $attributes['region']; ?></p>
        <p>Téléphone: <?php echo $attributes['tel']; ?></p>
    </div>

</div>
