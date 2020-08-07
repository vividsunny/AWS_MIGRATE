<?php
/*
 * GDPR Compliance
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

if ( ! class_exists( 'HRW_Privacy' ) ) :

    /**
     * HRW_Privacy class
     */
    class HRW_Privacy {

        /**
         * HRW_Privacy constructor.
         */
        public function __construct() {
            $this->init_hooks() ;
        }

        /**
         * Register plugin
         */
        public function init_hooks() {
            // This hook registers Booking System privacy content
            add_action( 'admin_init' , array( __CLASS__ , 'register_privacy_content' ) , 20 ) ;
        }

        /**
         * Register Privacy Content
         */
        public static function register_privacy_content() {
            if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
                return ;
            }

            $content = self::get_privacy_message() ;
            if ( $content ) {
                wp_add_privacy_policy_content( esc_html__( 'Wallet Premium' , HRW_LOCALE ) , $content ) ;
            }
        }

        /**
         * Prepare Privacy Content
         */
        public static function get_privacy_message() {

            return self::get_privacy_message_html() ;
        }

        /**
         * Get Privacy Content
         */
        public static function get_privacy_message_html() {
            ob_start() ;
            ?>
            <p><?php esc_html_e( 'This includes the basics of what personal data your store may be collecting, storing and sharing. Depending on what settings are enabled and which additional plugins are used, the specific information shared by your store will vary.' , HRW_LOCALE ) ?></p>
            <h2><?php esc_html_e( 'WHAT DOES THE PLUGIN DO?' , HRW_LOCALE ) ; ?></h2>
            <p><?php esc_html_e( 'Create Wallet for users and allows users to add funds to their wallet and use that funds to spend for future purchase on the site.' , HRW_LOCALE ) ; ?> </p>
            <h2><?php esc_html_e( 'WHAT WE COLLECT AND STORE?' , HRW_LOCALE ) ; ?></h2>
            <h4><?php esc_html_e( '- USER ID' , HRW_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php esc_html_e( 'The User id is used for identifying the user and record the wallet credits/debits' , HRW_LOCALE ) ; ?>
                </li>
            </ul>
            <h4><?php esc_html_e( '- EMAIL ID' , HRW_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php esc_html_e( "The Email ID is collected for sending Wallet Email Notifications" , HRW_LOCALE ) ; ?>
                </li>
            </ul>
            <h4><?php esc_html_e( '- PHONE NUMBER' , HRW_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php esc_html_e( 'The Phone Number is collected for sending Wallet SMS Notifications' , HRW_LOCALE ) ; ?>
                </li>
            </ul>
            <h4><?php esc_html_e( '- PAYMENT INFORMATION' , HRW_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php esc_html_e( "The user's PayPal Email id and Bank Account details are collected for processing Withdrawal from their Wallet balance" , HRW_LOCALE ) ; ?>
                </li>
            </ul>
            <?php
            $contents = ob_get_contents() ;
            ob_end_clean() ;

            return $contents ;
        }

    }

    new HRW_Privacy() ;

endif;
