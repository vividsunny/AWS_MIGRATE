<?php
/**
 * Functions.php
 *
 * @package  vivid_Customisations
 * @author   WooThemes
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * functions.php
 * Add PHP snippets here
 */

/**
 * Debug code
 * Add PHP snippets here
 */
// https://demo1.popupcomicshops.com/checkout/order-received/59598/?key=wc_order_oNSDwRiIkUM2R
// http://localhost/wp_test/checkout/order-received/55/?key=wc_order_nRxBofr6KAimj

include 'classes/class-pre-order.php';

function debug( $args ){
	echo '<pre>';
	print_r($args);
	echo '</pre>';
}

/** 
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
**/
function register_vvd_custom_order_status() {
    register_post_status( 'wc-vvd_preorder', array(
        'label'                     => 'Preorder',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Preorder <span class="count">(%s)</span>', 'Preorder <span class="count">(%s)</span>' )
    ) );

    register_post_status( 'wc-vvd_reorder', array(
        'label'                     => 'Reorder',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Reorder <span class="count">(%s)</span>', 'Reorder <span class="count">(%s)</span>' )
    ) );

    register_post_status( 'wc-vvd_order', array(
        'label'                     => 'Order',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Order <span class="count">(%s)</span>', 'Order <span class="count">(%s)</span>' )
    ) );

}
add_action( 'init', 'register_vvd_custom_order_status' );


// Add to list of WC Order statuses
function add_vvd_status_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-vvd_preorder'] = 'Preorder';
            $new_order_statuses['wc-vvd_reorder'] = 'Reorder';
            $new_order_statuses['wc-vvd_order'] = 'Order';
        }
    }
    return $new_order_statuses;
}
//add_filter( 'wc_order_statuses', 'add_vvd_status_to_order_statuses' );


//add_filter( 'bulk_actions-edit-shop_order', 'vvd_get_custom_order_status_bulk' );
function vvd_get_custom_order_status_bulk( $bulk_actions ) {
   // Note: "mark_" must be there instead of "wc"
   $bulk_actions['mark_custom-vvd-preorder'] = 'Change status to Preorder';
   $bulk_actions['mark_custom-vvd-reorder'] = 'Change status to reorder';
   $bulk_actions['mark_custom-vvd-order'] = 'Change status to order';
   return $bulk_actions;
}

add_action( 'woocommerce_thankyou', 'vvd_check_order_product_id', 10, 1);
function vvd_check_order_product_id( $order_id ){
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
                wc_update_order_item_meta($item_id, 'subsciption_label', 0);
                wc_update_order_item_meta($item_id, 'series_code', $order_series_code);


              }else if( strtotime($today_date) >= strtotime($IOD_date_strtotime) ){
              	
              	$vvd_key = 'item_label';
		        $vvd_value = 're-order';

		        if ( $_product->get_stock_quantity() > 0 ) {

	        		//debug('Order');
	        		$vvd_key = 'item_label';
		        	$vvd_value = 'order';
	        		wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                    wc_update_order_item_meta($item_id, 'subsciption_label', 0);
                    wc_update_order_item_meta($item_id, 'series_code', $order_series_code);

		        } else {

		        	//debug('Reorder');
		        	$vvd_key = 'item_label';
			        $vvd_value = 're-order';
			        wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                    wc_update_order_item_meta($item_id, 'subsciption_label', 0);
                    wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
		        }
		       
              }else{
              	//debug('Not In Else');
              	$vvd_key = 'item_label';
		        $vvd_value = '';
		        wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
                wc_update_order_item_meta($item_id, 'subsciption_label', 0);
                wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
              }
        	
        }else{
        	
        }
        
    }
}


add_action( 'woocommerce_product_options_advanced', 'vvd_IOD_adv_product_options');
function vvd_IOD_adv_product_options(){
 
	echo '<div class="options_group">';
 
	woocommerce_wp_text_input( array(
		'id'      => 'vvd_product_IOD_date',
		'value'   => get_post_meta( get_the_ID(), 'vvd_product_IOD_date', true ),
		'label'   => 'IOD Date',
		'desc_tip' => true,
		'description' => 'Set Initial Order Date',
	) );
 
	echo '</div>';
 
}
 
 
add_action( 'woocommerce_process_product_meta', 'vvd_IOD_save_fields', 10, 2 );
function vvd_IOD_save_fields( $id, $post ){
 
	update_post_meta( $id, 'vvd_product_IOD_date', $_POST['vvd_product_IOD_date'] );
 
}

