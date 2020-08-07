<?php
/**
 * Plugin Name: Weekly Invoice
 * Plugin URI: 
 * Description: Weekly Invoice Import
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain: 
 *
 * @package wp_weekly_invoice
 */

define( 'INVOICE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'INVOICE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

class wp_weekly_invoice
{

  public function __construct()
  {
    # code...
    add_action( 'admin_enqueue_scripts', array( $this, 'weekly_invoice_admin_style' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'weekly_invoice_admin_scripts' ) );

    #front CSS
    add_action( 'wp_enqueue_scripts', array( $this, 'weekly_invoice_frontend_style' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'weekly_invoice_frontend_scripts' ) );

    # Add Admin Page
    add_action( 'admin_menu', array( $this, 'weekly_invoice_admin_menu' ) );

    // called just before the template functions are included
    add_action( 'init', array( &$this, 'include_template_functions' ), 20 );

    add_shortcode('vvd_weekly_invoice', array( &$this, 'vvd_weekly_invoice_shortcode'));

    add_action( 'admin_notices', array( $this,  'weekly_invoice_admin_notice_example_notice' ) );
  }

  public function weekly_invoice_admin_notice_example_notice(){
    ?>
      <div class="updated fade">
          <p>Copy this shortcode and paste it into your post, page, or text widget content : <strong>[vvd_weekly_invoice per_page="" order="" orderby=""]</strong></p>
      </div>
    <?php
        
  }

  public function weekly_invoice_frontend_style(){
     wp_register_style( 'invoice_grid_style', INVOICE_PLUGIN_DIR_URL.'admin/css/invoice_grid_style.css');

  }

  public function weekly_invoice_frontend_scripts(){

    global $wp_query; 

    wp_register_script( 'invoice_grid_script', INVOICE_PLUGIN_DIR_URL.'admin/js/invoice_grid_script.js' );
    wp_localize_script( 'invoice_grid_script', 'invoice_script', array(
        'ajax_url' =>  admin_url("admin-ajax.php") ,
        'plugin_dir' =>  plugin_dir_path( __FILE__ ),
    ) );

    // This script will contain all the code which will process our load more button
    wp_register_script( 'multisite_loadmore', INVOICE_PLUGIN_DIR_URL .'admin/js/multisite_loadmore.js', array('jquery') );

    // And finally creating object of parameters with wp_localize_script()
    wp_localize_script( 'multisite_loadmore', 'misha_loadmore_params', array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
      'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
      'max_page' => $wp_query->max_num_pages
    ) );

  }

  public function weekly_invoice_admin_style(){

    wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/css/bootstrap.min.css', array(), "", "" );
    wp_register_style( 'invoice_style', plugin_dir_url( __FILE__ ).'admin/css/invoice_style.css', array(), "", "" );
   
  }

  public function weekly_invoice_admin_scripts(){

    wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/js/bootstrap.min.js' );

    wp_register_script( 'invoice_upload', plugin_dir_url( __FILE__ ).'admin/js/invoice_upload.js' );
    wp_localize_script( 'invoice_upload', 'admin_upload', array(
        'ajax_url' =>  admin_url("admin-ajax.php") ,
        'plugin_dir' =>  plugin_dir_path( __FILE__ ),
    ) );
  }

  public function weekly_invoice_admin_menu(){
      #adding as main menu
    add_menu_page( 'Weekly Invoice Upload', 'Weekly Invoice', 'manage_options', 'weekly_invoice_page', array( $this, 'weekly_invoice_html' ), 'dashicons-upload', 6  );
  }
  
  public function weekly_invoice_html(){
    require_once( 'admin/html/weekly-invoice-html.php' );  
  }

  /**
  * Override any of the template functions from woocommerce/woocommerce-template.php 
  * with our own template functions file
  */
  public function include_template_functions() {
    include( INVOICE_PLUGIN_DIR.'include/template-ajax.php' );
  }

  public function get_percent_complete($total_row,$end_pos) {
      //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
      return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
  }

  public function count_total_file_row($filename){
    $fp = file($filename, FILE_SKIP_EMPTY_LINES);
    return count($fp);
  }

  public function va_weekly_import_product($args, $startpos, $file_url, $total_data, $message, $invoice_date ){
    
    $exist = post_exists( $args['item_title'],'','','product' );
    $meta_val = substr( $args['diamond_no'], 0, 9 );
    $data = array(
      'post_type'      => 'product',
      'posts_per_page' => 10,
      'post_status'    => 'publish',
      'meta_key'       => 'diamond_number',
      'meta_value'     => $meta_val,
    );

    $query = new WP_Query( $data );
    $all_post = $query->posts;
    
    if( empty($all_post) ){
      $result = $args['item_title'].' - #Post Not Exists';
    }else{
      
      $open_popup = $this->check_product_count_preorder($all_post[0]->ID, $args);

      if( $open_popup['success'] ){

        $end_pos = $startpos+1;
        $total_percentage = $this->get_percent_complete($total_data,$end_pos);
        $d = date("j-M-Y H:i:s");

        $message = '['.$d.'] - '.$args['item_title'].' - #Open Popup';

        wp_send_json_success(
          array(
            'pos' => $end_pos,
            'file_path' => $file_url,
            'percentage' => $this->get_percent_complete($total_data,$end_pos),
            'message' => $message,
            'popup' => 'true',
            'prod_id' => $all_post[0]->ID,
            'prod_name' => $args['item_title'],
            'req_qty' => $open_popup['req_qty'],
            'invoice_date' => $invoice_date,
          )
        );

      }else{
        $today_date = date("Y-m-d");
        $time = strtotime($today_date);
        $year = date("Y",$time);

        $ddate = date("Y-m-d");
        $date = new DateTime($ddate);
        $week = $date->format("W");
        
        $date_range = $this->getWeekDates($year, $week);
        
        $this->update_weekly_invoice_meta( $all_post[0]->ID, $date_range, $today_date, $invoice_date );
        $result = $args['item_title']. '-' . $invoice_date.' - #Post Exists';
        
      }
      
    }

    return $result;
  }
  public function update_weekly_invoice_meta($post_id, $date_range, $import_date, $invoice_date){

    $date_arr = ( explode( "/", $date_range ) );
    $start_date = $date_arr[0];
    $end_date = $date_arr[1];
    
    update_post_meta( $post_id, 'weekly_invoice', 'yes' );
    update_post_meta( $post_id, 'weekly_invoice_update', 'yes');
    update_post_meta( $post_id, 'weekly_invoice_date', 'yes');
    update_post_meta( $post_id, 'weekly_invoice_week_start', $start_date);
    update_post_meta( $post_id, 'weekly_invoice_week_end', $end_date);
    update_post_meta( $post_id, 'import_invoice_date', $invoice_date);
  }

  public function getWeekDates($year, $week, $start=true){
      $from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
      $to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week
   
      /*if($start) {
          return $from;
      } else {
          return $to;
      }*/
      return "{$from}/{$to}";
  }

  public function va_weekly_import_preorder_update_qty( $product_id, $title, $invoice_date, $qty_value ) {

    if( !empty($product_id) ){

        $today_date = date("Y-m-d");
        $time = strtotime($today_date);
        $year = date("Y",$time);

        $ddate = date("Y-m-d");
        $date = new DateTime($ddate);
        $week = $date->format("W");
        
        $date_range = $this->getWeekDates($year, $week);
        
        $this->update_weekly_invoice_meta( $product_id, $date_range, $today_date, $invoice_date );

        $out_of_stock_staus = 'outofstock';

        // 1. Updating the stock quantity
        update_post_meta($product_id, '_stock', $qty_value);

        // 2. Updating the stock quantity
        // update_post_meta( $product_id, '_stock_status', wc_clean( $out_of_stock_staus ) );

        // 3. Updating post term relationship
        // wp_set_post_terms( $product_id, 'outofstock', 'product_visibility', true );

        // And finally (optionally if needed)
        wc_delete_product_transients( $product_id ); 

        $result = $title.' - #Popup Update';

    }else{
      $result = $title.' - #Post Not Exists';
    }

    return $result;

  }

  public function check_product_count_preorder($cur_product, $data) {

  	global $wpdb;

  	$today = date("m/d/Y");

  	$startdate = strtotime( $today );
  	$enddate = strtotime("-2 weeks", $startdate);

  	$subscription_to_date = date("Y-m-d H:i:s",strtotime( "+1 weeks", $startdate ));
  	$subscription_form_date = date("Y-m-d H:i:s",strtotime( $today ));

  	$blog_id = get_current_blog_id();
  	$sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id AND status = 'active'";

  	$result = $wpdb->get_results( $sql, 'ARRAY_A' );

  	if( !empty( $result ) ){
  		$row = 1;
  		$comics = array();
      $all_comics = array();
  		foreach ($result as $key => $value) {

  			$series_id  = $value['series_id'];
  			$user_id  = $value['user_id'];
  			$chk_status = $value['status'];

  			$author_obj = get_user_by( 'id', $user_id );
  			$username   = $author_obj->user_login;
  			$email      = $author_obj->user_email;


  			$child_products = vvd_popupcomicshops_get_child_product( $series_id );
        //vivid( $series_id ." ====> ". print_r($child_products,true));
  			foreach ( $child_products as $child_product_id ){
  				$available = get_post_meta( $child_product_id, 'available', true );
  				$newDateTime= date("Y-m-d H:i:s", strtotime($available));

  				if(strtotime($newDateTime) >= strtotime($subscription_form_date) && strtotime($newDateTime) <= strtotime($subscription_to_date))
  				{
  					if( !popupcomicshops_has_existing_order( $user_id , $child_product_id ) ){ 
  						if( !in_array( $child_product_id, $comics ) ){ 
  							$comics[] = $child_product_id;
  						}
  					}
  				} 

          $all_comics[] = $child_product_id;

          
  			}

  			$row++;
  		}
  	}

    $all_comics = array_count_values( $all_comics );
    //$cur_product = 64031;
    if (array_key_exists($cur_product, $all_comics))
    {
      $data_qty = $data['item_code'];
      $order_qty = $all_comics[ $cur_product ];

      if($data_qty <= $order_qty){
        $final_result['success'] = true;
        $final_result['req_qty'] = $order_qty;
      }
      
      
    }
    
  	return $final_result;

  }

  public function vvd_weekly_invoice_shortcode( $atts ){
    ob_start();
     
      #Style load
      wp_enqueue_style("invoice_grid_style");
      wp_enqueue_script("invoice_grid_script");
      wp_enqueue_script( 'multisite_loadmore' );

      $status = array('publish', 'draft');
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => $status,
        'meta_query' => array(
          array(
            'key' => 'weekly_invoice',
            'value' =>'yes',
            'compare' => 'EXISTS',
          )
        ),
      );

     /* $query = new WP_Query( $args );
      $all_post = $query->posts;*/

      $save_week_invoice_date = get_option( 'weekly_invoice_import_date');
      
      $week_invoice_date_arr = array();
      foreach ($save_week_invoice_date as $key => $value) {
        # code...

        $week_invoice_date_arr[] =  strtotime($value);
      }

      $result = array_unique( $week_invoice_date_arr );
      arsort($result);
      // vivid($result);
      $arr_key = array_key_first($result);

        
      ?>

      <form name="weekley_prod_filter" id="weekley_prod_filter" method="get">
        <section>
           <h5>Filter by Date :</h5>
            <select name="weekley_sel_import_date" class="weekley_sel_import_date">
                <option value="" disabled selected="selected">Please Select Date</option>
                <?php
                $i = 1;
                foreach ($result as $key_value) {
                    if($i == 1){
                        $latest_import_date = date("m/d/Y", $key_value);
                    }
                    ?>
                    <option value="<?php echo date("m/d/Y", $key_value); ?>" <?php if($i == 1 ){ echo 'selected="selected"';} ?> ><?php echo date("m/d/Y", $key_value); ?></option>
                    <?php  
                    $i++; 
                }
                ?>
            </select>
            <input type="button" name="" class="button weekley_submit_search_form" value="Filter"><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="filter_loader" style="display:none;">
            <input type="hidden" name="action" class="" value="func_weekly_import_date_filter">
            <?php 
            if ( is_front_page() && is_home() ) {
              // echo ' // Default homepage ';
            } elseif ( is_front_page()){
              $val = 1;
              echo '<input type="hidden" name="is_home" class="" value="'.$val.'">';
              // echo ' // Static homepage ';
            } elseif ( is_home()){
              // echo 'Blog page';
            } else {
              // echo '// Everything else';
            }
            ?>
        </section>
      </form>
      <?php

      $values = shortcode_atts(array(
        'per_page' => '',
        'include'=> '',
        ),$atts);  

        if(!empty($atts['per_page'])){
            $per_page = $atts['per_page'];
        }else{
            $per_page = 12;
        }

        if(!empty($atts['order'])){
            $popup_order = $atts['order'];
        }else{
            $popup_order = 'ASC';
        }

        if(!empty($atts['orderby'])){
            $popup_orderby = $atts['orderby'];
        }else{
            $popup_orderby = 'title';
        }

        if(!empty($atts['show_add_to_cart'])){
            $show_add_to_cart = $atts['show_add_to_cart'];
        }else{
            $show_add_to_cart = 'No';
        }

        // vivid( $result );
        // vivid( $arr_key );

        $latest_import_date = date("m/d/Y", $result[$arr_key]);
        // vivid( $latest_import_date );

        $args = array(
                'posts_per_page'    =>  $per_page,
                'post_type'         => array('product'/* ,'product_variation' */),
                'post_status'       => 'publish',
                'orderby'           => $popup_orderby,
                'order'             => $popup_order,
                'meta_query'        => array(
                    'relation'      => 'OR',
                     'import_invoice_date'   => array (
                        'key'       => 'import_invoice_date',
                        'value'     => $latest_import_date,
                        'compare'   => 'EXISTS',
                    ),
                ),

                
            );
        /*$include = $atts['include'];//'books-graphic-novels';
        if( isset( $include ) && !empty( $include )){
          $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array( $include ),
          );
        }

        $exclude = $atts['exclude'];//'books-graphic-novels';
        if( isset( $exclude ) && !empty( $exclude )){
          $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array( $exclude ),
            'operator' => 'NOT IN',
          );
        }*/

       
        $prod_query = new WP_Query($args);

        if ( ! $prod_query->have_posts() ) {
           ?>
            <section id="weeleky_cards" class="weeleky_cards">
              <?php
            echo "No Product found!";
            //exit();
            ?>
          </section>
            <?php
        }else{
            ?>
            <section id="weeleky_cards" class="weeleky_cards">
              <?php
              while ( $prod_query->have_posts() ): $prod_query->the_post();
                  $product = new WC_Product(get_the_ID());

                  $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
                  $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

                  $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

                  $aws_img = fetch_image_from_AWS( get_the_ID() );
                  if(!empty($aws_img)){
                      $prod_img = $aws_img;
                      $image_info = getimagesize( $prod_img ); 

                  }else{
                      $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
                  }
                  
                  ?>


                  <article>
                      <div class="product_column product-block" data-dimond="<?php echo $dimond_no; ?>">
                        <a href="<?php  echo get_permalink(get_the_ID()); ?>">
                          <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('<?php echo $prod_img; ?>')">
                              
                                  <div class="vyte-overlay"></div>
                                  <!-- <img src="<?php //echo $prod_img; ?>" alt = "" style="width: auto;height: 340px !important;"> -->
                              
                          </div></a>
                          <div class="product-info">
                              <div class="prod_links">
                                  <a href="<?php  echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title();  ?> </a>
                              </div>

                              <div class="prod_price">
                                  <?php echo $product->get_price_html(); ?>
                              </div>

                              <?php if( $show_add_to_cart != 'No') {?>
                                <div class="add_to_cart_link">
                                    <?php
                                    $add_to_cart = do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]');
                                    ?>

                                    <a href="<?php echo $add_to_cart; ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="img_loader" style="display:none;">

                                </div>
                              <?php } ?>

                          </div>
                      </div>
                  </article>

                  <?php
              endwhile;

            ?>
          </section>


        <?php

        if ( is_front_page() && is_home() ) {
          // echo ' // Default homepage ';
        } elseif ( is_front_page()){
          // echo ' // Static homepage ';
          echo '<a href="'.site_url().'/shop/#newreleases" id="more_posts" class="button product_type_simple newreleases" style="display: block;text-align: center;">More New Releases</a>';
        } elseif ( is_home()){
          // echo 'Blog page';
        } else {
          // echo '// Everything else';
        }

        if ( $prod_query->max_num_pages > 1 ) {
           // echo '<div id="misha_loadmore" class="misha_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">More posts</div>';
        } 

    }
    return ob_get_clean();
  }

} /* End Class */

$wp_upload = new wp_weekly_invoice();

?>
