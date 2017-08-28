<?php
/* 
 * Fichier gérant les tables persos
 */

/**
 * Ajout du nom de la table stockant les confirmation de présence
 *
 * @global type $wpdb
 */

function extranetcp_register_event_presence() {
    global $wpdb;
    $wpdb->extranet_event_presence = "{$wpdb->prefix}extranet_event_presence";
}
add_action( 'init', 'extranetcp_register_event_presence', 1 );
add_action( 'switch_blog', 'extranetcp_register_event_presence' );

/*
 * Fonction qui crée la table de stockage des confirmations de présence
 * à l'activation du thème
 * 
 */
function extranetcp_create_tables() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    global $charset_collate;
    extranetcp_register_event_presence();

    $sql_create_table = "CREATE TABLE {$wpdb->extranet_event_presence} (
          confirmation_id bigint(20) unsigned NOT NULL auto_increment,
          user_id bigint(20) unsigned NOT NULL default '0',
          event_id bigint(20) unsigned NOT NULL default '0',
          confirmation_message text NOT NULL default '',
          confirmation_dest_email varchar(50) NOT NULL default '',
          confirmation_date datetime NOT NULL default '0000-00-00 00:00:00',
          PRIMARY KEY  (confirmation_id),
          KEY user_id (user_id),
          KEY event_id (event_id)
     ) $charset_collate; ";

    dbDelta( $sql_create_table );
}
add_action("after_switch_theme", "extranetcp_create_tables");

/**
 * Fonction qui renvoi le nom et le format des colonnes de la table
 *
 * @return type
 */
function extranetcp_get_event_presence_table_columns(){
    return array(
        'confirmation_id'=> '%d',
        'user_id'=> '%d',
        'event_id'=>'%d',
        'confirmation_message'=>'%s',
        'confirmation_dest_email'=>'%s',
        'confirmation_date'=>'%s',
    );
}

/**
 * Fonction pour insérer une confirmation de présence à un événement
 *
 * @global type $wpdb
 * @param type $data
 * @return int
 */
function extranetcp_insert_confirmation ( $data ) {
    global $wpdb;

    //Set default values
    $data = wp_parse_args($data, array(
                'date'=> current_time('timestamp'),
    ));

    //Check date validity
    if( !is_float($data['date']) || $data['date'] <= 0 )
        return 0;

    //Convert confirmation date from local timestamp to GMT mysql format
    $data['confirmation_date'] = date_i18n( 'Y-m-d H:i:s', $data['date'], true );

    //Initialise column format array
    $column_formats = extranetcp_get_event_presence_table_columns();

    //Force fields to lower case
    $data = array_change_key_case ( $data );

    //White list columns
    $data = array_intersect_key($data, $column_formats);

    //Reorder $column_formats to match the order of columns given in $data
    $data_keys = array_keys($data);
    $column_formats = array_merge(array_flip($data_keys), $column_formats);

    $wpdb->insert($wpdb->extranet_event_presence, $data, $column_formats);

    return $wpdb->insert_id;

}

/* old method before 3.4 of if prepare method is the only solution */
/*function delete_confirmation( $user_id, $event_id ) {
    global $wpdb;
    $sql = $wpdb->prepare("DELETE from {$wpdb->extranet_event_presence} WHERE user_id = %d and event_id= %d", $user_id, $event_id);
    $deleted = $wpdb->query( $sql );
}*/

/**
 * Fonction pour supprimer une confirmation de présence
 * 
 * @global type $wpdb
 * @param type $user_id
 * @param type $event_id
 * @return boolean
 */
function extranetcp_delete_confirmation ($user_id, $event_id) {
    global $wpdb;

    //event and user id must be positive integer
    $user_id = absint($user_id);
    $event_id = absint($event_id);

    if( empty($user_id) || empty($event_id) ) {
         return false;
    }

    do_action('extranetcp_delete_confirmation',$user_id, $event_id);

    $deleted = $wpdb->delete(
                    $wpdb->extranet_event_presence,
                    array(
                        'user_id' => $user_id,
                        'event_id' => $event_id
                    ),
                    array(
                        '%d',
                        '%d')
                );
    if( !$deleted ) {
        return false;
    }

    do_action('extranetcp_deleted_confirmation',$user_id, $event_id);

    return true;
}

/**
 * Fonction pour mettre à jour une confirmation de présence
 *
 * @global type $wpdb
 * @param type $log_id
 * @param type $data
 * @return boolean
 */
