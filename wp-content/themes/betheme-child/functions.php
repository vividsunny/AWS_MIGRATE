<?php

/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );


/* ---------------------------------------------------------------------------
 * Define | YOU CAN CHANGE THESE
 * --------------------------------------------------------------------------- */

// White Label --------------------------------------------
define( 'WHITE_LABEL', false );

// Static CSS is placed in Child Theme directory ----------
define( 'STATIC_IN_CHILD', false );

//Include woo shortcode
include('custom_listing_shortcode.php');


/* ---------------------------------------------------------------------------
 * Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );
function mfnch_enqueue_styles() {

	// Enqueue the parent stylesheet
// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );		//we don't need this if it's empty

	// Enqueue the parent rtl stylesheet
	if ( is_rtl() ) {
		wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );
	}

	// Enqueue the child stylesheet
	wp_dequeue_style( 'style' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );

}
/* ---------------------------------------------------------------------------
 * Admin Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'admin_enqueue_scripts', 'tamberra_enqueue_admin_script' );
function tamberra_enqueue_admin_script( $hook ) {
	wp_enqueue_script( 'tamberra_popup.js', get_stylesheet_directory_uri() . '/js/tamberra_popup.js', array(), '',true );
	wp_localize_script( 'tamberra_popup.js', 'va_tamberra', array(
				        'ajax_url' =>  admin_url("admin-ajax.php") ,
				    ) );
	wp_enqueue_style( 'popup-css', get_stylesheet_directory_uri() . '/css/tamberra_popup.css'); 

	wp_register_style("subscription-style", get_stylesheet_directory_uri()."/css/subscription.css", array(), "1.0", "all");
}

// First we register our resources using the init hook
function shortcode_register_resources() {
	wp_register_script("shortcode-script", get_stylesheet_directory_uri()."/js/shortcode_script.js", array(), "1.0", false);
	wp_localize_script( 'shortcode-script', 'shortcode_ajax', array(
				        'ajax_url' =>  admin_url("admin-ajax.php") ,
				    ) );

	wp_register_script("subscription-script", get_stylesheet_directory_uri()."/js/subscription_script.js", array(), "1.0", false);
	wp_localize_script( 'subscription-script', 'subscription', array(
				        'ajax_url' =>  admin_url("admin-ajax.php") ,
				    ) );

	wp_register_style("shortcode-style", get_stylesheet_directory_uri()."/css/shortcode_style.css", array(), "1.0", "all");

	wp_register_style("subscription-style", get_stylesheet_directory_uri()."/css/subscription.css", array(), "1.0", "all");

}
add_action( 'init', 'shortcode_register_resources' );


/* ---------------------------------------------------------------------------
 * Load Textdomain
 * --------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'mfnch_textdomain' );
function mfnch_textdomain() {
    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );
}

// Include files
	require_once( get_stylesheet_directory() . '/asset/function/include.php' );
    







    /**
     * Set Orders from Subscriptions marked as Virtual Products Completed Automatically.
     *
     * @param  boolean $virtual_downloadable_item If item is virtual and downloadable.
     * @param  Object  $_product                  Product Object.
     * @param  int     $product_id                Product ID.
     * @return Boolean                            Should not be set to processing.
     */
    function rfvc_set_virtual_subscriptions_completed( $virtual_downloadable_item, $_product, $product_id ) {
        
        if ( $_product->is_virtual() && is_a( $_product, 'WC_Product_Subscription' ) ) {
            $virtual_downloadable_item = true;
        }
        
        return $virtual_downloadable_item;
    }
    add_filter( 'woocommerce_order_item_needs_processing', 'rfvc_set_virtual_subscriptions_completed', 10, 3 );
	
	add_filter('acf/settings/remove_wp_meta_box', '__return_false');
	
/**
 * Exclude Category ticket categories from shop page
 */
  
function dc_exclude_cat_shortcodes($q){
 
    if (!$q->is_main_query() || !is_shop()) return;

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array( 'magic-the-gathering','yu-gi-oh','pokemon','star-wars-games','miniatures-games','community-events','more-card-games'),
        'operator' => 'NOT IN'
    );

    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query' , 'dc_exclude_cat_shortcodes');

/**
 * Remove admin notices from selected role
 */
add_filter('ure_role_additional_options', 'ure_add_block_admin_notices_option', 10, 1);
function ure_add_block_admin_notices_option($items) {
    $item = URE_Role_Additional_Options::create_item('block_admin_notices', esc_html__('Block admin notices', 'user-role-editor'), 'admin_init', 'ure_block_admin_notices');
    $items[$item->id] = $item;
    
    return $items;
}
function ure_block_admin_notices() {
    add_action('admin_print_scripts', 'ure_remove_admin_notices');    
}
function ure_remove_admin_notices() {
    global $wp_filter;
    if (is_user_admin()) {
        if (isset($wp_filter['user_admin_notices'])) {
            unset($wp_filter['user_admin_notices']);
        }
    } elseif (isset($wp_filter['admin_notices'])) {
        unset($wp_filter['admin_notices']);
    }
    if (isset($wp_filter['all_admin_notices'])) {
        unset($wp_filter['all_admin_notices']);
    }
}

/*function wpb_remove_screen_options() { 
if(!current_user_can('manage_options')) {
return false;
}
return true; 
}
add_filter('screen_options_show_screen', 'wpb_remove_screen_options');*/

/* Subscription Sub Menu Page*/
add_action( 'admin_menu', 'all_subscription_submenu' );
function all_subscription_submenu(){
    add_menu_page( 
        __( 'Subscriptions', '' ),
        'Subscriptions',
        'manage_options',
        'all_subscriptions',
        'all_subscriptions',
       '',
        6
    ); 

    add_submenu_page('all_subscriptions', __('All Subscriptions'), __('All Subscriptions'), 'manage_options', 'all_subscriptions', 'all_subscriptions');

    // Add to admin_menu
	add_submenu_page('all_subscriptions', __('Create Order'), __('Create Order'), 'manage_options', 'subscription_create_order', 'subscription_create_order');

}


add_action( 'wp_ajax_all_subscriptions_create_order', 'all_subscriptions_create_order_handler' );
add_action( 'wp_ajax_nopriv_all_subscriptions_create_order', 'all_subscriptions_create_order_handler' );
function all_subscriptions_create_order_handler(){

	global $wpdb;

	$subscription_form_date= date("Y-m-d H:i:s",strtotime($_REQUEST["subscription_form_date"]));
	$subscription_to_date= date("Y-m-d H:i:s",strtotime($_REQUEST["subscription_to_date"]));

	$blog_id = get_current_blog_id();
    //$sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id AND status = 'active'";
    $sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id";

    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    // vivid( $result );
    if( !empty( $result ) ){
    	$row = 1;
      $comics = array();
      $issue_arr = array();
    	$body_arr = array();
    	foreach ($result as $key => $value) {

        $series_id  = $value['series_id'];
        $user_id  = $value['user_id'];
        $chk_status = $value['status'];

        $author_obj = get_user_by( 'id', $user_id );
        $username   = $author_obj->user_login;
        $email      = $author_obj->user_email;

        if( $value['status'] == 'active'){
            //$comics = array();
            $child_products = vvd_popupcomicshops_get_child_product( $series_id );
           // vivid( 'series_id ---- '.$series_id.'----' );
            // vivid( '---- '.print_r($child_products,true).' ----' );

            foreach ( $child_products as $child_product_id ){
              $available = get_post_meta( $child_product_id, 'available', true );
              $foc_date  = get_post_meta( $child_product_id, 'foc_date', true );
              $issue_seq_no = get_post_meta( $child_product_id, 'issue_sequence_number', true );

              $today_dt = date("Y/m/d");
              $newDateTime= date("Y-m-d H:i:s", strtotime($available));

              $newfoc_date= date("Y-m-d H:i:s", strtotime($foc_date));
              $newToday_dt= date("Y-m-d H:i:s", strtotime($today_dt));

              # check in between admin selected dates
              if(strtotime($newDateTime) >= strtotime($subscription_form_date) && strtotime($newDateTime) <= strtotime($subscription_to_date)){

                # check it is before FOC date or not
                if( strtotime( $newfoc_date ) >= strtotime( $newToday_dt ) ){

                  # check  issue_seq_no = 0 
                  if( $issue_seq_no == 0 ){
                   
                   # check  Already has order or not
                    if( !popupcomicshops_has_existing_order( $user_id , $child_product_id ) ){ 

                      if( !in_array( $child_product_id, $comics ) ){ 
                        $comics[$user_id][] = $child_product_id;
                      }

                    }

                  }
                   
                }else{
                  /* Create array for not receive product */
                  $issue_arr[$user_id][] = $child_product_id;
                }
                
              } 

            }// Child foreach end 
            // $comics = array(59588,59589,59591);
            
        }else{

          $child_products = vvd_popupcomicshops_get_child_product( $series_id );
          $body_content = '';
          foreach ($child_products as $value) {
             
             $product_id = $value;
             $product = wc_get_product( $product_id );

             $prod_name = $product->get_name();
             $body_arr[] = $prod_name;
             //vivid( $prod_name );
          }
          
          //vivid( $body_arr );
          $admin_email = get_option( 'admin_email' );
          $to = $admin_email;
          $subject = 'Unsubscribe Series';
          $body = "<p><b>".$email."</b> has cancelled subscription.</p>";
          $body .= "<p><b>Unsubscribe series as following.</b></p>";
          $body .= "<p>".implode('<br>', $body_arr)."</p>";
          $headers = array('Content-Type: text/html; charset=UTF-8');
           
          // wp_mail( $to, $subject, $body, $headers );

         // vivid('Deactive series');
        }
    		
    		$row++;
    	}

            // vivid( $issue_arr );
            // $log_obj = new wp_popup_cron();
            // $log_obj->create_order_log('Hello Testing');

            if(!empty($issue_arr)){
              foreach ($issue_arr as $user_id => $issue_arr_product) {
                $user_obj = get_user_by( 'id', $user_id );
                $username   = $user_obj->user_login;
                $i = 1;
                vivid( $username.' will not receive these issues due to them being placed into subscription after the Final Order Cutoff.');
                create_foc_log( $username.' will not receive these issues due to them being placed into subscription after the Final Order Cutoff.' );

                foreach( $issue_arr_product as $arr_product => $arr_product_id ){

                  $diamond_number = get_post_meta( $arr_product_id, 'diamond_number', true );

                  $product = wc_get_product( $arr_product_id );
                  vivid( $i .' - '. $product->get_title(). ' | <a href="'. get_edit_post_link($arr_product_id) .'">'.$diamond_number.'</a>'  );
                  // vivid(  $product->get_title().' | '.$diamond_number);
                  create_foc_log( $i .' - '.$product->get_title().' | '.$diamond_number );

                  $i++;
                }
                
              }
            }

            /* Create Order */
            if(!empty($comics)){
              foreach ($comics as $user_id => $comic_product) {
                $status = vvd_popupcomicshops_create_order( $comic_product, $user_id ); 
                 vivid('#'.$status.' Order Created.');
              }
              //$status = vvd_popupcomicshops_create_order( $comics, $user_id );
             
            }else{
              vivid('No Comics to add.');
            }
    }

	wp_die();
}

function vvd_popupcomicshops_get_child_product( $series_code ){		

	$args = array(
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_type'		=> 'product',
    'fields' 		=> 'ids',
    'meta_key'		=> 'series_code',
    'meta_value'	=> $series_code,
  );

	$query = new WP_Query( $args );
	
	$child_product_id = array();
	if( $query->have_posts() ) {
		$child_product_id = $query->posts;
	}

	return $child_product_id;
}

add_action( 'admin_menu', 'register_subscription_import' );
function register_subscription_import(){
    add_menu_page( 
        __( 'Subscription Management', '' ),
        'Subscription Management',
        'manage_options',
        'subscription_subscribers',
        'subscription_import',
       '',
        6
    ); 

    add_submenu_page('subscription_subscribers', __('Subscriber'), __('Subscriber'), 'manage_options', 'subscription_subscribers', 'subscription_import');

  // Add to admin_menu
  //add_submenu_page('subscription_subscribers', __('Create Order'), __('Create Order'), 'manage_options', 'subscription_create_order', 'subscription_create_order');

  add_submenu_page('subscription_subscribers', __('Maintaining Subscriptions by Series'), __('Maintaining Subscriptions by Series'), 'manage_options', 'subscription_by_series', 'subscription_by_series');

}

/*
* Subscriber listing Class Function
*/
require get_stylesheet_directory() .'/asset/va-import/va-import.php';
require get_stylesheet_directory() .'/asset/va_class/class-subscriber-listing.php';
require get_stylesheet_directory() .'/asset/va_class/class-subscriber-series.php';
require get_stylesheet_directory() .'/asset/va_class/class-series-db.php';
require get_stylesheet_directory() .'/asset/include/custom_taxonomy.php';
/*require get_stylesheet_directory() .'/asset/include/custom_posttype.php';*/
require get_stylesheet_directory() .'/asset/include/custom_shortcode.php';
require get_stylesheet_directory() .'/asset/include/custom_ajax.php';
require get_stylesheet_directory() .'/asset/include/custom_csv.php';
require get_stylesheet_directory() .'/asset/include/order_csv.php';
/*require get_stylesheet_directory() .'/asset/include/custom_metabox.php';*/

/*require get_stylesheet_directory() .'/asset/va_class/admin/import-customer-ajax.php';*/


/**
 * Display a custom menu page
 */
function all_subscriptions(){

   $SeriesListTable = new Subscriber_series_List_Table();
   $SeriesListTable->prepare_items(); 
   ?>
   	<div class="wrap">

		<h2>Subscription Listing</h2>
		
		<form id="email-sent-list" method="get">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<div id="ts-history-table" style="">
				<?php
				//wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' ); 
				$SeriesListTable->search_box('Search', 'search');
				$SeriesListTable->display();
				?>
			</div>

		</form>

	</div>

   <?php
   
}




function subscription_create_order(){
	?>
	<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<style type="text/css">
		.form-table th{
			width: 100px;
		}
	</style>
  <div id="dashboard-widgets" class="metabox-holder">
    <div class="postbox-container js">
      <div class="meta-box-sortables ui-sortable">
        <div id="dashboard_right_now" class="postbox ">
          <button type="button" class="handlediv" aria-expanded="true">
            <span class="screen-reader-text">Create Order</span>
            <span class="toggle-indicator" aria-hidden="true"></span>
          </button>
          <h2 class="hndle ui-sortable-handle">
            <span>Create Order</span>
          </h2>
          <div class="inside">
            <div class="main">
              <form method="post" action="">
                <table class="form-table">

                  <tr>
                    <th><label for="ilc_intro">From Date:</label></th>
                    <td>
                      <input type="text" name="subscription_form_date" id="subscription_form_date">
                    </td>
                  </tr>

                  <tr>
                    <th><label for="ilc_intro">To Date:</label></th>
                    <td>
                      <input type="text" name="subscription_to_date" id="subscription_to_date">
                    </td>
                  </tr>

                  <tr>
                    <th><label for="ilc_intro">&nbsp;</label></th>
                    <td>
                      <p class="loading_message"></p>
                      <input type="button" value="Create Order" name="subscription_button" id="subscription_button" class="button button-primary" />
                    </td>
                  </tr>
                </table>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="postbox-container" >
      <div class="meta-box-sortables ui-sortable">
        <div id="dashboard_right_now" class="postbox ">
          <button type="button" class="handlediv" aria-expanded="true">
            <span class="screen-reader-text">Order logs</span>
            <span class="toggle-indicator" aria-hidden="true"></span>
          </button>
          <h2 class="hndle ui-sortable-handle">
            <span>Order logs</span>
          </h2>
          <div class="inside" style="overflow-y: scroll; height: 200px;">
            <div class="main">
              <?php
                $upload_dir = wp_upload_dir();
                //vivid( $upload_dir );
                $path = $upload_dir['baseurl']."/order_log";
                $path_dir = $upload_dir['baseurl']."/order_log";
                $file = $path."/order_log.log";
                $filename = $path_dir."/order_log.log";

                // if (file_exists( $filename )) {
                //     echo "The file $filename exists";
                // } else {
                //     echo "The file $filename does not exist";
                // }

                $file = file($file);
                $file = array_reverse($file);
                foreach($file as $f){
                  echo $f."<br />";
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="postbox-container">
      <div class="meta-box-sortables ui-sortable">
        <div id="dashboard_right_now" class="postbox ">
          <button type="button" class="handlediv" aria-expanded="true">
            <span class="screen-reader-text">FOC Date logs</span>
            <span class="toggle-indicator" aria-hidden="true"></span>
          </button>
          <h2 class="hndle ui-sortable-handle">
            <span>FOC Date logs</span>
          </h2>
          <div class="inside" style="overflow-y: scroll; height: 200px;">
            <div class="main">
              <?php
                $upload_dir = wp_upload_dir();
                //vivid( $upload_dir );
                $path = $upload_dir['baseurl']."/foc_log";
                $path_dir = $upload_dir['baseurl']."/foc_log";
                $file = $path."/foc_log.log";
                $filename = $path_dir."/foc_log.log";

                $file = file($file);
                $file = array_reverse($file);
                foreach($file as $f){
                  echo $f."<br />";
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div id="message_log" style="display:none;">
      <h4>Create order Logs</h4>
    </div>
   <script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#message_log").hide();

      function two_weeks_ago(date_object) {
        var two_weeks = 1209600000; 
        var total = date_object.getTime() - two_weeks;
        var date  = new Date(total);

        //return date.getFullYear() +'/'+ (date.getMonth() + 1) +'/'+ date.getDate();
        return  (date.getMonth() + 1) +'/'+ date.getDate() +'/'+ date.getFullYear();
      }

      console.log( two_weeks_ago( new Date() ) );
      jQuery("#subscription_form_date").datepicker({  maxDate: new Date() }).datepicker("setDate",two_weeks_ago(new Date()));
      jQuery("#subscription_to_date").datepicker({  maxDate: new Date() }).datepicker("setDate", new Date());

			//jQuery("#subscription_form_date").datepicker({  maxDate: new Date() });
			//jQuery("#subscription_to_date").datepicker({  maxDate: new Date() });
			jQuery("#subscription_button").click(function(){
        $this_ = jQuery(this);
				jQuery("#message_log").show();
				jQuery("#subscription_button").attr("disabled","true");
				$this_.val("Please wait while order create. it may take little while .. ");
				jQuery.ajax({
				  method: "POST",
				  url: "<?php echo admin_url('admin-ajax.php'); ?>",
				  data: {
								action: 'all_subscriptions_create_order',
								subscription_form_date : jQuery("#subscription_form_date").val(),
								subscription_to_date : jQuery("#subscription_to_date").val(),
							}
				})
				  .done(function( msg ) {
					  jQuery("#subscription_button").removeAttr("disabled");
					$this_.val("Done");
					jQuery("#message_log").append(msg);
					setTimeout(function(){jQuery(".loading_message").html(""); }, 3000);
				  });
				});
		});
		
		
   </script>
   <?php

}

