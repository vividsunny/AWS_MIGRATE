<?php

/**
 * Notifications Instances Class
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Notification_Instances' ) ) {

    /**
     * Class HRW_Notification_Instances
     */
    class HRW_Notification_Instances {
        /*
         * Notifications
         */

        private static $notifications = array () ;

        /*
         * Get Notifications
         */

        public static function get_notifications() {

            if ( ! self::$notifications ) {
                self::load_notifications () ;
            }

            return self::$notifications ;
        }

        /*
         * Load all Notifications
         */

        public static function load_notifications() {

            if ( ! class_exists ( 'HRW_Notifications' ) )
                include HRW_PLUGIN_PATH . '/inc/abstracts/class-hrw-notifications.php' ;

            $default_notification_classes = array (
                'customer-funds-credited'                             => 'HRW_Customer_Funds_Credited_Notification' ,
                'customer-funds-debited'                              => 'HRW_Customer_Funds_Debited_Notification' ,
                'admin-funds-credited'                                => 'HRW_Admin_Funds_Credited_Notification' ,
                'admin-funds-debited'                                 => 'HRW_Admin_Funds_Debited_Notification' ,
                'customer-low-funds'                                  => 'HRW_Customer_Low_Funds_Notification' ,
                'customer-funds-expired'                              => 'HRW_Customer_Funds_Expired_Notification' ,
                'admin-funds-expired'                                 => 'HRW_Admin_Funds_Expired_Notification' ,
                'customer-wallet-unlock'                              => 'HRW_Customer_Wallet_Unlock_Notification' ,
                'admin-wallet-unlock'                                 => 'HRW_Admin_Wallet_Unlock_Notification' ,
                'customer-wallet-lock'                                => 'HRW_Customer_Wallet_Lock_Notification' ,
                'admin-wallet-lock'                                   => 'HRW_Admin_Wallet_Lock_Notification' ,
                'customer-fund-transfered'                            => 'HRW_Customer_Fund_Transfered_Notification' ,
                'customer-fund-received'                              => 'HRW_Customer_Fund_Received_Notification' ,
                'customer-fund-request-submitted'                     => 'HRW_Customer_Fund_Request_Submitted_Notification' ,
                'customer-fund-request-received'                      => 'HRW_Customer_Fund_Request_Received_Notification' ,
                'customer-fund-request-cancelled'                     => 'HRW_Customer_Fund_Request_Cancelled_Notification' ,
                'customer-fund-request-approved'                      => 'HRW_Customer_Fund_Request_Approved_Notification' ,
                'customer-fund-request-declined'                      => 'HRW_Customer_Fund_Request_Declined_Notification' ,
                'customer-purchase-total-cashback'                    => 'HRW_Customer_Purchase_Total_Cashback_Notification' ,
                'customer-topup-cashback'                             => 'HRW_Customer_Topup_Cashback_Notification' ,
                'auto-topup-acknowledgement'                          => 'HRW_Auto_Topup_Acknowledgement_Notification' ,
                'auto-topup-success'                                  => 'HRW_Auto_Topup_Success_Notification' ,
                'auto-topup-failure'                                  => 'HRW_Auto_Topup_Failure_Notification' ,
                'auto-topup-cancelled'                                => 'HRW_Auto_Topup_Cancelled_Notification' ,
                'admin-withdrawal-request'                            => 'HRW_Admin_Withdrawal_Notification' ,
                'customer-withdrawal-acknowledgement'                 => 'HRW_Customer_Withdrawal_Acknowledgement_Notification' ,
                'customer-withdrawal-success'                         => 'HRW_Customer_Withdrawal_Success_Notification' ,
                'customer-withdrawal-rejected'                        => 'HRW_Customer_Withdrawal_Rejected_Notification' ,
                'customer-week-month-custom-wallet-account-statement' => 'HRW_Customer_Week_Month_Wallet_Account_Statement_Notification' ,
                'customer-year-wallet-account-statement'              => 'HRW_Customer_Year_Wallet_Account_Statement_Notification' ,
                'customer-gift-card-received'                         => 'HRW_Customer_Gift_Card_Received_Notification' ,
                'customer-gift-card-sent'                             => 'HRW_Customer_Gift_Card_Sent_Notification' ,
                'customer-gift-card-redeemed'                         => 'HRW_Customer_Gift_Card_Redeemed_Notification' ,
                'customer-gift-card-expiry-reminder'                           => 'HRW_Customer_Gift_Card_Expiry_Notification' ,
                    ) ;

            foreach ( $default_notification_classes as $file_name => $notification_class ) {

                // include file
                include 'class-' . $file_name . '.php' ;

                //add notification
                self::add_notification ( new $notification_class ) ;
            }
        }

        /**
         * Add a Module
         */
        public static function add_notification( $notification ) {

            self::$notifications[ $notification->get_id () ] = $notification ;

            return new self() ;
        }

        /**
         * Get notification by id
         */
        public static function get_notification_by_id( $notification_id ) {
            $notifications = self::get_notifications () ;

            return isset ( $notifications[ $notification_id ] ) ? $notifications[ $notification_id ] : false ;
        }

    }

}
    