function extranetcp_update_confirmation( $confirmation_id, $data=array() ){
    global $wpdb;

    //Confirmation ID must be positive integer
    $confirmation_id = absint($confirmation_id);
    if( empty($confirmation_id) ) {
        return false;
    }

    //Initialise column format array
    $column_formats = extranetcp_get_event_presence_table_columns();

    //Force fields to lower case
    $data = array_change_key_case ( $data );

    //White list columns
    $data = array_intersect_key($data, $column_formats);

    //Reorder $column_formats to match the order of columns given in $data
    $data_keys = array_keys($data);
    $column_formats = array_merge(array_flip($data_keys), $column_formats);

    $update = $wpdb->update(
                    $wpdb->extranet_event_presence,
                    $data,
                    array('confirmation_id'=>$confirmation_id),
                    $column_formats
                );


    return $update;
}

/**
 * Fonction qui effectue les select
 *
 * @global  object  $wpdb
 * @param   array   $query
 * @return  array
 */
function extranetcp_get_confirmations( $query=array() ){

     global $wpdb;

     /* Parse defaults */
     $defaults = array(
                'fields'=>array(),
                'orderby'=>'datetime',
                'order'=>'desc',
                'user_id'=>false,
                'event_id'=> false,
                'number'=>10,
                'offset'=>0
     );

    $query = wp_parse_args($query, $defaults);

    /* Form a cache key from the query */
    /*$cache_key = 'wptuts_logs:'.md5( serialize($query));
    $cache = wp_cache_get( $cache_key );

    if ( false !== $cache ) {
            $cache = apply_filters('wptuts_get_logs', $cache, $query);
            return $cache;
    }*/

    extract($query);

    /* SQL Select */
    //Whitelist of allowed fields
    $allowed_fields = extranetcp_get_event_presence_table_columns();

    if( is_array($fields) ){
        //Convert fields to lowercase (as our column names are all lower case - see part 1)
        $fields = array_map('strtolower',$fields);

        //Sanitize by white listing
        $fields = array_intersect($fields, $allowed_fields);
    }else{
        $fields = strtolower($fields);
    }

    //Return only selected fields. Empty is interpreted as all
    if( empty($fields) ){
        $select_sql = "SELECT * FROM {$wpdb->extranet_event_presence}";
    }elseif( 'count' == $fields ) {
        $select_sql = "SELECT COUNT(*) FROM {$wpdb->extranet_event_presence}";
    }else{
        $select_sql = "SELECT ".implode(',',$fields)." FROM {$wpdb->extranet_event_presence}";
    }

     /*SQL Join */
     //We don't need this, but we'll allow it be filtered
     $join_sql='';

    /* SQL Where */
    //Initialise WHERE
    $where_sql = 'WHERE 1=1';

    if( !empty($user_id) ) {
       $where_sql .=  $wpdb->prepare(' AND user_id=%d', $user_id);
    }

    if( !empty($event_id) ) {
       $where_sql .=  $wpdb->prepare(' AND event_id=%d', $event_id);
    }



    /* SQL Order */
    //Whitelist order
    $order = strtoupper($order);
    $order = ( 'ASC' == $order ? 'ASC' : 'DESC' );

    switch( $orderby ){
        case 'confirmation_id':
            $order_sql = "ORDER BY confirmation_id $order";
        break;
        case 'user_id':
            $order_sql = "ORDER BY user_id $order";
        break;
        case 'event_id':
            $order_sql = "ORDER BY event_id $order";
        break;
        case 'datetime':
             $order_sql = "ORDER BY confirmation_date $order";
        default:
        break;
    }

    /* SQL Limit */
    $offset = absint($offset); //Positive integer
    if( $number == -1 ){
        $limit_sql = "";
    }else{
        $number = absint($number); //Positive integer
        $limit_sql = "LIMIT $offset, $number";
    }

    /* Filter SQL */
    $pieces = array( 'select_sql', 'join_sql', 'where_sql', 'order_sql', 'limit_sql' );
    $clauses = apply_filters( 'extranetcp_confirmation_presence_clauses', compact( $pieces ), $query );
    foreach ( $pieces as $piece ) {
            $$piece = isset( $clauses[ $piece ] ) ? $clauses[ $piece ] : '';
    }

    /* Form SQL statement */
    $sql = "$select_sql $join_sql $where_sql $order_sql $limit_sql";

    if( 'count' == $fields ){
        return $wpdb->get_var($sql);
    }

    /* Perform query */
    $logs = $wpdb->get_results($sql);

    /* Add to cache and filter */
    /*wp_cache_add( $cache_key, $logs, 24*60*60 );
    $logs = apply_filters('wptuts_get_logs', $logs, $query);*/
    
    return $logs;
 }