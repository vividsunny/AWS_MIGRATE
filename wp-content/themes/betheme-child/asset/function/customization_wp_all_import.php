<?php

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Customizations to WP All Import Plugin

		// Convert imported products to Network Products
			add_action( 'pmxi_saved_post', 'popupcomicshops_pmxi_saved_post', 10, 1 );	
		
			function popupcomicshops_pmxi_saved_post( $network_product_id ) {
				$sites = get_sites();																			// Get all subsites
				foreach ( $sites as $site ){
					if ( $site->blog_id != 1 ){	// Do not import products to main site		
						/* if ( $site->blog_id == 1 ){
							update_post_meta( $network_product_id, '_woonet_publish_to_' . $site->blog_id, 'no' );	// Do not publish to main site
						} else { */
							update_post_meta( $network_product_id, '_woonet_publish_to_' . $site->blog_id, 'yes' );	// Publish to each subsite
						//}

						switch_to_blog( $site->blog_id );

						$args = array(
								'post_type'		=> 'product',
								'fields' 		=> 'ids',			// Get only ID column
								'meta_key'		=> '_woonet_network_is_child_product_id',
								'meta_value'	=> $network_product_id,
							);				
						$query = new WP_Query( $args );
						$product_id = $query->posts[0];
						popupcomicshops_set_subscription_price( $product_id );
						
						restore_current_blog();
					}
				}

				update_post_meta( $network_product_id, '_woonet_network_main_product', 'true' );			// Set as network product			
				popupcomicshops_remove_parenthetical( $network_product_id );								// Remove parenthesis from post title

				if ( strpos( get_the_title( $network_product_id ), 'Subscription:' ) !== FALSE ){			// Check if post is a series (subscription)			
					$result = wp_set_object_terms( $network_product_id, 'subscription','product_type' );	// Change product type to 'subscription'
					popupcomicshops_set_subscription_price( $network_product_id );				
				}
			}
		
		// Set subscription sign up fee to $5 and monthly price to $0
			function popupcomicshops_set_subscription_price( $id ){
				if ( strpos( get_the_title( $id ), 'Subscription:' ) !== FALSE ){		// Check if subscription
					update_post_meta( $id, '_price', '0' );
					update_post_meta( $id, '_regular_price', '0' );
					update_post_meta( $id, '_subscription_price', '0' );
					update_post_meta( $id, '_subscription_sign_up_fee', '0.01' );		// Changed to $0.01 for now instead of $5
					update_post_meta( $id, '_subscription_period', 'month' );
					update_post_meta( $id, '_subscription_period_interval', '1' );
					update_post_meta( $id, '_sold_individually', 'yes' );				// Sold individually	
					update_post_meta( $id, '_parent_product', 'yes' );
				}
			}
		
		// Remove parenthesis from post title
			function popupcomicshops_remove_parenthetical( $id ){
				$title = get_the_title( $id );
				$title = str_replace( '(Net)', '', $title );			// Remove '(Net)' from title
				$title = preg_replace( "/\(C[^)]+\)/","", $title );		// Remove '(C: 1-1-2)' from title
				
				$post = array(
					'ID'           => $id,
					'post_title'   => $title,
				);

				wp_update_post( $post );
			}
	
		// Automatically create new orders on import when new volumes arrives
		/*	add_action( 'pmxi_after_xml_import', 'popupcomicshops_create_subscription_order', 10, 1 );	*/
			
			if ( isset( $_REQUEST[ 'dev' ] ) ){
				//add_action( 'init', 'popupcomicshops_create_subscription_order', 10, 1 );				// dev
			}
		
			function popupcomicshops_create_subscription_order( $import_id ){
				if ( popupcomicshops_throttle_call() ){
					return;
				}

				$sites = get_sites();													// Get all subsites
				foreach ( $sites as $site ){
					if ( $site->blog_id != 1 ){											// Do not do for main site
						switch_to_blog( $site->blog_id );								// Switch to subsite
						$subscriptions = popupcomicshops_get_active_subscription();		
						foreach ( $subscriptions as $subscription_id => $subscription ){
							$comics = array();					
							foreach ( $subscription->get_items() as $item_id => $item ){ // Get series from order items in subscription order 				
								$child_products = popupcomicshops_get_child_product( $item->get_product_id() );	// Get child comics from parent series																
								foreach ( $child_products as $child_product_id ){
									if( !popupcomicshops_has_existing_order( $subscription->get_user_id(), $child_product_id ) ){ // Check if child comics has already been ordered by customer
										if( !in_array( $child_product_id, $comics ) ){ // Check for uniqueness
											$comics[] = $child_product_id; // Add comics to items array for new order
										}
									}
								}								
							} 
									
							popupcomicshops_create_order( $comics, $subscription->get_user_id() ); // Create order for child comics						
						} // End subscription loop 
						restore_current_blog();
					} // End main site conditional
				} // End site loop				
			} 
			
		// Create order for child comics
			function popupcomicshops_create_order( $comics, $user_id ){
				if ( !empty( $comics ) ){
					$args = array( 
							'status' 		=> 'pending',
							'customer_id' 	=> $user_id,
						);
					$order = wc_create_order( $args );

					foreach( $comics as $comic => $comic_id ){						
						if ( is_object( $order ) ){
							$order->add_product( wc_get_product( $comic_id ), 1 );
							$order->calculate_totals();
						}
					} 
				}
			}
			
		// Throttle call
			function popupcomicshops_throttle_call(){
				if ( isset( $_REQUEST[ 'dev' ] ) ){	
					return false;
				} 
				
				// Limit this to once a month only, not running on every import	
					$interval = 30 * 24 * 60 * 60;  // Limit call, once a month ( 30 days * 24 hours * 60 minutes * 60 seconds ) 			
					if ( ( get_option( "last_popupcomicshops_call" ) + $interval ) > time() ){ 						
						return true;
					} 
					
				update_option( "last_popupcomicshops_call", time() );
				return false;
			}
			
		// Get active subscriptions
			function popupcomicshops_get_active_subscription(){
				$args = array( 
						'subscriptions_per_page' 	=> -1,
						//'post_status' 				=> 'wc-active',						// Active subscription
						'post_status' 				=> array( 'wc-on-hold', 'wc-active' ),	// Active subscription: TODO: add on-hold
						//'orderby'					=> '_customer_user', 					// TODO: sort by user
					);
				$subscriptions = wcs_get_subscriptions( $args );							// Get all subscriptions
				return $subscriptions;
			}
			
		// Check if customer already has a order with product 
			function popupcomicshops_has_existing_order( $user_id, $product_id ){
				$subscriber = get_user_by( 'id', $user_id );
				if ( wc_customer_bought_product( $subscriber->user_email, $user_id, $product_id ) ) { 
					return true;
				}
		
				$args = array(
                        'meta_key'		=> '_customer_user',
                        'meta_value'	=> $user_id,
                        'numberposts'	=> -1,
                    );
                $orders = wc_get_orders( $args ); // Check for pending orders
		
				foreach ( $orders as $order_id => $order ){
					foreach ( $order->get_items() as $item_id => $item ){ // Get series from order items in subscription 
						if ( $product_id == $item->get_product_id() ){
							return true;
						}
					}
				}
				return false;
			}			

			// Create order for child comics
			function vvd_popupcomicshops_create_order( $comics, $user_id ){

				if ( !empty( $comics ) ){
					$args = array( 
						'status' 		=> 'pending',
						'customer_id' 	=> $user_id,
					);
					$order = wc_create_order( $args );

					$address_type = array('billing','shipping');
					$address = array('first_name','last_name', 'company', 'email', 'phone', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', );
					$billing_address = array();
					$shipping_address = array();
					$remove_field = array('email','phone');
					foreach ($address_type as $key => $address_type) {
						foreach ($address as $key => $address_field) {
							if( $address_type == 'billing' ){
								$user_data = get_user_meta($user_id,$address_type.'_'.$address_field,true);
								if(!empty( $user_data ) ){
									$billing_address[$address_field] = $user_data;	
								}
								
							}else if( $address_type == 'shipping' && !in_array($address_field, $remove_field)){
								$user_data = get_user_meta($user_id,$address_type.'_'.$address_field,true);
								if(!empty( $user_data ) ){
									$shipping_address[$address_field] = $user_data;	
								}
								
							}
						}
					}

					if(!empty( $billing_address ) ){
						$order->set_address( $billing_address, 'billing' );
					}

					if(!empty( $shipping_address ) ){
						$order->set_address( $shipping_address, 'shipping' );
					}
					

					
					foreach( $comics as $comic => $comic_id ){						
						if ( is_object( $order ) ){
							$order->add_product( wc_get_product( $comic_id ), 1 );
							$order->calculate_totals();

							/* Order Meta */
							update_post_meta( $order->id , '_order_subscription' , 'yes' );
						}
						
						$author_obj = get_user_by('id', $user_id);
						$to = $user->user_email;
						$subject = 'New Order #'.$order->id;
						$body = 'The email body content';
						$headers = array('Content-Type: text/html; charset=UTF-8');
						
						wp_mail( $to, $subject, $body, $headers );
					} 
					vvd_popupcomicshops_set_custom_label( $order->id );
					// vvd_popupcomicshops_set_custom_label( 59602 );
				}

				return $order->id;
			}

			function vvd_popupcomicshops_set_custom_label( $order_id ){
			    # Get an instance of WC_Order object
			    $order = wc_get_order( $order_id );

			    # Iterating through each order items (WC_Order_Item_Product objects in WC 3+)
			    foreach ( $order->get_items() as $item_id => $item_values ) {

			        // Product_id
			        $product_id = $item_values->get_product_id(); 

			        // OR the Product id from the item data
			        $item_data = $item_values->get_data();
			        //debug( $item_data );

			        $product_id = $item_data['product_id'];
			        // debug( 'Product Id --->'.$product_id );

			        $_product = wc_get_product( $product_id );
			        $IOD_date = get_post_meta( $product_id, 'vvd_product_IOD_date', true );
			        $order_series_code = get_post_meta( $product_id, 'series_code', true );
					if(!empty( $order_series_code )){
						$order_series_code = $order_series_code;
					}else{
						$order_series_code = '-';
					}

			        if(!empty( $IOD_date )){

			        	$IOD_date_strtotime = date("Y-m-d H:i:s",strtotime( $IOD_date ));
			        	$today_date = date("Y-m-d H:i:s");
			        	if( strtotime($IOD_date_strtotime) >= strtotime($today_date) )
			              {
			              	/*debug( 'In IOD_date-- '.$IOD_date .'IOD_date_strtotime-- '. strtotime( $IOD_date_strtotime ) );
			        		debug( 'In today_date-- '.$today_date .'today_date-- '. strtotime( $today_date ) );*/

			        		$vvd_key = 'item_label';
					        $vvd_value = 'pre-order';
					        wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
			                wc_update_order_item_meta($item_id, 'subsciption_label', 1);
			                wc_update_order_item_meta($item_id, 'series_code', $order_series_code);

			              }else if( strtotime($today_date) >= strtotime($IOD_date_strtotime) ){
			              	
			              	$vvd_key = 'item_label';
					        $vvd_value = 're-order';

					        if ( $_product->get_stock_quantity() > 0 ) {

				        		//debug('Order');
				        		$vvd_key = 'item_label';
					        	$vvd_value = 'order';
				        		wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
			                    wc_update_order_item_meta($item_id, 'subsciption_label', 1);
			                    wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
					        } else {

					        	//debug('Reorder');
					        	$vvd_key = 'item_label';
						        $vvd_value = 're-order';
						        wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
			                    wc_update_order_item_meta($item_id, 'subsciption_label', 1);
			                    wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
					        }
					       
			              }else{
			              	//debug('Not In Else');
			              	$vvd_key = 'item_label';
					        $vvd_value = '';
					        wc_update_order_item_meta($item_id, $vvd_key, $vvd_value);
			                wc_update_order_item_meta($item_id, 'subsciption_label', 1);
			                wc_update_order_item_meta($item_id, 'series_code', $order_series_code);
			              }
			        	
			        }
			        
			    }
			}