<?php
/**
 * Refund Request View Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HR_Refund_Request_View' ) ) {

	/**
	 * HR_Refund_Request_View Class.
	 */
	class HR_Refund_Request_View {

		/**
		 * HR_Refund_Request_View Class initialization.
		 */
		public static function init() {
			add_action( 'admin_init' , array( __CLASS__ , 'remove_editor_and_title' ) ) ;
			add_action( 'add_meta_boxes' , array( __CLASS__ , 'add_meta_boxes' ) , 10 , 2 ) ;
			add_action( 'wp_ajax_hrr_update_status' , array( __CLASS__ , 'update_refund_status' ) ) ;
			add_action( 'wp_ajax_hrr_manual_refund' , array( __CLASS__ , 'manual_refund_request' ) ) ;
		}

		/**
		 * Remove Editor and Title Meta boxes.
		 */
		public static function remove_editor_and_title() {
			$remove_fields = array( 'editor' , 'title' ) ;
			foreach ( $remove_fields as $remove_field ) {
				//Remove Supports for Request Post Type.
				remove_post_type_support( 'hrr_request' , $remove_field ) ;
			}
		}

		/**
		 * Add Required Meta Boxes.
		 */
		public static function add_meta_boxes( $post_type, $post ) {
			if ( 'hrr_request' == $post_type ) {
				//Remove submit button.
				remove_meta_box( 'submitdiv' , 'hrr_request' , 'side' ) ;

				//Add meta box for Request Post.
				add_meta_box( 'hrr_refund_table' , esc_html__( 'Refund Request' , 'refund' ) , array( __CLASS__ , 'refund_request_table' ) , 'hrr_request' , 'normal' ) ;
				add_meta_box( 'hrr_refund_conversation' , esc_html__( 'Refund Request Conversation' , 'refund' ) , array( __CLASS__ , 'request_conversation' ) , 'hrr_request' , 'normal' ) ;

				add_meta_box( 'hrr_refund_request_data' , esc_html__( 'Refund Data' , 'refund' ) , array( __CLASS__ , 'refund_data' ) , 'hrr_request' , 'side' , 'low' ) ;
				add_meta_box( 'hrr_refund_submit_button' , esc_html__( 'Refund Status' , 'refund' ) , array( __CLASS__ , 'refund_status' ) , 'hrr_request' , 'side' , 'high' ) ;
			}
		}

		/**
		 * Refund Reason Table.
		 */
		public static function refund_request_table( $post ) {
			if ( ! $post ) {
				return ;
			}

			$request = hrr_get_request( $post->ID ) ;
			$order   = wc_get_order( $request->get_order_id() ) ;
			if ( $order ) {
				$tax_enabled       = ( 'yes' == get_option( 'hrr_refund_refund_tax' ) ) ;
				/* translators: %d: Refunded Order Id */
				$title_attr        = sprintf( esc_html__( 'Go to order #%d' , 'refund' ) , $request->get_order_id() ) ;
				$columns           = array( esc_html__('Image', 'refund' ) , esc_html__('Product Name', 'refund' ) , esc_html__('Item Value', 'refund' ) , esc_html__('Qty', 'refund' ) , esc_html__('Total', 'refund' ) , esc_html__('Refund Qty', 'refund' ) , esc_html__('Refund total', 'refund' ) ) ;
				$partial_enabled   = apply_filters( 'hrr_partial_enabled' , false ) ;
				$readonly          = ( ! $partial_enabled ) ? 'readonly="readonly"' : '' ;
				$line_items        = $request->get_line_item() ;
				$total_value       = $order->get_total() - $order->get_total_refunded() ;
				$colspan           = ( $partial_enabled ) ? 5 : 4 ;
				$payment_gateway   = wc_get_payment_gateway_by_order( $order ) ;
				$supported_gateway = ( false !== $payment_gateway ) && $payment_gateway->supports( 'refunds' ) ;
				$gateway_name      = ( false !== $payment_gateway ) ? ( ! empty( $payment_gateway->method_title ) ? $payment_gateway->method_title : $payment_gateway->get_title() ) : esc_html__( 'Payment Gateway' , 'refund' ) ;
								$whole_order = esc_html__('Whole Order', 'refund');
				if ( $whole_order == $request->get_type() ) {
					$disable_button = ( empty( $total_value ) ) ? 'disabled=disabled' : '' ;
				} else {
					$disable_button = ( ( $request->get_total() == $order->get_total_refunded() ) ) ? 'disabled=disabled' : '' ;
				}
				$automatic_tip = $supported_gateway ? '' : 'data-tip="' . esc_attr__( 'The payment gateway used to place this order does not support automatic refunds.' , 'refund' ) . '" disabled="disabled"' ;
				$manual_tip    = 'data-tip="' . esc_attr__( 'You will need to manually issue a refund through your payment gateway after using this.' , 'refund' ) . '"' ;
				//Display refund request table.
				include_once(HRR_ABSPATH . 'inc/admin/menu/views/refund-request-table.php') ;
			} else {
				echo '<h1>' . esc_html__( 'Order Data is not available' , 'refund' ) . '</h1>' ;
			}
		}

		/**
		 * Refund Status change.
		 */
		public static function refund_status( $post ) {
			if ( ! $post ) {
				return ;
			}
			?>
			<div class="hrr-refund-status-change">
				<ul>
					<li class="wide" id='major-publishing-actions'>
						<p>
							<span class="hrr_refund_current_status">
								<?php esc_html_e( 'Current Status' , 'refund' ) ; ?>: <b><?php echo hrr_display_status( get_post_status( $post->ID ) ) ; ?></b>
							</span>
						</p>
					</li>
					<li class="wide" id="hrr-refund-actions">
						<div class="submitbox" id="submitpost">
							<div id='major-publishing-actions'>
								<div id="publishing-action">
									<select id='hrr_status' name='hrr_status'>
										<option value=""><?php esc_html_e( 'Actions' , 'refund' ) ; ?></option>
										<option value="hrr-new"><?php esc_html_e( 'New' , 'refund' ) ; ?></option>
										<option value="hrr-accept"><?php esc_html_e( 'Accept' , 'refund' ) ; ?></option>
										<option value="hrr-reject"><?php esc_html_e( 'Reject' , 'refund' ) ; ?></option>
										<option value="hrr-processing"><?php esc_html_e( 'Processing' , 'refund' ) ; ?></option>
										<option value="hrr-on-hold"><?php esc_html_e( 'On-Hold' , 'refund' ) ; ?></option>
									</select>
									<?php submit_button( esc_html__( 'Update' , 'refund' ) , 'primary large' , 'hrr_submit' , false ) ; ?>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<?php
		}

		/**
		 * Refund Request Conversation.
		 */
		public static function request_conversation( $post ) {
			if ( ! $post ) {
				return ;
			}
			$request_obj      = hrr_get_request( $post->ID ) ;
			?>
			<form id='hrr-conversation-form' method="post" enctype="multipart/form-data" >
				<div class="hrr_refund_reply_request">
					<div class="hrr-refund-reply-request-tickets">
						<div class="hrr-refund-request-created hrr-refund-reply-ticket">
							<div class="hrr_refund_avator">
								<a href="<?php echo esc_url( admin_url( 'user-edit.php' ) . '?user_id=' . $post->post_author ) ; ?>"><?php echo get_avatar( $post->post_author , '50' ) ; ?></a>
							</div>
							<div class="hrr_refund_creator_name">
								<?php
								/* translators: 1:Username 2:Email */
								echo sprintf( esc_html__( 'Created by: %1$s ( %2$s )' , 'refund' ) , get_userdata( $post->post_author )->display_name , get_userdata( $post->post_author )->user_email ) ;
								?>
							</div>
							<div class="hrr_refund_created_on" title="<?php echo esc_attr( $post->post_date ) ; ?>">
								<?php
								/* translators: 1:Date 2:Time */
								echo sprintf( esc_html__( 'Created on: %1$s ( %2$s ago )' , 'refund' ) , $post->post_date , human_time_diff( strtotime( $post->post_date ) , ( current_time( 'timestamp' ) ) ) ) ;
								?>
							</div>
							<?php do_action( 'hrr_refund_system_before_request_content' , $post ) ; ?>
							<hr>
							<h1><?php echo esc_html( $post->post_title ) ; ?></h1>
							<div class="hrr_refund_request_content">
								<?php
								$find_array       = array( '<?' , '?>' ) ;
								$replace_array    = array( '&lt;?' , '?&gt;' ) ;
								echo wpautop( str_replace( $find_array , $replace_array , $post->post_content ) , true ) ;
								?>
							</div>
							<?php do_action( 'hrr_conversation_attachments' , $request_obj ) ; ?>
						</div>
						<?php
						$conversation_ids = hrr_get_conversation_ids( $request_obj->get_id() ) ;

						if ( hrr_check_is_array( $conversation_ids ) ) {
							foreach ( $conversation_ids as $conversation_id ) {
								echo self::reply_layout( $conversation_id ) ;
							}
						}
						?>
					</div>
					<?php do_action( 'hrr_after_admin_converation' ) ; ?>
				</div>
			</form>
			<?php
		}

		/**
		 * Display request Data.
		 */
		public static function refund_data( $post ) {
			if ( ! $post ) {
				return ;
			}

			$request = hrr_get_request( $post->ID ) ;
			$order   = wc_get_order( $request->get_order_id() ) ;
			if ( $order ) {
				//Display refund request table.
				include_once(HRR_ABSPATH . 'inc/admin/menu/views/refund-data.php') ;
			} else {
				echo '<h3>' . esc_html__( 'Order Data is not available' , 'refund' ) . '</h3>' ;
			}
		}

		/**
		 * Format reply layout.
		 */
		public static function reply_layout( $conversation_id ) {
			ob_start() ;
			$post_object   = hrr_get_conversation( $conversation_id ) ;
			$user_obj      = get_userdata( $post_object->get_user_id() ) ;
			$firstname     = ( '' != $user_obj->display_name ) ? $user_obj->display_name : $user_obj->user_login ;
			$class_name    = ( user_can( $user_obj , 'manage_woocommerce' ) ) ? 'hrr-refund-admin' : 'hrr-refund-customer' ;
			?>
			<div class="hrr-refund-reply-ticket hrr-refund-reply-ticket-<?php echo esc_attr( $post_object->get_id() . ' ' . $class_name ) ; ?>" >
				<div class="hrr_refund_reply_user_details">
					<?php echo get_avatar( $post_object->get_user_id() , '50' ) ; ?><br>
					<span class="hrr_refund_reply_user_name"><?php echo esc_html( $firstname ) ; ?></span>
					<span class="hrr_refund_reply_date">
						<?php
						/* translators: 1:Date 2:Time */
						echo sprintf( esc_html__( 'Replied on: %1$s ( %2$s ago )' , 'refund' ) , $post_object->get_created_date() , human_time_diff( strtotime( $post_object->get_created_date() ) , ( current_time( 'timestamp' ) ) ) ) ;
						?>
					</span>
				</div>
				<hr>
				<div>
					<?php
					$find_array    = array( '<?' , '?>' ) ;
					$replace_array = array( '&lt;?' , '?&gt;' ) ;
					echo wpautop( str_replace( $find_array , $replace_array , $post_object->get_message() ) , true ) ;
					?>
				</div>
				<?php do_action( 'hrr_conversation_attachments' , $post_object ) ; ?>
			</div>
			<?php
			$content       = ob_get_contents() ;
			ob_end_clean() ;
			return $content ;
		}

		/**
		 * Do Refund Request.
		 */
		public static function manual_refund_request() {
			check_ajax_referer( 'hrr-button-nonce' , 'hrr_security' ) ;

			$refund = false ;
			try {
				if ( ! isset( $_POST ) ) {
					throw new exception( __( 'Invalid Request' , 'refund' ) ) ;
				}

				$order_id             = isset( $_POST[ 'order_id' ] ) ? absint( $_POST[ 'order_id' ] ) : 0 ;
				$request_id           = isset( $_POST[ 'request_id' ] ) ? absint( $_POST[ 'request_id' ] ) : 0 ;
				$old_status           = get_post_status( $request_id ) ;
				$gateway_type         = isset( $_POST[ 'api_refund' ] ) ? wc_clean( wp_unslash( $_POST[ 'api_refund' ] ) ) : '' ;
				$api_refund           = ( 'gateway' == $gateway_type ) ? true : false ;
				$restock_items        = isset( $_POST[ 'restock_refunded_items' ] ) ? ( 'true' === wc_clean( wp_unslash( $_POST[ 'restock_refunded_items' ] ) ) ) : false ;
				$line_item_qtys       = isset( $_POST[ 'line_item_qtys' ] ) ? json_decode( wc_clean( wp_unslash( $_POST[ 'line_item_qtys' ] ) ) , true ) : array() ;
				$line_item_totals     = isset( $_POST[ 'line_item_totals' ] ) ? json_decode( wc_clean( wp_unslash( $_POST[ 'line_item_totals' ] ) ) , true ) : array() ;
				$line_item_tax_totals = isset( $_POST[ 'line_item_tax_totals' ] ) ? json_decode( wc_clean( wp_unslash( $_POST[ 'line_item_tax_totals' ] ) ) , true ) : array() ;
				$refund_amount        = isset( $_POST[ 'refund_amount' ] ) ? wc_format_decimal( absint( $_POST[ 'refund_amount' ] ) , wc_get_price_decimals() ) : 0 ;
				$request_obj          = hrr_get_request( $request_id ) ;
				$order                = wc_get_order( $order_id ) ;
				$order_items          = $order->get_items() ;
				$max_refund           = wc_format_decimal( ( float ) $order->get_total() - $order->get_total_refunded() , wc_get_price_decimals() ) ;

				if ( ! $refund_amount || $max_refund < $refund_amount || 0 > $refund_amount ) {
					throw new exception( esc_html__( 'Invalid refund amount' , 'refund' ) ) ;
				}

				//Prepare line items which we are refunding.
				$line_items           = array() ;
				$line_item_qty_keys   = array_keys( $line_item_qtys ) ;
				$line_item_total_keys = array_keys( $line_item_totals ) ;
				$item_ids             = array_unique( array_merge( $line_item_qty_keys , $line_item_total_keys ) ) ;

				foreach ( $item_ids as $item_id ) {

					$line_items[ $item_id ] = array( 'qty' => 0 , 'refund_total' => 0 , 'refund_tax' => array() ) ;

					if ( isset( $line_item_qtys[ $item_id ] ) ) {
						$line_items[ $item_id ][ 'qty' ] = max( $line_item_qtys[ $item_id ] , 0 ) ;
					}

					if ( isset( $line_item_totals[ $item_id ] ) ) {
						$line_items[ $item_id ][ 'refund_total' ] = wc_format_decimal( $line_item_totals[ $item_id ] ) ;
					}

					if ( isset( $line_item_tax_totals[ $item_id ] ) ) {
						$line_items[ $item_id ][ 'refund_tax' ] = array_filter( array_map( 'wc_format_decimal' , $line_item_tax_totals[ $item_id ] ) ) ;
					}
				}
				// Create the refund object.
				$refund = wc_create_refund( array(
					'amount'         => $refund_amount ,
					'reason'         => $request_obj->get_reason() ,
					'order_id'       => $order_id ,
					'line_items'     => $line_items ,
					'refund_payment' => $api_refund ,
					'restock_items'  => $restock_items ,
						) ) ;

				if ( is_wp_error( $refund ) ) {
					throw new Exception( $refund->get_error_message() ) ;
				} else {
					$id = hrr_update_request( $request_id , array() , array( 'post_status' => 'hrr-accept' ) ) ;
					if ( $id ) {
						update_post_meta( $request_id , 'hr_refund_request_old_status' , $old_status ) ;
						//Refund after success.
						do_action( 'hrr_refund_after_refund_success' , $refund_amount , $request_id , $order_id , $line_items , $refund->get_id() , $gateway_type ) ;

						do_action( 'hrr_refund_request_accepted' , $request_id ) ;
					}
				}

				wp_send_json_success() ;
			} catch ( Exception $e ) {
				if ( $refund && is_a( $refund , 'WC_Order_Refund' ) ) {
					wp_delete_post( $refund->get_id() , true ) ;
				}

				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Change Refund Request Status.
		 */
		public static function update_refund_status() {
			check_ajax_referer( 'hrr-status-nonce' , 'hrr_security' ) ;

			try {
				if ( ! isset( $_POST ) ) {
					throw new exception( __( 'Invalid Request' , 'refund' ) ) ;
				}

				$new_status = isset( $_POST[ 'status' ] ) ? wc_clean( wp_unslash( $_POST[ 'status' ] ) ) : '' ;
				$request_id = isset( $_POST[ 'request_id' ] ) ? absint( $_POST[ 'request_id' ] ) : 0 ;
				$old_status = get_post_status( $request_id ) ;

				$id = hrr_update_request( $request_id , array() , array( 'post_status' => $new_status ) ) ;
				if ( $id && $new_status != $old_status ) {
					update_post_meta( $request_id , 'hr_refund_request_old_status' , $old_status ) ;

					do_action( 'hrr_refund_request_status_changed' , $request_id ) ;
					do_action( 'hrr_refund_request_status_changed_to_' . $new_status , $request_id ) ;
				}
				wp_send_json_success( array( 'id' => $id ) ) ;
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

	}

	HR_Refund_Request_View::init() ;
}
