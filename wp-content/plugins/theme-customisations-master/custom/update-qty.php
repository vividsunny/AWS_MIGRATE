<?php 

add_action( 'admin_menu', 'stock_qty_admin_menu');
function stock_qty_admin_menu(){
    #adding as sub menu
    add_submenu_page( 'woocommerce', 'Update Stock', 'Update Stock', 'manage_options', 'update_stock_qty', 'update_qty_html' ); 
}

function update_qty_html(){
	?>
	    <div class="input-text-wrap" id="title-wrap">
	        <label for="title" style="margin-bottom: 4px;vertical-align: middle;display: inline-block;">Set Stock QTY :- </label>
	        <input type="text" name="post_title" id="set_stock_qty" autocomplete="off" style="margin-bottom: 10px;">
	    </div>
	    <button id="update_stock_qty" class="button button-primary button-large"> Update </button>

	    <script type="text/javascript">

	        jQuery(document).ready(function(){

	        	jQuery('#set_stock_qty').on('focus',function(){
	                jQuery(this).css('border-color','#ddd');
	            });

	        	jQuery('#update_stock_qty').on('click',function(){
	                // console.log('Bingo..');
	                var $start_pos  = 1;
	                var qty = jQuery('#set_stock_qty').val();
	                var $this = jQuery(this);

	                if(qty != ''){
	                    $this.text('Please wait ...');
	                    jQuery('#set_stock_qty').css('border-color','#ddd');
	                    setTimeout(function(){
	                        $this.text('Start updating...');
	                        $this.attr('disabled','disabled');
	                        update_product_available(qty);
	                    },500);
	                    
	                }else{
	                    jQuery('#set_stock_qty').css('border-color','red');
	                }
	            });
	        });

	        function update_product_available($qty){
	            jQuery.ajax({
	                url: '<?php echo admin_url( "admin-ajax.php" );?>',
	                type: 'POST',
	                dataType: 'json',
	                data: {
	                    action : 'vvd_update_product_qty_data',
	                    qty: $qty,
	                },
	                success: function( response ) {
	                    // console.log(response);
	                    if ( response.success ) {
	                     	jQuery('#update_stock_qty').text('Done');
	                     	jQuery('#update_stock_qty').removeAttr('disabled');
	                    }else{
	                        alert(response.data.message);
	                    }
	                }   
	            });

        	}
    	</script>
	<?php
    
}

add_action( 'wp_ajax_vvd_update_product_qty_data', 'va_vvd_update_product_qty_data' );
add_action( 'wp_ajax_nopriv_vvd_update_product_qty_data', 'va_vvd_update_product_qty_data' );
function va_vvd_update_product_qty_data(){

	
	global $wpdb;
	$qty = $_POST['qty'];
	
	$prefix = $wpdb->prefix;

	$table_name = $prefix.'postmeta';

	// $sql = "Update woo_postmeta Set meta_value = $qty Where meta_key = \'_stock\'";

	$sql = ("UPDATE $table_name SET meta_value='".$qty."' WHERE meta_key='_stock'");
	$updated = $wpdb->query($sql);

	if ( false === $updated ) {
		// There was an error.
		$message =  __("Something Wrong! Please Try Again!", "");
		$json['error'] = true;
		$json['message'] = $message;
		echo json_encode($json);
		wp_die();
    	
	} else {
		// No error. You can check updated to see how many rows were changed.
		$json['success'] = true;
		echo json_encode($json);
		wp_die();
	}

	wp_die();
}