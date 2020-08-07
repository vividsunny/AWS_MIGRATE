<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YWAU_Uploaded_Items' ) ) {
	
	/**
	 *
	 * @class   YWAU_Uploaded_Items
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Lorenzo Giuffrida
	 */
	class YWAU_Uploaded_Items {
		
		private $is_cart = true;
		
		private $current_item_id = 0;
		
		private $items = array();
		
		
		/**
		 * Constructor
		 *
		 * Initialize items
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function __construct() {
		}
		
		/**
		 * Load items into the current object, from cart or from a specific order
		 *
		 * @param bool   $is_cart it's a cart item
		 * @param int    $item_id the id of the object to check(it's an order item if scope is 'item' or and order id if
		 *                        $scope is 'order'
		 * @param string $scope   the scope of the operation
		 *
		 * @return array of items
		 */
		public function load_items( $is_cart = false, $item_id = 0, $scope = 'item' ) {
			
			$this->is_cart         = $is_cart;
			$this->current_item_id = $item_id;
			
			if ( ! $this->is_cart ) {
				
				if ( 'item' == $scope ) {
					
					$this->items = wc_get_order_item_meta ( $item_id, YWAU_METAKEY_ORDER_ITEM_FILES );
				} else if ( 'order' == $scope ) {
					$order       = wc_get_order ( $item_id );
					$this->items = yit_get_prop ( $order, YWAU_METAKEY_ORDER_ITEM_FILES );
				}
			} else {
				$this->items = WC ()->session->get ( YWAU_CART_UPLOAD_RESULTS );
			}
			
			return $this->get_items ();
		}
		
		/**
		 * Save items into the cart or attached to the order
		 *
		 * @param string $scope     check if the items belong to a product or to the entire order
		 * @param int    $object_id id of the object that the operation belong to
		 */
		public function save_items( $scope = 'item', $object_id = 0 ) {

			if ( ! $this->is_cart ) {
				
				if ( 'item' == $scope ) {
					
					wc_update_order_item_meta ( $this->current_item_id, YWAU_METAKEY_ORDER_ITEM_FILES, $this->items );
				} else if ( 'order' == $scope ) {
					$order = wc_get_order ( $object_id );
					yit_save_prop ( $order, YWAU_METAKEY_ORDER_ITEM_FILES, $this->items );
				}
			} else {
				
				WC ()->session->set ( YWAU_CART_UPLOAD_RESULTS, $this->items );
			}
		}
		
		public function item_exists( $item_id ) {
			return isset( $this->items[ $item_id ] );
		}
		
		public function get_item( $item_id, $rule_id ) {
			
			if ( $this->item_exists ( $item_id ) ) {
				if ( isset( $this->items[ $item_id ][ $rule_id ] ) ) {
					
					return new YWAU_Uploaded_Item( $this->items[ $item_id ][ $rule_id ] );
				}
			}
			
			return null;
		}
		
		/**
		 * @param $uploaded_item YWAU_Uploaded_Item
		 * @param $item_id       int
		 * @param $rule_id       int
		 */
		public function set_item( $uploaded_item, $item_id, $rule_id ) {
//			if ($this->items && isset($this->items[$item_id])) {
//				if (isset($this->items[ $item_id ][ $rule_id ])) {
//					$this->items[ $item_id ][ $rule_id ] = $uploaded_item->to_array();
//				}
//			}
			if (null == $uploaded_item) {
				return;
			}
			
			if ( ! $this->items ) {
				$this->items = array();
				$this->items[ $item_id ] = array();
			}
			$this->items[ $item_id ][ $rule_id ] = $uploaded_item->to_array ();
			
		}
		
		/**
		 * Retrieve all items
		 *
		 * @return array
		 */
		public function get_items() {
			$results = array();
			
			if ( $this->items ) {
				foreach ( $this->items as $key => $value ) {
					foreach ( $value as $item ) {
						$results[] = new YWAU_Uploaded_Item( $item );
					}
				}
			}
			
			return $results;
		}
		
		/**
		 * Retrieve all items for a product
		 *
		 * @return array
		 */
		public function get_items_by_key( $item_key ) {
			$results = array();
			
			if ( $this->items && isset( $this->items [ $item_key ] ) ) {
				foreach ( $this->items [ $item_key ] as $key => $value ) {
					$results[] = new YWAU_Uploaded_Item( $value );
				}
			}
			
			return $results;
		}
		
		/**
		 * Delete a specific item
		 *
		 * @param $item YWAU_Uploaded_Item
		 */
		public function delete_item( $item ) {
			if ( ! isset( $item ) ) {
				return;
			}
			
			if ( isset( $this->items[ $item->item_id ][ $item->rule_id ] ) ) {
				unset( $this->items[ $item->item_id ][ $item->rule_id ] );
			}
		}
	}
}