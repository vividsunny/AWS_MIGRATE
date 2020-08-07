<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YITH_WooCommerce_Additional_Uploads_Premium' ) ) {

	/**
	 *
	 * @class   YITH_WooCommerce_Additional_Uploads_Premium
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Lorenzo Giuffrida
	 */
	class YITH_WooCommerce_Additional_Uploads_Premium extends YITH_WooCommerce_Additional_Uploads {

		public $uploaded_notification = array();

		/**
		 * @var string Custom folder where to store file uploaded, starting from wp-content/uploads/yith-additional-uploads
		 */
		public $upload_folder = '';

		/**
		 * @var string Set the mode for creating a order univocal folder. Can be "order_id" or "order_number"
		 */
		public $order_folder_type = 'order_id';

		/**
		 * @var int Set the width for uploaded file thumbnail in pixel
		 */
		public $thumbnail_width = 100;

		/**
		 * @var int Set the height for uploaded file thumbnail in pixel
		 */
		public $thumbnail_height = 100;

		/**
		 * @var int Set the quality of the jpg thumbnail [0..100]
		 */
		public $thumbnail_quality = 100;

		/**
		 * @var bool Allow customers to upload files on thankyou page.
		 */
		public $allow_on_thankyou_page = false;

		/**
		 * @var bool Allow customers to upload files on checkout page.
		 */
		public $allow_on_checkout_page = false;

		/**
		 * @var bool Choose whether to use standard WooCommerce behaviour that groups more items of the same products in one line,
		 * or to add to cart one item for each line and allow users to upload different files for each item
		 */
		public $split_products_on_cart = false;

		/**
		 * @var bool Set the order status to processing when all file requested is accepted
		 */
		public $set_processing_when_all_accepted = false;

		/**
		 * @var array Order status on which allow file deletion
		 */
		public $allowed_deletion_status = array();

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
			if ( is_null ( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
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
			parent::__construct ();

			/**
			 * Do some stuff on plugin init
			 */
			add_action ( 'init', array( $this, 'on_plugin_init' ) );

            /**
             * Including the GDRP
             */
            add_action( 'plugins_loaded', array( $this, 'load_privacy' ), 20 );

			/*
			 * Enqueue scripts and styles
			 */
			add_action ( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_resources' ), 199 );

			/**
			 * Set the folder where to store the uploaded files for current order
			 */
			add_filter ( 'ywau_store_order_folder', array( $this, 'get_orders_store_folder' ) );

			//region    *************** Product rules metabox
			/**
			 * Add metabox for managing product rules
			 */
			add_action ( 'add_meta_boxes', array( $this, 'add_product_rules_metabox' ) );

			/**
			 * Save product rules on order
			 */
			add_action ( 'woocommerce_process_shop_order_meta', array( $this, 'update_rules_on_save_order' ), 1, 2 );

			/**
			 * Save product rules on product
			 */
			add_action ( 'woocommerce_process_product_meta', array( $this, 'update_rules_on_save_product' ), 1, 2 );

			//endregion

			add_action ( 'woocommerce_admin_field_ywau_multi_upload_settings', array(
				$this,
				'show_order_uploads_settings'
			) );

			/*
			 * Custom action for an upload file request
			 */
			add_action ( 'yith_woocommerce_additional_action_upload_files', array(
				$this,
				'save_uploaded_files'
			) );


			//  Ajax callback for deleting file uploaded
			add_action ( 'wp_ajax_delete_uploaded_file', array( $this, 'delete_uploaded_file_callback' ) );
			add_action ( 'wp_ajax_nopriv_delete_uploaded_file', array(
				$this,
				'delete_uploaded_file_callback',
			) );

			//  Ajax callback for sending message to user from the order
			add_action ( 'wp_ajax_sent_message', array(
				$this,
				'sent_message_callback'
			) );

			/**
			 *  Show uploaded image on backend order page
			 */
			add_action ( 'woocommerce_after_order_itemmeta', array(
				$this,
				'show_thumbnail_on_order'
			), 10, 3 );

			/**
			 * Save rules of the current order
			 */
			add_action ( "ywau_save_order", array(
				$this,
				"save_order_rules"
			), 10, 2 );

			/**
			 * Save rules of the current product
			 */
			add_action ( "ywau_save_product", array(
				$this,
				"save_product_rules"
			), 10, 2 );

			/**
			 * Email system
			 */
			add_filter ( 'woocommerce_email_classes', array(
				$this,
				'add_woocommerce_email_classes'
			) );

			add_filter ( 'woocommerce_email_actions', array(
				$this,
				'add_woocommerce_trigger_action'
			) );

			add_filter ( 'woocommerce_locate_core_template', array(
				$this,
				'locate_core_template'
			), 10, 3 );

			/**
			 * Show icon on backend page "orders" for order where there is file uploaded and waiting to be confirmed.
			 */
			add_action ( 'manage_shop_order_posts_custom_column', array(
				$this,
				'show_icon_for_file_uploaded'
			) );

			/**
			 * Save product uploads rules
			 */
			add_filter ( 'woocommerce_admin_settings_sanitize_option_ywau_multi_upload_settings', array(
				$this,
				'save_products_rules_settings'
			) );

			add_action ( 'woocommerce_product_after_variable_attributes', array(
				$this,
				'woocommerce_product_after_variable_attributes'
			), 10, 3 );

			add_action ( 'woocommerce_product_after_variable_attributes_js', array(
				$this,
				'woocommerce_product_after_variable_attributes_js'
			) );

			add_action ( 'woocommerce_save_product_variation', array(
				$this,
				'save_variable_fields'
			), 10, 2 );

			/**
			 * Show the files uploaded at the order level
			 */
			add_action ( 'ywar_before_order_metabox', array(
				$this,
				'show_order_attached_uploads'
			) );


			//frontend hooks
            if (!is_admin()){

                /*
			 * On cart page.
			 * Add a link near the product name for showing the upload section
			 */
                add_filter ( 'woocommerce_cart_item_name', array(
                    $this,
                    'woocommerce_cart_item_name'
                ), 99, 3 );

                /*
                 * On thankyou page.
                 * Add a link near the product name for showing the upload section
                 */
                add_action ( 'woocommerce_order_item_meta_start', array(
                    $this,
                    'woocommerce_order_item_meta_start',
                ), 10, 3 );

                /**
                 * Let the user to upload files on my order page
                 */
                add_action ( 'woocommerce_order_item_meta_end', array( $this, 'show_upload_button_my_order' ), 10, 3 );

                /**
                 * Manage the uploads rules at global order level and move them from cart to order metadata
                 */
                add_action ( 'woocommerce_new_order', array(
                    $this,
                    'move_global_uploads_to_order'
                ) );

                /**
                 * Disable the upload link while showing the mini-cart
                 */
                add_action ( 'woocommerce_before_mini_cart', array(
                    $this,
                    'disable_upload_link_visualization'
                ) );

                /**
                 * Enable the effects leaving the mini-cart
                 */
                add_action ( 'woocommerce_after_mini_cart', array(
                    $this,
                    'enable_upload_link_visualization'
                ) );

                /**
                 * Disable the upload link in emails
                 */
                add_action ( 'woocommerce_email_header', array(
                    $this,
                    'disable_upload_link_visualization'
                ) );

                /**
                 * Enable the upload link in emails
                 */
                add_action ( 'woocommerce_email_footer', array(
                    $this,
                    'enable_upload_link_visualization'
                ) );

                add_action ( 'yith_ywau_email_file_uploaded_order_item', array(
                    $this,
                    'show_email_file_uploaded'
                ), 10, 4 );

                /* Show link email for upload on order */
                add_action('woocommerce_email_before_order_table', array($this,'show_uploads_on_order'),10,2);

                /**
                 * Manage a unique id for current cart
                 */
                add_action ( 'woocommerce_set_cart_cookies', array(
                    $this,
                    'set_cart_id'
                ) );

                /**
                 * Let the customer to upload files attached to the order in checkout page
                 */
                add_action ( 'woocommerce_before_checkout_form', array(
                    $this,
                    'show_order_upload_on_woocommerce_before_checkout_form'
                ) );

                add_action ( "woocommerce_before_cart", array(
                    $this,
                    'show_order_upload_on_woocommerce_before_cart'
                ) );

                add_action ( 'woocommerce_thankyou', array(
                    $this,
                    'show_order_upload_on_woocommerce_thankyou'
                ), 1 );

                add_action ( 'woocommerce_view_order', array(
                    $this,
                    'show_order_upload_on_woocommerce_view_order'
                ), 1 );

                add_action ( 'woocommerce_checkout_order_processed', array(
                    $this,
                    'uploaded_item_email_notification'
                ) );

                add_filter ( 'woocommerce_add_cart_item', array(
                    $this,
                    'woocommerce_add_cart_item'
                ), 10, 2 );

                add_filter ( 'woocommerce_add_cart_item_data', array(
                    $this,
                    'woocommerce_add_cart_item_data'
                ), 10, 3 );

                if ( version_compare ( WC ()->version, '3.0', '<' ) ) {
                    add_action ( 'woocommerce_add_order_item_meta', array(
                        $this,
                        'woocommerce_add_order_item_meta'
                    ), 10, 3 );
                } else {
                    add_action ( 'woocommerce_new_order_item', array(
                        $this,
                        'woocommerce_new_order_item'
                    ), 10, 3 );

                }
            }


		}

        /**
         * Including the GDRP
         */
        public function load_privacy() {

            if ( class_exists( 'YITH_Privacy_Plugin_Abstract' ) )
                require_once( YITH_YWAU_DIR . 'lib/class.yith-woocommerce-additional-uploads-privacy.php' );

        }

		/**
		 * Notify new uploaded files, if any.
		 *
		 * @param int $order_id the order id
		 */
		public function uploaded_item_email_notification($order_id) {
			if ( $this->uploaded_notification ) {
				//  Send email notification for new element uploaded
				do_action ( 'ywau_email_order_file_uploaded_notification', $order_id, $this->uploaded_notification );
			}
		}

		/**
		 * Show the files uploaded at the order level
		 *
		 * @param WP_Post $post
		 */
		public function show_order_attached_uploads( $post ) {

			$order = wc_get_order ( $post->ID );
			$items = yit_get_prop ( $order, YWAU_METAKEY_ORDER_ITEM_FILES, true );
			if ( empty( $items ) ) {
				return;
			}


			foreach ( $items as $item_key => $item_array ) {
				foreach ( $item_array as $item_id => $item_content ) {
					$this->show_thumbnail_on_order ( $post->ID, $item_content, 0, 'order' );

					return;
				}
			}
		}

		/**
		 * Manage a unique id for current cart stored on COOKIE
		 */
		public function set_cart_id( $set ) {
			if ( $set ) {
				if ( ! isset( $_COOKIE['ywau_cart_id'] ) && WC ()->cart ) {
					wc_setcookie ( 'ywau_cart_id', md5 ( json_encode ( WC ()->cart->get_cart_for_session () ) . time () ) );
				}
			} elseif ( isset( $_COOKIE['ywau_cart_id'] ) ) {
				wc_setcookie ( 'ywau_cart_id', '', time () - HOUR_IN_SECONDS );
			}
		}

		/**
		 * Show the uploaded elements on email
		 *
		 * @param int      $item_id
		 * @param array    $item
		 * @param WC_Order $order
		 * @param array    $uploaded_elements
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function show_email_file_uploaded( $item_id, $item, $order, $uploaded_elements ) {
			if ( ! $uploaded_elements ) {
				return;
			}
			$label_shown = false;

			/** @var YWAU_Uploaded_Item $uploaded_item */
			foreach ( $uploaded_elements as $uploaded_item ):
				if ( null == $uploaded_item ) {
					continue;
				}
				if ($item_id != $uploaded_item->item_id) {
					continue;
				}
				if (!$label_shown) {
					$label_shown = true;
                    echo '<p>' . apply_filters( 'yith_wc_uploads_email_elements_uploades', esc_html__( "Uploaded elements", 'yith-woocommerce-additional-uploads' ) ) . '</p>';
				}
				$thumbnail_path = $uploaded_item->get_thumbnail_filepath ();
				$thumbnail_path = ! empty( $thumbnail_path ) ? ( YITH_YWAU_SAVE_URL . $thumbnail_path ) : ( YITH_YWAU_ASSETS_IMAGES_URL . 'placeholder.jpg' );
				?>

				<div class="rule-thumb">
					<a target="_blank"
					   href="<?php echo ( YITH_YWAU_SAVE_URL . $uploaded_item->upload_filepath ); ?>">
						<img
							src="<?php echo $thumbnail_path; ?>"
							style="height: <?php echo $this->thumbnail_height; ?>px; width: <?php echo $this->thumbnail_width; ?>px;" />
					</a>
				</div>

				<?php
			endforeach;
		}


		/**
		 * Disable the effects entering the mini-cart
		 */
		public function disable_upload_link_visualization() {

			remove_filter ( 'woocommerce_cart_item_name', array(
				$this,
				'woocommerce_cart_item_name',
			), 99 );


			remove_action ( 'woocommerce_order_item_meta_start', array(
				$this,
				'woocommerce_order_item_meta_start',
			), 10 );
		}

		/**
		 * Enable the effects leaving the mini-cart
		 */
		public function enable_upload_link_visualization() {

			if ( $this->allow_on_cart_page ) {
				add_filter ( 'woocommerce_cart_item_name', array(
					$this,
					'woocommerce_cart_item_name',
				), 99, 3 );
			}

			add_action ( 'woocommerce_order_item_meta_start', array(
				$this,
				'woocommerce_order_item_meta_start',
			), 10, 3 );
		}

		/**
		 * Show the upload button for uploads related to the order
		 *
		 * @param int $order_id
		 */
		public function show_global_order_upload_section( $order_id = 0 ) {

			$item_id = '';

			if ( $order_id ) {
				$order   = wc_get_order ( $order_id );
				$item_id = yit_get_prop ( $order, YWAU_METAKEY_ORDER_UPLOADS );
			} elseif ( isset( $_COOKIE['ywau_cart_id'] ) ) {
				$item_id = $_COOKIE['ywau_cart_id'];
			}

			if ( $item_id ) :
				?>
				<div class="ywau-order-files">
					<span class="upload-file-title">
						<?php echo get_option('ywau_order_upload_text'); ?>
					</span>
					<?php echo $this->get_upload_link ( $item_id, 0, 0, $order_id, 'order' ); ?>
                    <?php do_action('ywau_after_order_upload_link', $item_id, 0, 0, $order_id ); ?>
				</div>
				<?php
			endif;
		}

		/**
		 * Check if the upload files for order is enabled and show the button on cart page
		 */
		public function show_order_upload_on_woocommerce_before_cart() {
			$this->start_show_upload_for_order ();
		}

		/**
		 * Check if the upload files for order is enabled and show the button on checkout page
		 */
		public function show_order_upload_on_woocommerce_before_checkout_form() {
			$this->start_show_upload_for_order ();
		}

		/**
		 * Check if the upload files for order is enabled and show the button on thankyou page
		 *
		 * @param int $order_id
		 *
		 */
		public function show_order_upload_on_woocommerce_thankyou( $order_id ) {

			$this->start_show_upload_for_order ( $order_id );
		}

		/**
		 * Check if the upload files for order is enabled and show the button on view order page
		 *
		 * @param int $order_id
		 */
		public function show_order_upload_on_woocommerce_view_order( $order_id ) {
			$this->start_show_upload_for_order ( $order_id );
		}

		/**
		 * show the upload section for orders
		 *
		 * @param int $order_id
		 */
		public function start_show_upload_for_order( $order_id = 0 ) {

			if ( $this->can_show_order_upload_link () && $this->can_upload_file_on_order ( $order_id ) ) {
				$this->show_global_order_upload_section ( $order_id );
			}
		}


		public function move_global_uploads_to_order( $order_id ) {
			//  store files attached on cart and referring the entire order to the new order object
			$item_id = $_COOKIE['ywau_cart_id'];
			$order   = wc_get_order ( $order_id );
			yit_save_prop ( $order, YWAU_METAKEY_ORDER_UPLOADS, $item_id );

			$items_on_cart = new YWAU_Uploaded_Items();
			$items_on_cart->load_items ( true );

			$items_on_order = new YWAU_Uploaded_Items();
			$items_on_order->load_items ( false, $order_id, 'order' );

			/** @var $item YWAU_Uploaded_Item */
			if ( ! $items_on_cart->item_exists ( $item_id ) ) {
				return;
			}

			$items_by_key = $items_on_cart->get_items_by_key ( $item_id );

			/** @var $uploaded_item YWAU_Uploaded_Item */
			if ( ! $items_by_key ) {
				return;
			}

			foreach ( $items_by_key as $uploaded_item ) {

				//  We have to update order id from 0 to real order id and move files from temporary folder to order specific folder.
				$uploaded_item->order_id = $order_id;
				$uploaded_item->item_id  = $item_id;

				if ( $uploaded_item->has_file_uploaded () ) {

                    if ( get_option( 'ywau_accept_order_file_automatically' ) == 'yes' )
                        $uploaded_item->set_admin_accepted();

					$uploaded_item                 = $this->move_file_from_cart_to_order ( $item_id, $uploaded_item );
					$this->uploaded_notification[] = $uploaded_item;
				}

				$items_on_order->set_item ( $uploaded_item, $item_id, $uploaded_item->rule_id );
			}

			//  update woocommerce_order_itemmeta
			$items_on_order->save_items ( 'order', $order_id );
		}

		/**
		 * Move file uploaded in the cart to the corresponding item on the order created
		 *
		 * @param $order_item_id
		 * @param $cart_item_key
		 */
		public function move_item_from_cart_to_order( $order_item_id, $cart_item_key ) {

			//  store files attached on cart to order items
			if ( ! isset( WC ()->cart ) ) {

				return;
			}

			$uploadedItems = new YWAU_Uploaded_Items();
			$uploadedItems->load_items ( true );

			$order_item_items = new YWAU_Uploaded_Items();
			$order_item_items->load_items ( false, $order_item_id );

			/** @var $item YWAU_Uploaded_Item */
			if ( ! $uploadedItems->item_exists ( $cart_item_key ) ) {

				return;
			}

			//  Retrieve the order id
			global $wpdb;
			$order_id = $wpdb->get_var ( $wpdb->prepare (
				"SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items where order_item_id = %s", $order_item_id ) );

			$items_by_key = $uploadedItems->get_items_by_key ( $cart_item_key );

			/** @var $uploaded_item YWAU_Uploaded_Item */
			if ( ! $items_by_key ) {

				return;
			}

			foreach ( $items_by_key as $uploaded_item ) {

				//  We have to update order id from 0 to real order id and move files from temporary folder to order specific folder.
				$uploaded_item->order_id = $order_id;
				$uploaded_item->item_id  = $order_item_id;

				if ( $uploaded_item->has_file_uploaded () ) {

				    if ( get_option( 'ywau_accept_product_file_automatically' ) == 'yes' )
                        $uploaded_item->set_admin_accepted();

					$uploaded_item                 = $this->move_file_from_cart_to_order ( $order_item_id, $uploaded_item );
					$this->uploaded_notification[] = $uploaded_item;
				}

				$order_item_items->set_item ( $uploaded_item, $order_item_id, $uploaded_item->rule_id );
			}

			$order_item_items->save_items ();
		}

		/**
		 * Move the elements uploaded from cart page or checkout page to the final folder and associate them
		 * with the right order item
		 *
		 * @param int    $order_item_id
		 * @param string $cart_item_key
		 *
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function woocommerce_add_order_item_meta( $order_item_id, $values, $cart_item_key ) {
			if ( $this->allow_on_cart_page || $this->allow_on_checkout_page ) {
				$this->move_item_from_cart_to_order ( $order_item_id, $cart_item_key );
			}
		}

		public function woocommerce_new_order_item( $item_id, $item, $order_id ) {
			if ( 'line_item' == $item->get_type () ) {
				$this->woocommerce_add_order_item_meta ( $item_id, $item->legacy_values, $item->legacy_cart_item_key );
			}
		}

		public function woocommerce_add_cart_item( $data, $key ) {
			if ( $this->split_products_on_cart ) {
				$quantity         = $data["quantity"];
				$data["quantity"] = 1;
				WC ()->cart->add_to_cart ( $data["product_id"], $quantity - 1, $data["variation_id"], $data["variation"] );
			}

			return $data;
		}

		public function woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
			if ( $this->split_products_on_cart ) {
				$cart_item_data["unique_id"] = uniqid ( mt_rand () );
			}

			return $cart_item_data;
		}

		/**
		 * Override base class.
		 * Store data from cart to order item metas
		 *
		 * @param $order_id id of the order created
		 * @param $posted
		 */
		public function attach_file_from_cart_to_order( $order_id, $posted ) {
			WC ()->session->__unset ( "ywau_order_file_uploaded" );
			WC ()->session->__unset ( YWAU_CART_UPLOAD_RESULTS );
		}

		/**
		 * Skip standard saving steps for custom type ywau_multi_upload_settings
		 */
		public function save_products_rules_settings() {

			$this->update_multi_upload_settings ();

			return null;
		}


		public function locate_core_template( $core_file, $template, $template_base ) {
			$custom_template = array(
				'emails/ywau-order-admin-message.php',
				'emails/plain/ywau-order-admin-message.php',
				'emails/ywau-order-file-actions.php',
				'emails/plain/ywau-order-file-actions.php',
				'emails/ywau-order-file-deleted.php',
				'emails/plain/ywau-order-file-deleted.php',
				'emails/ywau-order-file-uploaded.php',
				'emails/plain/ywau-order-file-uploaded.php',
			);

			if ( in_array ( $template, $custom_template ) ) {
				$core_file = YITH_YWAU_TEMPLATES_DIR . '/' . $template;
			}

			return $core_file;
		}

		function add_woocommerce_trigger_action( $actions ) {
			$actions[] = 'ywau_email_order_admin_message';
			$actions[] = 'ywau_email_order_file_actions';
			$actions[] = 'ywau_email_order_file_uploaded';
			$actions[] = 'ywau_email_order_file_deleted';

			return $actions;
		}

		public function add_woocommerce_email_classes( $email_classes ) {
			// add the email class to the list of email classes that WooCommerce loads
			$email_classes['ywau_order_admin_message'] = include ( 'emails/class.ywau-order-admin-message.php' );
			$email_classes['ywau_order_file_actions']  = include ( 'emails/class.ywau-order-file-actions.php' );
			$email_classes['ywau_order_file_deleted']  = include ( 'emails/class.ywau-order-file-deleted.php' );
			$email_classes['ywau_order_file_uploaded'] = include ( 'emails/class.ywau-order-file-uploaded.php' );

			return $email_classes;
		}

		/**
		 * Admins can sent email messages to customer regarding the files to be attached to current order. Override base method.
		 */
		public function display_order_metabox( $post ) {
			do_action ( 'ywar_before_order_metabox', $post );
			?>
			<div class="order-message-section">
				<fieldset>
					<label><?php _e ( "Send a message to customer", 'yith-woocommerce-additional-uploads' ); ?></label>

					<div>
                        <textarea id="ywau_order_message" name="ywau_order_message" class="order-message"
                                  placeholder=" <?php esc_html__( "Insert here the message you want to send to the customer", 'yith-woocommerce-additional-uploads' ); ?>"></textarea>
						<button type="button" id="send-order-message"
						        class="send-order-message"><?php _e ( "Send", 'yith-woocommerce-additional-uploads' ); ?></button>
					</div>
				</fieldset>
			</div>
			<?php

			do_action ( 'ywar_after_order_metabox', $post );
		}

		/**
		 * Show uploaded file as thumbnail inside the order page, for every product
		 */
		public function show_thumbnail_on_order( $item_id, $item, $_product, $scope = 'item' ) {

			if ( ( 'item' == $scope ) && ( 'line_item' != $item['type'] ) ) {
				return;
			}

			$product_id   = $item["product_id"];
			$variation_id = $item["variation_id"];

			$_items = new YWAU_Uploaded_Items();
			$_items->load_items ( false, $item_id, $scope );

			$rules         = new YWAU_Rules();
			$product_rules = $rules->get_product_rules_settings ( $product_id, $variation_id );

			//  if the scope is 'order', $item_id is the order id while the actually $item_id is a field of $item
			if ( 'order' == $scope ) {
				$item_id = $item['item_id'];
			}

			// if the upload is disabled for products, dont show the upload section in the order
			if (  $item instanceof WC_Order_Item_Product && 'no' === get_option ('ywau_enable_product_upload') )
				return;

			/** @var $product_rule YWAU_Rule */
			?>
			<?php if ( apply_filters('ywar_show_uploaded_thumbnails_on_order', true) && $product_rules ) : ?>
				<div class="uploaded-thumbnails">
					<?php foreach ( $product_rules as $product_rule ) {


						$uploaded_item = $_items->get_item ( $item_id, $product_rule->rule_id );
						?>
						<div class="product-rule clearfix rule-no-<?php echo $product_rule->rule_id; ?>">
							<div class="rule-label">
<span class="rule-label-title"><?php _e ( "Rule label:", 'yith-woocommerce-additional-uploads' ); ?>
	<span class="rule-label-value"><?php echo $product_rule->label; ?></span>
</span>
							</div>

							<div class="rule-content">
								<?php if ( null != $uploaded_item ): ?>
									<?php if ( $uploaded_item->is_upload_successful () ): // show thumbnail ?>

										<div class="rule-thumb">
											<a target="_blank"
											   href="<?php echo ( YITH_YWAU_SAVE_URL . $uploaded_item->upload_filepath ); ?>">
												<?php
												$thumbnail_path = $uploaded_item->get_thumbnail_filepath ();
												$thumbnail_path = ! empty( $thumbnail_path ) ? ( YITH_YWAU_SAVE_URL . $thumbnail_path ) : ( YITH_YWAU_ASSETS_IMAGES_URL . 'placeholder.jpg' );
												?>
												<img
													src="<?php echo $thumbnail_path; ?>"
													style="height: <?php echo $this->thumbnail_height; ?>px; width: <?php echo $this->thumbnail_width; ?>px;">
											</a>
										</div>
									<?php endif; ?>

									<div class="user-note">
										<?php if ( ! empty( $uploaded_item->note ) ) {
											echo esc_html__( "User notes: ", 'yith-woocommerce-additional-uploads' ) . $uploaded_item->note;
										} else {
											_e ( "There are no user notes", 'yith-woocommerce-additional-uploads' );
										} ?>
									</div>

								<?php endif; ?>

							</div>

							<div class="rule-actions">
								<?php if ( null != $uploaded_item ): ?>
									<?php if ( $uploaded_item->is_waiting_response () ): ?>
										<input
											name="ywau_order_file_status[<?php echo sprintf ( "%s_%s", $item_id, $product_rule->rule_id ); ?>]"
											type="hidden" value="0" />
										<input
											name="ywau_file_scope[<?php echo sprintf ( "%s_%s", $item_id, $product_rule->rule_id ); ?>]"
											type="hidden" value="<?php echo $scope; ?>" />
										<button class="set-upload-file-status accept"
										        data-item-id="<?php echo $item_id; ?>"
										        data-rule-id="<?php echo $product_rule->rule_id; ?>"
										        data-action="accept"><?php _e ( "Accept", 'yith-woocommerce-additional-uploads' ); ?></button>
										<button class="set-upload-file-status reject"
										        data-item-id="<?php echo $item_id; ?>"
										        data-rule-id="<?php echo $product_rule->rule_id; ?>"
										        data-action="reject"><?php _e ( "Reject", 'yith-woocommerce-additional-uploads' ); ?></button>
										<span class="upload-file-status accepted"
										      style="display:none"><?php _e ( "File will be <b>accepted</b>: please remember to save the order.", 'yith-woocommerce-additional-uploads' ); ?></span>
										<span class="upload-file-status rejected"
										      style="display:none"><?php _e ( "File will be <b>rejected</b>: please remember to save the order.", 'yith-woocommerce-additional-uploads' ); ?></span>
										<a href="#" class="reset-upload-file-status" style="display:none"
										   data-item-id="<?php echo $item_id; ?>"
										   data-rule-id="<?php echo $product_rule->rule_id; ?>"><?php _e ( "Cancel", 'yith-woocommerce-additional-uploads' ); ?></a>
										<textarea
											name="ywau_order_file_note[<?php echo sprintf ( "%s_%s", $item_id, $product_rule->rule_id ); ?>]"
											disabled style="display:none" class="order-file-note"
											placeholder="<?php _e ( "Insert here the message you want to send to the customer", 'yith-woocommerce-additional-uploads' ); ?>"></textarea>
									<?php else: ?>
										<?php if ( $uploaded_item->is_accepted () ): ?>
											<span
												class="admin-response-exists"><?php echo sprintf ( esc_html__( "The file has been accepted on %s.", 'yith-woocommerce-additional-uploads' ), $uploaded_item->admin_response_date ); ?> </span>
											<?php
										elseif ( $uploaded_item->is_rejected () ):
											$message = sprintf ( esc_html__( "The file has been rejected on %s.", 'yith-woocommerce-additional-uploads' ), $uploaded_item->admin_response_date );
											if ( ! empty( $uploaded_item->admin_response ) ) {
												$message .= sprintf ( esc_html__( " You have sent the following notification: %s.", 'yith-woocommerce-additional-uploads' ), $uploaded_item->admin_response );
											}
											?>
											<span class="admin-response-exists"><?php echo $message; ?></span>
										<?php endif; ?>
									<?php endif; ?>
								<?php else: ?>
									<span><?php _e ( "There are no associated files.", 'yith-woocommerce-additional-uploads' ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php } ?>
				</div>

				<?php
			endif;

		}

		/**
		 * Let the user to upload file on thankyou page
		 *
		 * @param          $item_id
		 * @param          $item
		 * @param WC_Order $order
		 */
		public function woocommerce_order_item_meta_start( $item_id, $item, $order ) {

			if ( $this->can_show_product_upload_link () ) {

				//  Pick upload data from the order item id
				$product_id   = $item["product_id"];
				$variation_id = $item["variation_id"];
				echo $this->get_upload_link ( $item_id, $product_id, $variation_id, yit_get_prop ( $order, 'id' ) );
			}
		}


		public function woocommerce_order_item_name( $title, $item ) {
			return;
		}

		/**
		 * Create a link for file deletion
		 *
		 * @param $order_id
		 * @param $order_item
		 * @param $rule_id
		 *
		 * @return string
		 */
		private function get_uploaded_file_delete_url( $order_id, $order_item, $rule_id ) {
			if ( is_cart () || ( ! $order_id ) || ( ( ( $order = wc_get_order ( $order_id ) ) != null ) && in_array ( "wc-" . $order->get_status (), $this->allowed_deletion_status ) ) ) {
				return '<a href="#" class="delete-uploaded-file" data-rule-id="' . $rule_id . '">' . esc_html__( "Remove file", 'yith-woocommerce-additional-uploads' ) . '</a>';
			}

			return '';
		}

		/**
		 * Move a temporary element uploaded from the cart to the final folder
		 *
		 * @param int                $order_item_id the order item id
		 * @param YWAU_Uploaded_Item $uploaded_item the item to move
		 *
		 * @return mixed
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function move_file_from_cart_to_order( $order_item_id, $uploaded_item ) {

			$order_id          = $uploaded_item->order_id;
			$uploaded_filename = basename ( $uploaded_item->upload_filepath );

			$relative_filepath = $this->get_relative_item_upload_path ( $order_id, $order_item_id, $uploaded_item->rule_id, $uploaded_filename );

			$new_dir = sprintf ( "%s/%s", YITH_YWAU_SAVE_DIR, $this->get_relative_item_upload_dir ( $order_id, $order_item_id, $uploaded_item->rule_id ) );

			wp_mkdir_p ( $new_dir );

			$starting_path    = YITH_YWAU_SAVE_DIR . $uploaded_item->upload_filepath;
			$destination_path = YITH_YWAU_SAVE_DIR . $relative_filepath;

			//  move file to new folder
			try {
			    if ( file_exists( $starting_path ) && $destination_path)
				    rename ( $starting_path, $destination_path );
			} catch ( Exception $e ) {
				error_log ( 'Caught exception: ', $e->getMessage (), "\n" );
			}

			//  move the thumbnail if exists
			$starting_path_thumb = YITH_YWAU_SAVE_DIR . $uploaded_item->get_thumbnail_filepath ();

			if ( ! empty( $starting_path_thumb ) ) {

				$destination_path_thumb = sprintf ( "%s/%s", $new_dir, $uploaded_item->get_thumbnail_filename () );

				//  move file to new folder
				try {
                    if ( file_exists( $starting_path_thumb ) && $destination_path_thumb )
                        rename ( $starting_path_thumb, $destination_path_thumb );

				} catch ( Exception $e ) {
					error_log ( 'Caught exception: ', $e->getMessage (), "\n" );
				}
			}

			$uploaded_item->upload_filepath = $relative_filepath;
			$uploaded_item->item_id         = $order_item_id;

			return $uploaded_item;
		}

		/**
		 * Enqueue frontend resources
		 */
		public function enqueue_frontend_resources() {

			$do_enqueue = ( $this->allow_on_products && $this->can_show_product_upload_link () ) ||
			              ( $this->allow_on_orders && $this->can_show_order_upload_link () );

			if ( $do_enqueue ) {

				parent::enqueue_frontend_resources ();

				/**
				 * Enqueue PrettyPhoto plugin
				 */
				$wc_assets_path = str_replace ( array( 'http:', 'https:' ), '', WC ()->plugin_url () ) . '/assets/';
				$suffix         = defined ( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				wp_register_script ( 'prettyPhoto', $wc_assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
				wp_enqueue_script ( 'prettyPhoto' );
				wp_enqueue_style ( 'ywau-prettyPhoto', $wc_assets_path . 'css/prettyPhoto.css' );

				/**
				 * Add custom init
				 */
                wp_enqueue_script ( 'yith-ywau-prettyPhoto-init',
                    apply_filters('ywau_src_pretty_photo_script',YITH_YWAU_ASSETS_JS_URL . yit_load_js_file ( 'init.prettyPhoto.js' )),
                    array(
                        'jquery',
                        'prettyPhoto',
                    ), false, true );
			}
		}

		/**
		 * Set the folder where to store the uploaded files for current order
		 */
		public function get_orders_store_folder( $order_id ) {
			if ( ! empty( $this->upload_folder ) ) {
				return sprintf ( "%s/%s", $this->upload_folder, $this->get_folder_name_from_order ( $order_id ) );
			}

			return $this->get_folder_name_from_order ( $order_id );
		}

		/**
		 * Retrieve the order id or order number, based on the user settings
		 *
		 * @param int $order_id current order id
		 *
		 * @return string folder name to be used
		 */
		private function get_folder_name_from_order( $order_id ) {

			$res = $order_id;

			if ( 'order_number' == $this->order_folder_type ) {
				$order = wc_get_order ( $order_id );
				if ( $order ) {
					$res = $order->get_order_number ();
				}
			}

			return $res;
		}

		//region ******************* Custom type - global rules shown on plugin setting page

		/**
		 * Show product uploads settings
		 *
		 * @param $value
		 */
		public function show_order_uploads_settings( $value ) {
			$upload_type = "global";

			include ( YITH_YWAU_TEMPLATES_DIR . '/admin/file-upload-rules.php' );
		}

		/**
		 * Save global settings from the plugin setting page
		 *
		 */
		public function update_multi_upload_settings() {

			$this->save_rules ();
		}

		//endregion

		//region ***********************    Product rules metabox
		/**
		 * Add metabox for product rules
		 */
		public function add_product_rules_metabox() {
			add_meta_box ( 'ywau_order_metabox', 'YITH Uploads', array(
				$this,
				'display_product_rules_metabox',
			), 'product', 'normal', 'high' );
		}

		/**
		 * Display a metabox on product page for adding rules for the product
		 */
		public function display_product_rules_metabox() {
			global $post;
			$product = wc_get_product ( $post->ID );

			$disabled_upload = yit_get_prop ( $product, '_ywau_disabled_upload' );
			?>

			<input type="hidden" name="ywau_section" value="1">
			<input type="checkbox" name="ywau_disable_upload"
			       class="disable_upload" <?php checked ( $disabled_upload ); ?>><?php _e ( "Disable the upload", 'yith-woocommerce-additional-uploads' ); ?>
			<?php

			$this->show_product_uploads_controls ( $post->ID );
		}

		/**
		 * Show upload controls for a specific product
		 *
		 * @param $product_id product id to show
		 */
		public function show_product_uploads_controls( $product_id ) {
			$upload_type = "product";
			$item_id     = $product_id;
			$strict_mode = true;
			include ( YITH_YWAU_TEMPLATES_DIR . '/admin/file-upload-rules.php' );
		}

		/**
		 * Save the status for each rules from the order page
		 *
		 * @param int     $post_id current order id
		 * @param WP_Post $post    current order
		 */
		public function save_order_rules( $post_id, $post ) {
			if ( isset( $_POST["ywau_order_file_status"] ) ) {

				$file_status = $_POST["ywau_order_file_status"];
				$notify      = false;

				foreach ( $file_status as $key => $value ) {

					//  if value if 1, the file is set as accepted, while -1
					//  stands for file rejected. 0 is the default value, for
					//  status not updated.
					if ( 0 == $value ) {
						continue;
					}

					//  Some change should be notified
					$notify = true;

					//  $key is like 133_3 where 133 is the item id and 3 is the rule id
					//  that identify a specific file.
					list( $item_id, $rule_id ) = explode ( "_", $key );

					$admin_note = '';
					$scope      = 'item';
					if ( isset( $_POST["ywau_file_scope"] ) ) {
						$scope = $_POST["ywau_file_scope"][ $key ];
					}

					if ( isset( $_POST["ywau_order_file_note"] ) &&
					     isset( $_POST["ywau_order_file_note"][ $key ] )
					) {
						$admin_note = $_POST["ywau_order_file_note"][ $key ];

					}

					//  Check for action to take
					$_items = new YWAU_Uploaded_Items();

					// the item_id used for load_items is the order id if the scope is 'order'
					if ( 'order' == $scope ) {
						$_items->load_items ( false, $post_id, $scope );
					} else {
						$_items->load_items ( false, $item_id, $scope );
					}


					if ( $_items ) {
						$item = $_items->get_item ( $item_id, $rule_id );

						if ( $item ) {

							if ( 1 == $value ) {
								$item->set_admin_accepted ();
							} else if ( - 1 == $value ) {
								$item->set_admin_rejected ( $admin_note );
							}

							$_items->set_item ( $item, $item_id, $rule_id );

							$_items->save_items ( $scope, $post_id );
						}
					}
				}

				if ( $notify ) {
					//  send a notification to the user
					do_action ( 'ywau_email_order_file_actions_notification', $post_id );

					/*
					$send_mail = new YWAU_Order_Admin_Message();
					$result = $send_mail->trigger($post_id);
					*/
				}
			}
		}

		/**
		 * Save rules of the current product, on edit product page
		 *
		 */
		public function save_product_rules( $post_id, $post ) {
			if ( ! isset( $_POST["ywau_section"] ) ) {
				return;
			}

			//  Current product has uploads disabled ?
			$disabled_upload = isset( $_POST["ywau_disable_upload"] ) ? true : false;
			$product         = wc_get_product ( $_POST["ID"] );
			if ( $disabled_upload ) {
				yit_save_prop ( $product, '_ywau_disabled_upload', true );
			} else {
				yit_delete_prop ( $product, '_ywau_disabled_upload' );
			}

			$this->save_rules ();
		}

		/**
		 * Save data uploads for order
		 *
		 * @param $post_id
		 * @param $post
		 */
		public function update_rules_on_save_order( $post_id, $post ) {
			if ( "shop_order" === get_post_type ( intval ( $post_id ) ) ) {
				do_action ( "ywau_save_order", $post_id, $post );
			}
		}


		/**
		 * Save rules for products
		 *
		 * @param $post_id
		 * @param $post
		 */
		public function update_rules_on_save_product( $post_id, $post ){
			if ( "product" === get_post_type ( intval ( $post_id ) ) ) {
				do_action ( "ywau_save_product", $post_id, $post );
			}
		}

		//endregion

		/**
		 * Save submitted rules options for product, category or global use.
		 */
		private function save_rules() {

			if ( isset( $_POST["ywau_upload_label"] ) && isset( $_POST["ywau_allowed_extensions"] ) ) {

				$labels          = $_POST["ywau_upload_label"];
				$extensions      = $_POST["ywau_allowed_extensions"];
				$max_sizes       = $_POST["ywau_max_size"];
				$rule_ids        = $_POST["ywau_rule_id"];
				$allow_rule_note = isset( $_POST["ywau_rule_note"] ) ? $_POST["ywau_rule_note"] : array();
				$visible_rules   = $_POST["ywau_rule_status"];

				$rule_settings = new YWAU_Rules();

				foreach ( $rule_ids as $key => $value ) {
					if ( 0 == $value ) {
						update_option ( "ywau_multi_upload_max_id", 1 + ( $max_id = get_option ( 'ywau_multi_upload_max_id', 1 ) ) );
						$value = $max_id;
					}

					$rule_array = array(
						'rule-id'        => $value,
						'upload-type'    => $_POST["upload_type"],
						'item_id'        => $_POST["item_id"],
						'status'         => $visible_rules[ $key ],
						'rule-label'     => $labels[ $key ],     //  value from the labels array
						'rule-extension' => $extensions[ $key ],   //  value from extension array
						'rule-note'      => isset( $allow_rule_note[ $value ] ) ? 1 : 0,
						'rule-max-size'  => $max_sizes[ $key ]   //  value from extension array
					);

					$rule_settings->add_item_from_array ( $rule_array );
				}

//				update_option( "ywau_multi_upload_settings", $rule_settings );
				$rule_settings->save ();
			}
		}

		/**
		 * Override base method.
		 * Call action on plugin init.
		 *
		 */
		public function on_plugin_init() {
			WC ()->mailer ();

			if ( isset( $_POST["ywau_action"] ) && ( ! ( defined ( 'DOING_AJAX' ) && DOING_AJAX ) ) ) { //  a file is uploaded
				do_action ( "yith_woocommerce_additional_action_" . $_POST["ywau_action"] );
			}
		}

		/**
		 * Override base method. Init plugin settings.
		 */
		public function init_plugin_settings() {
			$this->allow_on_cart_page     = ( "yes" === get_option ( 'ywau_allow_upload_on_cart', 'no' ) ) ? true : false;
			$this->allow_on_thankyou_page = ( "yes" === get_option ( 'ywau_allow_upload_on_thankyou', 'no' ) ) ? true : false;
			$this->allow_on_checkout_page = ( "yes" === get_option ( 'ywau_allow_upload_on_checkout', 'no' ) ) ? true : false;
			$this->split_products_on_cart = ( "yes" === get_option ( 'ywau_split_products_on_cart', 'no' ) ) ? true : false;
			$this->allow_on_myaccount     = ( "yes" === get_option ( 'ywau_allow_upload_on_myaccount', 'no' ) ) ? true : false;

			$this->allow_on_orders   = ( "yes" === get_option ( 'ywau_enable_order_upload', 'no' ) ) ? true : false;
			$this->allow_on_products = ( "yes" === get_option ( 'ywau_enable_product_upload', 'no' ) ) ? true : false;

			$this->upload_folder = get_option ( 'ywau_upload_folder', '' );

			if ( ! empty( $this->upload_folder ) ) {
				$full_dir_path = sprintf ( "%s/%s",
					YITH_YWAU_SAVE_DIR,
					$this->upload_folder );

				if ( ! file_exists ( $full_dir_path ) ) {
					wp_mkdir_p ( $full_dir_path );
				}
			}

			$this->order_folder_type = get_option ( 'ywau_folder_by_order_type', '' );

			$statuses = wc_get_order_statuses ();
			/* List all order status when the user is allowed to upload files or delete an uploaded file */
			foreach ( $statuses as $status => $status_name ) {

				$allow_upload_option_name = "ywau_allow_upload_" . $status;
				//  Check if current order status allow the file upload
				if ( "yes" == get_option ( $allow_upload_option_name, "no" ) ) {
					$this->allowed_status[] = $status;
				}

				//  Check if current order status allow the file deletion
				$allow_deletion_option_name = "ywau_allow_file_deletion_" . $status;
				if ( "yes" == get_option ( $allow_deletion_option_name, "no" ) ) {
					$this->allowed_deletion_status[] = $status;
				}
			}

			//  Get size of thumbnails
			$this->thumbnail_width   = get_option ( 'ywau_thumbnail_width', 100 );
			$this->thumbnail_height  = get_option ( 'ywau_thumbnail_height', 100 );
			$this->thumbnail_quality = get_option ( 'ywau_thumbnail_quality', 100 );
		}

		/**
		 * Override base method. Do nothing.
		 *
		 * @param int|WC_Order $order_id
		 */
		public function show_upload_section( $order_id ) {

		}

		/**
		 * Save all files submitted
		 */
		public function save_uploaded_files() {

			$order_id           = $_POST["ywau_order"];
			$context            = isset( $_POST["ywau_context"] ) ? $_POST["ywau_context"] : 'item';
			$scope              = isset( $_POST["ywau_scope"] ) ? $_POST["ywau_scope"] : 'item';
			$item_id            = isset( $_POST["ywau-order-item"] ) ? $_POST["ywau-order-item"] : 0;
			$submitted_rules_id = isset( $_POST["rule-id"] ) ? $_POST["rule-id"] : array();

			$is_cart = "cart" == $context;

			$_items = new YWAU_Uploaded_Items();

			$object_id = "order" == $scope ? $order_id : $item_id;
			$_items->load_items ( $is_cart, $object_id, $scope );
			/** @var $uploaded_item YWAU_Uploaded_Item */

			$uploaded_notification = array();

			$send_email = false;

			$save_data = false;
			for ( $index = 0; $index < count ( $submitted_rules_id ); $index ++ ) {
				$rule_id       = $submitted_rules_id[ $index ];
				$uploaded_item = $this->validate_posted_data ( $rule_id );

				if ( $uploaded_item->is_validated () ) {
					$save_data               = true;
					$uploaded_notification[] = $uploaded_item;

					$_items->set_item ( $uploaded_item, $item_id, $rule_id );
				}
			}

			foreach ( $uploaded_notification as $notification ){

			     if( $notification->upload_status != 'failed' ){
                     $send_email = true;
                 }

            }

			if ( $save_data ) {
				$_items->save_items ( $scope, $order_id );
				if ( $order_id && $uploaded_notification && $send_email ) {

					do_action ( 'ywau_email_order_file_uploaded_notification', $order_id, $uploaded_notification );

				}
			}
		}

		public function show_upload_button_my_order( $item_id, $item, $order ) {
			//  Do nothing
		}


		public function get_product_rules( $product_id, $variation_id ){

            $rules = new YWAU_Rules();

            $product_rules = $rules->get_product_rules_settings ( $product_id, $variation_id );

        }

		/**
		 * Retrieve the html code for the product rule that must be shown
		 *
		 * @param string $order_item
		 * @param int    $product_id
		 * @param int    $variation_id
		 * @param int    $order_id
		 * @param string $scope
		 *
		 * @return
		 */
		private function get_upload_rule_html_data( $order_item, $product_id, $variation_id = 0, $order_id = 0, $scope = 'item' ) {
			$upload_errors = 0;

			$is_cart = ! $order_id;

			$_items = new YWAU_Uploaded_Items();

			$object_id = 'order' == $scope ? $order_id : $order_item;
			$_items->load_items ( $is_cart, $object_id, $scope );

			$file_status_by_admin = '';

			$context = $order_id ? 'order' : 'cart';

			$html_to_show = '<div class="upload-rules">' .
			                '<form name="rules_form_' . $product_id . '" id="rules_form_' . $product_id . '" enctype="multipart/form-data" method="POST">' .
			                '<input type="hidden" name="ywau_context" value="' . $context . '">' .
			                '<input type="hidden" name="ywau_scope" value="' . $scope . '">' .
			                '<input type="hidden" name="ywau_order" value="' . $order_id . '" />' .
			                '<input type="hidden" name="ywau_product" value="' . $product_id . '" />' .
			                '<input type="hidden" name="ywau_variation" value="' . $variation_id . '" />' .
			                '<input type="hidden" name="ywau_action" value="upload_files" />' .
			                '<input type="hidden" name="ywau-order-item" value="' . $order_item . '" />';

			$rules = new YWAU_Rules();

			$product_rules = $rules->get_product_rules_settings ( $product_id, $variation_id );

			/** @var $product_rule YWAU_Rule */

			$show_submit = false;
			/**
			 * Count the number of rule without a file successfully uploaded
			 */
			$to_be_uploaded_count = 0;
			$to_be_uploaded_max   = 0;

			if ( count ( $product_rules ) ) {

				foreach ( $product_rules as $product_rule ) {



					$to_be_uploaded_max ++;
					$single_item_rule = $_items->get_item ( $order_item, $product_rule->rule_id );

					$insert_note   = false;
					$can_send_file = true;

					if ( ( null != $single_item_rule ) && $single_item_rule->is_upload_failed () ) {
						$upload_errors ++;
					}

					if ( ( null == $single_item_rule ) || ! $single_item_rule->is_upload_successful () ) {
						$to_be_uploaded_count ++;
					}

					$html_to_show .=
						'<div class="upload-rule rule-id-' . $product_rule->rule_id . '">' .
						'<span class="rule-label">' . $product_rule->label . '</span>';

					if ( ! empty( $product_rule->extension ) ) {
						$html_to_show .= '<span class="rule-allowed-extension">' . sprintf ( esc_html__( "Allowed extension: %s.", 'yith-woocommerce-additional-uploads' ), $product_rule->extension ) . '</span>';
					}

					$html_to_show .= '<span class="rule-max-size">' . sprintf ( esc_html__( "Max allowed size: %s MB.", 'yith-woocommerce-additional-uploads' ), $product_rule->file_max_size ) . '</span>' .
					                 '<div class="upload-items">' .
					                 '<input type="hidden" name="rule-id[]" value="' . $product_rule->rule_id . '" />';

					if ( null != $single_item_rule ) {

						//  check if file was accepted and can't be modified anymore
						if ( $single_item_rule->is_accepted () ) {
                            $file_status_by_admin = 'yith_ywau_is_accepted';
							$can_send_file = false;
							$html_to_show .=
								'<span class="upload-result success">' .
								esc_html__( "The file you sent has been verified and accepted", 'yith-woocommerce-additional-uploads' ) .
								'</span>';

						} elseif ( $single_item_rule->is_rejected () ) {
                            $file_status_by_admin = 'yith_ywau_is_rejected';
							$html_to_show .= '<span class="upload-result failed">';

							if ( ! empty( $single_item_rule->admin_response ) ) {
								$html_to_show .= $single_item_rule->admin_response;
							} else {
								$html_to_show .= esc_html__( "The file you sent has been rejected.", 'yith-woocommerce-additional-uploads' );
							}

							$html_to_show .= '</span>';



						} elseif ( $single_item_rule->is_upload_successful () ) {

							$can_send_file = false;
							$html_to_show .= '<span class="upload-result success">' . $single_item_rule->upload_message . '</span>';
							$html_to_show .= $this->get_uploaded_file_delete_url ( $order_id, $order_item, $product_rule->rule_id );

						} elseif ( $single_item_rule->is_upload_failed () ) {

							$html_to_show .= '<span class="upload-result failed">' . $single_item_rule->upload_message . '</span>';

						}
					}

					if ( $can_send_file ) {
						$show_submit = true;
						//  Let the user to upload a file
						$html_to_show .= '<input type="button" data-rule-id="' . $product_rule->rule_id . '" data-item-id="' . $order_item . '" class="select-file" value="' . esc_html__( "Select file", 'yith-woocommerce-additional-uploads' ) . '" />' .
						                 '<input type="file" name="uploadFile[' . $product_rule->rule_id . ']" id="uploadFile_' . $product_rule->rule_id . '_' . $order_item . '" class="file-chooser" />';

						//  Let the user to enter his own notes
						if ( $product_rule->enable_note ) {
							$insert_note = true;
							$html_to_show .=
								'<div class="rule-note">' .
								'<label class="notes-title">' . apply_filters( "yith_ywau_notes_frontend_label", esc_html__( "Notes:", 'yith-woocommerce-additional-uploads' ) ) . '</label>' .
								'<textarea name="rule-note[' . $product_rule->rule_id . ']" id="rule-note_' . $product_rule->rule_id . '_' . $order_item . '" class="rule-note" />' .
								'</textarea>' .
								'</div>';
						}
					}

					$html_to_show .= '</div></div>';
				}

				if ( $show_submit ) {
					$html_to_show .=
						'<div class="submit-files">' .
						'<input type="submit" name="submit-files" value="' . esc_html__( "Submit", 'yith-woocommerce-additional-uploads' ) . '">' .
						'</div>';
				}

				$html_to_show .=
					'</form>' .
					'</div>';

                $html_to_show_removed_files =
                    '<div class="upload-rules">' .
                        '<form name="rules_form_' . $product_id . '" id="rules_form_' . $product_id . '" enctype="multipart/form-data" method="POST">' .
                            '<div class="submit-files">' .
                                '<input style="display: none" type="submit" name="submit-files" value="' . esc_html__( "Submit", 'yith-woocommerce-additional-uploads' ) . '">' .
                            '</div>' .
                        '</form>' .
                    '</div>';

                $html_to_show_removed_files .=
                    '<span style="text-align: center; font-weight: bold">' . esc_html__( "File succesfully removed!", 'yith-woocommerce-additional-uploads' ) . '</span>';


				//  build an array with some variable as item key
				$res["upload_errors"]        = $upload_errors;
				$res["to_be_uploaded_count"] = $to_be_uploaded_count;
				$res["html_to_show"]         = $html_to_show;
				$res["html_to_show_removed_files"]         = $html_to_show_removed_files;
				$res["to_be_uploaded_max"]   = $to_be_uploaded_max;
				$res["file_status_by_admin"] = $file_status_by_admin;

				return $res;
			}

			return null;
		}

		/**
		 * Check if in the current screen the upload link could be used for orders
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		public function can_show_order_upload_link() {

			if ( ! apply_filters ( 'yith_ywau_can_show_order_upload_link', true ) ) {
				return false;
			}

			if ( ! $this->allow_on_orders ) {
				return false;
			}

			if ( is_cart () ) {

				return $this->allow_on_cart_page;
			}

			if ( is_checkout () ) {
				if ( is_wc_endpoint_url ( 'order-received' ) ) {

					return $this->allow_on_thankyou_page;
				} else {

					return $this->allow_on_checkout_page;
				}
			}

			if ( is_account_page () ) {

				return $this->allow_on_myaccount;
			}

			return false;
		}

		/**
		 * Check if in the current screen the upload link could be used
		 *
		 * @return bool
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		public function can_show_product_upload_link() {

			if ( ! apply_filters ( 'yith_ywau_can_show_product_upload_link', true ) ) {
				return false;
			}

			if ( ! $this->allow_on_products ) {
				return false;
			}

			if ( is_cart () ) {

				return $this->allow_on_cart_page;
			}

			if ( is_checkout () ) {
				if ( is_wc_endpoint_url ( 'order-received' ) ) {

					return $this->allow_on_thankyou_page;
				} else {

					return $this->allow_on_checkout_page;
				}
			}

			if ( is_account_page () ) {

				return $this->allow_on_myaccount;
			}

			return false;
		}

		/**
		 * Return a link that let the user to start showing the upload section
		 *
		 * @param int|string $order_item
		 * @param int        $product_id
		 * @param int        $variation_id
		 * @param int        $order_id
		 * @param string     $scope
		 *
		 * @return string|void
		 */
		private function get_upload_link( $order_item, $product_id, $variation_id = 0, $order_id = 0, $scope = 'item' ) {

			//  Check if upload is permitted based on current order status or cart page
			if ( $order_id ) {    //  trying to upload file to an order
				$order = wc_get_order ( $order_id );
				//  if current order has received files and all files are accepted, don't show the "upload" link anymore.
				if ( $this->order_has_all_file_accepted ( $order_id ) ) {
					return '';
				}

				if ( ! in_array ( "wc-" . $order->get_status (), $this->allowed_status ) ) {
					return '';
				}
			} else if ( 'order' == $scope && ! $this->can_show_order_upload_link () ) {  //  in cart page check if upload is enabled at this stage
				return '';

			} else if ( 'item' == $scope && ! $this->can_show_product_upload_link () ) {
				return '';
			}

			$res = $this->get_upload_rule_html_data ( $order_item, $product_id, $variation_id, $order_id, $scope );

			if ( $res != null ) {
				$upload_errors        = $res["upload_errors"];
				$to_be_uploaded_count = $res["to_be_uploaded_count"];
				$html_to_show         = $res["html_to_show"];
				$to_be_uploaded_max   = $res["to_be_uploaded_max"];
                $file_status_by_admin = $res["file_status_by_admin"];

				extract ( $res );

				//  Build a link to open a modal with all file uploaded
				$class_message = $upload_errors ? "upload-file upload-errors" : "upload-file et_smooth_scroll_disabled";
				$class_message = apply_filters('ywau_link_class_message',sprintf ( "%s %s_%s_%s", $class_message, 'rules_product', $product_id, $variation_id ),10,1);

				$link_message = $this->get_upload_link_text ( $to_be_uploaded_count, $to_be_uploaded_max );
				if ( ! $to_be_uploaded_count ) {
					//  all requested file were uploaded, we show a "modify" link with a CSS class
					$class_message = sprintf ( "%s %s %s", $class_message, 'modify',$file_status_by_admin );
				}

				//  Add a container for the html block to show
				$div_id = "rules_product_{$product_id}_{$variation_id}_{$order_item}";

				$html_to_show = '<div id="' . $div_id . '" style="display:none">' . str_replace ( "\n", "<br>", $html_to_show ) . '</div>';

				echo '<script type="text/javascript">
						jQuery("body").append("' . addslashes ( $html_to_show ) . '");
					</script>';

				$upload_link = '<br><a href="#' . $div_id . '" class="' . $class_message . ' ywau_pp_no_loaded" rel="prettyPhoto[ywau_upload_rules]">' . $link_message . '</a>';

				return $upload_link;
			}

			return '';
		}

		/**
		 * Retrieve a text to be used as label for the upload link
		 *
		 * @param int $to_be_uploaded_count number of unsetted rules asking for a file upload
		 * @param int $to_be_uploaded_max   number of rules asking for a file upload
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function get_upload_link_text( $to_be_uploaded_count, $to_be_uploaded_max ) {
			$link_message = '';

			if ( $to_be_uploaded_count ) {
				$link_message = apply_filters ( 'yith_ywau_upload_link_upload_file_text',
					sprintf ( _n ( 'Upload %s/%s file', 'Upload %s/%s files', $to_be_uploaded_count, 'yith-woocommerce-additional-uploads' ),
						$to_be_uploaded_count, $to_be_uploaded_max ), $to_be_uploaded_count, $to_be_uploaded_max );
			} else {
				$link_message = apply_filters ( 'yith_ywau_upload_link_modify_file_text',
					sprintf ( _n ( 'Modify %s file', 'Modify %s files', $to_be_uploaded_max, 'yith-woocommerce-additional-uploads' ), $to_be_uploaded_max ), $to_be_uploaded_count, $to_be_uploaded_max );
			}

			return $link_message;
		}

		/**
		 * Show upload file link and let the user upload file for a specific item on cart
		 *
		 * @param string $title
		 * @param array  $cart_item
		 * @param string $cart_item_key
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public function woocommerce_cart_item_name( $title, $cart_item, $cart_item_key ) {

			if ( $this->can_show_product_upload_link () ) {

				$product_id   = $cart_item["product_id"];
				$variation_id = $cart_item["variation_id"];

				$link = $this->get_upload_link ( $cart_item_key, $product_id, $variation_id );

				if ( '' != $link ) {
					return $title . $link;
				}
			}

			return $title;
		}

		/**
		 * Verify the data sent via $_POST and build a message as a result to the caller
		 *
		 * @param int $rule_id the rule id to be checked
		 *
		 * @return array|null
		 */
		private function validate_posted_data( $rule_id ) {
			$postfile = $_FILES['uploadFile'];

			$order_id     = isset( $_POST["ywau_order"] ) ? $_POST["ywau_order"] : 0;
			$product_id   = isset( $_POST["ywau_product"] ) ? $_POST["ywau_product"] : 0;
			$item_id      = isset( $_POST["ywau-order-item"] ) ? $_POST["ywau-order-item"] : 0;
			$variation_id = isset( $_POST["ywau_variation"] ) ? $_POST["ywau_variation"] : 0;
			$rule_notes   = isset( $_POST["rule-note"] ) ? $_POST["rule-note"] : array();

			$_rules = new YWAU_Rules();

			$current_rule = $_rules->get_rule ( $product_id, $rule_id );

			//  Initialize response array
			$defaults = array(
				'item_id'      => $item_id,
				'variation_id' => $variation_id,
				'rule_id'      => $rule_id,
				'order_id'     => $order_id,
				'product_id'   => $product_id,
				'note'         => isset( $rule_notes[ $rule_id ] ) ? $rule_notes[ $rule_id ] : '',
			);

			$uploaded_item_result = new YWAU_Uploaded_Item( $defaults );

			//  Check if a file was uploaded
			if ( empty( $postfile['name'][ $rule_id ] ) ) {

				return $uploaded_item_result;
			}

			if ( $postfile['error'][ $rule_id ] ) {

				$uploaded_item_result->set_upload_failed (
					sprintf ( esc_html__( "The following error occurred during the upload of %s: %s", 'yith-woocommerce-additional-uploads' ),
						$postfile['name'][ $rule_id ],
						$postfile['error'][ $rule_id ] )
				);

				return $uploaded_item_result;
			}

			//now is the time to modify the future file name and validate the file
			$file_name = sanitize_file_name ( strtolower ( $postfile['name'][ $rule_id ] ) ); //rename file

			//  Check if file extension is allowed
			$allowed_ext_array = explode ( ',', str_replace ( " ", "", $current_rule->extension ) );
			$file_ext          = pathinfo ( $file_name, PATHINFO_EXTENSION );
			if ( ! empty( $current_rule->extension ) && ( count ( $allowed_ext_array ) > 0 ) && ( ! in_array ( $file_ext, $allowed_ext_array ) ) ) {
				$uploaded_item_result->set_upload_failed (
					sprintf ( esc_html__( "The format of the %s file is not accepted.", 'yith-woocommerce-additional-uploads' ),
						$postfile['name'][ $rule_id ] ) );

				return $uploaded_item_result;
			}

			$max_size_byte = 1048576 * $current_rule->file_max_size; //  max size in bytes

			if ( $current_rule->file_max_size && ( $postfile['size'][ $rule_id ] > $max_size_byte ) ) {

				//  File size not allowed
				$uploaded_item_result->set_upload_failed (
					sprintf ( esc_html__( "The %s file has not been accepted, the maximum dimension is %s MB.", 'yith-woocommerce-additional-uploads' ), $postfile['name'][ $rule_id ],
						$current_rule->file_max_size ) );

				return $uploaded_item_result;
			}

			$file_name = sanitize_file_name ( strtolower ( $postfile['name'][ $rule_id ] ) );

			$relative_folder_path = $this->get_relative_item_upload_dir ( $order_id, $item_id, $rule_id );
			$relative_filepath    = $this->get_relative_item_upload_path ( $order_id, $item_id, $rule_id, $file_name );

			if ( $this->save_file ( $postfile['tmp_name'][ $rule_id ], $relative_folder_path, $file_name, true ) ) {

				$uploaded_item_result->set_upload_successful (
					sprintf ( esc_html__( "The <b>%s</b> file has been included in the current order. Your order is now being processed.", 'yith-woocommerce-additional-uploads' ), $postfile['name'][ $rule_id ] ), $relative_filepath );

				return $uploaded_item_result;
			}

			return null;
		}

		/**
		 * Save uploaded file
		 *
		 * @param $uploaded_file
		 * @param $order_id
		 * @param $filename
		 *
		 * @return bool
		 */
		protected function save_file( $uploaded_file, $folder, $filename, $create_thumb = false ) {

			//  Put the files on a folder reserved to current order
			$order_dir = sprintf ( "%s/%s",
				YITH_YWAU_SAVE_DIR,
				$folder );

			if ( ! file_exists ( $order_dir ) ) {
				wp_mkdir_p ( $order_dir );
			}

			$upload_file_path = sprintf ( "%s/%s", $order_dir, $filename );
			$thumb_name       = pathinfo ( $filename, PATHINFO_FILENAME ) . "_thumb";
			$thumb_ext        = pathinfo ( $filename, PATHINFO_EXTENSION );
			$thumb_filename   = sprintf ( "%s.%s", $thumb_name, $thumb_ext );
			$thumb_file_path  = sprintf ( "%s/%s", $order_dir, $thumb_filename );

			$result = move_uploaded_file ( $uploaded_file, $upload_file_path );

			$mime_type = wp_check_filetype ( $upload_file_path );
			$mime_type = $mime_type['type'];

			try {
				if ( $create_thumb ) {

					$crop_width  = $this->thumbnail_width;
					$crop_height = $this->thumbnail_height;

					$canvas = imagecreatetruecolor ( $crop_width, $crop_height );

					try{
						switch ( $mime_type ) {
							case 'image/jpeg':
								if( exif_imagetype($upload_file_path) == IMAGETYPE_JPEG ){
									$current_image = imagecreatefromjpeg ( $upload_file_path );
								}else{
									$current_image = null;
									throw new Exception("The file is corrupted");
								}
								break;
							case 'image/png':
								if( exif_imagetype($upload_file_path) == IMAGETYPE_PNG ){
									$current_image = imagecreatefrompng ( $upload_file_path );
								}else{
									$current_image = null;
									throw new Exception("The file is corrupted");
								}
								break;
							case 'image/gif':
								if( exif_imagetype($upload_file_path) == IMAGETYPE_GIF){
									$current_image = imagecreatefromgif ( $upload_file_path );
								}else{
									$current_image = null;
									throw new Exception("The file is corrupted");
								}
								break;
							default:
								$current_image = null;
								break;
						}

						if ( $current_image == null ) {
							return $result;
						}
					}catch(Exception $e) {
						echo $e->getMessage();
					}
					if( $current_image ){
						list( $width, $height ) = getimagesize ( $upload_file_path );

						$dest_x = 0;
						$dest_y = 0;
						$dest_w = $crop_width;  //optimalWidth
						$dest_h = $crop_height;//optimalHeight
						$src_x  = 0;
						$src_y  = 0;
						$src_w  = $width;
						$src_h  = $height;

						imagecopyresized ( $canvas, $current_image, $dest_x, $dest_y, $src_x, $src_y, $dest_w, $dest_h, $src_w, $src_h );

						switch ( $mime_type ) {
							case 'image/jpeg':
								imagejpeg ( $canvas, $thumb_file_path, $this->thumbnail_quality );
							case 'image/png':
								imagepng ( $canvas, $thumb_file_path );
							case 'image/gif':
								imagegif ( $canvas, $thumb_file_path );
						}
					}



					//imagejpeg($canvas, $thumb_file_path, $this->thumbail_quality);
				}
			} catch ( Exception $e ) {
				error_log ( 'Caught exception: ', $e->getMessage (), "\n" );
			}

			return $result;
		}

		/**
		 * Retrieve the relative path of the file that should be stored inside an order specific folder
		 *
		 * @param int    $order_id the order id
		 * @param int    $item_id  the order item id
		 * @param int    $rule_id  the upload rule id
		 * @param string $filename the filename
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		protected function get_relative_item_upload_path( $order_id, $item_id, $rule_id, $filename ) {

			$order_dir = $this->get_relative_item_upload_dir ( $order_id, $item_id, $rule_id );

			$relative_path = sprintf ( "%s/%s", $order_dir, $filename );

			return $relative_path;
		}

		/**
		 * Retrieve the relative path where the files uploaded should be stored
		 *
		 * @param int $order_id the order id
		 * @param int $item_id  the orde item id
		 * @param int $rule_id  the upload rule
		 *
		 * @return string the path where the files should be saved
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		protected function get_relative_item_upload_dir( $order_id, $item_id, $rule_id ) {

			$order_dir = apply_filters ( 'ywau_store_order_folder', $order_id );

			$relative_path = sprintf ( "%s/%s/%s", $order_dir, $item_id, $rule_id );

			return $relative_path;
		}

		/** Override base method.
		 * Upload the customer file and link it to the current order
		 */
		public function order_file_uploaded() {
			//  do nothing
		}


		/**
		 * Show upload file button on my-orders page
		 *
		 * @param array    $actions
		 * @param WC_Order $order
		 *
		 * @return array
		 */
		public function print_upload_file_button( $actions, $order ) {

			/**
			 * Do nothing if the order status do not permit uploads
			 */
			if ( $this->can_upload_file_on_order ( $order ) ) {

				/**
				 * Check if the upload is permitted at order level or at product level
				 */
				$upload_for_order    = $this->can_show_order_upload_link ();
				$upload_for_products = false;

				if ( ! $upload_for_order ) {

					$upload_for_products = $this->can_show_product_upload_link () && $this->order_item_upload_enabled ( yit_get_prop ( $order, 'id' ) );
				}

				if ( $upload_for_order || $upload_for_products ) {
					$actions['upload-file'] = array(
						'url'  => $order->get_view_order_url (),
						'name' => esc_html__( 'Upload', 'yith-woocommerce-additional-uploads' ),

					);
				}
			}

			return $actions;
		}

		public function show_upload_section_on_thankyoupage( $text, $order ) {
			//  Do nothing
		}

		/**
		 * Ajax callback for deleting file uploaded
		 */
		public function delete_uploaded_file_callback() {
			try {
//         todo check for global uploaded items
				$product_id   = intval ( $_POST['product_id'] );
				$variation_id = intval ( $_POST['variation_id'] );

				$order_id = intval ( $_POST["order_id"] );
				$item_id  = $_POST["item_id"];
				$rule_id  = intval ( $_POST["rule_id"] );
				$scope    = isset( $_POST["scope"] ) ? $_POST["scope"] : 'item';

				$is_cart   = ( $order_id > 0 ) ? false : true;
				$object_id = 'order' == $scope ? $order_id : $item_id;

				//  retrieve data for current object
				$_items = new YWAU_Uploaded_Items();
				$_items->load_items ( $is_cart, $object_id, $scope );

				$_item = $_items->get_item ( $item_id, $rule_id );

				$_items->delete_item ( $_item );

				$_items->save_items ( $scope, $object_id );

				do_action ( 'ywau_email_order_file_deleted_notification', $order_id );

				$result = $this->get_upload_rule_html_data ( $item_id, $product_id, $variation_id, $order_id );

				wp_send_json ( array(
					"code"   => 1,
					"items"  => $result,
					"button" => $this->get_upload_link_text ( $result["to_be_uploaded_count"], $result["to_be_uploaded_max"] ),
				) );
			} catch ( Exception $ex ) {
				wp_send_json ( array(
					"code" => - 1,
				) );
			}
		}

		/**
		 * Ajax callback for deleting file uploaded
		 */
		public function sent_message_callback() {

			$message = $_POST['message'];
			$post_id = intval ( $_POST["post_id"] );

			if ( empty( $message ) ) {
				return;
			}

			// send a message to the user
			do_action ( 'ywau_email_order_admin_message_notification', $post_id, $message );

			wp_send_json ( array(
				"code"  => 1,
				"items" => 1,
			) );
		}

		/**
		 * Check if an order item have file waiting for being processed
		 */
		public function order_item_has_file_to_be_accepted( $order_item_id, $order_item_content, $scope = 'item' ) {

			$uploaded_items = new YWAU_Uploaded_Items();
			$rules          = new YWAU_Rules();

			$product_id   = $order_item_content["product_id"];
			$variation_id = $order_item_content["variation_id"];

			$order_item_files = $uploaded_items->load_items ( false, $order_item_id, $scope );
			foreach ( $order_item_files as $single_file ) {
				//  Check if the rule still exists
				if ( $rules->get_rule ( $product_id, $single_file->rule_id ) ) {

					if ( $single_file->is_waiting_response () ) {

						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Check if an order has file attached to product, waiting to be accepted.
		 *
		 * @param int $order_id the order id to check
		 *
		 * @return bool
		 */
		public function order_has_file_to_be_accepted( $order_id ) {

			$order_items = wc_get_order ( $order_id )->get_items ( "line_item" );

			/** @var $single_file YWAU_Uploaded_Item */
			if ( $order_items ) {
				foreach ( $order_items as $order_item_id => $order_item_content ) {
					if ( $this->order_item_has_file_to_be_accepted ( $order_item_id, $order_item_content ) ) {

						return true;
					}
				}
			}

			//  Check if there are uploads at the order level
			$order = wc_get_order ( $order_id );
			$items = yit_get_prop ( $order, YWAU_METAKEY_ORDER_ITEM_FILES );

			if ( $items ) {
				foreach ( $items as $item_key => $item_array ) {
					foreach ( $item_array as $item_id => $item_content ) {
						if ( $this->order_item_has_file_to_be_accepted ( $order_id, $item_content, 'order' ) ) {

							return true;
						}
					}
				}
			}


			return false;
		}

		/**
		 * Check if an order has file attached to product, waiting to be accepted.
		 *
		 * @param int $order_id the order id to check
		 */
		public function order_has_all_file_accepted( $order_id ) {
			$order = wc_get_order ( $order_id );

			$_items = new YWAU_Uploaded_Items();
			$rules  = new YWAU_Rules();

			$order_items = $order->get_items ();

			/** @var YWAU_Uploaded_Item $single_item */
			/** @var  YWAU_Rule $product_rule */
			foreach ( $order_items as $item_id => $item ) {
				$_product = is_object ( $item ) ? $item->get_product () : $order->get_product_from_item ( $item );

				$_items->load_items ( false, $item_id );
				$parent_product_id = yit_get_base_product_id ( $_product );

				$variation_id = 0;
				if ( $_product instanceof WC_Data ) {
					if ( $_product->is_type ( 'variation' ) ) {
						$variation_id = yit_get_product_id ( $_product );
					}
				} else {
					if ( $_product instanceof WC_Product_Variation ) {
						$variation_id = $_product->variation_id;
					}
				}

				$product_rules = $rules->get_product_rules_settings ( $parent_product_id, $variation_id );

				/**
				 * If there aren't product rules, do not check the single item
				 */
				if ( ! $product_rules ) {
					return false;
				}

				foreach ( $product_rules as $product_rule ) {

					$single_item = $_items->get_item ( $item_id, $product_rule->rule_id );

					if ( ( $single_item == null ) || ( ! $single_item->is_accepted () ) ) {
						return false;
					}
				}
			}

			return true;
		}

		/**
		 * Check if an order has file attached to product, waiting to be accepted.
		 *
		 * @param int $order_id the order id to check
		 *
		 * @return bool
		 */
		public function order_item_upload_enabled( $order_id ) {
			$order_items = wc_get_order ( $order_id )->get_items ( "line_item" );

			/** @var $single_file YWAU_Uploaded_Item */

			foreach ( $order_items as $order_item_id => $order_item_content ) {

				$_items = new YWAU_Uploaded_Items();
				$_items->load_items ( false, $order_item_id );

				$product_id   = $order_item_content["product_id"];
				$variation_id = $order_item_content["variation_id"];

				$rules         = new YWAU_Rules();
				$product_rules = $rules->get_product_rules_settings ( $product_id, $variation_id );

				foreach ( $product_rules as $product_rule ) {
					$single_item_rule = $_items->get_item ( $order_item_id, $product_rule->rule_id );

					if ( $single_item_rule == null ) {
						continue;
					}

					if ( $single_item_rule->is_accepted () ) {
						//  When the file is accepted, cannot be modified
						continue;
					}

					//  on first element that let the customer to upload a file, return true
					return true;
				}

			}

			return false;
		}

		/**
		 * Show icon on backend page "orders" for order where there is file uploaded and waiting to be confirmed.
		 *
		 * @param string $column current column being shown
		 */
		public function show_icon_for_file_uploaded( $column ) {
			//  If column is not of type order_status, skip it
			if ( 'order_status' !== $column ) {
				return;
			}

			/** @var WC_Order $the_order */
			global $the_order;
			if ( $this->order_has_file_to_be_accepted ( yit_get_prop ( $the_order, 'id' ) ) ) {
				echo '<img class="order-waiting-response" src="' . YITH_YWAU_ASSETS_IMAGES_URL . 'waiting.png" title="' . esc_html__( "This order contains file waiting for approval.", 'yith-woocommerce-additional-uploads' ) . '" />';
			} elseif ( $this->order_has_all_file_accepted ( yit_get_prop ( $the_order, 'id' ) ) ) {
				echo '<img class="order-waiting-response" src="' . YITH_YWAU_ASSETS_IMAGES_URL . 'order-accepted.png" title="' . esc_html__( "All requested files have been received and accepted.", 'yith-woocommerce-additional-uploads' ) . '" />';
			}
		}

		/**
		 * @param $loop
		 * @param $variation_data
		 * @param $variation
		 */
		public function woocommerce_product_after_variable_attributes( $loop, $variation_data, $variation ) {
			/* @var WP_Post $variation */
			$product_id = $variation->post_parent;

			$rules         = new YWAU_Rules();
			$product_rules = $rules->get_product_rules_settings ( $product_id );

			if ( ! $product_rules ) {
				return;
			}

            if ( get_option( 'ywau_enable_product_variations_upload' ) == 'yes' ){
                echo '<div class="variation_rules"><p>
                <label class="choose-rules">' . esc_html__( "Upload rules to hide", 'yith-woocommerce-additional-uploads' ) . '</label>';
            }
            else{
                echo '<div class="variation_rules"><p>
                <label class="choose-rules">' . esc_html__( "Upload rules to display", 'yith-woocommerce-additional-uploads' ) . '</label>';
            }



			/* @var YWAU_Rule $product_rule */
			foreach ( $product_rules as $product_rule ) {

				$metas = yit_get_prop ( $variation, '_variation_rules' );

				woocommerce_wp_checkbox (
					array(
						'id'            => '_variation_rules[' . $loop . '][' . $product_rule->rule_id . ']',
						'wrapper_class' => 'rule_active',
						'label'         => $product_rule->label,
						'value'         => is_array ( $metas ) && array_key_exists ( $product_rule->rule_id, $metas ) ? 'yes' : 'no',
					)
				);
			}

			echo '</p></div>';
		}

		public function woocommerce_product_after_variable_attributes_js() {
			/* @var WP_Post $variation */
			$product_id = $variation->post_parent;

			$rules         = new YWAU_Rules();
			$product_rules = $rules->get_product_rules_settings ( $product_id );

			if ( ! $product_rules ) {
				return;
			}

			echo '<div class="variation_rules"><p>
            <label class="choose-rules">' . esc_html__( "Upload rules to display", 'yith-woocommerce-additional-uploads' ) . '</label>';

			/* @var YWAU_Rule $product_rule */
			foreach ( $product_rules as $product_rule ) {
				woocommerce_wp_checkbox (
					array(
						'id'            => '_variation_rules[ + loop + ][' . $product_rule->rule_id . ']',
						'wrapper_class' => 'rule_active',
						'label'         => $product_rule->label,
					)
				);
			}

			echo '</p></div>';
		}

		public function save_variable_fields( $variation_id, $_i ) {

            $product       = wc_get_product ( $variation_id );

            $_active_rules = isset( $_POST['_variation_rules'][ $_i ] ) ? $_POST['_variation_rules'][ $_i ] : array();

            yit_save_prop ( $product, '_variation_rules', $_active_rules );

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

            $links = yith_add_action_links( $links, $this->_panel_page, true );
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
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_YWAU_INIT' ) {

            $new_row_meta_args = parent::plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file );

            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ){
                $new_row_meta_args[ 'is_premium' ] = true;
            }

            return $new_row_meta_args;
        }


        /**
         * Show link email for upload on order
         * @param $order
         * @param $uploaded_elements
         */
        public function show_uploads_on_order( $order, $uploaded_elements ){

            $enable_on_products = get_option('ywau_enable_product_upload');
            $enable_on_variations = get_option('ywau_enable_product_variations_upload');

            if( $enable_on_products != 'yes' && $enable_on_variations != 'yes' && is_array($uploaded_elements) && count($uploaded_elements) > 0 ){

                $html = '<h4>'. esc_html__('Uploads','yith-woocommerce-additional-uploads') .'</h4><ul>';

                $n=1;

                foreach ( $uploaded_elements as $upload ){

                    if( $upload->upload_status == 'failed' )
                        continue;

                    $html .= '<li>
                                <a href="'. YITH_YWAU_SAVE_URL . $upload->upload_filepath .'">File' . $n . '</a>';
                              if( $upload->note != '' ):
                                  $html .= '<p><b>'. esc_html__('Note','yith-woocommerce-additional-uploads') .'</b>: '. $upload->note .'</p>';
                              endif; ;
                    $html .=   '</li>';

                    $n++;

                }

                $html .= '</ul>';

                echo $html;

            }

        }

	}
}
