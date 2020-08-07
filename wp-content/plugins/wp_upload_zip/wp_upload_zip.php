<?php
/**
 * Plugin Name: Wordpress Upload Handler
 * Plugin URI:
 * Description: File Upload widget with multiple file selection, drag&drop support, progress bars, validation and preview for jQuery.
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain:
 *
 * @package Wordpress_Upload_Handler
 */

define( '_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( '_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

class WP_Upload_Handler
{

    public function __construct()
    {
        # code...
        add_action( 'admin_enqueue_scripts', array( $this, 'fileupload_admin_style' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'fileupload_admin_scripts' ) );
        add_action( 'admin_head', array( $this, 'fileupload_default_admin_css' ) );

        add_action( 'admin_menu', array( $this, 'fileupload_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'importXML_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'titleChange_admin_menu' ) );
        // add_action( 'admin_menu', array( $this, 'cancelImport_admin_menu' ) );
        // add_action( 'admin_menu', array( $this, 'newSeriesImport_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'imageScraper_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'updateprice_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'updateshipping_admin_menu' ) );

        add_action('init',array( $this , 'setup_log_dir' ) );

        //add_action( 'admin_menu', array( $this, 'remove_menus' ) );

        // called just before the template functions are included
        add_action( 'init', array( &$this, 'include_template_functions' ), 20 );

    }

    public function fileupload_admin_style(){
        wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/css/bootstrap.min.css', array(), "", "" );
         wp_register_style( 'upload-css', plugin_dir_url( __FILE__ ).'admin/css/upload.css', array(), "", "" );
        wp_register_style( 'blueimp-gallery.min', plugin_dir_url( __FILE__ ).'admin/css/blueimp-gallery.min.css', array(), "", "" );
        wp_register_style( 'upload_style', plugin_dir_url( __FILE__ ).'admin/css/upload_style.css', array(), "", "" );
        wp_register_style( 'jquery.fileupload', plugin_dir_url( __FILE__ ).'admin/css/jquery.fileupload.css', array(), "", "" );
        wp_register_style( 'jquery.fileupload-ui', plugin_dir_url( __FILE__ ).'admin/css/jquery.fileupload-ui.css', array(), "", "" );
        wp_register_style( 'jquery.fileupload-noscript', plugin_dir_url( __FILE__ ).'admin/css/jquery.fileupload-noscript.css', array(), "", "" );
        wp_register_style( 'jquery.fileupload-ui-noscript', plugin_dir_url( __FILE__ ).'admin/css/jquery.fileupload-ui-noscript.css', array(), "", "");
    }

    public function fileupload_admin_scripts(){

        wp_register_script( 'tmpl.min', plugin_dir_url( __FILE__ ).'admin/js/tmpl.min.js' );
        wp_register_script( 'load-image.all.min', plugin_dir_url( __FILE__ ).'admin/js/load-image.all.min.js' );
        wp_register_script( 'canvas-to-blob.min', plugin_dir_url( __FILE__ ).'admin/js/canvas-to-blob.min.js' );
        wp_register_script( 'jquery.ui.widget', plugin_dir_url( __FILE__ ).'admin/js/jquery.ui.widget.js' );
        wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/js/bootstrap.min.js' );
        wp_register_script( 'jquery.blueimp-gallery.min', plugin_dir_url( __FILE__ ).'admin/js/jquery.blueimp-gallery.min.js' );
        wp_register_script( 'jquery.iframe-transport', plugin_dir_url( __FILE__ ).'admin/js/jquery.iframe-transport.js' );
        wp_register_script( 'jquery.fileupload', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload.js' );
        wp_register_script( 'jquery.fileupload-process', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-process.js' );
        wp_register_script( 'jquery.fileupload-image', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-image.js' );
        wp_register_script( 'jquery.fileupload-audio', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-audio.js' );
        wp_register_script( 'jquery.fileupload-video', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-video.js' );
        wp_register_script( 'jquery.fileupload-validate', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-validate.js' );
        wp_register_script( 'jquery.fileupload-ui', plugin_dir_url( __FILE__ ).'admin/js/jquery.fileupload-ui.js' );


        wp_register_script( 'upload_main', plugin_dir_url( __FILE__ ).'admin/js/main.js' );
            wp_localize_script(
            'upload_main', 'upload_params', array(
                'import_nonce'    => wp_create_nonce( 'wc-upload' ),
                'url'            => plugin_dir_url( __FILE__ ).'php/',
            )
        );

        wp_register_script( 'aws_upload', plugin_dir_url( __FILE__ ).'admin/js/aws_upload.js' );
        wp_localize_script( 'aws_upload', 'admin_upload', array(
                'ajax_url' =>  admin_url("admin-ajax.php") ,
                'plugin_dir' =>  plugin_dir_path( __FILE__ ),
        ) );

        wp_register_script( 'aws_import_product', plugin_dir_url( __FILE__ ).'admin/js/aws_import_product.js' );
        wp_localize_script( 'aws_import_product', 'admin_upload', array(
                'ajax_url' =>  admin_url("admin-ajax.php") ,
                'plugin_dir' =>  plugin_dir_path( __FILE__ ),
        ) );

        wp_register_script( 'va-import', plugin_dir_url( __FILE__ ).'admin/js/va_import.js' );
    }

    public function setup_log_dir(){
        $dir = $this->plugin_dir();
        $plugin_url = $this->plugin_url();
        $log_dir = $dir."log/" ;
        $product_dir = $dir."log/product" ;

        if ( ! is_dir( $log_dir ) ) {
            wp_mkdir_p( $log_dir, 0777 );
        }

        if ( ! is_dir( $product_dir ) ) {
            wp_mkdir_p( $product_dir, 0777 );

            if ( $file_handle = @fopen( trailingslashit( $product_dir ) .'product_log.log', 'w' ) ) {
                fwrite( $file_handle, 'testing' );
                fclose( $file_handle );
            }

        }

    }

    public function product_import_log($str) {

        $d = date("j-M-Y H:i:s");
        $dir = $this->plugin_dir();
        $plugin_url = $this->plugin_url();
        $product_dir = $dir."log/product" ;
        error_log('['.$d.']'. $str.PHP_EOL, 3, $product_dir."/product_log.log");
    }

    public function fileupload_default_admin_css(){

        echo '<style>
            #toplevel_page_fileupload_page .wp-submenu li:nth-child(3){
                display:none;
            }
        </style>';

    }

    public function get_json_content($file){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $file,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Postman-Token: 9834a158-939b-44ba-a36a-822a8d668385",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = $err;
        } else {
            $result = $response;
        }
        return $result;
    }

    public function asos_upload_json_file($data_array,$filename){

        $dir = $this->plugin_dir();
        $plugin_url = $this->plugin_url();
        $temp_name = "$filename-".date("Y-m-d-H-i-s");
        $files_to_Delete = array();
        $json_file_dir = $dir."temp/".$temp_name.'.json';
        $json_file = $plugin_url."temp/".$temp_name.'.json';
        $json_cat = json_encode($data_array);
        file_put_contents($json_file_dir, $json_cat);
        return $json_file;
    }


    public static function plugin_dir() {
        return plugin_dir_path(__FILE__);
    }

    public static function plugin_url() {
        return plugin_dir_url(__FILE__);
    }

    public function fileupload_admin_menu(){
            #adding as main menu
        add_menu_page( 'Upload Zip', 'Uploads', 'manage_options', 'fileupload_page', array( $this, 'fileupload_html' ), 'dashicons-media-spreadsheet', 6  );
    }

    public function importXML_admin_menu(){
            #adding as main menu

         add_submenu_page('fileupload_page', __('XML Import'), __('XML Import'), 'manage_options', 'read_large_xml', array( $this,'read_xml_html') );

    }

    public function titleChange_admin_menu(){
            #adding as main menu

        add_submenu_page('fileupload_page', __('Change Title'), __('Change Title'), 'manage_options', 'change_title', array( $this,'change_title_html') );

    }
    public function cancelImport_admin_menu(){

        #adding as main menu
        add_submenu_page('fileupload_page', __('Cancel Import'), __('Cancel Import'), 'manage_options', 'cancel_import', array( $this,'cancel_import_html') );

    }

    public function newSeriesImport_admin_menu(){
            #adding as main menu

        add_submenu_page('fileupload_page', __('New Series Import'), __('New Series Import'), 'manage_options', 'new_series_import', array( $this,'new_series_import_html') );

    }

    public function imageScraper_admin_menu(){
            #adding as main menu

        add_submenu_page('fileupload_page', __('Image Scraper'), __('Image Scraper'), 'manage_options', '_image_scraper', array( $this,'image_scraper_html') );

    }

    public function updateprice_admin_menu(){
            #adding as main menu

        add_submenu_page('fileupload_page', __('Change Price'), __('Change Price'), 'manage_options', 'change_price', array( $this,'price_change_html') );

    }

    public function updateshipping_admin_menu(){
            #adding as main menu

        add_submenu_page('fileupload_page', __('Change Shipping'), __('Change Shipping'), 'manage_options', 'change_shipping', array( $this,'shipping_change_html') );

    }

    public function shipping_change_html(){
        require_once( 'admin/html/shipping-change-html.php' );
    }

    public function price_change_html(){
        require_once( 'admin/html/price-change-html.php' );
    }

    public function image_scraper_html(){
        require_once( 'admin/html/image-scraper-html.php' );
    }

    public function new_series_import_html(){
        require_once( 'admin/html/newSeries-import-html.php' );
    }

    public function change_title_html(){
        require_once( 'admin/html/change-title-html.php' );
    }

    public function read_xml_html(){
        require_once( 'admin/html/read-xml-html.php' );
    }

    public function fileupload_html(){
        require_once( 'admin/html/fileupload-html.php' );
    }

    //Remove Admin Backend Menus for all users except admin
    public function remove_menus () {
        global $submenu;
        //vivid($submenu);


        if ( isset( $submenu[ 'fileupload_page' ] ) ) {
            foreach ( $submenu[ 'fileupload_page' ] as $key => $menu ) {
                //vivid($menu);
                if ( 'read_large_xml' === $menu[2] ) {
                    //vivid($menu[2].'----'.$key);
                    unset( $submenu[ 'fileupload_page' ][ $key ] );
                }
            }
        }
    }

    /**
    * Override any of the template functions from woocommerce/woocommerce-template.php
    * with our own template functions file
    */
    public function include_template_functions() {
        include( _PLUGIN_DIR.'include/template-ajax.php' );
        include( _PLUGIN_DIR.'include/class-aws-upload.php' );
    }

    public function get_absolute_path($path) {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }

    public function read_large_xml_file($file){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $file,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Postman-Token: 1f609191-469e-43a9-b568-c32251f973ab",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = $err;
        } else {
            $result = $response;
        }

        return $result;
    }

    public function vs_popupcomics_post_exists( $diamd_no ){

        $args=array(
                // 'post_title'     => $the_slug,
                // 'name'     => $the_slug,
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'diamond_number',
                            'value'   => $diamd_no,
                        ),
                        array(
                            'key'     => 'Diamond Number',
                            'value'   => $diamd_no,
                        ),
                ),
                // 'meta_key'    => 'diamond_number',
                // 'meta_value'  => $diamd_no,
        );

        $query = new WP_Query( $args );

        $exist_posts = array();
        if( $query->have_posts() ) {
            $exist_posts = $query->posts;
        }

        return $exist_posts;
    }

    public function set_product_status( $issue_seq_no ){

        if(isset( $issue_seq_no )){
            if( $issue_seq_no <= 1 ){
                $status = "publish";
            }else{
                $status = "draft";
            }
        }
        return $status;

    }

    public function va_import_xml_product($args,$iod_date){
        // $exist = post_exists($args['FULL_TITLE'],'','','product');

        $diamd_no = $args['DIAMD_NO'];

        $new_title = preg_replace("/\([^)]+\)/","",$args['FULL_TITLE']);
        $title_string = $new_title;

        $search = preg_replace("/\([^)]+/","", $title_string);
        $search1 = strtr($search, array('(' => '', ')' => ''));
        $final_title = preg_replace('/\s+/', ' ', $search1);

        // $exist = post_exists($final_title,'','','product');
        $all_product = $this->vs_popupcomics_post_exists( $diamd_no );

        if(!empty( $args['PreviewHTML'] )){
            $content = $args['PreviewHTML'];
        }else{
            $content = '';
        }

        /* VIVID ISSUE_SEQ_NO CODE */
        $prod_status = $this->set_product_status( $args['ISSUE_SEQ_NO'] );
        if( !empty( $prod_status )){
            $product_status = $prod_status;
        }else{
            $product_status = 'publish';
        }

        if( !empty( $all_product )){

            foreach ($all_product as $blog_value) {
                $blog_post_id = $blog_value->ID;

                $data_arry = array(
                    'ID'           => $blog_post_id,
                    'post_title'   => $final_title,
                    'post_type'    => 'product',
                    'post_status'  => $product_status,
                    'post_content' => $content,
                    'post_excerpt' => $args['MAIN_DESC']
                );

                wp_update_post( $data_arry );

                $this->set_product_meta($exist, $args,$iod_date);
                $this->import_in_subsite( $args,$iod_date );

                $result = $final_title.'- #Post Exists';
            }

        }else{

            $data_arry = array(
                'post_title' => $final_title,
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => $content,
                'post_excerpt' => $args['MAIN_DESC']
            );

            $post_id = wp_insert_post( $data_arry );

                if(!is_wp_error($post_id)){

                    $this->set_product_meta($post_id,$args,$iod_date);
                    $this->import_in_subsite( $args, $iod_date);
                    $result = $final_title.' - #Inserted';

                }else{

                    $error =  $post_id->get_error_message();
                    $result = $final_title.' - '.$error.' - #Error';
                }
        }
        return $result;
    }


    public function get_percent_complete($total_row,$end_pos) {
            //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
            return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
    }

    public function import_in_subsite( $args, $iod_date ){
        /* Insert in subsite */
            $subsites = get_sites();
            
            unset($subsites[0]);
            unset($subsites[5]);

            $diamd_no = $args['DIAMD_NO'];
            $new_title = preg_replace("/\([^)]+\)/","",$args['FULL_TITLE']);
            $title_string = $new_title;

            $search = preg_replace("/\([^)]+/","", $title_string);
            $search1 = strtr($search, array('(' => '', ')' => ''));
            $final_title = preg_replace('/\s+/', ' ', $search1);

            // $exist = post_exists($final_title,'','','product');

            if(!empty( $args['PreviewHTML'] )){
                $content = $args['PreviewHTML'];
            }else{
                $content = '';
            }

            /* VIVID ISSUE_SEQ_NO CODE */
            $prod_status = $this->set_product_status( $args['ISSUE_SEQ_NO'] );
            if( !empty( $prod_status )){
                $product_status = $prod_status;
            }else{
                $product_status = 'publish';
            }

            /*let's get this post data as an array*/
            $data_arry = array(
                'post_title' => $final_title,
                'post_type' => 'product',
                'post_status' => $product_status,
                'post_content' => $content,
                'post_excerpt' => $args['MAIN_DESC']
            );

            foreach( $subsites as $subsite ) {
                $subsite_id = get_object_vars( $subsite )["blog_id"];
                $subsite_name = get_blog_details( $subsite_id )->blogname;

                    switch_to_blog( $subsite_id );

                    $all_product = $this->vs_popupcomics_post_exists( $diamd_no );

                    if( !empty( $all_product )){

                        foreach ($all_product as $blog_value) {

                                $blog_post_id = $blog_value->ID;

                                $data_update = array(
                                    'ID'           => $blog_post_id,
                                    'post_title' => $final_title,
                                    'post_type' => 'product',
                                    'post_status' =>  $product_status,
                                    'post_content' => $content,
                                    'post_excerpt' => $args['MAIN_DESC']
                                );

                                wp_update_post( $data_update );

                                $result = $blog_post_id;
                                $this->set_product_meta($exist, $args, $iod_date);

                            }

                    }else{
                        $inserted_post_id = wp_insert_post( $data_arry );
                        $result = $inserted_post_id;
                        $this->set_product_meta($inserted_post_id, $args,$iod_date);

                    }
                    restore_current_blog();

            }
            return $result;
    }

    public function set_product_meta($post_id, $args, $iod_date){

        /* Default Product meta */
        // $sku = str_replace("STL","",$args['STOCK_NO']);
        // set product is simple/variable/grouped
        wp_set_object_terms( $post_id, 'simple', 'product_type' );
        update_post_meta( $post_id, '_visibility', 'visible' );
        update_post_meta( $post_id, '_stock_status', 'instock');
        update_post_meta( $post_id, 'total_sales', '0' );
        update_post_meta( $post_id, '_downloadable', 'no' );
        update_post_meta( $post_id, '_virtual', '' );
        update_post_meta( $post_id, '_regular_price', $args['SRP'] );
        update_post_meta( $post_id, '_sale_price', '' );
        update_post_meta( $post_id, '_purchase_note', '' );
        update_post_meta( $post_id, '_featured', 'no' );
        update_post_meta( $post_id, '_sku',  $args['STOCK_NO'] );
        update_post_meta( $post_id, '_product_attributes', array() );
        update_post_meta( $post_id, '_sale_price_dates_from', '' );
        update_post_meta( $post_id, '_sale_price_dates_to', '' );
        update_post_meta( $post_id, '_price', $args['SRP'] );
        update_post_meta( $post_id, '_sold_individually', '' );
        update_post_meta( $post_id, '_manage_stock', 'yes' );
        update_post_meta( $post_id, '_backorders', 'notify' );
        update_post_meta( $post_id, 'vvd_product_IOD_date', $iod_date );
        // update_post_meta( $post_id, '_stock', 0 );

        /* Custom Product meta */
        update_post_meta($post_id,'diamond_number',$args['DIAMD_NO']);
        update_post_meta($post_id,'stock_number',$args['STOCK_NO']);
        update_post_meta($post_id,'series_code',$args['SERIES_CODE']);
        update_post_meta($post_id,'issue_number',$args['ISSUE_NO']);
        update_post_meta($post_id,'issue_sequence_number',$args['ISSUE_SEQ_NO']);
        update_post_meta($post_id,'price',$args['PRICE']);
        update_post_meta($post_id,'publisher',$args['PUBLISHER']);
        update_post_meta($post_id,'upc_number',$args['UPC_NO']);
        update_post_meta($post_id,'cards_per_pack',$args['CARDS_PER_PACK']);
        update_post_meta($post_id,'pack_per_box',$args['PACK_PER_BOX']);
        update_post_meta($post_id,'box_per_case',$args['BOX_PER_CASE']);
        update_post_meta($post_id,'discount_code',$args['DISCOUNT_CODE']);
        update_post_meta($post_id,'increment',$args['INCREMENT']);
        update_post_meta($post_id,'print_date',$args['PRNT_DATE']);
        update_post_meta($post_id,'foc_vendor',$args['FOC_VENDOR']);
        update_post_meta($post_id,'available',$args['SHIP_DATE']);
        update_post_meta($post_id,'srp',$args['SRP']);
        update_post_meta($post_id,'category',$args['CATEGORY']);
        update_post_meta($post_id,'mature',$args['MATURE']);
        update_post_meta($post_id,'adult',$args['ADULT']);
        update_post_meta($post_id,'oa',$args['OA']);
        update_post_meta($post_id,'caut1',$args['CAUT1']);
        update_post_meta($post_id,'caut2',$args['CAUT2']);
        update_post_meta($post_id,'caut3',$args['CAUT3']);
        update_post_meta($post_id,'resol',$args['RESOL']);
        update_post_meta($post_id,'note_price',$args['NOTE_PRICE']);
        update_post_meta($post_id,'order_form_notes',$args['ORDER_FORM_NOTES']);
        update_post_meta($post_id,'page',$args['PAGE']);
        update_post_meta($post_id,'foc_date',$args['FOC_DATE']);
        update_post_meta($post_id,'preview_html',$args['PreviewHTML']);
        update_post_meta($post_id,'image_path',$args['ImagePath']);
        update_post_meta($post_id,'genre',$args['GENRE']);
        update_post_meta($post_id,'brand_code',$args['BRAND_CODE']);
        update_post_meta($post_id,'writer',$args['WRITER']);
        update_post_meta($post_id,'artist',$args['ARTIST']);
        update_post_meta($post_id,'covert_artist',$args['COVER_ARTIST']);
        update_post_meta($post_id,'variant_desc',$args['VARIANT_DESC']);
        update_post_meta($post_id,'short_isbn_no',$args['SHORT_ISBN_NO']);
        update_post_meta($post_id,'ean_no',$args['EAN_NO']);
        update_post_meta($post_id,'colorist',$args['COLORIST']);
        update_post_meta($post_id,'alliance_sku',$args['ALLIANCE_SKU']);
        update_post_meta($post_id,'volume_tag',$args['VOLUME_TAG']);
        update_post_meta($post_id,'parent_item_no_alt',$args['PARENT_ITEM_NO_ALT']);
        update_post_meta($post_id,'offered_day',$args['OFFERED_DATE']);
        update_post_meta($post_id,'max_issue',$args['MAX_ISSUE']);
        update_post_meta($post_id,'cost',$args['PRICE']);
        update_post_meta($post_id,'stockid',$args['STOCK_NO']);

        $orders_availability = $this->convertDate($args['SHIP_DATE']);
        update_post_meta($post_id,'_wc_pre_orders_availability_datetime',$orders_availability );

        $orders_enabled = $this->enablePreOrder($args['SHIP_DATE']);
        update_post_meta($post_id,'_wc_pre_orders_enabled',$orders_enabled );

        #check image on aws
        $va_aws = new va_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');
        $s3path = 'zip-image/';
        //$image_url = $args['ImagePath'];
     // $image_arr = explode("\\", $image_url);
        $file_name = end($image_arr);
        $file_name = $args['DIAMD_NO'].'.jpg';
        $_key = $s3path.$file_name;
        $bucket_name  = 'darksidecomics';
        $aws_image = $va_aws->aws_check_image( $bucket_name, $_key );
        if( $aws_image ){
            update_post_meta($post_id,'aws_image','yes');
            update_post_meta($post_id,'aws_key',$_key);
            update_post_meta($post_id,'aws_bucketname',$bucket_name);
        }else{
            update_post_meta($post_id,'aws_image','no');
        }

    }

    public function convertDate($date) {
        $newDate = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);
        return $newDate ? $newDate->getTimestamp() : $date;
    }

    public function enablePreOrder($date) {
        $now = date('Y-m-d\TH:i:s');
        $today = DateTime::createFromFormat('Y-m-d\TH:i:s', $now);
        $newDate = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);

        if($today < $newDate) {
            return 'yes';
        }
        else {
            return 'no';
        }
    }

    public function vvd_upload_image_aws($file){

        $va_aws = new va_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');

        $file_name     = wp_basename( $file );
        $content_type  = wp_check_filetype($file);

        $type       = $content_type['type'];
        $ext      = '.'.$content_type['ext'];

        $bucket_name  = 'darksidecomics';
        $org_file_path  = $file;

        $s3path = 'zip-image/';
        $orignal_key = $s3path.$file_name;


        $og_img_arg = array(
            'Bucket' => $bucket_name,
            'Key' => $orignal_key,
            'SourceFile' => $org_file_path,
            'ContentType' => $type,
        );

        $va_aws->aws_upload($og_img_arg);
        //unlink($file);


        return $file_name;
    }

    public function va_product_image_scrapper($final_data){

        /*vivid( $final_data );*/

        $va_aws = new va_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');

        $post_id = $final_data->ID;

        /* Get Product meta */
        $dimond_no = get_post_meta($post_id, 'diamond_number', true);
        $Stock_Number = get_post_meta($post_id, 'stock_number', true);

        /* Image URL */
        $img_url = 'https://www.previewsworld.com/SiteImage/CatalogImage/'.$Stock_Number.'?type=1';
        $image_data = file_get_contents($img_url);

        /* Image */
        $img = '<img src="'.$img_url.'">';

        $file_name     = wp_basename( $img_url );
        $content_type  = wp_check_filetype($file);

        $filename = str_replace( $file_name, '', $dimond_no  );
        $filename = $filename.'.jpg';

        $bucket_name  = 'darksidecomics';
        $s3path = 'zip-image/';
        $orignal_key = $s3path.$filename;

        $og_img_arg = array(
            'Bucket' => $bucket_name,
            'Key' => $orignal_key,
            'Body'   => $image_data,
                    //'ContentType' => $type,

        );

        $va_aws->aws_upload($og_img_arg);

        $aws_image = $va_aws->aws_check_image( $bucket_name, $orignal_key );
        $prod_args = array(
            'prod_name' => $value->post_title,
            'aws_key' => $orignal_key,
            'aws_bucketname' => $bucket_name,
        );

        if( $aws_image ){
            $result = 'In If - '.$post_id;

            update_post_meta($post_id,'aws_image','yes');
            update_post_meta($post_id,'aws_key',$orignal_key);
            update_post_meta($post_id,'aws_bucketname',$bucket_name);
            $this->image_scrapper_subsite( $prod_args );

        }else{
            $result = 'IN Else - '.$post_id;
        }

        return $result;
    }

    public function image_scrapper_subsite($args){
        $subsites = get_sites();

        $status = array('publish', 'draft');

        foreach( $subsites as $subsite ) {
            $subsite_id = get_object_vars( $subsite )["blog_id"];
            $subsite_name = get_blog_details( $subsite_id )->blogname;

         // $this->product_import_log('Subsite -> '.$subsite_id.' Title '.$args['prod_name']);

            switch_to_blog( $subsite_id );
            $exist_post = post_exists($args['prod_name'],'','','product');

            if($exist_post == 0){
                 //$this->product_import_log('Subsite -> '.$subsite_id.' Not Exist '.$args['prod_name']);
            }else{

                $result = $exist_post;
                //$this->product_import_log('Subsite -> '.$subsite_id.' Product ID '.$result);
                //vivid($subsite_id.'------> '.$result);
                //vivid($subsite_id.'------> '.$args['aws_key']);

                update_post_meta($result,'aws_image','yes');
                update_post_meta($result,'aws_key',$args['aws_key']);
                update_post_meta($result,'aws_bucketname',$args['aws_bucketname']);
            }

            restore_current_blog();

        }
    }

    public function count_total_file_row($filename){
        $fp = file($filename, FILE_SKIP_EMPTY_LINES);
        return count($fp);
    }

    public function popupcomics_new_title($title){

        $new_title = preg_replace("/\([^)]+\)/","", $title );
        $title_string = $new_title;

        $search = preg_replace("/\([^)]+/","", $title_string);
        $search1 = strtr($search, array('(' => '', ')' => ''));
        $result = preg_replace('/\s+/', ' ', $search1);

        return $result;
    }

    public function va_change_title_product($args){

        // $exist = post_exists($args['old_title'],'','','product');

        $diamd_no = $args['item_code'];
        $all_product = $this->vs_popupcomics_post_exists( $diamd_no );

        if( !empty( $all_product )){
            foreach ($all_product as $blog_value) {
                $blog_post_id = $blog_value->ID;
                $new_title = $this->popupcomics_new_title( $args['new_title'] );

                $data_arry = array(
                    'ID'            => $exist,
                    'post_title'    => $new_title,
                    'post_name'     => sanitize_title( $new_title ),
                );
                wp_update_post( $data_arry );
                $this->set_title_change_product_meta( $blog_post_id, $args );
                $this->subsite_change_product_title( $args );
                $result = $diamd_no.' - '.$new_title.' - #Post title update.';
            }
        }else{
            $new_title = $this->popupcomics_new_title( $args['new_title'] );
            $result = $diamd_no.' - '.$new_title.' - #Post not found.';
        }

        return $result;
    }

    public function subsite_change_product_title( $args ){
        /* Insert in subsite */
            $subsites = get_sites();
            $diamd_no = $args['item_code'];

            foreach( $subsites as $subsite ) {
                $subsite_id = get_object_vars( $subsite )["blog_id"];
                $subsite_name = get_blog_details( $subsite_id )->blogname;

                    switch_to_blog( $subsite_id );
                    $all_product = $this->vs_popupcomics_post_exists( $diamd_no );

                    if( !empty( $all_product )){
                        foreach ($all_product as $blog_value) {
                            $blog_post_id = $blog_value->ID;
                            $new_title = $this->popupcomics_new_title( $args['new_title'] );

                            $data_arry = array(
                                'ID'            => $exist,
                                'post_title'    => $new_title,
                                'post_name'     => sanitize_title( $new_title ),
                            );
                            wp_update_post( $data_arry );
                            $this->set_title_change_product_meta( $blog_post_id, $args );
                            $result = $diamd_no.' - '.$new_title.' - #Post title update.';
                        }
                    }else{
                        $new_title = $this->popupcomics_new_title( $args['new_title'] );
                        $result = $diamd_no.' - '.$new_title.' - #Post not found.';
                    }

                    restore_current_blog();

            }
            return $result;
    }

    public function set_title_change_product_meta($post_id,$args){
        /* Custom Product meta */
        update_post_meta($post_id,'diamond_number',$args['item_code']);
    }

    public function fun_img_scraper_script($final_data){
        $va_aws = new va_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');

        $post_id = $final_data->ID;

        /* Get Product meta */
        $dimond_no = get_post_meta($post_id, 'diamond_number', true);
        $Stock_Number = get_post_meta($post_id, 'stockid', true);
        if(!empty( $dimond_no )){
            $result = 'In If - '.$post_id.' - Stock_Number -> '.$Stock_Number.' - Diamond Number -> '.$dimond_no;
        }else{
            $result = 'In Else - '.$post_id.' - Stock_Number -> '.$Stock_Number.' - Diamond Number -> '.$dimond_no;
        }

        //$result = 'https://www.previewsworld.com/SiteImage/CatalogImage/'.$Stock_Number.'?type=1';

        /* Image URL */
         $img_url = 'https://www.previewsworld.com/SiteImage/CatalogImage/'.$Stock_Number.'?type=1';
         $image_data = file_get_contents($img_url);

        /* Image */
        // $img = '<img src="'.$img_url.'">';

        $file_name     = wp_basename( $img_url );
        $content_type  = wp_check_filetype($file);

        $filename = str_replace( $file_name, '', $dimond_no  );
        $filename = $filename.'.jpg';

        $data =' Image ---> '. $filename . ' Title ---> '.$final_data->post_title;

        $bucket_name  = 'darksidecomics';

        $s3path = 'previews/';
        $orignal_key = $s3path.$filename;
        $aws_image = $va_aws->aws_check_image( $bucket_name, $orignal_key );

        $zippath = 'zip-image/';
        $zip_filename  = $zippath.$filename;
        $zip_file = $va_aws->aws_check_image($bucket_name, $zip_filename);

        if(!empty( $image_data )){
            //$this->product_import_log('In $image_data Not Empty '. $img_url);
            $og_img_arg = array(
                'Bucket' => $bucket_name,
                'Key' => $zip_filename,
                'Body'   => $image_data,
                        //'ContentType' => $type,

            );
            // $va_aws->aws_upload($og_img_arg);
        }





        if( $aws_image ){
            $result = 'IN - DarkSideComics Previews '.$data;
            //$this->product_import_log('DarkSideComics Previews'.$data);
            $prod_args = array(
                'prod_name' => $final_data->post_title,
                'aws_key' => $orignal_key,
                'aws_bucketname' => $bucket_name,
            );

            update_post_meta($post_id,'aws_image','yes');
            update_post_meta($post_id,'aws_key',$orignal_key);
            update_post_meta($post_id,'aws_bucketname',$bucket_name);
            $this->image_scrapper_subsite( $prod_args );

        }else if( $zip_file ){
            $result = 'IN - DarkSideComics - zip-image '.$data;
            //$this->product_import_log('DarkSideComics Zip-image'.$data);
            $prod_args = array(
                'prod_name' => $final_data->post_title,
                'aws_key' => $zip_filename,
                'aws_bucketname' => $bucket_name,
            );

            update_post_meta($post_id,'aws_image','yes');
            update_post_meta($post_id,'aws_key',$orignal_key);
            update_post_meta($post_id,'aws_bucketname',$bucket_name);
            $this->image_scrapper_subsite( $prod_args );

        }else{
            $result = 'IN Upload Image '.$data;
            //$this->product_import_log('Zip-image Upload'.$data);
            //$this->product_import_log( print_r($og_img_arg,true) );
            if(!empty( $image_data )){
                $va_aws->aws_upload($og_img_arg);
                $prod_args = array(
                    'prod_name' => $final_data->post_title,
                    'aws_key' => $zip_filename,
                    'aws_bucketname' => $bucket_name,
                );

                update_post_meta($post_id,'aws_image','yes');
                update_post_meta($post_id,'aws_key',$zip_filename);
                update_post_meta($post_id,'aws_bucketname',$bucket_name);
                $this->image_scrapper_subsite( $prod_args );
            }

        }

        return $result;
    }

    /* Price Change */
    public function va_change_price_product($args){

        $diamond_no = $args['item_code'];
        $diamd_no = substr($diamond_no, 0, -1);
        $all_product = $this->vs_popupcomics_post_exists( $diamd_no );

        if( !empty( $all_product )){
            foreach ($all_product as $blog_value) {
                $blog_post_id = $blog_value->ID;
                $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                $price = $args['old_price'];
                $new_price = $args['new_price'];
                if (strpos($price, '$') !== false) {
                    update_post_meta( $blog_post_id, '_regular_price', $new_price );
                    $this->subsite_change_price_product( $args );
                    $result = '#TRUE. Old Price-->'.$price . ' - New Price' .$new_price;
                }else{
                    $result = $diamd_no.' - '.$new_title.' - #Product not found.';
                }

            }
        }else{
            $new_title = $this->popupcomics_new_title( $args['prod_title'] );
            $result = $diamd_no.' - '.$new_title.' - #Product not found.';
        }

        return $result;
    }

    public function subsite_change_price_product( $args ){
        /* Insert in subsite */
        $subsites = get_sites();
        $diamond_no = $args['item_code'];
        $diamd_no = substr($diamond_no, 0, -1);

        foreach( $subsites as $subsite ) {
            $subsite_id = get_object_vars( $subsite )["blog_id"];
            $subsite_name = get_blog_details( $subsite_id )->blogname;

                switch_to_blog( $subsite_id );

                $all_product = $this->vs_popupcomics_post_exists( $diamd_no );
                if( !empty( $all_product )){
                    foreach ($all_product as $blog_value) {
                        $blog_post_id = $blog_value->ID;
                        $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                        $price = $args['old_price'];
                        $new_price = $args['new_price'];
                        if (strpos($price, '$') !== false) {
                            update_post_meta( $blog_post_id, '_regular_price', $new_price );
                            $result = '#TRUE. Old Price-->'.$price . ' - New Price' .$new_price;
                        }else{
                            $result = $diamd_no.' - '.$new_title.' - #Product not found.';
                        }

                    }
                }else{
                    $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                    $result = $diamd_no.' - '.$new_title.' - #Product not found.';
                }
                restore_current_blog();

        }
        return $result;
    }


    /* Shipping Change */
    public function va_change_shipping_product($args){

        $diamond_arr = $args['item_code'];
        $diamond_no = explode("/",$diamond_arr);

        foreach ($diamond_no as $key => $value) {
            # code...
            $all_product = $this->vs_popupcomics_post_exists( $value );

            if( !empty( $all_product )){
                foreach ($all_product as $blog_value) {
                    $blog_post_id = $blog_value->ID;
                    $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                    $old_date = $args['old_date'];
                    $new_date = $args['new_date'];
                    $new_date = date("Y-m-d",strtotime($new_date));
                    $final_date = $new_date.'T00:00:00';
                    $this->product_import_log( $final_date );

                    update_post_meta($blog_post_id,'available',$final_date);
                    $this->subsite_change_shipping_product( $args );
                    $result = $args['prod_title'].' - #Product shipping update.';

                }
            }else{

                $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                $result = $diamd_no.' - '.$new_title.' - #Product not found.';
            }
        }
        return $result;
    }

    public function subsite_change_shipping_product( $args ){
        /* Insert in subsite */
        $subsites = get_sites();
        $diamond_arr = $args['item_code'];
        $diamond_no = explode("/",$diamond_arr);

        foreach( $subsites as $subsite ) {
            $subsite_id = get_object_vars( $subsite )["blog_id"];
            $subsite_name = get_blog_details( $subsite_id )->blogname;

            switch_to_blog( $subsite_id );
                foreach ($diamond_no as $key => $value) {
                    # code...
                    $all_product = $this->vs_popupcomics_post_exists( $value );

                    if( !empty( $all_product )){
                        foreach ($all_product as $blog_value) {
                            $blog_post_id = $blog_value->ID;
                            $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                            $old_date = $args['old_date'];
                            $new_date = $args['new_date'];

                            $new_date = date("Y-m-d",strtotime($new_date));
                            $final_date = $new_date.'T00:00:00';

                            update_post_meta($blog_post_id,'available',$final_date);
                            $result = $args['prod_title'].' - #Product shipping update.';

                        }
                    }else{

                        $new_title = $this->popupcomics_new_title( $args['prod_title'] );
                        $result = $diamd_no.' - '.$new_title.' - #Product not found.';
                    }
                }
            restore_current_blog();

        }
        return $result;
    }

} /* End Class */

$wp_upload = new WP_Upload_Handler();

?>