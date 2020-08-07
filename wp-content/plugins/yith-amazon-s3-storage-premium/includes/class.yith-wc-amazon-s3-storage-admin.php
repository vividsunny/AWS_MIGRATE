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
 * @class      YITH_WC_Favoritos_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author
 *
 */

if ( ! class_exists( 'YITH_WC_Amazon_S3_Storage_Admin' ) ) {
	/**
	 * Class YITH_WC_Favoritos_Admin
	 *
	 * @author
	 */
	class YITH_WC_Amazon_S3_Storage_Admin {

		/**
		 * @var Panel object
		 */
		protected $_panel = null;

		/**
		 * @var Panel page
		 */
		protected $_panel_page = 'yith_wc_amazon_s3_storage_panel';

		/**
		 * @var bool Show the premium landing page
		 */
		public $show_premium_landing = true;

		/**
		 * @var string Official plugin documentation
		 */
		protected $_official_documentation = 'https://docs.yithemes.com/yith-amazon-s3-storage/';

		/**
		 * @var string Official plugin landing page
		 */
		protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-amazon-s3-storage/';

		/**
		 * @var string Official plugin landing page
		 */
		protected $_premium_live = 'https://plugins.yithemes.com/yith-amazon-s3-storage/';

        /**
         * @var string Official plugin support page
         */
        protected $_support = 'https://yithemes.com/my-account/support/dashboard/';

		/**
		 * Construct
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.0
		 */
		/**
		 * @var doc_url
		 */
		protected $doc_url = '';

		/**
		 * @var string Official plugin documentation
		 */

		/**
		 * @var widget class of the cart
		 */
		public $widget_cart = null;

		public function __construct() {

            /* === Show Plugin Information === */

            add_filter( 'plugin_action_links_' . plugin_basename( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . '/' . basename( constant( 'YITH_WC_AMAZON_S3_STORAGE_FILE' ) ) ), array(
                $this,
                'action_links',
            ) );

            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			/* ====== ENQUEUE STYLES AND JS ====== */

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			/* ====== Sessions initation for ajax to include the classes with "Backend" value ====== */
			$this->yith_woocommerce_sessions();

			/* === Register Panel Settings === */
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			/* === Tabs for the panel settings === */

			add_action( 'yith_wc_amazon_s3_storage_connect_to_as3s_account', array(
				$this,
				'connect_to_S3_amazon_account'
			) );

			add_action( 'yith_wc_amazon_s3_storage_general_settings', array( $this, 'general_settings' ) );

			add_action( 'yith_wc_amazon_s3_storage_woocommerce_settings', array( $this, 'woocommerce_settings' ) );

			/* ====== AJAX ADMIN FUNCTIONS ====== */

			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '-ajax-admin.php' );

			$this->ajax = YITH_WC_Amazon_S3_Storage_Ajax_Admin::get_instance();

			/* ====== Adding filters ====== */

			add_filter( "bulk_actions-upload", array( $this, 'yith_wc_as3s_bulk_actions_extra_options' ) );

			add_filter( 'handle_bulk_actions-upload', array(
				$this,
				'yith_wc_as3s_do_bulk_actions_extra_options'
			), 10, 3 );

			add_filter( 'media_row_actions', array( $this, 'yith_wc_as3s_media_row_actions_extra' ), 10, 2 );

			add_filter( 'upload_dir', array( $this, 'yith_wc_as3s_upload_dir' ), 10, 1 );

			add_filter( 'wp_get_attachment_url', array( $this, 'yith_wc_as3s_wp_get_attachment_url' ), 10, 2 );

            add_filter('wp_get_attachment_image_attributes',array( $this,'get_attachment_images_attribute'),10,3);

			/* ====== Adding actions ====== */

			add_action( 'post_action_Copy_to_S3', array( $this, 'yith_wc_as3s_post_action_Copy_to_S3' ), 10, 1 );

			add_action( 'post_action_Remove_from_S3', array(
				$this,
				'yith_wc_as3s_post_action_Remove_from_S3'
			), 10, 1 );

			add_action( 'post_action_Copy_to_server_from_S3', array(
				$this,
				'yith_wc_as3s_post_action_Copy_to_server_from_S3'
			), 10, 1 );

			add_action( 'post_action_Remove_from_server', array(
				$this,
				'yith_wc_as3s_post_action_Remove_from_server'
			), 10, 1 );

			add_action( 'delete_attachment', array( $this, 'yith_wc_as3s_delete_attachment' ), 10, 1 );

			add_action( 'added_post_meta', array( $this, 'yith_wc_as3s_added_post_meta' ), 10, 4 );

			add_action( 'post-upload-ui', array( $this, 'yith_wc_as3s_post_upload_ui' ), 10 );

			$doaction = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_admin_notice__success'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_admin_notice__success'] : 'none' );
			if ( $doaction != 'none' && $doaction != 'delete' && $doaction != 'Uploading_File' )
				add_action( 'admin_notices', array( $this, 'yith_wc_as3s_admin_notice__success' ) );


			add_filter('get_sample_permalink_html',array($this,'change_the_permalink'),10,5);


		}

        /**
         * Action links
         *
         *
         * @return void
         * @since    1.0.11
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function action_links( $links ) {

            $links = yith_add_action_links( $links, $this->_panel_page, true );
            return $links;

        }

        /**
         * Plugin Row Meta
         *
         *
         * @return void
         * @since    1.0.11
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WC_AMAZON_S3_STORAGE_INIT' ) {

            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
                $new_row_meta_args['slug'] = 'yith-amazon-s3-storage';
                $new_row_meta_args[ 'is_premium' ] = true;
            }

            return $new_row_meta_args;
        }

		function yith_wc_as3s_admin_notice__success() {

			$doaction = $_SESSION['YITH_WC_amazon_s3_storage_admin_notice__success'];

			$message = '';
			switch ( $doaction ) {

				case 'Copy_to_S3':
					$message = 'Copied to S3';
					break;
				case 'Remove_from_S3':
					$message = 'Removed from S3';
					break;
				case 'Copy_to_server_from_S3':
					$message = 'Copied to server from S3';
					break;
				case 'Remove_from_server':
					$message = 'Removed from server';
					break;

			}

			?>
			<div class="notice notice-success is-dismissible">
				<p><?php _e( $message, 'yith-amazon-s3-storage' ); ?></p>
			</div>

			<?php

			$_SESSION['YITH_WC_amazon_s3_storage_admin_notice__success'] = 'none';

		}

		/* ================================================================ */
		/* ====== Show - Copying files to S3 and removing from server ===== */
		/* ================================================================ */
		public function yith_wc_as3s_post_upload_ui() {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				$copy_file_s3_checkbox = get_option( 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' );
				$File_S3_checkbox = ( $copy_file_s3_checkbox ? 'checked="checked"' : '' );

				$remove_from_server_checkbox = get_option( 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' );
				$Remove_From_Server_checkbox = ( $copy_file_s3_checkbox ? ( $remove_from_server_checkbox ? 'checked="checked"' : '' ) : '' );

				$radio = ( get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) ? get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) : 'private' );

				?>

                <div id="YITH_WC_amazon_s3_storage_admin_uploading_wrap">

                    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

                        <label>

                            <span >

                                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_copy_file_s3_checkbox" <?php echo $File_S3_checkbox; ?>>

                                <?php _e( 'Copy files also to S3', 'yith-amazon-s3-storage' ); ?>

                                <?php

                                $current_url = $_SERVER['REQUEST_URI'];

                                if ( strpos( $current_url, 'post.php' ) !== false || strpos( $current_url, 'post-new.php' ) !== false  )
                                {

                                    ?>

                                    <span class="YITH_WC_amazon_s3_storage_private_public_html <?php echo ( $copy_file_s3_checkbox ? '' : 'hidden' ); ?>">:</span>
                                    </span></label>

                                    <ul class="YITH_WC_amazon_s3_storage_private_public_html <?php echo ( $copy_file_s3_checkbox ? '' : 'hidden' ); ?>">

                                        <li>
                                            <label>

                                                <input class="" type="radio" name="YITH_WC_amazon_s3_storage_private_public_radio_button" value="private" <?php echo( ( $radio == "private" ) ? 'checked="checked"' : '' ); ?>/>

                                                <span class="Yith_wc_amazon_s3_Storage_margin_right">
                                                    <?php _e( 'Private ', 'yith-amazon-s3-storage' ); ?>
                                                </span>

                                            </label>
                                        </li>

                                        <li>
                                            <label>
                                                <input class="YITH_WC_amazon_s3_storage_input_text" type="radio" name="YITH_WC_amazon_s3_storage_private_public_radio_button" value="public" <?php  echo( ( $radio == "public" ) ? 'checked="checked"' : '' ); ?>/>

                                                <span class="Yith_wc_amazon_s3_Storage_margin_right">
                                                    <?php _e( 'Public ', 'yith-amazon-s3-storage' ); ?>
                                                </span>
                                            </label>
                                        </li>

                                    </ul>

                                    <?php

                                }
                                else
                                    echo "</span></label>";

                                ?>

                    </p>

                    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

                        <label>

                            <span>

                                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_remove_from_server_checkbox" <?php echo $Remove_From_Server_checkbox; ?>>

                                <?php _e( 'Remove from the server', 'yith-amazon-s3-storage' ); ?>

                            </span>

                            <span>

                                <?php

                                    if ( strpos( $current_url, 'post.php' ) !== false || strpos( $current_url, 'post-new.php' ) !== false )
                                        _e( '(after removing from the server the file is NOT going to be shown in the media library)', 'yith-amazon-s3-storage' );

                                ?>

                            </span>

                        </label>
                        <div class="notice notice-warning is-dismissible hidden" id="YITH_WC_amazon_s3_message_warning_remove_from_server">
                            <p>To check this box you have to check first "Copy files also to S3"</p>
                            <button id="YITH_WC_amazon_s3_message_warning_remove_from_server_button" type="button" class="notice-dismiss"></button>
                        </div>

                    </p>

                </div>

                <script>

                    if ( document.querySelector( '.media-router' ) !== null ) {

                        function ywas3_display_options( yith_count ){

                            setTimeout( function(){

                                yith_count = yith_count + 1;

                                var uploading = document.getElementById( 'YITH_WC_amazon_s3_storage_admin_uploading_wrap' );

                                if ( document.querySelector( '.yith_wc_as3s_activate_S3_file_manager' ) !== null ){
                                    uploading.style.display = 'block';
                                    yith_count = 10;
                                }
                                else{
                                    uploading.style.display = 'none';
                                }

                                if ( yith_count < 10 ){

                                    ywas3_display_options( yith_count );

                                }

                                }, 500 );

                        }

                        yith_init_count = 0;

                        if ( document.querySelector( '.yith_wc_as3s_activate_S3_file_manager' ) !== null ){
                            document.getElementById( 'YITH_WC_amazon_s3_storage_admin_uploading_wrap' ).style.display = 'block';
                            yith_init_count = 10;
                        }
                        else{
                            document.getElementById( 'YITH_WC_amazon_s3_storage_admin_uploading_wrap' ).style.display = 'none';
                        }

                        ywas3_display_options( yith_init_count );

                    }

                </script>

				<?php

			}

		}

		/* ================================================================ */
		/* ========= Copying files to S3 and removing from server ========= */
		/* ================================================================ */
		public function yith_wc_as3s_added_post_meta( $mid, $object_id, $meta_key, $_meta_value ) {

		    if ( isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] = 'downloadable_product' ) {

                if (get_option('YITH_WC_amazon_s3_storage_connection_success') && ($meta_key == '_wp_attachment_metadata' || $meta_key == '_wp_attached_file')) {

                    $do_actions = true;

                    //== We check if the file is an image. If it is an image we don't do anything because after this step, wordpress is going to add anther '_wp_attachment_metadata'
                    //== for the same file, then we do the actions to copy to S3 or remove from server in case
                    if ($meta_key == '_wp_attached_file') {

                        $filetype = wp_check_filetype($_meta_value);
                        if ($filetype['ext'] == 'jpg' || $filetype['ext'] == 'jpeg' || $filetype['ext'] == 'png' || $filetype['ext'] == 'gif' || $filetype['ext'] == 'ico')
                            $do_actions = false;

                    }

                    //== Actions to copy files to S3 and Removing from server
                    //== We check what to do with variable sessions which were created when the file was selected
                    if ($do_actions) {

                        $copy_file_s3_checkbox = (isset($_SESSION['YITH_WC_amazon_s3_storage_copy_file_s3_checkbox']) ? $_SESSION['YITH_WC_amazon_s3_storage_copy_file_s3_checkbox'] : '');

                        if ($copy_file_s3_checkbox) {

                            $radio_private_or_public = (isset($_SESSION['YITH_WC_amazon_s3_storage_private_public_radio_button']) ? $_SESSION['YITH_WC_amazon_s3_storage_private_public_radio_button'] : '');

                            //== If the checkbox of "Copy file to S3" is checked
                            yith_wc_as3s_Copy_to_S3_function($object_id, $radio_private_or_public);

                            // == We set this session with the id of the attachment to be retrieved later on, and open the Amazon S3 tab in downloadable products
                            $_SESSION['YITH_WC_amazon_s3_storage_file_copied_to_S3'] = $object_id;

                        }

                        //== We set this session to 'done' for the message process bar 'Uploading file' to disappear
                        $_SESSION['YITH_WC_amazon_s3_storage_uploading_file'] = 'done';

                    }

                }

            }

        }

		/* ================================================================ */
		/* =============== MODIFYING THE URL OF ATTACHMENT ================ */
		/* ================================================================ */
		public function yith_wc_as3s_wp_get_attachment_url( $url, $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ){

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );

				if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) || ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) ){

				    $replace = false;

					$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

					if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

					    $replace = true;

					} else {

						global $wpml_post_translations;

						$id = $post_id;
						if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id) ) {

							$s3_path = get_post_meta($parent_id, '_wp_yith_wc_as3s_s3_path', true);

							if (($s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null)) {

								$replace = true;
							}
						}

                    }

					if ( $replace ) {

						$aws_Base_url = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

						$upload_dir = wp_upload_dir();

						$url = str_replace( $upload_dir['baseurl'], $aws_Base_url, $url );
                    }

				}

			}

			return $url;

		}

		/* ================================================================ */
		/* ============= MODIFYING THE URL OF UPLOADED FILES ============== */
		/* ================================================================ */
		public function yith_wc_as3s_upload_dir( $cache_key ) {

			if ( true ) {

				$post_id = get_the_ID();
				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );

				if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) || ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) ) {

					$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

					if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

						$cache_key['baseurl'] = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

					}

				}

			}

			return $cache_key;

		}

		/* ================================================================ */
		/* ======================== COPY TO S3 ============================ */
		/* ================================================================ */
		public function yith_wc_as3s_post_action_Copy_to_S3( $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				yith_wc_as3s_Copy_to_S3_function( $post_id );

			}
			$sendback = wp_get_referer();

            if( !$sendback ) {
                $admin_url = admin_url();
                $url = $admin_url.'upload.php?item='.$post_id;
                wp_safe_redirect($url);
            }

			wp_redirect( $sendback );

			die();

		}

		/* ================================================================ */
		/* ======================= REMOVE FROM S3 ========================= */
		/* ================================================================ */

		public function yith_wc_as3s_post_action_Remove_from_S3( $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				yith_wc_as3s_Remove_from_S3_function( $post_id );

			}

			$sendback = wp_get_referer();

            if( !$sendback ) {
                $admin_url = admin_url();
                $url = $admin_url.'upload.php?item='.$post_id;
                wp_safe_redirect($url);
            }


			wp_redirect( $sendback );

			die();

		}

		/* ================================================================ */
		/* ===================== DELETED PERMANENTLY ====================== */
		/* ================================================================ */
		public function yith_wc_as3s_delete_attachment( $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				$_SESSION['YITH_WC_amazon_s3_storage_deleting_file'] = 'done';

				$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				$post_id_protected = ( isset( $_SESSION['YITH_WC_amazon_s3_storage_remain_file_in_S3'] ) ? $_SESSION['YITH_WC_amazon_s3_storage_remain_file_in_S3'] : '' );

				/*== Resetting the session of the post id protected ==*/
				$_SESSION['YITH_WC_amazon_s3_storage_remain_file_in_S3'] = 'none';

				if ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null && $post_id != $post_id_protected ) {

					yith_wc_as3s_Remove_from_S3_function( $post_id );

				}

			}

		}

		/* ================================================================ */
		/* ================== COPY TO SERVER FROM S3 ====================== */
		/* ================================================================ */
		public function yith_wc_as3s_post_action_Copy_to_server_from_S3( $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				yith_wc_as3s_Copy_to_server_from_S3_function( $post_id );

			}

			$sendback = wp_get_referer();

            if( !$sendback ) {
                $admin_url = admin_url();
                $url = $admin_url.'upload.php?item='.$post_id;
                wp_safe_redirect($url);
            }

			wp_redirect( $sendback );

			die();

		}

		/* ================================================================ */
		/* ===================== REMOVE FROM SEVER ======================== */
		/* ================================================================ */
		public function yith_wc_as3s_post_action_Remove_from_server( $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				yith_wc_as3s_Remove_from_server_function( $post_id );

			}

			$sendback = wp_get_referer();

            if( !$sendback ) {
                $admin_url = admin_url();
                $url = $admin_url.'upload.php?item='.$post_id;
                wp_safe_redirect($url);
            }

			wp_redirect( $sendback );

			die();

		}

		/* ================================================================ */
		/* =================== SHOW INDIVIDUAL OPTIONS ==================== */
		/* ================================================================ */
		public function yith_wc_as3s_media_row_actions_extra( $actions, $post ) { // 3º parameter $this->detached

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				$post_id = get_the_ID( $post );

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
				$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				// Show the copy to s3 link if the file is not in S3
				if ( $s3_path == '_wp_yith_wc_as3s_s3_path_not_in_used' || $s3_path == null ) {
					$actions['Copy_to_S3'] = '<a href="post.php?post=' . $post_id . '&action=Copy_to_S3">Copy to S3</a>';
				}

				// Remove the file from the server if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					$actions['Remove_from_server'] = '<a href="post.php?post=' . $post_id . '&action=Remove_from_server">Remove from server</a>';
				}

				// Show the copy to server from S3 link if the file is not in the server and it is in S3
				if ( ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) && ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {
					$actions['Copy_to_server_from_S3'] = '<a href="post.php?post=' . $post_id . '&action=Copy_to_server_from_S3">Copy to server from S3</a>';
				}

				// Remove the file from S3 if it is in both places (wordpress installation and S3) otherwise user will click in "delete permanently"
				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					$actions['Remove_from_S3'] = '<a href="post.php?post=' . $post_id . '&action=Remove_from_S3">Remove from S3</a>';
				}

			}

			return $actions;

		}

		/* ================================================================ */
		/* ====================== SHOW BULK ACTIONS ======================= */
		/* ================================================================ */
		public function yith_wc_as3s_bulk_actions_extra_options( $actions ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

				$actions['Copy_to_S3']             = 'Copy to S3';
				$actions['Remove_from_S3']         = 'Remove from S3';
				$actions['Copy_to_server_from_S3'] = 'Copy to server from S3';
				$actions['Remove_from_server']     = 'Remove from server';

			}

			return $actions;

		}

		/* ================================================================ */
		/* ====================== DO BULK ACTIONS ========================= */
		/* ================================================================ */
		public function yith_wc_as3s_do_bulk_actions_extra_options( $location, $doaction, $post_ids ) {

			yith_wc_as3s_do_bulk_actions_extra_options_function( $doaction, $post_ids );

			return $location;

		}

		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$menu_title = __( 'Amazon S3 Storage', 'yith-amazon-s3-storage' );


			$admin_tabs = apply_filters( 'yith_wc_as3s_admin_tabs', array(
					'connectS3' => __( 'Connect to your S3 Amazon account', 'yith-amazon-s3-storage' ),
				)
			);

			if ( $this->show_premium_landing ) {
				$admin_tabs['generalsettings']     = __( 'Settings', 'yith-amazon-s3-storage' );
				if ( function_exists( 'WC' ) )
					$admin_tabs['woocommercesettings'] = __( 'WooCommerce settings for digital goods', 'yith-amazon-s3-storage' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => $menu_title,
				'menu_title'       => $menu_title,
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_WC_AMAZON_S3_STORAGE_OPTIONS_PATH,
			);


			/* === Fixed: not updated theme/old plugin framework  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once( 'plugin-fw/lib/yit-plugin-panel.php' );
			}

			$this->_panel = new YIT_Plugin_Panel( $args );
		}

		public function connect_to_S3_amazon_account() {

			if ( file_exists( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/connects3_tab.php' ) ) {
				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/connects3_tab.php' );
			}

		}

		public function general_settings() {

			if ( file_exists( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/general_settings_tab.php' ) ) {
				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/general_settings_tab.php' );
			}

		}

		public function woocommerce_settings() {

			if ( file_exists( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/woocommerce_settings_tab.php' ) ) {
				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/woocommerce_settings_tab.php' );
			}

		}

		public function show_s3_landing() {

			if ( file_exists( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/s3_tab.php' ) ) {
				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'templates/s3_tab.php' );
			}

		}

		public function enqueue_scripts() {

			global $pagenow;

			if( ! in_array( $pagenow, [ 'post.php', 'upload.php', 'media-new.php', 'post-new.php' ] ) ){
				return;
			}

			/* ====== Style ====== */

			wp_register_style( 'yith-wc-' . YITH_AS3S_CONSTANT_NAME . '-style', constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . 'css/yith-as3s-admin.css', array(), constant( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) );
			wp_enqueue_style( 'yith-wc-' . YITH_AS3S_CONSTANT_NAME . '-style' );

			/* ====== Script ====== */

            if ( ! wp_script_is( 'selectWoo' ) ) {
                wp_enqueue_script( 'selectWoo' );
                wp_enqueue_script( 'wc-enhanced-select' );
            }

			wp_register_script( 'yith-wc-' . YITH_AS3S_CONSTANT_NAME . '-js', constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . 'js/yith-as3s-admin.js', array(
				'jquery',
				'jquery-ui-sortable'
			), constant( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ), true );


			wp_localize_script( 'yith-wc-' . YITH_AS3S_CONSTANT_NAME . '-js', 'yith_wc_amazong_s3_storage_object', apply_filters( 'yith_wc_as3s_admin_localize', array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'ajax_loader' => constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader.gif',
			) ) );

			wp_enqueue_script( 'yith-wc-' . YITH_AS3S_CONSTANT_NAME . '-js' );

		}

		public function yith_woocommerce_sessions() {

			$_SESSION[ 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_current_ajax' ] = "Backend";

		}
        /**
         * Get attachment images attribute
         *
         * Override image link when the images are loaded from S3
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.1.1
         */

		public function get_attachment_images_attribute($attr, $attachment, $size) {


            $post_id = $attachment->ID;
            $url = $attr['src'];
            if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ){

                $wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );

                if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) || ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) ){

                    $replace = false;

                    $s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

                    if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

                        $replace = true;

                    } else {

	                    global $wpml_post_translations;

	                    $id = $post_id;
	                    if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id) ) {

		                    $s3_path = get_post_meta($parent_id, '_wp_yith_wc_as3s_s3_path', true);

		                    if (($s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null)) {

			                    $replace = true;
		                    }
	                    }
                    }

                    if( $replace ) {
	                    $aws_Base_url = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

	                    $upload_dir = wp_upload_dir();

	                    $url = str_replace( $upload_dir['baseurl'], $aws_Base_url, $url );
	                    $attr['src'] = $url;
	                    $attr['srcset'] = $url;
                    }

                }

            }

		    return $attr;
        }

        /**
         * Filter the permalink
         *
         * Override permalink with amazon s3 link if option is enabled
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.2.11
         */

        public function change_the_permalink( $return, $post_id, $new_title, $new_slug, $post ) {

            if ('attachment' == $post->post_type) {
                
                $wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );

                if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) || ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) ){

                    $attachement_url = wp_get_attachment_url($post_id);
                    $preview_target = '';
                    $return  = '<strong>' . __( 'Permalink:' ) . "</strong>\n";
                    $return .= '<span id="sample-permalink"><a href="' . esc_url( $attachement_url ) . '"' . $preview_target . '>' . $attachement_url . "</a></span>\n";

                }
            }


            return $return;
        }
	}
}
