<?php

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Include files
		require_once( get_stylesheet_directory() . '/asset/function/image.php' );
		require_once( get_stylesheet_directory() . '/asset/function/customization_woocommerce.php' );
		require_once( get_stylesheet_directory() . '/asset/function/customization_woocommerce_subscribe.php' );
		require_once( get_stylesheet_directory() . '/asset/function/customization_wp_all_import.php' );

	// Include files in admin Dashboard
		if ( is_admin() ){
			require_once( get_stylesheet_directory() . '/asset/function/admin_menu.php' );
			require_once( get_stylesheet_directory() . '/asset/function/admin_export.php' );
			require_once( get_stylesheet_directory() . '/asset/function/cron.php' );
		}
