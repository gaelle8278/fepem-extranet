<?php
/**
 * Template Name: Tableau de bord
 *
 * @package WordPress
 * @since Extranet 1.0
 */

redirect_user_if_no_access_extranet();

$id_connected_user = get_current_user_id();

//récupération des instances auquelles à accès l'utilisateur
$instances = get_instances_of_user($id_connected_user);

$all_composants_view_instance=[];
$list_id_instances_view_activity=[];
$all_composants_view_activity=[];
if(!empty($instances)) {

    //pour la vue par instance
    foreach($instances as $instance) {
        $id_instance = $instance->ID;
        $list_id_instances_view_activity[]=$id_instance;

        //récupération des composants ordonnés pour chaque instance
        $list_composants_to_display=getcomposants_of_instances([$id_instance], true, false);

        //ajout de l'instance à la liste globale
        $all_composants_view_instance[$id_instance]['titre'] = $instance->post_title ;
        $all_composants_view_instance[$id_instance]['lien'] = get_permalink($id_instance);
        $all_composants_view_instance[$id_instance]['composants'] = $list_composants_to_display;

    }

    //pour la vue par activité : récupération des composants pour toutes les instances
    $all_composants_view_activity = getcomposants_of_instances($list_id_instances_view_activity, true, false);
    

}

get_header();
?>
<div class="site-content">
    <section>
        <div class="wrapper">

            <div id="tdb-tabs" class="tabs-pills">
                <ul>
                    <li><a href="#tdb-instances">Vue par instance</a></li>
                    <li><a href="#tdb-activities">Vue par activités</a></li>
                </ul>
            
                <div id="tdb-instances">
                    <?php
                    //var_dump($all_composants);
                    if(!empty($all_composants_view_instance)) {
                        foreach($all_composants_view_instance as $id_instance => $infos_instance) {
                            ?>
                            <h2 class="section-header header-highlight"><a href="<?php echo $infos_instance['lien']; ?>"><?php echo $infos_instance['titre']; ?></a></h2>
                            <?php
                            if(!empty($infos_instance['composants'])) {
                                foreach($infos_instance['composants'] as $composant) {
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
                        }
                    }
                    ?>
                </div>
                <div id="tdb-activities">
                    <?php
                    if(!empty($all_composants_view_activity)) {
                        foreach($all_composants_view_activity as $composant) {
                            ?>
                            <div class="item-composant">
                                <span class="tag-composant">
                                    <span class="<?php echo $composant['tag-class']; ?>"><?php echo $composant['tag']; ?></span>
                                </span><!-- @whitespace
                                --><span class="title-composant">
                                    <span>
                                        <?php echo $composant['parent-instance-title']; ?><br />
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
        </div>
    </section><!--@whitespace
    --><aside>
        <?php get_sidebar(); ?>
    </aside>
</div>
<?php

get_footer();