function subscription_by_series(){
	require get_stylesheet_directory() .'/asset/va_class/admin/subscription_by_series.php';	
}

/*function subscription_import_customer_csv(){
	require get_stylesheet_directory() .'/asset/va_class/admin/import-customer.php';	
}*/


/* Debug */
function vivid($args){
	echo '<pre>';
	print_r($args);
	echo '</pre>';
}



function eds_admin_styles() {
	wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'eds_admin_styles');
/**
 * Display a custom menu page
 */
function subscription_import(){

  wp_enqueue_style('woocommerce_admin_styles');
  wp_enqueue_script( 'selectWoo' );
  wp_enqueue_style( 'select2' );
  wp_enqueue_style( 'subscription-style' );
  
   //exit;
   $myListTable = new Subscriber_List_Table();
   $myListTable->prepare_items(); 
   ?>
   	<div class="wrap">

		<h2>Subscriber Data</h2>
		
		<form id="email-sent-list" method="get">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<div id="ts-history-table" style="">
				<?php
				//wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' ); 
				$myListTable->display();
				?>
			</div>

		</form>

	</div>

   <?php
   
}

add_action( 'wp_ajax_subscription_import', 'subscription_import_handler' );
add_action( 'wp_ajax_nopriv_subscription_import', 'subscription_import_handler' );

function subscription_import_handler() {
    // Make your response and echo it.
	$subscription_form_date= date("Y-m-d H:i:s",strtotime($_REQUEST["subscription_form_date"]));
	$subscription_to_date= date("Y-m-d H:i:s",strtotime($_REQUEST["subscription_to_date"]));

	$subscriptions = popupcomicshops_get_active_subscription();	

	 foreach ( $subscriptions as $subscription_id => $subscription ){
	 	vivid(" ========== subscription Id ->".$subscription_id." ========== ");
		vivid("subscription userId -->".$subscription->get_user_id());
		 $comics = array();	
		 foreach ( $subscription->get_items() as $item_id => $item ){ 

		 // Get series from order items in subscription order 				
		 vivid("product_id ->".$item->get_product_id());

		$parent_sku = get_post_meta( $item->get_product_id(), '_sku', true );
		if($parent_sku != "")
		{
		$child_products = popupcomicshops_get_child_product( $item->get_product_id() );	

		// Get child comics from parent series											
		foreach ( $child_products as $child_product_id ){
			$available = get_post_meta( $child_product_id, 'available', true );
			$newDateTime= date("Y-m-d H:i:s", strtotime($available));

			vivid("child product Id ->".$child_product_id);

			// here we can match condition if $newDateTime <= cureent time
			if(strtotime($newDateTime) >= strtotime($subscription_form_date) && strtotime($newDateTime) <= strtotime($subscription_to_date))
			{
				if( !popupcomicshops_has_existing_order( $subscription->get_user_id(), $child_product_id ) ){ // Check if child comics has already been ordered by customer
					if( !in_array( $child_product_id, $comics ) ){ // Check for uniqueness
						$comics[] = $child_product_id; // Add comics to items array for new order
					}
				}
			} 
		} 
		
	} 

		
		 }
		vivid(" ========== Comics to add ========== ");

		if(!empty($comics)){
			vivid($comics);

		vvd_popupcomicshops_create_order( $comics, $subscription->get_user_id() ); 
		// Create order for child comics

		}else{
			vivid('No Comics to add.');
		}

							

		//popupcomicshops_create_order( $comics, $subscription->get_user_id() ); // Create order for child comics						
	 	
		
	}
	
    // Don't forget to stop execution afterward.
    wp_die();
}


add_action( 'woocommerce_thankyou', 'subscription_product_check', 5 );
function subscription_product_check( $order_id ){
	if( ! $order_id ){
        return;
    }
    $subscription_prod_ids = array();
    $subscription_order = array();
    $parent_prod_arr = array();
    /* Order Data*/
    $order = wc_get_order( $order_id );

    /* Order Item*/
    $items = $order->get_items();

    /* Get Item */
    foreach ( $items as $item ) {
    	/* prod_id*/
    	$product_id = $item['product_id'];
    	$prod_name = $item['name'];
    	if($prod_name == trim('Become Member')){
    		$user_id = get_current_user_id();
    		/* Update authorization status */
    		update_user_meta($user_id,'authorization_status','Yes');
    		
    	}
    	$subscription_prod_ids[] = $product_id;

    	/* Get product */
    	$product      = $item->get_product();
    	$parent_product = get_post_meta($product_id,'_parent_product',true);
    	/* Check Product type = subscription*/
    	if ( $product->is_type( 'subscription' ) && $parent_product == 'yes') {

    		/* Check _SKU */
    		$parent_sku = get_post_meta( $item->get_product_id(), '_sku', true );

    		if($parent_sku != "")
			{
				$parent_prod_arr[] = $product_id;
			}
    	} 
    }

    if(!empty($parent_prod_arr)){
    	/* Order Meta */
    	update_post_meta( $order_id , 'order_has_subscription_product' , 'yes' );
    	update_post_meta( $order_id , 'parent_product_ids' , $parent_prod_arr );
    	//update_post_meta( $order_id , 'parent_product_ids' , $parent_prod_arr );
    }

}

function va_wc_auth_net_cim_tweak_held_order_status( $order_status, $order, $response ) {
	//if ( 'on-hold' === $order_status && $response instanceof SV_WC_Payment_Gateway_API_Response && $response->transaction_approved() ) {
	if ( 'on-hold' === $order_status ) {
		$order_status = 'processing';
	}
	return $order_status;
}
add_filter( 'wc_payment_gateway_authorize_net_cim_credit_card_held_order_status', 'va_wc_auth_net_cim_tweak_held_order_status', 10, 3 );



add_action( 'admin_footer', 'tamberra_popup_func', 10, 1 );
function tamberra_popup_func(){
	?>
	
	<div id="tamberra_popup" class="modal-box">
		<header> <a href="#" class="js-modal-close close">×</a>
			<h3>&nbsp;</h3>
		</header>
		<div class="modal-body">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo at felis vitae facilisis. Cras volutpat fringilla nunc vitae hendrerit. Donec porta id augue quis sodales. Sed sit amet metus ornare, mattis sem at, dignissim arcu. Cras rhoncus ornare mollis. Ut tempor augue mi, sed luctus neque luctus non. Vestibulum mollis tristique blandit. Aenean condimentum in leo ac feugiat. Sed posuere, est at eleifend suscipit, erat ante pretium turpis, eget semper ex risus nec dolor. Etiam pellentesque nulla neque, ut ullamcorper purus facilisis at. Nam imperdiet arcu felis, eu placerat risus dapibus sit amet. Praesent at justo at lectus scelerisque mollis. Mauris molestie mattis tellus ut facilisis. Sed vel ligula ornare, posuere velit ornare, consectetur erat.</p>
		</div>
		<footer> <a href="#" class="btn btn-small js-modal-close">Close</a> </footer>
	</div>

	<?php
}



function hwl_home_pagesize( $query ) {

  if ( ! is_admin() ) {
     // echo "You are viewing the theme";
  } else {
      if ( is_post_type_archive( 'product' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 5 );
        return;
    }
  }

    
}
add_action( 'pre_get_posts', 'hwl_home_pagesize', 1 );

/* View All subscriptions has no data on all sites */
function fix_request_query_args_for_woocommerce( $query_args ) {
	
	if(isset($query_args['post_type']) == 'shop_subscription'){
		if ( isset( $query_args['post_status'] ) && empty( $query_args['post_status'] ) ) {
			unset( $query_args['post_status'] );
		}
	}
	
	return $query_args;
}
add_filter( 'request', 'fix_request_query_args_for_woocommerce', 1, 1 );



/*Tickera - All Events Shortcode*/

function tc_event_query() { 
    ob_start();
    //query argumenets
    $args = array(
        'post_type' => 'tc_events',
        'posts_per_page' => 6,
        'meta_query' => array(
            array(
                'key' => 'event_date_time',
                'value' => date('Y-m-d H:i'),
                'type' => 'DATETIME',
                'compare' => '>='
            ),
            'orderby' => 'event_date_time',
        ),
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'post_status' => 'publish'
    );
    // The Query
    $the_query = new WP_Query($args);
    // The Loop
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $event_date_time_raw = get_post_meta(get_the_ID(), 'event_date_time', true);
            $event_date_formatted = date_i18n(get_option('date_format'), strtotime($event_date_time_raw));
            $event_time_formatted = date_i18n(get_option('time_format'), strtotime($event_date_time_raw));
?>
                                <div class="tc-single-event">
                                   <h3>
                                       <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                        </a>
                                   </h3>
                                    <div class="tc_the_content_pre">
                                        <i class="fa fa-clock-o"></i> <span class="tc_event_date_title_front"><?php echo $event_date_formatted.' | '.$event_time_formatted; ?> </span>
                                        <!--<i class="fa fa-map-marker"></i> <span class="tc_event_location_title_front"><?php //echo get_post_meta(get_the_ID(), 'event_location', true); ?></span>-->
                                    </div>
                                    <?php
                                    if ( is_plugin_active( 'bridge-for-woocommerce/bridge-for-woocommerce.php' ) ) {
                                        //echo do_shortcode('[tc_wb_event]');
                                        }
                                    else {
                                        //echo do_shortcode('[tc_event]');
                                        }
                                    ?>
                                </div>
        <?php
        }
    } else {
        // if no posts are found you can add message here
    }
    /* Restore original Post Data */
    wp_reset_postdata();
    $content = ob_get_clean();
    return $content;
}
//tc_event_query
add_shortcode('all_events', 'tc_event_query');


//add_action('woocommerce_before_checkout_form', 'vvd_only_one_in_cart');
//add_action('woocommerce_before_cart', 'vvd_only_one_in_cart');
//add_action('woocommerce_before_mini_cart', 'vvd_only_one_in_cart'); 
add_filter( 'woocommerce_add_to_cart_validation', 'vvd_only_one_in_cart', 99, 2 );
function vvd_only_one_in_cart( $passed, $added_product_id ) {

	global $wpdb;
	
	$blog_id = get_current_blog_id();

	$wpdb->blogid = $blog_id;
	$wpdb->set_prefix( $wpdb->base_prefix );
	$posttitle = 'Become Member';
	$product_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_type ='product'" );

	if($added_product_id == $product_id){

		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$authorization_status = get_user_meta($user_id,'authorization_status',true);
		if($authorization_status != trim('Yes')){
			wc_empty_cart();
		}else{
			wc_clear_notices();
			wc_add_notice( sprintf( esc_html__( "Already have membership.", "popupcomic" ) ) ,'error' );
			return false;
		}
		
	}
	
	$product_cart_id = WC()->cart->generate_cart_id( $product_id );
	$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

	if ( $in_cart ) {

		wc_clear_notices();
		wc_add_notice( sprintf( esc_html__( "'Become Member' , Only onetime payment for membership. After that you can subscribe this comics.", "popupcomic" ) ) ,'error' );

		return false;
	}

	
	//empty cart first: new item will replace previous
	return $passed;
}

 /* Check if item already in cart */
function woo_in_cart($product_id) {
    global $woocommerce;
 
    foreach($woocommerce->cart->get_cart() as $key => $val ) {
        $_product = $val['data'];
 
        if($product_id == $_product->id ) {
            return true;
        }
    }
 
    return false;
}

/*
 * Step 1. Add Link (Tab) to My Account menu
 */
add_filter ( 'woocommerce_account_menu_items', 'misha_log_history_link', 40 );
function misha_log_history_link( $menu_links ){
 
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'subscriptions_mng' => 'My Subscriptions' )
	+ array_slice( $menu_links, 5, NULL, true );
 
	return $menu_links;
 
}
/*
 * Step 2. Register Permalink Endpoint
 */
add_action( 'init', 'misha_add_endpoint' );
function misha_add_endpoint() {
 
	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'subscriptions_mng', EP_PAGES );
 
}
/*
 * Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_subscriptions_mng_endpoint', 'misha_my_account_endpoint_content' );
function misha_my_account_endpoint_content() {
 	wp_enqueue_script( 'subscription-script' );
 	wp_enqueue_style( 'subscription-style' );
 	global $wpdb;

	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
	//echo 'Last time you logged in: yesterday from Safari.';
 	$series = get_user_meta(get_current_user_id(),'_user_subscription_series_id',true);
	$sub_time = get_user_meta(get_current_user_id(),'subscription_time_',true);
	$blog_id = get_current_blog_id();

	$sql = "SELECT *  FROM `subscribers_data` WHERE `user_id` = ".get_current_user_id()." AND `blog_id` = $blog_id";

	$result = $wpdb->get_results( $sql, 'ARRAY_A' );
  echo '<div class="jq-tabs tabs_wrapper tabs_horizontal ui-tabs ui-widget ui-widget-content ui-corner-all">
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
    <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="my-subscription" aria-labelledby="ui-id-1" aria-selected="false" aria-expanded="false">
      <a href="#my-subscription" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">My Subscription</a>
    </li>
    <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active ui-state-hover" role="tab" tabindex="0" aria-controls="find-subscription" aria-labelledby="ui-id-2" aria-selected="true" aria-expanded="true">
      <a href="#find-subscription" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Subscription
      </a>
    </li>
  </ul>
  <div id="my-subscription" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
    ';
    if(!empty($result)){

    


    $i = 0;
    foreach ($result as $key => $value) {
      #code 
      $series_id = $value['series_id'];
      $sdata = new series_subscription();
      $series_data = $sdata->series_data( $series_id );

      $series_code = $series_data->code;
      $description = $series_data->description;
      $status    = $value['status'];

      if( $status == trim('active')){
        $btn  = '<button type="button" name="ax_popup_remove_btn" class="ax_popup_remove_btn sub_remove_user" data-seriesid="'.$series_code.'" data-user_id="'.get_current_user_id().'">Unsubscribe</button>';
      }else{
        $btn_text = 'Subscribe';
        $class = 'add_parent_product_id';
        $url = 'javascript:void(0);';
        $u_id = get_current_user_id();
        $btn  = '&nbsp; <a rel="nofollow" href="' . $url . '" data-quantity="1" data-product_id="' . $series_id . '" class="product_type_simple '.$class.'" data-user_id = "'.$u_id.'"><button type="button" class="single_add_to_cart_button button alt" id="sub_btn">'.$btn_text.'</button></a>';
      }

      //vivid($s_id);
      echo '<div class="ax_series_list_item_container '.$series_id.'">
              <div class="ax_series_seg_1">
                  <label class="ax_series_code">'.$series_code.'</label>
              </div>
              <div class="ax_series_seg_2">
                  <label class="ax_series_title">'.$description.'</label>
              </div>
              <div class="ax_customer_list_seg_3">'.$btn.'</div>
          </div>';
      $i++;
    }

  }else{
    _e( 'No subscriber avaliable.', 'sp' );
  }
    echo '
  </div>
  <div id="find-subscription" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false" style="display: block;">';
    
    if( isset( $_GET['product_name'] ) ){
      $string = $_GET['product_name'];
    }else{
      $string = '';
    }
    echo '<div class="search-fields">';
      echo '<form method="get" name="series_search_form" class="series_search_form">';
      echo '<div class="input-group">';
          echo '<input type="text" name="product_name" style="display: inline;" value="'. $string .'" placeholder="Search by title">';
          echo '<button type="submit" class="button">Search</button>';
      echo '</div>';
      echo '</form>';
    echo '</div>'; 

    global $wpdb;
  // QUERY HERE TO COUNT TOTAL RECORDS FOR PAGINATION 
  $total = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM series_subscription) AS a");
  $post_per_page = 10;
  $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

  $wpdb_table = 'series_subscription';

  
  $product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : '';
  $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'code';
  $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
  $user_query = "SELECT * FROM $wpdb_table";

  $offset = ( $page * $post_per_page ) - $post_per_page;

  if( $product_name ){
    $user_query .= " WHERE `description` LIKE '%$product_name%'";
  }

  if( $orderby ){
    $user_query .= " ORDER BY $orderby";
  }

  if( $order ){
    $user_query .= " $order";
  }

  if( $post_per_page ){
    $user_query .= " LIMIT $post_per_page";
  }

  if( $offset ){
    $user_query .= " OFFSET $offset";
  }

  //$user_query = "SELECT * FROM `series_subscription` WHERE `description` LIKE 'Spawn' ORDER BY code ASC LIMIT 10";

  $results = $wpdb->get_results( $user_query, ARRAY_A  );
  if( $product_name ){
    $total = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM series_subscription WHERE `description` LIKE '%$product_name%' ) AS a");
  }
  // QUERY HERE TO GET OUR RESULTS 
  //$results = $wpdb->get_results("SELECT * FROM series_subscription LIMIT $post_per_page OFFSET $offset");

  
  
  // PHP FOR EACH LOOP HERE TO DISPLAY OUR RESULTS
  foreach($results as $row)
   {

    $series_class = new series_subscription();
    $series_data = $series_class->series_data( $row['code'] );

    $subscribers_sql = "SELECT *  FROM `subscribers_data` WHERE `user_id` = ".get_current_user_id()." AND `blog_id` = $blog_id AND `series_id` =  ".$row['code'];

    $subscribers_result = $wpdb->get_results( $subscribers_sql, 'ARRAY_A' );

     if( !empty($subscribers_result)){

        $series_id = $subscribers_result[0]['series_id'];

        $series_code = $series_data->code;
        $description = $series_data->description;
        $status    = $subscribers_result[0]['status'];

        

        if( $status == trim('active')){
          $btn  = '<button type="button" name="ax_popup_remove_btn" class="ax_popup_remove_btn sub_remove_user" data-seriesid="'.$series_code.'" data-user_id="'.get_current_user_id().'">Unsubscribe</button>';
        }else{
          $btn_text = 'Subscribe';
          $class = 'add_parent_product_id';
          $url = 'javascript:void(0);';
          $u_id = get_current_user_id();
          $btn  = '&nbsp; <a rel="nofollow" href="' . $url . '" data-quantity="1" data-product_id="' . $series_id . '" class="product_type_simple '.$class.'" data-user_id = "'.$u_id.'"><button type="button" class="single_add_to_cart_button button alt" id="sub_btn">'.$btn_text.'</button></a>';
        }

        echo '<div class="ax_series_list_item_container '.$row['code'].'">
                <div class="ax_series_seg_1">
                    <label class="ax_series_code">'.$row['code'].'</label>
                </div>
                <div class="ax_series_seg_2">
                    <label class="ax_series_title">'.$row['description'].'</label>
                </div>
                <div class="ax_customer_list_seg_3">'.$btn.'</div>
            </div>';

     }else {

        $btn_text = 'Subscribe';
        $class = 'add_parent_product_id';
        $url = 'javascript:void(0);';
        $u_id = get_current_user_id();
        $btn  = '&nbsp; <a rel="nofollow" href="' . $url . '" data-quantity="1" data-product_id="' . $row['code'] . '" class="product_type_simple '.$class.'" data-user_id = "'.$u_id.'"><button type="button" class="single_add_to_cart_button button alt" id="sub_btn">'.$btn_text.'</button></a>';

       echo '<div class="ax_series_list_item_container '.$row['code'].'">
              <div class="ax_series_seg_1">
                  <label class="ax_series_code">'.$row['code'].'</label>
              </div>
              <div class="ax_series_seg_2">
                  <label class="ax_series_title">'.$row['description'].'</label>
              </div>
              <div class="ax_customer_list_seg_3">'.$btn.'</div>
          </div>';
     }
        
    

   //echo '<p>'.$row->code.' - '.$row->description.'</p>'; 
   }
// END OUR FOR EACH LOOP

   /* Pagination */

   echo '<div class="pagination">';
  // echo $page.' -->'.$total.' -->'.$post_per_page;
    echo paginate_links( array(
'base' => add_query_arg( 'cpage', '%#%' ),
'format' => '',
'prev_text' => __('&laquo;'),
'next_text' => __('&raquo;'),
'total' => ceil($total / $post_per_page),
'current' => $page,
'type' => 'list'
));
    echo '</div>';

   /* End Pagination */
  echo '</div>
