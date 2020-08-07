<?php

namespace Woo_MP\Payment_Gateways\Stripe;

defined( 'ABSPATH' ) || die;

/**
 * Stripe payment gateway.
 */
class Payment_Gateway extends \Woo_MP\Payment_Gateway\Payment_Gateway {

    /**
     * Set up initial values.
     */
    public function __construct() {
        $this->id           = 'stripe';
        $this->title        = 'Stripe';
        $this->custom_title = get_option( 'woo_mp_stripe_title', 'Credit Card (Stripe)' );
    }

    public function get_settings() {
        $text_style = 'width: 400px;';

        $moto_link = 'https://support.stripe.com/questions/mail-order-telephone-order-moto-transactions-when-to-categorize-transactions-as-moto';

        $moto_description = sprintf(
            /* translators: %s: Link to Stripe support page */
            __( 'You will need to contact Stripe to get this feature enabled for your account. Read more about it %s.', 'woo-mp' ),
            sprintf( '<a href="%s" target="_blank">%s</a>', $moto_link, __( 'here', 'woo-mp' ) )
        );

        return [
            [
                'title' => __( 'API Keys', 'woo-mp' ),
                'type'  => 'title',
                'desc'  => WOO_MP_CONFIG_HELP,
            ],
            [
                'title' => __( 'Secret Key', 'woo-mp' ),
                'type'  => 'text',
                'id'    => 'woo_mp_stripe_secret_key',
                'css'   => $text_style,
            ],
            [
                'title' => __( 'Publishable Key', 'woo-mp' ),
                'type'  => 'text',
                'id'    => 'woo_mp_stripe_publishable_key',
                'css'   => $text_style,
            ],
            [
                'type' => 'sectionend',
            ],
            [
                'title' => __( 'Settings', 'woo-mp' ),
                'type'  => 'title',
            ],
            [
                'title'    => __( 'Title', 'woo-mp' ),
                'type'     => 'text',
                'desc'     => __( 'Choose a payment method title.', 'woo-mp' ),
                'id'       => 'woo_mp_stripe_title',
                'default'  => 'Credit Card (Stripe)',
                'desc_tip' => true,
                'css'      => $text_style,
            ],
            [
                'title'   => __( 'Include Customer Name and Email', 'woo-mp' ),
                'type'    => 'checkbox',
                'desc'    => __( "Send customer's billing name and email to Stripe.", 'woo-mp' ),
                'id'      => 'woo_mp_stripe_include_name_and_email',
                'default' => 'yes',
            ],
            [
                'title'    => __( 'Mark Payments as MOTO', 'woo-mp' ),
                'type'     => 'checkbox',
                'desc'     => __( 'Enable Mail Order / Telephone Order (MOTO) SCA exemption.', 'woo-mp' ),
                'desc_tip' => $moto_description,
                'id'       => 'woo_mp_stripe_moto_enabled',
                'default'  => 'no',
            ],
            [
                'type' => 'sectionend',
            ],
        ];
    }

    public function get_payment_meta_box_helper() {
        return new Payment_Meta_Box_Helper();
    }

    public function get_payment_processor() {
        return new Payment_Processor();
    }

}
