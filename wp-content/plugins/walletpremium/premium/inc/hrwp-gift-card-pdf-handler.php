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
if ( ! class_exists ( 'HRWP_Wallet_Gift_Card_PDF' ) ) {

    include_once ( HRW_PLUGIN_PATH . '/premium/inc/lib/TCPDF/tcpdf.php' ) ;

    class HRWP_Wallet_Gift_Card_PDF extends TCPDF {

        /*
         * Gift File Name 
         */
        private $gift_file_name ;

        /*
         * Gift Card module 
         */
        private $gift_card_module ;

        /*
         * Initialiaze class
         */

        public function __construct( $gift_id , $gift_file_name ) {
            $this->gift_file_name   = $gift_file_name ;
            $this->gift             = hrw_get_gift ( $gift_id ) ;
            $this->gift_card_module = new HRW_GiftCard() ;

            parent::__construct () ;

            $this->populate_data () ;
        }

        /*
         * Set PDF Header
         */

        public function Header() {
            // Logo
            $image = '<td></td>' ;
            if ( $this->gift_card_module->logo_image_url != '' && $this->gift_card_module->logo_max_percent != '' ) {
                $image_details = getimagesize ( $this->gift_card_module->logo_image_url ) ;
                $width         = $image_details[ 0 ] ;
                $height        = $image_details[ 1 ] ;
                $percetage     = $this->gift_card_module->logo_max_percent / 100 ;
                if ( $this->gift_card_module->logo_max_percent != '' ) {
                    $new_image_width  = $percetage * $width ;
                    $new_image_height = ($height / $width) * $new_image_width ;
                    $width            = $new_image_width ;
                    $height           = $new_image_height ;
                }
                $image = '<td><p><img src="' . $this->gift_card_module->logo_image_url . '" alt="alt attribute" width="' . $width . 'px" height="' . $height . 'px" ></p></td>' ;
            }
            $image_html_path = '<table>'
                    . '<thead>'
                    . '<tr>'
                    . $image
                    . '<td align="center"><h2>' . esc_html__ ( 'Gift Card' , HRW_LOCALE ) . '</h2></td>'
                    . '<td></td></tr>'
                    . '</thead>'
                    . '</table>' ;
            //writing header html information
            $this->writeHTML ( $image_html_path , true , false , true , false , '' ) ;
            //horizontal line for header
            $this->writeHTML ( "<hr>" , true , false , false , false , '' ) ;
        }

        /*
         * Set PDF Default arguments
         */

        public function populate_data() {
            // set document information
            $this->SetCreator ( PDF_CREATOR ) ;
            $this->SetTitle ( 'Wallet Pro - Gift Card PDF' ) ;

            // set default header data
            $this->SetHeaderData ( PDF_HEADER_LOGO , PDF_HEADER_LOGO_WIDTH , PDF_HEADER_TITLE . ' 048' , PDF_HEADER_STRING ) ;

            // set header and footer fonts
            $this->setHeaderFont ( Array ( PDF_FONT_NAME_MAIN , '' , PDF_FONT_SIZE_MAIN ) ) ;
            $this->setFooterFont ( Array ( PDF_FONT_NAME_DATA , '' , PDF_FONT_SIZE_DATA ) ) ;

            // set default monospaced font
            $this->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED ) ;

            // set margins
            $this->SetMargins ( PDF_MARGIN_LEFT , PDF_MARGIN_TOP , PDF_MARGIN_RIGHT ) ;
            $this->SetHeaderMargin ( PDF_MARGIN_HEADER ) ;
            $this->SetFooterMargin ( PDF_MARGIN_FOOTER ) ;

            // set auto page breaks
            $this->SetAutoPageBreak ( TRUE , PDF_MARGIN_BOTTOM ) ;

            // set image scale factor
            $this->setImageScale ( PDF_IMAGE_SCALE_RATIO ) ;

            // set some language-dependent strings (optional)
            if ( @file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' ) ) {
                require_once(dirname ( __FILE__ ) . '/lang/eng.php') ;
                $this->setLanguageArray ( $l ) ;
            }

            // add a page
            $this->AddPage () ;
        }

        /*
         * Generate PDF File
         */

        public function generate_pdf() {
            //Admin info
            $this->write_admin_info () ;
            //Gift info
            $this->write_gift_info () ;

            //directory section
            $file_dir        = new HRW_File_Uploader() ;
            $gift_card_file  = $file_dir->prepare_file_name ( $this->gift_file_name . '.pdf' ) ;
            $normalized_path = wp_normalize_path ( $gift_card_file ) ;
            $this->Output ( $normalized_path , 'F' ) ;
            chmod ( $normalized_path , 0777 ) ;

            return $gift_card_file ;
        }

        /*
         * Write wallet info to pdf
         */

        public function write_gift_info_old() {
            $gift_content = '<div>'
                    . '<p>' . sprintf ( __ ( 'Your Friend Sent A Gift card to You %s' , HRW_LOCALE ) , $this->gift->get_gift_code () ) . '</p>'
                    . '</div>' ;

            $this->writeHTML ( $gift_content , true , false , false , false , '' ) ;
        }

        /*
         * Write wallet info to pdf
         */

        public function write_admin_info() {

            $admin_info_html = '<table style="display:block;" cellpadding="5">'
                    . '<thead>'
                    . '<tr align="center">'
                    . '<td style="border:1px solid #ccc;" ><b>' . $this->gift_card_module->admin_section_heading . '</b><br> ' . $this->gift_card_module->admin_deatils . '  </td>'
                    . '<td style="border:1px solid #ccc;" ><b>' . $this->gift_card_module->sender_section_heading . '</b><br> <b>User Name:</b> ' . $this->gift->get_user ()->display_name . '<br>'
                    . '<b>Email ID:</b> ' . $this->gift->get_user ()->user_email
                    . '</td>'
                    . '</tr>'
                    . '</thead>'
                    . '</table>' ;

            $this->writeHTML ( $admin_info_html , true , false , false , false , '' ) ;
        }

        public function write_gift_info() {

            $thead = '<thead>'
                    . '<tr>'
                    . '<th style="border:1px solid #ccc;"><b>' . esc_html__ ( 'Gift Card' , HRW_LOCALE ) . '</b></th>'
                    . '<th style="border:1px solid #ccc;"><b>' . esc_html__ ( 'Amount' , HRW_LOCALE ) . '</b></th>'
                    . '<th style="border:1px solid #ccc;"><b>' . esc_html__ ( 'Expiry Date' , HRW_LOCALE ) . '</b></th>'
                    . '</tr>'
                    . '</thead>' ;

            $row_content = '<tbody>' ;

            $row_content .= '<tr>'
                    . '<td style="border:1px solid #ccc;">' . $this->gift->get_gift_code () . '</td>'
                    . '<td style="border:1px solid #ccc;">' . hrw_price ( $this->gift->get_amount () ) . '</td>'
                    . '<td style="border:1px solid #ccc;">' . $this->gift->get_formatted_expired_date () . '</td>'
                    . '</tr>' ;
            $row_content .= '</tbody>' ;

            $gift_table = '<h2>' . esc_html__ ( 'Gift Card' , HRW_LOCALE ) . '</h2>'
                    . '<table cellpadding="5" border="0" align="center">'
                    . $thead
                    . $row_content
                    . '</table>' ;

            $this->writeHTML ( $gift_table , true , false , false , false , '' ) ;
        }

    }

}