<?php

namespace Woo_MP\Controllers;

use Woo_MP\Payment_Gateways;
use Woo_MP\Woo_MP_Order;
use Woo_MP\Payment_Gateway\Payment_Meta_Box_Helper;
use YeEasyAdminNotices\V1\AdminNotice;

defined( 'ABSPATH' ) || die;

/**
 * Controller for the payment meta box.
 */
class Payment_Meta_Box_Controller {

    /**
     * The gateway's payment meta box helper class.
     *
     * @var Payment_Meta_Box_Helper
     */
    private $gateway_helper;

    /**
     * The order.
     *
     * @var object
     */
    private $order;

    /**
     * The currency that payments will be made in.
     *
     * @var string
     */
    private $payment_currency;

    /**
     * The templates that the gateway is providing.
     *
     * Keys are template names and values are the paths to those templates.
     *
     * @var array
     */
    private $gateway_templates;

    /**
     * Payments made for this order.
     *
     * @var array
     */
    private $charges;

    /**
     * Register the meta box for the 'shop_order' post type.
     *
     * @return void
     */
    public static function add_meta_box() {
        $title = apply_filters( 'woo_mp_payments_meta_box_title', 'Payments' );

        add_meta_box( 'woo-mp', $title, [ new static(), 'display' ], 'shop_order' );
    }

    /**
     * Do validation and allow for payment gateways to add their own validation.
     *
     * @return bool true if valid, false otherwise.
     */
    private function validation() {
        $validation = [];

        if ( $this->gateway_helper ) {
            if ( $this->payment_currency !== $this->order->get_currency() ) {
                $validation[] = [
                    'message' => "Transactions will be processed in $this->payment_currency.",
                    'type'    => 'info',
                    'valid'   => true,
                ];
            }

            $validation = array_merge( $validation, $this->gateway_helper->validation() );
        } else {
            $validation[] = [
                'message' => sprintf(
                    'Please <a href="%s">choose your payment processor</a>. %s',
                    WOO_MP_SETTINGS_URL,
                    WOO_MP_CONFIG_HELP
                ),
                'type'    => 'info',
                'valid'   => false,
            ];
        }

        if ( $validation ) {
            $errors = array_values( array_filter(
                $validation,
                function ( $message ) {
					return ! $message['valid'];
                }
            ) );

            if ( $errors ) {
                $validation = [ $errors[0] ];
            }

            foreach ( $validation as $message ) {
                $notice = AdminNotice::create();

                if ( $message['valid'] ) {
                    $notice->dismissible();
                }

                $notice
                    ->type( $message['type'] )
                    ->addClass( 'inline' )
                    ->html( wp_kses_post( $message['message'] ) )
                    ->outputNotice();
            }

            if ( $errors ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Enqueue assets and make some data available on the client side via a global 'wooMP' JavaScript object.
     *
     * @return void
     */
    private function enqueue_assets() {
        \Woo_MP\style( 'style', WOO_MP_URL . '/assets/css/style.css' );

        if ( version_compare( $GLOBALS['wp_version'], '5.3-beta1', '<' ) ) {
            \Woo_MP\style( 'style-5-3', WOO_MP_URL . '/assets/css/wp-backward-compatibility/style-5-3.css' );
        }

        wp_enqueue_script( 'jquery-payment', plugins_url( 'assets/js/jquery-payment/jquery.payment.min.js', WC_PLUGIN_FILE ), [], WC_VERSION, true );
        \Woo_MP\script( 'script', WOO_MP_URL . '/assets/js/script.js' );

        do_action( 'woo_mp_enqueued_assets' );

        $this->gateway_helper->enqueue_assets();

        $client_data = $this->gateway_helper->client_data() + [
            'AJAXURL'        => admin_url( 'admin-ajax.php' ),
            'nonces'         => [
                'woo_mp_process_transaction' => wp_create_nonce( 'woo_mp_process_transaction_' . $this->order->get_id() ),
            ],
            'gatewayID'      => Payment_Gateways::get_active_id(),
            'currency'       => $this->payment_currency,
            'currencySymbol' => get_woocommerce_currency_symbol( $this->payment_currency ),
        ];

        wp_localize_script( 'woo-mp-script', 'wooMP', $client_data );
    }

    /**
     * Output a template.
     *
     * @param  string $name The name of the template.
     * @return void
     */
    private function template( $name ) {
        if ( isset( $this->gateway_templates[ $name ] ) ) {
            require $this->gateway_templates[ $name ];
        } else {
            $template_path = "/templates/$name.php";

            if ( is_readable( WOO_MP_PATH . $template_path ) ) {
                require WOO_MP_PATH . $template_path;
            } elseif ( WOO_MP_PRO && is_readable( WOO_MP_PRO_PATH . $template_path ) ) {
                require WOO_MP_PRO_PATH . $template_path;
            }
        }
    }

    /**
     * Run validation, enqueue assets, and output the meta box content.
     *
     * @return void
     */
    public function display() {
        $gateway = Payment_Gateways::get_active();

        if ( $gateway ) {
            $this->gateway_helper    = $gateway->get_payment_meta_box_helper();
            $this->order             = new Woo_MP_Order( wc_get_order() );
            $this->payment_currency  = $this->gateway_helper->get_currency( $this->order->get_currency() );
            $this->gateway_templates = $this->gateway_helper->get_templates();
            $this->charges           = $this->order->get_woo_mp_payments();
        }

        if ( ! $this->validation() ) {
            return;
        }

        $this->enqueue_assets();

        $this->template( 'payments-meta-box' );
    }

}
