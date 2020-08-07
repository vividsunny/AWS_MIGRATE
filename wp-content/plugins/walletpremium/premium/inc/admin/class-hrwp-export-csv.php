<?php

/**
 * Export CSV File.
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'HRWP_Export_CSV' ) ) {

    /**
     * HRWP_Export_CSV.
     */
    class HRWP_Export_CSV {

        /**
         * Filename.
         */
        private static $file_name = '' ;

        /**
         * Heading.
         */
        private static $heading = '' ;

        /**
         * Data.
         */
        private static $data = array() ;

        /**
         * Withdrawal IDs.
         */
        private static $withdrawal_ids ;

        /**
         * Export CSV File.
         */
        public static function export_csv_file() {

            ob_end_clean() ;
            header( "Content-type:text/csv" ) ;
            header( "Content-Disposition:attachment;filename=" . sanitize_file_name( self::$file_name ) . ".csv" ) ;
            header( "Pragma:no-cache" ) ;

            echo self::$heading ;

            self::export_column_for_csv_file() ;
        }

        /**
         * Export Column for CSV File.
         */
        public static function export_column_for_csv_file() {

            /* fopen — Opens file or URL */
            /* fputcsv — Format line as CSV and write to file pointer */

            $output = fopen( "php://output" , "w" ) ;
            if( hrw_check_is_array( self::$data ) ) {
                foreach( self::$data as $table_data ) {
                    fputcsv( $output , $table_data ) ;
                }
            }

            fclose( $output ) ;

            exit ;
        }

        /**
         * Export Paypal Mass Payment CSV File.
         */
        public static function export_paypal_mass_payment_csv() {

            self::get_withdrawal_ids() ;

            if( ! hrw_check_is_array( self::$withdrawal_ids ) )
                return ;

            foreach( self::$withdrawal_ids as $id ) {

                $withdrawal_obj = hrw_get_wallet_withdrawal( $id ) ;

                if( $withdrawal_obj->get_payment_method() != 'paypal' )
                    continue ;

                self::$data[] = array(
                    esc_html( $withdrawal_obj->get_id() ) ,
                    esc_html( $withdrawal_obj->get_user()->display_name ) ,
                    esc_html( $withdrawal_obj->get_paypal_details() ) ,
                    esc_html( $withdrawal_obj->get_amount() ) ,
                    esc_html( get_woocommerce_currency() ) ,
                        ) ;
            }

            /* filename,heading,data to export csv file */
            self::$file_name = 'hrw_paypal_mass_payment_csv_file' ;
            self::$heading   = "ID,Username,paypalemail,Amount,Currency" . "\n" ;

            /* export csv file */
            self::export_csv_file() ;
        }

        /**
         * Get Withdrawal IDs.
         */
        public static function get_withdrawal_ids() {

            if( isset( self::$withdrawal_ids ) )
                return self::$withdrawal_ids ;

            self::$withdrawal_ids = get_posts( array(
                'post_type'      => HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ,
                'post_status'    => hrw_get_withdrawal_log_statuses() ,
                'fields'         => 'ids' ,
                'posts_per_page' => '-1'
                    ) ) ;

            return self::$withdrawal_ids ;
        }

    }

}