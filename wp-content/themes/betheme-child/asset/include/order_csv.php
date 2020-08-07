<?php
add_action('init','setup_transactions_upload_dir');
function setup_transactions_upload_dir(){
	$upload_dir = wp_upload_dir();
	$upload_dir = $upload_dir['basedir']."/transactions/" ;
	if ( ! is_dir( $upload_dir ) ) {
		wp_mkdir_p( $upload_dir, 0777 );
	}
}

add_action('wp', 'order_csv_activateMe');
function order_csv_activateMe() {
    if ( !wp_next_scheduled( 'order_csv_check' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'every_three_minutes', 'order_csv_check');
    }
}

add_action('order_csv_check', 'vvd_function_daily_order_csv');
//add_action('init', 'vvd_function_daily_order_csv');
function vvd_function_daily_order_csv() {

	$today = date("Y-m-d");
    
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir']."/transactions/";


    $header_row = array(
    	'Order ID',
        'Order Status',
        'Order Date',
        'Order Total',
        'Order Currency',
        'Customer Name',
        'Customer`s Billing Email',
        'Cart Discount',
        'Cart Discount Tax',
        'Order Shipping',
        'Order Subscription',
        'Item ID',
        'Product ID',
        'Product Name',
        'Item quantity',

    );
    $data_rows = array();

    $args = array(
    	'post_type'	       => 'shop_order',
    	'posts_per_page'   => -1,
    	'post_status'      => 'all',
    );
    $orders = new WP_Query($args);
    $order_posts = $orders->posts;

    $i = 0;
    foreach( $order_posts as $key => $value ) {
        $prod_ids = array();
        $prod_names = array();
        $prod_qty = array();
        
        /* Order ID */
        $order_id = $value->ID;

        /* Get Order Data*/
        $order = wc_get_order( $order_id );
        $status = $order->post_status;
        $order_date = $order->order_date;

        /* Order Meta */
        $order_meta = get_post_meta($order_id);
        $order_total = $order_meta['_order_total'][0];
        $order_currency = $order_meta['_order_currency'][0];
        $_customer_user = $order_meta['_customer_user'][0];
        $author_obj = get_user_by( 'id', $_customer_user );
        $cust_name   = $author_obj->user_login;
        $email      = $author_obj->user_email;

        $_billing_email = $order_meta['_billing_email'][0];
        $_cart_discount = $order_meta['_cart_discount'][0];
        $_cart_discount_tax = $order_meta['_cart_discount_tax'][0];
        $_order_shipping = $order_meta['_order_shipping'][0];
        $_order_subscription = $order_meta['_order_subscription'][0];

        /* Order Items */
        $items = $order->get_items();
        foreach ( $items as $item_id => $item_data ) {
            $i_id = $item_id;
            $product_id = $item_data["product_id"];
            $prod_name = $item_data["name"];
            $prod_qty = $order->get_item_meta($item_id, '_qty', true);

            /*$prod_ids[] = $product_id;
            $prod_names[]  = $prod_name;
            $prod_qty[] = $order->get_item_meta($item_id, '_qty', true);
            
            $product_id = implode(",",$prod_ids);
            $prod_name = implode(",",$prod_names);
            $p_qty = implode(",",$prod_qty);*/

            $_row = array(
                $order_id,
                $status,
                $order_date,
                $order_total,
                $order_currency,
                $cust_name,
                $_billing_email,
                $_cart_discount,
                $_cart_discount_tax,
                $_order_shipping,
                $_order_subscription,
                $i_id,
                $product_id,
                $prod_name,
                $prod_qty,
            );
            $data_rows[] = $_row;
        }


        /*$_row = array(
            $order_id,
            $status,
            $order_date,
            $order_total,
            $order_currency,
            $_customer_user,
            $_billing_email,
            $_cart_discount,
            $_cart_discount_tax,
            $_order_shipping,
            $_order_subscription,
            $i_id,
            $product_id,
            $prod_name,
            $p_qty,
        );
        $data_rows[] = $_row;*/

        $i++;
    }

    $blog_id = get_current_blog_id();
    $blog_details = get_blog_details( $blog_id ); 
    $domain = $blog_details->domain;
    $d = date("j-M-Y H:i:s");
    $filename = $domain.'_'.$d.'.csv';
    

    $order_csv = "Order ID,Order Status,Order Date,Order Total,Order Currency,Customer Name,Customer`s Billing Email,Cart Discount,Cart Discount Tax,Order Shipping,Order Subscription,Item ID,Product ID,Product Name,Item quantity \n";
    foreach ( $data_rows as $data_row ) {
        $order_csv .= $data_row[0].','.$data_row[1].','.$data_row[2].','.$data_row[3].','.$data_row[4].','.$data_row[5].','.$data_row[6].','.$data_row[7].','.$data_row[8].','.$data_row[9].','.$data_row[10].','.$data_row[11].','.$data_row[12].','.$data_row[13].','.$data_row[14]."\n";
    }

    
    $fh = @fopen (trailingslashit( $upload_dir ).$filename ,'w');
    fwrite ($fh,$order_csv);
    fclose ($fh);
}