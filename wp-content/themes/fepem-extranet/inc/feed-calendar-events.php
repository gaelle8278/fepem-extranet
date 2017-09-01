<?php
/* 
 * Fichier en interaction avec fullcaldendar.js servant à générer la vue Agenda du calendrier des instances
 *
 * Récupération des événements et envoi en json
 */

//json file
header('Content-Type:application/json');

// wp load pour avoir les fonctionnalités de wordpress
require( __DIR__ . '/../../../../wp-load.php' );

global $wpdb;

$id_instance = intval($_POST['instance_id']);
$id_calendrier= get_post_meta($id_instance, '_meta_calendar_commission',true);

$calendar_events=[];
$events=getevents_of_calendar($id_calendrier);

if(!empty($events)) {
   foreach($events as $event) {
        $tab_event=[];
        $tab_event['title']=$event->post_title;

        $startdate=get_post_meta($event->ID, '_ecp_event_startdate', true);
        //prise en compte de la timezone définie dans Wordpress (GMT)
        $formatstartdate=date('Y-m-d H:i:s', $startdate);
        $gmtsd=strtotime(get_gmt_from_date($formatstartdate));
        //date au format ISO 8601
        $tab_event['start' ]=date('c',$gmtsd);

        $enddate=get_post_meta($event->ID, '_ecp_event_enddate', true);
        //prise en compte de la timezone définie dans Wordpress (GMT)
        $formatenddate=date('Y-m-d H:i:s', $enddate);
        $gmted=strtotime(get_gmt_from_date($formatenddate));
        //date au format ISO 8601
        $tab_event['end']=date('c',$gmted);
        
        $calendar_events[]=$tab_event;
    }
}



// Send JSON to the client.
echo json_encode($calendar_events);