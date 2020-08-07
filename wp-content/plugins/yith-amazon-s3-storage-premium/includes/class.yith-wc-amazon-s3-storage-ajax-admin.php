<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_WC_FAVORITOS_Class
 * @package    Yithemes
 * @since      Version 2.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_WC_Amazon_S3_Storage_Ajax_Admin' ) ) {
	/**
	 * Class YITH_WC_FAVORITOS_Class
	 *
	 * @author Danie Sanchez Saez <dssaez@gmail.com>
	 */
	class YITH_WC_Amazon_S3_Storage_Ajax_Admin {

		/**
		 * Main Instance
		 *
		 * @var YITH_WC_Quick_Order_Forms_Ajax_Admin
		 * @since  1.0
		 * @access protected
		 */
		protected static $_instance = null;

		public function __construct() {

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_Show_Keys_of_a_Folder_Bucket', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_Show_Keys_of_a_Folder_Bucket'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_Checking_Credentials', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_Checking_Credentials'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_Showing_Bucket_List', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_Showing_Bucket_List'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_button_action_mode_grid', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_button_action_mode_grid'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_button_action_mode_grid_loader', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_button_action_mode_grid_loader'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_Add_doactions_to_Attachment_details_mode_grid', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_Add_doactions_to_Attachment_details_mode_grid'
			) );

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_S3_File_Manager', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_S3_File_Manager'
			) );

			// ===============================================
			// ======= Uploading to the media library ========

			// ========= Change session of 'Copy file to S3' ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_input_checkbox_copy_file_to_s3', array(
				$this,
				'YITH_WC_amazon_s3_storage_input_checkbox_copy_file_to_s3'
			) );

			// ========= Change session of 'Private or Public' ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_private_public_radio_button', array(
				$this,
				'YITH_WC_amazon_s3_storage_private_public_radio_button'
			) );

			// ========= Change session of 'Remove from the server' ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_input_checkbox_remove_from_server', array(
				$this,
				'YITH_WC_amazon_s3_storage_input_checkbox_remove_from_server'
			) );

			// ========= Setting back sessions to the default value ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_setting_back_sessions', array(
				$this,
				'YITH_WC_amazon_s3_storage_setting_back_sessions'
			) );

			// ========= Setting sessions to the default value when uploading a file from anywhere ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_setting_sessions', array(
				$this,
				'YITH_WC_amazon_s3_storage_setting_sessions'
			) );

			// ========= Setting the path of the new file uploaded ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded', array(
				$this,
				'YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded'
			) );

			// ========= Checking if the new file was uploaded ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_input_hidden_uploading_file', array(
				$this,
				'YITH_WC_amazon_s3_storage_input_hidden_uploading_file'
			) );

			// ========= Checking if files were deleted ============

			add_action( 'wp_ajax_YITH_WC_amazon_s3_storage_input_hidden_deleting_file', array(
				$this,
				'YITH_WC_amazon_s3_storage_input_hidden_deleting_file'
			) );

			// ========= Adding notice after actions ============

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_wc_add_notice', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_wc_add_notice'
			) );

			// ========= Showing individual actions

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_show_individual_actions', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_show_individual_actions'
			) );

			// ========= Doing individual actions

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_do_individual_actions', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_do_individual_actions'
			) );

			// ========= Showing details of S3

			add_action( 'wp_ajax_Yith_wc_as3s_ajax_admin_show_s3_details', array(
				$this,
				'Yith_wc_as3s_ajax_admin_show_s3_details'
			) );

			// ========= Checking settings

			add_action( 'wp_ajax_Yith_wc_as3s_Ajax_Admin_check_settings', array(
				$this,
				'Yith_wc_as3s_Ajax_Admin_check_settings'
			) );

            // ========= Showing the download url for admin orders

            add_action( 'wp_ajax_yith_wc_amazon_s3_storage_result_ajax_show_downloads_url_of_admin_order', array(
                $this,
                'yith_wc_amazon_s3_storage_result_ajax_show_downloads_url_of_admin_order'
            ) );


            //Carlos R --> Update files to amazon s3
            add_action('wp_ajax_wccr_upload_data_to_s3', array($this,'upload_files_to_amazon_s3'));
            add_action('wp_ajax_add_progress_bar_loader',array( $this,'add_progress_bar_loader' ));


		}

        // ========= Showing the download url for admin orders
        function yith_wc_amazon_s3_storage_result_ajax_show_downloads_url_of_admin_order(){

            $order_id = ( isset( $_POST['order_id'] ) ? $_POST['order_id'] : null );

            $order = new WC_Order( $order_id );

            $yith_count = 0;
            foreach ( $order->get_items() as $item ) {
                if ( is_object( $item ) && $item->is_type( 'line_item' ) && ( $item_downloads = $item->get_item_downloads() ) ) {
                    if ( $product = $item->get_product() ) {

                        foreach ( $item_downloads as $file ) {

                            $name = explode('name="', $file['file'] );
                            $name = explode('"', $name[1] );
                            $name = $name[0];

                            if ( strpos( $file['file'], 'yith_wc_amazon_s3_storage' ) !== false) {
                                $file_name = sanitize_file_name( $file['name'] );

                                ?>

                                <script>

                                    var strong_text = document.getElementById( "yith_wc_amazon_s3_storage_admin_orders_strong_<?php echo $yith_count; ?>" ).textContent;

                                    var array_strong_test = strong_text.split( "—" );

                                    array_strong_test[2] = "<?php echo $file_name . ": " . $name; ?>";

                                    strong_text = array_strong_test.join(" - ");

                                    document.getElementById( "yith_wc_amazon_s3_storage_admin_orders_strong_<?php echo $yith_count; ?>" ).textContent = strong_text;

                                    //document.getElementById( "yith_wc_amazon_s3_storage_admin_orders_a_<?php echo $yith_count; ?>" ).innerHTML = "";


                                </script>

                                <?php

                            }

                            $yith_count = $yith_count + 1;

                        }
                    }
                }
            }

            wp_die();

        }


		// ========= Checking settings
		function Yith_wc_as3s_Ajax_Admin_check_settings(){

			echo "<div class='YITH_WC_amazon_s3_storage_bad_settings_containter'>";

			if ( ! YITH_WC_amazon_s3_check_connection_success() )
			{

				?>

				<script>

					document.getElementById( "YITH_WC_amazon_s3_storage_bad_settings_ID" ).style.display = "block";
					document.getElementById( "YITH_WC_amazon_s3_storage_bad_settings_input_hidden" ).click();
					window.scrollTo( 0, 0 );

				</script>

				<?php

			}

			echo "</div>";

            wp_die();

		}

		function Yith_wc_as3s_Ajax_Admin_show_s3_details_common( $post_id ){

			$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

			if ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ){

				$Bucket_Selected = ( get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) : '' );

				$Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

				if ( count( $Array_Bucket_Selected ) == 2 ){
					$Bucket                = $Array_Bucket_Selected[0];
					$Region                = $Array_Bucket_Selected[1];
				}
				else{
					$Bucket                = 'none';
					$Region                = 'none';
				}

				$Path = get_post_meta( $post_id, '_wp_attached_file', true );

				$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

				$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

				$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

				$Access = $aws_s3_client->Get_Access_of_Object( $Bucket, $Region, $Path );

				?>

				<div class="Bucket">
					<strong>Bucket:</strong>
					<?php echo $Bucket; ?>
				</div>

				<div class="Path">
					<strong>Path:</strong>
					<?php echo $Path; ?>
				</div>

				<div class="Region">
					<strong>Region:</strong>
					<?php echo $Region; ?>
				</div>

				<div class="Access">
					<strong>Access:</strong>
					<?php echo $Access; ?>
				</div>

				<?php

			}

		}

		// ========= Doing individual actions

		function Yith_wc_as3s_ajax_admin_show_s3_details() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$post_id  = ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );

				$this->Yith_wc_as3s_Ajax_Admin_show_s3_details_common( $post_id );

			}

            wp_die();

		}

		// ========= Doing individual actions

		function Yith_wc_as3s_Ajax_Admin_do_individual_actions(){

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$post_id = ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );
				$doaction = ( isset( $_POST['doaction'] ) ? $_POST['doaction'] : null );

				switch ( $doaction ) {

					case 'Copy_to_S3':

							$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );
							if ( $s3_path == '_wp_yith_wc_as3s_s3_path_not_in_used' || $s3_path == null ) {
								yith_wc_as3s_Copy_to_S3_function( $post_id );
								$message = __( 'Copied to S3', 'yith-amazon-s3-storage' );
							}

						break;

					case 'Remove_from_S3':

							$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
							$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

							if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
								yith_wc_as3s_Remove_from_S3_function( $post_id );
								$message = __( 'Removed from S3', 'yith-amazon-s3-storage' );
							}

						break;

					case 'Copy_to_server_from_S3':

							$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
							$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );
							if ( ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) && ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {
								yith_wc_as3s_Copy_to_server_from_S3_function( $post_id );
								$message = __( 'Copied to server from S3', 'yith-amazon-s3-storage' );
							}

						break;

					case 'Remove_from_server':

							$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
							$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

							if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
								yith_wc_as3s_Remove_from_server_function( $post_id );
								$message = __( 'Removed from server', 'yith-amazon-s3-storage' );
							}

						break;
				}

				$this->Yith_wc_as3s_Ajax_Admin_show_individual_actions_common( $post_id );

				?>

				<script>

					document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_message_of_action" ).value = '<?php echo $message; ?>';
					document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_message_of_action" ).click();

				</script>

				<?php
			}

            wp_die();

		}

		function Yith_wc_as3s_Ajax_Admin_show_individual_actions_common( $post_id ){

			$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
			$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

			// Show the copy to s3 link if the file is not in S3
			if ( $s3_path == '_wp_yith_wc_as3s_s3_path_not_in_used' || $s3_path == null ) {
				echo '<div><a class="Copy_to_S3" href="post.php?post=' . $post_id . '&action=Copy_to_S3" data-post_id="' . $post_id .'">Copy to S3</a></div>';
			}

			// Remove the file from the server if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
			if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
				echo '<div><a class="Remove_from_server" href="post.php?post=' . $post_id . '&action=Remove_from_server" data-post_id="' . $post_id .'">Remove from server</a></div>';
			}

			// Show the copy to server from S3 link if the file is not in the server and it is in S3
			if ( ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) && ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {
				echo '<div><a class="Copy_to_server_from_S3" href="post.php?post=' . $post_id . '&action=Copy_to_server_from_S3" data-post_id="' . $post_id .'">Copy to server from S3</a></div>';
			}

			// Remove the file from S3 if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
			if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
				echo '<div><a class="Remove_from_S3" href="post.php?post=' . $post_id . '&action=Remove_from_S3" data-post_id="' . $post_id .'">Remove from S3</a></div>';
			}

			$this->Yith_wc_as3s_Ajax_Admin_show_s3_details_common( $post_id );

		}

		// ========= Showing individual actions

		function Yith_wc_as3s_Ajax_Admin_show_individual_actions(){

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$post_id = ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );

				$this->Yith_wc_as3s_Ajax_Admin_show_individual_actions_common( $post_id );

			}

            wp_die();

		}

		// ========= Adding notice after actions ============

		function Yith_wc_as3s_Ajax_Admin_wc_add_notice(){

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$_SESSION['YITH_WC_amazon_s3_storage_admin_notice__success'] = ( isset( $_POST['doaction'] ) ? $_POST['doaction'] : 'none' );

			}

            wp_die();

		}

		// ========= Checking if the new file was deleted ============

		public function YITH_WC_amazon_s3_storage_input_hidden_deleting_file() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$File_Deleted = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_deleting_file'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_deleting_file'] : 'none' );

				if ( $File_Deleted == 'done' ){

					$_SESSION['YITH_WC_amazon_s3_storage_deleting_file'] = 'none';

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Deleted" ).click();

					</script>

					<?php

				}
				else{

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Searching" ).click();

					</script>

					<?php

				}

			}

            wp_die();

		}

		// ===========================================================
		// ======= Uploading to the media library from product =======

		// ========= Checking if the new file was uploaded ============

		public function YITH_WC_amazon_s3_storage_input_hidden_uploading_file() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$File_Uploaded = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] : 'none' );

				if ( $File_Uploaded == 'done' ){

					$_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] = 'none';

					//== We only remove from the server if previously we copy the file to S3
					$remove_from_server_checkbox = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_remove_from_server_checkbox'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_remove_from_server_checkbox'] : false );

					if ( $remove_from_server_checkbox ){

						$object_id = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_file_copied_to_S3'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_file_copied_to_S3'] : 'none' );
						yith_wc_as3s_Remove_from_server_function( $object_id );

					}

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Uploaded" ).click();

					</script>

					<?php

				}
				else{

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Searching" ).click();

					</script>

					<?php

				}

			}

            wp_die();

		}

		// ========= Checking if a file is already uploaded ============

		public function YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$File_Uploaded = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] : 'none' );

				if ( $File_Uploaded == 'done' ){

					$_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] = 'none';

					$object_id = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_file_copied_to_S3'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_file_copied_to_S3'] : 'none' );

					$Path_to_File = get_post_meta( $object_id, '_wp_attached_file', true );

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded" ).value = '<?php echo $Path_to_File; ?>';
						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded" ).click();

					</script>

					<?php

					//== We only remove from the server if previously we copy the file to S3
					$remove_from_server_checkbox = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_remove_from_server_checkbox'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_remove_from_server_checkbox'] : false );

					if ( $remove_from_server_checkbox ){

						yith_wc_as3s_Remove_from_server_function( $object_id );

						//== In case we are uploading a file from a downloadable product we read this flag not to remove from S3
						//== when we delete the post from the database of wordpress
						$wp_delete_post_protecting_S3 = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_wp_delete_post_protecting_S3'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_wp_delete_post_protecting_S3'] : false );

						if ( $wp_delete_post_protecting_S3 ){
							// == Setting back protection file session to false for next time ==
							$_SESSION['YITH_WC_amazon_s3_storage_wp_delete_post_protecting_S3'] = false;
							// == We set the session with the post_id not to be deleted from S3 ==
							$_SESSION['YITH_WC_amazon_s3_storage_remain_file_in_S3'] = $object_id;

							wp_delete_post( $object_id, true );

						}

					}

				}
				else{

					?>

					<script>

						document.getElementById( "YITH_WC_amazon_s3_storage_input_hidden_searching_path_file_to_uploaded" ).click();

					</script>

					<?php

				}

			}

            wp_die();

		}

		// ========= Setting sessions to the default value when uploading a file from anywhere ============

		public function YITH_WC_amazon_s3_storage_setting_sessions() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$type = ( isset( $_POST['type'] ) ? $_POST['type'] : null );

				$rute = ( isset( $_POST['type'] ) ? $_POST['type'] : null );

				$copy_file_s3 = ( isset( $_POST['copy_file_s3'] ) ? $_POST['copy_file_s3'] : null );
                $private_public = ( isset( $_POST['private_public'] ) ? $_POST['private_public'] : null );
                $remove_from_server = ( isset( $_POST['remove_from_server'] ) ? $_POST['remove_from_server'] : null );



				$_SESSION[ 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' ] = ( $copy_file_s3 == 'on' ? true : false );

				$_SESSION[ 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' ] = ( $remove_from_server == 'on' ? true : false );

				$_SESSION[ 'YITH_WC_amazon_s3_storage_private_public_radio_button' ] = ( $type == 'product' ? $private_public : '' );

				/*== Setting the session to protect the file from S3 when removing from the server from products ==*/
				$_SESSION['YITH_WC_amazon_s3_storage_wp_delete_post_protecting_S3'] = ( $type == 'product' ? true : false );

				//== We set this session to 'none' for the message process bar 'Uploading file' to check when it turns to 'done'
				$_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] = 'none';

			}

            wp_die();

		}

		// ========= Setting back the sessions to upload a file correctly from products ============

		public function YITH_WC_amazon_s3_storage_setting_back_sessions() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$_SESSION[ 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' ] = get_option( 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' );
				$_SESSION[ 'YITH_WC_amazon_s3_storage_private_public_radio_button' ] = ( get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) ? get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) : 'private' );
				$_SESSION[ 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' ] = get_option( 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' );
				$_SESSION['YITH_WC_amazon_s3_storage_wp_delete_post_protecting_S3'] = true;

			}

            wp_die();

		}

		// ========= Change session of Copy file to S3 ============

		public function YITH_WC_amazon_s3_storage_input_checkbox_copy_file_to_s3() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

			    error_log(print_r("Testing Connection success",true));

				$checked = ( isset( $_POST['checked'] ) ? $_POST['checked'] : null );
				$_SESSION[ 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' ] = $checked;

			}

            wp_die();

		}

		// =========Change session of 'Private or Public' ============

		public function YITH_WC_amazon_s3_storage_private_public_radio_button() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$radio = ( isset( $_POST['radio'] ) ? $_POST['radio'] : null );
				$_SESSION[ 'YITH_WC_amazon_s3_storage_private_public_radio_button' ] = $radio;

			}

            wp_die();

		}

		// ========= Change session of Remove from the server ============

		public function YITH_WC_amazon_s3_storage_input_checkbox_remove_from_server() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$checked = ( isset( $_POST['checked'] ) ? $_POST['checked'] : null );
				$_SESSION[ 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' ] = $checked;

			}

            wp_die();

		}

		// ===============================================

		public function Yith_wc_as3s_Ajax_Admin_S3_File_Manager() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$Bucket_Selected = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' );

				$Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

				$Bucket = $Array_Bucket_Selected[0];
				$Region = $Array_Bucket_Selected[1];

				$File_Checked = 'none';

				if ( isset( $_POST['S3_Path_To_File'] ) ){

					$S3_Path_To_File = $_POST['S3_Path_To_File'];

					$Array_S3_Path_To_File = explode( "/", $S3_Path_To_File );

					$File_Checked = array_pop( $Array_S3_Path_To_File );

					$Bucket = $Bucket . "/" . implode( "/", $Array_S3_Path_To_File );

				}

				$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

				$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

				$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

				echo "<div id='Yith_WC_as3s_Show_Keys_of_a_Folder_Bucket_Result_ID'>";

				echo $aws_s3_client->Show_Keys_of_a_Folder_Bucket( $Bucket, $Region, $File_Checked );

				echo "</div>";

			}

            wp_die();

		}

		public function Yith_wc_as3s_Ajax_Admin_Show_Keys_of_a_Folder_Bucket() {

			$Region = ( isset( $_POST['Region'] ) ? $_POST['Region'] : null );

			$Current_folder = ( isset( $_POST['Current_folder'] ) ? $_POST['Current_folder'] : null );

			$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

			$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

			$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

			echo $aws_s3_client->Show_Keys_of_a_Folder_Bucket( $Current_folder, $Region );


            wp_die();

		}

		public function Yith_wc_as3s_Ajax_Admin_Add_doactions_to_Attachment_details_mode_grid() {

			if ( YITH_WC_amazon_s3_check_connection_success() ) {

				$post_id = ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
				$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				// Show the copy to s3 link if the file is not in S3
				if ( $s3_path == '_wp_yith_wc_as3s_s3_path_not_in_used' || $s3_path == null ) {
					echo ' | <a class="Copy_to_S3" href="post.php?post=' . $post_id . '&action=Copy_to_S3">Copy to S3</a>';
				}

				// Remove the file from the server if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					echo '| <a class="Remove_from_server" href="post.php?post=' . $post_id . '&action=Remove_from_server">Remove from server</a>';
				}

				// Show the copy to server from S3 link if the file is not in the server and it is in S3
				if ( ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) && ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {
					echo ' | <a class="Copy_to_server_from_S3" href="post.php?post=' . $post_id . '&action=Copy_to_server_from_S3">Copy to server from S3</a>';
				}

				// Remove the file from S3 if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					echo ' | <a class="Remove_from_S3" href="post.php?post=' . $post_id . '&action=Remove_from_S3">Remove from S3</a>';
				}

			}

            wp_die();

		}

		public function Yith_wc_as3s_Ajax_Admin_button_action_mode_grid_loader() {

			?>

			<div class='YITH_WC_amazon_s3_storage_sub_button_AJAX'>

				<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Uploaded'>
				<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Searching'>
				<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Deleted'>
				<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Searching'>
				<span id='YITH_WC_amazon_s3_storage_span_action_files'></span>

				<h1>

				</h1>

				<?php

				$ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

				?>
				<img class='YITH_WC_amazon_s3_storage_button_action_ajax_loader' src='<?php echo $ajax_loader; ?>' />

			</div>

			<?php

            wp_die();

		}

		public function Yith_wc_as3s_Ajax_Admin_button_action_mode_grid() {

			$doaction = ( isset( $_POST['doaction'] ) ? $_POST['doaction'] : null );

			$post_ids = ( isset( $_POST['post_ids'] ) ? $_POST['post_ids'] : null );


            yith_wc_as3s_do_bulk_actions_extra_options_function( $doaction, $post_ids );


            wp_send_json_success();

		}

		public function Yith_wc_as3s_Ajax_Admin_Checking_Credentials() {

			$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

			$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

			$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

			$buckets = $aws_s3_client->Checking_Credentials();

			if ( $buckets ) {

				echo "<div>";

				echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

				$Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-ok.png';

				echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
				echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
				_e( 'Connection to Amazon S3 Storage successful', 'yith-amazon-s3-storage' );
				echo "</span>";

				echo "</p>";

				echo "</div>";

			} else {

				echo "<div>";

				echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

				$Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-error-logs.png';

				echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
				echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
				_e( 'An error occurred while accessing, the credentials (access key or secret key) are NOT correct',
                    'yith-amazon-s3-storage' );
				echo "</span>";

				echo "</p>";

				echo "</div>";

			}

            wp_die();

		}

		public function Yith_wc_as3s_Ajax_Admin_Showing_Bucket_List() {

			$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

			$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

			$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

			echo '<select class="YITH_WC_amazon_s3_storage_input_text Yith_WC_as3s_Buckets_List_select" name="YITH_WC_amazon_s3_storage_connection_bucket_selected_select">';

				echo $aws_s3_client->Show_Buckets();

			echo '</select>';

			?>

			<script>

				jQuery(function ($) {

					$(".YITH_WC_amazon_s3_storage_admin_parent_wrap .Yith_WC_as3s_Buckets_List_select").select2({
						placeholder: "Choose a bucket"
					});

				});

			</script>

			<?php

            wp_die();

		}

		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

			if ( is_null( $self::$_instance ) ) {
				$self::$_instance = new $self;
			}

			return $self::$_instance;
		}

		/**

        Upload file and remove file from media library and product section

         **/

		public function upload_files_to_amazon_s3() {

            $type = ( isset( $_POST['type'] ) ? $_POST['type'] : null );

            $rute = ( isset( $_POST['type'] ) ? $_POST['type'] : null );

            $copy_file_s3 = ( isset( $_POST['copy_file_s3'] ) ? $_POST['copy_file_s3'] : null );
            $private_public = ( isset( $_POST['private_public'] ) ? $_POST['private_public'] : null );
            $remove_from_server = ( isset( $_POST['remove_from_server'] ) ? $_POST['remove_from_server'] : null );
            $object_id = ( isset( $_POST['file_id'] ) ? $_POST['file_id'] : null );

            if( $copy_file_s3 && $copy_file_s3 == 'true' ) {

                yith_wc_as3s_Copy_to_S3_function($object_id, $private_public);

                if( $remove_from_server ) {
                    yith_wc_as3s_Remove_from_server_function( $object_id );

                }

            }

            wp_die();

        }

        /**

        Add progress bar loader

         **/
        public function add_progress_bar_loader() {

            $type = apply_filters('yith_wcamz_progress_bar_type',$_POST['type']);
            $top = apply_filters('yith_wcamz_progress_bar_top',$_POST['top']);

            switch ( $type ) {

                case 'Copy_to_S3':
                     $doaction_String = 'Copying to S3';
                    break;
                case 'Remove_from_S3':
                    $doaction_String = 'Removing from S3';

                    break;
                case 'Copy_to_server_from_S3':
                    $doaction_String = 'Copying to server from S3';

                    break;
                case 'Remove_from_server':
                    $doaction_String = 'Removing from server';

                    break;
                case 'Uploading_File':
                    $doaction_String = 'Uploading';

                    break;
                case 'delete':
                    $doaction_String = 'Deleting permanently';

                    break;
            }

            ?>

            <div class='YITH_WC_amazon_s3_storage_sub_button_AJAX' style="margin-top: <?php echo $top ?>px">

                <input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Uploaded'>
                <input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Searching'>
                <input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Deleted'>
                <input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_deleting_file_Searching'>
                <span id='YITH_WC_amazon_s3_storage_span_action_files'></span>

                <h1>
                    <?php echo apply_filters('yith_wcamz_progress_bar_string',$doaction_String)  ?>
                </h1>

                <?php

                $ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

                ?>
                <img class='YITH_WC_amazon_s3_storage_button_action_ajax_loader' src='<?php echo $ajax_loader; ?>' />

            </div>

            <?php

            wp_die();

        }

    }
}