function popupcomics_extend_search( $query ) {
    $search_term = filter_input( INPUT_GET, 's', FILTER_SANITIZE_NUMBER_INT) ?: 0;

    if ( $query->is_search && !is_admin()) {
        $query->set('meta_query', [
            [
                'key' => 'Diamond Number',
                'value' => $_GET['s'],
                'compare' => 'LIKE'
            ]
        ]);

        add_filter( 'get_meta_sql', function( $sql )
        {
            global $wpdb;

            static $nr = 0;
            if( 0 != $nr++ ) return $sql;

            $sql['where'] = mb_eregi_replace( '^ AND', ' OR', $sql['where']);

            return $sql;
        });

        $query->set( 'posts_per_page', '30' );

    }
    return $query;
}
add_action( 'pre_get_posts', 'popupcomics_extend_search',999);


// define the woocommerce_single_product_image_thumbnail_html callback 
function filter_woocommerce_single_product_image_thumbnail_html( $sprintf, $post_id ) {
    global $post;

    // Must be inside a loop.
    $product = wc_get_product( $post->ID );
    $post_thumbnail_id = $product->get_image_id();
  
    if ( $product->get_image_id() ) {

        if ( $product->get_image_id() ) {
            if( version_compare( WC_VERSION, '3.3.2', '<' ) ){
                // WC < 3.3.2 backward compatibility

                $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                $html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
                $html .= '</a></div>';

            } else {
                // WC 3.3.2+
                $html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
            }

        } else {

            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
            $html .= '</div>';

        }
        
    }else {
        

        $path = get_post_meta($post->ID ,"Image Path", true);
        $image_path = get_post_meta($post->ID ,"image_path", true);

        if(!empty($path)){
          $pro = $path;
        }else if($image_path){
          $pro = $image_path;
        }else{
            $pro = get_post_meta($post->ID ,"Image-Path", true);
        }
          
        $pro = str_replace("diamondcomics.com","",$pro);
        $s3_image = substr($pro, 6);

        require(get_stylesheet_directory().'/aws/vendor/autoload.php');
         $s3 = new Aws\S3\S3Client([
          'region'  => 'us-east-2',
          'version' => 'latest',
          'credentials' => [
            'key'    => 'AKIAWAJYLDONJ4G3V3NJ',
            'secret' => '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',
          ]
        ]); 
        $bucket = 'darksidecomics';
        if (empty($s3_image)){
            $s3_image = 'comingsooncolor.jpg';
        }
        $filename  = "previews/".$s3_image;
        $info = $s3->doesObjectExist($bucket, $filename);

        $zip_filename  = "zip-image/".$s3_image;
        $zip_file = $s3->doesObjectExist($bucket, $zip_filename);

        

        if($info)
        {
            $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$filename;
            $img_data = getimagesize($thumbnail);

            $width = $img_data[0];
            $height = $img_data[1];
            if( $width == 600 && $height == 900){
                $thumbnail = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
            }else{
                $thumbnail = $thumbnail;
            }

            $html  = '<div data-thumb="' . $thumbnail . '" class="woocommerce-product-gallery__image"><a href="' . $thumbnail . '">';
            $html .= '<img src="'.$thumbnail.'" class="wp-post-image" alt="" title="'.$s3_image.'" data-caption="" data-src="'.$thumbnail.'" data-large_image="'.$thumbnail.'" data-large_image_width="'.$img_data[0].'" data-large_image_height="'.$img_data[1].'" srcset="'.$thumbnail.' 204w, '.$thumbnail.' 99w, '.$thumbnail.' 34w, '.$thumbnail.' 51w">';
            $html .= '</a></div>';

        }

        if($zip_file)
        {
            $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$zip_filename;
            $img_data = getimagesize($thumbnail);

            $width = $img_data[0];
            $height = $img_data[1];
            if( $width == 600 && $height == 900){
                $thumbnail = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
            }else{
                $thumbnail = $thumbnail;
            }

            $html  = '<div data-thumb="' . $thumbnail . '" class="woocommerce-product-gallery__image"><a href="' . $thumbnail . '">';
            $html .= '<img src="'.$thumbnail.'" class="wp-post-image" alt="" title="'.$s3_image.'" data-caption="" data-src="'.$thumbnail.'" data-large_image="'.$thumbnail.'" data-large_image_width="'.$img_data[0].'" data-large_image_height="'.$img_data[1].'" srcset="'.$thumbnail.' 204w, '.$thumbnail.' 99w, '.$thumbnail.' 34w, '.$thumbnail.' 51w">';
            $html .= '</a></div>';

        }
    }   
    // make filter magic happen here... 
    return $html; 
}
         
