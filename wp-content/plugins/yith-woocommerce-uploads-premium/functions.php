<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

//region    ***************  METAKEY name definition    *******************

if ( ! defined( 'YWAU_METAKEY_ORDER_ITEM_FILES' ) ) {
	define( 'YWAU_METAKEY_ORDER_ITEM_FILES', '_ywau_order_item_files' );
}

if ( ! defined( 'YWAU_CART_UPLOAD_RESULTS' ) ) {
	define( 'YWAU_CART_UPLOAD_RESULTS', 'ywau_upload_results' );
}

if ( ! defined( 'YWAU_METAKEY_ORDER_FILE_UPLOADED' ) ) {
	define( 'YWAU_METAKEY_ORDER_FILE_UPLOADED', '_ywau_order_file' );
}

if ( ! defined( 'YWAU_ACTION_DOWNLOAD_FILE' ) ) {
	define( 'YWAU_ACTION_DOWNLOAD_FILE', 'download-file' );
}

if ( ! defined( 'YWAU_METAKEY_ORDER_UPLOADS' ) ) {
	define( 'YWAU_METAKEY_ORDER_UPLOADS', '_ywau_order_uploads' );
}

//endregion

if ( ! function_exists( 'yith_get_wpml_parent_id' ) ) {

	function yith_get_wpml_parent_id( $id, $post_type = 'product' ) {

		global $sitepress;
		if ( isset( $sitepress ) ) {

			$_wcml_settings = get_option( '_wcml_settings' );
			if ( isset( $_wcml_settings['trnsl_interface'] ) && '1' == $_wcml_settings['trnsl_interface'] ) {

				$default_language = $sitepress->get_default_language();

				if ( function_exists( 'icl_object_id' ) ) {
					$id = icl_object_id( $id, $post_type, true, $default_language );
				} else {
					if ( function_exists( 'wpml_object_id_filter' ) ) {
						$id = wpml_object_id_filter( $id, $post_type, true, $default_language );
					}
				}
			}
		}

		return $id;
	}
}

add_filter( 'yith_ywau_get_product_id_before_rules_checking', 'yith_get_wpml_parent_id' );