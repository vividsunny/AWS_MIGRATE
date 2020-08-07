<?php

    class WOO_MSTORE_options_interface
        {
         
            var $licence;
         
            function __construct()
                {
                    
                    $this->licence          =   new WOO_MSTORE_licence();
                    
                    if (isset($_GET['page']) && $_GET['page'] == 'woo-ms-options')
                        {
                            add_action( 'init', array($this, 'options_update'), 1 );
                        }

                    add_action( 'network_admin_menu', array($this, 'network_admin_menu') );
                    if(!$this->licence->licence_key_verify())
                        {
                            add_action('admin_notices', array($this, 'admin_no_key_notices'));
                            add_action('network_admin_notices', array($this, 'admin_no_key_notices'));
                        }
                    
                }
                
            function __destruct()
                {
                
                }
            
            function network_admin_menu()
                {
                    $parent_slug    =   'settings.php';
                        
                    $hookID   = add_submenu_page($parent_slug, 'WooCommerce Multistore ', 'WooCommerce Multistore ', 'manage_options', 'woo-ms-options', array($this, 'options_interface'));
                        
                    add_action('load-' . $hookID , array($this, 'load_dependencies'));
                    add_action('load-' . $hookID , array($this, 'admin_notices'));
                    
                    add_action('admin_print_styles-' . $hookID , array($this, 'admin_print_styles'));
                    add_action('admin_print_scripts-' . $hookID , array($this, 'admin_print_scripts'));    
                }

		public function options_interface() {
			if ( ! $this->licence->licence_key_verify() ) {
				$this->licence_form();

				return;
			}

			if ( $this->licence->licence_key_verify() ) {
				$this->licence_deactivate_form();
			}

			global $WOO_MSTORE;
			$options = $WOO_MSTORE->functions->get_options();

			?>
			<div class="wrap">
				<div id="icon-settings" class="icon32"></div>
				<h2><?php _e( "General Settings", 'woonet' ) ?></h2>

				<form id="form_data" name="form" method="post">
					<br/>
					<table class="form-table">
						<tbody>

						<tr valign="top">
							<th scope="row">
								<select name="synchronize-stock">
									<option value="yes" <?php selected( 'yes', $options['synchronize-stock'] ); ?>><?php _e( "Yes", 'woonet' ) ?></option>
									<option value="no" <?php selected( 'no', $options['synchronize-stock'] ); ?>><?php _e( "No", 'woonet' ) ?></option>
								</select>
							</th>
							<td>
								<label><?php _e( "Always maintain stock synchronization for re-published products", 'woonet' ) ?>
									<span class='tips'
										  data-tip='<?php _e( "Stock updates either manually or checkout will also change other shops that have the product.", 'woonet' ) ?>'><span
												class="dashicons dashicons-info"></span></span></label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<select name="synchronize-trash">
									<option value="yes" <?php selected( 'yes', $options['synchronize-trash'] ); ?>><?php _e( "Yes", 'woonet' ) ?></option>
									<option value="no" <?php selected( 'no', $options['synchronize-trash'] ); ?>><?php _e( "No", 'woonet' ) ?></option>
								</select>
							</th>
							<td>
								<label><?php _e( "Trash the child product when the parent product is trashed", 'woonet' ) ?>
									<span class='tips'
										  data-tip='<?php _e( "Sync child product status when the parent product is trashed/untrashed/deleted.", 'woonet' ) ?>'><span
												class="dashicons dashicons-info"></span></span></label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<select name="sequential-order-numbers">
									<option value="yes" <?php selected( 'yes', $options['sequential-order-numbers'] ); ?>><?php _e( "Yes", 'woonet' ) ?></option>
									<option value="no" <?php selected( 'no', $options['sequential-order-numbers'] ); ?>><?php _e( "No", 'woonet' ) ?></option>
								</select>
							</th>
							<td>
								<label><?php _e( "Use sequential Order Numbers across multisite environment", 'woonet' ) ?></label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<select name="publish-capability">
									<option value="super-admin" <?php selected( 'super-admin', $options['publish-capability'] ); ?>><?php _e( "Super Admin", 'woonet' ) ?></option>
									<option value="administrator" <?php selected( 'administrator', $options['publish-capability'] ); ?>><?php _e( "Administrator", 'woonet' ) ?></option>
									<option value="shop_manager" <?php selected( 'shop_manager', $options['publish-capability'] ); ?>><?php _e( "Shop Manager", 'woonet' ) ?></option>
								</select>
							</th>
							<td>
								<label><?php _e( "Minimum user role to allow MultiStore Publish", 'woonet' ) ?></label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<select name="network-user-info">
									<option value="yes" <?php selected( $options['network-user-info'], 'yes' ); ?>><?php _e( "Yes", 'woonet' ) ?></option>
									<option value="no" <?php selected( $options['network-user-info'], 'no' ); ?>><?php _e( "No", 'woonet' ) ?></option>
								</select>
							</th>
							<td>
								<label><?php _e( 'Display all user info at all stores', 'woonet' ) ?></label>
							</td>
						</tr>

						</tbody>
					</table>

					<?php
						$blog_ids = $WOO_MSTORE->functions->get_active_woocommerce_blog_ids();
						if ( isset( $options['blog_tab_order'] ) ) {
							$blog_tab_order = array_filter( array_map( 'intval', $options['blog_tab_order'] ) );
							$blog_ids       = array_merge(
								array_intersect( $blog_tab_order, $blog_ids ),
								array_diff( $blog_ids, $blog_tab_order )
							);
						}
						if ( $blog_ids ) {
							echo '<h4>' . __( "Child product inherit Parent changes - Fields control", 'woonet' ) . '</h4>';
							echo '<div id="fields-control">';

								echo '<ul>';
									foreach ( $blog_ids as $index => $blog_id ) {
										switch_to_blog( $blog_id );

										$blog_name = get_bloginfo('name');

										$blog_ids[ $index ] = array( $blog_id, $blog_name );

										printf(
											'<li><a href="#tabs-%d">%s</a><input type="hidden" name="blog_tab_order[]" value="%d" /></li>',
											$blog_id,
											$blog_name,
											$blog_id
										);

										restore_current_blog();
									}
								echo '</ul>';

								foreach ( $blog_ids as $blog_data ) {
								    list( $blog_id, $blog_name ) = $blog_data;

									printf( '<div id="tabs-%d"><h3>%s options</h3>', $blog_id, $blog_name );

										echo '<table class="form-table"><tbody>';

											$option_name = 'child_inherit_changes_fields_control__title';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit title changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__description';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit description changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__short_description';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit short description changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__price';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit price changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__product_cat';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit product categories changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__product_tag';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit product tags changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__variations';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit product variations', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__category_changes';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit category image and description changes', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											$option_name = 'child_inherit_changes_fields_control__reviews';
											echo '<tr valign="top"><th scope="row">';
												printf(
													'<select name="%s"><option value="yes" %s>%s</option><option value="no" %s>%s</option></select>',
													"{$option_name}[{$blog_id}]",
													selected( $options[ $option_name ][ $blog_id ], 'yes', false ),
													__( 'Yes', 'woonet' ),
													selected( $options[ $option_name ][ $blog_id ], 'no', false ),
													__( 'No', 'woonet' )
												);
											echo '</th><td>';
												printf(
													'<label>%s<span class="tips" data-tip="%s"><span class="dashicons dashicons-info"></span></span></label>',
													__( 'Child product inherit product reviews.', 'woonet' ),
													__( "This works in conjunction with <b>Child product inherit Parent changes</b> being active on individual product page.", 'woonet' )
												);
											echo '</td></tr>';

											do_action( 'woo_mstore/options/options_output/child_inherit_changes_fields_control', $blog_id );

										echo '</tbody></table>';

									echo '</div>';
								}

							echo '</div>';
						}
					?>

					<?php do_action( 'woo_mstore/options/options_output' ); ?>

					<p class="submit">
						<input type="submit" name="Submit" class="button-primary"
							   value="<?php _e( 'Save Settings', 'woonet' ) ?>">
					</p>

					<?php wp_nonce_field( 'mstore_form_submit', 'mstore_form_nonce' ); ?>
					<input type="hidden" name="mstore_form_submit" value="true"/>

				</form>
			</div>
			<?php
		}
            
            function options_update()
                {
                    
                    if (isset($_POST['mstore_licence_form_submit']))
                        {
                            $this->licence_form_submit();
                            return;
                        }
                        
                    if (isset($_POST['mstore_form_submit']))
                        {
                            //check nonce
                            if ( ! wp_verify_nonce($_POST['mstore_form_nonce'],'mstore_form_submit') ) 
                                return;
                            
                            global $WOO_MSTORE;
                            $options    =   $WOO_MSTORE->functions->get_options();
                            
                            global $mstore_form_submit_messages;

                            $options['synchronize-stock']                           =   $_POST['synchronize-stock'];
                            $options['synchronize-trash']        = isset( $_POST['synchronize-trash'] ) && in_array( $_POST['synchronize-trash'], array('yes', 'no') )
                                                                   ? $_POST['synchronize-trash']
                                                                   : 'no';;
                            $options['sequential-order-numbers']                    =   $_POST['sequential-order-numbers'];
                            $options['publish-capability']                          =   $_POST['publish-capability'];
                            $options['network-user-info']        = isset( $_POST['network-user-info'] ) && in_array( $_POST['network-user-info'], array('yes', 'no') )
                                                                   ? $_POST['network-user-info']
                                                                   : 'yes';

                            $options['child_inherit_changes_fields_control__title']             = $_POST['child_inherit_changes_fields_control__title'];
                            $options['child_inherit_changes_fields_control__description']       = $_POST['child_inherit_changes_fields_control__description'];
                            $options['child_inherit_changes_fields_control__short_description'] = $_POST['child_inherit_changes_fields_control__short_description'];
                            $options['child_inherit_changes_fields_control__price']             = $_POST['child_inherit_changes_fields_control__price'];
                            $options['child_inherit_changes_fields_control__product_cat']       = $_POST['child_inherit_changes_fields_control__product_cat'];
                            $options['child_inherit_changes_fields_control__product_tag']       = $_POST['child_inherit_changes_fields_control__product_tag'];
                            $options['child_inherit_changes_fields_control__variations']        = $_POST['child_inherit_changes_fields_control__variations'];
                            $options['child_inherit_changes_fields_control__category_changes']  = $_POST['child_inherit_changes_fields_control__category_changes'];
                            $options['child_inherit_changes_fields_control__reviews']           = $_POST['child_inherit_changes_fields_control__reviews'];

                            $options['blog_tab_order'] = $_POST['blog_tab_order'];

                            $options    =   apply_filters('woo_mstore/options/options_save', $options);
                            
                            $WOO_MSTORE->functions->update_options($options);  
                            
                            $mstore_form_submit_messages[] = __('Settings Saved', 'woonet');
                            
                            //post processing
                            if($options['sequential-order-numbers'] ==  'yes')
                                {
                                    include_once(WOO_MSTORE_PATH . '/include/class.sequential-order-numbers.php');
                                    
                                    WOO_SON::network_update_order_numbers();
                                }
                                
                        }
            
                }

            function load_dependencies()
                {

                }
                
            function admin_notices()
                {
                    global $mstore_form_submit_messages;
            
                    if($mstore_form_submit_messages == '')
                        return;
                    
                    $messages = $mstore_form_submit_messages;
 
                          
                    if(count($messages) > 0)
                        {
                            echo "<div id='notice' class='updated fade'><p>". implode("</p><p>", $messages )  ."</p></div>";
                        }

                }

		public function admin_print_styles() {
			wp_enqueue_style( 'jquery-ui-ms', WOO_MSTORE_URL . '/assets/css/jquery-ui.css' );

			wp_enqueue_style( 'woosl-options', WOO_MSTORE_URL . '/assets/css/woosl-options.css');
		}

	    public function admin_print_scripts() {
		    $WC_url =   plugins_url() . '/woocommerce';
		    wp_enqueue_script( 'jquery-tiptip', $WC_url . '/assets/js/jquery-tiptip/jquery.tipTip.js' );

			wp_enqueue_script(
				'jquery-ms',
				WOO_MSTORE_URL . '/assets/js/jquery-3.3.1.min.js',
				array()
			);
			wp_enqueue_script(
				'jquery-ui-ms',
				WOO_MSTORE_URL . '/assets/js/jquery-ui.min.js',
				array( 'jquery-ms', )
			);
			wp_add_inline_script( 'jquery-ui-ms', 'var $ms = $.noConflict(true);' );

			wp_enqueue_script(
				'woosl-options',
				WOO_MSTORE_URL . '/assets/js/woosl-options.js',
				array( 'jquery-ms', 'jquery-ui-ms', 'jquery-tiptip' )
			);
		}

            function admin_no_key_notices()
                {
                    if ( !current_user_can('manage_options'))
                        return;
                    
                    $screen = get_current_screen();
                        
                    if(is_multisite())
                        {
                            if(isset($screen->id) && $screen->id    ==  'settings_page_woo-ms-options-network')
                                return;
                            ?><div class="updated fade"><p><?php _e( "WooCommerce Multistore plugin is inactive, please enter your", 'woonet' ) ?> <a href="<?php echo network_admin_url() ?>settings.php?page=woo-ms-options"><?php _e( "Licence Key", 'woonet' ) ?></a></p></div><?php
                        }
                }
            
            function licence_form_submit()
                {
                    global $mstore_form_submit_messages; 
                    
                    //check for de-activation
                    if (isset($_POST['mstore_licence_form_submit']) && isset($_POST['mstore_licence_deactivate']) && wp_verify_nonce($_POST['mstore_license_nonce'],'mstore_license'))
                        {
                            global $mstore_form_submit_messages;
                            
                            $license_data = get_site_option('mstore_license');                        
                            $license_key = $license_data['key'];

                            //build the request query
                            $args = array(
                                                'woo_sl_action'         => 'deactivate',
                                                'licence_key'           => $license_key,
                                                'product_unique_id'     => WOO_MSTORE_PRODUCT_ID,
                                                'domain'                => WOO_MSTORE_INSTANCE
                                            );
                            $request_uri    = WOO_MSTORE_APP_API_URL . '?' . http_build_query( $args , '', '&');
                            $data           = wp_remote_get( $request_uri );
                            
                            if(is_wp_error( $data ) || $data['response']['code'] != 200)
                                {
                                    $mstore_form_submit_messages[] .= __('There was a problem connecting to ', 'woonet') . WOO_MSTORE_APP_API_URL;
                                    return;  
                                }
                                
                            $response_block = json_decode($data['body']);
                            //retrieve the last message within the $response_block
                            $response_block = $response_block[count($response_block) - 1];
                            $response = $response_block->message;
                            
                            if(isset($response_block->status))
                                {
                                    if($response_block->status == 'success' && $response_block->status_code == 's201')
                                        {
                                            //the license is active and the software is active
                                            $mstore_form_submit_messages[] = $response_block->message;
                                            
                                            $license_data = get_site_option('mstore_license');
                                            
                                            //save the license
                                            $license_data['key']          = '';
                                            $license_data['last_check']   = time();
                                            
                                            update_site_option('mstore_license', $license_data);
                                        }
                                        
                                    else //if message code is e104  force de-activation
                                            if ($response_block->status_code == 'e002' || $response_block->status_code == 'e104')
                                                {
                                                    $license_data = get_site_option('mstore_license');
                                            
                                                    //save the license
                                                    $license_data['key']          = '';
                                                    $license_data['last_check']   = time();
                                                    
                                                    update_site_option('mstore_license', $license_data);
                                                }
                                        else
                                        {
                                            $mstore_form_submit_messages[] = __('There was a problem deactivating the licence: ', 'woonet') . $response_block->message;
                                     
                                            return;
                                        }   
                                }
                                else
                                {
                                    $mstore_form_submit_messages[] = __('There was a problem with the data block received from ', 'woonet') . WOO_MSTORE_APP_API_URL;
                                    return;
                                }
                                
                            //redirect
                            $current_url    =   'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            
                            wp_redirect($current_url);
                            die();
                            
                        }   
                    
                    
                    
                    if (isset($_POST['mstore_licence_form_submit']) && wp_verify_nonce($_POST['mstore_license_nonce'],'mstore_license'))
                        {
                            
                            $license_key = isset($_POST['license_key'])? sanitize_key(trim($_POST['license_key'])) : '';

                            if($license_key == '')
                                {
                                    $mstore_form_submit_messages[] = __("Licence Key can't be empty", 'woonet');
                                    return;
                                }
                                
                            //build the request query
                            $args = array(
                                                'woo_sl_action'         => 'activate',
                                                'licence_key'       => $license_key,
                                                'product_unique_id'        => WOO_MSTORE_PRODUCT_ID,
                                                'domain'          => WOO_MSTORE_INSTANCE
                                            );
                            $request_uri    = WOO_MSTORE_APP_API_URL . '?' . http_build_query( $args , '', '&');
                            $data           = wp_remote_get( $request_uri );
                            
                            if(is_wp_error( $data ) || $data['response']['code'] != 200)
                                {
                                    $mstore_form_submit_messages[] .= __('There was a problem connecting to ', 'woonet') . WOO_MSTORE_APP_API_URL;
                                    return;  
                                }
                                
                            $response_block = json_decode($data['body']);
                            //retrieve the last message within the $response_block
                            $response_block = $response_block[count($response_block) - 1];
                            $response = $response_block->message;
                            
                            if(isset($response_block->status))
                                {
                                    if($response_block->status == 'success' && $response_block->status_code == 's100')
                                        {
                                            //the license is active and the software is active
                                            $mstore_form_submit_messages[] = $response_block->message;
                                            
                                            $license_data = get_site_option('mstore_license');
                                            
                                            //save the license
                                            $license_data['key']          = $license_key;
                                            $license_data['last_check']   = time();
                                            
                                            update_site_option('mstore_license', $license_data);

                                        }
                                        else
                                        {
                                            $mstore_form_submit_messages[] = __('There was a problem activating the licence: ', 'woonet') . $response_block->message;
                                            return;
                                        }   
                                }
                                else
                                {
                                    $mstore_form_submit_messages[] = __('There was a problem with the data block received from ', 'woonet'). WOO_MSTORE_APP_API_URL;
                                    return;
                                }
                                
                            //redirect
                            $current_url    =   'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            
                            wp_redirect($current_url);
                            die();
                        }   
                    
                }
                
            function licence_form()
                {
                    ?>
                        <div class="wrap"> 
                            <div id="icon-settings" class="icon32"></div>
                            <h2><?php _e( "WooCommerce Multistore", 'woonet' ) ?><br />&nbsp;</h2>
                            
                            
                            <form id="form_data" name="form" method="post">
                                <div class="postbox">
                                    
                                        <?php wp_nonce_field('mstore_license','mstore_license_nonce'); ?>
                                        <input type="hidden" name="mstore_licence_form_submit" value="true" />
                                           
                                        

                                         <div class="section section-text ">
                                            <h4 class="heading"><?php _e( "License Key", 'woonet' ) ?></h4>
                                            <div class="option">
                                                <div class="controls">
                                                    <input type="text" value="" name="license_key" class="text-input">
                                                </div>
                                                <div class="explain"><?php _e( "Enter the License Key you got when bought this product. If you lost the key, you can always retrieve it from", 'woonet' ) ?> <a href="http://woomultistore.com/premium-plugins/my-account/" target="_blank"><?php _e( "My Account", 'woonet' ) ?></a><br />
                                                <?php _e( "More keys can be generate from", 'woonet' ) ?> <a href="http://woomultistore.com/premium-plugins/my-account/" target="_blank"><?php _e( "My Account", 'woonet' ) ?></a> 
                                                </div>
                                            </div> 
                                        </div>

                                    
                                </div>
                                
                                <p class="submit">
                                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'woonet') ?>">
                                </p>
                            </form> 
                        </div> 
                    <?php  
     
                }
            
            function licence_deactivate_form()
                {
                    $license_data = get_site_option('mstore_license');
                    
                    if(is_multisite())
                        {
                            ?>
                                <div class="wrap"> 
                                    <div id="icon-settings" class="icon32"></div>
                            <?php
                        }
                    
                    ?>
                        <div id="form_data">
                        <h2 class="subtitle"><?php _e( "Software License", 'woonet' ) ?></h2>
                        <div class="postbox">
                            <form id="form_data" name="form" method="post">    
                                <?php wp_nonce_field('mstore_license','mstore_license_nonce'); ?>
                                <input type="hidden" name="mstore_licence_form_submit" value="true" />
                                <input type="hidden" name="mstore_licence_deactivate" value="true" />

                                 <div class="section section-text ">
                                    <h4 class="heading"><?php _e( "License Key", 'woonet' ) ?></h4>
                                    <div class="option">
                                        <div class="controls">
                                            <?php  
                                                if($this->licence->is_local_instance())
                                                {
                                                ?>
                                                <p>Local instance, no key applied.</p>
                                                <?php   
                                                }
                                                else {
                                                ?>
                                            <p><b><?php echo substr($license_data['key'], 0, 20) ?>-xxxxxxxx-xxxxxxxx</b> &nbsp;&nbsp;&nbsp;<a class="button-secondary" title="Deactivate" href="javascript: void(0)" onclick="jQuery(this).closest('form').submit();">Deactivate</a></p>
                                            <?php } ?>
                                        </div>
                                        <div class="explain"><?php _e( "You can generate more keys from", 'woonet' ) ?> <a href="http://woomultistore.com/premium-plugins/my-account/" target="_blank">My Account</a> 
                                        </div>
                                    </div> 
                                </div>
                             </form>
                        </div>
                        </div> 
                    <?php  
     
                    if(is_multisite())
                        {
                            ?>
                                </div>
                            <?php
                        }
                }
                
            function licence_multisite_require_nottice()
                {
                    ?>
                        <div class="wrap"> 
                            <div id="icon-settings" class="icon32"></div>

                            <h2 class="subtitle"><?php _e( "Software License", 'woonet' ) ?></h2>
                            <div id="form_data">
                                <div class="postbox">
                                    <div class="section section-text ">
                                        <h4 class="heading"><?php _e( "License Key Required", 'woonet' ) ?>!</h4>
                                        <div class="option">
                                            <div class="explain"><?php _e( "Enter the License Key you got when bought this product. If you lost the key, you can always retrieve it from", 'woonet' ) ?> <a href="http://www.nsp-code.com/premium-plugins/my-account/" target="_blank"><?php _e( "My Account", 'woonet' ) ?></a><br />
                                            <?php _e( "More keys can be generate from", 'woonet' ) ?> <a href="http://www.nsp-code.com/premium-plugins/my-account/" target="_blank"><?php _e( "My Account", 'woonet' ) ?></a> 
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php
                
                }    

                
        }

                                   

?>