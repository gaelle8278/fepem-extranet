<?php
/**
 * Content of template for CPT event
 */

while (have_posts()) : the_post();
    ?>
    <h2><?php the_title(); ?></h2>

    <p>
        Lieu : <?php echo get_post_meta(get_the_ID(), '_ecp_event_place', true); ?>
    </p>
    <p>
        Quand :
        <?php
        $startdate=date_i18n("j F Y", get_post_meta(get_the_ID(), '_ecp_event_startdate', true));
        $enddate=date_i18n("j F Y", get_post_meta(get_the_ID(), '_ecp_event_enddate', true));
        if($startdate == $enddate) {
            echo $startdate;
        } else {
            echo $startdate." - ".$enddate;
        }
        ?>
    </p>
    <p>
        Horaire : <?php echo date_i18n("H\hi", get_post_meta(get_the_ID(), '_ecp_event_startdate', true)); ?> à
         <?php echo date_i18n("H\hi", get_post_meta(get_the_ID(), '_ecp_event_enddate', true)); ?>
    </p>
    <p>
        <?php the_content(); ?>
    </p>
    <?php
endwhile;