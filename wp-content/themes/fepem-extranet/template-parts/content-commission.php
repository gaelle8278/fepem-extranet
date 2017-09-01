<?php
/**
 * Content of template for CPT commission
 */
?>
<div>
    <?php
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