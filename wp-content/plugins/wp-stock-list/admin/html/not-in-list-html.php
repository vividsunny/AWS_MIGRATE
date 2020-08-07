<?php

  /*
   Add CSS File */
  // wp_enqueue_style("bootstrap.min");
  wp_enqueue_style( 'invoice_style' );

  global $wpdb;


  $get_meta_query = "SELECT COUNT(*) FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE 'item_in_list'";
  $total_product = "SELECT COUNT(*) FROM `{$wpdb->prefix}posts` WHERE `post_type` LIKE '%product%'";

  $get_meta_query_total = $wpdb->get_var( $get_meta_query );
  $total_product_total = $wpdb->get_var( $total_product );

  // vivid( $get_meta_query_total );
  // vivid( $total_product_total );
  $total_record = $total_product_total - $get_meta_query_total;
  // exit;
  $args = array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'order'    => 'DESC',
		// 'page' => 1,
		'meta_query'     => array(
				// 'relation' => 'OR',
				array(
			        'key'       => 'item_in_list',
			        'value'   	=> 'in_list_file',
			        'compare'   => 'NOT EXISTS',
			    )
		),

	);

  	// $total_products = count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => '-1') ) );

	// $query = new WP_Query( $args );
	// $count = $query->found_posts;

	// $exist_posts = array();
	// if ( $query->have_posts() ) {
	// 	$exist_posts = $query->posts;
	// }

	// vivid( $total_products ); exit; 

?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<div class="containAll">
	<div class="containLoader">
		
		<div class="innerText">
			<button class="import_btn button button-primary button-next" id="import_btn" data-pos="1" data-total="<?php echo $total_record; ?>">Not in list</button>
		</div>
	</div>
</div>

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

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#import_btn').on('click',function(){
			console.log('Click');
			var cnt = jQuery(this).attr('data-pos');
			var total = jQuery(this).attr('data-total');
			console.log( cnt );
			console.log( total );

			if( total != ''){
				jQuery(this).text('Loading...');
			    script_wp_not_list( cnt, total );
			}
		});
	});

	function script_wp_not_list(pos,total){
		jQuery.ajax({
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				action : 'not_in_list_import_script',
				startpos: pos,
				total_record : total,
			},
			success: function( response ) {
				jQuery('.containAll').css('display','none');
				if ( response.success ) {
					var newpos = response.data.pos;
					var total_record = response.data.total_record;
					var message = response.data.message;

					if(newpos == 'done'){
						jQuery('.import_message').hide();
						jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
						jQuery('.import_response').prepend(message +'</br>' );
						jQuery('.va-progress').html( 'Done' );
					}else{
						//jQuery('.import_message').hide();
						jQuery('.import_response').show();
								// background: -webkit-linear-gradient(left, green, green 0%, black 100%, black);
						jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );        
						jQuery('.va-importer-progress').css('width', '100%' );
						jQuery('.va-progress').html( response.data.percentage+'%' );
						jQuery('.import_response').prepend(message +'</br>' );
						script_wp_not_list(newpos,total_record);
					}
				}else{
					alert(response.data.message);
				}
			}   
		});

	}

</script>
<?php
	wp_enqueue_script( 'bootstrap.min' );
?>
