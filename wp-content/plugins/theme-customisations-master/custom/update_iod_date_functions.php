<?php 

add_action( 'admin_menu', 'fileupload_admin_menu');
function fileupload_admin_menu(){
    #adding as main menu
    add_menu_page( 'Update Product', 'Update Product', 'manage_options', 'update_prod_page','update_prod_html', 'dashicons-media-spreadsheet', 6  );
}

function update_prod_html(){

    wp_enqueue_style( 'woocommerce_admin_styles' );
    wp_enqueue_style( 'jquery-ui-style' );

    $args = array(
      'post_type' => 'product',
      'posts_per_page' => -1,
      'post_status' => $status,
      'meta_query'=>array(
        array(
          'key' => 'product_title_update',
          'value' => 'Yes',
          'compare' => 'NOT EXISTS'
        ),
      ),
    );

    $query = new WP_Query( $args );
    $all_post = $query->posts;

    $total_product = count( $all_post );
    ?>
    <style type="text/css">
        .progress.hide {
            opacity: 0;
            transition: opacity 1.3s;
        } 
        .progress {
            background: #4CAF50 !important;
            display: block;
            height: 20px;
            text-align: center;
            transition: width .3s;
            margin-top: 10px;
            width: 0;
            color: #ffffff;
            font-size: 16px;

        }
        .import_response{
            border: 1px solid #cfcfcf;
            padding: 10px;
            margin-top: 10px;
            max-height: 400px;
            overflow: auto;
            background:lavender;
        }
    </style>

    <hr>
    <div class="input-text-wrap" id="title-wrap">
        <label for="title" style="margin-bottom: 4px;vertical-align: middle;display: inline-block;">IOD Date :- </label>
        <input type="text" name="post_title" id="set_iod_date_input" autocomplete="off" style="margin-bottom: 10px;">
    </div>
    <button id="update_product_btn" class="button button-primary button-large" data-total="<?php echo $total_product; ?>"> Update Product </button>

    <!-- Import Progress -->
    <div id="import-widgets" class="metabox-holder" style="margin: 5px 15px 2px;">
        <div class="postbox-container-">
            <div class="meta-box-sortables ui-sortable">
                <div class="progress va-importer-progress">
                    <span class="va-progress" style="vertical-align: middle;"></span>
                </div>
                <div class="import_response" style="display: none;"></div>
            </div>
        </div>
    </div>
    <!-- HTML -->

    <?php  wp_enqueue_script( 'jquery-ui-datepicker' ); ?>
    <script type="text/javascript">

        jQuery(document).ready(function(){

            
            jQuery(function($){
                jQuery("#set_iod_date_input").datepicker( { dateFormat: "yy-mm-dd" } );
            });
        
            jQuery('#set_iod_date_input').on('focus',function(){
                jQuery(this).css('border-color','#ddd');
            });

            jQuery('#update_product_btn').on('click',function(){
                // console.log('Bingo..');
                var $start_pos  = 1;
                var iod_date = jQuery('#set_iod_date_input').val();
                var total_product = jQuery('#update_product_btn').attr('data-total');
                // var total_product = 3;
                var $this = jQuery(this);

                if(iod_date != ''){
                    $this.text('Please wait ...');
                    jQuery('#set_iod_date_input').css('border-color','#ddd');
                    setTimeout(function(){
                        $this.text('Start updating...');
                        $this.attr('disabled','disabled');
                        update_product_change_import($start_pos, iod_date, total_product);
                    },500);
                    
                }else{
                    jQuery('#set_iod_date_input').css('border-color','red');
                }
            });


        });

        function update_product_change_import($pos,iod_date,total_product){
            jQuery.ajax({
                url: '<?php echo admin_url( "admin-ajax.php" );?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'vvd_update_product_data',
                    startpos: $pos,
                    iod_date: iod_date,
                    total_product: total_product,
                },
                success: function( response ) {
                    // console.log(response);
                    if ( response.success ) {
                        var newpos = response.data.pos;
                        var iod_date = response.data.iod_date;
                        var total_product = response.data.total_product;
                        var message = response.data.message;

                        if(newpos == 'done'){
                            jQuery('.import_message').hide();
                            jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
                            jQuery('.import_response').prepend(message +'</br>' );
                            jQuery('.va-progress').html( 'Done' );
                            jQuery('#update_product_btn').text('Done');
                            jQuery('#update_product_btn').removeAttr('disabled');
                        }else{
                            
                            jQuery('.import_response').show();
                            jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );
                            jQuery('.va-importer-progress').css('width', '100%' );
                            jQuery('.va-progress').html( response.data.percentage+'%' );
                            jQuery('.import_response').prepend(message +'</br>' );
                            update_product_change_import(newpos,iod_date,total_product);
                        }
                    }else{
                        alert(response.data.message);
                    }
                }   
            });

        }
    </script>

    <?php
}

