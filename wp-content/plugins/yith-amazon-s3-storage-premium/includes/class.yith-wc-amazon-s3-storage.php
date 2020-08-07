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
 * @class      YITH_WC_AMAZON_S3_STORAGE_Class
 * @package    Yithemes
 * @since      Version 2.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_WC_Amazon_S3_Storage_Main_Class' ) ) {
	/**
	 * Class YITH_WC_Quick_Order_Main_Class
	 *
	 * @author Daniel Sánchez Sáez <dssaez@gmail.com>
	 */
	class YITH_WC_Amazon_S3_Storage_Main_Class {
		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0
		 */
		public $version = YITH_WC_AMAZON_S3_STORAGE_VERSION;

		/**
		 * Main Instance
		 *
		 * @var YITH_WC_AMAZON_S3_STORAGE_Class
		 * @since  1.0
		 * @access protected
		 */
		protected static $_instance = null;

		/**
		 * Main Admin Instance
		 *
		 * @var YITH_AMAZON_S3_STORAGE_Admin
		 * @since 1.0
		 */
		public $admin = null;

		/**
		 * Main Frontpage Instance
		 *
		 * @var YITH_AMAZON_S3_STORAGE_Frontend
		 * @since 1.0
		 */
		public $frontend = null;

		/**
		 * Main common Instance
		 *
		 * @var YITH_AMAZON_S3_STORAGE_Common
		 * @since 1.0
		 */
		public $common = null;

		/**
		 * check if the plugin is activated or not
		 *
		 * @var bool
		 * @since 1.3.6
		 */
		public $is_plugin_enabled = true;


		/**
		 * Construct
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.0
		 */

		public $ajax = null;

		public function __construct() {

			// Load required Amazon S3 with PHP: SDK through composer
			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/vendor/autoload.php' );

			/* === Require Main Files === */
			$require = apply_filters( 'YITH_WC_' . YITH_AS3S_CONSTANT_NAME . '_require_class',
				array(
					'common'   => array(
						'includes/functions.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '.php',
						'includes/class.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '-aws-s3-client.php',
					),
					'admin'    => array(
						'includes/class.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '-admin.php',
					),
					'frontend' => array(
						'includes/class.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '-frontend.php',
					),
				)
			);

			$this->_require( $require );

			/* === Load Plugin Framework === */
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_filter( 'body_class', array( $this, 'body_class' ) );

			/* == Plugins Init === */
			add_action( 'init', array( $this, 'init' ) );

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

            /* ====== RUNING THE SHORTCODE ====== */

            require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-' . YITH_AS3S_FILES_INCLUDE_NAME . '-shortcodes.php' );

            Amazon_S3_Storage_Shortcodes::init();

            add_action( 'woocommerce_download_product', array( $this, 'woocommerce_download_product_yith_as3_call_back' ), 10, 6 );

            add_action( 'woocommerce_email_downloads_column_download-file', array( $this, 'woocommerce_email_downloads_column_download_file_yith_as3_call_back' ), 10, 1 );

        }

        function woocommerce_email_downloads_column_download_file_yith_as3_call_back( $download ) {

            ?>
                <a href="<?php echo esc_url( $download['download_url'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download['download_name'] ); ?></a>
            <?php

            $order = new WC_Order( $download['order_id'] );
            $text_email_link = ( get_option( 'YITH_WC_amazon_s3_storage_textarea_email_link' ) ? get_option( 'YITH_WC_amazon_s3_storage_textarea_email_link' ) : '' );
            if ( ( $order->get_user_id() != 0 ) and ( get_option( 'YITH_WC_amazon_s3_storage_order_link_checkbox' ) != 'no' ) ){
                ?>
                    <br><br>
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" target='_blank' class="woocommerce-MyAccount-downloads-file button alt"><?php echo $text_email_link; ?>
                <?php
            }

        }

        /**
         * woocommerce_download_product_yith_as3_call_back
         *
         * Function download a product from s3 when you click on download button.
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0
         */

        function woocommerce_download_product_yith_as3_call_back( $user_email, $order_key, $product_id, $user_id, $download_id, $order_id ) {

            $order = wc_get_order( $order_id );


            foreach ( $order->get_items() as $item ) {
                if ( is_object( $item ) && $item->is_type( 'line_item' ) && ( $item_downloads = $item->get_item_downloads() ) ) {

                    if ( $product = $item->get_product() ) {
                        foreach ( $item_downloads as $file ) {

                            if ( $file['id'] == $download_id ){

                                if ( strpos( $file['file'], 'yith_wc_amazon_s3_storage' ) !== false) {


                                    $data_store_as3   = WC_Data_Store::load( 'customer-download' );

                                    $email = ( isset( $_GET['email'] ) ? $_GET['email'] : '' );
                                    $download_ids = $data_store_as3->get_downloads( array(
                                        'user_email'  => sanitize_email( str_replace( ' ', '+', $email ) ),
                                        'order_key'   => wc_clean( $_GET['order'] ),
                                        'product_id'  => $product_id,
                                        'download_id' => wc_clean( preg_replace( '/\s+/', ' ', $_GET['key'] ) ),
                                        'orderby'     => 'downloads_remaining',
                                        'order'       => 'DESC',
                                        'limit'       => 1,
                                        'return'      => 'ids',
                                    ) );

                                    $download = new WC_Customer_Download( current( $download_ids ) );

                                    $count     = $download->get_download_count();
                                    $remaining = $download->get_downloads_remaining();
                                    $download->set_download_count( $count + 1 );

                                    if ( '' !== $remaining ) {
                                        $download->set_downloads_remaining( $remaining - 1 );
                                    }
                                    $download->save();

                                    $content = do_shortcode( $file['file'] );

                                    if ( $content != 'by_php' )
                                        echo $content;

                                    exit();

                                } else {

                                    if( apply_filters('yith_wcamz_download_amazon_s3_file_private',false ) ) {
                                        //Download a product served by S3 whem the url is private
                                        $Bucket_Selected = (get_option('YITH_WC_amazon_s3_storage_connection_bucket_selected_select') ? get_option('YITH_WC_amazon_s3_storage_connection_bucket_selected_select') : '');

                                        $Array_Bucket_Selected = explode("_yith_wc_as3s_separator_", $Bucket_Selected);

                                        $Bucket = $Array_Bucket_Selected[0];
                                        $Region = $Array_Bucket_Selected[1];

                                        $Access_Key = (get_option('YITH_WC_amazon_s3_storage_connection_access_key_text') ? get_option('YITH_WC_amazon_s3_storage_connection_access_key_text') : null);

                                        $Secret_Access_Key = (get_option('YITH_WC_amazon_s3_storage_connection_secret_access_key_text') ? get_option('YITH_WC_amazon_s3_storage_connection_secret_access_key_text') : null);

                                        require_once(constant('YITH_WC_AMAZON_S3_STORAGE_PATH') . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php');

                                        $aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client($Access_Key, $Secret_Access_Key);

                                        $cliente = $aws_s3_client->Init_S3_Client($Region, 'latest', $Access_Key, $Secret_Access_Key);
                                        $cliente->registerStreamWrapper();

                                        $end = basename($file['file']);

                                        if (is_file('s3://' . $Bucket . '/' . $end)) {

                                            $result = $cliente->getObject([
                                                'Bucket' => $Bucket,
                                                'Key' => $end
                                            ]);

                                            header("Content-Type: {$result['ContentType']}");
                                            echo $result['Body'];
                                        }

                                        exit();

                                    }
                                }

                                break;

                            }

                        }
                    }

                }
            }

        }

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/licence/lib/yit-licence.php';
				require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}

			YIT_Plugin_Licence()->register( constant( 'YITH_WC_AMAZON_S3_STORAGE_INIT' ), constant( 'YITH_WC_AMAZON_S3_STORAGE_SECRETKEY' ), constant( 'YITH_WC_AMAZON_S3_STORAGE_SLUG' ) );

		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {

			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'plugin-fw/lib/yit-upgrade.php' );
			}

			YIT_Upgrade()->register( constant( 'YITH_WC_AMAZON_S3_STORAGE_SLUG' ), constant( 'YITH_WC_AMAZON_S3_STORAGE_INIT' ) );
		}

		/**
		 * Main plugin Instance
		 *
		 * @return YITH_Amazon_S3_Storage Main instance
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Add the main classes file
		 *
		 * Include the admin and frontend classes
		 *
		 * @param $main_classes array The require classes file path
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.0
		 *
		 * @return void
		 * @access protected
		 */
		protected function _require( $main_classes ) {
			foreach ( $main_classes as $section => $classes ) {
				foreach ( $classes as $class ) {

					if ( file_exists( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . $class ) )
						switch ( $section ) {

							case 'common':
								require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . $class );
								break;

                            case 'frontend':
                                // We check is ajax is being runing by admin and see if the variable session is set to frontend
                                if ( is_admin() && ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
                                    require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . $class );
                                } elseif ( ! is_admin() ) {
                                    require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . $class );
                                }

                                break;

                            case 'admin':
                                // We check is ajax is being running by admin and see if the variable session is set to backend
                                if ( is_admin() ) {
                                    require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . $class );
                                }

                                break;

						}

				}
			}
		}

		/**
		 * Load plugin framework
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.0
		 * @return void
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );
				}
			}
		}

		/**
		 * Class Initializzation
		 *
		 * Instance the admin or frontend classes
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.0
		 * @return void
		 * @access protected
		 */
		public function init() {

            if ( is_admin() ) {

                $this->admin = new YITH_WC_Amazon_S3_Storage_Admin();

                if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

                    $this->frontend = new YITH_WC_Amazon_S3_Storage_Frontend();

                }

            } else {

                $this->frontend = new YITH_WC_Amazon_S3_Storage_Frontend();

            }

		}

		/**
		 * Add a body class(es)
		 *
		 * @param $classes The classes array
		 *
		 * @author Daniel Sanchez Saez <dssaez@gmail.com>
		 * @since  1.3.0
		 * @return array
		 */
		public function body_class( $classes ) {
			$classes[] = 'yith-wc-Amazon-S3-Storage';
			$classes[] = 'yes' == get_option( 'woocommerce_enable_checkout_login_reminder', 'yes' ) ? 'show_checkout_login_reminder' : 'hide_checkout_login_reminder';

			return $classes;
		}
	}
}