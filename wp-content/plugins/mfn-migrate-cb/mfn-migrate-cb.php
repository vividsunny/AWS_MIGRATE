<?php
/*
Plugin Name: Muffin Builder & Theme Options Migrate Tool
Plugin URI: http://muffingroup.com
Description: Muffin Content Builder & Theme Options Migrate Tool. Works with Muffin Builder 3
Author: Pross
Author URI: http://muffingroup.com
Version: 3.1
*/

function mfn_migrate_menu() {
	add_submenu_page(
		'tools.php',
		'Muffin Builder & Theme Options Migrate Tool 3',
		'Mfn CB Migrate Tool',
		'edit_theme_options',
		'mfn_migrate_cb',
		'mfn_migrate_cb'
	);
}
add_action('admin_menu', 'mfn_migrate_menu');

function mfn_migrate_cb(){

	global $wpdb;

	$safety_limit = 10;

	if( key_exists( 'mfn_migrate_nonce',$_POST ) ) {
		if ( wp_verify_nonce( $_POST['mfn_migrate_nonce'], basename(__FILE__) ) ) {

			$old_url = stripslashes(htmlspecialchars($_POST['old']));
			$new_url = stripslashes(htmlspecialchars($_POST['new']));

			if( strlen($old_url) < $safety_limit || strlen($new_url) < $safety_limit ){

				echo '<p><strong>For your own safety please use URLs longer than '. $safety_limit .' characters !</strong></p>';

			} elseif( strpos( $old_url, 'http' ) !== 0 || strpos( $new_url, 'http' !== 0 )  ){

				echo '<p><strong>URLs must begin with http:// or https:// !</strong></p>';

			} else {


				// Theme Options -------------------------------------------------------

				$data = get_option( 'betheme' );

				if( $data && is_array( $data ) ){
					foreach( $data as $key => $option ){
						if( is_string( $option ) ){
							// variable type string only
							$data[$key] = str_replace( $old_url, $new_url, $option );
						}
					}
				}

				update_option( 'betheme', $data );


				// Muffin Builder -------------------------------------------------------

				$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta
					WHERE `meta_key` = 'mfn-page-items'
				" );

				if( is_array( $results ) ){

					// posts loop -----------------
					foreach( $results as $result_key=>$result ){

						$meta_id = $result->meta_id;

						$meta_value = @unserialize( $result->meta_value );

						// Builder 2.0 Compatibility
						if( $meta_value === false ){
							$meta_value = unserialize( call_user_func( 'base'.'64_decode', $result->meta_value ) );
						}

						// Loop | Sections ----------------
						if( is_array( $meta_value ) ){
							foreach( $meta_value as $sec_key => $sec ){

								// Loop | Section Attributes ----------------
								if( isset( $sec['attr'] ) && is_array( $sec['attr'] ) ){
									foreach( $sec['attr'] as $attr_key => $attr ){
										$attr = str_replace( $old_url, $new_url, $attr );
										$meta_value[$sec_key]['attr'][$attr_key] = $attr;
									}
								}

								// Builder 3.0 | Loop | Wraps ----------------
								if( isset( $sec['wraps'] ) && is_array( $sec['wraps'] ) ){
									foreach( $sec['wraps'] as $wrap_key => $wrap ){

										// Loop | Items ----------------
										if( isset( $wrap['items'] ) && is_array( $wrap['items'] ) ){
											foreach( $wrap['items'] as $item_key => $item ){

												// Loop | Fields ----------------
												if( isset( $item['fields'] ) && is_array( $item['fields'] ) ){
													foreach( $item['fields'] as $field_key => $field ) {

														if( $field_key == 'tabs' ) {
															// Tabs, Accordion, FAQ, Timeline

															// Loop | Tabs --------------------
															if( isset( $field ) && is_array( $field ) ){
																foreach( $field as $tab_key => $tab ){
																	$field = str_replace( $old_url, $new_url, $tab['content'] );
																	$meta_value[$sec_key]['wraps'][$wrap_key]['items'][$item_key]['fields'][$field_key][$tab_key]['content'] = $field;
																}
															}
														} else {
															// Default
															$field = str_replace( $old_url, $new_url, $field );
															$meta_value[$sec_key]['wraps'][$wrap_key]['items'][$item_key]['fields'][$field_key] = $field;
														}
													}
												}

											}
										}

									}
								}

								// Builder 2.0 | Loop | Items ----------------
								if( isset( $sec['items'] ) && is_array( $sec['items'] ) ){
									foreach( $sec['items'] as $item_key => $item ){

										// Loop | Fields ----------------
										if( isset( $item['fields'] ) && is_array( $item['fields'] ) ){
											foreach( $item['fields'] as $field_key => $field ) {

												if( $field_key == 'tabs' ) {
													// Tabs, Accordion, FAQ, Timeline

													// Loop | Tabs --------------------
													if( is_array( $field ) ){
														foreach( $field as $tab_key => $tab ){
															$field = str_replace( $old_url, $new_url, $tab['content'] );
															$meta_value[$sec_key]['items'][$item_key]['fields'][$field_key][$tab_key]['content'] = $field;
														}
													}
												} else {
													// Default
													$field = str_replace( $old_url, $new_url, $field );
													$meta_value[$sec_key]['items'][$item_key]['fields'][$field_key] = $field;
												}
											}
										}

									}
								}

							}
						}

// 						print_r($meta_value);

						$meta_value = call_user_func( 'base'.'64_encode', serialize( $meta_value ) );

						$wpdb->query( "UPDATE $wpdb->postmeta
							SET `meta_value` = '" . addslashes( $meta_value ) . "'
							WHERE `meta_key` = 'mfn-page-items'
							AND `meta_id`= " . $meta_id . "
						" );

					}
				}


				echo '<p><strong>All done. Have fun!</strong></p>';

			}
		} else {
			echo '<p><strong>Invalid Nonce !</strong></p>';
		}
	}

	?>
		<div class="wrap">

			<div id="icon-tools" class="icon32"></div>
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<br />

			<form action="" method="post">

				<input type="hidden" name="mfn_migrate_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />

				<label style="width:50px; display:inline-block;">Find</label>
				<input type="text" name="old" value="" placeholder="Old URL" style="width:300px;" />
				<br />

				<label style="width:50px; display:inline-block;">Replace</label>
				<input type="text" name="new" value="<?php echo home_url(); ?>" style="width:300px;" />

				<input type="submit" name="submit" class="button button-primary" value="Replace" />

        	</form>

		</div>
	<?php
}

?>
