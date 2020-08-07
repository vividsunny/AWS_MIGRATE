<?php 

add_action( 'admin_menu', 'fileupload_admin_menu');
function fileupload_admin_menu(){
    #adding as main menu
    add_menu_page( 'Available', 'Available', 'manage_options', 'available_page','available_html', 'dashicons-media-spreadsheet', 6  );
}

function available_html(){

    wp_enqueue_style( 'woocommerce_admin_styles' );
    wp_enqueue_style( 'jquery-ui-style' );

    $args = array(
      'post_type' => 'product',
      'posts_per_page' => -1,
      'post_status' => $status,
      'meta_query'=>array(
        array(
          'key' => '_wc_pre_orders_availability_datetime',
          'compare' => 'EXISTS'
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
                        update_product_available($start_pos, iod_date, total_product);
                    },500);
                    
                }else{
                    jQuery('#set_iod_date_input').css('border-color','red');
                }
            });


        });

        function update_product_available($pos,iod_date,total_product){
            jQuery.ajax({
                url: '<?php echo admin_url( "admin-ajax.php" );?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'vvd_update_available_data',
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
                            update_product_available(newpos,iod_date,total_product);
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

add_action( 'wp_ajax_vvd_update_available_data', 'va_vvd_update_available_data' );
add_action( 'wp_ajax_nopriv_vvd_update_available_data', 'va_vvd_update_available_data' );
function va_vvd_update_available_data(){

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
          'key' => '_wc_pre_orders_availability_datetime',
          'compare' => 'EXISTS'
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

        $message = '['.$d.'] - '.vvd_update_availablle_date( $final_data, $iod_date );
        
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

function vvd_update_availablle_date($args, $iod_date){
    $exist = post_exists($args['post_title'],'','','product');
    
    if($exist == 0){
        
        $result = $args['post_title'].' - '.$iod_date.'- #Insert Post';
        
    }else{
        
        $product_id = $args['ID'];

        /* Update in Post Meta */
        $avai_date =  update_available_exist_product_meta( $product_id );
        if( !empty( $avai_date )){
            $avai_date = date('Y-m-d\TH:i:s', $avai_date );
        }else{
            // echo 'else';
            $avai_date = 'else';
        }
        
        $result = $product_id.' - '.$args['post_title'].' Available Date - '.$avai_date.' - #UPdate Product';
      
    }
    return $result;
}

function update_available_exist_product_meta( $post_id ){
    $product_id = $post_id;

    $pre_orders = get_post_meta( $product_id, '_wc_pre_orders_availability_datetime', true );

    if( !empty( $pre_orders )){
        $avai_date = date('Y-m-d\TH:i:s', $pre_orders );
    }else{
        $avai_date = '';
    }
    update_post_meta( $product_id, 'available', $avai_date );
    
    return $pre_orders;
}
