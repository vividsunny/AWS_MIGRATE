<?php
//Our Ajax handler has a default action, so the $_POST['action'] existence check has been removed.
define('DOING_AJAX', TRUE);
require_once('../../../wp-load.php');
@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );
send_nosniff_header();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$allowedActions = array(
    'my_function',
    'another_function',
    'va_do_ajax__import',
);
 
//Let's add a default action.
$action = (isset($_POST['action']))? $_POST['action']:'my_function';
$action = (in_array($action, $allowedActions))? $action:'my_function';
 
//This handler will only handle my plugin calls so the function prefix can adhere to the plugin. 
//I also don't need to enable any admin privileges so both logged in and logged out users can use the same prefix.
do_action( 'popup_ajax_' . $action );
?>