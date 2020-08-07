<?php

/**
 * Export Data to CSV file
 * Could be used in WordPress plugin or theme
 */

add_action('admin_footer','add_new_import_button');
function add_new_import_button(){
?>

<script>
	jQuery(function($){
		var url ='<?php echo admin_url( 'admin.php?' ) ?>action=download_csv&_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>';
		jQuery("body.toplevel_page_subscription_import .wrap h2").after('<div style="display: block;margin-top: 15px;"><a href="'+url+'" class="page-title-action">Export to CSV</a></div>');
	});
</script>
<?php 
} 

add_action( 'admin_init', 'csv_export');
function csv_export() 
{
    if ( isset($_GET['action'] ) && $_GET['action'] == 'download_csv' )  {
    // Check for current user privileges 
    if( !current_user_can( 'manage_options' ) ){ return false; }
    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }
    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_csv' ) ) {
        die( 'Security check error' );
    }
    
    ob_start();
    $domain = $_SERVER['SERVER_NAME'];
    $filename = $domain . '-dailySummary.csv';
    
    $header_row = array(
    	'No',
        'Series ID',
        'User Email',
        'Subscription ID',
        'Created Date',
        'Add/Remove',
    );
    $data_rows = array();
    
    $args = array(
    	'post_type'	=> 'shop_order',
    	'posts_per_page'	=>-1,
				'post_status' 		=> 'all',//array( 'wc-on-hold', 'wc-active' ),
				'meta_query'	=> array(
					array(
						'key'	=> 'order_has_subscription_product',
						'value'	=> 'yes'
					)
				),
			);
    $orders = new WP_Query($args);
    $order_posts = $orders->posts;

    $sub_arr = array();
    $i = 1;

    foreach ( $order_posts as  $order_values ){
			$sku_arr = array();
			$arr1 = array();
			$user_email_arr = array();
			$date_arr = array();
			$sub_ids_arr = array();
			$sku = array();
			$orderid = $order_values->ID;

    		$parent_product_ids = get_post_meta( $orderid , 'parent_product_ids' , true );

    		$user_id = get_post_meta($orderid, '_customer_user', true);

    		$user = get_user_by( 'ID', $user_id );
    		
    		$user_email = $user->user_email;
    		
    		$create_date = get_the_date( 'F j, Y', $orderid );
    		

    		$args = array( 
    			'order_id'	=> $orderid,
    		);


    		$subscription    = wcs_get_subscriptions( $args );

    		foreach ( $subscription as $subscription_id => $subscription ){
    			
    			$arr[$i]['subscription_ID'] = $subscription_id;
    			$Subscription_ID = $subscription_id;
    		}
    		
    		foreach ($parent_product_ids as $value) {
    			# code...
    			$product_id = $value;
    			$parent_sku = get_post_meta( $product_id, '_sku', true );

    			$sku_arr[] = $parent_sku;
    			$parent_sku_save = implode(",",$sku_arr);

			    $no = $i;
	        	$Series_ID =  $parent_sku;
	            $User_Email = $user_email;
	            $Created_Date = $create_date;
	            $Add_Remove = '';

	            $row = array(
		        	$no,
		            $Series_ID,
		            $User_Email,
		            $Subscription_ID,
		            $Created_Date,
		            $Add_Remove,
		        );
		        $data_rows[] = $row;
    		}
    		$i++;
		}

    
    $fh = @fopen( 'php://output', 'w' );
    fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
    //header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    //header( 'Content-Description: File Transfer' );
    //header('Content-Transfer-Encoding: binary');
    //header('Content-Encoding: UTF-8');// 'accept-charset' => 'ISO-8859-1'
    header( 'Content-type: text/csv; charset = UTF-8' );
    header( "Content-Disposition: attachment; filename={$filename}" );
    //header( 'Expires: 0' );
    //header( 'Pragma: public' );
    fputcsv( $fh, $header_row );
    foreach ( $data_rows as $data_row ) {
        fputcsv( $fh, $data_row );
    }
    fclose( $fh );
    
    ob_end_flush();
    
    die();
    }
}


add_action('init','setup_subscription_upload_dir');
function setup_subscription_upload_dir(){
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir']."/subscriptions/" ;
    if ( ! is_dir( $upload_dir ) ) {
        wp_mkdir_p( $upload_dir, 0777 );

       /* if ( $file_handle = @fopen( trailingslashit( $upload_dir ) .'custom_log.log', 'w' ) ) {
           fwrite( $file_handle, 'testing' );
           fclose( $file_handle );
        }*/

    }

}

