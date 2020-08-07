<?php

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-load.php';

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
//require('./wp-load.php');

global $wpdb;
$sql = $wpdb->prepare("SELECT domain, path FROM $wpdb->blogs WHERE archived='0' AND deleted ='0' LIMIT 0,300", '');

$blogs = $wpdb->get_results($sql);

foreach($blogs as $blog) {
    $command = "http://" . $blog->domain . ($blog->path ? $blog->path : '/') . 'wp-cron.php';
    $ch = curl_init($command);
    $rc = curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
    $rc = curl_exec($ch);
    curl_close($ch);
}
?>