</div>';
	
	
	//echo $html;
}
/*
 * Step 4
 */
// Go to Settings > Permalinks and just push "Save Changes" button.
add_filter('woocommerce_login_redirect', 'redirect_previous_page', 10, 2);
add_filter( 'woocommerce_registration_redirect', 'redirect_previous_page', 10, 1 ); 
function redirect_previous_page( $redirect ) {

    if( isset( $_POST['last_page_referer'] ) && 0 != strlen( $_POST['last_page_referer'] ) ) {
        $redirect = esc_url( $_POST['last_page_referer'] );
    }
    return $redirect;
}

add_action( 'woocommerce_login_form_end', 'wooc_extra_register_fields' );
function wooc_extra_register_fields() {
  
  ?>
   <input type="hidden" name="last_page_referer" class="input-text" id="last_page_referer"  value="<?php if( isset( $_POST['wp_last_page_referer'] ) && 0 != strlen( $_POST['wp_last_page_referer'] ) ) { echo esc_url( $_POST['wp_last_page_referer'] ); } ?>">

  <?php
}


add_action( 'woocommerce_before_add_to_cart_form', 'bbloomer_custom_action', 5 );
 
function bbloomer_custom_action($product) {
  if ( !is_user_logged_in() ) {
    global $wp;
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    $site = get_blog_details();
    $url = $site->path. 'my-account';
    $html ='<form id="last_page_referer_form" action="'.$url.'" method="post" style="display:none;"><input type="hidden" name="wp_last_page_referer" class="input-text" id="wp_last_page_referer"  value="'.$current_url.'"><input type="submit" value="Send" id="my_submit_click"></form>';
    $html .='<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("a.product_type_simple").click(function(){
        jQuery("#my_submit_click").trigger("click");
    });
  });
</script>';

    echo $html;
  }

}

add_shortcode('vivid-prod-update','add_prod_price_meta');
function add_prod_price_meta(){
  $status = array('publish', 'draft');
  $args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => $status,
  );

  $query = new WP_Query( $args );
  $all_post = $query->posts;
  vivid(count( $all_post ));

  if(!empty( $all_post )){
    foreach ($all_post as $key => $value) {
      # code...
      $post_id = $value->ID;

      // vivid( $post_id );

      $reg_price = get_post_meta( $post_id, '_regular_price', true );
      // vivid( $reg_price );

      if( !empty( $reg_price )){
        //$reg_price = 35;
        update_post_meta( $post_id, '_price', $reg_price );
        //update_post_meta( $post_id, '_regular_price', $reg_price );
      }
    }
  }
}

function me_search_query( $query ) {
  if ( $query->is_search ) {
    $meta_query_args = array(
      array(
        'key' => 'diamond_number',
        'value' => $query->query_vars['s'],
        'compare' => 'LIKE',
      ),
    );

    // vivid( $meta_query_args );

    $query->set('meta_query', $meta_query_args);
  };
}
//add_filter( 'pre_get_posts', 'me_search_query');


add_action('init','setup_FOC_log_dir');
function setup_FOC_log_dir(){
    $upload_dir = wp_upload_dir();
    $log_dir = $upload_dir['basedir']."/foc_log/" ;

    if ( ! is_dir( $log_dir ) ) {
      wp_mkdir_p( $log_dir, 0777 );
           if ( $file_handle = @fopen( trailingslashit( $log_dir ) .'foc_log.log', 'w' ) ) {
            fwrite( $file_handle, 'testing' );
            fclose( $file_handle );
          }
    }

}

function create_foc_log($str) {

  $d = date("j-M-Y H:i:s");

  $upload_dir = wp_upload_dir();
  $create_order_dir = $upload_dir['basedir']."/foc_log/" ;
  error_log(' ['.$d.'] - '. $str.PHP_EOL, 3, $create_order_dir."/foc_log.log");
}



// add_filter('woof_get_request_data', 'my_woof_get_request_data');
//  
// function my_woof_get_request_data($request) {
//     if (is_shop()) :
//         $request['product_cat'] = 'comics';   
//         return $request;
//     endif; 
// }

/* WooCommerce - Exclude Products From Shipping */

// function cs_exlude_free_shipping( $packages ) {
//   foreach( $packages as $i => $package ){
//     foreach ( $package['contents'] as $key => $item ) {
//       if ( $item['data']->get_shipping_class() == 'free-shipping' ) {
//         unset( $packages[$i]['contents'][$key] );
//         add_filter( 'woocommerce_cart_needs_shipping', '__return_true' );
//       }
//     }
//   }
//   return $packages;
// }
// add_filter( 'woocommerce_cart_shipping_packages', 'cs_exlude_free_shipping' );


