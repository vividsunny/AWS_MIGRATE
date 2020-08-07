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
 * @class      YITH_WCACT_Notifty
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */


if ( !class_exists( 'YITH_WCACT_Notify' ) ) {

    class YITH_WCACT_Notify
    {
        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCACT_Notify
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
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */

        public function __construct() {
          
            add_filter('woocommerce_email_classes', array($this, 'register_email_classes'));
            add_filter('woocommerce_locate_core_template', array($this, 'locate_core_template'), 10, 3);


        }

        /**
         * Register email classes
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return array
         */
        public function register_email_classes($email_classes)
        {
            //User Emails
            $email_classes['YITH_WCACT_Email_Better_Bid'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-better-bid.php');
            $email_classes['YITH_WCACT_Email_End_Auction'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-end-auction.php');
            $email_classes['YITH_WCACT_Email_Auction_Winner'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-auction-winner.php');
            $email_classes['YITH_WCACT_Email_Successfully_Bid'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-successfully-bid.php');
            $email_classes['YITH_WCACT_Email_Auction_No_Winner'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-auction-no-winner.php');
            $email_classes['YITH_WCACT_Email_Delete_Bid'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-delete-bid.php');



            //Admin Emails
            $email_classes['YITH_WCACT_Email_Not_Reached_Reserve_Price'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-not-reached-reserve-price.php');
            $email_classes['YITH_WCACT_Email_Without_Bid'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-without-bid.php');
            $email_classes['YITH_WCACT_Email_Winner_Admin'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-winner-admin.php');
            $email_classes['YITH_WCACT_Email_Successfully_Bid_Admin'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-successfully-bid-admin.php');
            $email_classes['YITH_WCACT_Email_Delete_Bid_Admin'] = include(YITH_WCACT_PATH . 'includes/emails/class.yith-wcact-auction-email-delete-bid-admin.php');


            return $email_classes;

        }
        /**
         * locate core template
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return string
         */
        public function locate_core_template($core_file, $template, $template_base)
        {
            $custom_template = array(
                //HTML Email
                'emails/better-bid.php',
                'emails/end-auction.php',
                'emails/not-reached-reserve-price.php',
                'emails/auction-winner.php',
                'emails/without-any-bids.php',
                'emails/auction-winner-admin.php',
                'emails/successfully-bid.php',
                'emails/successfully-bid-admin.php',
                'emails/auction-no-winner.php',
                'emails/auction-delete-bid',
                'emails/auction-delete-bid-admin',


                // Plain Email
                'emails/plain/better-bid.php',
                'emails/plain/end-auction.php',
                'emails/not-reached-reserve-price.php',

            );

            if (in_array($template, $custom_template)) {
                $core_file = YITH_WCACT_TEMPLATE_PATH . $template;
            }

            return $core_file;
        }
       
    }

}

return new YITH_WCACT_Notify();