// add the filter 
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'filter_woocommerce_single_product_image_thumbnail_html', 10, 2 ); 


function custom_new_product_image( $_product_img, $cart_item, $cart_item_key ) {

    // $html = filter_woocommerce_single_product_image_thumbnail_html('',$cart_item['product_id']);

    if ( has_post_thumbnail( $cart_item['product_id'] ) ) {
        $a      =   $_product_img;
    }else {
        $path = get_post_meta($cart_item['product_id'] ,"Image Path", true);
        $image_path = get_post_meta($cart_item['product_id'] ,"image_path", true);

        if(!empty($path)){
          $pro = $path;
        }else if($image_path){
          $pro = $image_path;
        }else{
            $pro = get_post_meta($cart_item['product_id'] ,"Image-Path", true);
        }
          
        $pro = str_replace("diamondcomics.com","",$pro);
        $s3_image = substr($pro, 6);
        require(get_stylesheet_directory().'/aws/vendor/autoload.php');
         $s3 = new Aws\S3\S3Client([
          'region'  => 'us-east-2',
          'version' => 'latest',
          'credentials' => [
            'key'    => 'AKIAWAJYLDONJ4G3V3NJ',
            'secret' => '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',
          ]
        ]); 
        $bucket = 'darksidecomics';
        if (empty($s3_image)){
            $s3_image = 'comingsooncolor.jpg';
        }
        $filename  = "previews/".$s3_image;
        $info = $s3->doesObjectExist($bucket, $filename);

        $zip_filename  = "zip-image/".$s3_image;
        $zip_file = $s3->doesObjectExist($bucket, $zip_filename);
        if($info)
        {
            $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$filename;
        }

        if($zip_file)
        {
            $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$zip_filename;
        }
        if (file_get_contents($thumbnail) === false) 
        {
            $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg';
        }
        $a      =   '<img src="'. $thumbnail .'" />';
    }
    return $a;
}

add_filter( 'woocommerce_cart_item_thumbnail', 'custom_new_product_image', 10, 3 );

add_shortcode('vvd_meta_update','popupcomics_meta_query_search');
function popupcomics_meta_query_search(){

    // $meta_keys = array(
    //     'Diamond Number',
    //     'Stock Number',
    //     'Series Code',
    //     'Issue Number',
    //     'Issue Sequence Number',
    //     'Price',
    //     'Publisher',
    //     'UPC Number',
    //     'Cards Per Pack',
    //     'Pack Per Box',
    //     'Box Per Case',
    //     'Discount Code',
    //     'Increment',
    //     'Print Date',
    //     'FOC Vendor',
    //     'Available',
    //     'SRP',
    //     'Category',
    //     'Mature',
    //     'Adult',
    //     'OA',
    //     'CAUT1',
    //     'CAUT2',
    //     'CAUT3',
    //     'RESOL',
    //     'Note Price',
    //     'Order Form Notes',
    //     'Page',
    //     'FOC Date',
    //     'Preview HTML',
    //     'Image Path',
    //     'Genre',
    //     'Brand Code',
    //     'Writer',
    //     'Artist',
    //     'Covert Artist',
    //     'Variant Desc',
    //     'Short ISBN No',
    //     'EAN No',
    //     'Colorist',
    //     'Alliance SKU',
    //     'Volume Tag',
    //     'Parent Item No Alt',
    //     'Offered Day',
    //     'Max Issue',
    //     'Cost',
    //     'StockID',
    // );
     
    // foreach ( $meta_keys as $k ) {
        
       
    //     $old_key = $k;
    //      // debug( $old_key );
    //     $lower = strtolower( $old_key );
    //     $new_key = str_replace(" ","_",$lower);
    //     debug( $old_key.' - '.$lower.' - '.$new_key );
        
    //     // update_meta_key( $old_key, $new_key );
     
    // }
    $countIx = 0;
    $file = "https://demo1.popupcomicshops.com/wp-content/plugins/wp_upload_zip/php/files/201905.xml";
    $reader = new XMLReader();
    $reader->open($file);
    while( $reader->read() ) {
      // Execute processing here

            while($reader->name == 'EXPORT_FILE')
            {
                $element = new SimpleXMLElement($reader->readOuterXML());
           
                echo '<pre>';
                print_r($element);
                echo '</pre>';
                
                print "\n";
                $countIx++;
                
                $reader->next('EXPORT_FILE');
                unset($element);
            }

    }
    $reader->close();

}

