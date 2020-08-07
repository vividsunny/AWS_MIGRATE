<?php 

add_action( 'admin_menu', 'delete_product_admin_menu');
function delete_product_admin_menu(){
    #adding as main menu
    add_menu_page( 'Delete Product', 'Delete Product', 'manage_options', 'delete_prod_page','delete_prod_html', 'dashicons-media-spreadsheet', 6  );
}

function delete_prod_html(){

    wp_enqueue_style( 'woocommerce_admin_styles' );
    wp_enqueue_style( 'jquery-ui-style' );

    global $wpdb;

    $blog_id = get_current_blog_id();

    if( $blog_id === 1 ){
        $tbl_name = 'wptr_posts';
    }else{
        $tbl_name = 'wptr_'.$blog_id.'_posts';
    }

    $querystr = "SELECT a.`ID`, a.`post_title`, a.`post_type`, a.`post_status`
        FROM $tbl_name AS a
        INNER JOIN (
            SELECT post_title, MIN( id ) AS min_id
            FROM $tbl_name
            WHERE post_type = 'product'
            AND post_status = 'publish'
            GROUP BY post_title
            HAVING COUNT( * ) > 1
        ) AS b ON b.`post_title` = a.`post_title`
        AND b.`min_id` <> a.`id`
        AND a.`post_type` = 'product'
        AND a.`post_status` = 'publish'";
    $posts_found = $wpdb->get_results($querystr, OBJECT);
    $total_product = count( $posts_found );
    
    // $data_json = json_encode($posts_found, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

    $dir = plugin_dir_path(__FILE__);
    $plugin_url = plugin_dir_url(__FILE__);
    $json_dir = $dir."log" ;

    $temp_name = "delete";
	$json_file_dir = $json_dir."/".$temp_name.'.json'; 
	$json_file_ = $plugin_url."log/".$temp_name.'.json'; 
	$json_cat = json_encode($posts_found);

	chmod($json_file_dir,0777);
	file_put_contents($json_file_dir, $json_cat);

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
    
    <button id="delete_product_btn" class="button button-primary button-large" data-total="<?php echo $total_product; ?>" data-product_data='<?php echo $json_file_; ?>'> Delete Product </button>

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

            jQuery('#delete_product_btn').on('click',function(){
                // console.log('Bingo..');
                var $start_pos  = 0;
                var $total_product = jQuery('#delete_product_btn').attr('data-total');
                var $product_data = jQuery('#delete_product_btn').attr('data-product_data');
                // var total_product = 3;
                var $this = jQuery(this);

                if($product_data != ''){
                    $this.text('Please wait ...');
                    // jQuery('#set_iod_date_input').css('border-color','#ddd');
                    setTimeout(function(){
                        $this.text('Start Deleting Duplicate Product ...');
                        $this.attr('disabled','disabled');
                        delete_duplicate_product_process( $start_pos, $total_product, $product_data );
                    },500);
                    
                }else{
                    // jQuery('#set_iod_date_input').css('border-color','red');
                    console.log('In Else');
                }
            });


        });

        function delete_duplicate_product_process($pos, $total_product, $product_data){

            jQuery.ajax({
                url: '<?php echo admin_url( "admin-ajax.php" );?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'vvd_delete_duplicate_product_data',
                    startpos: $pos,
                    total_product: $total_product,
                    product_data: $product_data,
                    
                },
                success: function( response ) {
                    // console.log(response);
                    if ( response.success ) {
                        var newpos = response.data.pos;
                        var product_data = response.data.product_data;
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
                            delete_duplicate_product_process(newpos, total_product, product_data);
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

add_action( 'wp_ajax_vvd_delete_duplicate_product_data', 'va_vvd_delete_duplicate_product_data' );
add_action( 'wp_ajax_nopriv_vvd_delete_duplicate_product_data', 'va_vvd_delete_duplicate_product_data' );
function va_vvd_delete_duplicate_product_data(){

    $startpos = $_POST['startpos'];
    $product_data = $_POST['product_data'];
    $total_product = $_POST['total_product'];
    

    // $json 	= 	json_encode($posts_found);

    $total_data = $total_product;

    $json_data = file_get_contents( $product_data );
    $configData = json_decode($json_data, true);
   	

    $d = date("j-M-Y H:i:s");

    // debug( $configData );
    $final_data = $configData[ $startpos ];

    // debug( $final_data );

    $end_pos = $startpos + 1;
    $total_percentage = vvd_delete_product_percent_complete($total_data,$end_pos);

    if($total_data <= $startpos){
        $message = '['.$d.'] - Done';
        wp_send_json_success(
            array(
                'pos' => 'done',
                'percentage' => vvd_delete_product_percent_complete($total_data,$end_pos),
                'message' => $message,
              
            )
        );
    }else{

        $message = '['.$d.'] - '.vvd_delete_duplicate_product_with_meta_date( $final_data );
        
        wp_send_json_success(
            array(
                'pos' => $end_pos,
                'product_data' => $product_data,
                'percentage' => vvd_delete_product_percent_complete($total_data,$end_pos),
                'total_product' => $total_product,
                'message' => $message,
            )
        );
    }
   

    wp_die();
}

function vvd_delete_product_percent_complete( $total_row, $end_pos ) {
  //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
  return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
}

function vvd_delete_duplicate_product_with_meta_date( $args ){
	
    /* Delete Product ID */
    $delete_post = $args['ID'];

    /* Get all Product meta key */
    $myvals = get_post_meta( $delete_post );
    foreach($myvals as $key => $val)  {

        /* Delete All meta key and value */
        // delete_post_meta($article->ID, $key);
        
    }

    /* Delete Product */
    // wp_delete_post( $delete_post );

    $result = $delete_post.' --- '.$args['post_title'].' - #Delete Product';
    
    return $result;
}