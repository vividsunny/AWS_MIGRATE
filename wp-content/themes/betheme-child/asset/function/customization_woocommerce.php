<?php

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	/**
	 * Change the product placeholder image when no image is specified
		from: https://docs.woocommerce.com/document/change-the-placeholder-image/
	 */
		add_filter( 'woocommerce_placeholder_img_src', 'popupcomicshops_woocommerce_placeholder_img_src' );

		function popupcomicshops_woocommerce_placeholder_img_src( $src ){
			$src = popupcomicshops_get_base_url() . '/2018/09/comingsooncolor.jpg';
			return $src;
		}

	// Replace image URL for "coming soon" images in single product
		add_filter( 'woocommerce_single_product_image_thumbnail_html', 'popupcomicshops_woocommerce_single_product_image_thumbnail_html', 10, 2 ); // Main product thumbnail

		function popupcomicshops_woocommerce_single_product_image_thumbnail_html( $html, $post_thumbnail_id ){
			$media = wp_get_attachment_metadata( $post_thumbnail_id );

			if ( isset( $media[ 'width' ] ) && isset( $media[ 'height' ] ) ){
				if( $media[ 'width' ] == 120 && $media[ 'height' ] == 180 ){ // TODO: Add check if the image is a placeholder
					$post_thumbnail_id = 2175;	// Placeholder image
					$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
				}
			}

			return $html;
		}

	// Replace image URL for "coming soon" images in store page
		add_filter( 'woocommerce_product_get_image', 'popupcomicshops_woocommerce_product_get_image', 10, 2 );

		function popupcomicshops_woocommerce_product_get_image( $image, $product ){
			$product_id = $product->get_id();
			$product_meta = get_post_meta($product_id);
			if ( isset( $product_meta[ '_thumbnail_id' ] ) ){
				$post_id = $product_meta[ '_thumbnail_id' ][0];
				$media = wp_get_attachment_metadata( $post_id );

				if( isset( $media[ 'width' ] ) && $media[ 'width' ] == 120 && isset( $media[ 'height' ] ) &&  $media[ 'height' ] == 180 ){
					$image = get_the_post_thumbnail( 2175 );
				}
			}

			return $image;
		}

		add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );

		function custom_pre_get_posts_query( $q ) {
			$tax_query = (array) $q->get( 'tax_query' );

			$tax_query[] = array(
				   'taxonomy' 	=> 'product_tag',
				   'field' 		=> 'slug',
				   'terms' 		=> array( 'marvel-heroes', 'dc-universe' ), // Don't display products with the tag "banana"
				   'operator' 	=> 'NOT IN'
			);

			$q->set( 'tax_query', $tax_query );
		}

	// Change price to "Please inquire" if price is $0
		 add_filter( 'woocommerce_get_price_html', 'popupcomicshops_price_replace', 10, 2 );
		//add_filter( 'woocommerce_cart_item_price', 'popupcomicshops_price_replace', 10, 2 );

		function popupcomicshops_price_replace( $price, $product ){
			// vivid( $product );
			// vivid( $price );
			if ( is_admin() ){		// Do only on front end
				return;
			}

			if ( $product->price == 0 ) {			// && !$product->is_type( 'subscription' )
				$price = '<span class="woocommerce-Price-amount amount">Please inquire</span>';
			}

			return $price;
		}
