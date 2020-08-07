<?php 

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	// WooCommerce
	
		add_action( 'woocommerce_after_add_to_cart_button', 'popupcomicshops_woocommerce_after_add_to_cart_button' );
		
		// Add 'Subscribe' button to product page
			function popupcomicshops_woocommerce_after_add_to_cart_button(){
				global $product, $wpdb;

				if ( !$product->is_type( 'subscription' ) ){	// Don't add 'Subscribe' button if product type is 'subscription'

					

					/*$parent_product_id = popupcomicshops_get_parent_subscription( $product->get_id() );*/		
					$series_code = get_post_meta( $product->get_id(), 'series_code', true );
					if( !empty( $series_code ) ){
						$sdata = new series_subscription();
						$series_data = $sdata->series_data( $series_code );
						$code = $series_data->code;
						$parent_product_id = $code;
					}else{
						$parent_product_id = ''; 
					}
					

					$site = get_blog_details();

					wp_enqueue_script("shortcode-script");
					
					if ( !empty( $parent_product_id ) ){								

						$blog_id = get_current_blog_id();

						/*if($blog_id == 13){*/
							

							if ( is_user_logged_in() ) {

								/* Get current login user */
								$current_user = wp_get_current_user();
								$user_id = $current_user->ID;

								$subscription_time = get_user_meta($user_id,'subscription_time_',true);

								//$time = update_user_meta($user_id,'subscription_time_','');

								//vivid($subscription_time);
								//vivid($time);

        						$authorization_status = get_user_meta($user_id,'authorization_status',true);

        						//vivid($authorization_status);
        						/*$authorization_status = 'No';*/
        						$wpdb->blogid = $blog_id;
								$wpdb->set_prefix( $wpdb->base_prefix );
								$posttitle = 'Become Member';
        						$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND post_type ='product'" );

        						

        						/*vivid($postid);*/
        						if($authorization_status != trim('Yes')){
        							$btn_text = 'Subscribe';
        							$url = $site->path. 'checkout/?add-to-cart='.$postid;
        							//$class = 'add_to_cart_button ajax_add_to_cart';
        						}else{

        							$user_subscriber_series = get_user_meta($user_id,'_user_subscription_series_id',true);
        							/*vivid($user_subscriber_series);*/
        							if( in_array($parent_product_id, $user_subscriber_series) ){
        								$btn_text = 'Subscribed';
        								$class = 'active_';
        								$url = 'javascript:void(0);';
        							}else{
        								$btn_text = 'Subscribe';
        								$class = 'add_parent_product_id';
        								$url = 'javascript:void(0);';
        								$u_id = $user_id;
        							}

        							
        						}
								
							}else{
								$btn_text = 'Subscribe';
								//$url = $site->path. 'my-account';
								$url = 'javascript:void(0);';
							}
							
							$html = ' &nbsp; <a rel="nofollow" href="' . $url . '" data-quantity="1" data-product_id="' . $parent_product_id . '" class="product_type_simple '.$class.'" data-user_id = "'.$u_id.'"><button type="button" class="single_add_to_cart_button button alt" id="sub_btn">'.$btn_text.'</button></a>';


						/*}else{
							$url = $site->path . 'shop/?add-to-cart=' . $parent_product_id;
							$html = ' &nbsp; <a rel="nofollow" href="' . $url . '" data-quantity="1" data-product_id="' . $parent_product_id . '" class="add_to_cart_button ajax_add_to_cart product_type_simple"><button type="submit" class="single_add_to_cart_button button alt">Subscribe</button></a>';
						}*/	
						echo $html;	
						
					} 
				}
			}
			
		function popupcomicshops_get_parent_subscription( $product_id ){			
			$series_code = get_post_meta( $product_id, 'series_code', true );
			if($series_code == 0){
				$parent_product_id ='';
			}else{
				$parent_product_id = wc_get_product_id_by_sku( $series_code );
			
				if ( empty( $parent_product_id ) ){	// Get parent product id from network product instead
					$network_product_id = get_post_meta( $product_id, '_woonet_network_is_child_product_id', true );
					if ( empty( $network_product_id ) ){
						$network_product_id = get_post_meta( $product_id, '_woonet_network_unassigned_product_id', true );	
					}
					
					if ( empty( $main_site ) ){
						$main_site = get_post_meta( $product_id, '_woonet_network_unassigned_site_id', true );			
					}
					if ( empty( $main_site ) ){
						$main_site = 1;		// hardcoded main site		
					}
				
					switch_to_blog( $main_site );
					$series_code = get_post_meta( $network_product_id, 'series_code', true );
					restore_current_blog();
					
					$parent_product_id = wc_get_product_id_by_sku( $series_code );
				}
			}
			
			return $parent_product_id;	
		}
		
		function popupcomicshops_get_child_product( $parent_product_id ){		
			$series_code = get_post_meta( $parent_product_id, '_sku', true );
			
			$args = array(
					'post_type'		=> 'product',
					'fields' 		=> 'ids',
					'meta_key'		=> 'series_code',
					'meta_value'	=> $series_code,
				);
			 
			$query = new WP_Query( $args );
	
			$child_product_id = array();
			if( $query->have_posts() ) {
				$child_product_id = $query->posts;
			}

			return $child_product_id;
		}
	
	// Remove manual payment box for subscriptions: TODO
	
		//add_action( 'admin_head', 'popupcomicshops_remove_payment_box' );
	
		function popupcomicshops_remove_payment_box(){
			if ( !is_admin() ){
				return;
			}

			global $post;
			if ( !empty( $post ) ){
				if ( wcs_order_contains_subscription( $post->ID ) ){
					echo '<style>
						.post-type-shop_order div#woo-mp{
							display: none;
						}
					</style>';
				}
			}
		}