function update_meta_key( $old_key=null, $new_key=null ){
    global $wpdb;
    // debug($wpdb->prefix.' = '.$old_key.' - '.$new_key );
    $query = "UPDATE ".$wpdb->prefix."postmeta SET meta_key = '".$new_key."' WHERE meta_key = '".$old_key."'";
    $results = $wpdb->get_results( $query, ARRAY_A );
    // $results = $query;
    return $results;
}

add_action( 'woocommerce_before_shop_loop', 'popupcomics_before_shop_loop' );
function popupcomics_before_shop_loop() {
    wp_enqueue_style("invoice_grid_style");
    wp_enqueue_script("invoice_grid_script");
    wp_enqueue_script( 'multisite_loadmore' );
    ?>

    <script type="text/javascript">

    jQuery(document).ready(function(){
        var hash = window.location.hash;
        var strNewString = hash.replace(/\#/g,'');
        if(hash != ''){
            
            jQuery('.woocommerce-result-count').fadeOut(150);
            jQuery('.woocommerce-ordering').fadeOut(150);
            jQuery('.woocommerce ul.products').fadeOut(150);
            jQuery('.pager_wrapper').fadeOut(150);

            setTimeout(function(){
                script_hash_product_script( hash, strNewString );
            },600);
            
        }else{
            console.log('Not Found!');
        }
    });


    function script_hash_product_script($hash, strNewString){

        var $this_ = jQuery('body').find('.vvd_filter .'+strNewString);
        jQuery('body').find('.vvd_filter .'+strNewString).addClass('active');

        var fillter = $this_.attr('data-filter');
        var data_name = $this_.attr('data-name');

        $this_.text('loading...');

        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'hash_product_ajax',
                fragment: $hash,
            },
            beforeSend: function () {
                jQuery('#result').html('Loading All '+ strNewString.charAt(0).toUpperCase() + strNewString.substr(1).toLowerCase() +' Products...');
            },
            success: function( response ) {
                jQuery('#vvd_preloader').fadeOut(150);
                // console.log(response);
                if ( response.success ) {
                    jQuery('.woocommerce-result-count').fadeOut(150);
                    jQuery('.woocommerce-ordering').fadeOut(150);
                    jQuery('.woocommerce ul.products').fadeOut(150);
                    jQuery('.pager_wrapper').fadeOut(150);
                    console.log(response.success);
                    jQuery('#result').html('');
                    jQuery('#result').html( response.html );
                    $this_.text( data_name );
                }else{
                    console.log(response.data.message);
                }
            }   
        });

    }

</script>

    <?php
    $shop_url = wc_get_page_permalink( 'shop' );
    echo '<div class="vvd_filter">
        <a href="javascript:void(0);" class="button product_type_simple newreleases" data-filter="newreleases" data-name="New Releases">New Releases</a>
        <a href="javascript:void(0);" class="button product_type_simple previews" data-filter="previews" data-name="Previews">Previews</a>
        <a href="'.$shop_url .'" class="button product_type_simple all" data-filter="all" data-name="All">All</a>
    </div>';
}


add_action( 'wp_ajax_hash_product_ajax', 'va_hash_product_ajax' );
add_action( 'wp_ajax_nopriv_hash_product_ajax', 'va_hash_product_ajax' );
function va_hash_product_ajax(){
    $fragment = str_replace("#","", $_POST['fragment'] );

    switch ($fragment) {
        case "all":
            $result = do_shortcode('[products limit="20" columns="4"]');
            $json['success'] = true;
            $json['html'] = $result ;
            echo json_encode($json);
            break;
        case "previews":
            $result = do_shortcode('[vvd_preview_product]');
            $json['success'] = true;
            $json['html'] = $result ;
            echo json_encode($json);
            break;
        case "newreleases":
            $result = do_shortcode('[vvd_weekly_invoice per_page="12" order="" orderby="" include="books-graphic-novels" exclude="0-dark-nights-metal-tp" show_add_to_cart="No"]');
            $json['success'] = true;
            $json['html'] = $result ;
            echo json_encode($json);
            break;
        default:
            $json['error'] = true;
            $json['message'] = 'Your favorite color is neither red, blue, nor green!' ;
            echo json_encode($json);
    }


    wp_die();
}

