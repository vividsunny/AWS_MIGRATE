<?php 
	wp_enqueue_style('woocommerce_admin_styles');
	wp_enqueue_script( 'selectWoo' );
	wp_enqueue_style( 'select2' );
	wp_enqueue_style( 'subscription-style' );


	/*$args = array(
		'meta_key' => '_user_subscription_series_id',
		'meta_compare' => 'EXISTS',
	);
	$user_query = new WP_User_Query($args);

	
	echo '<pre>';
	print_r($user_query->get_results());
	echo '</pre>';*/
?>
<div class="ax_container">
	<div class="ax_series_container">
		<div class="ax_series_search_container">
			<select name="ax_search_series_inpt" class="ax_search_series_inpt" id="ax_search_series_inpt">
				<option value="">Select Series</option>
			</select>
			<button type="button" name="ax_search_series_btn" class="ax_search_series_btn">Find Series</button>
		</div>
		<div class="ax_series_list_container">
			
		</div>
	</div>
</div>

<div class="ax_popup_mask ax_popup_hidden ax_popup_custom" id="ax_customer_search_popup">
	<div class="ax_popup_container">
		<div class="ax_popup_header">
			<p class="ax_popup_header_title">Add User</p>
			<a href="javascript:void(0);" class="ax_close_popup"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<hr class="ax_popup_seperator">
		<div class="ax_popup_body">
			<div class="ax_customer_popup_search">
				<input type="hidden" id="add_series_id">
				<select name="ax_customer_popup_search_inpt" class="ax_customer_popup_search_inpt" id="ax_customer_popup_search_inpt">
					<option value="">Select User</option>
				</select>
				<button type="button" name="ax_search_customer_btn" class="ax_search_customer_btn" id="add_customer_btn">Add User</button>
			</div>
		</div>
	</div>
</div>

