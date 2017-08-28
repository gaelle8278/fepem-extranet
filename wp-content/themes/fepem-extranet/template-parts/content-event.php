<?php
/* 
 * Template part pour afficher un événement
 *
 * @package fepem-extranet
 */

$startdate=get_post_meta(get_the_ID(),'_ecp_event_startdate',true);
                    $enddate=get_post_meta(get_the_ID(),'_ecp_event_enddate',true);
                    $start_day=date_i18n('j M Y', $startdate);
                    $start_hour=date_i18n('H\hi', $startdate);
                    $end_day=date_i18n('j M Y', $enddate);
                    $end_hour=date_i18n('H\hi', $enddate);