<?php
/**
 * My Account Handler.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Myaccount_Handler' ) ) {

	/**
	 * HRR_Myaccount_Handler Class.
	 */
	class HRR_Myaccount_Handler {

		/**
		 * Refund Requests endpoint name.
		 */
		public static $hrr_requests_endpoint = 'hrr-refund-requests' ;

		/**
		 * Refund Request Form endpoint name.
		 */
		public static $hrr_requests_form_endpoint = 'hrr-refund-request-form' ;

		/**
		 * Refund Request View endpoint name.
		 */
		public static $hrr_request_view_endpoint = 'hrr-refund-request-view' ;

		/**
		 * HRR_Myaccount_Handler Class initialization.
		 */
		public static function init() {
			//Display Request Button in My Orders.
			add_filter( 'woocommerce_my_account_my_orders_actions' , array( __CLASS__ , 'button_in_my_orders' ) , 10 , 2 ) ;
			//Display Request Button in View Order Page.
			add_action( 'woocommerce_order_details_after_order_table' , array( __CLASS__ , 'button_in_view_order_page' ) , 20 , 1 ) ;
			//Add Title for Refund Pages.
			add_filter( 'the_title' , array( __CLASS__ , 'add_endpoint_title' ) ) ;
			//Add EndPoint for Refund Pages.
			add_action( 'init' , array( __CLASS__ , 'add_custom_end_point' ) ) ;
			//Add Custom Query for Refund Pages.
			add_filter( 'query_vars' , array( __CLASS__ , 'add_custom_query_vars' ) , 0 ) ;
			//Flush and Rewrite the Rules.
			add_action( 'wp_loaded' , array( __CLASS__ , 'flush_rewrite_rules' ) ) ;
			//Create and Save Request Post.
			add_action( 'wp_ajax_hrr_refund_request' , array( __CLASS__ , 'request_save_data' ) ) ;
			//Add Custom Menu in My Account Page.
			add_filter( 'woocommerce_account_menu_items' , array( __CLASS__ , 'add_custom_myaccount_menu' ) ) ;
			//Display Refund Data in View Page.
			add_action( 'woocommerce_account_' . self::$hrr_request_view_endpoint . '_endpoint' , array( __CLASS__ , 'request_view_content' ) ) ;
			//Display Refund Request Form.
			add_action( 'woocommerce_account_' . self::$hrr_requests_form_endpoint . '_endpoint' , array( __CLASS__ , 'request_form_content' ) ) ;
			//Display Content for Custom Menu in My Account.
			add_action( 'woocommerce_account_' . self::$hrr_requests_endpoint . '_endpoint' , array( __CLASS__ , 'custom_my_account_menu_content' ) ) ;
			//Unsubscribe Email.
			add_action( 'wp_ajax_hrr_refund_unsubscribe' , array( __CLASS__ , 'unsubscribe_mail' ) ) ;

			//Display Unsubscribe Email Checkbox in My Account.
			$unsubscribe_option = get_option( 'hrr_refund_enable_unsubscribe_option' ) ;
			if ( 'yes' == $unsubscribe_option ) {
				add_action( 'woocommerce_before_my_account' , array( __CLASS__ , 'unsubscribe_option' ) ) ;
			}
		}

		/**
		 * Add Refund Button in My Orders.
		 * */
		public static function button_in_my_orders( $actions, $order ) {
			if ( ! $order || ! is_user_logged_in() ) {
				return $actions ;
			}

			if ( ! empty( $order->get_refunds() ) ) {
				return $actions ;
			}

			$enable_request = get_option( 'hrr_refund_refund_request' , 'no' ) ;

			if ( ( 'yes' == $enable_request ) && is_valid_for_refund( $order->get_id() ) ) {
				$actions[ 'whole-refund' ] = array(
					'url'  => wc_get_endpoint_url( 'hrr-refund-request-form' , $order->get_id() , wc_get_page_permalink( 'myaccount' ) ) ,
					'name' => get_option( 'hrr_refund_full_order_button_label' , 'Whole Refund' )
						) ;
			}

			return $actions ;
		}

		/**
		 * Add Refund Button in View Order Page.
		 * */
		public static function button_in_view_order_page( $order ) {
			if ( ! $order || ! is_user_logged_in() ) {
				return ;
			}

			if ( ! empty( $order->get_refunds() ) ) {
				return ;
			}

			$enable_request = get_option( 'hrr_refund_refund_request' , 'no' ) ;

			if ( ( 'yes' == $enable_request ) && is_valid_for_refund( $order->get_id() ) ) {
				hrr_get_template( 'button/whole-refund.php' , array( 'order' => $order ) ) ;
			}
		}

		/**
		 * Add Refund Button in Request Table.
		 * */
		public static function button_in_request_table( $request_id ) {
			if ( ! $request_id || ! is_user_logged_in() ) {
				return ;
			}

			//View request Button
			hrr_get_template( 'button/view-refund.php' , array( 'request_id' => $request_id ) ) ;
		}

		/**
		 * Rewrite Refund Endpoint.
		 */
		public static function add_custom_end_point() {
			add_rewrite_endpoint( self::$hrr_requests_endpoint , EP_ROOT | EP_PAGES ) ;
			add_rewrite_endpoint( self::$hrr_requests_form_endpoint , EP_ROOT | EP_PAGES ) ;
			add_rewrite_endpoint( self::$hrr_request_view_endpoint , EP_ROOT | EP_PAGES ) ;
		}

		/**
		 * Add custom Query var for Refund.
		 */
		public static function add_custom_query_vars( $vars ) {
			$vars[] = self::$hrr_requests_endpoint ;
			$vars[] = self::$hrr_request_view_endpoint ;
			$vars[] = self::$hrr_requests_form_endpoint ;

			return $vars ;
		}

		/**
		 * Flush Rewrite Rules.
		 */
		public static function flush_rewrite_rules() {
			flush_rewrite_rules() ;
		}

		/**
		 * Add Endpoint Title.
		 */
		public static function add_endpoint_title( $title ) {
			global $wp_query ;

			$refund_requests     = isset( $wp_query->query_vars[ self::$hrr_requests_endpoint ] ) ;
			$refund_request_form = isset( $wp_query->query_vars[ self::$hrr_requests_form_endpoint ] ) ;
			$refund_request_view = isset( $wp_query->query_vars[ self::$hrr_request_view_endpoint ] ) ;

			if ( $refund_requests && is_main_query() && in_the_loop() && is_account_page() ) {
				$title = esc_html__( 'My Refund Requests' , 'refund' ) ;
				remove_filter( 'the_title' , array( 'HRR_Myaccount_Handler' , 'endpoint_title' ) ) ;
			} elseif ( $refund_request_form && is_main_query() && in_the_loop() && is_account_page() ) {
				$title = esc_html__( 'Refund Request Form' , 'refund' ) ;
				remove_filter( 'the_title' , array( 'HRR_Myaccount_Handler' , 'endpoint_title' ) ) ;
			} elseif ( $refund_request_view && is_main_query() && in_the_loop() && is_account_page() ) {
				$request_id = ! empty( $wp_query->query_vars[ 'hrr-refund-request-view' ] ) ? $wp_query->query_vars[ 'hrr-refund-request-view' ] : '' ;
				/* translators: %s: Refund Id */
				$title      = sprintf( esc_html__( 'Refund Request #%s' , 'refund' ) , $request_id ) ;
				remove_filter( 'the_title' , array( 'HRR_Myaccount_Handler' , 'endpoint_title' ) ) ;
			}

			return $title ;
		}

		/**
		 * Add Custom My account Menu.
		 */
		public static function add_custom_myaccount_menu( $items ) {
			$logout = $items[ 'customer-logout' ] ;

			unset( $items[ 'customer-logout' ] ) ;

			$items[ self::$hrr_requests_endpoint ] = esc_html__( 'Refund Request' , 'refund' ) ;
			$items[ 'customer-logout' ]            = $logout ;

			return $items ;
		}

		/**
		 * Display Custom Menu Content.
		 */
		public static function custom_my_account_menu_content() {
			$args = array(
				'posts_per_page' => -1 ,
				'post_type'      => 'hrr_request' ,
				'post_status'    => array( 'hrr-new' , 'hrr-accept' , 'hrr-reject' , 'hrr-on-hold' , 'hrr-processing' ) ,
				'order'          => 'DESC' ,
				'author'         => get_current_user_id() ,
				'fields'         => 'ids'
					) ;

			$request_data = get_posts( $args ) ;

			//Display refund request table.
			hrr_get_template( 'myaccount/refund-request-table.php' , array( 'request_data' => $request_data ) ) ;
		}

		/**
		 * Display Refund request View Content.
		 */
		public static function request_view_content() {
			global $wp_query ;
			$request_id = ! empty( $wp_query->query_vars[ 'hrr-refund-request-view' ] ) ? $wp_query->query_vars[ 'hrr-refund-request-view' ] : '' ;
			$request    = hrr_get_request( $request_id ) ;
			$order_id   = $request->get_order_id() ;

			//Display Content for View Request Page.
			hrr_get_template( 'myaccount/refund-request-view.php' , array( 'request_obj' => $request , 'order' => wc_get_order( $order_id ) ) ) ;
		}

		/**
		 * Display Refund request Form Content.
		 */
		public static function request_form_content() {
			global $wp_query ;
			$order_id = ! empty( $wp_query->query_vars[ 'hrr-refund-request-form' ] ) ? $wp_query->query_vars[ 'hrr-refund-request-form' ] : '' ;

			//Display Request Form.
			hrr_get_template( 'myaccount/refund-request-form.php' , array( 'order_id' => $order_id , 'order' => wc_get_order( $order_id ) ) ) ;
		}

		/**
		 * Create Refund Request Post.
		 */
		public static function request_save_data() {
			check_ajax_referer( 'hrr-refund-request' , 'hrr_security' ) ;

			try {
				do_action( 'hrr_file_validation' , $_FILES , 'request' ) ;

				$bool              = false ;
				$item_count        = 0 ;
				$total_qty_send    = 0 ;
				$refund_item_count = 0 ;
				$user_id           = isset( $_POST[ 'hrr_refund_user_id' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_user_id' ] ) ) : 0 ;
				$order_id          = isset( $_POST[ 'hrr_refund_order_id' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_order_id' ] ) ) : 0 ;
				$request_as        = isset( $_POST[ 'hrr_refund_request_as' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_request_as' ] ) ) : '' ;
				$details           = isset( $_POST[ 'hrr_refund_form_details' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_form_details' ] ) ) : '' ;
				$reasons           = isset( $_POST[ 'hrr_refund_general_reasons' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_general_reasons' ] ) ) : '' ;
				$refund_amount     = isset( $_POST[ 'hrr_refund_total' ] ) ? wc_clean( wp_unslash( $_POST[ 'hrr_refund_total' ] ) ) : 0 ;
				$order             = wc_get_order( $order_id ) ;
				$order_items       = $order->get_items() ;
				$line_item_ids     = isset( $_POST[ 'line_item_ids' ] ) ? json_decode( wc_clean( wp_unslash( $_POST[ 'line_item_ids' ] ) ) , true ) : array() ;
				$line_items        = isset( $_POST[ 'line_items' ] ) ? json_decode( wc_clean( wp_unslash( $_POST[ 'line_items' ] ) ) , true ) : array() ;

				foreach ( $order_items as $item_id => $item ) {
					$already_item_send = wc_get_order_item_meta( $item_id , 'hr_refund_request_item' ) ;
					$qty_send          = ( int ) wc_get_order_item_meta( $item_id , 'hr_refund_request_item_qty' ) ;
					$original_quantity = isset( $item[ 'quantity' ] ) ? $item[ 'quantity' ] : $item[ 'qty' ] ;
					$item_count        += $original_quantity ;
					if ( 'yes' == $already_item_send ) {
						$total_qty_send    += $qty_send ;
					}

					if ( ! isset( $line_items[ $item_id ] ) ) {
						continue ;
					}

					$refund_item_count += $line_items[ $item_id ] ;
				}

				if ( ( ( $item_count - $total_qty_send ) - $refund_item_count ) <= 0 ) {
					$bool = true ;
				}

								$whole_order = esc_html__('Whole Order', 'refund');
								
				$type = apply_filters( 'hrr_request_type' , $whole_order , $order , $item_count , $refund_item_count , $refund_amount ) ;

				$meta_args = apply_filters( 'hrr_request_metas' , array(
					'hrr_order_id'      => $order_id ,
					'hrr_user_id'       => $user_id ,
					'hrr_mode'          => $request_as ,
					'hrr_type'          => $type ,
					'hrr_line_item'     => $line_items ,
					'hrr_line_item_ids' => $line_item_ids ,
					'hrr_total'         => $refund_amount ,
					'hrr_currency'      => $order->get_currency() ,
					'hrr_old_status'    => 'hrr-new'
						) , $_POST , $_FILES ) ;

				$post_args = array(
					'post_parent'  => $order_id ,
					'post_title'   => $reasons ,
					'post_content' => $details ,
						) ;

				$request_id = hrr_create_new_request( $meta_args , $post_args ) ;
				if ( $request_id ) {
					foreach ( $order_items as $item_id => $item ) {
						if ( ! isset( $line_items[ $item_id ] ) ) {
							continue ;
						}

						wc_update_order_item_meta( $item_id , 'hr_refund_request_item' , 'yes' ) ;
						$prevoius_qty = ( int ) wc_get_order_item_meta( $item_id , 'hr_refund_request_item_qty' ) ;
						wc_update_order_item_meta( $item_id , 'hr_refund_request_item_qty' , $prevoius_qty + $line_items[ $item_id ] ) ;
					}

										$whole_order = esc_html__('Whole Order', 'refund');
					if ( ( $whole_order || $bool ) == $type ) {
						update_post_meta( $order_id , 'hr_refund_request_already_send' , $request_id ) ;
					}

					do_action( 'hrr_refund_request_created' , $request_id ) ;
				}
				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Display Unsubscribe Option in MyAccount Page.
		 */
		public static function unsubscribe_option() {
			ob_start() ;
			?>
			<div class="hrr_refund_unsubscribe">
				<h3><?php echo esc_html( get_option( 'hrr_refund_unsub_heading' ) ) ; ?></h3>
				<p>
					<input type="checkbox" id="hrr_refund_unsubscribed_id" value="yes" <?php checked( 'yes' , get_user_meta( get_current_user_id() , 'hrr_refund_unsubscribed_id' , true ) ) ; ?>/>
					<?php echo esc_html( get_option( 'hrr_refund_unsub_label' ) ) ; ?>
				</p>
			</div>
			<?php
			echo ob_get_clean() ;
		}

		/**
		 * Unsubscribe User.
		 */
		public static function unsubscribe_mail() {
			check_ajax_referer( 'hrr-unsubscribe-email' , 'hrr_security' ) ;

			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'dataclicked' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'refund' ) ) ;
				}

				$userid = get_current_user_id() ;
				if ( 'false' == wc_clean( $_POST[ 'dataclicked' ] ) ) {
					update_user_meta( $userid , 'hr_refund_unsubscribed_id' , 'yes' ) ;
					$message = esc_html__( 'Unsubscribed Successfullly' , 'refund' ) ;
				} else {
					delete_user_meta( $userid , 'hr_refund_unsubscribed_id' ) ;
					$message = esc_html__( 'Subscribed Successfullly' , 'refund' ) ;
				}
				wp_send_json_success( array( 'alert_message' => $message ) ) ;
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

	}

	HRR_Myaccount_Handler::init() ;
}
