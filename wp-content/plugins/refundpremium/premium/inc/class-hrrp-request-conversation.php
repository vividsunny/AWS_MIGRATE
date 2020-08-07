<?php
/**
 * Request Conversation.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'HRRP_Request_Conversation' ) ) {

	/**
	 * Class.
	 */
	class HRRP_Request_Conversation {

		/**
		 * Class initialization.
		 */
		public static function init() {

			$enable_conversation = get_option( 'hrr_refund_enable_conversation' ) ;
			if ( 'no' != $enable_conversation ) {
				//Admin Conversation Reply Layout.
				add_action( 'hrr_after_admin_converation' , array( __CLASS__ , 'admin_converation' ) ) ;
				//User Conversation Reply Layout.
				add_action( 'hrr_after_user_converation' , array( __CLASS__ , 'user_converation' ) , 10 , 1 ) ;
				//Replies to the Conversation.
				add_action( 'wp_ajax_hrr_refund_save_message' , array( __CLASS__ , 'save_replies' ) ) ;
				//Attachment Field.
				add_action( 'hrr_before_request_submit' , array( __CLASS__ , 'attachment_field' ) , 10 , 1 ) ;
				//Attachment Lists.
				add_action( 'hrr_conversation_attachments' , array( __CLASS__ , 'attachment_lists' ) , 10 , 1 ) ;
				//Attachment Validation.
				add_action( 'hrr_file_validation' , array( __CLASS__ , 'attachment_validations' ) , 10 , 2 ) ;
				//Update Attachment.
				add_action( 'hrr_request_metas' , array( __CLASS__ , 'update_attachments' ) , 10 , 3 ) ;
			}
		}

		/**
		 * Display Admin Conversation.
		 */
		public static function admin_converation() {
			global $post ;
			?> 
			<div class='hrr-refund-reply-request-form'>
				<p>
					<?php
					/* translators: %s: Post Title */
					echo sprintf( esc_html__( 'Write a reply to: %1$s' , 'refund' ) , $post->post_title ) ;
					?>
				</p>
				<div class='hrr-refund-reply-request-textarea'>
					<input type="hidden" id="hrr_refund_reply_request_id" value="<?php echo esc_attr( $post->ID ) ; ?>"/>
					<textarea class="hrr-refund-reply-content" rows="8"></textarea>
					<input type="file" name="hrr_conversation_attachment[]" multiple="multiple">
					<p>
						<button id="hrr_refund_reply_button" class="button button-primary">
							<span><?php esc_html_e( 'Reply' , 'refund' ) ; ?> </span>
						</button>
					</p>
				</div>
			</div>
			<?php
		}

		/**
		 * Display User Conversation in View Page.
		 */
		public static function user_converation( $request_obj ) {
			$attachment = get_option( 'hrr_refund_enable_attachment' , 'no' ) ;
			?>
			<br>
			<p>
				<?php
				/* translators: %s: Post Title */
				echo sprintf( esc_html__( 'Write a reply to: %1$s' , 'refund' ) , $request_obj->get_reason() ) ;
				?>
			<div class='hrr-refund-reply-request-form'>
				<input type="hidden" id="hrr_refund_reply_request_id" value="<?php echo esc_attr( $request_obj->get_id() ) ; ?>"/>
				<textarea class="hrr-refund-reply-content" rows="8"></textarea>
				<?php if ( 'yes' == $attachment ) : ?>
					<input type="file" name="hrr_conversation_attachment[]" multiple="multiple">
				<?php endif ; ?>
				<p>
					<button id="hrr_refund_reply_button" class="button">
						<span> <?php esc_html_e( 'Submit' , 'refund' ) ; ?> </span>
					</button>
				</p>
			</div>
			<?php
		}

		/**
		 * Attachment Field in Request Form.
		 */
		public static function attachment_field() {
			$attachment = get_option( 'hrr_refund_enable_attachment' , 'no' ) ;
			if ( 'yes' != $attachment ) {
				return ;
			}
			ob_start() ;
			?>
			<tr>
				<th>
					<?php echo esc_html( get_option( 'hrr_refund_attachment' ) ) ; ?>
				</th>
				<td>                
					<input type="file" name="hrr_refund_attachment[]" multiple="" id="hrr_refund_attachment">                
				</td>
			</tr>
			<?php
			$contents = ob_get_contents() ;
			ob_end_clean() ;
			echo $contents ;
		}

		/**
		 * List of Attachments.
		 */
		public static function attachment_lists( $post_obj ) {
			ob_start() ;

			if ( empty( $post_obj->get_attachment() ) ) {
				return ;
			}
			if ( ! empty( $post_obj->post_content ) ) {
				?>
				<hr>
				<?php
			}
			?>
			<div class="hrr-conversation-attachments">
				<h2><?php esc_html_e( 'Attachments' , 'refund' ) ; ?></h2>
				<?php foreach ( $post_obj->get_attachment() as $file_name => $each_attachment ) : ?>
					<div class="hrr-conversation-attachments-info">
						<a target="blank" href="<?php echo esc_url( $each_attachment ) ; ?>">
							<img src="<?php echo esc_url( HRR_PLUGIN_URL . '/assets/images/attachment-80.png' ) ; ?>"/>
						</a>
						<label><?php echo esc_html( $file_name ) ; ?></label>
					</div>
				<?php endforeach ; ?>
			</div>
			<?php
			$contents = ob_get_contents() ;
			ob_end_clean() ;
			echo $contents ;
		}

		/**
		 * Save reply messages.
		 */
		public static function save_replies() {
			check_ajax_referer( 'hrr-refund-message' , 'hrr_security' ) ;

			try {
				if ( ! isset( $_POST[ 'message' ] ) ) {
					throw new exception( esc_html__( 'Invalid Request' , 'refund' ) ) ;
				}

				do_action( 'hrr_file_validation' , $_FILES , 'conversation' ) ;

				$request_id = isset( $_POST[ 'request_id' ] ) ? absint( $_POST[ 'request_id' ] ) : 0 ;
				$post_args  = array(
					'post_content' => wc_clean( wp_unslash( $_POST[ 'message' ] ) ) ,
					'post_parent'  => $request_id ,
					'post_type'    => HRR_Register_Post_Type::CONVERSATION_POSTTYPE ,
					'post_status'  => 'hrr-replied' ,
					'post_author'  => get_current_user_id() ,
						) ;

				$meta_args = array() ;

				$enable_attachment = get_option( 'hrr_refund_enable_attachment' , 'no' ) ;

				if ( 'yes' == $enable_attachment ) {
					if ( isset( $_FILES[ 'hrr_conversation_attachment' ] ) ) {

						$attachments = new HRR_File_Uploader() ;

						$meta_args = array(
							'hrr_attachments' => $attachments->upload_files( wc_clean( $_FILES[ 'hrr_conversation_attachment' ] ) )
								) ;
					}
				}
				
				$id = hrr_create_new_conversation( $meta_args , $post_args ) ;

				do_action( 'hrr_conversation_created' , $id ) ;

				wp_send_json_success( array( 'id' => $id ) ) ;
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Validate attached files.
		 */
		public static function attachment_validations( $files, $type ) {
			$enable_attachment = get_option( 'hrr_refund_enable_attachment' , 'no' ) ;
			if ( 'yes' != $enable_attachment ) {
				return ;
			}

			$i = 0 ;

			$allowed_types = get_option( 'hrr_refund_file_type' ) ? explode( ',' , get_option( 'hrr_refund_file_type' ) ) : array( 'jpg' , 'jpeg' , 'png' , 'gif' , 'doc' , 'docx' , 'pdf' ) ;
			$type_error    = get_option( 'hrr_refund_error_message_for_type' ) ;

			$mandatory       = get_option( 'hrr_refund_upload_mandatory' , 'no' ) ;
			$mandatory_error = get_option( 'hrr_refund_error_message_for_mandatory' ) ;

			$file_size  = get_option( 'hrr_refund_file_size' ) ;
			$size_error = get_option( 'hrr_refund_error_message_for_size' ) ;

			foreach ( $files as $file ) {
				$ext = pathinfo( $file[ 'name' ][ $i ] , PATHINFO_EXTENSION ) ;

				if ( 'request' == $type && 'yes' == $mandatory && $file[ 'error' ][ $i ] > 0 ) {
					throw new exception( esc_html( $mandatory_error ) ) ;
				}

				if ( $file[ 'error' ][ $i ] > 0 ) {
					return ;
				}

				if ( ! in_array( $ext , $allowed_types ) ) {
					$format_error = str_replace( '{supported_file_types}' , $allowed_types , $type_error ) ;
					throw new exception( esc_html( $format_error ) ) ;
				}

				if ( '' != $file_size && ( $file[ 'size' ][ $i ] > ( $file_size * 1024 ) ) ) {
					$size_error = str_replace( '{file_size}' , $file_size , $size_error ) ;
					throw new exception( esc_html( $size_error ) ) ;
				}
				$i ++ ;
			}
			//                    exit();
		}

		/**
		 * Update Attachments in Request.
		 */
		public static function update_attachments( $post_metas, $post, $file ) {

			$enable_attachment = get_option( 'hrr_refund_enable_attachment' , 'no' ) ;
			if ( 'yes' != $enable_attachment ) {
				return $post_metas ;
			}

			if ( ! isset( $file[ 'hrr_refund_attachment' ] ) ) {
				return $post_metas ;
			}

			$attachments = new HRR_File_Uploader() ;

			$post_metas[ 'hrr_attachments' ] = $attachments->upload_files( $file[ 'hrr_refund_attachment' ] ) ;

			return $post_metas ;
		}

	}

	HRRP_Request_Conversation::init() ;
}