add_shortcode('vvd_preview_product', 'vvd_preview_product_shortcode' );
function vvd_preview_product_shortcode(){
    ob_start();

    wp_enqueue_style("invoice_grid_style");
    wp_enqueue_script("invoice_grid_script");
    wp_enqueue_script( 'multisite_loadmore' );

    $status = array('publish', 'draft');
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 20,
            'post_status' => $status,
            'meta_query' => array(
            array(
                'key' => 'available',
                'value' => array(date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('28 days'))),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            ),
        )
    );

    // vivid( $args );

        $prod_query = new WP_Query($args);

        if ( ! $prod_query->have_posts() ) {
            echo "No Product found!";
            //exit();
        }else{
            ?>
            
        <form name="preview_prod_filter" id="preview_prod_filter" method="get" style="margin: 15px 11px;">
            <section>
               <h5>Filtered by the Diamond Number :</h5>
                <select name="diamond_filter" class="diamond_filter">
                    <option value="" disabled selected="selected">Please Select Diamond Number</option>
                    <?php
                    $diamond = array('JAN14', 'FEB14', 'MAR14', 'APR14', 'MAY14', 'JUN14', 'JUL14', 'AUG14', 'SEP14', 'OCT14', 'NOV14','DEC14', 'JAN15', 'FEB15', 'MAR15', 'APR15', 'MAY15', 'JUN15', 'JUL15', 'AUG15', 'SEP15', 'OCT15', 'NOV15','DEC15', 'JAN16', 'FEB16', 'MAR16', 'APR16', 'MAY16', 'JUN16', 'JUL16', 'AUG16', 'SEP16', 'OCT16', 'NOV16','DEC16', 'JAN17', 'FEB17', 'MAR17', 'APR17', 'MAY17', 'JUN17', 'JUL17', 'AUG17', 'SEP17', 'OCT17', 'NOV17','DEC17', 'JAN18', 'FEB18', 'MAR18', 'APR18', 'MAY18', 'JUN18', 'JUL18', 'AUG18', 'SEP18', 'OCT18', 'NOV18','DEC18', 'JAN19', 'FEB19', 'MAR19', 'APR19', 'MAY19', 'JUN19', 'JUL19', 'AUG19', 'SEP19', 'OCT19', 'NOV19','DEC19', 'JAN20', 'FEB20'/*, 'MAR20', 'APR20', 'MAY20', 'JUN20', 'JUL20', 'AUG20', 'SEP20', 'OCT20', 'NOV20','DEC20'*/);
                    $i = 1;
                    foreach ($diamond as $key_value) {
                        ?>
                        <option value="<?php echo $key_value; ?>" ><?php echo $key_value; ?></option>
                        <?php  
                        $i++; 
                    }
                    ?>
                </select>
                <input type="button" name="" class="button preview_submit_search_form" value="Filter"><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="filter_loader" style="display:none;">
                <input type="hidden" name="action" class="" value="func_diamond_filter">
            </section>
        </form>

            <section id="weeleky_cards" class="weeleky_cards">
              <?php
              while ( $prod_query->have_posts() ): $prod_query->the_post();
                  $product = new WC_Product(get_the_ID());

                  $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
                  $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

                  $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

                  $aws_img = fetch_image_from_AWS( get_the_ID() );
                  if(!empty($aws_img)){
                    $image_info = getimagesize( $prod_img ); 
                    $prod_img = $aws_img;

                  }else{
                    $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
                  }
                  
                  ?>


                  <article>
                      <div class="product_column product-block" data-dimond="<?php echo $dimond_no; ?>">
                        <a href="<?php  echo get_permalink(get_the_ID()); ?>">
                          <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('<?php echo $prod_img; ?>')">
                                <div style="display: none;"><?php vivid($image_info); ?></div>
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

                          </div>
                      </div>
                  </article>

                  <?php
              endwhile;

            ?>
          </section>


        <?php

        if ( $prod_query->max_num_pages > 1 ) {
            echo '<div id="misha_loadmore" class="misha_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">More previews</div>';
          } 

    }
    return ob_get_clean();
}

/**
 * This code should be added to functions.php of your theme
 **/
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');

function custom_default_catalog_orderby() {
     return 'date'; // Can also use title and price
}

/* Get All Image From AWS */

