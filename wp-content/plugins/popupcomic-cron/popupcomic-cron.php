<?php
/**
 * Plugin Name: Popupcomic Cron
 * Plugin URI: 
 * Description: All Popupcomic Cron
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain: 
 *
 * @package wp_popup_cron
 */

define( 'CRON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CRON_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

class wp_popup_cron
{

  public function __construct()
  {
    # code...
    add_action( 'admin_enqueue_scripts', array( $this, 'popup_cron_admin_style' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'popup_cron_admin_scripts' ) );

    # Add Cron Schedules
    add_filter( 'cron_schedules', array( $this, 'popup_cron_recurrence_interval' ) );

    # Cron activation
    register_activation_hook(__FILE__, array( $this, 'popup_cron_activation_daily_once' ) );
    
    # Deactivation Cron
    register_deactivation_hook(__FILE__, array( $this,'popup_cron_deactivation_daily_once' ) );
    
    #log dir
    add_action('init',array( $this , 'setup_order_log_dir' ) );

    #Cron Function
    add_action('popup_cron_daily_once', array( $this, 'do_this_daily_once' ) );
    add_action('aws_image_cron_daily_once', array( $this, 'daily_image_scraper_cron' ) );
    add_action('popup_cron_update_order_label', array( $this, 'daily_update_order_label_cron' ) );
    add_action('popup_cron_send_payment_link', array( $this, 'daily_check_pending_payment_order' ) );

    /* New */
    add_action('popup_cron_stock_list', array( $this, 'stock_list_import_cron' ) );
    // add_action('init', array( $this, 'stock_list_import_cron' ) );

    
    #called just before the template functions are included
    add_action( 'init', array( &$this, 'popup_cron_template_functions' ), 20 );
  }

  public function popup_cron_admin_style(){

    wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/css/bootstrap.min.css', array(), "", "" );
  }

  public function popup_cron_admin_scripts(){

    wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/js/bootstrap.min.js' );
    
  }

  public function popup_cron_admin_menu(){
    #adding as main menu
    add_menu_page( 'Weekly Invoice Upload', 'Weekly Invoice', 'manage_options', 'popup_cron_page', array( $this, 'popup_cron_html' ), 'dashicons-upload', 6  );
  }

  public static function cron_plugin_dir() {
    return plugin_dir_path(__FILE__);
  }

  public static function cron_plugin_url() {
    return plugin_dir_url(__FILE__);
  }

  public function setup_order_log_dir(){
    $upload_dir = wp_upload_dir();

    $dir = $this->cron_plugin_dir();
    $plugin_url = $this->cron_plugin_url();
    $cron_dir = $dir."cron-log/";

    if ( ! is_dir( $cron_dir ) ) {
      wp_mkdir_p( $cron_dir, 0777 );
    }

    $aws_dir = $upload_dir['basedir']."/aws_log/";
    if ( ! is_dir( $aws_dir ) ) {
      wp_mkdir_p( $aws_dir, 0777 );

      if ( $file_handle = @fopen( trailingslashit( $aws_dir ) .'aws_log.log', 'w' ) ) {
        fwrite( $file_handle, 'testing' );
        fclose( $file_handle );
      }

    }

    
    $log_dir = $upload_dir['basedir']."/order_log/" ;

    if ( ! is_dir( $log_dir ) ) {
      wp_mkdir_p( $log_dir, 0777 );
           if ( $file_handle = @fopen( trailingslashit( $log_dir ) .'order_log.log', 'w' ) ) {
            fwrite( $file_handle, 'testing' );
            fclose( $file_handle );
          }
    }

  }

  public function create_order_log($str) {

    $d = date("j-M-Y H:i:s");

    $upload_dir = wp_upload_dir();
    $create_order_dir = $upload_dir['basedir']."/order_log/" ;
    error_log(' ['.$d.'] - '. $str.PHP_EOL, 3, $create_order_dir."/order_log.log");
  }

 public function aws_upload_log($str) {

    $d = date("j-M-Y H:i:s");

    $upload_dir = wp_upload_dir();
    $create_order_dir = $upload_dir['basedir']."/aws_log/" ;
    error_log(' ['.$d.'] - '. $str.PHP_EOL, 3, $create_order_dir."/aws_log.log");
    
  } 

  /**
  * Override any of the template functions 
  * Own template functions file
  */
  public function popup_cron_template_functions() {
    include( CRON_PLUGIN_DIR.'include/template-ajax.php' );
    include( CRON_PLUGIN_DIR.'include/cron-class-aws-upload.php' );
  }

  public function popup_cron_recurrence_interval( $schedules ) {

    $schedules['every_three_minutes'] = array(
      'interval'  => 180,
      'display'   => __( 'Every 3 Minutes', 'popup_cron' )
    );

    // $schedules['every_fifteen_minutes'] = array(
    //   'interval'  => 900,
    //   'display'   => __( 'Every 15 Minutes', 'popup_cron' )
    // );  

    return $schedules;
  }

  public function popup_cron_activation_daily_once(){
    if ( !wp_next_scheduled( 'popup_cron_daily_once' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'popup_cron_daily_once');
    }

    if ( !wp_next_scheduled( 'aws_image_cron_daily_once' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'aws_image_cron_daily_once');
    }

    if ( !wp_next_scheduled( 'popup_cron_update_order_label' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'popup_cron_update_order_label');
    }

    if ( !wp_next_scheduled( 'popup_cron_send_payment_link' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'popup_cron_send_payment_link');
    }

    /* NEw */
    if ( !wp_next_scheduled( 'popup_cron_stock_list' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'popup_cron_stock_list');
    }
  }

  public function popup_cron_deactivation_daily_once(){
    wp_clear_scheduled_hook('popup_cron_daily_once');
    wp_clear_scheduled_hook('aws_image_cron_daily_once');
    wp_clear_scheduled_hook('popup_cron_update_order_label');
    wp_clear_scheduled_hook('popup_cron_send_payment_link');

    /* New */
    wp_clear_scheduled_hook('popup_cron_stock_list');
  }


  /* New */
  public function stock_list_import_cron() {
    // $this->create_order_log('Bingo......');

    global $wpdb;

    $sql = "SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_date_gmt` >= date_sub(now(), interval 6 month) LIMIT 10";

    $result = $wpdb->get_results(  $sql, 'ARRAY_A' );



    foreach ($result as $key => $value) {

      $prod_id  = $value['ID'];

      $this->import_change_status( $value );
    }
  }

  public function import_change_status( $value ){
     $stock = get_post_meta( $value['ID'] , '_stock', true );
     $this->create_order_log('---- INNN -----');
      if( $stock <= 0 ){
          /* Set Unpublish */

          $available = get_post_meta( $value['ID'], 'available', true );

          $today_date = date("Y-m-d");
          $after_2_week = date('Y-m-d', strtotime(' + 14 days'));

          // vivid( $available );
          // vivid( $today_date );
          // vivid( $after_2_week );exit;
          $newDateTime  = date("Y-m-d H:i:s", strtotime($available));
          $today      = date("Y-m-d H:i:s", strtotime($today_date));
          $to_date    = date("Y-m-d H:i:s", strtotime($after_2_week));

          if(strtotime($newDateTime) >= strtotime($today) && strtotime($newDateTime) <= strtotime($to_date)){

            $update_result = $this->cron_stock_list_published_product( $value );
            $result = $update_result;
            // $this->create_order_log('Bingo......');
          }else{

            $update_result = $this->cron_stock_list_unpublished_product( $value );
            $result = $update_result;
            
          }
          
        }else{
         
          /* Set Publish */

          $update_result = $this->cron_stock_list_published_product( $value );
          $result = $update_result;
        }

        return $result;
  }

  public function cron_stock_list_unpublished_product( $found_product ) {

    $status = array( 'draft' );
    foreach ( $found_product as $blog_value ) {

      $post_id    = $blog_value['ID'];
      $post_title = $blog_value['post_title'];

      $post = array(
        'ID'          => $post_id,
        'post_status' => 'draft',
      );
      // wp_update_post( $post );
      $result = $post_title . ' - #Unpublished Product';

      $this->create_order_log( $result );
    }

    return $result;
  }

  public function cron_stock_list_published_product( $found_product ) {

    $status = array( 'publish' );
    foreach ( $found_product as $blog_value ) {

      $post_id    = $blog_value['ID'];
      $post_title = $blog_value['post_title'];

      $post = array(
        'ID'          => $post_id,
        'post_status' => 'publish',
      );
      // wp_update_post( $post );
      $result = $post_title . ' - #Published Product';
      $this->create_order_log( $result );
    }
    
    return $result;
  }

  public function do_this_daily_once() {
    global $wpdb;

    $today = date("m/d/Y");

    $startdate = strtotime( $today );
    $enddate = strtotime("-2 weeks", $startdate);

    $subscription_form_date = date("Y-m-d H:i:s",strtotime( "-2 weeks", $startdate ));
    $subscription_to_date = date("Y-m-d H:i:s",strtotime( $today ));

    # Current Blog_id

    $blog_id = get_current_blog_id();

    $sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id AND status = 'active'";
    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    if( !empty( $result ) ){
      $row = 1;
      // $comics = array();
      foreach ($result as $key => $value) {

        $series_id  = $value['series_id'];
        $user_id  = $value['user_id'];
        $chk_status = $value['status'];

        $author_obj = get_user_by( 'id', $user_id );
        $username   = $author_obj->user_login;
        $email      = $author_obj->user_email;

        $comics = array();
        $child_products = $this->cron_popupcomicshops_get_child_product( $series_id );

        foreach ( $child_products as $child_product_id ){

          $available = get_post_meta( $child_product_id, 'available', true );
          $foc_date  = get_post_meta( $child_product_id, 'foc_date', true );
          $issue_seq_no = get_post_meta( $child_product_id, 'issue_sequence_number', true );

          $today_dt = date("Y/m/d");
          $newDateTime= date("Y-m-d H:i:s", strtotime($available));

          $newfoc_date= date("Y-m-d H:i:s", strtotime($foc_date));
          $newToday_dt= date("Y-m-d H:i:s", strtotime($today_dt));

          if(strtotime($newDateTime) >= strtotime($subscription_form_date) && strtotime($newDateTime) <= strtotime($subscription_to_date)){

            if( strtotime( $newfoc_date ) >= strtotime( $newToday_dt ) ){

              if( $issue_seq_no == 0 ){

                if( !$this->cron_popupcomicshops_has_existing_order( $user_id , $child_product_id ) ){

                  if( !in_array( $child_product_id, $comics ) ){ 

                    $comics[$user_id][] = $child_product_id;

                  }
                }
              }
            }
            
          } 

        } // end for each

        

        $row++;
      } // end main for each


      # Create Order
        if(!empty($comics)){
          $status = $this->cron_daily_once_create_order( $comics, $user_id );
          // vivid('#'.$status.' Order Created.');
          $this->create_order_log('blog_id -> '.$blog_id.' #'.$status.' Order Created.' );
        }else{
          $this->create_order_log( 'blog_id -> '.$blog_id.' No Comics to add.' );
          // vivid('No Comics to add.');
        }

    }
  }

  public function cron_popupcomicshops_get_child_product( $series_code ){   

    $args = array(
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'post_type'   => 'product',
      'fields'    => 'ids',
      'meta_key'    => 'series_code',
      'meta_value'  => $series_code,
    );

    $query = new WP_Query( $args );
    
    $child_product_id = array();
    if( $query->have_posts() ) {
      $child_product_id = $query->posts;
    }

    return $child_product_id;
  }

  // Check if customer already has a order with product 
  public function cron_popupcomicshops_has_existing_order( $user_id, $product_id ){
    $subscriber = get_user_by( 'id', $user_id );
    if ( wc_customer_bought_product( $subscriber->user_email, $user_id, $product_id ) ) { 
      return true;
    }

    $args = array(
            'meta_key'    => '_customer_user',
            'meta_value'  => $user_id,
            'numberposts' => -1,
        );
    $orders = wc_get_orders( $args ); // Check for pending orders

    foreach ( $orders as $order_id => $order ){
      foreach ( $order->get_items() as $item_id => $item ){ // Get series from order items in subscription 
        if ( $product_id == $item->get_product_id() ){
          return true;
        }
      }
    }
    return false;
  }


  public function vvd_popupcomicshops_set_custom_label( $order_id ){
          # Get an instance of WC_Order object
          $order = wc_get_order( $order_id );

          # Iterating through each order items (WC_Order_Item_Product objects in WC 3+)
          foreach ( $order->get_items() as $item_id => $item_values ) {

              // Product_id
              $product_id = $item_values->get_product_id(); 

              // OR the Product id from the item data
              $item_data = $item_values->get_data();
              //debug( $item_data );

              $product_id = $item_data['product_id'];
              // debug( 'Product Id --->'.$product_id );

              $_product = wc_get_product( $product_id );
              $IOD_date = get_post_meta( $product_id, 'vvd_product_IOD_date', true );

              $order_series_code = get_post_meta( $product_id, 'series_code', true );
              if(!empty( $order_series_code )){
                  $order_series_code = $order_series_code; 
              }else{
                  $order_series_code = '-';
              }

              if(!empty( $IOD_date )){

                $IOD_date_strtotime = date("Y-m-d H:i:s",strtotime( $IOD_date ));
                $today_date = date("Y-m-d H:i:s");
                if( strtotime($IOD_date_strtotime) >= strtotime($today_date) )
                    {
                      /*debug( 'In IOD_date-- '.$IOD_date .'IOD_date_strtotime-- '. strtotime( $IOD_date_strtotime ) );
                  debug( 'In today_date-- '.$today_date .'today_date-- '. strtotime( $today_date ) );*/

                  $vvd_key = 'item_label';
                  $vvd_value = 'pre-order';
                      wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                      wc_update_order_item_meta($item_id, 'subsciption_label', 1);
                      wc_update_order_item_meta($item_id, 'series_code', $order_series_code);

                    }else if( strtotime($today_date) >= strtotime($IOD_date_strtotime) ){
                      
                      $vvd_key = 'item_label';
                  $vvd_value = 're-order';

                  if ( $_product->get_stock_quantity() > 0 ) {

                    //debug('Order');
                    $vvd_key = 'item_label';
                    $vvd_value = 'order';
                    wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                          wc_update_order_item_meta($item_id, 'subsciption_label', 1);
                          wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
                  } else {

                    //debug('Reorder');
                    $vvd_key = 'item_label';
                    $vvd_value = 're-order';
                    wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                          wc_update_order_item_meta($item_id, 'subsciption_label', 1);
                          wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
                  }
                 
                    }else{
                      //debug('Not In Else');
                      $vvd_key = 'item_label';
                      $vvd_value = '';
                      wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                      wc_update_order_item_meta($item_id, 'subsciption_label', 1);
                      wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
                    }
                
              }
              
          }
      }

  public function cron_daily_once_create_order( $comics, $user_id ){

        if ( !empty( $comics ) ){
          $args = array( 
            'status'    => 'pending',
            'customer_id'   => $user_id,
          );
          $order = wc_create_order( $args );

          $address_type = array('billing','shipping');
          $address = array('first_name','last_name', 'company', 'email', 'phone', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', );
          $billing_address = array();
          $shipping_address = array();
          $remove_field = array('email','phone');
          foreach ($address_type as $key => $address_type) {
            foreach ($address as $key => $address_field) {
              if( $address_type == 'billing' ){
                $user_data = get_user_meta($user_id,$address_type.'_'.$address_field,true);
                if(!empty( $user_data ) ){
                  $billing_address[$address_field] = $user_data;  
                }
                
              }else if( $address_type == 'shipping' && !in_array($address_field, $remove_field)){
                $user_data = get_user_meta($user_id,$address_type.'_'.$address_field,true);
                if(!empty( $user_data ) ){
                  $shipping_address[$address_field] = $user_data; 
                }
                
              }
            }
          }

          if(!empty( $billing_address ) ){
            $order->set_address( $billing_address, 'billing' );
          }

          if(!empty( $shipping_address ) ){
            $order->set_address( $shipping_address, 'shipping' );
          }
          

          
          foreach( $comics as $comic => $comic_id ){            
            if ( is_object( $order ) ){
              $order->add_product( wc_get_product( $comic_id ), 1 );
              $order->calculate_totals();

              /* Order Meta */
              update_post_meta( $order->id , '_order_subscription' , 'yes' );
            }
            
            $author_obj = get_user_by('id', $user_id);
            $to = $user->user_email;
            $subject = 'New Order #'.$order->id;
            $body = 'The email body content';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            wp_mail( $to, $subject, $body, $headers );
          } 
          $this->vvd_popupcomicshops_set_custom_label( $order->id );
          // vvd_popupcomicshops_set_custom_label( 59602 );
        }

        return $order->id;
    
  }

  public function daily_image_scraper_cron() {
    $va_aws = new cron_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');

    $status = array('publish', 'draft');
    $args = array(
      'post_type' => 'product',
      'posts_per_page' => 1,
      'post_status' => $status,
      'meta_query' => array(
        array(
          'key' => 'aws_image',
          'value' =>'no',
          'compare' => '=',
          //'compare' => 'NOT EXISTS',
        )
      ),
    );

    $query = new WP_Query( $args );
    $all_post = $query->posts;

    if( !empty( $all_post ) ){

      foreach ($all_post  as $value) {
        # code...
          $this->aws_upload_log('--- Start ---');
          $this->aws_upload_log('--- '.$value->post_title.' ---');
          $dimond_no = get_post_meta($value->ID, 'diamond_number', true);
          $Stock_Number = get_post_meta($value->ID, 'stockid', true);

          $this->aws_upload_log('--- Diamond Numbe ---'. $dimond_no );
          $this->aws_upload_log('--- StockID ---'. $Stock_Number );

          if( !empty( $Stock_Number )){
            $img_url = 'https://www.previewsworld.com/SiteImage/CatalogImage/'.$Stock_Number.'?type=1';
            $image_data = file_get_contents($img_url);

            $file_name     = wp_basename( $img_url );
            $content_type  = wp_check_filetype($file);

            $filename = str_replace( $file_name, '', $dimond_no );
            $filename = $filename.'.jpg';

            $bucket_name  = 'darksidecomics';
            $zippath = 'zip-image/';
            $zip_filename  = $zippath.$filename;
            $zip_file = $va_aws->aws_check_image($bucket_name, $zip_filename);

            $this->aws_upload_log('img_url - '.$img_url );
            $this->aws_upload_log('file_name - '.$filename );

            if(!empty( $image_data )){
              $og_img_arg = array(
                'Bucket' => $bucket_name,
                'Key' => $zip_filename,
                'Body'   => $image_data,
                    //'ContentType' => $type,
              );
            }

            if( $zip_file ){
              $this->aws_upload_log('In If file_name - '.$filename );
              update_post_meta($value->ID,'aws_image','yes');
              update_post_meta($value->ID,'aws_key',$_key);
              update_post_meta($value->ID,'aws_bucketname',$bucket_name);
            }else{
              $this->aws_upload_log('In Else file_name - '.$filename);
              $external_link = $img_url;
              if (@getimagesize($external_link)) {
                if(!empty( $image_data )){

                  $va_aws->aws_upload($og_img_arg);
                  update_post_meta($value->ID,'aws_image','yes');
                  update_post_meta($value->ID,'aws_key',$zip_filename);
                  update_post_meta($value->ID,'aws_bucketname',$bucket_name);
                  $this->aws_upload_log('Upload AWS - '.$filename);
                }
              } else {
                update_post_meta($value->ID,'aws_image','yes');
                update_post_meta($value->ID,'aws_image_exist','no');
                $this->aws_upload_log('Image does not exist');
              }

              
              
            }

            $this->aws_upload_log('--- End ---');
          }
      }/* end foreach */
      
    }else{
      $this->aws_upload_log('No Product found!');
    }
  }

  public function daily_update_order_label_cron(){

    // $status = array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-refunded', 'wc-failed', 'wc-cancelled');
    $status = array('wc-pending', 'wc-processing');
    $args = array(
          'posts_per_page' => -1,
          'post_type'   => wc_get_order_types(),
          // 'post_status' => array_keys( wc_get_order_statuses() ),
          'post_status' => $status,
      );

    $query = new WP_Query( $args );
    $all_order_post = $query->posts;

    foreach ($all_order_post as $key => $value) {
      # code...
      $order_id = $value->ID;
      $this->cron_update_vvd_popupcomicshops_set_custom_label( $order_id );
      // cron_update_vvd_popupcomicshops_set_custom_label( 59602 );
    }

  }

  public function cron_update_vvd_popupcomicshops_set_custom_label( $order_id ){
      # Get an instance of WC_Order object
      $order = wc_get_order( $order_id );

      # Iterating through each order items (WC_Order_Item_Product objects in WC 3+)
      foreach ( $order->get_items() as $item_id => $item_values ) {

          // Product_id
          $product_id = $item_values->get_product_id(); 

          // OR the Product id from the item data
          $item_data = $item_values->get_data();

          $product_id = $item_data['product_id'];

          $_product = wc_get_product( $product_id );
          $IOD_date = get_post_meta( $product_id, 'vvd_product_IOD_date', true );

          $order_series_code = get_post_meta( $product_id, 'series_code', true );
          if(!empty( $order_series_code )){
              $order_series_code = $order_series_code; 
          }else{
              $order_series_code = '-';
          }

          if(!empty( $IOD_date )){

            $IOD_date_strtotime = date("Y-m-d H:i:s",strtotime( $IOD_date ));
            $today_date = date("Y-m-d H:i:s");

            if( strtotime($today_date) >= strtotime($IOD_date_strtotime) )
            {
                if ( $_product->get_stock_quantity() > 0 ) {

                    $vvd_key = 'item_label';
                    $vvd_value = 'order';
                    wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);

                } else {

                    $vvd_key = 'item_label';
                    $vvd_value = 're-order';
                    wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);

                }
             
            }else{

                $vvd_key = 'item_label';
                $vvd_value = 'pre-order';
                wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);

            }
            
          }
          
      }
  }

  public function daily_check_pending_payment_order(){
    
    $status = array('wc-pending');
    $args = array(
          'posts_per_page' => -1,
          'post_type'   => wc_get_order_types(),
          // 'post_status' => array_keys( wc_get_order_statuses() ),
          'post_status' => $status,
      );

    $query = new WP_Query( $args );
    $all_order_post = $query->posts;

     foreach ($all_order_post as $key => $value) {
        # code...
        $order_id = $value->ID;
        $order = wc_get_order( $order_id );

        $user_id   = $order->get_user_id(); // Get the costumer ID
        $user      = $order->get_user(); // Get the WP_User object

        if( $order_id === 37229 ){
            do_action( 'woocommerce_before_resend_order_emails', $order, 'customer_invoice' );

            // Send the customer invoice email.
            WC()->payment_gateways();
            WC()->shipping();
            WC()->mailer()->customer_invoice( $order );

            // Note the event.
            $order->add_order_note( __( 'Order details manually sent to customer.', 'woocommerce' ), false, true );

            do_action( 'woocommerce_after_resend_order_email', $order, 'customer_invoice' );
        }
      }
  }

} /* End Class */

$wp_upload = new wp_popup_cron();

?>