<?php

/**
 * Export Data to CSV file
 * Could be used in WordPress plugin or theme
 */

add_action('admin_footer','add_new_import_button');
function add_new_import_button(){
?>

<script>
	/*jQuery(function($){
		var url ='<?php //echo admin_url( 'admin.php?' ) ?>action=download_csv&_wpnonce=<?php //echo wp_create_nonce( 'download_csv' )?>';
		jQuery("body.toplevel_page_subscription_subscribers .wrap h2").after('<div style="display: block;margin-top: 15px;"><a href="'+url+'" class="page-title-action">Export to CSV</a></div>');
	});*/
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
        'Series Code',
        'User Email',
        'Date',
        'Status',
    );
    $data_rows = array();
 
    global $wpdb;

    $blog_id = get_current_blog_id();
    $sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id";

    $product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : 'series_id';
    if ( ! empty( $_GET['product_name'] ) ) {

        $myposts   = get_post( $product_name );
        $product_id = $myposts->ID;

        $series_code = get_post_meta( $product_id, '_sku', true );
        $sql .= " AND `series_id` = $series_code";
    }

    $user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : 'user_id';
    if ( ! empty( $_GET['user_id'] ) ) {

        $sql .= " AND `user_id` = $user_id";
    }
        
    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    if( !empty( $result ) ){
        $row = 1;

        foreach ($result as $key => $value) {

            $series_id  = $value['series_id'];
            $user_id  = $value['user_id'];
            $chk_status = $value['status'];

            $author_obj = get_user_by( 'id', $user_id );
            $username   = $author_obj->user_login;
            $email      = $author_obj->user_email;

            if( $chk_status == trim('active')){
                $time      = $value['create_date'];
            }else{
                $time      = $value['delete_data'];
            }

            $no = $row;
            $Series_code =  $series_id;
            $User_Email = $email;
            $Created_Date = $time;
            $Status = $value['status'];

            $_row = array(
                $no,
                $Series_code,
                $User_Email,
                $Created_Date,
                $Status,
            );
            $data_rows[] = $_row;

            $row++;
        }
    }

    //vivid( $data_rows );

    
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

    $schedules['every_four_hours'] = array(
            'interval'  => 14400,
            'display'   => __( 'Every 4 Hours', 'textdomain' )
    );
    
    return $schedules;
}
add_filter( 'cron_schedules', 'popupcomicshops_add_cron_interval_CSV' );

add_action('wp', 'activateMe');
function activateMe() {
    if ( !wp_next_scheduled( 'daily_check' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'every_four_hours', 'daily_check');
    }
}

add_action('daily_check', 'function_dailySummary');
//add_action('init', 'function_dailySummary');
function function_dailySummary() {

    global $wpdb;
    

    $today = date("Y-m-d");
    
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir']."/subscriptions/";

    $header_row = array(
        'No',
        'Series Code',
        'User Email',
        'Date',
        'Status',
    );
    $data_rows = array();
    

    $blog_id = get_current_blog_id();
    $sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id";

    $blog_details = get_blog_details( $blog_id ); 
    $domain = $blog_details->domain;
    $d = date("j-M-Y H:i:s");
    $filename = $domain.'_'.$d.'.csv';
    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    if( !empty( $result ) ){
        $row = 1;

        foreach ($result as $key => $value) {

            $series_id  = $value['series_id'];
            $user_id  = $value['user_id'];
            $chk_status = $value['status'];

            $author_obj = get_user_by( 'id', $user_id );
            $username   = $author_obj->user_login;
            $email      = $author_obj->user_email;

            if( $chk_status == trim('active')){
                $time      = $value['create_date'];
            }else{
                $time      = $value['delete_data'];
            }

            $no = $row;
            $Series_code =  $series_id;
            $User_Email = $email;
            $Created_Date = $time;
            $Status = $value['status'];

            $_row = array(
                $no,
                $Series_code,
                $User_Email,
                $Created_Date,
                $Status,
            );
            $data_rows[] = $_row;

            $row++;
        }
    }

    $csv = "No,Series ID,User Email,Date,Status \n";
    foreach ( $data_rows as $data_row ) {
        $csv .= $data_row[0].','.$data_row[1].','.$data_row[2].','.$data_row[3].','.$data_row[4]."\n";
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