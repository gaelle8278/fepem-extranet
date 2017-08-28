<?php
/* 
 * Page template des CPT événement
 */


//check if user can access Extranet
redirect_user_if_no_access_extranet();

//fetch object
$event= get_queried_object();

//fetch parent instance
$instance = get_parent_instance_of_event($event->ID);

$page_active="calendrier";

if(!empty($instance) && check_user_access_instance($instance->ID, get_current_user_id())) {
    get_header();
    ?>
    <div class="site-content">
        <section>
            <div class="wrapper">
                <h1><?php echo $instance->post_title; ?></h1>
                <?php
                display_extranet_menu($instance->ID,$page_active);

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
                    <p>
                        Horaire : <?php echo date_i18n("H\hi", get_post_meta(get_the_ID(), '_ecp_event_startdate', true)); ?> à
                        <?php echo date_i18n("H\hi", get_post_meta(get_the_ID(), '_ecp_event_enddate', true)); ?>
                    </p>
                    <p>
                        <?php the_content(); ?> </p>
                    </p>
                    <?php


                endwhile;
                ?>
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