/**
 * @snippet       Hide ALL shipping rates in ALL zones when Free Shipping is available
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=260
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.3
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// add_filter( 'woocommerce_package_rates', 'bbloomer_unset_shipping_when_free_is_available_all_zones', 10, 2 );
//    
// function bbloomer_unset_shipping_when_free_is_available_all_zones( $rates, $package ) {
//       
// $all_free_rates = array();
//      
// foreach ( $rates as $rate_id => $rate ) {
//       if ( 'free_shipping' === $rate->method_id ) {
//          $all_free_rates[ $rate_id ] = $rate;
//          break;
//       }
// }
//      
// if ( empty( $all_free_rates )) {
//         return $rates;
// } else {
//         return $all_free_rates;
// } 
//  
// }




// add_action( 'after_setup_theme', 'mfn_get_fields_item' );
  function mfn_get_fields_item( $item = false ){

    $items = array(

        // Placeholder ----------------------------------------------------

      'placeholder' => array(
        'type'    => 'placeholder',
        'title'   => __('- Placeholder', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('This is Muffin Builder Placeholder.', 'nhp-opts'),
            'class'   => 'mfn-info info',
          ),

        ),
      ),

      // Accordion  -----------------------------------------------------

      'accordion' => array(
        'type'    => 'accordion',
        'title'   => __('Accordion', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'tabs',
            'type'    => 'tabs',
            'title'   => __('Accordion', 'mfn-opts'),
            'sub_desc'  => __('You can use Drag & Drop to set the order', 'mfn-opts'),
            'desc'    => __('<b>JavaScript</b> content like Google Maps and some plugins shortcodes do <b>not work</b> in tabs', 'mfn-opts'),
          ),

          array(
            'id'    => 'open1st',
            'type'    => 'select',
            'title'   => __('Open First', 'mfn-opts'),
            'desc'    => __('Open first tab at start.', 'mfn-opts'),
            'options' => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'openAll',
            'type'    => 'select',
            'options'   => array( 0 => 'No', 1 => 'Yes' ),
            'title'   => __('Open All', 'mfn-opts'),
            'desc'    => __('Open all tabs at start', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options' => array(
              'accordion' => __( 'Accordion', 'mfn-opts' ),
              'toggle'  => __( 'Toggle', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Article box  ---------------------------------------------------

      'article_box' => array(
        'type'    => 'article_box',
        'title'   => __( 'Article box', 'mfn-opts' ),
        'size'    => '1/3',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'sub_desc'=> __( 'Featured Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>384px - 960px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'      => 'slogan',
            'type'    => 'text',
            'title'   => __( 'Slogan', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          // link
          array(
            'id'      => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Link', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Link | Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'      => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'sub_desc'=> __( 'Entrance animation', 'mfn-opts' ),
            'options' => mfn_get_animations(),
          ),

          // custom
          array(
            'id'      => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Before After  ---------------------------------------------------

      'before_after' => array(
        'type'    => 'before_after',
        'title'   => __('Before After', 'mfn-opts'),
        'size'    => '1/3',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image_before',
            'type'    => 'upload',
            'title'   => __('Image | Before', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'image_after',
            'type'    => 'upload',
            'title'   => __('Image | After', 'mfn-opts'),
            'desc'    => __('Both images <b>must have the same size</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Blockquote -----------------------------------------------------

      'blockquote' => array(
        'type'    => 'blockquote',
        'title'   => __('Blockquote', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'typography',
        'fields'  => array(

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'sub_desc'  => __('Blockquote content.', 'mfn-opts'),
            'desc'    => __('Some HTML tags allowed.', 'mfn-opts')
          ),

          array(
            'id'    => 'author',
            'type'    => 'text',
            'title'   => __('Author', 'mfn-opts'),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
            'sub_desc'  => __('Link to company page.', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Blog -----------------------------------------------------------

      'blog' => array(
        'type'    => 'blog',
        'title'   => __('Blog', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'sub_desc'  => __('Number of posts to show', 'mfn-opts'),
            'std'     => '3',
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'desc'    => __('If you do not know what <b>image size</b> is being used for selected style, please navigate to the: Appearance > <a target="_blank" href="admin.php?page=be-options">Theme Options</a> > Blog, Portfolio & Shop > <b>Featured Images</b>', 'mfn-opts'),
            'options' => array(
              'classic'   => __( 'Classic', 'mfn-opts' ),
              'grid'      => __( 'Grid', 'mfn-opts' ),
              'masonry'   => __( 'Masonry Blog Style', 'mfn-opts' ),
              'masonry tiles' => __( 'Masonry Tiles', 'mfn-opts' ),
              'photo'     => __( 'Photo (Horizontal Images)', 'mfn-opts' ),
              'timeline'    => __( 'Timeline', 'mfn-opts' ),
            ),
            'std'   => 'grid',
          ),

          array(
            'id'    => 'columns',
            'type'    => 'select',
            'title'   => __('Columns', 'mfn-opts'),
            'desc'    => __('Default: 3. Recommended: 2-4. Too large value may crash the layout.<br />This option works in styles: <b>Grid, Masonry</b>', 'mfn-opts'),
            'options'  => array(
              2 => 2,
              3 => 3,
              4 => 4,
              5 => 5,
              6 => 6,
            ),
            'std'     => 3,
          ),

          array(
            'id'    => 'images',
            'type'    => 'select',
            'title'   => __('Post Image', 'mfn-opts'),
            'desc'    => __('for all Blog styles except Masonry Tiles', 'mfn-opts'),
            'options' => array(
              ''        => 'Default',
              'images-only'   => 'Featured Images only (replace sliders and videos with featured image)',
            ),
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __( 'Category', 'mfn-opts' ),
            'options'   => mfn_get_categories( 'category' ),
            'sub_desc'  => __( 'Select posts category', 'mfn-opts' ),
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __( 'Multiple Categories', 'mfn-opts' ),
            'sub_desc'  => __( 'Categories <b>slugs</b>', 'mfn-opts' ),
            'desc'    => __( 'Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts' ),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __( 'Order by', 'mfn-opts' ),
            'desc'    => __( 'Do not use random order with pagination or load more', 'mfn-opts' ),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __( 'Order', 'mfn-opts' ),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'exclude_id',
            'type'    => 'text',
            'title'   => __('Exclude Posts', 'mfn-opts'),
            'sub_desc'  => __('Posts <b>IDs</b>', 'mfn-opts'),
            'desc'    => __('IDs should be separated with <b>coma</b> ( , )', 'mfn-opts'),
          ),

          array(
            'id'    => 'filters',
            'type'    => 'select',
            'title'   => __('Filters', 'mfn-opts'),
            'desc'    => __('This option works in <b>Category: All</b> and <b>Style: Masonry</b>', 'mfn-opts'),
            'options'   => array(
              '0'         => __( 'Hide', 'mfn-opts' ),
              '1'         => __( 'Show', 'mfn-opts' ),
              'only-categories'   => __( 'Show only Categories', 'mfn-opts' ),
              'only-tags'     => __( 'Show only Tags', 'mfn-opts' ),
              'only-authors'    => __( 'Show only Authors', 'mfn-opts' ),
            ),
            'std'     => '0'
          ),

          array(
            'id'    => 'more',
            'type'    => 'select',
            'options'   => array( 0 => 'No', 1 => 'Yes' ),
            'title'   => __('Read More link', 'mfn-opts'),
            'std'   => 1,
          ),

          // pagination
          array(
            'id'    => 'info_pagination',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Pagination', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'pagination',
            'type'    => 'select',
            'options'   => array( 0 => 'No', 1 => 'Yes' ),
            'title'   => __('Pagination', 'mfn-opts'),
            'desc'    => __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site.', 'mfn-opts'),
          ),

          array(
            'id'    => 'load_more',
            'type'    => 'select',
            'title'   => __('Load More button', 'mfn-opts'),
            'desc'    => __('<b>Sliders</b> will be replaced with featured images', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // Style
          array(
            'id'    => 'info_style',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Style', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => __('Greyscale Images', 'mfn-opts'),
            'options'   => array( 0 => 'No', 1 => 'Yes' ),
          ),

          array(
            'id'    => 'margin',
            'type'    => 'select',
            'title'   => __('Margin', 'mfn-opts'),
            'desc'    => __('for <b>Style: Masonry Tiles</b> only', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // Plugins
          array(
            'id'    => 'info_plugins',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Plugins', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'events',
            'type'    => 'select',
            'title'   => __('Include events', 'mfn-opts'),
            'sub_desc'  => __('The Events Calendar', 'mfn-opts'),
            'desc'    => __('This option works in <b>Category: All</b> and requires free <b>The Events Calendar</b> plugin', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Blog News ------------------------------------------------------

      'blog_news' => array(
        'type'    => 'blog_news',
        'title'   => __('Blog News', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'desc'    => __('Image size for this item is the same as for Blog Page, please navigate to the: Appearance > <a target="_blank" href="admin.php?page=be-options">Theme Options</a> > Blog, Portfolio & Shop > <b>Featured Images</b> > Blog & Portfolio', 'mfn-opts'),
            'options'   => array(
              ''      => __('Default', 'mfn-opts'),
              'featured'  => __('Featured 1st', 'mfn-opts'),
            ),
          ),

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'sub_desc'  => __('Number of posts to show', 'mfn-opts'),
            'std'     => '5',
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __( 'Category', 'mfn-opts' ),
            'options'   => mfn_get_categories( 'category' ),
            'sub_desc'  => __( 'Select posts category', 'mfn-opts' ),
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __( 'Multiple Categories', 'mfn-opts' ),
            'sub_desc'  => __( 'Categories <b>slugs</b>', 'mfn-opts' ),
            'desc'    => __( 'Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts' ),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __( 'Order by', 'mfn-opts' ),
            'desc'    => __( 'Do not use random order with pagination or load more', 'mfn-opts' ),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __( 'Order', 'mfn-opts' ),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'excerpt',
            'type'    => 'select',
            'title'   => __('Excerpt', 'mfn-opts'),
            'options'   => array(
              0       => __('Hide', 'mfn-opts'),
              1       => __('Show', 'mfn-opts'),
              'featured'  => __('Show for Featured only', 'mfn-opts'),
            ),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Button | Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'link_title',
            'type'    => 'text',
            'title'   => __('Button | Title', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Blog Slider ----------------------------------------------------

      'blog_slider' => array(
        'type'    => 'blog_slider',
        'title'   => __('Blog Slider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'sub_desc'  => __('Number of posts to show', 'mfn-opts'),
            'desc'    => __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
            'std'     => '5',
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __( 'Category', 'mfn-opts' ),
            'options'   => mfn_get_categories( 'category' ),
            'sub_desc'  => __( 'Select posts category', 'mfn-opts' ),
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __( 'Multiple Categories', 'mfn-opts' ),
            'sub_desc'  => __( 'Categories <b>slugs</b>', 'mfn-opts' ),
            'desc'    => __( 'Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts' ),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __( 'Order by', 'mfn-opts' ),
            'desc'    => __( 'Do not use random order with pagination or load more', 'mfn-opts' ),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __( 'Order', 'mfn-opts' ),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'more',
            'type'    => 'select',
            'title'   => __('Show Read More button', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
            'std'   => 1,
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options' => array(
              ''      => __('Default', 'mfn-opts'),
              'flat'    => __('Flat', 'mfn-opts'),
            ),
          ),

          array(
            'id'    => 'navigation',
            'type'    => 'select',
            'title'   => __('Navigation', 'mfn-opts'),
            'options' => array(
              ''        => __('Default', 'mfn-opts'),
              'hide-arrows' => __('Hide Arrows', 'mfn-opts'),
              'hide-dots'   => __('Hide Dots', 'mfn-opts'),
              'hide-nav'    => __('Hide Navigation', 'mfn-opts'),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Blog Teaser ------------------------------------------------------

      'blog_teaser' => array(
        'type'    => 'blog_teaser',
        'title'   => __('Blog Teaser', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Recommended wrap width: 1/1, minimum wrap width: 2/3', 'nhp-opts'),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'title_tag',
            'type'    => 'select',
            'title'   => __('Title | Tag', 'mfn-opts'),
            'desc'    => __('Title tag for 1st item, others use a smaller one', 'mfn-opts'),
            'options'   => array(
              'h1' => 'H1',
              'h2' => 'H2',
              'h3' => 'H3',
              'h4' => 'H4',
              'h5' => 'H5',
              'h6' => 'H6',
            ),
            'std'   => 'h3'
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __( 'Category', 'mfn-opts' ),
            'options'   => mfn_get_categories( 'category' ),
            'sub_desc'  => __( 'Select posts category', 'mfn-opts' ),
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __( 'Multiple Categories', 'mfn-opts' ),
            'sub_desc'  => __( 'Categories <b>slugs</b>', 'mfn-opts' ),
            'desc'    => __( 'Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts' ),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __( 'Order by', 'mfn-opts' ),
            'desc'    => __( 'Do not use random order with pagination or load more', 'mfn-opts' ),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __( 'Order', 'mfn-opts' ),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'margin',
            'type'    => 'select',
            'title'   => __('Margin', 'mfn-opts'),
            'options'   => array(
              '1'     => __('Default', 'mfn-opts'),
              '0'     => __('No margins', 'mfn-opts'),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Button ----------------------------------------------------

      'button' => array(
        'type'    => 'button',
        'title'   => __('Button', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'typography',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __('Align', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Left', 'mfn-opts' ),
              'center'  => __( 'Center', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
            ),
          ),

          // icon
          array(
            'id'    => 'info_icon',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Icon', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'icon_position',
            'type'    => 'select',
            'title'   => __('Position', 'mfn-opts'),
            'options' => array(
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
            ),
          ),

          // color
          array(
            'id'    => 'info_color',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Color', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'color',
            'type'    => 'color',
            'title'   => __('Background', 'mfn-opts'),
          ),

          array(
            'id'    => 'font_color',
            'type'    => 'color',
            'title'   => __('Font', 'mfn-opts'),
          ),

          // style
          array(
            'id'    => 'info_style',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Style', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'size',
            'type'    => 'select',
            'title'   => __('Size', 'mfn-opts'),
            'options' => array(
              1 => __( 'Small', 'mfn-opts' ),
              2 => __( 'Default', 'mfn-opts' ),
              3 => __( 'Large', 'mfn-opts' ),
              4 => __( 'Very Large', 'mfn-opts' ),
            ),
            'std'     => 2,
          ),

          array(
            'id'    => 'full_width',
            'type'    => 'select',
            'title'   => __('Full Width', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'class',
            'type'    => 'text',
            'title'   => __('Class', 'mfn-opts'),
            'desc'    => __('This option is useful when you want to use <b>scroll</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'rel',
            'type'    => 'text',
            'title'   => __('Rel', 'mfn-opts'),
            'desc'    => __('Adds an rel="..." attribute to the link', 'mfn-opts'),
          ),

          array(
            'id'    => 'download',
            'type'    => 'text',
            'title'   => __('Download', 'mfn-opts'),
            'sub_desc'  => __('Download file when clicking on the link', 'mfn-opts'),
            'desc'    => __('Enter the new filename for the downloaded file', 'mfn-opts'),
          ),

          array(
            'id'    => 'onclick',
            'type'    => 'text',
            'title'   => __('OnClick', 'mfn-opts'),
            'desc'    => __('Adds an onclick="..." attribute to the link', 'mfn-opts'),
          ),

        ),
      ),

      // Call to Action -------------------------------------------------

      'call_to_action' => array(
        'type'    => 'call_to_action',
        'title'   => __('Call to Action', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('HTML tags allowed.', 'mfn-opts'),
          ),

          array(
            'id'    => 'button_title',
            'type'    => 'text',
            'title'   => __('Button Title', 'mfn-opts'),
            'desc'    => __('Leave this field blank if you want Call to Action with Big Icon', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // link
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'class',
            'type'    => 'text',
            'title'   => __('Class', 'mfn-opts'),
            'desc'    => __('This option is useful when you want to use <b>scroll</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Chart  ---------------------------------------------------------

      'chart' => array(
        'type'    => 'chart',
        'title'   => __('Chart', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          // chart
          array(
            'id'    => 'info_chart',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Chart', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'percent',
            'type'    => 'text',
            'title'   => __('Percent', 'mfn-opts'),
            'desc'    => __('Number between 0-100', 'mfn-opts'),
          ),

          array(
            'id'    => 'label',
            'type'    => 'text',
            'title'   => __('Label', 'mfn-opts'),
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('Recommended image size: <b>70px x 70px</b>', 'mfn-opts'),
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'line_width',
            'type'    => 'text',
            'title'   => __('Line Width', 'mfn-opts'),
            'sub_desc'  => __('optional', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Clients  -------------------------------------------------------

      'clients' => array(
        'type'    => 'clients',
        'title'   => __('Clients', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'in_row',
            'type'    => 'text',
            'title'   => __('Items in Row', 'mfn-opts'),
            'sub_desc'  => __('Number of items in row', 'mfn-opts'),
            'desc'    => __('Recommended number: 3-6', 'mfn-opts'),
            'std'     => 6,
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'client-types' ),
            'sub_desc'  => __('Select the client post category.', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'menu_order'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'ASC'
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Default', 'mfn-opts' ),
              'tiles'   => __( 'Tiles', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => 'Greyscale Images',
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Clients Slider -------------------------------------------------

      'clients_slider' => array(
        'type'    => 'clients_slider',
        'title'   => __('Clients Slider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'client-types' ),
            'sub_desc'  => __('Select the client post category.', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'menu_order'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'ASC'
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Code  ----------------------------------------------------------

      'code' => array(
        'type'    => 'code',
        'title'   => __('Code', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'class'   => 'full-width',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Column  --------------------------------------------------------

      'column' => array(
        'type'    => 'column',
        'title'   => __('Column', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'typography',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
            'desc'    => __('This field is used as an Item Label in admin panel only', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Shortcodes and HTML tags allowed. Some plugin\'s shortcodes work only in WordPress editor', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          array(
            'id'      => 'align',
            'type'    => 'select',
            'title'   => __( 'Text Align', 'mfn-opts' ),
            'options' => array(
              ''        => __( '-- Default --', 'mfn-opts' ),
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
              'center'  => __( 'Center', 'mfn-opts' ),
              'justify' => __( 'Justify', 'mfn-opts' ),
            ),
          ),

          array(
            'id'        => 'align-mobile',
            'type'      => 'select',
            'title'     => __( 'Text Align | Mobile', 'mfn-opts' ),
            'sub_desc'  => __( '< 768px', 'mfn-opts' ),
            'options'   => array(
              ''          => __( '-- The same as selected above --', 'mfn-opts' ),
              'left'      => __( 'Left', 'mfn-opts' ),
              'right'     => __( 'Right', 'mfn-opts' ),
              'center'    => __( 'Center', 'mfn-opts' ),
              'justify'   => __( 'Justify', 'mfn-opts' ),
            ),
          ),

          // background
          array(
            'id'      => 'info_background',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Background', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'column_bg',
            'type'    => 'color',
            'title'   => __( 'Color', 'mfn-opts' ),
            'alpha'   => true,
          ),

          array(
            'id'      => 'bg_image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
          ),

          array(
            'id'      => 'bg_position',
            'type'    => 'select',
            'title'   => __( 'Position', 'mfn-opts' ),
            'desc'    => __( 'This option can be used only with your custom image selected above', 'mfn-opts' ),
            'options' => mfna_bg_position( 'column' ),
            'std'     => 'center top no-repeat',
          ),

          array(
            'id'    => 'bg_size',
            'type'    => 'select',
            'title'   => __('Size', 'mfn-opts'),
            'desc'    => __('Works only in modern browsers', 'mfn-opts'),
            'options'   => mfna_bg_size(),
          ),

          // layout
          array(
            'id'    => 'info_layout',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Layout', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'margin_bottom',
            'type'    => 'select',
            'title'   => __('Margin | Bottom', 'mfn-opts'),
            'desc'    => __('<b>Overrides</b> section settings', 'mfn-opts'),
            'options'   => array(
              ''      => __( '-- Default --', 'mfn-opts' ),
              '0px'   => '0px',
              '10px'    => '10px',
              '20px'    => '20px',
              '30px'    => '30px',
              '40px'    => '40px',
              '50px'    => '50px',
            ),
          ),

          array(
            'id'    => 'padding',
            'type'    => 'text',
            'title'   => __('Padding', 'mfn-opts'),
            'desc'    => __('Use value with <b>px</b> or <b>%</b>. Example: <b>20px</b> or <b>20px 10px 20px 10px</b> or <b>20px 1%</b>', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'text',
            'title'   => __('Custom | Styles', 'mfn-opts'),
            'sub_desc'  => __('Custom inline CSS Styles', 'mfn-opts'),
            'desc'    => __('Example: <b>border: 1px solid #999;</b>', 'mfn-opts'),
          ),

        ),
      ),

      // Contact box ----------------------------------------------------

      'contact_box' => array(
        'type'    => 'contact_box',
        'title'   => __('Contact Box', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'address',
            'type'    => 'textarea',
            'title'   => __('Address', 'mfn-opts'),
            'desc'    => __('HTML tags allowed.', 'mfn-opts'),
          ),

          array(
            'id'    => 'telephone',
            'type'    => 'text',
            'title'   => __('Phone', 'mfn-opts'),
            'desc'    => __('Phone number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'telephone_2',
            'type'    => 'text',
            'title'   => __('Phone 2nd', 'mfn-opts'),
            'desc'    => __('Additional Phone number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'fax',
            'type'    => 'text',
            'title'   => __('Fax', 'mfn-opts'),
            'desc'    => __('Fax number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'email',
            'type'    => 'text',
            'title'   => __('Email', 'mfn-opts'),
          ),

          array(
            'id'    => 'www',
            'type'    => 'text',
            'title'   => __('WWW', 'mfn-opts'),
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Background Image', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Content  -------------------------------------------------------

      'content' => array(
        'type'    => 'content',
        'title'   => __('Content WP', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'typography',
        'fields'   => array(

          array(
            'id'    => 'info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Adding this Item will show Content from WordPress Editor above Page Options. You can use it only once per page. Please also remember to turn on "Hide The Content" option.', 'nhp-opts'),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Countdown  -----------------------------------------------------

      'countdown' => array(
        'type'    => 'countdown',
        'title'   => __( 'Countdown', 'mfn-opts' ),
        'size'    => '1/1',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'date',
            'type'    => 'text',
            'title'   => __( 'Lunch Date', 'mfn-opts' ),
            'desc'    => __( 'Format: 12/30/2018 12:00:00 month/day/year hour:minute:second', 'mfn-opts' ),
            'std'     => '12/30/2018 12:00:00',
            'class'   => 'small-text',
          ),

          array(
            'id'      => 'timezone',
            'type'    => 'select',
            'title'   => __( 'UTC Timezone', 'mfn-opts' ),
            'options' => mfna_utc(),
            'std'     => '0',
          ),

          // options
          array(
            'id'      => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'show',
            'type'    => 'select',
            'title'   => __( 'Show', 'mfn-opts' ),
            'options'   => array(
              ''        => __( 'days hours minutes seconds', 'mfn-opts' ),
              'dhm'     => __( 'days hours minutes', 'mfn-opts' ),
              'dh'      => __( 'days hours', 'mfn-opts' ),
              'd'       => __( 'days', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Counter  -------------------------------------------------------

      'counter' => array(
        'type'    => 'counter',
        'title'   => __('Counter', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          // counter
          array(
            'id'    => 'info_counter',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Counter', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'std'     => 'icon-lamp',
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'color',
            'type'    => 'color',
            'title'   => __('Icon Color', 'mfn-opts'),
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('If you upload an image, icon will not show', 'mfn-opts'),
          ),

          array(
            'id'    => 'prefix',
            'type'    => 'text',
            'title'   => __('Prefix', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'number',
            'type'    => 'text',
            'title'   => __('Number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'label',
            'type'    => 'text',
            'title'   => __('Postfix', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'type',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'desc'    => __( 'Vertical style works only for column widths: 1/4, 1/3 & 1/2', 'mfn-opts' ),
            'options'   => array(
              'horizontal'  => __( 'Horizontal', 'mfn-opts' ),
              'vertical'    => __( 'Vertical', 'mfn-opts' ),
            ),
            'std'   => 'vertical',
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Divider  -------------------------------------------------------

      'divider' => array(
        'type'    => 'divider',
        'title'   => __('Divider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'height',
            'type'    => 'text',
            'title'   => __('Divider height', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              'default' => __( 'Default', 'mfn-opts' ),
              'dots'    => __( 'Dots', 'mfn-opts' ),
              'zigzag'  => __( 'ZigZag', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'line',
            'type'    => 'select',
            'title'   => __('Line', 'mfn-opts'),
            'desc'    => __('This option can be used <strong>only</strong> with Style: Default.', 'mfn-opts'),
            'options'   => array(
              'default' => __( 'Default', 'mfn-opts' ),
              'narrow'  => __( 'Narrow', 'mfn-opts' ),
              'wide'    => __( 'Wide', 'mfn-opts' ),
              ''      => __( 'No Line', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'themecolor',
            'type'    => 'select',
            'title'   => __('Theme Color', 'mfn-opts'),
            'desc'    => __('This option can be used <strong>only</strong> with Style: Default.', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Fancy Divider  -------------------------------------------------

      'fancy_divider' => array(
        'type'    => 'fancy_divider',
        'title'   => __('Fancy Divider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('This item can only be used on pages <strong>Without Sidebar</strong>', 'nhp-opts'),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              'circle up'   => __( 'Circle Up', 'mfn-opts' ),
              'circle down' => __( 'Circle Down', 'mfn-opts' ),
              'curve up'    => __( 'Curve Up', 'mfn-opts' ),
              'curve down'  => __( 'Curve Down', 'mfn-opts' ),
              'stamp'     => __( 'Stamp', 'mfn-opts' ),
              'triangle up' => __( 'Triangle Up', 'mfn-opts' ),
              'triangle down' => __( 'Triangle Down', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'color_top',
            'type'    => 'color',
            'title'   => __('Color Top', 'mfn-opts'),
          ),

          array(
            'id'    => 'color_bottom',
            'type'    => 'color',
            'title'   => __('Color Bottom', 'mfn-opts'),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Fancy Heading --------------------------------------------------

      'fancy_heading' => array(
        'type'    => 'fancy_heading',
        'title'   => __('Fancy Heading', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'h1',
            'type'    => 'select',
            'title'   => __('Use H1 tag', 'mfn-opts'),
            'desc'    => __('Wrap title into H1 instead of H2', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          // style
          array(
            'id'    => 'info_style',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Style', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              'icon'    => __( 'Icon', 'mfn-opts' ),
              'line'    => __( 'Line', 'mfn-opts' ),
              'arrows'  => __( 'Arrows', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'sub_desc'  => __('for <b>Style: Icon</b>', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'slogan',
            'type'    => 'text',
            'title'   => __('Slogan', 'mfn-opts'),
            'sub_desc'  => __('for <b>Style: Line</b>', 'mfn-opts'),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // FAQ  -----------------------------------------------------------

      'faq' => array(
        'type'    => 'faq',
        'title'   => __('FAQ', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'tabs',
            'type'    => 'tabs',
            'title'   => __('FAQ', 'mfn-opts'),
            'sub_desc'  => __('You can use Drag & Drop to set the order', 'mfn-opts'),
            'desc'    => __('<b>JavaScript</b> content like Google Maps and some plugins shortcodes do <b>not work</b> in tabs', 'mfn-opts'),
          ),

          array(
            'id'    => 'open1st',
            'type'    => 'select',
            'title'   => __('Open First', 'mfn-opts'),
            'desc'    => __('Open first tab at start', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'openAll',
            'type'    => 'select',
            'title'   => __('Open All', 'mfn-opts'),
            'desc'    => __('Open all tabs at start', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Feature Box -------------------------------------------------------

      'feature_box' => array(
        'type'    => 'feature_box',
        'title'   => __( 'Feature Box', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>384px - 960px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          array(
            'id'      => 'content',
            'type'    => 'textarea',
            'title'   => __( 'Content', 'mfn-opts' ),
            'validate'=> 'html',
          ),

          array(
            'id'      => 'background',
            'type'    => 'color',
            'title'   => __( 'Background color', 'mfn-opts' ),
          ),

          // link
          array(
            'id'      => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Link', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
            'sub_desc'=> __( 'Image Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Link | Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'      => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'sub_desc'=> __( 'Entrance animation', 'mfn-opts' ),
            'options' => mfn_get_animations(),
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Feature List ---------------------------------------------------

      'feature_list' => array(
        'type'    => 'feature_list',
        'title'   => __('Feature List', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'  => 'title',
            'type'  => 'text',
            'title' => __('Title', 'mfn-opts'),
            'desc'  => __('This field is used as an Item Label in admin panel only', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'  => 'textarea',
            'title' => __('Content', 'mfn-opts'),
            'desc'  => __('Please use <strong>[item icon="" title="" link="" target=""]</strong> shortcodes.', 'mfn-opts'),
            'std'   => '[item icon="icon-lamp" title="" link="" target="" animate=""]',
          ),

          array(
            'id'    => 'columns',
            'type'    => 'select',
            'title'   => __('Columns', 'mfn-opts'),
            'desc'    => __('Default: 4. Recommended: 2-4. Too large value may crash the layout.', 'mfn-opts'),
            'options'  => array(
              2 => 2,
              3 => 3,
              4 => 4,
              5 => 5,
              6 => 6,
            ),
            'std'     => 4,
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Flat Box -------------------------------------------------------

      'flat_box'  => array(
        'type'    => 'flat_box',
        'title'   => __( 'Flat Box', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          array(
            'id'      => 'content',
            'type'    => 'textarea',
            'title'   => __( 'Content', 'mfn-opts' ),
            'desc'    => __( 'Some Shortcodes and HTML tags allowed', 'mfn-opts' ),
            'class'   => 'full-width sc',
            'validate'=> 'html',
          ),

          // icon
          array(
            'id'      => 'info_icon',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Icon', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'icon',
            'type'    => 'icon',
            'title'   => __( 'Icon', 'mfn-opts' ),
            'std'     => 'icon-lamp',
            'class'   => 'small-text',
          ),

          array(
            'id'      => 'icon_image',
            'type'    => 'upload',
            'title'   => __( 'Icon | Image', 'mfn-opts' ),
            'desc'    => __( 'You can use image icon instead of font icon', 'mfn-opts' ),
          ),

          array(
            'id'      => 'background',
            'type'    => 'color',
            'title'   => __( 'Background', 'mfn-opts' ),
          ),

          // link
          array(
            'id'      => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Link', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Link | Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'      => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'sub_desc'=> __( 'Entrance animation', 'mfn-opts' ),
            'options' => mfn_get_animations(),
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Helper -------------------------------------------------------

      'helper' => array(
        'type'    => 'helper',
        'title'   => __('Helper', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'title_tag',
            'type'    => 'select',
            'title'   => __('Title | Tag', 'mfn-opts'),
            'options'   => array(
              'h1' => 'H1',
              'h2' => 'H2',
              'h3' => 'H3',
              'h4' => 'H4',
              'h5' => 'H5',
              'h6' => 'H6',
            ),
            'std'   => 'h4'
          ),

          array(
            'id'    => 'info_item1',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Item 1', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'title1',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content1',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          array(
            'id'    => 'link1',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
            'desc'    => __('Use this field if you want to link to another page instead of showing the content', 'mfn-opts'),
          ),

          array(
            'id'    => 'target1',
            'type'    => 'select',
            'options'   => array( 0 => 'No', 1 => 'Yes' ),
            'title'   => __('Link | Open in new window', 'mfn-opts'),
            'desc'    => __('Adds a target="_blank" attribute to the link', 'mfn-opts'),
          ),

          array(
            'id'    => 'class1',
            'type'    => 'text',
            'title'   => __('Link | Class', 'mfn-opts'),
            'desc'    => __('This option is useful when you want to use <b>prettyphoto</b> or <b>scroll</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'info_item2',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Item 2', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'title2',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content2',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          array(
            'id'    => 'link2',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
            'desc'    => __('Use this field if you want to link to another page instead of showing the content', 'mfn-opts'),
          ),

          array(
            'id'    => 'target2',
            'type'    => 'select',
            'title'   => __('Link | Open in new window', 'mfn-opts'),
            'desc'    => __('Adds a target="_blank" attribute to the link', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'class2',
            'type'    => 'text',
            'title'   => __('Link | Class', 'mfn-opts'),
            'desc'    => __('This option is useful when you want to use <b>prettyphoto</b> or <b>scroll</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Hover Box ------------------------------------------------------

      'hover_box' => array(
        'type'    => 'hover_box',
        'title'   => __('Hover Box', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'image_hover',
            'type'    => 'upload',
            'title'   => __('Image | Hover', 'mfn-opts'),
            'desc'    => __('Both images <b>must have the same size</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Hover Color ----------------------------------------------------

      'hover_color' => array(
        'type'    => 'hover_color',
        'title'   => __( 'Hover Color', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __( 'Content', 'mfn-opts' ),
            'desc'    => __( 'Some Shortcodes and HTML tags allowed', 'mfn-opts' ),
            'class'   => 'full-width sc',
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __( 'Text Align', 'mfn-opts' ),
            'options'   => array(
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
              ''      => __( 'Center', 'mfn-opts' ),
              'justify' => __( 'Justify', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'padding',
            'type'    => 'text',
            'title'   => __('Padding', 'mfn-opts'),
            'sub_desc'  => __('default: 40px 30px', 'mfn-opts'),
            'desc'    => __('Use value with <b>px</b> or <b>%</b>. Example: <b>20px</b> or <b>20px 10px 20px 10px</b> or <b>20px 1%</b>', 'mfn-opts'),
            'class'   => 'small-text',
            'std'     => '40px 30px',
          ),

          // background
          array(
            'id'      => 'info_background',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Background', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'background',
            'type'    => 'color',
            'title'   => __( 'Color', 'mfn-opts' ),
            // 'alpha'    => true, // requires change to jquery because of background div
          ),

          array(
            'id'    => 'background_hover',
            'type'    => 'color',
            'title'   => __( 'Hover color', 'mfn-opts' ),
            // 'alpha'    => true,
          ),

          // border
          array(
            'id'      => 'info_border',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Border', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'border',
            'type'    => 'color',
            'title'   => __( 'Color', 'mfn-opts' ),
            'sub_desc'=> __( 'optional', 'mfn-opts' ),
            // 'alpha'    => true,
          ),

          array(
            'id'      => 'border_hover',
            'type'    => 'color',
            'title'   => __( 'Hover color', 'mfn-opts' ),
            'sub_desc'=> __( 'optional', 'mfn-opts' ),
            // 'alpha'    => true,
          ),

          array(
            'id'      => 'border_width',
            'type'    => 'text',
            'title'   => __( 'Width', 'mfn-opts' ),
            'sub_desc'=> __( 'default: 2px', 'mfn-opts' ),
            'desc'    => __( 'Use value with <b>px</b>. Example: <b>1px</b> or <b>2px 5px 2px 5px</b>', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => '2px',
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'class',
            'type'    => 'text',
            'title'   => __( 'Class', 'mfn-opts' ),
            'desc'    => __( 'This option is useful when you want to use <b>scroll</b>', 'mfn-opts' ),
          ),

          // custom
          array(
            'id'      => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'text',
            'title'   => __('Custom | Styles', 'mfn-opts'),
            'sub_desc'  => __('Custom inline CSS Styles', 'mfn-opts'),
            'desc'    => __('Example: <b>opacity: 0.5;</b>', 'mfn-opts'),
          ),


        ),
      ),

      // How It Works ---------------------------------------------------

      'how_it_works' => array(
        'type'    => 'how_it_works',
        'title'   => __('How It Works', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Background Image', 'mfn-opts'),
            'desc'    => __('Recommended: Square Image with transparent background.', 'mfn-opts'),
          ),

          array(
            'id'    => 'number',
            'type'    => 'text',
            'title'   => __('Number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          // style
          array(
            'id'    => 'info_style',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Style', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'border',
            'type'    => 'select',
            'title'   => __('Line', 'mfn-opts'),
            'sub_desc'  => __('Show right connecting line', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'sub_desc'  => __('Background Image style', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Small centered image (image size: max 116px)', 'mfn-opts' ),
              'fill'    => __( 'Fill the circle (image size: 200px x 200px)', 'mfn-opts' ),
            ),
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Icon Box  ------------------------------------------------------

      'icon_box' => array(
        'type'    => 'icon_box',
        'title'   => __('Icon Box', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'title_tag',
            'type'    => 'select',
            'title'   => __('Title | Tag', 'mfn-opts'),
            'options'   => array(
              'h1' => 'H1',
              'h2' => 'H2',
              'h3' => 'H3',
              'h4' => 'H4',
              'h5' => 'H5',
              'h6' => 'H6',
            ),
            'std'   => 'h4'
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
          ),

          // icon
          array(
            'id'    => 'info_icon',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Icon', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'std'     => 'icon-lamp',
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'icon_position',
            'type'    => 'select',
            'title'   => __('Icon Position', 'mfn-opts'),
            'desc'    => __('Left position works only for column widths: 1/4, 1/3 & 1/2', 'mfn-opts'),
            'options' => array(
              'left'  => __( 'Left', 'mfn-opts' ),
              'top' => __( 'Top', 'mfn-opts' ),
            ),
            'std'   => 'top',
          ),

          array(
            'id'    => 'border',
            'type'    => 'select',
            'title'   => __('Border', 'mfn-opts'),
            'sub_desc'  => __('Show right border', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'class',
            'type'    => 'text',
            'title'   => __('Link | Class', 'mfn-opts'),
            'desc'    => __('This option is useful when you want to use <b>scroll</b>', 'mfn-opts'),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Image  ---------------------------------------------------------

      'image' => array(
        'type'    => 'image',
        'title'   => __('Image', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'typography',
        'fields'  => array(

          array(
            'id'    => 'src',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'size',
            'type'    => 'select',
            'title'   => __('Image | Size', 'mfn-opts'),
            'desc'    => __('Select image size from <a target="_blank" href="options-media.php">Settings > Media > Image sizes</a> (Media Library images only)<br />or use below fields for HTML resize', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Full size', 'mfn-opts' ),
              'large'   => __( 'Large', 'mfn-opts' ) .' | '. mfn_get_image_sizes( 'large', 1 ),
              'medium'  => __( 'Medium', 'mfn-opts' ) .' | '. mfn_get_image_sizes( 'medium', 1 ),
              'thumbnail' => __( 'Thumbnail', 'mfn-opts' ) .' | '. mfn_get_image_sizes( 'thumbnail', 1 ),
            ),
          ),

          array(
            'id'    => 'width',
            'type'    => 'text',
            'title'   => __('Image | Width', 'mfn-opts'),
            'sub_desc'  => __('HTML resize | optional', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'height',
            'type'    => 'text',
            'title'   => __('Image | Height', 'mfn-opts'),
            'sub_desc'  => __('HTML resize | optional', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __('Align', 'mfn-opts'),
            'desc'    => __('If you want image to be <b>resized</b> to column width use <b>align none</b>', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'None', 'mfn-opts' ),
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
              'center'  => __( 'Center', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'stretch',
            'type'    => 'select',
            'title'   => __('Stretch', 'mfn-opts'),
            'sub_desc'  => __('Stretch image to column width', 'mfn-opts'),
            'desc'    => __('The height of the image will be changed proportionally', 'mfn-opts'),
            'options'   => array(
              '0'     => __( 'No', 'mfn-opts' ),
              '1'     => __( 'Yes', 'mfn-opts' ),
              'ultrawide' => __( 'Yes, on ultrawide screens only > 1920px', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'border',
            'type'    => 'select',
            'title'   => __('Border', 'mfn-opts'),
            'sub_desc'  => __('Show Image Border', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'margin',
            'type'    => 'text',
            'title'   => __('Margin | Top', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'margin_bottom',
            'type'    => 'text',
            'title'   => __('Margin | Bottom', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link_image',
            'type'    => 'upload',
            'title'   => __('Zoomed image', 'mfn-opts'),
            'desc'    => __('This <b>image or embed video</b> will be opened in lightbox.', 'mfn-opts'),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Open in new window', 'mfn-opts'),
            'desc'    => __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'hover',
            'type'    => 'select',
            'title'   => __('Hover Effect', 'mfn-opts'),
            'options'   => array(
              ''      => __('- Default -', 'mfn-opts'),
              'disable'   => __('Disable', 'mfn-opts'),
            ),
          ),

          // description
          array(
            'id'    => 'info_description',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Description', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'alt',
            'type'    => 'text',
            'title'   => __('Alternate Text', 'mfn-opts'),
          ),

          array(
            'id'    => 'caption',
            'type'    => 'text',
            'title'   => __('Caption', 'mfn-opts'),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => 'Greyscale Images',
            'desc'    => 'Works only for images with link',
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Image Gallery  ---------------------------------------------------------

      'image_gallery' => array(
        'type'    => 'image_gallery',
        'title'   => __( 'Image Gallery', 'mfn-opts' ),
        'size'    => '1/1',
        'cat'     => 'typography',
        'fields'  => array(

          array(
            'id'    => 'ids',
            'type'    => 'upload_multi',
            'title'   => __( 'Image Gallery', 'mfn-opts' ),
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'columns',
            'type'    => 'text',
            'title'   => __( 'Columns', 'mfn-opts' ),
            'desc'    => __( 'min: <b>1</b>, max: <b>9</b>', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => '3',
          ),

          array(
            'id'    => 'size',
            'type'    => 'select',
            'title'   => __( 'Size' , 'mfn-opts' ),
            'options'   => array(
              'thumbnail' => __( 'Thumbnail', 'mfn-opts' ),
              'medium'  => __( 'Medium', 'mfn-opts' ),
              'large'   => __( 'Large', 'mfn-opts' ),
              'full'    => __( 'Full Size', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style' , 'mfn-opts' ),
            'options'   => array(
              ''      => __( 'Default', 'mfn-opts' ),
              'flat'    => __( 'Flat', 'mfn-opts' ),
              'fancy'   => __( 'Fancy', 'mfn-opts' ),
              'masonry'   => __( 'Masonry', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => __( 'Greyscale Images', 'mfn-opts' ),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'  => __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Info box -------------------------------------------------------

      'info_box' => array(
        'type'    => 'info_box',
        'title'   => __('Info Box', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('HTML tags allowed.', 'mfn-opts'),
            'std'     => '<ul><li>list item 1</li><li>list item 2</li></ul>',
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Background Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // List -----------------------------------------------------------

      'list' => array(
        'type'    => 'list',
        'title'   => __('List', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Icon', 'mfn-opts'),
            'std'     => 'icon-lamp',
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Open in new window', 'mfn-opts'),
            'desc'    => __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'desc'    => __('Only <strong>Vertical Style</strong> works for column widths 1/5 & 1/6', 'mfn-opts'),
            'options'   => array(
              1 => __( 'With background', 'mfn-opts' ),
              2 => __( 'Transparent', 'mfn-opts' ),
              3 => __( 'Vertical', 'mfn-opts' ),
              4 => __( 'Ordered list', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Map Basic ------------------------------------------------------------

      'map_basic' => array(
        'type'    => 'map_basic',
        'title'   => __( 'Map Basic', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          // iframe
          array(
            'id'      => 'info_iframe',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Iframe', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'info_iframe_info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Number of iframe map loads is unlimited.', 'mfn-opts' ),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'      => 'iframe',
            'type'    => 'textarea',
            'title'   => __( 'Iframe', 'mfn-opts' ),
            'sub_desc'=> __( 'Leave this filed blank if you use Embed Map', 'mfn-opts' ),
            'desc'    => __( 'Visit <a target="_blank" href="https://google.com/maps">Google Maps</a> and follow these instructions:<br />1. Find place. 2. Click the share button in the left panel. 3. Select "embed a map" 4. Choose size. 5. Click "copy HTML" and paste it above', 'mfn-opts' ),
            'class'   => 'small-text',
          ),

          // embed
          array(
            'id'      => 'info_embed',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Embed', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'info_embed_info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Number of embed map loads is unlimited. Google Maps API key is required.<span>Please go to <a target="_blank" href="admin.php?page=be-options">Theme Options</a><strong> > Global > Advanced</strong> and paste your API key in the <strong>Google Maps API Key</strong> field.</span>', 'mfn-opts' ),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'      => 'address',
            'type'    => 'text',
            'title'   => __( 'Address or place name', 'mfn-opts' ),
          ),

          array(
            'id'      => 'zoom',
            'type'    => 'text',
            'title'   => __( 'Zoom', 'mfn-opts' ),
            'sub_desc'=> __( 'default: 13', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => 13,
          ),

          array(
            'id'      => 'height',
            'type'    => 'text',
            'title'   => __( 'Height', 'mfn-opts' ),
            'sub_desc'=> __( 'default: 300', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => 300,
          ),

        ),
      ),

      // Map Advanced ------------------------------------------------------------

      'map' => array(
        'type'    => 'map',
        'title'   => __( 'Map Advanced', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'      => 'info_advanced_info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Number of free dynamic map loads is limited. Google Maps API key is required.<span>Please go to <a target="_blank" href="admin.php?page=be-options">Theme Options</a><strong> > Global > Advanced</strong> and paste your API key in the <strong>Google Maps API Key</strong> field.<br />If you need more than 28500 map loads per month please check current Google Maps <a target="_blank" href="https://cloud.google.com/maps-platform/pricing/">Pricing & Plans</a> or choose Map Basic instead.</span>', 'mfn-opts' ),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'      => 'lat',
            'type'    => 'text',
            'title'   => __( 'Google Maps Lat', 'mfn-opts' ),
            'desc'    => __( 'The map will appear only if this field is filled correctly.<br />Example: <b>-33.87</b>', 'mfn-opts' ),
            'class'   => 'small-text',
          ),

          array(
            'id'      => 'lng',
            'type'    => 'text',
            'title'   => __( 'Google Maps Lng', 'mfn-opts' ),
            'desc'    => __( 'The map will appear only if this field is filled correctly.<br />Example: <b>151.21</b>', 'mfn-opts' ),
            'class'   => 'small-text',
          ),

          array(
            'id'      => 'zoom',
            'type'    => 'text',
            'title'   => __( 'Zoom', 'mfn-opts' ),
            'sub_desc'=> __( 'default: 13', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => 13,
          ),

          array(
            'id'      => 'height',
            'type'    => 'text',
            'title'   => __( 'Height', 'mfn-opts' ),
            'sub_desc'=> __( 'default: 300', 'mfn-opts' ),
            'class'   => 'small-text',
            'std'     => 300,
          ),

          // options
          array(
            'id'      => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Options', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'type',
            'type'    => 'select',
            'title'   => __( 'Type', 'mfn-opts' ),
            'options' => array(
              'ROADMAP'   => __( 'Map', 'mfn-opts' ),
              'SATELLITE' => __( 'Satellite', 'mfn-opts' ),
              'HYBRID'    => __( 'Satellite + Map', 'mfn-opts' ),
              'TERRAIN'   => __( 'Terrain', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'controls',
            'type'    => 'select',
            'title'   => __( 'Controls', 'mfn-opts' ),
            'options' => array(
              '' => __( 'Zoom', 'mfn-opts' ),
              'mapType' => __( 'Map Type', 'mfn-opts' ),
              'streetView'  => __( 'Street View', 'mfn-opts' ),
              'zoom mapType' => __( 'Zoom & Map Type', 'mfn-opts' ),
              'zoom streetView' => __( 'Zoom & Street View', 'mfn-opts' ),
              'mapType streetView' => __( 'Map Type & Street View', 'mfn-opts' ),
              'zoom mapType streetView' => __( 'Zoom, Map Type & Street View', 'mfn-opts' ),
              'hide' => __( 'Hide All', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'draggable',
            'type'    => 'select',
            'title'   => __( 'Draggable', 'mfn-opts' ),
            'options' => array(
              '' => __( 'Enable', 'mfn-opts' ),
              'disable' => __( 'Disable', 'mfn-opts' ),
              'disable-mobile'=> __( 'Disable on Mobile', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'border',
            'type'    => 'select',
            'title'   => __( 'Border', 'mfn-opts' ),
            'sub_desc'=> __( 'Show map border', 'mfn-opts' ),
            'options' => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'      => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'icon',
            'type'    => 'upload',
            'title'   => __( 'Marker Icon', 'mfn-opts' ),
            'desc'    => __( '.png', 'mfn-opts' ),
          ),

          array(
            'id'      => 'color',
            'type'    => 'color',
            'title'   => __( 'Map color', 'mfn-opts' ),
          ),

          array(
            'id'      => 'styles',
            'type'    => 'textarea',
            'title'   => __( 'Styles', 'mfn-opts' ),
            'sub_desc'=> __( 'Google Maps API styles array', 'mfn-opts' ),
            'desc'    => __( 'You can get predefined styles from <a target="_blank" href="https://snazzymaps.com/explore">snazzymaps.com/explore</a> or generate your own <a target="_blank" href="https://snazzymaps.com/editor">snazzymaps.com/editor</a>', 'mfn-opts' ),
          ),

          array(
            'id'      => 'latlng',
            'type'    => 'textarea',
            'title'   => __( 'Additional Markers | Lat,Lng,IconURL', 'mfn-opts' ),
            'desc'    => __( 'Separate Lat,Lng,IconURL[optional] with <b>coma</b> [ , ]<br />Separate multiple Markers with <b>semicolon</b> [ ; ]<br />Example: <b>-33.88,151.21,ICON_URL;-33.89,151.22</b>', 'mfn-opts' ),
          ),

          // contact
          array(
            'id'      => 'info_contact',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Contact Box', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'class'   => 'small-text',
          ),

          array(
            'id'      => 'content',
            'type'    => 'textarea',
            'title'   => __( 'Address', 'mfn-opts' ),
            'desc'    => __( 'HTML tags allowed.', 'mfn-opts' ),
          ),

          array(
            'id'      => 'telephone',
            'type'    => 'text',
            'title'   => __( 'Telephone', 'mfn-opts' ),
          ),

          array(
            'id'    => 'email',
            'type'    => 'text',
            'title'   => __( 'Email', 'mfn-opts' ),
          ),

          array(
            'id'      => 'www',
            'type'    => 'text',
            'title'   => __( 'WWW', 'mfn-opts' ),
          ),

          array(
            'id'      => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'options' => array(
              'box'   => __( 'Contact Box on the map (for full width column/wrap)', 'mfn-opts' ),
              'bar'   => __( 'Bar at the top', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'      => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Offer Slider Full ----------------------------------------------

      'offer' => array(
        'type'    => 'offer',
        'title'   => __('Offer Slider Full', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'info',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('This item can only be used on pages <strong>Without Sidebar</strong>.<br />Please also set Section Style to <strong>Full Width</strong> and use one Item in one Section.', 'nhp-opts'),
            'class'   => 'mfn-info info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'offer-types' ),
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __( 'Text Align', 'mfn-opts' ),
            'desc'    => __( 'Text align center does not affect title if button is active', 'mfn-opts' ),
            'options'   => array(
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
              'center'  => __( 'Center', 'mfn-opts' ),
              'justify' => __( 'Justify', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Offer Slider Thumb ---------------------------------------------

      'offer_thumb' => array(
        'type'    => 'offer_thumb',
        'title'   => __('Offer Slider Thumb', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __( 'Category', 'mfn-opts' ),
            'options' => mfn_get_categories( 'offer-types' ),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'options' => array(
              'bottom'  => __( 'Thumbnails at the bottom', 'mfn-opts' ),
              ''      => __( 'Thumbnails on the left', 'mfn-opts' ),
            ),
            'std'   => 'bottom',
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __( 'Text Align', 'mfn-opts' ),
            'desc'    => __( 'Text align center does not affect title if button is active', 'mfn-opts' ),
            'options'   => array(
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
              'center'  => __( 'Center', 'mfn-opts' ),
              'justify' => __( 'Justify', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Opening Hours --------------------------------------------------

      'opening_hours' => array(
        'type'    => 'opening_hours',
        'title'   => __('Opening Hours', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('HTML tags allowed.', 'mfn-opts'),
            'std'     => '<ul><li><label>Monday - Saturday</label><span>8am - 4pm</span></li></ul>',
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Background Image', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Our team -------------------------------------------------------

      'our_team' => array(
        'type'    => 'our_team',
        'title'   => __('Our Team', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'heading',
            'type'    => 'text',
            'title'   => __('Heading', 'mfn-opts'),
          ),

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Photo', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'subtitle',
            'type'    => 'text',
            'title'   => __('Subtitle', 'mfn-opts'),
          ),

          // description
          array(
            'id'    => 'info_description',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Description', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'phone',
            'type'    => 'text',
            'title'   => __('Phone', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
          ),

          array(
            'id'    => 'email',
            'type'    => 'text',
            'title'   => __('E-mail', 'mfn-opts'),
          ),

          // social
          array(
            'id'    => 'info_social',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Social', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'facebook',
            'type'    => 'text',
            'title'   => __('Facebook', 'mfn-opts'),
          ),

          array(
            'id'    => 'twitter',
            'type'    => 'text',
            'title'   => __('Twitter', 'mfn-opts'),
          ),

          array(
            'id'    => 'linkedin',
            'type'    => 'text',
            'title'   => __('LinkedIn', 'mfn-opts'),
          ),

          array(
            'id'    => 'vcard',
            'type'    => 'text',
            'title'   => __('vCard', 'mfn-opts'),
          ),

          // other
          array(
            'id'    => 'info_other',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Other', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'blockquote',
            'type'    => 'textarea',
            'title'   => __('Blockquote', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'options' => array(
              'circle'    => __( 'Circle', 'mfn-opts' ),
              'vertical'    => __( 'Vertical', 'mfn-opts' ),
              'horizontal'  => __( 'Horizontal [only: 1/2]', 'mfn-opts' ),
            ),
            'std'   => 'vertical',
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Our team list --------------------------------------------------

      'our_team_list' => array(
        'type'    => 'our_team_list',
        'title'   => __('Our Team List', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Photo', 'mfn-opts'),
            'desc'    => __('Recommended minimum image width: <b>768px</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'subtitle',
            'type'    => 'text',
            'title'   => __('Subtitle', 'mfn-opts'),
          ),

          // description
          array(
            'id'    => 'info_description',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Description', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'phone',
            'type'    => 'text',
            'title'   => __('Phone', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
          ),

          array(
            'id'    => 'blockquote',
            'type'    => 'textarea',
            'title'   => __('Blockquote', 'mfn-opts'),
          ),

          array(
            'id'    => 'email',
            'type'    => 'text',
            'title'   => __('E-mail', 'mfn-opts'),
          ),

          // social
          array(
            'id'    => 'info_social',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Social', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'facebook',
            'type'    => 'text',
            'title'   => __('Facebook', 'mfn-opts'),
          ),

          array(
            'id'    => 'twitter',
            'type'    => 'text',
            'title'   => __('Twitter', 'mfn-opts'),
          ),

          array(
            'id'    => 'linkedin',
            'type'    => 'text',
            'title'   => __('LinkedIn', 'mfn-opts'),
          ),

          array(
            'id'    => 'vcard',
            'type'    => 'text',
            'title'   => __('vCard', 'mfn-opts'),
          ),

          // link
          array(
            'id'    => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Link', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Photo Box ------------------------------------------------------

      'photo_box' => array(
        'type'    => 'photo_box',
        'title'   => __( 'Photo Box', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          array(
            'id'      => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'      => 'content',
            'type'    => 'textarea',
            'title'   => __( 'Content', 'mfn-opts' ),
            'desc'    => __( 'Some Shortcodes and HTML tags allowed', 'mfn-opts' ),
            'class'   => 'full-width sc',
          ),

          array(
            'id'      => 'align',
            'type'    => 'select',
            'title'   => __( 'Text Align', 'mfn-opts' ),
            'options' => array(
              ''        => __( 'Center', 'mfn-opts' ),
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
            ),
          ),

          // link
          array(
            'id'      => 'info_link',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Link', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Link | Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'      => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'greyscale',
            'type'    => 'select',
            'title'   => __( 'Greyscale Images', 'mfn-opts' ),
            'desc'    => __( 'Works only for images with link', 'mfn-opts' ),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'sub_desc'=> __( 'Entrance animation', 'mfn-opts' ),
            'options' => mfn_get_animations(),
          ),

          // custom
          array(
            'id'      => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Portfolio ------------------------------------------------------

      'portfolio' => array(
        'type'    => 'portfolio',
        'title'   => __('Portfolio', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'class'   => 'small-text',
            'std'   => 3,
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'desc'    => __('If you do not know what <b>image size</b> is being used for selected style, please navigate to the: Appearance > <a target="_blank" href="admin.php?page=be-options">Theme Options</a> > Blog, Portfolio & Shop > <b>Featured Images</b>', 'mfn-opts'),
            'options'   => array(
              'flat'        => __( 'Flat', 'mfn-opts' ),
              'grid'        => __( 'Grid', 'mfn-opts' ),
              'masonry'     => __( 'Masonry Blog Style', 'mfn-opts' ),
              'masonry-hover'   => __( 'Masonry Hover Description', 'mfn-opts' ),
              'masonry-minimal' => __( 'Masonry Minimal', 'mfn-opts' ),
              'masonry-flat'    => __( 'Masonry Flat', 'mfn-opts' ),
              'list'        => __( 'List', 'mfn-opts' ),
              'exposure'      => __( 'Exposure', 'mfn-opts' ),
            ),
            'std'     => 'grid'
          ),

          array(
            'id'    => 'columns',
            'type'    => 'select',
            'title'   => __('Columns', 'mfn-opts'),
            'desc'    => __('Default: 3. Recommended: 2-4. Too large value may crash the layout.<br />This option works in styles: <b>Flat, Grid, Masonry Blog Style, Masonry Hover Details</b>', 'mfn-opts'),
            'options'  => array(
              2 => 2,
              3 => 3,
              4 => 4,
              5 => 5,
              6 => 6,
            ),
            'std'     => 3,
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'portfolio-types' ),
            'wpml'    => 'portfolio-types',
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __('Multiple Categories', 'mfn-opts'),
            'sub_desc'  => __('Categories <b>slugs</b>', 'mfn-opts'),
            'desc'    => __('Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'desc'    => __( 'Do not use random order with pagination or load more', 'mfn-opts' ),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'exclude_id',
            'type'    => 'text',
            'title'   => __('Exclude Posts', 'mfn-opts'),
            'sub_desc'  => __('Posts <b>IDs</b>', 'mfn-opts'),
            'desc'    => __('IDs should be separated with <b>coma</b> ( , )', 'mfn-opts'),
          ),

          array(
            'id'    => 'related',
            'type'    => 'select',
            'title'   => __('Use as Related Projects', 'mfn-opts'),
            'sub_desc'  => __('use on Single Project page', 'mfn-opts'),
            'desc'    => __('Exclude current Project. This option will override Exclude Posts option above', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'filters',
            'type'    => 'select',
            'title'   => __('Filters', 'mfn-opts'),
            'desc'    => __('Works only with <b>Category: All</b> or Multiple Categories (only selected categories show in filters)', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'pagination',
            'type'    => 'select',
            'title'   => __('Pagination', 'mfn-opts'),
            'desc'    => __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'load_more',
            'type'    => 'select',
            'title'   => __('Load More button', 'mfn-opts'),
            'desc'    => __('This will replace all sliders on list with featured images. Please also <b>show Pagination</b>', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => 'Greyscale Images',
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Portfolio Grid -------------------------------------------------

      'portfolio_grid' => array(
        'type'    => 'portfolio_grid',
        'title'   => __('Portfolio Grid', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'std'   => '4',
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'portfolio-types' ),
            'wpml'    => 'portfolio-types',
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __('Multiple Categories', 'mfn-opts'),
            'sub_desc'  => __('Categories Slugs', 'mfn-opts'),
            'desc'    => __('Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => 'Greyscale Images',
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Portfolio Photo ------------------------------------------------

      'portfolio_photo' => array(
        'type'    => 'portfolio_photo',
        'title'   => __('Portfolio Photo', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'std'   => '5',
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'portfolio-types' ),
            'wpml'    => 'portfolio-types',
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __('Multiple Categories', 'mfn-opts'),
            'sub_desc'  => __('Categories <b>slugs</b>', 'mfn-opts'),
            'desc'    => __('Slugs should be separated with <b>coma</b> ( , )', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Open in new window', 'mfn-opts'),
            'desc'    => __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'greyscale',
            'type'    => 'select',
            'title'   => 'Greyscale Images',
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'margin',
            'type'    => 'select',
            'title'   => __('Margin', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Portfolio Slider -----------------------------------------------

      'portfolio_slider' => array(
        'type'    => 'portfolio_slider',
        'title'   => __('Portfolio Slider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'desc'    => __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
            'std'   => '6',
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'portfolio-types' ),
            'sub_desc'  => __('Select the portfolio post category.', 'mfn-opts'),
            'wpml'    => 'portfolio-types',
          ),

          array(
            'id'    => 'category_multi',
            'type'    => 'text',
            'title'   => __('Multiple Categories', 'mfn-opts'),
            'sub_desc'  => __('Categories Slugs', 'mfn-opts'),
            'desc'    => __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
              'rand'      => __( 'Random', 'mfn-opts' ),
            ),
            'std'   => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'   => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'arrows',
            'type'    => 'select',
            'title'   => __('Navigation', 'mfn-opts'),
            'sub_desc'  => __('Navigation arrows', 'mfn-opts'),
            'options' => array(
              ''      => __( 'None', 'mfn-opts' ),
              'hover'   => __( 'Show on Hover', 'mfn-opts' ),
              'always'  => __( 'Always Show', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'size',
            'type'    => 'select',
            'title'   => __('Size', 'mfn-opts'),
            'sub_desc'  => __('Image size', 'mfn-opts'),
            'options' => array(
              'small'   => __( 'Small', 'mfn-opts' ),
              'medium'  => __( 'Medium', 'mfn-opts' ),
              'large'   => __( 'Large', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'scroll',
            'type'    => 'select',
            'title'   => __('Slides to scroll', 'mfn-opts'),
            'options' => array(
              'page'    => __( 'One Page', 'mfn-opts' ),
              'slide'   => __( 'Single Slide', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Pricing item ---------------------------------------------------

      'pricing_item' => array(
        'type'    => 'pricing_item',
        'title'   => __('Pricing Item', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
            'sub_desc'  => __('Pricing item title', 'mfn-opts'),
          ),

          array(
            'id'    => 'price',
            'type'    => 'text',
            'title'   => __('Price', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'currency',
            'type'    => 'text',
            'title'   => __('Currency', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'currency_pos',
            'type'    => 'select',
            'title'   => __('Currency | Position', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'period',
            'type'    => 'text',
            'title'   => __('Period', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // description
          array(
            'id'    => 'info_description',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Description', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'subtitle',
            'type'    => 'text',
            'title'   => __('Subtitle', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('HTML tags allowed.', 'mfn-opts'),
            'std'     => '<ul><li><strong>List</strong> item</li></ul>',
          ),

          // button
          array(
            'id'    => 'info_button',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Button', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'link_title',
            'type'    => 'text',
            'title'   => __('Button | Title', 'mfn-opts'),
            'desc'    => __('Button will appear only if this field will be filled.', 'mfn-opts'),
          ),

          array(
            'id'    => 'icon',
            'type'    => 'icon',
            'title'   => __('Button | Icon', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Button | Link', 'mfn-opts'),
            'desc'    => __('Button will appear only if this field will be filled.', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Button | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'featured',
            'type'    => 'select',
            'title'   => __('Featured', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'options'   => array(
              'box' => __( 'Box', 'mfn-opts' ),
              'label' => __( 'Table Label', 'mfn-opts' ),
              'table' => __( 'Table', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Progress Bars  -------------------------------------------------

      'progress_bars' => array(
        'type'    => 'progress_bars',
        'title'   => __('Progress Bars', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'      => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Please use <strong>[bar title="Title" value="50" size="20"]</strong> shortcodes here.', 'mfn-opts'),
            'std'     => '[bar title="Bar1" value="50" size="20"]'."\n".'[bar title="Bar2" value="60" size="20"]',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Promo Box ------------------------------------------------------

      'promo_box' => array(
        'type'    => 'promo_box',
        'title'   => __('Promo Box', 'mfn-opts'),
        'size'    => '1/2',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('Recommended minimum image width: <b>768px</b>', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
          ),

          // button
          array(
            'id'    => 'info_button',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Button', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'btn_text',
            'type'    => 'text',
            'title'   => __('Button | Text', 'mfn-opts'),
            'class'   => 'small-text',
          ),
          array(
            'id'    => 'btn_link',
            'type'    => 'text',
            'title'   => __('Button | Link', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Button | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'position',
            'type'    => 'select',
            'title'   => __('Image position', 'mfn-opts'),
            'options'   => array(
              'left'  => __( 'Left', 'mfn-opts' ),
              'right' => __( 'Right', 'mfn-opts' ),
            ),
            'std'   => 'left',
          ),

          array(
            'id'    => 'border',
            'type'    => 'select',
            'title'   => __('Border', 'mfn-opts'),
            'sub_desc'  => __('Show right border', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Quick Fact -----------------------------------------------------

      'quick_fact' => array(
        'type'    => 'quick_fact',
        'title'   => __('Quick Fact', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'heading',
            'type'    => 'text',
            'title'   => __('Heading', 'mfn-opts'),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
        ),

        array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          // quick fact
          array(
            'id'    => 'info_quick',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Quick Fact', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'number',
            'type'    => 'text',
            'title'   => __('Number', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'prefix',
            'type'    => 'text',
            'title'   => __('Prefix', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'label',
            'type'    => 'text',
            'title'   => __('Postfix', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'align',
            'type'    => 'select',
            'title'   => __('Align', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Center', 'mfn-opts' ),
              'left'    => __( 'Left', 'mfn-opts' ),
              'right'   => __( 'Right', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Shop Slider ----------------------------------------------------

      'shop_slider' => array(
        'type'    => 'shop_slider',
        'title'   => __('Shop Slider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'count',
            'type'    => 'text',
            'title'   => __('Count', 'mfn-opts'),
            'sub_desc'  => __('Number of posts to show', 'mfn-opts'),
            'desc'    => __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
            'std'     => '5',
            'class'   => 'small-text',
          ),

          array(
            'id'    => 'show',
            'type'    => 'select',
            'title'   => __('Show', 'mfn-opts'),
            'options' => array(
              ''        => __( 'All (or category selected below)', 'mfn-opts' ),
              'featured'    => __( 'Featured', 'mfn-opts' ),
              'onsale'    => __( 'Onsale', 'mfn-opts' ),
              'best-selling'  => __( 'Best Selling (order by: Sales)', 'mfn-opts' ),
              'pre-orderable' => __( 'Pre-orderables', 'mfn-opts' ),
              'new-releases'  => __( 'New Releases', 'mfn-opts' ),
              'best-selling-large'  => __( 'Best Selling (Carousel Large)', 'mfn-opts' ),
              'pre-orderable-large' => __( 'Pre-orderables (Carousel Large)', 'mfn-opts' ),
              'new-releases-large'  => __( 'New Releases (Carousel Large)', 'mfn-opts' ),
              'best-selling-grid' => __( 'Best Selling Grid (order by: Sales)', 'mfn-opts' ),
              'pre-orderable-grid'=> __( 'Pre-orderables Grid', 'mfn-opts' ),
              'new-releases-grid' => __( 'New Releases Grid', 'mfn-opts' ),
              'best-selling-grid-fc' => __( 'Best Selling Grid (Full Cover)', 'mfn-opts' ),
              'pre-orderable-grid-fc'=> __( 'Pre-orderables Grid (Full Cover)', 'mfn-opts' ),
              'new-releases-grid-fc' => __( 'New Releases Grid (Full Cover)', 'mfn-opts' ),
              'best-selling-grid-dx' => __( 'Best Selling Grid (Deluxe)', 'mfn-opts' ),
              'pre-orderable-grid-dx'=> __( 'Pre-orderables Grid (Deluxe)', 'mfn-opts' ),
              'new-releases-grid-dx' => __( 'New Releases Grid (Deluxe)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'product_cat' ),
            'sub_desc'  => __('Select the products category', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
            ),
            'std'     => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'     => 'DESC'
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Sidebar Widget -------------------------------------------------

      'sidebar_widget' => array(
        'type'    => 'sidebar_widget',
        'title'   => __('Sidebar Widget', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'sidebar',
            'type'    => 'select',
            'title'   => __('Select Sidebar', 'mfn-opts'),
            'desc'    => __('1. Create Sidebar in Theme Options > Getting Started > Sidebars.<br />2. Add Widget.<br />3. Select your sidebar.', 'mfn-opts'),
            'options'   => mfn_opts_get( 'sidebars' ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Slider ---------------------------------------------------------

      'slider' => array(
        'type'    => 'slider',
        'title'   => __('Slider', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'slide-types' ),
            'sub_desc'  => __('Select the slides category', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
            ),
            'std'     => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'     => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'options'   => array(
              ''        => __( 'Default', 'mfn-opts' ),
              'flat'      => __( 'Flat', 'mfn-opts' ),
              'description' => __( 'Flat with title and description', 'mfn-opts' ),
              'carousel'    => __( 'Flat carousel with titles', 'mfn-opts' ),
              'center'    => __( 'Center mode', 'mfn-opts' ),
            ),
            'title'   => __('Style', 'mfn-opts'),
          ),

          array(
            'id'    => 'navigation',
            'type'    => 'select',
            'title'   => __('Navigation', 'mfn-opts'),
            'options' => array(
              ''          => __( 'Default', 'mfn-opts' ),
              'hide-arrows'   => __( 'Hide Arrows', 'mfn-opts' ),
              'hide-dots'     => __( 'Hide Dots', 'mfn-opts' ),
              'hide'        => __( 'Hide', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Slider Plugin --------------------------------------------------

      'slider_plugin' => array(
        'type'    => 'slider_plugin',
        'title'   => __('Slider Plugin', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'rev',
            'type'    => 'select',
            'title'   => __('Slider | Revolution Slider', 'mfn-opts'),
            'desc'    => __('Select one from the list of available <a target="_blank" href="admin.php?page=revslider">Revolution Sliders</a>', 'mfn-opts'),
            'options'   => mfn_get_sliders(),
          ),

          array(
            'id'    => 'layer',
            'type'    => 'select',
            'title'   => __('Slider | Layer Slider', 'mfn-opts'),
            'desc'    => __('Select one from the list of available <a target="_blank" href="admin.php?page=layerslider">Layer Sliders</a>', 'mfn-opts'),
            'options'   => mfn_get_sliders_layer(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Sliding Box ----------------------------------------------------

      'sliding_box' => array(
        'type'    => 'sliding_box',
        'title'   => __( 'Sliding Box', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'      => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
            'desc'    => __( 'Allowed HTML tags: span, strong, b, em, i, u', 'mfn-opts' ),
          ),

          array(
            'id'      => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'      => 'target',
            'type'    => 'select',
            'title'   => __( 'Link | Target', 'mfn-opts' ),
            'options' => array(
              0           => __( 'Default | _self', 'mfn-opts' ),
              1           => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'      => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'sub_desc'=> __( 'Entrance animation', 'mfn-opts' ),
            'options' => mfn_get_animations(),
          ),

          array(
            'id'      => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'=> __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Story Box ------------------------------------------------------

      'story_box' => array(
        'type'    => 'story_box',
        'title'   => __('Story Box', 'mfn-opts'),
        'size'    => '1/2',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>750px - 1500px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              ''      => __( 'Horizontal Image', 'mfn-opts' ),
              'vertical'  => __( 'Vertical Image', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width sc',
            'validate'  => 'html',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __('Animation', 'mfn-opts'),
            'sub_desc'  => __('Entrance animation', 'mfn-opts'),
            'options'   => mfn_get_animations(),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Tabs -----------------------------------------------------------

      'tabs' => array(
        'type'    => 'tabs',
        'title'   => __('Tabs', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'blocks',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
          ),

          // tabs
          array(
            'id'    => 'info_tabs',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Tabs', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'tabs',
            'type'    => 'tabs',
            'title'   => '',
            'sub_desc'  => __('To add an <strong>icon</strong> in Title field, please use the following code:<br/><br/>&lt;i class=" icon-lamp"&gt;&lt;/i&gt; Tab Title', 'mfn-opts'),
            'desc'    => __('<b>JavaScript</b> content like Google Maps and some plugins shortcodes do <b>not work</b> in tabs. You can use Drag & Drop to set the order', 'mfn-opts'),
          ),

          // options
          array(
            'id'    => 'info_options',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Options', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'type',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'desc'    => __('Vertical tabs works only for column widths: 1/2, 3/4 & 1/1', 'mfn-opts'),
            'options'   => array(
              'horizontal'  => __( 'Horizontal', 'mfn-opts' ),
              'centered'    => __( 'Horizontal (centered tab)', 'mfn-opts' ),
              'vertical'    => __( 'Vertical', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'padding',
            'type'    => 'text',
            'title'   => __('Content Padding', 'mfn-opts'),
            'sub_desc'  => __('Leave empty to use defult padding', 'mfn-opts'),
            'desc'    => __('Use value with <b>px</b> or <b>%</b>. Example: <b>20px</b> or <b>15px 20px 20px</b> or <b>20px 1%</b>', 'mfn-opts'),
            'class'   => 'small-text',
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'uid',
            'type'    => 'text',
            'title'   => __('Unique ID [optional]', 'mfn-opts'),
            'sub_desc'  => __('Allowed characters: "a-z" "-" "_"', 'mfn-opts'),
            'desc'    => __('Use this option if you want to open specified tab from link.<br />For example: Your Unique ID is <strong>offer</strong> and you want to open 2nd tab, please use link: <strong>your-url/#offer-2</strong>', 'mfn-opts'),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Testimonials ---------------------------------------------------

      'testimonials' => array(
        'type'    => 'testimonials',
        'title'   => __('Testimonials', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'testimonial-types' ),
            'sub_desc'  => __('Select the testimonial post category.', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
            ),
            'std'     => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'     => 'DESC'
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Advanced', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              ''        => __('Default','mfn-opts'),
        //              'hide-bar'    => __('Hide bar beneath images','mfn-opts'),
              'single-photo'  => __('Single Photo','mfn-opts'),
            ),
          ),

          array(
            'id'    => 'hide_photos',
            'type'    => 'select',
            'title'   => __('Hide Photos', 'mfn-opts'),
            'options'   => array(
              0 => __( 'No', 'mfn-opts' ),
              1 => __( 'Yes', 'mfn-opts' ),
            ),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __('Custom', 'mfn-opts'),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Testimonials List ----------------------------------------------

      'testimonials_list' => array(
        'type'    => 'testimonials_list',
        'title'   => __('Testimonials List', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'loops',
        'fields'  => array(

          array(
            'id'    => 'category',
            'type'    => 'select',
            'title'   => __('Category', 'mfn-opts'),
            'options' => mfn_get_categories( 'testimonial-types' ),
            'sub_desc'  => __('Select the testimonial post category.', 'mfn-opts'),
          ),

          array(
            'id'    => 'orderby',
            'type'    => 'select',
            'title'   => __('Order by', 'mfn-opts'),
            'options'   => array(
              'date'      => __( 'Date', 'mfn-opts' ),
              'menu_order'  => __( 'Menu order', 'mfn-opts' ),
              'title'     => __( 'Title', 'mfn-opts' ),
            ),
            'std'     => 'date'
          ),

          array(
            'id'    => 'order',
            'type'    => 'select',
            'title'   => __('Order', 'mfn-opts'),
            'options' => array(
              'ASC'   => __( 'Ascending', 'mfn-opts' ),
              'DESC'  => __( 'Descending', 'mfn-opts' ),
            ),
            'std'     => 'DESC'
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __('Style', 'mfn-opts'),
            'options'   => array(
              ''      => __('Default','mfn-opts'),
              'quote'   => __('Quote above the author','mfn-opts'),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Timeline -------------------------------------------------------

      'timeline' => array(
        'type'    => 'timeline',
        'title'   => __('Timeline', 'mfn-opts'),
        'size'    => '1/1',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'tabs',
            'type'    => 'tabs',
            'title'   => __('Timeline', 'mfn-opts'),
            'sub_desc'  => __('Please add <strong>date</strong> wrapped into <strong>span</strong> tag in Title field.<br/><br/>&lt;span&gt;2013&lt;/span&gt;Event Title', 'mfn-opts'),
            'desc'    => __('You can use Drag & Drop to set the order.', 'mfn-opts'),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Trailer Box ----------------------------------------------------

      'trailer_box' => array(
        'type'    => 'trailer_box',
        'title'   => __( 'Trailer Box', 'mfn-opts' ),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __( 'Image', 'mfn-opts' ),
            'desc'    => __( 'Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts' ),
          ),

          array(
            'id'    => 'slogan',
            'type'    => 'text',
            'title'   => __( 'Slogan', 'mfn-opts' ),
          ),

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __( 'Title', 'mfn-opts' ),
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __( 'Link', 'mfn-opts' ),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          // advanced
          array(
            'id'    => 'info_advanced',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Advanced', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'style',
            'type'    => 'select',
            'title'   => __( 'Style', 'mfn-opts' ),
            'options'   => array(
              ''      => __( 'Default', 'mfn-opts' ),
              'plain'   => __( 'Plain', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'animate',
            'type'    => 'select',
            'title'   => __( 'Animation', 'mfn-opts' ),
            'desc'    => __( '<b>Notice:</b> In some versions of Safari browser Hover works only if you select: <b>Not Animated</b> or <b>Fade In</b>', 'mfn-opts' ),
            'sub_desc'  => __( 'Entrance animation', 'mfn-opts' ),
            'options'   => mfn_get_animations(),
          ),

          // custom
          array(
            'id'    => 'info_custom',
            'type'    => 'info',
            'title'   => '',
            'desc'    => __( 'Custom', 'mfn-opts' ),
            'class'   => 'mfn-info',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __( 'Custom | Classes', 'mfn-opts' ),
            'sub_desc'  => __( 'Custom CSS Item Classes Names', 'mfn-opts' ),
            'desc'    => __( 'Multiple classes should be separated with SPACE', 'mfn-opts' ),
          ),

        ),
      ),

      // Video  --------------------------------------------
      'video' => array(
        'type'    => 'video',
        'title'   => __('Video', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'elements',
        'fields'  => array(

          array(
            'id'    => 'video',
            'type'    => 'text',
            'title'   => __('YouTube or Vimeo | Video ID', 'mfn-opts'),
            'sub_desc'  => __('YouTube or Vimeo', 'mfn-opts'),
            'desc'    => __('It`s placed in every YouTube & Vimeo video, for example:<br /><b>YouTube:</b> http://www.youtube.com/watch?v=<u>WoJhnRczeNg</u><br /><b>Vimeo:</b> http://vimeo.com/<u>62954028</u>', 'mfn-opts'),
            'class'   => 'small-text'
          ),

          array(
            'id'    => 'parameters',
            'type'    => 'text',
            'title'   => __('YouTube or Vimeo | Parameters', 'mfn-opts'),
            'sub_desc'  => __('YouTube or Vimeo', 'mfn-opts'),
            'desc'    => __('Multiple parameters should be connected with "&"<br />For example: <b>autoplay=1&loop=1</b><br /><br />Vimeo authors may disable some parameters for their videos', 'mfn-opts'),
          ),

          array(
            'id'      => 'mp4',
            'type'    => 'upload',
            'title'   => __( 'HTML5 | MP4 video', 'mfn-opts' ),
            'sub_desc'=> __( 'm4v [.mp4]', 'mfn-opts' ),
            'desc'    => __( 'Please add both mp4 and ogv for cross-browser compatibility.', 'mfn-opts' ),
            'data'    => 'video',
          ),

          array(
            'id'      => 'ogv',
            'type'    => 'upload',
            'title'   => __( 'HTML5 | OGV video', 'mfn-opts' ),
            'sub_desc'=> __( 'ogg [.ogv]', 'mfn-opts' ),
            'data'    => 'video',
          ),

          array(
            'id'    => 'placeholder',
            'type'    => 'upload',
            'title'   => __('HTML5 | Placeholder image', 'mfn-opts'),
            'desc'    => __('Placeholder Image will be used as video placeholder before video loads and on mobile devices.', 'mfn-opts'),
          ),

          array(
            'id'      => 'html5_parameters',
            'type'    => 'select',
            'title'   => __( 'HTML5 | Parameters', 'mfn-opts' ),
            'desc'    => __( 'Recent versions of WebKit browsers and iOS do not support autoplay.', 'mfn-opts' ),
            'options' => array(
              ''        => __( 'autoplay controls loop muted', 'mfn-opts' ),
              'a;c;l;'  => __( 'autoplay controls loop', 'mfn-opts' ),
              'a;c;;m'  => __( 'autoplay controls muted', 'mfn-opts' ),
              'a;;l;m'  => __( 'autoplay loop muted', 'mfn-opts' ),
              'a;c;;'   => __( 'autoplay controls', 'mfn-opts' ),
              'a;;l;'   => __( 'autoplay loop', 'mfn-opts' ),
              'a;;;m'   => __( 'autoplay muted', 'mfn-opts' ),
              'a;;;'    => __( 'autoplay', 'mfn-opts' ),
              ';c;l;m'  => __( 'controls loop muted', 'mfn-opts' ),
              ';c;l;'   => __( 'controls loop', 'mfn-opts' ),
              ';c;;m'   => __( 'controls muted', 'mfn-opts' ),
              ';c;;'    => __( 'controls', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'width',
            'type'    => 'text',
            'title'   => __('Width', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
            'std'     => 700,
          ),

          array(
            'id'    => 'height',
            'type'    => 'text',
            'title'   => __('Height', 'mfn-opts'),
            'desc'    => __('px', 'mfn-opts'),
            'class'   => 'small-text',
            'std'     => 400,
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Visual Editor  -------------------------------------------------

      'visual' => array(
        'type'    => 'visual',
        'title'   => __('Visual Editor', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'other',
        'fields'  => array(

          array(
            'id'    => 'title',
            'type'    => 'text',
            'title'   => __('Title', 'mfn-opts'),
            'desc'    => __('This field is used as an Item Label in admin panel only', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'visual',
            'title'   => __('Visual Editor', 'mfn-opts'),
          //            'param'   => 'editor',
          //            'validate'  => 'html',
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

      // Zoom Box -------------------------------------------------------

      'zoom_box' => array(
        'type'    => 'zoom_box',
        'title'   => __('Zoom Box', 'mfn-opts'),
        'size'    => '1/4',
        'cat'     => 'boxes',
        'fields'  => array(

          array(
            'id'    => 'image',
            'type'    => 'upload',
            'title'   => __('Image', 'mfn-opts'),
            'desc'    => __('Recommended image width: <b>768px - 1920px</b>, depending on size of the item', 'mfn-opts'),
          ),

          array(
            'id'    => 'bg_color',
            'type'    => 'color',
            'title'   => __('Overlay background', 'mfn-opts'),
            'std'     => '#CCCCCC',
          ),

          array(
            'id'    => 'content_image',
            'type'    => 'upload',
            'title'   => __('Content Image', 'mfn-opts'),
          ),

          array(
            'id'    => 'content',
            'type'    => 'textarea',
            'title'   => __('Content', 'mfn-opts'),
            'desc'    => __('HTML tags allowed', 'mfn-opts'),
            'class'   => 'full-width',
          ),

          array(
            'id'    => 'link',
            'type'    => 'text',
            'title'   => __('Link', 'mfn-opts'),
          ),

          array(
            'id'    => 'target',
            'type'    => 'select',
            'title'   => __('Link | Target', 'mfn-opts'),
            'options' => array(
              0       => __( 'Default | _self', 'mfn-opts' ),
              1       => __( 'New Tab or Window | _blank', 'mfn-opts' ),
              'lightbox'  => __( 'Lightbox (image or embed video)', 'mfn-opts' ),
            ),
          ),

          array(
            'id'    => 'classes',
            'type'    => 'text',
            'title'   => __('Custom | Classes', 'mfn-opts'),
            'sub_desc'  => __('Custom CSS Item Classes Names', 'mfn-opts'),
            'desc'    => __('Multiple classes should be separated with SPACE', 'mfn-opts'),
          ),

        ),
      ),

    );

      if( $item ){
        return $items[ $item ];
      }

      return $items;
  }
  

//add_action( 'after_setup_theme', 'sc_shop_slider' );
    function sc_shop_slider($attr, $content = null) {
    
		extract(shortcode_atts(array(
			'title'			=> '',
			'count'			=> 5,
			'show'			=> '',
			'category'	=> '',
			'orderby' 	=> 'date',
			'order' 		=> 'DESC',
		), $attr));

		// query args

		$args = array(
			'post_type' 			=> 'product',
			'posts_per_page' 	=> intval($count, 10),
			'paged' 					=> -1,
			'orderby' 				=> $orderby,
			'order' 					=> $order,
			'ignore_sticky_posts'	=> 1,
		);

		// show

		if ($show == 'featured') {

			// featured ------------------------------
			$args['post__in'] =  array_merge(array( 0 ), wc_get_featured_product_ids());
		} elseif ($show == 'onsale') {

			// onsale --------------------------------
			$args['post__in'] =  array_merge(array( 0 ), wc_get_product_ids_on_sale());			
		} elseif ($show == 'pre-orderable' || $show == 'pre-orderable-grid' || $show == 'pre-orderable-large' || $show == 'pre-orderable-grid-fc' || $show == 'pre-orderable-grid-dx') {
            
			// pre-orderable --------------------------------
			$args['post__in'] =  array_merge(array( 0 ), wc_get_product_ids_on_preorder());
		} elseif ($show == 'new-releases'  || $show == 'new-releases-grid' || $show == 'new-releases-large' || $show == 'new-releases-grid-fc' || $show == 'new-releases-grid-dx') {
            
			// new-releases --------------------------------
			$args['post__in'] =  array_merge(array( 0 ), wc_get_product_ids_new_releases());		
			
		} elseif ($show == 'best-selling' || $show == 'best-selling-grid' || $show == 'best-selling-large' || $show == 'best-selling-grid-fc' || $show == 'best-selling-grid-dx') {
			// best-selling --------------------------
			$args['meta_key'] = 'total_sales';
			$args['orderby'] 	= 'meta_value_num';
		}

		// category

		if ($category) {
			$args['product_cat'] = $category;
		}

		$query_shop = new WP_Query();
		$query_shop->query($args);
        
		// output -----

		$output = '<div class="shop_slider" data-count="'. esc_attr($query_shop->post_count) .'">';

			$output .= '<div class="blog_slider_header">';
				if ($title) {
					$output .= '<h4 class="title">'. wp_kses($title, mfn_allowed_html()) .'</h4>';
				}
			$output .= '</div>';

			if ( $show == 'pre-orderable-grid' || $show == 'best-selling-grid' || $show == 'new-releases-grid') {
                $output .= '<ul class="shop_slider_ul grid checking-ansari">';
			}elseif( $show == 'pre-orderable-grid-dx' || $show == 'best-selling-grid-dx' || $show == 'new-releases-grid-dx') {
                $output .= '<ul class="shop_slider_ul grid deluxe">';
			}elseif( $show == 'pre-orderable-grid-fc' || $show == 'best-selling-grid-fc' || $show == 'new-releases-grid-fc') {
                $output .= '<ul class="shop_slider_ul fullcover">';
			}elseif( $show == 'pre-orderable-large' || $show == 'best-selling-large' || $show == 'new-releases-large') {
                $output .= '<ul class="shop_slider_ul large checking-ansari">';
			}else{
                $output .= '<ul class="shop_slider_ul">';
			}			
				while ($query_shop->have_posts()) {
				
                    $query_shop->the_post();
					global $product;
					$terms = get_the_terms( $post->ID, 'product_cat' );
					if ($terms == 'comics'){ 
                        $terms = $terms;
					}
					
                    if ( $show == 'pre-orderable' || $show == 'best-selling' || $show == 'new-releases' || $show == 'pre-orderable-large' || $show == 'best-selling-large' || $show == 'new-releases-large') {
                                        
					$diamond = get_post_meta($product->get_id() ,"diamond_number", true);
					if (empty($diamond)) {
                        $diamond = 'comingsooncolor';
                    }
                    $thumbnailurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
                    if(!empty($thumbnailurl))
                    {
                        $imgurl = $thumbnailurl;
                    }else{		
                        $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/$diamond.jpg";
                        $thumbnail_p = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                        
                        if (file_get_contents($imgurl) === false) {  
                            if (file_get_contents($thumbnail_p) === false){
                                $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg";
                            }else{
                                $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                            }                        
                        }                    
                    }
					$output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .' carousel">';
						$output .= '<div class="item_wrapper">';
							
								$output .= '<div class="image_frame scale-with-grid product-loop-thumb">';

									$output .= '<div class="image_wrapper">';

										$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
											$output .= '<div class="mask"></div>';
											$output .= '<img src="'. esc_url($imgurl) .'">';
										$output .= '</a>';

										$output .= '<div class="image_links">';
											$output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'"><i class="icon-link"></i></a>';
										$output .= '</div>';

									$output .= '</div>';

 									if ($product->is_on_sale()) {
 										$output .= '<span class="onsale"><i class="icon-star"></i></span>';
 									}
                                $output .= '<div class="desc">';

								$output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(strtolower(get_the_title()), mfn_allowed_html()) .'</a></h4>';

 								if ($price_html = $product->get_price_html()) {
 									$output .= '<span class="price">'. $price_html .'</span>';
 									if ($show == 'pre-orderable-large' || $show == 'best-selling-large' || $show == 'new-releases-large') {
                                        $output .= '<span class="addtocart"><a class="link" href="/cart/?add-to-cart='. $product->get_id() .'"><i class="icon-basket"></i></a></span>';
 									}
                                }

							$output .= '</div>';
                        
                        $output .= '</div>';
							
							

						$output .= '</div>';
					$output .= '</li>';				                 
                     
                    
                    }elseif ( $show == 'pre-orderable-grid' || $show == 'best-selling-grid' || $show == 'new-releases-grid' || $show == 'pre-orderable-grid-fc' || $show == 'best-selling-grid-fc' || $show == 'new-releases-grid-fc' || $show == 'pre-orderable-grid-dx' || $show == 'best-selling-grid-dx' || $show == 'new-releases-grid-dx') {
                    $diamond = get_post_meta($product->get_id() ,"diamond_number", true);
                    if (empty($diamond)) {
                        $diamond = 'comingsooncolor';
                    }
                    $thumbnailurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
                    if(!empty($thumbnailurl))
                    {
                        $imgurl = $thumbnailurl;
                    }else{
                        $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/$diamond.jpg";
                        $thumbnail_p = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                        
                        if (file_get_contents($imgurl) === false) {  
                            if (file_get_contents($thumbnail_p) === false){
                                $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg";
                            }else{
                                $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                            }                        
                        }
                    }
                    $output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .' grid">';
						$output .= '<div class="item_wrapper">';
							
								$output .= '<div class="image_frame scale-with-grid product-loop-thumb">';

									$output .= '<div class="image_wrapper">';

										$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
											$output .= '<div class="mask"></div>';
											$output .= '<img src="'. esc_url($imgurl) .'">';
										$output .= '</a>';
                                        if ( $show == 'pre-orderable-grid-dx' || $show == 'best-selling-grid-dx' || $show == 'new-releases-grid-dx' ) {
                                            $output .= '<div class="addtocart"><a class="link" href="/cart/?add-to-cart='. $product->get_id() .'" style="color:#fff !important;">Add to Cart</a></div>';
                                        }
									$output .= '</div>';

 									if ($product->is_on_sale()) { 
 										$output .= '<span class="onsale"><i class="icon-star"></i></span>';
 									}
                        
                                $output .= '</div>';
                                
                                if ( $show == 'pre-orderable-grid-fc' || $show == 'best-selling-grid-fc' || $show == 'new-releases-grid-fc' ) {
                                        $output .= '<div class="product_links">';
                                            $output .= '<a class="link" href="/cart/?add-to-cart='. $product->get_id() .'" style="color:#fff !important;">Add to Cart</a>';
                                        $output .= '</div>';                                    
                                }else{
                                    $output .= '<div class="desc">';
                                        $output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
        // 								if ($price_html = $product->get_price_html()) {TODO
        // 									$output .= '<span class="price">'. $price_html .'</span>';
        // 								}
                                    $output .= '</div>';
                                
                            
                                $output .= '<div class="price">';
//                                     if ($price_html = $product->get_price_html()) {
//                                             $output .= '<span>'. $price_html .'</span>';
//                                         }
                                $output .= '</div>';
                                
                                $output .= '<div class="product_links">';
                                    $output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'">View product</a>';
                                $output .= '</div>';
							}

                        $output .= '</div>';
					$output .= '</li>';	
                     
                    }else{
                    
					$output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';
						$output .= '<div class="item_wrapper">';

							if (mfn_opts_get('shop-images') == 'secondary') {
								$output .= '<div class="hover_box hover_box_product" ontouchstart="this.classList.toggle(\'hover\');">';

									$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
										$output .= '<div class="hover_box_wrapper">';

											$output .= get_the_post_thumbnail(null, 'shop_catalog', array('class'=>'visible_photo scale-with-grid' ));

											if ($attachment_ids = $product->get_gallery_attachment_ids()) {
												$secondary_image_id = $attachment_ids['0'];
												$output .= wp_get_attachment_image($secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'hidden_photo scale-with-grid' ));
											}

										$output .= '</div>';
									$output .= '</a>';

// 									if ($product->is_on_sale()) { TODO
// 										$output .= '<span class="onsale"><i class="icon-star"></i></span>';
// 									}

								$output .= '</div>';
							} else {
								$output .= '<div class="image_frame scale-with-grid product-loop-thumb">';

									$output .= '<div class="image_wrapper">';

										$output .= '<a href="'. esc_url(get_the_permalink()) .'">';
											$output .= '<div class="mask"></div>';
											$output .= get_the_post_thumbnail(null, 'shop_catalog', array( 'class' => 'scale-with-grid' ));
										$output .= '</a>';

										$output .= '<div class="image_links">';
											$output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'"><i class="icon-link"></i></a>';
										$output .= '</div>';

									$output .= '</div>';

// 									if ($product->is_on_sale()) { TODO
// 										$output .= '<span class="onsale"><i class="icon-star"></i></span>';
// 									}

								$output .= '</div>';
							}
    
							$output .= '<div class="desc">';

								$output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';

// 								if ($price_html = $product->get_price_html()) {TODO
// 									$output .= '<span class="price">'. $price_html .'</span>';
// 								}

							$output .= '</div>';

						$output .= '</div>';
					$output .= '</li>';
				  }
				}

			$output .= '</ul>';
			
            if ( $show == 'pre-orderable-grid' || $show == 'best-selling-grid' || $show == 'new-releases-grid' || $show == 'pre-orderable-grid-fc' || $show == 'best-selling-grid-fc' || $show == 'new-releases-grid-fc' || $show == 'pre-orderable-grid-dx' || $show == 'best-selling-grid-dx' || $show == 'new-releases-grid-dx' ) {
                    $output .= '';
                }else{
                    $output .= '<div class="slider_pager slider_pagination"></div>';                
                }

		$output .= '</div>'."\n";

		wp_reset_postdata();

		return $output;
	    
    }
  
  /**
	 * Returns an array of pre-orderable products
    */
	//add_action('init','get_on_preorder_products', 10, 2);
	function get_on_preorder_products() {
		global $wpdb;
	return $wpdb->get_results( "
        SELECT post.ID as id, post.post_parent as parent_id FROM `$wpdb->posts` AS post
        JOIN `$wpdb->postmeta` AS meta ON post.ID = meta.post_id
        WHERE post.post_type = 'product'
            AND post.post_status = 'publish'
            AND meta.meta_key = '_wc_pre_orders_enabled'
            AND meta.meta_value = 'yes'
	" );
	}
	
	/**
	 * Returns an array of new release products
    */
	//add_action('init','get_on_newrelease_products', 10, 2);
	function get_on_newrelease_products() {
		global $wpdb;
	
	return $wpdb->get_results( "
        SELECT post.ID as id, post.post_parent as parent_id FROM `$wpdb->posts` AS post
        JOIN `$wpdb->postmeta` AS meta ON post.ID = meta.post_id
        WHERE post.post_type = 'product'
            AND post.post_status = 'publish'
            AND meta.meta_key = 'weekly_invoice_week_start'
	" );
	}
  
  /**
    * Function that returns an array containing the IDs of the products that are  pre-orderable.
    *
    */
    function wc_get_product_ids_on_preorder() {
        
        $product_ids_on_preorder = get_transient( 'wc_products_preorder' );
        // Valid cache found.
//         if ( false !== $product_ids_on_preorder ) {
//             return $product_ids_on_preorder;
//         }

        $data_store          = WC_Data_Store::load( 'product' );
        $preorder_products    = get_on_preorder_products();
        // $preorder_products    = $data_store->get_on_preorder_products();
        $product_ids_on_preorder = wp_parse_id_list( array_merge( wp_list_pluck( $preorder_products, 'id' ), array_diff( wp_list_pluck( $preorder_products, 'parent_id' ), array( 0 ) ) ) );

        set_transient( 'wc_products_preorder', $product_ids_on_preorder, DAY_IN_SECONDS * 30 );

        return $product_ids_on_preorder;
    }

    /**
    * Function that returns an array containing the IDs of the products that are  pre-orderable.
    *
    */
    function wc_get_product_ids_new_releases() {
        
        $product_ids_new_releases = get_transient( 'wc_products_new_releases' );

        // Valid cache found.
//         if ( false !== $product_ids_new_releases ) {
//             return $product_ids_new_releases;
//         }

        $data_store          = WC_Data_Store::load( 'product' );
    // $new_releases_products    = $data_store->get_on_newrelease_products();
        $new_releases_products    = get_on_newrelease_products();
        $product_ids_new_releases = wp_parse_id_list( array_merge( wp_list_pluck( $new_releases_products, 'id' ), array_diff( wp_list_pluck( $new_releases_products, 'parent_id' ), array( 0 ) ) ) );

        set_transient( 'wc_products_new_releases', $product_ids_new_releases, DAY_IN_SECONDS * 30 );

        return $product_ids_new_releases;
    }

function mfn_get_attachment_id_url( $url ){
    $attachment_id = 0;

    $dir = wp_upload_dir();

    if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
        $file = basename( $url );

        $query_args = array(
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'fields'      => 'ids',
            'meta_query'  => array(
                array(
                    'value'   => $file,
                    'compare' => 'LIKE',
                    'key'     => '_wp_attachment_metadata',
                ),
            )
        );

        $query = new WP_Query( $query_args );

        if ( $query->have_posts() ) {

            foreach ( $query->posts as $post_id ) {

                $meta = wp_get_attachment_metadata( $post_id );

                $original_file       = basename( $meta['file'] );
                $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

                if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                    $attachment_id = $post_id;
                    break;
                }

            }

        }

    }

    return $attachment_id;

}
?>