add_action( 'wp_ajax_vvd_update_product_data', 'va_vvd_update_product_data' );
add_action( 'wp_ajax_nopriv_vvd_update_product_data', 'va_vvd_update_product_data' );
function va_vvd_update_product_data(){

    $startpos = $_POST['startpos'];
    $iod_date = $_POST['iod_date'];
    $total_product = $_POST['total_product'];
    //$startpos = 0;

    $status = array('publish', 'draft');
    $args = array(
      'post_type' => 'product',
      'posts_per_page' => 1,
      'post_status' => $status,
      'paged' => $startpos,
      'meta_query'=>array(
        array(
          'key' => 'product_title_update',
          'value' => 'Yes',
          'compare' => 'NOT EXISTS'
        ),
      ),
    );

    $query = new WP_Query( $args );
    $all_post = $query->posts;

    $total_data = $total_product;
    $json     = json_encode($all_post);
    $configData = json_decode($json, true);
   
    $d = date("j-M-Y H:i:s");
    $final_data = $configData[0];

    $end_pos = $startpos+1;
    $total_percentage = vvd_get_percent_complete($total_data,$end_pos);

    if($total_data <= $startpos){
        $message = '['.$d.'] - Done';
        wp_send_json_success(
            array(
                'pos' => 'done',
                'percentage' => vvd_get_percent_complete($total_data,$end_pos),
                'message' => $message,
              
            )
        );
    }else{

        $message = '['.$d.'] - '.vvd_update_iod_date( $final_data, $iod_date );
        
        wp_send_json_success(
            array(
                'pos' => $end_pos,
                'iod_date' => $iod_date,
                'percentage' => vvd_get_percent_complete($total_data,$end_pos),
                'total_product' => $total_product,
                'message' => $message,
            )
        );
    }
   

    wp_die();
}

function vvd_get_percent_complete($total_row,$end_pos) {
  //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
  return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
}

function vvd_update_iod_date($args, $iod_date){
    $exist = post_exists($args['post_title'],'','','product');
    
    if($exist == 0){
        
        $result = $args['post_title'].' - '.$iod_date.'- #Insert Post';
        
    }else{
        vvd_update_product_log( '---- Start ----' );
        vvd_update_product_log( $args['post_title'] );
        $product_id = $args['ID'];

        $new_title = preg_replace("/\([^)]+\)/","",$args['post_title']);
        $title_string = $new_title;
        
        $search = preg_replace("/\([^)]+/","", $title_string);
        $search1 = strtr($search, array('(' => '', ')' => ''));
        $final_title = preg_replace('/\s+/', ' ', $search1);

        $data_arry = array(
            'ID'           => $product_id,
            'post_title' => trim($final_title),
        );

        vvd_update_product_log( 'New Title --> '.trim($final_title) ); 
        /*Remove (content) & Update Product title*/
        wp_update_post( $data_arry );

        /* Update in Post Meta */
        update_exist_product_meta( $product_id, $iod_date);

        /* Update in subsite */
        // update_meta_in_subsite( $args, $iod_date );
        

        vvd_update_product_log( '---- End ----' );

        $result = $args['post_title'].' IOD Date - '.$iod_date.' - '.trim($final_title).'- #UPdate Product IOD Date';
      
    }
    return $result;
}

function update_meta_in_subsite( $args, $iod_date ){
    /* Insert in subsite */
    $subsites = get_sites();

    foreach( $subsites as $subsite ) {
        $subsite_id = get_object_vars( $subsite )["blog_id"];

        vvd_update_product_log( '---- '.$subsite_id.' ----' ); 

        $subsite_name = get_blog_details( $subsite_id )->blogname;

          switch_to_blog( $subsite_id );

          $exist = post_exists($args['post_title'],'','','product');
          /*if post with the same slug exists, do nothing*/

          if($exist == 0){

            $result = '';

          }else{
      
            $product_id = $exist;
            vvd_update_product_log( '---- '.$product_id.' ----' );

            $new_title = preg_replace("/\([^)]+\)/","",$args['post_title']);
            $title_string = $new_title;
            $search = preg_replace("/\([^)]+/","", $title_string);
            $search1 = strtr($search, array('(' => '', ')' => ''));
            $final_title = preg_replace('/\s+/', ' ', $search1);

            $data_arry = array(
                'ID'           => $product_id,
                'post_title' => trim($final_title),
            );
           
            /*Remove (content) & Update Product title*/
            wp_update_post( $data_arry );

            $result = $product_id;
            /* Update in Post Meta */
            update_exist_product_meta($product_id,$iod_date);
            
          }
          restore_current_blog();

    }
    return $result;
}