<div class="ax_popup_mask ax_popup_hidden ax_popup_custom" id="ax_customer_view_popup">
	<div class="ax_popup_container">
		<div class="ax_popup_header">
			<p class="ax_popup_header_title">Customer List</p>
			<a href="javascript:void(0);" class="ax_close_popup"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<hr class="ax_popup_seperator">
		<div class="ax_popup_body">
			<input type="hidden" id="view_series_id">
			<div class="ax_customer_popup_list ">

				<?php for($i=1; $i<=10; $i++){ ?>
					<!-- <div class="ax_customer_item_container">
						<div class="ax_customer_list_seg_1">
							<label class="ax_customer_id"><?php echo $i; ?></label>
						</div>
						<div class="ax_customer_list_seg_2">
							<label class="ax_customer_name">Lucifer</label>
						</div>
						<div class="ax_customer_list_seg_3">
							<button type="button" name="ax_popup_remove_btn" class="ax_popup_remove_btn">Delete</button>
						</div>
					</div> -->
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(function($){
		/*simple multiple select*/
		var ajaxurl = '<?php echo admin_url( "admin-ajax.php"); ?>';

		/*multiple select with AJAX search*/
		$('#ax_search_series_inpt').select2({
			allowClear: true,
			ajax: {
				url: ajaxurl, /*// AJAX URL is predefined in WordPress admin*/
				dataType: 'json',
				delay: 250, /*// delay in ms while typing when to perform a AJAX search*/
				data: function (params) {
					return {
						q: params.term, /*// search query*/
						action: 'getsubscriptionID' /*// AJAX action for admin-ajax.php*/
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						/*data is the array of arrays, and each of them contains ID and the Label of the option*/
						$.each( data, function( index, text ) { /*// do not forget that "index" is just auto incremented value*/
							options.push( { id: text[0], text: text[1] } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			minimumInputLength: 3 /*// the minimum of symbols to input before perform a search*/
		});

		/* USER */
		/*multiple select with AJAX search*/
		$('#ax_customer_popup_search_inpt').select2({
			allowClear: true,
			ajax: {
				url: ajaxurl, /*// AJAX URL is predefined in WordPress admin*/
				dataType: 'json',
				delay: 250, /*// delay in ms while typing when to perform a AJAX search*/
				data: function (params) {
					return {
						q: params.term, /*// search query*/
						action: 'comics_getUserID' /*// AJAX action for admin-ajax.php*/
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {

						/*data is the array of arrays, and each of them contains ID and the Label of the option*/
						$.each( data, function( index, text ) { /*// do not forget that "index" is just auto incremented value*/
							options.push( { id: text[0], text: text[1] } );
						});

					}
					return {
						results: options
					};
				},
				cache: true
			},
			minimumInputLength: 3 /*// the minimum of symbols to input before perform a search*/
		});
		/* END */
	});

	jQuery(document).ready(function(){
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery('.ax_series_list_container').hide();
		jQuery(document).on('click', '.ax_search_series_btn', function(){
			var $this = jQuery(this);
			$this.text('Loading..');
			var parent_product_id = jQuery('#ax_search_series_inpt').val();
			
			if(parent_product_id != ""){
				console.log(parent_product_id);

				//var ajaxurl = shortcode_ajax.ajax_url;
				var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			      jQuery.post(
			        ajaxurl, 
			        {
			          'action'   : 'getParentSubData',
			          'parent_product_id' : parent_product_id,
			        }, 
			        function(response){
			        	$this.text('Find Series');
			          //console.log(response);
			          jQuery('.ax_series_list_container').show();
			          jQuery('.ax_series_list_container').html('');
			          jQuery('.ax_series_list_container').html(response);
			          /*if(response.success){
			            console.log('Success');
			            jQuery('#sub_btn').text(response.message);
			          }*/

			        });
			      return false;
			}
		});

		/* Add User */
		jQuery(document).on('click', '.ax_search_customer_btn', function(){
			var parent_product_id = jQuery('#hidden_parent_product_id').val();
			var userID  = jQuery('#ax_customer_popup_search_inpt').val();

			console.log(parent_product_id);
			console.log(userID);
			/*if(parent_product_id != ""){
				console.log(parent_product_id);

				//var ajaxurl = shortcode_ajax.ajax_url;
				var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			      jQuery.post(
			        ajaxurl, 
			        {
			          'action'   : 'comics_subscribe_data',
			          'parent_product_id' : parent_product_id,
			          'userID' : userID,
			        }, 
			        function(response){

			          console.log(response);
			          
			        });
			      return false;
			}*/
		});

		jQuery(document).on('click', '.ax_add_series_to_customer', function(){
			var series_id = jQuery(this).attr('data-id');
			jQuery('#ax_customer_search_popup #add_customer_btn').text('Add user');
			jQuery('#ax_customer_search_popup #add_series_id').val(series_id);
			jQuery('#ax_customer_search_popup').removeClass('ax_popup_hidden');
		});

		jQuery(document).on('click', '.ax_view_customers', function(){
			var series_id = jQuery(this).attr('data-id');
			jQuery('#ax_customer_view_popup #view_series_id').val(series_id);
			var $this = jQuery(this);
			$this.text('Loading..');
			jQuery.post(
				ajaxurl,
				{
					action : 'view_series_subscribers',
					series_id : series_id,
				},function(response){
					$this.text('View');
					jQuery('#ax_customer_view_popup .ax_customer_popup_list').html('');
					jQuery('#ax_customer_view_popup .ax_customer_popup_list').html(response);
					jQuery('#ax_customer_view_popup').removeClass('ax_popup_hidden');
				}
			);
			
		});
		jQuery('#ax_customer_view_popup').on('click','.sub_remove_user',function(){
			var series_id,user_id,$this;
			series_id = jQuery(this).attr('data-seriesid');
			user_id = jQuery(this).attr('data-user_id');
			$this = jQuery(this);
			$this.text('Loading..');
			jQuery.post(
				ajaxurl,
				{
					action : 'subscribtion_remove_',
					user_id : user_id,
					series_id : series_id,
				},function(response){
					$this.closest('.ax_customer_item_container').remove();
				}
			);
		});
		jQuery(document).on('click', '.ax_close_popup', function(){
			jQuery(this).closest('.ax_popup_custom').addClass('ax_popup_hidden');
		});
		jQuery('#add_customer_btn').click(function(){
			var series_id, user_id ,$this;
			$this = jQuery(this);
			series_id = jQuery('#ax_customer_search_popup #add_series_id').val();
			user_id = jQuery('#ax_customer_popup_search_inpt').val();
			//alert(series_id+','+user_id);
			if(user_id == ''){
				alert('Please select customer');
			}else{
				$this.text('Loading..');
				jQuery.post(
					ajaxurl,
					{
						action : 'add_customer_into_series',
						parent_product_id : series_id,
						user_id : user_id,
					},function(reponse){
						var data = jQuery.parseJSON(reponse);
						if(data.success){
							$this.text('added.!');
							
							setTimeout(function(){
								jQuery('#ax_customer_popup_search_inpt').val('').trigger('change');
								$this.text('Add user');
							},500);
						}
					}
				);
			}

		});
	});
</script>