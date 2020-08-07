<?php

  /*
   Add CSS File */
  // wp_enqueue_style("bootstrap.min");
  wp_enqueue_style( 'invoice_style' );

  $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
  $size  = size_format( $bytes );

if ( isset( $_FILES['import'] ) ) {

	$filetype = array(
		'csv' => 'text/csv',
		'txt' => 'text/plain',
	);

	$overrides = array(
		'test_form' => false,
		'mimes'     => $filetype,
	);

	$import = $_FILES['import']; /*WPCS: sanitization ok, input var ok.*/
	$upload = wp_handle_upload( $import, $overrides );
	$error  = false;

	if ( isset( $upload['error'] ) ) {
		echo $upload['error'];
		$error = true;
	} else {
		echo '<p class="va-processing-file"><b>Wait we are processing your file.</b></p>';
	}

	/*Construct the object array.*/
	$object = array(
		'post_title'     => basename( $upload['file'] ),
		'post_content'   => $upload['url'],
		'post_mime_type' => $upload['type'],
		'guid'           => $upload['url'],
		'context'        => 'import',
		'post_status'    => 'private',
	);

	/*Save the data.*/
	$id = wp_insert_attachment( $object, $upload['file'] );

	/*
	* Schedule a cleanup for one day from now in case of failed
	* import or missing wp_import_cleanup() call.
	*/
	wp_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $id ) );

	if ( ! $error ) {
		$file_url = $upload['file'];

		/* $file_url = 'C://xampp//htdocs/woo-demo/wp-content/uploads/2020/02/truall.csv'; */

		// $file_url = 'https://carinsurent.com/wp-content/uploads/2019/12/Anual-Renewal.csv';
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){

				var start_pos  = 0;
				var url        = '<?php echo $file_url; ?>';
				console.log('URL--> '+url);

				jQuery('body').find('.notice').css('display','none');
				jQuery('body').find('.updated').css('display','none');
				jQuery('#upload-widgets').css('display','none');
				if( url != ''){
				    script_wp_stock_list( start_pos, url );
				}

			});
		</script>
		<?php

	}
}
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div id="upload-widgets" class="metabox-holder" style="margin: 5px 15px 2px;">
	<div class="postbox-container-">
		<div class="meta-box-sortables ui-sortable">
			<div id="dashboard_activity" class="postbox">
				<h2 class="hndle ui-sortable-handle">
					<span>User Import</span></br>
				</h2>
				<div class="inside">
					<div class="col-md-12">
						<div class="woocommerce-progress-form-wrapper">
							<form class="wc-progress-form-content va-customer-import va-importer" enctype="multipart/form-data" method="post">
								<section>
									<table class="form-table woocommerce-importer-options">
										<tbody>
											<tr>
												<th scope="row">
													<label for="upload">
														<?php esc_html_e( 'Choose a file :', 'woocommerce' ); ?>
													</label>
												</th>
												<td>
													<?php
													if ( ! empty( $upload_dir['error'] ) ) {
														?>
														<div class="inline error">
															<p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'woocommerce' ); ?></p>
															<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
														</div>
														<?php
													} else {
														?>
														<div class="file-field">
															<div class="d-flex justify-content-center">
															<div class="btn btn-mdb-color btn-rounded float-left">
																<span>Choose file</span>
																<input type="file" id="upload" name="import" size="25">
															</div>
														</div>
													</div>


													<!-- <input type="file" id="upload" name="import" size="25" /> -->
													<input type="hidden" name="action" value="save" />
													<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
													<br>
													<small>
														<?php
														printf(
															/* translators: %s: maximum upload size */
															esc_html__( 'Maximum size: %s', 'woocommerce' ),
															esc_html( $size )
														);
														?>
													</small>
														<?php
													}
													?>
											</td>
										</tr>

										<tr class="woocommerce-importer-advanced hidden">
											<th>
												<label for="woocommerce-importer-file-url"><?php esc_html_e( 'Alternatively, enter the path to a CSV file on your server:', 'woocommerce' ); ?></label>
											</th>
											<td>
												<label for="woocommerce-importer-file-url" class="woocommerce-importer-file-url-field-wrapper">
													<code><?php echo esc_html( ABSPATH ) . ' '; ?></code><input type="text" id="woocommerce-importer-file-url" name="file_url" />
												</label>
											</td>
										</tr>
										<tr class="woocommerce-importer-advanced hidden">
											<th><label><?php esc_html_e( 'Delimiter', 'woocommerce' ); ?></label><br/></th>
											<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
										</tr>

										<tr class="woocommerce-importer-advanced hidden">
											<th><label><?php esc_html_e( 'Use previous column mapping preferences?', 'woocommerce' ); ?></label><br/></th>
											<td><input type="checkbox" id="woocommerce-importer-map-preferences" name="map_preferences" value="1" /></td>
										</tr>
									</tbody>
								</table>
							</section>
							<div class="wc-actions">

								<p><button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Submit', 'woocommerce' ); ?>" name="save_step"><?php esc_html_e( 'Submit', 'woocommerce' ); ?></button>
									<img id="csvloading" src="<?php echo home_url(); ?>/wp-admin/images/spinner.gif" style="vertical-align: -webkit-baseline-middle;">
									<?php wp_nonce_field( 'woocommerce-csv-importer' ); ?></p>

								</div>
							</form>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="containAll">
	<div class="containLoader">
		<div class="circleGroup circle-1"></div>
		<div class="circleGroup circle-2"></div>
		<div class="circleGroup circle-3"></div>
		<div class="circleGroup circle-4"></div>
		<div class="circleGroup circle-5"></div>
		<div class="circleGroup circle-6"></div>
		<div class="circleGroup circle-7"></div>
		<div class="circleGroup circle-8"></div>
		<div class="innerText">
			<p>Loading...</p>
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
	function script_wp_stock_list(pos,fileurl=''){
		jQuery.ajax({
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				action : 'stock_list_import_script',
				startpos: pos,
				file_url : fileurl,
			},
			success: function( response ) {
				jQuery('.containAll').css('display','none');
				if ( response.success ) {
					var newpos = response.data.pos;
					var fileurl = response.data.file_path;
					var message = response.data.message;

					if(newpos == 'done'){
						jQuery('.import_message').hide();
						jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
						jQuery('.import_response').prepend(message +'</br>' );
						jQuery('.va-progress').html( 'Done' );
						window.location.href = response.data.redirect;
						
					}else{
						//jQuery('.import_message').hide();
						jQuery('.import_response').show();
								// background: -webkit-linear-gradient(left, green, green 0%, black 100%, black);
						jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );        
						jQuery('.va-importer-progress').css('width', '100%' );
						jQuery('.va-progress').html( response.data.percentage+'%' );
						jQuery('.import_response').prepend(message +'</br>' );
						script_wp_stock_list(newpos,fileurl);
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
