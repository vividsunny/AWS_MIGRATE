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
 * @class      YITH_WCACT_Multivendor_Compatibility
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'YITH_WCACT_Multivendor_Compatibility' ) ) {

	class YITH_WCACT_Multivendor_Compatibility {

		public function __construct() {
			add_filter( 'woocommerce_email_classes', array( $this, 'register_vendor_email_classes' ) );
		}

		public function register_vendor_email_classes($email_classes) {
            //Vendor Emails
			$email_classes['YITH_WCACT_Vendor_Email_Not_Reached_Reserve_Price'] = include YITH_WCACT_PATH . 'includes/compatibility/class.yith-wcact-auction-vendor-email-not-reached-reserve-price.php';
			$email_classes['YITH_WCACT_Vendor_Email_Without_Bid']               = include YITH_WCACT_PATH . 'includes/compatibility/class.yith-wcact-auction-vendor-email-without-bid.php';
			$email_classes['YITH_WCACT_Vendor_Email_Winner']                    = include YITH_WCACT_PATH . 'includes/compatibility/class.yith-wcact-auction-vendor-email-winner.php';

            return $email_classes;
        }

    }
}

return new YITH_WCACT_Multivendor_Compatibility();