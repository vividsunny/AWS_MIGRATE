<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'YITH_WCACT_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_WCACT_Email_Auction_No_Winner
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 *
 */

if ( !class_exists( 'YITH_WCACT_Email_Auction_No_Winner' ) ) {
    /**
     * Class YITH_WCACT_Email_Not_Reached_Reserve_Price
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
     */
    class YITH_WCACT_Email_Auction_No_Winner extends WC_Email {

        /**
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function __construct() {

            // set ID, this simply needs to be a unique name
            $this->id = 'yith_wcact_email_auction_no_winner';

            // this is the title in WooCommerce Email settings
            $this->title = esc_html__( '​Auctions - No Winner', 'yith-auctions-for-woocommerce' );

            $this->customer_email = true;

            // this is the description in WooCommerce email settings
            $this->description = esc_html__( '​Can be emailed to users who did not win  the auction after it is finished', 'yith-auctions-for-woocommerce' );

            // these are the default heading and subject lines that can be overridden using the settings
            $this->heading = esc_html__( 'Auction No Winner', 'yith-auctions-for-woocommerce' );

            $this->subject = esc_html__( 'Auction No Winner', 'yith-auctions-for-woocommerce' );

            // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
            $this->template_html = 'emails/auction-no-winner.php';
            $this->template_html = 'emails/auction-no-winner.php';


            // Trigger on new paid orders
            add_action( 'yith_wcact_auction_no_winner', array( $this, 'trigger' ), 10,2);

            // Call parent constructor to load any other defaults not explicity defined here
            parent::__construct();

        }


        public function trigger( $product,$user ) {
            /*
             * Edit Lorenzo: first of all, populate $the $this->object var with the parameter received here so
             *              they will be available inside the template
             */

            //Check is email enable or not
            if ( !$this->is_enabled() ) {
                return;
            }

            $url_product = get_permalink($product->get_id());


            $this->object = array(
                'user_email'    => $user->data->user_email,
                'user_name'     => $user->user_login,
                'product_id'    => $product->get_id(),
                'product_name'  => $product->get_title(),
                'product'       => $product,
                'url_product'   => $url_product,
            );

            $mail_is_send = $this->send( $this->object[ 'user_email' ],
                $this->get_subject(),
                $this->get_content(),
                $this->get_headers(),
                $this->get_attachments() );

            if($mail_is_send) {

            }
        }


        public function get_content_html() {
            return wc_get_template_html( $this->template_html, array(
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'    => false,
                'email'         => $this
            ),
                '',
                YITH_WCACT_TEMPLATE_PATH );
        }


        public function get_content_plain() {
            return wc_get_template_html( $this->template_plain, array(
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'    => true,
                'email'         => $this
            ),
                '',
                YITH_WCACT_TEMPLATE_PATH );
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'   => esc_html__( 'Enable/Disable', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'label'   => esc_html__( 'Enable this email notification', 'yith-auctions-for-woocommerce' ),
                    'default' => 'false'
                ),

                'subject'    => array(
                    'title'       => esc_html__( 'Subject', 'yith-auctions-for-woocommerce' ),
                    'type'        => 'text',
                    'description' => sprintf( esc_html__( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'yith-auctions-for-woocommerce' ), $this->subject ),
                    'placeholder' => '',
                    'default'     => '',
                    'desc_tip'    => true
                ),
                'heading'    => array(
                    'title'       => esc_html__( 'Email Heading', 'yith-auctions-for-woocommerce' ),
                    'type'        => 'text',
                    'description' => sprintf( esc_html__( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'yith-auctions-for-woocommerce' ), $this->heading ),
                    'placeholder' => '',
                    'default'     => '',
                    'desc_tip'    => true
                ),
                'email_type' => array(
                    'title'       => esc_html__( 'Email type', 'yith-auctions-for-woocommerce' ),
                    'type'        => 'select',
                    'description' => esc_html__( 'Choose the email format to send.', 'yith-auctions-for-woocommerce' ),
                    'default'     => 'html',
                    'class'       => 'email_type wc-enhanced-select',
                    'options'     => $this->get_email_type_options(),
                    'desc_tip'    => true
                )
            );
        }


    }

}
return new YITH_WCACT_Email_Auction_No_Winner();