function vivid_log($str) {

    $d = date("j-M-Y H:i:s");
    $upload_dir = wp_upload_dir();
    error_log('['.$d.']'. $str.PHP_EOL, 3, $upload_dir['basedir']."/subscriptions/custom_log.log");
}


function popupcomicshops_add_cron_interval_CSV( $schedules ) {
 
    $schedules['every_three_minutes'] = array(
            'interval'  => 180,
            'display'   => __( 'Every 3 Minutes', 'textdomain' )
    );
    
    return $schedules;
}
add_filter( 'cron_schedules', 'popupcomicshops_add_cron_interval_CSV' );

add_action('wp', 'activateMe');
function activateMe() {
    if ( !wp_next_scheduled( 'daily_check' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'every_three_minutes', 'daily_check');
    }
}

add_action('daily_check', 'function_dailySummary');
//add_action('init', 'function_dailySummary');
function function_dailySummary() {

    

	//$domain = $_SERVER['SERVER_NAME'];
    $today = date("Y-m-d");
    $filename = 'dailySummary-'.$today.'.csv';
    //$filename = 'Order-' . time() . '.csv';

    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir']."/subscriptions/";

    $header_row = array(
        'No',
        'Series ID',
        'User Email',
        'Subscription ID',
        'Created Date',
        'Add/Remove',
    );
    $data_rows = array();
    
    $args = array(
        'post_type' => 'shop_order',
        'posts_per_page'    =>-1,
                'post_status'       => 'all',//array( 'wc-on-hold', 'wc-active' ),
                'meta_query'    => array(
                    array(
                        'key'   => 'order_has_subscription_product',
                        'value' => 'yes'
                    )
                ),
            );
    $orders = new WP_Query($args);
    $order_posts = $orders->posts;

    $sub_arr = array();
    $i = 1;

    foreach ( $order_posts as  $order_values ){
            $sku_arr = array();
            $arr1 = array();
            $user_email_arr = array();
            $date_arr = array();
            $sub_ids_arr = array();
            $sku = array();
            $orderid = $order_values->ID;

            $parent_product_ids = get_post_meta( $orderid , 'parent_product_ids' , true );

            $user_id = get_post_meta($orderid, '_customer_user', true);

            $user = get_user_by( 'ID', $user_id );
            
            $user_email = $user->user_email;
            
            $create_date = get_the_date( 'F j, Y', $orderid );
            

            $args = array( 
                'order_id'  => $orderid,
            );


            $subscription    = wcs_get_subscriptions( $args );

            foreach ( $subscription as $subscription_id => $subscription ){
                
                $arr[$i]['subscription_ID'] = $subscription_id;
                $Subscription_ID = $subscription_id;
            }
            
            foreach ($parent_product_ids as $value) {
                # code...
                $product_id = $value;
                $parent_sku = get_post_meta( $product_id, '_sku', true );

                $sku_arr[] = $parent_sku;
                $parent_sku_save = implode(",",$sku_arr);

                $no = $i;
                $Series_ID =  $parent_sku;
                $User_Email = $user_email;
                $Created_Date = $create_date;
                $Add_Remove = '';

                $row = array(
                    $no,
                    $Series_ID,
                    $User_Email,
                    $Subscription_ID,
                    $Created_Date,
                    $Add_Remove,
                );
                $data_rows[] = $row;
            }
            $i++;
        }

    $csv = "No,Series ID,User Email,Subscription ID,Created Date,Add/Remove \n";
    foreach ( $data_rows as $data_row ) {
        $csv .= $data_row[0].','.$data_row[1].','.$data_row[2].','.$data_row[3].','.$data_row[4].','.$data_row[5]."\n";
    }

    
    $fh = @fopen (trailingslashit( $upload_dir ).$filename ,'w');
    fwrite ($fh,$csv);
    fclose ($fh);

    /*$to = 'vivid.sunnypatel@gmail.com';
    $subject = 'Cron Check';
    $body = trailingslashit( $upload_dir ).$filename;
    $headers = array('Content-Type: text/html; charset=UTF-8');
     
    wp_mail( $to, $subject, $body, $headers );*/

}