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

    //vivid( $result );
    if( !empty( $result ) ){
    	$row = 1;
      $comics = array();
    	$body_arr = array();
    	foreach ($result as $key => $value) {

        $series_id  = $value['series_id'];
        $user_id  = $value['user_id'];
        $chk_status = $value['status'];

        $author_obj = get_user_by( 'id', $user_id );
        $username   = $author_obj->user_login;
        $email      = $author_obj->user_email;

        if( $value['status'] == 'active'){
            $child_products = vvd_popupcomicshops_get_child_product( $series_id );

            foreach ( $child_products as $child_product_id ){
              $available = get_post_meta( $child_product_id, 'Available', true );
              $newDateTime= date("Y-m-d H:i:s", strtotime($available));

              if(strtotime($newDateTime) >= strtotime($subscription_form_date) && strtotime($newDateTime) <= strtotime($subscription_to_date))
              {
                if( !popupcomicshops_has_existing_order( $user_id , $child_product_id ) ){ 
                  if( !in_array( $child_product_id, $comics ) ){ 
                    $comics[] = $child_product_id;
                  }
                }
              } 

            }// Child foreach end 

            /* Create Order */
            if(!empty($comics)){
              $status = vvd_popupcomicshops_create_order( $comics, $user_id );
              vivid('#'.$status.' Order Created.');
            }else{
              vivid('No Comics to add.');
            }
            //vivid( $comics );
           // vivid( 'active' );
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

          vivid('Deactive series');
        }
    		
    		$row++;
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
    'meta_key'		=> 'Series Code',
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

    <div class="postbox-container">
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
				jQuery("#message_log").show();
				jQuery("#subscription_button").attr("disabled","true");
				jQuery(".loading_message").html("Please wait while order create. it may take little while .. ");
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
					jQuery(".loading_message").html("Done");
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
			$available = get_post_meta( $child_product_id, 'Available', true );
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
		<!-- <footer> <a href="#" class="btn btn-small js-modal-close">Close</a> </footer> -->
	</div>

	<?php
}



function hwl_home_pagesize( $query ) {

    if ( is_post_type_archive( 'product' ) ) {
        // Display 50 posts for a custom post type called 'movie'
        $query->set( 'posts_per_page', 10 );
        return;
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


/**
* @snippet       Tiered Shipping Rates | WooCommerce
* @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
* @sourcecode    https://businessbloomer.com/?p=21425
* @author        Rodolfo Melogli
* @testedwith    WooCommerce 3.5.2
* @donate $9     https://businessbloomer.com/bloomer-armada/
*/
 
add_filter( 'woocommerce_package_rates', 'darkside_woocommerce_tiered_shipping', 10, 2 );
   
function darkside_woocommerce_tiered_shipping( $rates, $package ) {

   $cart_content = $package['contents'];

   $found_member = false;

   if(!empty($cart_content)){
		foreach ($cart_content as $value) {
			if ( has_term( 'member','become_member', $value['product_id'] )) {
				$found_member = true;
			}
		} 
	}
	if($found_member ){
		foreach($rates as $rate_id => $rate) {
		 	unset( $rates[$rate_id] );
		}
	}
   $shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
   $shipping_qty = WC()->cart->get_cart_contents_count();
   $shipping_qty_cost = ($shipping_qty - 30)*.2;
   $zone=$shipping_zone->get_id();
   $site_id = get_current_blog_id();
    if ($site_id == 13 ) {
        if ( $zone == 1 ) {//Domestic Shipping
                if ( WC()->cart->subtotal < 25 ) {
                        unset( $rates['flat_rate:5'], $rates['flat_rate:2'] );
                }elseif ( WC()->cart->subtotal > 25 && WC()->cart->subtotal < 60 ) {
                        unset( $rates['flat_rate:1'], $rates['flat_rate:5'] );
                }elseif ( WC()->cart->subtotal > 60 ) {
                        unset( $rates['flat_rate:1'], $rates['flat_rate:2'] );
                }
            }elseif ( $zone == 0 ) { //International Shipping
                if ( WC()->cart->subtotal < 45 ) {
                        unset( $rates['flat_rate:7'], $rates['flat_rate:8'] );
                }elseif ( WC()->cart->subtotal > 45 && WC()->cart->subtotal < 150 ) {
                        unset( $rates['flat_rate:6'], $rates['flat_rate:8'] );
                }elseif ( WC()->cart->subtotal > 150 ) {
                    if ( $shipping_qty <= 30 ) {
                        unset( $rates['flat_rate:6'], $rates['flat_rate:7'] );
                        }else{
                        unset( $rates['flat_rate:6'], $rates['flat_rate:7'] );
                        $rates['flat_rate:8']->cost = $shipping_qty_cost;
                        }
                }
        }
    }elseif ($site_id == 4 ) {
        if ( $zone == 2 ) {//Domestic Shipping
                if ( WC()->cart->subtotal < 25 ) {
                        unset( $rates['flat_rate:7'], $rates['flat_rate:8'] );
                }elseif ( WC()->cart->subtotal > 25 && WC()->cart->subtotal < 60 ) {
                        unset( $rates['flat_rate:8'], $rates['flat_rate:5'] );
                }elseif ( WC()->cart->subtotal > 60 ) {
                        unset( $rates['flat_rate:5'], $rates['flat_rate:7'] );
                }
            }elseif ( $zone == 0 ) {//International Shipping
                if ( WC()->cart->subtotal < 45 ) {
                        unset( $rates['flat_rate:9'], $rates['flat_rate:10'] );
                }elseif ( WC()->cart->subtotal > 45 && WC()->cart->subtotal < 150 ) {
                        unset( $rates['flat_rate:3'], $rates['flat_rate:10'] );
                }elseif ( WC()->cart->subtotal > 150 ) {
                    if ( $shipping_qty <= 30 ) {
                        unset( $rates['flat_rate:3'], $rates['flat_rate:9'] );
                        }else{
                        unset( $rates['flat_rate:3'], $rates['flat_rate:9'] );
                        $rates['flat_rate:10']->cost = $shipping_qty_cost;
                        }
                }
        }
    }elseif ( $site_id == 5 || $site_id == 6 ) {
        if ( $zone == 1 ) {//Domestic Shipping
                if ( WC()->cart->subtotal < 25 ) {
                        unset( $rates['flat_rate:2'], $rates['flat_rate:3'] );
                }elseif ( WC()->cart->subtotal > 25 && WC()->cart->subtotal < 60 ) {
                        unset( $rates['flat_rate:1'], $rates['flat_rate:3'] );
                }elseif ( WC()->cart->subtotal > 60 ) {
                        unset( $rates['flat_rate:1'], $rates['flat_rate:2'] );
                }
            }elseif ( $zone == 0 ) {//International Shipping
                if ( WC()->cart->subtotal < 45 ) {
                        unset( $rates['flat_rate:5'], $rates['flat_rate:6'] );
                }elseif ( WC()->cart->subtotal > 45 && WC()->cart->subtotal < 150 ) {
                        unset( $rates['flat_rate:4'], $rates['flat_rate:6'] );
                }elseif ( WC()->cart->subtotal > 150 ) {
                    if ( $shipping_qty <= 30 ) {
                        unset( $rates['flat_rate:4'], $rates['flat_rate:5'] );
                        }else{
                        unset( $rates['flat_rate:4'], $rates['flat_rate:5'] );
                        $rates['flat_rate:6']->cost = $shipping_qty_cost;
                        }
                }
        }
    }
   return $rates;
}

//Update shipping label if $shipping_qty > 30 on $zone == 0
function darkside_change_int_rate_label( $label, $method ) {
   $shipping_qty = WC()->cart->get_cart_contents_count();
   $shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
   $zone = $shipping_zone->get_id();
	if ( $zone == 0 && $shipping_qty > 30 ) {
		$label = str_replace( "Free Shipping", "Shipping Rate", $label );
	}
	return $label;
}
add_filter( 'woocommerce_cart_shipping_method_full_label', 'darkside_change_int_rate_label', 10, 2 );

//Adding Insurance to Shipping
add_action('woocommerce_cart_totals_after_shipping', 'wc_shipping_insurance_note_after_cart');
function wc_shipping_insurance_note_after_cart() {
	global $woocommerce;
	$order_subtotal = WC()->cart->subtotal;	
	$total_insurance = (3+(floor(($order_subtotal-100)/100)));
	$product_slug = "shipping-insurance";
	$product_obj = get_page_by_path( $product_slug, OBJECT, 'product' );
	$product_details = wc_get_product( $product_obj );
	$_product_id = $product_details->get_id();
	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
		if ( $_product->get_slug() == $product_slug )
			$found = true;
	}
	// if product not found, add it
	if ( ! $found ):
		?>
		<tr class="shipping">
			<th><?php _e( 'Shipping Insurance', 'woocommerce' ); ?></th>
			<td><a href="<?php echo do_shortcode('[add_to_cart_url id="'. $_product_id . '"]'); ?>"><span>Add shipping insurance (+$<?php echo $total_insurance; ?>)</span></a></td>
		</tr>
		<?php else: ?>
			<tr class="shipping">
				<th><?php _e( 'Shipping Insurance', 'woocommerce' ); ?></th>
				<td>$<?php echo $total_insurance; ?></td>
			</tr>
		<?php endif;
	}
add_action( 'woocommerce_after_calculate_totals', 'add_custom_price' );
function add_custom_price( $cart_object ) {
    global $woocommerce;
    $order_subtotal = WC()->cart->subtotal;
    $total_insurance = (3+(floor(($order_subtotal-100)/100)));
    foreach ( $cart_object->cart_contents as $key => $value ) {
        $_product = $value['data'];
        $custom_price = $total_insurance;
        if ( $_product->get_slug() == "shipping-insurance" ) {
            $value['data']->set_price($custom_price); 
        }
    }
}

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

    echo '<div class="search-fields">';
      echo '<form method="post" name="series_search_form" class="series_search_form">';
      echo '<div class="input-group">';
          echo '<input type="text" name="search_keyword"style="display: inline;">';
          echo '<button type="submit" class="button">Search</button>';
      echo '</div>';
      echo '</form>';
    echo '</div>'; 


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
  <div id="find-subscription" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false" style="display: block;">
    content for subscription 
  </div>
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
?>

