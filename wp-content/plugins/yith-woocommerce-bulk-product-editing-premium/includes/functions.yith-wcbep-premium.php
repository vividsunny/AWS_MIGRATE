<?php
/**
 * Functions
 *
 * @author  Yithemes
 * @package YITH WooCommerce Bulk Product Editing
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCBEP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_wcbep_get_template' ) ) {
	function yith_wcbep_get_template( $template, $args = array() ) {
		extract( $args );
		include( YITH_WCBEP_TEMPLATE_PATH . '/' . $template );
	}
}

if ( ! function_exists( 'yith_wcbep_strContains' ) ) {
	function yith_wcbep_strContains( $haystack, $needle ) {
		return stripos( $haystack, $needle ) !== false;
	}
}

if ( ! function_exists( 'yith_wcbep_strStartsWith' ) ) {
	function yith_wcbep_strStartsWith( $haystack, $needle ) {
		return $needle === "" || strirpos( $haystack, $needle, - strlen( $haystack ) ) !== false;
	}
}

if ( ! function_exists( 'yith_wcbep_strEndsWith' ) ) {
	function yith_wcbep_strEndsWith( $haystack, $needle ) {
		return $needle === "" || ( ( $temp = strlen( $haystack ) - strlen( $needle ) ) >= 0 && stripos( $haystack, $needle, $temp ) !== false );
	}
}

if ( ! function_exists( 'yith_wcbep_posts_filter_where' ) ) {
	function yith_wcbep_posts_filter_where( $where = '' ) {
		$f_title_sel       = ! empty( $_REQUEST['f_title_select'] ) ? $_REQUEST['f_title_select'] : 'cont';
		$f_title_val       = isset( $_REQUEST['f_title_value'] ) ? $_REQUEST['f_title_value'] : '';
		$f_description_sel = ! empty( $_REQUEST['f_description_select'] ) ? $_REQUEST['f_description_select'] : 'cont';
		$f_description_val = isset( $_REQUEST['f_description_value'] ) ? $_REQUEST['f_description_value'] : '';

		// Filter Title
		if ( isset( $f_title_val ) && strlen( $f_title_val ) > 0 ) {
			$compare = 'LIKE';
			$value   = '%' . $f_title_val . '%';
			switch ( $f_title_sel ) {
				case 'cont':
					$compare = 'LIKE';
					break;
				case 'notcont':
					$compare = 'NOT LIKE';
					break;
				case 'starts':
					$compare = 'REGEXP';
					$value   = '^' . $f_title_val;
					break;
				case 'ends':
					$compare = 'REGEXP';
					$value   = $f_title_val . '$';
					break;
				case 'regex':
					$compare = 'REGEXP';
					$value   = $f_title_val;
					break;
			}

			$where .= " AND post_title {$compare} '{$value}'";
		}

		// Filter Description
		if ( isset( $f_description_val ) && strlen( $f_description_val ) > 0 ) {
			$compare = 'LIKE';
			$value   = '%' . $f_description_val . '%';
			switch ( $f_description_sel ) {
				case 'cont':
					$compare = 'LIKE';
					break;
				case 'notcont':
					$compare = 'NOT LIKE';
					break;
				case 'starts':
					$compare = 'REGEXP';
					$value   = '^' . $f_description_val;
					break;
				case 'ends':
					$compare = 'REGEXP';
					$value   = $f_description_val . '$';
					break;
				case 'regex':
					$compare = 'REGEXP';
					$value   = $f_description_val;
					break;
			}

			$where .= " AND post_content {$compare} '{$value}'";
		}

		return $where;
	}
}

if ( ! function_exists( 'yith_wcbep_get_terms' ) ) {
	function yith_wcbep_get_terms( $args = array() ) {
		global $wp_version;

		if ( version_compare( '4.5.0', $wp_version, '>=' ) ) {
			return get_terms( $args );
		} else {
			$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : '';
			if ( isset( $args['taxonomy'] ) ) {
				unset( $args['taxonomy'] );
			}

			return get_terms( $taxonomy, $args );
		}
	}
}

if ( ! function_exists( 'yith_wcbep_get_wc_product_types' ) ) {
	function yith_wcbep_get_wc_product_types() {
		$terms         = yith_wcbep_get_terms( array( 'taxonomy' => 'product_type' ) );
		$product_types = array();
		foreach ( $terms as $term ) {
			$name = sanitize_title( $term->name );
			switch ( $name ) {
				case 'grouped' :
					$label = __( 'Grouped product', 'yith-woocommerce-bulk-product-editing' );
					break;
				case 'external' :
					$label = __( 'External/Affiliate product', 'yith-woocommerce-bulk-product-editing' );
					break;
				case 'variable' :
					$label = __( 'Variable product', 'yith-woocommerce-bulk-product-editing' );
					break;
				case 'simple' :
					$label = __( 'Simple product', 'yith-woocommerce-bulk-product-editing' );
					break;
				default :
					$label = ucfirst( $term->name );
					break;
			}
			$product_types[ $name ] = $label;
			if ( 'variable' === $name ) {
				$product_types['variation'] = __( 'Variation', 'yith-woocommerce-bulk-product-editing' );
			}
		}

		return $product_types;
	}
}
if ( ! function_exists( 'yith_wcbep_get_labels' ) ) {
	function yith_wcbep_get_labels() {
		return apply_filters(
			'yith_wcbep_labels', array(
								   'ID'                 => __( 'ID', 'yith-woocommerce-bulk-product-editing' ),
								   'title'              => __( 'Title', 'yith-woocommerce-bulk-product-editing' ),
								   'slug'               => __( 'Slug', 'yith-woocommerce-bulk-product-editing' ),
								   'image'              => __( 'Image', 'yith-woocommerce-bulk-product-editing' ),
								   'image_gallery'      => __( 'Product gallery', 'yith-woocommerce-bulk-product-editing' ),
								   'description'        => __( 'Description', 'yith-woocommerce-bulk-product-editing' ),
								   'shortdesc'          => __( 'Short description', 'yith-woocommerce-bulk-product-editing' ),
								   'regular_price'      => __( 'Regular price', 'yith-woocommerce-bulk-product-editing' ),
								   'sale_price'         => __( 'Sale price', 'yith-woocommerce-bulk-product-editing' ),
								   'purchase_note'      => __( 'Purchase note', 'yith-woocommerce-bulk-product-editing' ),
								   'categories'         => __( 'Categories', 'yith-woocommerce-bulk-product-editing' ),
								   'tags'               => __( 'Tags', 'yith-woocommerce-bulk-product-editing' ),
								   'sku'                => __( 'SKU', 'yith-woocommerce-bulk-product-editing' ),
								   'weight'             => __( 'Weight', 'yith-woocommerce-bulk-product-editing' ),
								   'height'             => __( 'Height', 'yith-woocommerce-bulk-product-editing' ),
								   'width'              => __( 'Width', 'yith-woocommerce-bulk-product-editing' ),
								   'length'             => __( 'Length', 'yith-woocommerce-bulk-product-editing' ),
								   'stock_quantity'     => __( 'Stock qty', 'yith-woocommerce-bulk-product-editing' ),
								   'download_limit'     => __( 'Download limit', 'yith-woocommerce-bulk-product-editing' ),
								   'download_expiry'    => __( 'Download expiry', 'yith-woocommerce-bulk-product-editing' ),
								   'downloadable_files' => __( 'Downloadable files', 'yith-woocommerce-bulk-product-editing' ),
								   'menu_order'         => __( 'Menu order', 'yith-woocommerce-bulk-product-editing' ),
								   'stock_status'       => __( 'Stock status', 'yith-woocommerce-bulk-product-editing' ),
								   'low_stock_amount'   => __( 'Low stock threshold', 'yith-woocommerce-bulk-product-editing' ),
								   'stock_qty'          => __( 'Stock quantity', 'yith-woocommerce-bulk-product-editing' ),
								   'manage_stock'       => __( 'Manage stock', 'yith-woocommerce-bulk-product-editing' ),
								   'sold_individually'  => __( 'Sold individually', 'yith-woocommerce-bulk-product-editing' ),
								   'featured'           => __( 'Featured', 'yith-woocommerce-bulk-product-editing' ),
								   'virtual'            => __( 'Virtual', 'yith-woocommerce-bulk-product-editing' ),
								   'downloadable'       => __( 'Downloadable', 'yith-woocommerce-bulk-product-editing' ),
								   'enable_reviews'     => __( 'Enable reviews', 'yith-woocommerce-bulk-product-editing' ),
								   'tax_status'         => __( 'Tax status', 'yith-woocommerce-bulk-product-editing' ),
								   'tax_class'          => __( 'Tax class', 'yith-woocommerce-bulk-product-editing' ),
								   'allow_backorders'   => __( 'Allow backorders?', 'yith-woocommerce-bulk-product-editing' ),
								   'shipping_class'     => __( 'Shipping class', 'yith-woocommerce-bulk-product-editing' ),
								   'status'             => __( 'Status', 'yith-woocommerce-bulk-product-editing' ),
								   'visibility'         => __( 'Catalog visibility', 'yith-woocommerce-bulk-product-editing' ),
								   'download_type'      => __( 'Download Type', 'yith-woocommerce-bulk-product-editing' ),
								   'prod_type'          => __( 'Product Type', 'yith-woocommerce-bulk-product-editing' ),
								   'date'               => __( 'Date', 'yith-woocommerce-bulk-product-editing' ),
								   'sale_price_from'    => __( 'Sale price from', 'yith-woocommerce-bulk-product-editing' ),
								   'sale_price_to'      => __( 'Sale price to', 'yith-woocommerce-bulk-product-editing' ),
								   'button_text'        => __( 'Button text', 'yith-woocommerce-bulk-product-editing' ),
								   'product_url'        => __( 'Product URL', 'yith-woocommerce-bulk-product-editing' ),
								   'up_sells'           => __( 'Upsells', 'yith-woocommerce-bulk-product-editing' ),
								   'cross_sells'        => __( 'Cross-sells', 'yith-woocommerce-bulk-product-editing' ),
							   )
		);
	}
}

if ( ! function_exists( 'yith_wcbep_get_label' ) ) {
	function yith_wcbep_get_label( $key ) {
		$labels = yith_wcbep_get_labels();
		$label  = isset( $labels[ $key ] ) ? $labels[ $key ] : '';

		return apply_filters( 'yith_wcbep_get_label', $label, $key );
	}
}

if ( ! function_exists( 'yith_wcbep_get_enabled_columns' ) ) {
	function yith_wcbep_get_enabled_columns() {
		return YITH_WCBEP_List_Table_Premium::get_enabled_columns();
	}
}

if ( ! function_exists( 'yith_wcbep_is_column_enabled' ) ) {
	function yith_wcbep_is_column_enabled( $column ) {
		$enabled_columns = yith_wcbep_get_enabled_columns();

		return in_array( $column, $enabled_columns );
	}
}

if ( ! function_exists( 'yith_wcbep_get_hidden_columns_option' ) ) {
	function yith_wcbep_get_hidden_columns_option() {
		$per_user = 'yes' === get_option( 'yith-wcbep-hidden-columns-per-user', 'no' );
		$user_id  = get_current_user_id();
		$option   = 'yith_wcbep_default_hidden_cols';

		if ( $per_user && $user_id ) {
			$option .= '-' . $user_id;
		}

		return $option;
	}
}

if ( ! function_exists( 'yith_wcbep_get_hidden_columns' ) ) {
	function yith_wcbep_get_hidden_columns( $default = false ) {
		$option = yith_wcbep_get_hidden_columns_option();

		return get_option( $option, $default );
	}
}

if ( ! function_exists( 'yith_wcbep_set_hidden_columns' ) ) {
	function yith_wcbep_set_hidden_columns( $value = array() ) {
		$option = yith_wcbep_get_hidden_columns_option();
		update_option( $option, (array) $value );
	}
}