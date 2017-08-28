<!doctype html>
<!--[if (lte IE 7)&!(IEMobile)]> <html lang="fr" class="ie7 old-ie no-js"> <![endif]-->
<!--[if (IE 8)&!(IEMobile)]> <html lang="fr" class="ie8 old-ie no-js"> <![endif]-->
<!--[if (gt IE 8)&!(IEMobile)]><!--> <html lang="fr" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php
            if ( is_search() ) {
                echo 'Résultats de recherche pour "'.get_search_query().'" | ';
            } elseif (is_front_page() ) {
                echo "Extranet";
            } else {
                wp_title('',true);
            }
            ?>
        </title>
        <meta name="description" content="<?php wp_title('',true); ?>">
        <!--<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() ?>/images/favicon-32x32.png">-->
        
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script>
		document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'js');
	</script>

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <?php
        get_template_part("popup-profil");
        ?>
        <div id="site-container">
            <?php
            $member=get_member_connected();
            ?>
            <header>
                <div class="site-content">
                    <div class="site-branding">
                        <a href="<?php echo home_url(); ?>">Extranet Fepem</a>
                    </div><!--@whitespace
                    --><div class="member-header">
                        <?php
                        if($member) {
                            ?>
                            <span><?php echo ucfirst($member->user_firstname)." ".ucfirst($member->user_lastname); ?> </span>
                            <span><a href="<?php echo home_url('profil');  ?>" title="Mon profil" class="popup-button" data-modal="popup-profil">Mon profil</a> </span>
                            <span><a href="<?php echo wp_logout_url(); ?>" title="Déconnexion" > x </a></span>
                        <?php
                        }
                        ?>
                    </div>
                    <nav></nav>
                </div>
            </header>
            <main>

