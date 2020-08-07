<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YITH_WooCommerce_Additional_Uploads' ) ) {
	
	/**
	 *
	 * @class   YITH_WooCommerce_Additional_Uploads
	 *
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Lorenzo Giuffrida
	 */
	class YITH_WooCommerce_Additional_Uploads {
		
		/**
		 * @var int Max file size allowed in mega bytes
		 */
		public $max_size = 0;
		
		/**
		 * @var string Restrict allowed file extension with a comma separated list of extensios accepted
		 */
		public $allowed_extension = '';
		
		/**
		 * @var bool Allow customers to upload files on cart page.
		 */
		public $allow_on_cart_page = false;
		
		/**
		 * @var bool enable the upload for orders
		 */
		public $allow_on_orders = false;
		
		/**
		 * @var bool enable the upload for products
		 */
		public $allow_on_products = true;
		
		/**
		 * @var bool Allow customers to upload files on myaccount page.
		 */
		public $allow_on_myaccount = false;
		
		/**
		 * @var array Restrict the order status on which the file upload is permitted
		 */
		public $allowed_status = array();

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-uploads/';

        /**
         * @var string Plugin official documentation
         */
        protected $_official_documentation = 'https://docs.yithemes.com/yith-woocommerce-uploads/';

        /**
         * @var string Official plugin landing page
         */
        protected $_premium_live = 'https://plugins.yithemes.com/yith-woocommerce-uploads/';

        /**
         * @var string Official plugin support page
         */
        protected $_support = 'https://yithemes.com/my-account/support/dashboard/';

        /**
         * @var string Plugin panel page
         */
        protected $_panel_page = 'yith_woocommerce_additional_uploads_panel';

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 */
		protected static $instance;
		
		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
            $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

            if ( is_null( $self::$instance ) ) {
                $self::$instance = new $self;
            }

            return $self::$instance;
		}
		
		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		protected function __construct() {
			
			$this->init_plugin_settings ();
			
			/**
			 * Do some stuff on plugin init
			 */
			add_action ( 'init', array( $this, 'on_plugin_init' ) );
			
			/** Add styles and scripts */
			add_action ( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_resources' ) );
			add_action ( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_resources' ) );
			
			/**
			 * Add metabox to question and answer post type
			 */
			add_action ( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
			
			add_action ( 'woocommerce_view_order', array( $this, 'show_upload_section_on_view_page' ), 1 );
			
			add_action ( 'yith_woocommerce_additional_uploads_order_file_uploaded', array(
				$this,
				'order_file_uploaded',
			) );
			
			/**
			 * Add  button on frontend my-orders page
			 */
			add_action ( 'woocommerce_my_account_my_orders_actions', array(
				$this,
				'print_upload_file_button',
			), 10, 2 );
			
			/**
			 * Download uploaded file from order page metabox
			 */
			add_action ( "admin_action_" . YWAU_ACTION_DOWNLOAD_FILE, array( $this, 'download_order_uploaded_file' ) );
			
			if ( $this->allow_on_cart_page ) {
				
				add_action ( "woocommerce_before_cart", array( $this, 'show_upload_section_on_cart_page' ) );
			}
			
			add_action ( 'woocommerce_checkout_order_processed', array(
				$this,
				'attach_file_from_cart_to_order',
			), 10, 2 );

            /* === Show Plugin Information === */

            add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWAU_DIR . '/' . basename( YITH_YWAU_FILE ) ), array(
                $this,
                'action_links',
            ) );

            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

		}
		
		/**
		 * Check if there is a file attached to cart and attach it to relative order
		 *
		 * @param $order_id
		 * @param $posted
		 */
		public function attach_file_from_cart_to_order( $order_id, $posted ) {
			$uploaded_file = WC ()->session->get ( "ywau_order_file_uploaded" );
			WC ()->session->__unset ( "ywau_order_file_uploaded" );
			
			if ( $uploaded_file != null ) {
				//  change file folder, using the real order id
				$relative_path = sprintf ( "%s/%s", $order_id, basename ( $uploaded_file ) );
				
				$starting_path = sprintf ( "%s/%s",
					YITH_YWAU_SAVE_DIR,
					untrailingslashit ( $uploaded_file ) );
				
				$destination_path = sprintf ( "%s/%s",
					YITH_YWAU_SAVE_DIR,
					untrailingslashit ( $relative_path ) );
				
				$new_dir = sprintf ( "%s/%s",
					YITH_YWAU_SAVE_DIR,
					$order_id );
				
				wp_mkdir_p ( $new_dir );
				
				//  move file to new folder
				rename ( $starting_path, $destination_path );
				
				$order = wc_get_order ( $order_id );
				yit_save_prop ( $order, YWAU_METAKEY_ORDER_FILE_UPLOADED, $relative_path );
			}
			
		}
		
		/**
		 * Show the upload section on cart page
		 */
		public function show_upload_section_on_cart_page() {
			
			if ( $this->allow_on_cart_page ) {
				
				$this->show_upload_section ( 0 );
			}
		}
		
		/**
		 * Download uploaded file from order page metabox
		 */
		public function download_order_uploaded_file() {
			if ( ! isset( $_GET["order_id"] ) ) {
				return;
			}
			
			$order_id  = $_GET["order_id"];
			$file_path = $this->order_has_file_uploaded ( $order_id );
			$full_path = YITH_YWAU_SAVE_DIR . $file_path;
			
			if ( ! empty( $full_path ) ) {
				header ( 'Content-type: application/octet-stream' );
				header ( 'Content-Disposition: attachment; filename = "' . basename ( $full_path ) . '"' );
				@readfile ( $full_path );
				exit();
			}
		}
		
		/**
		 * show upload file button on my-orders page
		 *
		 * @param array    $actions
		 * @param WC_Order $order
		 *
		 * @return array
		 */
		public function print_upload_file_button( $actions, $order ) {
			
			if ( $this->can_upload_file_on_order ( $order ) && ( ! $this->order_has_file_uploaded ( yit_get_prop ( $order, 'id' ) ) ) ) {
				
				$actions['upload-file'] = array(
					'url'  => $order->get_view_order_url (),
					'name' => esc_html__( 'Upload file', 'yith-woocommerce-additional-uploads' ),
				);
			}
			
			return $actions;
		}
		
		public function on_plugin_init() {
			
			if ( isset( $_POST["upload-file"] ) && isset( $_POST["submit-files"] ) ) {
				//check if this is an ajax request
				if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) {
					die();
				}
				do_action ( "yith_woocommerce_additional_uploads_order_file_uploaded" );
			}
		}
		
		/**
		 * Push data to $_POST vars
		 *
		 * @param $key
		 * @param $message
		 */
		private function set_upload_status( $status, $message ) {
			$_POST["upload-status"]  = $status;
			$_POST["upload-message"] = $message;
		}
		
		/**
		 * Upload the customer file and link it to the current order
		 */
		public function order_file_uploaded() {
			
			$order_id = intval ( $_POST["order-id"] );
			
			if ( $this->order_has_file_uploaded ( $order_id ) ) {
				$this->set_upload_status ( "rejected", sprintf ( esc_html__( "You have already sent a file for the current order, it is not possible to add the new %s file.", 'yith-woocommerce-additional-uploads' ), $_FILES['uploadFile']['name'][0] ) );
				
				return;
			}
			
			if ( ! $_FILES['uploadFile']['name'][0] ) {
				$this->set_upload_status ( "failed", sprintf ( esc_html__( "The name of the file %s has not been accepted.", 'yith-woocommerce-additional-uploads' ), $_FILES['uploadFile']['name'][0] ) );
				
				//  no file name ?
				return;
			}
			
			if ( $_FILES['uploadFile']['error'][0] ) {
				$this->set_upload_status ( "failed", sprintf ( esc_html__( "The following error occurred during the upload of %s: %s", 'yith-woocommerce-additional-uploads' ), $_FILES['uploadFile']['name'][0], $_FILES['uploadFile']['error'][0] ) );
				
				//  there was an error
				return;
			}
			
			//now is the time to modify the future file name and validate the file
			$file_name = sanitize_file_name ( strtolower ( $_FILES['uploadFile']['name'][0] ) ); //rename file
			
			//  Check if file extension is allowed
			$allowed_ext_array = explode ( ',', $this->allowed_extension );
			
			$file_ext = wp_check_filetype ( $file_name );
			$file_ext = $file_ext['ext'];
			
			if ( ( count ( $allowed_ext_array ) > 0 ) && ( ! in_array ( $file_ext, $allowed_ext_array ) ) ) {
				//  File extension not allowed
				$this->set_upload_status ( "failed", sprintf ( esc_html__( "The format of the %s file is not valid. The allowed extensions are: %s.", 'yith-woocommerce-additional-uploads' ),
					$_FILES['uploadFile']['name'][0],
					$this->allowed_extension ) );
				
				return;
			}
			
			$max_size_byte = 1048576 * $this->max_size; //  max size in bytes
			
			if ( $this->max_size && ( $_FILES['uploadFile']['size'][0] > $max_size_byte ) ) {
				$this->set_upload_status ( "failed", sprintf ( esc_html__( "The %s file has not been accepted, the maximum dimension is %s MB.", 'yith-woocommerce-additional-uploads' ), $_FILES['uploadFile']['name'][0], $this->max_size ) );
				
				//  File size not allowed
				return;
			}
			
			$file_name = sanitize_file_name ( strtolower ( $_FILES['uploadFile']['name'][0] ) ); //rename file
			
			if ( $this->save_file ( $_FILES['uploadFile']['tmp_name'][0], $order_id, $file_name ) ) {
				
				$relative_path = $this->get_relative_upload_path ( $order_id, $file_name );
				
				if ( $order_id ) {
					$order = wc_get_order ( $order_id );
					
					yit_save_prop ( $order, YWAU_METAKEY_ORDER_FILE_UPLOADED, $relative_path );
				} else {
					//  store reference to uploaded item on cart
					WC ()->session->set ( "ywau_order_file_uploaded", $relative_path );
				}
				
				$this->set_upload_status ( "successful", sprintf ( esc_html__( "The %s file has been included in the current order. Your order is now being processed.", 'yith-woocommerce-additional-uploads' ), $_FILES['uploadFile']['name'][0] ) );
			}
		}
		
		protected function save_file( $uploaded_file, $order_id, $filename ) {
			//  Put the files on a folder reserved to current order
			$order_dir = sprintf ( "%s/%s",
				YITH_YWAU_SAVE_DIR,
				apply_filters ( 'ywau_store_order_folder', $order_id ) );
			
			if ( ! file_exists ( $order_dir ) ) {
				wp_mkdir_p ( $order_dir );
			}
			
			$upload_file_path = sprintf ( "%s/%s", $order_dir, $filename );
			
			return move_uploaded_file ( $uploaded_file, $upload_file_path );
		}
		
		/**
		 * Retrieve the relative path for storing a file inside an order specific folder
		 *
		 * @param $order_id
		 * @param $filename
		 *
		 * @return string
		 */
		protected function get_relative_upload_path( $order_id, $filename ) {
			$order_dir = apply_filters ( 'ywau_store_order_folder', $order_id );
			
			return sprintf ( "%s/%s", $order_dir, $filename );
		}
		
		/**
		 * Init plugin settings
		 */
		public function init_plugin_settings() {
			$this->allowed_extension  = get_option ( 'ywau_allowed_extension', '' );
			$this->allow_on_cart_page = ( "yes" === get_option ( 'ywau_allow_upload_on_cart', 'no' ) );
			
			$this->max_size = get_option ( "ywau_max_file_size", 0 );
			
			if ( "yes" == get_option ( "ywau_allow_wc-completed", "no" ) ) {
				$this->allowed_status[] = 'wc-completed';
			}
			
			if ( "yes" == get_option ( "ywau_allow_wc-on-hold", "no" ) ) {
				$this->allowed_status[] = 'wc-on-hold';
			}
			
			if ( "yes" == get_option ( "ywau_allow_wc-pending", "no" ) ) {
				$this->allowed_status[] = 'wc-pending';
			}
			
			if ( "yes" == get_option ( "ywau_allow_wc-processing", "no" ) ) {
				$this->allowed_status[] = 'wc-processing';
			}
		}
		
		/**
		 * Add metaboxes for plugin features
		 */
		function add_metaboxes() {
			
			//  Add metabox on order page
			add_meta_box ( 'ywau_order_metabox', 'YITH Uploads', array(
				$this,
				'display_order_metabox',
			), 'shop_order', 'side', 'high' );
		}
		
		public function display_order_metabox( $post ) {
			
			if ( ! isset( $_GET["post"] ) ) {
				return;
			}
			
			$order_id = intval ( $_GET["post"] );
			
			echo '<div id="ywau_uploaded_file">';
			
			if ( $this->order_has_file_uploaded ( $order_id ) ) {
				echo '<span class="file-uploaded">' . esc_html__( "The customer has sent a file.", 'yith-woocommerce-additional-uploads' ) . '</span>';
				echo '<a class="download-uploaded-file" href="' . admin_url ( "admin.php?action=" . YWAU_ACTION_DOWNLOAD_FILE . "&order_id=$order_id" ) . '">' . esc_html__( "Download", 'yith-woocommerce-additional-uploads' ) . '</a>';
				
			} else {
				echo '<span class="file-not-uploaded">' . esc_html__( "There are no files attached to the order.", 'yith-woocommerce-additional-uploads' ) . '</span>';
			}
			echo "</div>";
			
		}
		
		/**
		 * Add frontend style
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function enqueue_frontend_resources() {
			//  register and enqueue ajax calls related script file
			wp_register_script ( "ywau-frontend",
				YITH_YWAU_ASSETS_JS_URL . yit_load_js_file ( 'ywau-frontend.js' ),
				array( 'jquery' ),
				false,
				true );
			
			wp_localize_script ( 'ywau-frontend', 'ywau', array(
					'loader'        => apply_filters ( 'yith_additional_uploads', YITH_YWAU_ASSETS_URL . '/images/loading.gif' ),
					'ajax_url'      => admin_url ( 'admin-ajax.php' ),
					'unable_delete' => esc_html__( "The file could not be deleted", 'yith-woocommerce-additional-uploads' ),
				)
			);
			wp_enqueue_script ( "ywau-frontend" );
			
			wp_enqueue_style ( 'ywau-frontend', YITH_YWAU_ASSETS_URL . '/css/ywau-frontend.css' );
		}
		
		/**
		 * Enqueue scripts on administration comment page
		 *
		 * @param $hook
		 */
		function enqueue_backend_resources( $hook ) {
			
			/**
			 * Add styles
			 */
			wp_enqueue_style ( 'ywau-backend', YITH_YWAU_ASSETS_URL . '/css/ywau-backend.css' );
			
			/**
			 * Add scripts
			 */
			wp_register_script ( "ywau-backend", YITH_YWAU_URL . 'assets/js/ywau-backend.js', array(
				'jquery',
				'jquery-blockui',
			) );
			
			wp_localize_script ( 'ywau-backend', 'ywau', array(
				'loader'                => apply_filters ( 'yith_question_answer_loader_gif', YITH_YWAU_ASSETS_URL . '/images/loading.gif' ),
				'ajax_url'              => admin_url ( 'admin-ajax.php' ),
				'order_message_success' => esc_html__( 'The message has been sent to the email address of the customer.', 'yith-woocommerce-additional-uploads' ),
				'order_message_fail'    => esc_html__( 'An error occurred, the message has not been sent.', 'yith-woocommerce-additional-uploads' ),
			) );
			
			wp_enqueue_script ( "ywau-backend" );
			
		}
		
		/**
		 * check if current order is eligible for file uploading
		 *
		 * @param int|WC_Order $order current order
		 *
		 * @return bool
		 */
		public function can_upload_file_on_order( $order ) {
			if ( is_null ( $order ) || ! $order ) {
				return true;    //  No restriction on $order not set
			}
			
			//  If the $order object is set, check if the order status is enabled looking to the plugin options
			if ( is_numeric ( $order ) ) {
				$order = wc_get_order ( $order );
			}
			
			return ( ! is_null ( $order ) && in_array ( 'wc-' . $order->get_status (), $this->allowed_status ) );
		}

		/**
		 *
		 * @param $order_id
		 *
		 * @return mixed
		 */
		public function order_has_file_uploaded( $order_id ) {
			if ( $order_id ) {
				$order = wc_get_order ( $order_id );
				
				return yit_get_prop ( $order, YWAU_METAKEY_ORDER_FILE_UPLOADED );
			} else {
				
				return WC ()->session->get ( "ywau_order_file_uploaded" );
			}
		}
		
		/**
		 * Build HTML block for uploads functionalities
		 *
		 * @param $order_id order id to whose files are attached. If $order_id is 0, files are attached to cart session and
		 *                  attached to the order  after
		 */
		public function show_upload_section( $order_id ) {
			echo "Order id: " . $order_id;
			echo '<div class="upload-file-section">';
			
			$allow_upload = true;
			
			//  Check if there was a previous POST and a file was uploaded
			if ( isset( $_POST["upload-status"] ) ) {
				echo '<div class="additional-uploads-message">';
				
				switch ( $_POST["upload-status"] ) {
					
					case "rejected" :
						$allow_upload = false;
						echo '<span class="error-message">' . esc_html ( $_POST["upload-message"] ) . '</span>';
						
						break;
					
					case "failed" :
						if ( $_POST["upload-message"] ) {
							
							echo '<span class="error-message">' . esc_html ( $_POST["upload-message"] ) . '</span>';
						} else {
							echo '<span class="error-message">' . esc_html__( "An error occurred, the file has not been accepted.", 'yith-woocommerce-additional-uploads' ) . '</span>';
						}
						
						break;
					
					case "successful" :
						$file_path = $this->order_has_file_uploaded ( $order_id );
						if ( ! empty( $file_path ) ) {
							$allow_upload = false;
							echo '<span class="success-message">' . esc_html ( $_POST["upload-message"] ) . '</span>';
							break;
						}
						echo '</div>';
				}
			} else {
				//  Check if current order has a file attached
				$uploaded_file = $this->order_has_file_uploaded ( $order_id );
				if ( ! empty( $uploaded_file ) ) {
					$allow_upload = false;
					
					echo '<span class="success-message">' . sprintf ( esc_html__( "The %s file has been included in the current order. Your order is now being processed.", 'yith-woocommerce-additional-uploads' ), basename ( $uploaded_file ) ) . '</span>';
				}
			}
			
			if ( ! $allow_upload ) {
				return;
			}
			
			?>
			<span
				class="upload-file-title"><?php _e ( "You can customize your order sending a file.", 'yith-woocommerce-additional-uploads' ); ?>
				<?php if ( $this->allowed_extension ):
					printf ( esc_html__( 'Choose one of the following formats: %s', 'yith-woocommerce-additional-uploads' ), $this->allowed_extension );
				endif; ?>
				</span>
			
			<form enctype="multipart/form-data" action="" method="POST">
				<input type="hidden" name="upload-file" value="file-to-upload">
				<input type="hidden" name="order-id" value="<?php echo $order_id; ?>">
				
				<div class="upload-items">
					<input type="button" value="<?php _e ( "Select file", 'yith-woocommerce-additional-uploads' ); ?>"
					       id="do_uploadFile" />
					<input type="file" name="uploadFile[]" id="uploadFile" accept="image/*" />
				</div>
				
				<div id="uploadFileList"></div>
			
			</form>
			
			<?php
			echo '</div>';
		}
		
		public function show_upload_section_on_view_page( $order_id ) {
			$this->show_upload_section ( $order_id );
		}

        /**
         * Action links
         *
         *
         * @return void
         * @since    1.2.4
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function action_links( $links ) {

            $links = yith_add_action_links( $links, $this->_panel_page, false );
            return $links;

        }

        /**
         * Plugin Row Meta
         *
         *
         * @return void
         * @since    1.2.4
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_YWAU_FREE_INIT' ) {

            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
                $new_row_meta_args[ 'slug' ] = 'yith-woocommerce-uploads';
            }

            return $new_row_meta_args;
        }

	}
}