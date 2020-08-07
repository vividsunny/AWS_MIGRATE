<?php

//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */
if ( ! class_exists( 'HRWP_Wallet_Statement_PDF' ) ) {

    include_once ( HRW_PLUGIN_PATH . '/premium/inc/lib/TCPDF/tcpdf.php' ) ;

    class HRWP_Wallet_Statement_PDF extends TCPDF {
        /*
         * Wallet 
         */

        private $wallet ;

        /*
         * Statement Name 
         */
        private $statement_name ;

        /*
         * Statement module 
         */
        private $statement_module ;

        /*
         * Initialiaze class
         */

        public function __construct( $wallet_id , $statement_name ) {
            $this->statement_name   = $statement_name ;
            $this->wallet           = hrw_get_wallet( $wallet_id ) ;
            $this->statement_module = HRW_Module_Instances::get_module_by_id( 'wallet_account_statement' ) ;

            parent::__construct() ;

            $this->populate_data() ;
        }

        /*
         * Set PDF Header
         */

        public function Header() {
            // Logo
            $image = '<td></td>' ;
            if ( $this->statement_module->logo_image_url != '' ) {
                $image_details = getimagesize( $this->statement_module->logo_image_url ) ;
                $width         = $image_details[ 0 ] ;
                $height        = $image_details[ 1 ] ;
                $percetage     = $this->statement_module->logo_max_percent / 100 ;
                if ( $this->statement_module->logo_max_percent != '' ) {
                    $new_image_width  = $percetage * $width ;
                    $new_image_height = ($height / $width) * $new_image_width ;
                    $width            = $new_image_width ;
                    $height           = $new_image_height ;
                }
                $image = '<td><p><img src="' . $this->statement_module->logo_image_url . '" alt="alt attribute" width="' . $width . 'px" height="' . $height . 'px" ></p></td>' ;
            }
            $image_html_path = '<table>'
                    . '<thead>'
                    . '<tr>'
                    . $image
                    . '<td align="center"><h2>' . esc_html__( 'Wallet Account Statements' , HRW_LOCALE ) . '</h2></td>'
                    . '<td></td></tr>'
                    . '</thead>'
                    . '</table>' ;
            //writing header html information
            $this->writeHTML( $image_html_path , true , false , true , false , '' ) ;
            //horizontal line for header
            $this->writeHTML("<hr>", true, false, false, false, '');
        }

        /*
         * Set PDF Default arguments
         */

        public function populate_data() {
            // set document information
            $this->SetCreator( PDF_CREATOR ) ;
            $this->SetTitle( 'Wallet Pro - Account Statement PDF' ) ;

            // set default header data
            $this->SetHeaderData( PDF_HEADER_LOGO , PDF_HEADER_LOGO_WIDTH , PDF_HEADER_TITLE . ' 048' , PDF_HEADER_STRING ) ;

            // set header and footer fonts
            $this->setHeaderFont( Array( PDF_FONT_NAME_MAIN , '' , PDF_FONT_SIZE_MAIN ) ) ;
            $this->setFooterFont( Array( PDF_FONT_NAME_DATA , '' , PDF_FONT_SIZE_DATA ) ) ;

            // set default monospaced font
            $this->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED ) ;

            // set margins
            $this->SetMargins( PDF_MARGIN_LEFT , PDF_MARGIN_TOP , PDF_MARGIN_RIGHT ) ;
            $this->SetHeaderMargin( PDF_MARGIN_HEADER ) ;
            $this->SetFooterMargin( PDF_MARGIN_FOOTER ) ;

            // set auto page breaks
            $this->SetAutoPageBreak( TRUE , PDF_MARGIN_BOTTOM ) ;

            // set image scale factor
            $this->setImageScale( PDF_IMAGE_SCALE_RATIO ) ;

            // set some language-dependent strings (optional)
            if ( @file_exists( dirname( __FILE__ ) . '/lang/eng.php' ) ) {
                require_once(dirname( __FILE__ ) . '/lang/eng.php') ;
                $this->setLanguageArray( $l ) ;
            }

            // add a page
            $this->AddPage() ;
        }

        /*
         * Generate PDF File
         */

        public function generate_pdf( $from , $to , $name ) {
            $args = array( 'from' => $from ,
                'to'   => $to ,
                'name' => $name ,
            ) ;

            // pdf wallet info
            $this->write_wallet_info( $args ) ;

            // total transaction details
            $this->write_wallet_total_transaction_info( $args ) ;

            // total transaction details
            $this->write_wallet_transaction_info( $args ) ;
            //directory section
            $file_dir        = new HRW_File_Uploader() ;
            $statement_name  = $file_dir->prepare_file_name( $this->statement_name . '.pdf' ) ;
            $normalized_path = wp_normalize_path( $statement_name ) ;
            $this->Output( $normalized_path , 'F' ) ;
            chmod( $normalized_path , 0777 ) ;

            return $statement_name ;
        }

        /*
         * Write wallet info to pdf
         */

        public function write_wallet_info( $args ) {
            if ( hrw_check_is_array( $args ) ) {
                extract( $args ) ;
                $statement_particulars = '<table style="display:block;" cellpadding="5">'
                        . '<thead>'
                        . '<tr align="center">'
                        . '<td><b>' . $this->statement_module->name_label_heading . '</b><br>' . $this->statement_name . '</td>'
                        . '<td><b>' . $this->statement_module->date_heading . '</b><br>' . date( 'd-M-Y' , current_time( 'timestamp' ) ) . '</td>'
                        . '</tr>'
                        . '</thead>'
                        . '</table>' ;

                $this->writeHTML( $statement_particulars , true , false , false , false , '' ) ;

                //address box
                $table_address = '<table cellpadding="5" >'
                        . '<thead>'
                        . '<tr>'
                        . '<td style="border:1px solid #ccc;" ><b>' . $this->statement_module->admin_section_heading . '</b>' . '<br>' . $this->statement_module->admin_section . '</td>'
                        . '<td style="border:1px solid #ccc;" ><b>' . esc_html__( 'Customer Details' , HRW_LOCALE ) . '</b><br>' . $this->wallet->get_user()->display_name . '<br>' . $this->wallet->get_user()->user_email . '</td>'
                        . '</tr>'
                        . '</thead>'
                        . '</table>' ;
                $this->writeHTML( $table_address , true , false , false , false , '' ) ;

                // time interval box
                $time_interval = '<table cellpadding="5">'
                        . '<thead>'
                        . '<tr>'
                        . '<td style="border:1px solid #ccc;"><b>' . esc_html__( 'Account Statement for : ' ) . $from . esc_html__( ' to ' , HRW_LOCALE ) . $to . '</b></td>'
                        . '</tr>'
                        . '</thead>'
                        . '</table>' ;
                $this->writeHTML( $time_interval , true , false , false , false , '' ) ;
            }
        }

        /*
         * Write wallet total transaction info to pdf
         */

        public function write_wallet_total_transaction_info( $args ) {
            if ( hrw_check_is_array( $args ) ) {
                extract( $args ) ;
                $label = array() ;
                $args  = array( 'wallet_id' => $this->wallet->get_id() ,
                    'from_date' => date( 'Y-m-d H:i:s' , strtotime( $from ) ) ,
                    'to_date'   => date( 'Y-m-d H:i:s' , strtotime( $to ) )
                        ) ;

                $args[ 'key' ]   = 'hrw_total' ;
                $total_available = $this->statement_module->get_wallet_info_by_interval( $args ) ;

                $args[ 'key' ] = 'hrw_credit' ;
                $total_credit  = $this->statement_module->get_wallet_info_by_interval( $args ) ;

                $args[ 'key' ] = 'hrw_debit' ;
                $total_debit   = $this->statement_module->get_wallet_info_by_interval( $args ) ;
                $result        = ( float ) $total_available + ( float ) $total_credit - ( float ) $total_debit ;

                $label[ 'avilable' ] = sprintf( esc_html__( 'Opening balance amount on this %s from (%s) to (%s)' , HRW_LOCALE ) , $name , $from , $to ) ;
                $label[ 'credit' ]   = sprintf( esc_html__( 'Total Credit of this %s from (%s) to (%s)' , HRW_LOCALE ) , $name , $from , $to ) ;
                $label[ 'debit' ]    = sprintf( esc_html__( 'Total Debit of this %s from (%s) to (%s)' , HRW_LOCALE ) , $name , $from , $to ) ;
                $label[ 'end' ]      = sprintf( esc_html__( 'at the end of this %s from (%s) to (%s)' , HRW_LOCALE ) , $name , $from , $to ) ;

                $period_action_table = '<table cellpadding="5" style="width:100%;" >'
                        . '<thead>'
                        . '<tr align="center" >'
                        . '<td style="border:1px solid #ccc; width:20%;" >' . hrw_price( $total_available ) . '<br/><br/>' . $label[ 'avilable' ] . '</td>'
                        . '<td style="vertical-align:middle; width:6.6%;">' . '+' . '</td>'
                        . '<td style="border:1px solid #ccc; width:20%;" >' . hrw_price( $total_credit ) . '<br/><br/>' . $label[ 'credit' ] . '</td>'
                        . '<td style="vertical-align:middle; width:6.6%;">' . '-' . '</td>'
                        . '<td style="border:1px solid #ccc; width:20%;" >' . hrw_price( $total_debit ) . '<br/><br/>' . $label[ 'debit' ] . '</td>'
                        . '<td style="vertical-align:middle; width:6.6%;">' . '=' . '</td>'
                        . '<td style="border:1px solid #ccc; width:20%;" >' . hrw_price( $result ) . '<br/><br/>' . $label[ 'end' ] . '</td>'
                        . '</tr>'
                        . '</thead>'
                        . '</table>' ;

                $this->writeHTML( $period_action_table , true , false , false , false , '' ) ;
            }
        }

        /*
         * Write wallet transaction info to pdf
         */

        public function write_wallet_transaction_info( $args ) {
            if ( hrw_check_is_array( $args ) ) {
                extract( $args ) ;
                $args            = array( 'wallet_id' => $this->wallet->get_id() ,
                    'from_date' => date( 'Y-m-d H:i:s' , strtotime( $from ) ) ,
                    'to_date'   => date( 'Y-m-d H:i:s' , strtotime( $to ) )
                        ) ;
                // write transaction table.
                $args[ 'key' ]   = 'hrw_transactions_log' ;
                $transaction_ids = $this->statement_module->get_transaction_ids_by_interval( $args ) ;

                $thead = '<thead>'
                        . '<tr>'
                        . '<th style="border:1px solid #ccc;"><b>' . esc_html__( 'Date & Time' , HRW_LOCALE ) . '</b></th>'
                        . '<th style="border:1px solid #ccc;"><b>' . esc_html__( 'Transaction Detials' , HRW_LOCALE ) . '</b></th>'
                        . '<th style="border:1px solid #ccc;"><b>' . esc_html__( 'Amount' , HRW_LOCALE ) . '</b></th>'
                        . '<th style="border:1px solid #ccc;"><b>' . esc_html__( 'Action' , HRW_LOCALE ) . '</b></th>'
                        . '<th style="border:1px solid #ccc;"><b>' . esc_html__( 'Available Balance' , HRW_LOCALE ) . '</b></th>'
                        . '</tr>'
                        . '</thead>' ;

                $row_content = '<tbody>' ;
                if ( hrw_check_is_array( $transaction_ids ) ) {
                    foreach ( $transaction_ids as $transaction_id ) {
                        $transaction_details = hrw_get_transaction_log( $transaction_id ) ;
                        $row_content         .= '<tr>'
                                . '<td style="border:1px solid #ccc;">' . $transaction_details->get_formatted_date() . '</td>'
                                . '<td style="border:1px solid #ccc;">' . $transaction_details->get_event() . '</td>'
                                . '<td style="border:1px solid #ccc;">' . hrw_price( $transaction_details->get_amount() ) . '</td>'
                                . '<td style="border:1px solid #ccc;">' . hrw_display_status( $transaction_details->get_status() ) . '</td>'
                                . '<td style="border:1px solid #ccc;">' . hrw_price( $transaction_details->get_total() ) . '</td>'
                                . '</tr>' ;
                    }
                } else {
                    $row_content .= '<tr>'
                            . '<td colspan="5" style="border:1px solid #ccc;">' . esc_html__( 'No transaction found' , HRW_LOCALE ) . '</td>'
                            . '</tr>' ;
                }
                $row_content .= '</tbody>' ;

                $transaction_tbl = '<h2>' . esc_html__( 'Transaction Logs' , HRW_LOCALE ) . '</h2>'
                        . '<table cellpadding="5" border="0">'
                        . $thead
                        . $row_content
                        . '</table>' ;

                $this->writeHTML( $transaction_tbl , true , false , false , false , '' ) ;
            }
        }

    }

}