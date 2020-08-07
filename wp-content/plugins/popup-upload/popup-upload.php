<?php
/**
 * Plugin Name: PopupComics Upload
 * Plugin URI:
 * Description: File Upload widget with multiple file selection, drag&drop support, progress bars, validation and preview for jQuery.[ TESTING ]
 * Version: 1.0
 * Author: Team vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain:
 *
 * @package Wordpress_Upload_Handler
 * va
 */

define( 'POPUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'POPUP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Popup comics uploader
 */
class PopupComicsUploader{
	
	function __construct(){
		// adding new admin page for the new file uploader for custom table
		add_action( 'admin_menu', array( $this, 'popup_admin_menu' ) );	
		//adding script to handle the file calling and ajac calling
		add_action( 'admin_enqueue_scripts', array( $this, 'va_admin_scripts' ) );	
		//allow to upload the xml files - not working checking
		add_filter('upload_mimes', array( $this, 'va_allow_upload_xml' ) );
		//here handle the uploaded files 
        //add_action('wp_ajax_va_do_ajax__import', array( $this, 'va_do_ajax__import' ) );
		add_action('popup_ajax_va_do_ajax__import', array( $this, 'va_do_ajax__import' ) );
        
		//include all class
		$this->popup_include_class();
	}

	public function popup_include_class(){
        include_once 'insert-import/master-product/class-master-product.php';
        include_once 'class-popup-shortcode.php';
		include_once 'class-master-product-db.php';
        include_once 'include/class-aws-upload.php';
	}

	public function va_do_ajax__import(){
      	$file   = wc_clean( wp_unslash( $_POST['file'] ) ); // PHPCS: input var ok.

        $params = array(
        	'delimiter'       => ! empty( $_POST['delimiter'] ) ? wc_clean( wp_unslash( $_POST['delimiter'] ) ) : ',', // PHPCS: input var ok.
        	'start_pos'       => isset( $_POST['position'] ) ? absint( $_POST['position'] ) : 0, // PHPCS: input var ok.
        	'mapping'         => isset( $_POST['mapping'] ) ? (array) wc_clean( wp_unslash( $_POST['mapping'] ) ) : array(), // PHPCS: input var ok.
        	'update_existing' => isset( $_POST['update_existing'] ) ? (bool) $_POST['update_existing'] : false, // PHPCS: input var ok.
        	'lines'           => 30,
        	'parse'           => true,
          'imported'=> isset( $_POST['imported'] ) ? $_POST['imported'] : 0,
          'updated'=> isset( $_POST['updated'] ) ? $_POST['updated'] : 0,          
          'failed'=> isset( $_POST['failed'] ) ? $_POST['failed'] : 0,          
          'skipped'=> isset( $_POST['skipped'] ) ? $_POST['skipped'] : 0,          
      	);
        //checking the file extension for reading
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if( 'xml' === $ext ){
        	$results = $this->va_read_xml_file($file,$params);
        }else if( 'csv' === $ext ){
        	$results = $this->va_read_csv_file($file,$params);
        }

        if ( 100 === $results['percentage'] ) {
          wp_send_json_success(
            array(
              'position'   => 'done',
              'percentage' => 100,
             // 'url'        => add_query_arg( array( 'nonce' => wp_create_nonce( 'product-csv' ) ), admin_url( 'edit.php?post_type=product&page=product_importer&step=done' ) ),
              'imported'   => $results['imported'] ,
              'failed'     => $results['failed'] ,
              'updated'    => $results['updated'] ,
              'skipped'    => $results['skipped'] ,
              'data'  => $results['inserted_data'],
              'va_message' => $results['message'],
            )
          );
        }else{
            wp_send_json_success(
              array(
                'position'   => $results['position'],//2,//$importer->get_file_position(),
                'percentage' => $results['percentage'],
                'imported'   => $results['imported'],
                'failed'     => $results['failed'],
                'updated'    => $results['updated'],
                'skipped'    => $results['skipped'],
                'data'  => $results['inserted_data'],
                'va_message' => $results['message'],
              )
            );
        }
        

    }
    /**
     * [va_read_xml_file Read xml file and performing the code]
     * @param  [type] $file_path    [passed the file path]
     * @param  [type] $params [this parameters for our loading parameters]
     * @return [type]               [array]
     */
    public function va_read_xml_file( $file_path , $params ){
    
    	
    	$d = date("j-M-Y H:i:s");
    	$start_pos = (int) $params['start_pos'];
		$end_pos = (int)$params['start_pos'] + 1;
		$row = 0;
		//$row = (int)$start_pos;
		$parse_data = array();
		$update_count = (int)$params['updated'];
		$insert_count =  (int)$params['imported'];
		$failed_count = (int)$params['failed'];
		$skipped_count = (int)$params['skipped'];
		//reading xml file	
		$xml_element 	= new SimpleXMLElement( file_get_contents( $file_path ) );	
		$json_xml  		= json_encode($xml_element);
		$xml_arr 		= json_decode($json_xml, true);
    	// "export file" is my xml read array we need to change according to your xml arr
    	$xml_data = $xml_arr['EXPORT_FILE'][$start_pos];
    	//get the total data
    	$total_data = count( $xml_arr['EXPORT_FILE'] );
    	
    	$results['position'] = $end_pos;
        $results['percentage'] = $this->get_percent_complete( $total_data, $end_pos );
    	if($total_data <= $start_pos){
    		$message = ' - Done';
    		$results['updated'] = $update_count;
            $results['imported'] = $insert_count;
            $results['skipped'] = $skipped_count;
            $results['message'] = '['.$d.'] - '.$message;
    	}else{
    		$insert_arr = $this->popup_insert_data($xml_data);
    		//$insert_count = $insert_count + 1;
    		//$str_message = " - insert ";
    		$results['updated'] = $update_count + $insert_arr['update'];
            $results['imported'] = $insert_count + $insert_arr['insert'];
            $results['failed'] = $failed_count + $insert_arr['failed'];
            $results['skipped'] = $skipped_count;
            $results['message'] = '['.$d.'] - '.$insert_arr['message'];
    	}
    	return $results;

    } 
    public  function popup_insert_data($args){
       //$title = $this->popup_clean_title( $args['FULL_TITLE'] );
       //this is class for insert data in master product table
       $pmp = new popupMasterProduct();
       //calling insert data into master product table
       $return = $pmp->insert_data( $args );

      // $return['message'] = $return['message'];
       return $return;
    }

    public static function popup_clean_title($title){
        $new_title = preg_replace("/\([^)]+\)/","",$title);
        $title_string = $new_title;
        $search = preg_replace("/\([^)]+/","", $title_string);
        $search1 = strtr($search, array('(' => '', ')' => ''));
        $final_title = preg_replace('/\s+/', ' ', $search1);
        return $final_title;
    }
	public function va_allow_upload_xml( $mimes ){
		$mimes = array_merge($mimes, array('xml' => 'application/xml'));
    	return $mimes;
	}
	/*
	adding js file to handle the upload file
	 */
	public function va_admin_scripts(){
		 wp_register_script( 'va-popup-import', plugin_dir_url( __FILE__ ).'admin/js/va_import.js' );
	}
	/**
	 * this function are adding the admin menu
	 */
	public function popup_admin_menu(){
		//master uploader
		add_menu_page( 'Upload Master', 'Upload Master', 'manage_options', 'popupupload_pager', array( $this, 'popup_upload_cb' ), 'dashicons-media-spreadsheet', 6  );
	}

	public function popup_upload_cb(){
		require_once( 'admin/html/va-import-html.php' );
	}

	public function get_percent_complete($total_row,$end_pos) {
            //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
            return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
    }
}

$popcu = new PopupComicsUploader();
// class for handel all type of file reading
//include 'class-va-import-file.php';

$keto_cfg = array(
    'path'  => plugin_dir_path(__DIR__),
    'base'  => plugin_basename(__DIR__),
    'url'   => plugin_dir_url(__FILE__),
);


add_action( 'after_setup_theme', 'ketomei_vc_config', 4 );

if ( !function_exists('ketomei_vc_config')) {

    function ketomei_vc_config() {

        global $keto_cfg;

        $value = array();
        $value = apply_filters( 'popup_get_vc_config', $value );

        $keto_cfg = array_merge($keto_cfg, $value);

        return $value;
    }
}
function ketoGetLocalPath($file) {

    global $keto_cfg;

    return $keto_cfg['path'].$keto_cfg['base'].$file;
}
// Get Local Path of include file
function ketoGetLocalIncPath($file) {

    global $keto_cfg;

    return $keto_cfg['path'].$keto_cfg['base'].'/custom/inc'.$file;
}
// Get Plugin Url
function ketoGetPluginUrl($file) {

    global $keto_cfg;

    return $keto_cfg['url'].$file;
}