function fetch_image_from_AWS( $post_id ) {
    global $post;

    $path = get_post_meta($post->ID ,"Image Path", true);
    $image_path = get_post_meta($post->ID ,"image_path", true);

    if(!empty($path)){
      $pro = $path;
    }else if($image_path){
      $pro = $image_path;
    }else{
        $pro = get_post_meta($post->ID ,"Image-Path", true);
    }
      
    $pro = str_replace("diamondcomics.com","",$pro);
    $s3_image = substr($pro, 6);

    require(get_stylesheet_directory().'/aws/vendor/autoload.php');
     $s3 = new Aws\S3\S3Client([
      'region'  => 'us-east-2',
      'version' => 'latest',
      'credentials' => [
        'key'    => 'AKIAWAJYLDONJ4G3V3NJ',
        'secret' => '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',
      ]
    ]); 
    $bucket = 'darksidecomics';
    $filename  = "previews/".$s3_image;
    $info = $s3->doesObjectExist($bucket, $filename);

    $zip_filename  = "zip-image/".$s3_image;
    $zip_file = $s3->doesObjectExist($bucket, $zip_filename);

    if($info)
    {
        $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$filename;
        $image_info = getimagesize( $thumbnail ); 
        $width = $image_info[0];
        $height = $image_info[1];
        if( $width == 600 && $height == 900){
            $final_thumbnail = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
        }else{
            $final_thumbnail = $thumbnail;
        }

    }

    if($zip_file)
    {
        $thumbnail = 'https://s3.us-east-2.amazonaws.com/darksidecomics/'.$zip_filename;
        $image_info = getimagesize( $thumbnail ); 
        $width = $image_info[0];
        $height = $image_info[1];

        if( $width == '600' && $height == '900'){
            $final_thumbnail = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
        }else{
            $final_thumbnail = $thumbnail;
        }
    }

    // make filter magic happen here... 
    return $final_thumbnail; 
}

add_action('wp_ajax_func_diamond_filter', 'vvd_func_diamond_filter');
add_action('wp_ajax_nopriv_func_diamond_filter', 'vvd_func_diamond_filter');
function vvd_func_diamond_filter() {

    global $wp;
    $current_url = home_url();

    $diamond_filter = $_POST['diamond_filter'];
    $args = array(
        'posts_per_page' => 12,
        'post_type' => array('product'/* ,'product_variation' */),
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'desc',
        'meta_query' => array (
            'relation' => 'AND',
             'import_invoice_date'   => array (
                'key'       => 'diamond_number',
                'value'     => $diamond_filter,
                'compare'   => 'LIKE',
            ),
            // array(
            //     'key' => 'available',
            //     'value' => array(date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('28 days'))),
            //     'compare' => 'BETWEEN',
            //     'type' => 'DATE'
            // ),
        ),
        
    );

    
    $prod_query = new WP_Query($args);
    $all_post = $prod_query->posts;

    // vivid( count($all_post) );
    // vivid( $all_post );exit;
    if ( ! $prod_query->have_posts() ) {
        $json['error'] = true;
        $json['message'] =  __("No Product found!.", "tamberra") ;
        echo json_encode($json);
        die();
    } 

    
    $html = '';
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

        $add_to_cart = '?add-to-cart='.get_the_ID();
        $price = $product->get_price_html();
        $loader = "/wp-admin/images/spinner.gif";


        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta( get_the_ID(), '_regular_price', true);
        $sale = get_post_meta( get_the_ID(), '_sale_price', true);

        if($sale) : 
            $price = '<del>'.$currency.$price.'</del>'.$currency.$sale;    
        elseif($price) :
            $price = $currency.$price;   
        endif; 

        $html .='<article>
            <div class="product_column product-block">
            <a href="'.get_permalink(get_the_ID()).'">
                <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('.$prod_img.')">
                    
                        <div class="vyte-overlay"></div>
                        
                    
                </div></a>
                <div class="product-info">
                    <div class="prod_links">
                        <a href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
                    </div>

                    <div class="prod_price">
                        '.$price.'
                    </div>

                </div>
            </div>
        </article>';

        
    endwhile; 
       if ( $prod_query->max_num_pages > 1 ) {
          $loadmore .= '<div id="misha_loadmore" class="misha_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">More previews</div>';
        } 
       $json['success'] = true;
        $json['html'] = $html;
        $json['loadmore'] = $loadmore;
        echo json_encode($json);

    wp_die();
}

add_action('wp_footer','func_add_hidden');
function func_add_hidden(){
    ?>
    <input type="hidden" name="canBeLoaded" id="canBeLoaded" value="true">
    <?php
}
