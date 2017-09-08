<?php
/*
 * dl-file.php
 *
 * Protect uploaded files with login.
 *
 * @link http://wordpress.stackexchange.com/questions/37144/protect-wordpress-uploads-if-user-is-not-logged-in
 *
 * @author hakre <http://hakre.wordpress.com/>
 * @license GPL-3.0+
 * @registry SPDX
 */

require_once('wp-load.php');

redirect_user_if_no_access_extranet();

//subdir is the ged id
$subdir=intval($_GET[ 'subdir' ]);

//fetch parent instance
$instance = get_parent_instance_of_ged($subdir);

//connected user
$id_user=get_current_user_id();

//check access
if(!empty($instance) && check_user_access_instance($instance->ID, $id_user) && check_user_type_can_access_cpt($subdir, $id_user) ) {

    $basedir = WP_CONTENT_DIR . '/ged/';
    $file =  rtrim($basedir,'/').'/'.$subdir.'/'.str_replace('..', '', isset($_GET[ 'file' ])?$_GET[ 'file' ]:'');
    if (!$basedir || !is_file($file)) {
  	status_header(404);
  	die('404 &#8212; fichier non trouvé');
    }

    $mime = wp_check_filetype($file);
    if( false === $mime[ 'type' ] && function_exists( 'mime_content_type' ) )
  	$mime[ 'type' ] = mime_content_type( $file );

    if( $mime[ 'type' ] )
  	$mimetype = $mime[ 'type' ];
    else
  	$mimetype = 'image/' . substr( $file, strrpos( $file, '.' ) + 1 );

    header( 'Content-Type: ' . $mimetype ); // always send this
    if ( false === strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS' ) )
  	header( 'Content-Length: ' . filesize( $file ) );

    $last_modified = gmdate( 'D, d M Y H:i:s', filemtime( $file ) );
    $etag = '"' . md5( $last_modified ) . '"';
    header( "Last-Modified: $last_modified GMT" );
    header( 'ETag: ' . $etag );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 100000000 ) . ' GMT' );

    // Support for Conditional GET
    $client_etag = isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ? stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) : false;

    if( ! isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
  	$_SERVER['HTTP_IF_MODIFIED_SINCE'] = false;

    $client_last_modified = trim( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
    // If string is empty, return 0. If not, attempt to parse into a timestamp
    $client_modified_timestamp = $client_last_modified ? strtotime( $client_last_modified ) : 0;

    // Make a timestamp for our most recent modification...
    $modified_timestamp = strtotime($last_modified);

    if ( ( $client_last_modified && $client_etag )
  	? ( ( $client_modified_timestamp >= $modified_timestamp) && ( $client_etag == $etag ) )
  	: ( ( $client_modified_timestamp >= $modified_timestamp) || ( $client_etag == $etag ) )
  	) {
  	status_header( 304 );
  	exit;
    }

    // If we made it this far, just serve the file

    readfile( $file );
} else {
    //wp_redirect( home_url( 'member-login' ) );
    wp_redirect(home_url('page-non-accessible'));
    exit;
}
