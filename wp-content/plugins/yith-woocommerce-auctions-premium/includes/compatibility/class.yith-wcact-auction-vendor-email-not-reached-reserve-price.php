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
 * @class      YITH_WCACT_Vendor_Email_Not_Reached_Reserve_Price
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 *
 */

if ( ! class_exists( 'YITH_WCACT_Vendor_Email_Not_Reached_Reserve_Price' ) ) {
    /**
     * Class YITH_WCACT_Email_Not_Reached_Reserve_Price
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
     */
    class YITH_WCACT_Vendor_Email_Not_Reached_Reserve_Price extends WC_Email {

        /**
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function __construct() {

            // set ID, this simply needs to be a unique name
            $this->id = 'yith_wcact_vendor_email_not_reached_reserve_price';

            // this is the title in WooCommerce Email settings
            $this->title = esc_html__('​Auctions - reserve price not exceeded (to vendor)', 'yith-auctions-for-woocommerce');


            // this is the description in WooCommerce email settings
            $this->description = esc_html__('​Can be emailed when the auction has finished, and the highest bid doesn\'t exceed the minimum reserve price  ', 'yith-auctions-for-woocommerce');

            // these are the default heading and subject lines that can be overridden using the settings
            $this->heading = esc_html__('Max bid doesn\'t exceed minimum reserve price', 'yith-auctions-for-woocommerce');

            $this->subject = esc_html__('Max bid doesn\'t exceed minimum reserve price', 'yith-auctions-for-woocommerce');

            // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
            $this->template_html = 'emails/vendor-not-reached-reserve-price.php';

            // Trigger on new paid orders
            add_action( 'yith_wcact_vendor_not_reached_reserve_price', array( $this, 'trigger' ), 10, 2 );

            // Call parent constructor to load any other defaults not explicity defined here
            parent::__construct();

            // this sets the recipient to the settings defined below in init_form_fields()
            //$this->recipient = $this->get_option( 'recipient' );

            // if none was entered, just use the WP admin email as a fallback
            //if ( ! $this->recipient )
            //$this->recipient = get_option( 'admin_email' );
        }


        public function trigger( $product, $vendor ) {
            /*
             * Edit Lorenzo: first of all, populate $the $this->object var with the parameter received here so
             *              they will be available inside the template
             */

            //Check is email enable or not
            if ( !$this->is_enabled() ) {
                return;
            }

            $url_product = add_query_arg( array( 'post' => $product->get_id(), 'action' => 'edit' ), admin_url( 'post.php' ) );


            $owner = get_user_by('id',$vendor->get_owner());
            $owner = $owner->user_email;

            $this->object = array(

                'product_name'  => $product->post->post_title,
                'product'       => $product,
                'url_product'   => $url_product,
                'owner'         => $owner,
            );

            $this->send( $this->object['owner'],
                $this->get_subject(),
                $this->get_content(),
                $this->get_headers(),
                $this->get_attachments() );
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
                'enabled'    => array(
                    'title'   => esc_html__( 'Enable/Disable', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'label'   => esc_html__( 'Enable this email notification', 'yith-auctions-for-woocommerce' ),
                    'default' => 'yes'
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
return new YITH_WCACT_Vendor_Email_Not_Reached_Reserve_Price();
