<?php

/**
 * Restrict Refund Button.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRRP_Button_Restriction' ) ) {

	/**
	 * Class.
	 */
	class HRRP_Button_Restriction {

		/**
		 * Order Object.
		 */
		public static $order ;

		/**
		 * Order Item.
		 */
		public static $order_items ;

		/**
		 * Check given order id is valid for refund.
				 * 
				 * @return bool
		 */
		public static function is_valid_order( $bool, $order_id ) {

			self::$order = wc_get_order( $order_id ) ;

			if ( ! self::$order ) {
				return false ;
			}

			self::$order_items = self::$order->get_items() ;

			if ( ! self::is_valid_status( $order_id ) ) {
				return false ;
			}

			if ( ! self::is_valid_amount() ) {
				return false ;
			}

			if ( ! self::is_valid_date() ) {
				return false ;
			}

			if ( ! self::is_valid_user() ) {
				return false ;
			}

			if ( ! self::check_product_and_category_valid_to_refund() ) {
				return false ;
			}

			if ( ! self::check_product_sale_item_valid_to_refund() ) {
				return false ;
			}

			return true ;
		}

		/**
		 * Check if given order status is matched with selected order status.
				 * 
				 * @return bool
		 */
		public static function is_valid_status( $order_id ) {
			$order_status    = get_post_status( $order_id ) ;
			$selected_status = get_option( 'hrr_refund_order_status' ) ;
			if ( ! hrr_check_is_array( $selected_status ) ) {
				return true ;
			} else {
				$order_status = str_replace( 'wc-' , '' , $order_status ) ;
				if ( in_array( $order_status , $selected_status ) ) {
					return true ;
				}
			}
			return false ;
		}

		/**
		 * Check if order total is greater than refund amount.
				 * 
				 * @return bool
		 */
		public static function is_valid_amount() {
			if ( get_option( 'hrr_refund_minimum_order_amount' ) <= self::$order->get_total() ) {
				return true ;
			}

			return false ;
		}

		/**
		 * Check if given order is within selected time period.
				 * 
				 * @return bool
		 */
		public static function is_valid_date() {
						$time_period = get_option( 'hrr_refund_request_time_period' );
			if ( '1' == $time_period ) {
				return true ;
			}

			$order_date    = strtotime( self::$order->get_date_created() ) ;
			$restrict_date = $order_date + ( ( int ) get_option( 'hrr_refund_request_time_period_value' ) * 86400 ) ;
			if ( $restrict_date >= time() ) {
				return true ;
			}

			return false ;
		}

		/**
		 * Check if ordered user is matched with selected user/user role for refund.
				 * 
				 * @return bool
		 */
		public static function is_valid_user() {
			$user_type = get_option( 'hrr_refund_refundable_user' ) ;
			if ( '2' == $user_type ) {
				$user_ids = get_option( 'hrr_refund_included_user' ) ;
				if ( ! hrr_check_is_array( $user_ids ) ) {
					return true ;
				} else {
					if ( in_array( self::$order->get_user_id() , $user_ids ) ) {
						return true ;
					}
				}
			} else if ( '4' == $user_type ) {
				$user_roles = get_option( 'hrr_refund_included_user_role' ) ;
				if ( ! hrr_check_is_array( $user_roles ) ) {
					return true ;
				} else {
					return self::is_valid_user_role( self::$order->get_user_id() , $user_roles ) ;
				}
			} else {
				return true ;
			}

			return false ;
		}

		/**
		 * Check if order has selected product/category.
				 * 
				 * @return bool
		 */
		public static function check_product_and_category_valid_to_refund() {
			$product_type = get_option( 'hrr_refund_refundanable_product' ) ;
			if ( '2' == $product_type) {
				$product_ids = get_option( 'hrr_refund_included_product' ) ;
				if ( ! hrr_check_is_array( $product_ids ) ) {
					return true ;
				} else {
					return self::check_get_items( 'product' , $product_ids ) ;
				}
			} else if ( '4' == $product_type ) {
				$category = get_option( 'hrr_refund_included_category' ) ;
				if ( ! hrr_check_is_array( $category ) ) {
					return true ;
				} else {
					return self::check_get_items( 'category' , $category ) ;
				}
			} else {
				return true ;
			}

			return false ;
		}

		/**
		 * Check if order has sale item.
				 * 
				 * @return bool
		 */
		public static function check_product_sale_item_valid_to_refund() {
			return self::check_get_items( 'sale_item' , '' ) ;
		}

		/**
		 * Check if product/category/saleitem is matched with selected value.
				 * 
				 * @return bool
		 */
		public static function check_get_items( $post_type, $select_products ) {
			$bool = false ;
			foreach ( self::$order_items as $item ) {
				if ( 'product' == $post_type ) {
					$product_id = self::get_product_variation_id( $item ) ;
					return self::is_selected_product( $product_id , $select_products ) ;
				} elseif ( 'category' == $post_type) {
					return self::is_selected_category( $item[ 'product_id' ] , $select_products ) ;
				} elseif ( 'sale_item' == $post_type) {
					return self::is_sale_product( $item[ 'product_id' ] ) ;
				}
			}
			return $bool ;
		}

		/**
		 * Check if corresponding product is selected.
				 * 
				 * @return bool
		 */
		public static function is_selected_product( $product_id, $selected_products ) {
			$selected_products = hrr_check_is_array( $selected_products ) ? $selected_products : explode( ',' , $selected_products ) ;
			$product_obj       = wc_get_product( $product_id ) ;
			if ( ! is_object( $product_obj ) ) {
				return false ;
			}

			if ( 'simple' === $product_obj->get_type() ) {
				if ( in_array( $product_id , $selected_products ) ) {
					return true ;
				}
			} else if ( 'variation' === $product_obj->get_type() ) {
				$productid = $product_obj->get_id() ;
				if ( in_array( $product_id , $selected_products ) || in_array( $productid , $selected_products ) ) {
					return true ;
				}
			}

			return false ;
		}

		/**
		 * Check if corresponding category is selected.
				 * 
				 * @return bool
		 */
		public static function is_selected_category( $product_id, $selected_category ) {
			$selected_category = hrr_check_is_array( $selected_category ) ? $selected_category : explode( ',' , $selected_category ) ;
			$product_obj       = wc_get_product( $product_id ) ;
			if ( ! is_object( $product_obj ) ) {
				return false ;
			}

			$terms = get_the_terms( $product_id , 'product_cat' ) ;
			if ( ! hrr_check_is_array( $terms ) ) {
				return false ;
			}

			foreach ( $terms as $key => $term ) {
				if ( in_array( $term->term_id , $selected_category ) ) {
					return true ;
				}
			}
			return false ;
		}

		/**
		 * Check if order has sale product.
				 * 
				 * @return bool
		 */
		public static function is_sale_product( $product_id ) {                        
			$whole_product = wc_get_product( $product_id ) ;
			if ( ! is_object( $whole_product ) ) {
				return true ;
			}
						
			if ( ! $whole_product->is_on_sale() ) {
				return true;
			}

						$sale_item = get_option( 'hrr_refund_refund_for_sale_items' );
			if ( 'yes' == $sale_item) {
				return true ;
			}

			return false ;
		}

		/**
		 * Check if corresponding user role is selected.
				 * 
				 * @return bool
		 */
		public static function is_valid_user_role( $user_id, $selected_user_roles ) {
			$user_obj = get_userdata( $user_id ) ;
			if ( ! hrr_check_is_array( $user_obj->roles ) ) {
				return false ;
			}

			foreach ( $user_obj->roles as $role ) {
				if ( in_array( $role , $selected_user_roles ) ) {
					return true ;
				}
			}

			return false ;
		}

		/**
		 * Get product/variation id.
				 * 
				 * @return int
		 */
		public static function get_product_variation_id( $product ) {
			$product_id    = $product[ 'product_id' ] ;
			$whole_product = wc_get_product( $product_id ) ;
			if ( is_object( $whole_product ) ) {
				$product_id    = $whole_product->is_type( 'variable' ) ? $product[ 'variation_id' ] : $product[ 'product_id' ] ;
			}

			return $product_id ;
		}

	}

}
