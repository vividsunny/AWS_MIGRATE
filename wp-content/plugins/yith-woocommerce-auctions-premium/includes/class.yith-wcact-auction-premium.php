<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCACT_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_AUCTIONS
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_Auctions_Premium' ) ) {
	/**
	 * Class YITH_AUCTIONS
	 *
	 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
	 */
	class YITH_Auctions_Premium extends YITH_Auctions {
        /**
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function __construct(){
			add_filter( 'yith_wcact_require_class', array( $this, 'load_premium_classes' ) );

			add_filter('woocommerce_product_class',array( $this,'return_premium_product_class' ));

			parent::__construct();
		}
		

		/**
		 * Main Init classes
		 *
		 * @return YITH_Auctions Main instance
		 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
		 */
		public function init_classes(){
			$this->bids = YITH_WCACT_Bids::get_instance();
			$this->ajax = YITH_WCACT_Auction_Ajax_Premium::get_instance();
			$this->compatibility = YITH_WCACT_Compatibility_Premium::get_instance();
			$this->shortcode = YITH_WCACT_Auction_Shortcodes::init();
			$this->endpoint = YITH_Auctions_My_Auctions::get_instance();

		}

		/**
		 * Return premium classes
		 *
		 * @return  Premium classes
		 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
		 */

		public function return_premium_product_class($classname){

			if ( 'WC_Product_Auction' === $classname ) {
				return $classname.'_Premium';
			}
			return $classname;
		}
		/**
		 * Add premium files to Require array
		 *
		 * @param $require The require files array
		 *
		 * @return Array
		 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
		 * @since 1.0
		 *
		 */
		public function load_premium_classes( $require ){
			$frontend = array(
				'includes/class.yith-wcact-auction-frontend-premium.php',
			);
			$common = array(
				'includes/class.yith-wcact-auction-product-premium.php',
				'includes/class.yith-wcact-auction-ajax-premium.php',
				'includes/class.yith-wcact-auction-widget.php',
				'includes/class.yith-wcact-auction-cron.php',
				'includes/class.yith-wcact-auction-notify.php',
				'includes/class.yith-wcact-auction-shortcodes.php',
				'includes/compatibility/class.yith-wcact-compatibility-premium.php',
                'includes/data-stores/class.yith-wcact-product-auction-data-store-cpt.php',
                //'includes/legacy/abstract.yith-wcact-legacy-auction-product.php',
			);
			$require['admin'][]   = 'includes/class.yith-wcact-auction-admin-premium.php';
			$require['frontend']  = array_merge($require['frontend'],$frontend);
			$require['common']    = array_merge($require['common'],$common);

			return $require;
		}


        /**
		 * Function init()
		 *
		 * Instance the admin or frontend classes
		 *
		 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
		 * @since  1.0
		 * @return void
		 * @access protected
		 */
		public function init() {
            if ( is_admin() ) {
				$this->admin =  YITH_Auction_Admin_Premium::get_instance();
			}

			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				$this->frontend = YITH_Auction_Frontend_Premium::get_instance();
			}
		}
		
    }
}