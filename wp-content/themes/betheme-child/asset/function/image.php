<?php 

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	// Get base URL for images on subsites	
		function popupcomicshops_get_base_url(){
			$base_url = "https://popshopcom.s3.amazonaws.com/uploads";
			return $base_url;
		}
		
		add_filter( 'pre_option_upload_url_path', 'popupcomicshops_get_base_url' );
	
	// Change base URL for subsites		
		function popupcomicshops_wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ){
			//$image[0] = str_replace( popupcomicshops_get_base_url() . '/sites/' . get_current_blog_id(), popupcomicshops_get_base_url(), $image[0] );
			if ( !is_main_site() ){				
				if ( is_plugin_active( 'S3-Uploads-2.0.0-beta3' ) ){	// Turn off if S3 plugin is deactivated.
					$image[0] = str_replace( '.jpg', '.jpeg', $image[0] );	// TODO: temporary fix, AWS S3 files for subsites have the file extension .jpeg instead of .jpg
				}
			}

			if ( isset( $image[1] ) && isset( $image[2] ) && $image[1] == 120 && $image[2] == 180 ){
				$image[0] = popupcomicshops_woocommerce_placeholder_img_src( $image[0] );
			}
			return $image;
		}
		
		add_filter( 'wp_get_attachment_image_src', 'popupcomicshops_wp_get_attachment_image_src', 10000, 4 );
	
	/* Update image path to AWS S3 CDN
		function popupcomicshops_icon_dir( $path ){
			error_log( "PATH: " );
			error_log( $path );
			//$path = popupcomicshops_get_base_url();
			return $path;
		}

		add_filter( 'icon_dir', 'popupcomicshops_icon_dir', 10, 2 );

		function wpdocs_theme_icon_uri( $icon_dir ) {
			//$icon_dir = '';
			return $icon_dir; 
		}
		
		add_filter( 'icon_dir_uri', 'wpdocs_theme_icon_uri' );*/
