<?php
/**
 * Refund Request View
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}
do_action( 'hrr_before_frontend_refund_request_view' , $order ) ;
?>
<div>
	<div class = 'hrr-refund-form-field'>
		<table class = 'shop_table' cellspacing = "0" cellpadding = "6">
			<tr>
				<th>
					<?php echo esc_html__( 'Status' , 'refund' ) ; ?>
				</th>
				<td>
					<?php echo hrr_display_status( $request_obj->get_status() ) ; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo esc_html__( 'Amount' , 'refund' ) ; ?>
				</th>
				<td>
					<?php echo wc_price( $request_obj->get_total() , $request_obj->get_currency() ) ; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo esc_html__( 'Type' , 'refund' ) ; ?>
				</th>
				<td>
					<?php echo esc_html( $request_obj->get_type() ) ; ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo esc_html__( 'Request as' , 'refund' ) ; ?>
				</th>
				<td>
					<?php echo esc_html( $request_obj->get_mode() ) ; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="hrr-view-order-btn">
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'view-order' , $request_obj->get_order_id() , wc_get_page_permalink( 'myaccount' ) ) ) ; ?>" class="button"><?php esc_html_e( 'View Order' , 'refund' ) ; ?></a>
				</td>
			</tr>
		</table>
	</div>
	<hr>
	<div class='hrr_refund_reply_request'>
			<form id='hrr-conversation-form' method="post" enctype="multipart/form-data" >
		<div>
			<p><?php echo '<b>' . esc_html__( 'Message History' , 'refund' ) . '</b>' ; ?></p>
			<div class="hrr-refund-reply-request-tickets">
				<div class="hrr-refund-request-created">
					<div class="hrr_refund_avator">
						<a href="<?php echo esc_url( admin_url( 'user-edit.php' ) . '?user_id=' . $request_obj->get_user_id() ) ; ?>"><?php echo get_avatar( $request_obj->get_user_id() , '50' ) ; ?></a>
					</div>
					<div class="hrr_refund_creator_name">
						<?php 
												/* translators: 1:Username 2:Useremail */
												echo sprintf( esc_html__( 'Created by: %1$s ( %2$s )' ) , $request_obj->get_user()->display_name , $request_obj->get_user()->user_email ) ; 
						?>
					</div>
					<hr>
					<div class="hrr_refund_request_content">
						<?php
						$find_array       = array( '<?' , '?>' ) ;
						$replace_array    = array( '&lt;?' , '?&gt;' ) ;
						echo wpautop( str_replace( $find_array , $replace_array , $request_obj->get_reason() ) , true ) ;
						?>
					</div>
										<?php do_action ( 'hrr_conversation_attachments' , $request_obj ); ?>
				</div>
				<?php
				$conversation_ids = hrr_get_conversation_ids($request_obj->get_id()) ;

				if ( hrr_check_is_array( $conversation_ids ) ) :
					foreach ( $conversation_ids as $conversation_id ) :
						$post_object = hrr_get_conversation( $conversation_id ) ;
						if ( ! is_object( $post_object ) ) {
							continue ;
						}

						$user_obj      = get_userdata( $post_object->get_user_id() ) ;
						$firstname     = ( '' != $user_obj->display_name ) ? $user_obj->display_name : $user_obj->user_login ;
						$msg_class     = ( user_can( $user_obj , 'manage_woocommerce' ) ) ? 'hrr-refund-admin' : 'hrr-refund-customer' ;
						?>
						 
						<div class = "hrr-refund-reply-ticket hrr-refund-reply-ticket-<?php echo esc_attr( $post_object->get_id() . ' ' . $msg_class ) ; ?>" >
							<div class = "hrr_refund_reply_user_details">
								<?php echo get_avatar( $post_object->get_user_id() , '50' ) ; ?><br>
								<span class="hrr_refund_reply_user_name"><?php echo esc_html( $firstname ) ; ?></span>
								<span class="hrr_refund_reply_date">
									<?php 
																		/* translators: 1:Date 2:Time */
																		echo sprintf( esc_html__( 'Replied on: %1$s ( %2$s ago )' ) , $post_object->get_created_date() , human_time_diff( strtotime( $post_object->get_created_date() ) , ( current_time( 'timestamp' ) ) ) ) ; 
									?>
								</span>
							</div>
							<hr>
							<div class="hrr_refund_reply_content">
								<?php
								$find_array    = array( '<?' , '?>' ) ;
								$replace_array = array( '&lt;?' , '?&gt;' ) ;
								echo wpautop( str_replace( $find_array , $replace_array , $post_object->get_message() ) , true ) ;
								?>
							</div>
														<?php do_action ( 'hrr_conversation_attachments' , $post_object ); ?>
						</div>                
					<?php endforeach ; ?>
				</div>
			<?php endif ; ?>
		</div>
		<?php do_action( 'hrr_after_user_converation' , $request_obj ) ; ?>
			</form>
	</div>
</div>
<?php