function update_exist_product_meta( $post_id,$iod_date){
    $product_id = $post_id;

    /*Update IOD Date*/
    update_post_meta( $product_id, 'vvd_product_IOD_date', $iod_date );

    $reg_price = get_post_meta( $post_id, '_regular_price', true );
    if( !empty( $reg_price )){
        //$reg_price = 35;
        update_post_meta( $product_id, '_price', $reg_price );
        update_post_meta( $product_id, '_regular_price', $reg_price );
    }

    $image_path = get_post_meta( $post_id, 'Image Path', true );

    if( !empty( $image_path )){
        //$reg_price = 35;
        update_post_meta( $product_id, 'Image-Path', $image_path );
        delete_post_meta( $product_id , 'Image Path', $image_path);
    }

    $available_date = get_post_meta( $product_id, 'Available', true );
    if( !empty( $available_date )){
        //$reg_price = 35;
        update_post_meta( $product_id, 'available', $available_date );
        // delete_post_meta( $product_id , 'Available', $available_date);
    }
    
    $sku = get_post_meta( $product_id, '_sku',  true );
    if ( stripos($sku, "STK") !== false ) {
        // $sku_val =  "True STK";
        $sku = $sku;
    }elseif( stripos($sku, "VIVID") !== false ){
        // $sku_val =  "True VIVID";
        $sku = str_replace("VIVID","STL",$sku);
    }elseif( stripos($sku, "STL") !== false ){
        // $sku_val =  "True STL";
        $sku = $sku;
    }else{
        // $sku_val =  "False";
        $sku = 'STL'.$sku;
    }

    update_post_meta( $product_id, '_sku',  $sku );
    update_post_meta( $product_id, 'product_title_update', 'Yes' );



    // 'Image Path',

    $meta_keys = array(
        
        'Diamond Number',
        'Stock Number',
        'Series Code',
        'Issue Number',
        'Issue Sequence Number',
        'Price',
        'Publisher',
        'UPC Number',
        'Cards Per Pack',
        'Pack Per Box',
        'Box Per Case',
        'Discount Code',
        'Increment',
        'Print Date',
        'FOC Vendor',
        'SRP',
        'Category',
        'Mature',
        'Adult',
        'OA',
        'CAUT1',
        'CAUT2',
        'CAUT3',
        'RESOL',
        'Note Price',
        'Order Form Notes',
        'Page',
        'FOC Date',
        'Preview HTML',
        'Genre',
        'Brand Code',
        'Writer',
        'Artist',
        'Covert Artist',
        'Variant Desc',
        'Short ISBN No',
        'EAN No',
        'Colorist',
        'Alliance SKU',
        'Volume Tag',
        'Parent Item No Alt',
        'Offered Day',
        'Max Issue',
        'Cost',
        'StockID',
    );

    foreach ( $meta_keys as $k ) {
        $meta_value = get_post_meta( $product_id, $k, true );

        if ( $meta_value ) {
            
            $old_key = $k;
            $lower = strtolower( $old_key );
            $new_key = str_replace(" ","_",$lower);
            $final_key = $new_key;

            vvd_update_product_log( '--- '.$product_id.' ---- '.$meta_value.' - '.$old_key.' - '.$lower.' - '.$final_key.' ----' );

            // Migrate the meta to the new name

            update_post_meta($product_id, $final_key, $meta_value );  // add the meta with the new name
            delete_post_meta( $product_id, $old_key );        // delete the old meta
             
        }else{

            $old_key = $k;
            $lower = strtolower( $old_key );
            $new_key = str_replace(" ","_",$lower);
            $final_key = $new_key;


            vvd_update_product_log( '--- '.$product_id.' -- In Else -- '.$old_key.' - '.$lower.' - '.$final_key.' ----' );
            update_post_meta( $product_id, $final_key, $meta_value );  // add the meta with the new name
            delete_post_meta( $product_id, $old_key ); 
        }

    }

}

add_action('init','vivid_setup_log_dir' );
function vivid_setup_log_dir(){
    $dir = plugin_dir_path(__FILE__);
    $plugin_url = plugin_dir_url(__FILE__);
    $log_dir = $dir."log/" ;

    if ( ! is_dir( $log_dir ) ) {
      wp_mkdir_p( $log_dir, 0777 );

      if ( $file_handle = @fopen( trailingslashit( $product_dir ) .'update_log.log', 'w' ) ) {
        fwrite( $file_handle, 'testing' );
        fclose( $file_handle );
      }

    }
}

function vvd_update_product_log($str) {

    $d = date("j-M-Y H:i:s");
    $dir = plugin_dir_path(__FILE__);
    $plugin_url =  plugin_dir_url(__FILE__);
    $product_dir = $dir."log" ;
    error_log('['.$d.']'. $str.PHP_EOL, 3, $product_dir."/update_log.log");
}