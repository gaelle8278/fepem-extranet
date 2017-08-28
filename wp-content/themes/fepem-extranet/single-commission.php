<?php
/* 
 * Page d'affichage d'une commission
 */

//check if user can access Extranet
redirect_user_if_no_access_extranet();

//paramètre indiquant la page

$section = "tdb";

//fetch instance
$instance = get_queried_object();

//check if user can access instance
if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
    get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$section);
                ?>
                <div>
                    <?php
                    $all_composants = getcomposants_of_instances([$instance->ID]);

                    //affichage du contenu du CPT commission
                    while (have_posts()) : the_post();


                    endwhile;

                    //affichage des composants
                    if(!empty($all_composants)) {
                        foreach($all_composants as $composant) {
                            ?>
                            <div class="item-composant">
                                <span class="tag-composant">
                                    <span class="<?php echo $composant['tag-class']; ?>"><?php echo $composant['tag']; ?></span>
                                </span><!-- @whitespace
                                --><span class="title-composant">
                                    <span>
                                        <a href="<?php echo $composant['lien']; ?>"><?php echo $composant['titre']; ?></a>
                                    </span>
                                </span><!-- @whitespace
                                --><span class="date-composant">
                                    <span><?php echo $composant['date']; ?></span>
                                </span>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section><!--@whitespace
        --><aside>
            <?php get_sidebar(); ?>
        </aside>
    </div>
    <?php

    get_footer();
} else {
    wp_redirect(home_url('page-non-accessible'));
    exit;
}