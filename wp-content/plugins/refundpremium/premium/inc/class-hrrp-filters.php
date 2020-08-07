<?php
/**
 * Refund Filters.
 */
if ( ! class_exists( 'HRRP_Filters' ) ) {

	/**
	 * Class.
	 */
	class HRRP_Filters {

		/**
		 * Class initialization.
		 */
		public static function init() {
			//Check if partial refund is enabled.
			add_filter( 'hrr_partial_enabled' , array( __CLASS__ , 'allow_partial_refund' ) , 10 , 1 ) ;
			//Check if it is Partial/Whole Order.
			add_filter( 'hrr_request_type' , array( __CLASS__ , 'refund_request_type' ) , 10 , 5 ) ;
			//Check if order is valid for Refund.
			add_filter( 'hrr_is_valid_refund' , array( 'HRRP_Button_Restriction' , 'is_valid_order' ) , 10 , 2 ) ;
						//Check if Reason Field to be displayed or not.
						add_filter( 'hrr_is_reason_field_enabled' , array( __CLASS__ , 'display_reason_field' ) , 10 , 1 ) ;
						//Check if Reason Field mandatory or not.
						add_filter( 'hrr_is_reason_field_mandatory' , array( __CLASS__ , 'mandatory_reason_field' ) , 10 , 1 ) ;

			$enable_partial_refund = get_option( 'hrr_refund_partial_refund' , 'yes' ) ;
			if ( 'no' != $enable_partial_refund ) {
				add_action( 'hrr_admin_item_column_start' , array( __CLASS__ , 'item_checkbox_for_admin' ) , 10 , 3 ) ;
				add_action( 'hrr_frontend_item_column_start' , array( __CLASS__ , 'item_checkbox_for_user' ) , 10 , 3 ) ;

				add_action( 'hrr_admin_item_column_header_start' , array( __CLASS__ , 'checkbox_for_admin' ) , 10 , 1 ) ;
				add_action( 'hrr_frontend_item_column_header_start' , array( __CLASS__ , 'checkbox_for_user' ) , 10 , 1 ) ;
			}
		}

		/**
		 * Check Partial refund is enabled.
		 */
		public static function allow_partial_refund( $bool ) {
			$partial_refund = get_option( 'hrr_refund_partial_refund' , 'yes' ) ;
			return ( 'yes' == $partial_refund ) ;
		}

		/**
		 * Refund Request Type.
		 */
		public static function refund_request_type( $type, $order, $item_count, $refund_item_count, $refund_amount ) {
			if ( ( ( $item_count - $refund_item_count ) > 0 ) && ( ( $order->get_total() - $refund_amount ) > 0 ) ) {
				$type = esc_html__( 'Partial Order' , 'refund' ) ;
			}

			return $type ;
		}
				
			   /**
				* Display Reason field.
				*/
		public static function display_reason_field( $bool ) {
			$enabled = get_option( 'hrr_refund_enable_reason_in_detail' , 'yes' ) ;

			return ( 'yes' == $enabled ) ;
		}

			   /**
				* Validate refund reason field
				*/
		public static function mandatory_reason_field( $bool ) {
			$enabled = get_option( 'hrr_refund_mandatory_reason_field' , 'yes' ) ;

			return ( 'yes' == $enabled ) ;
		}

		/**
		 * Add item checkbox for Admin.
		 */
		public static function item_checkbox_for_admin( $bool, $order, $item_id ) {
						$check_attr = ( $bool ) ? "checked='checked'" : "disabled='disabled";
			?>
			<td class='hrr_refund_item_check_box'>
				<input type='checkbox' id='hrr_refund_enable_product' class='hrr_refund_enable_product hrr_refund_select' <?php echo esc_attr($check_attr); ?>/>
			</td>
			<?php
		}

		/**
		 * Add item checkbox for User.
		 */
		public static function item_checkbox_for_user( $bool, $order, $item_id ) {
			?>
			<td class='hrr_refund_item_check_box'>
				<input type='checkbox' id='hrr_refund_enable_product' class='hrr_refund_enable_product hrr_refund_select' checked="checked"/>
			</td>
			<?php
		}

		/**
		 * Add Header checkbox for Admin.
		 */
		public static function checkbox_for_admin( $order ) {
			?>
			<th class='hrr_refund_select_product'>
				<input type='checkbox' id='hrr_refund_select_all_product' class='hrr_refund_select_all_product' checked="checked"/>
			</th>
			<?php
		}

		/**
		 * Add Header checkbox for User.
		 */
		public static function checkbox_for_user( $order ) {
			?>
			<th class='hrr_refund_select_product'>
				<input type='checkbox' id='hrr_refund_select_all_product' class='hrr_refund_select_all_product' checked="checked"/>
			</th>
			<?php
		}

	}

	HRRP_Filters::init() ;
}
