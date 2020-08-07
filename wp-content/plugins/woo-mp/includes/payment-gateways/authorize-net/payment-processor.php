<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net;

defined( 'ABSPATH' ) || die;

/**
 * Process a payment with Authorize.Net.
 */
class Payment_Processor extends Transaction_Processor {

    /**
     * Process a payment.
     *
     * @param  array $params The following payment parameters are required:
     *
     * [
     *     'token'          => 'abc123', // The payment nonce.
     *     'tax_amount'     => 1.23,     // The portion of the order total that is made up of taxes.
     *     'duty_amount'    => 4.56,     // The portion of the order total that is made up of duties.
     *     'freight_amount' => 7.89,     // The portion of the order total that is made up of freight/shipping fees.
     *     'tax_exempt'     => false,    // Whether the order is tax-exempt.
     *     'po_number'      => 'xyz789'  // The purchase order number.
     *
     *     // The following parameters are provided by {@see \Woo_MP\Payment_Processor::process()}.
     *     'order'          => null,
     *     'capture'        => false,
     *     'description'    => ''
     * ]
     *
     * @see \Woo_MP\Payment_Processor::process() For parameters required for all payment processors.
     *
     * @return array         Result:
     *
     * [
     *     'trans_id'        => '',
     *     'held_for_review' => false
     * ]
     */
    public function process( $params ) {
        $transaction_type = $params['capture'] ? 'authCaptureTransaction' : 'authOnlyTransaction';

        $request = [
            'createTransactionRequest' => [
                'transactionRequest' => [
                    'transactionType' => $transaction_type,
                    'amount'          => $params['amount'],
                    'payment'         => [
                        'opaqueData' => [
                            'dataDescriptor' => 'COMMON.ACCEPT.INAPP.PAYMENT',
                            'dataValue'      => $params['token'],
                        ],
                    ],
                    'order'           => [
                        'invoiceNumber' => $params['order']->get_order_number(),
                        'description'   => $this->trim_chars( $params['description'], 255 ),
                    ],
                    'lineItems'       => [],
                    'tax'             => [
                        'amount' => (float) $params['tax_amount'],
                    ],
                    'duty'            => [
                        'amount' => (float) $params['duty_amount'],
                    ],
                    'shipping'        => [
                        'amount' => (float) $params['freight_amount'],
                    ],
                    'taxExempt'       => $params['tax_exempt'],
                    'poNumber'        => $params['po_number'],
                    'billTo'          => [],
                    'shipTo'          => [],
                ],
            ],
        ];

        if ( get_option( 'woo_mp_authorize_net_include_item_details', 'yes' ) === 'yes' ) {
            $line_items = [];

            foreach ( $params['order']->get_items() as $item ) {
                if ( \Woo_MP\is_wc3() ) {
                    $product     = $item->get_product();
                    $item_id     = ( $product ? $product->get_sku() : '' ) ?: $item->get_product_id();
                    $name        = $item->get_name() ?: $item_id;
                    $description = $product ? ( $product->get_short_description() ?: $product->get_description() ) : '';
                    $quantity    = $item->get_quantity();
                } else {
                    $product     = wc_get_product( $item['product_id'] );
                    $item_id     = ( $product ? $product->get_sku() : '' ) ?: $item['product_id'];
                    $name        = $item['name'] ?: $item_id;
                    $description = $product ? ( $product->post->post_excerpt ?: $product->post->post_content ) : '';
                    $quantity    = $item['qty'];
                }

                $line_items[] = [
                    'itemId'      => $this->trim_chars( $item_id, 31 ),
                    'name'        => $this->trim_chars( $name, 31 ),
                    'description' => $this->trim_chars( wp_strip_all_tags( strip_shortcodes( $description ) ), 255 ),
                    'quantity'    => $quantity,
                    'unitPrice'   => (float) ( $product ? $product->get_price() : 0 ),
                    'taxable'     => $product ? $product->is_taxable() : true,
                ];
            }

            $request['createTransactionRequest']['transactionRequest']['lineItems']['lineItem'] = $line_items;
        }

        if ( get_option( 'woo_mp_authorize_net_include_billing_details', 'yes' ) === 'yes' ) {
            $request['createTransactionRequest']['transactionRequest']['billTo'] = [
                'firstName'   => $this->trim_chars( $params['order']->get_billing_first_name(), 50 ),
                'lastName'    => $this->trim_chars( $params['order']->get_billing_last_name(), 50 ),
                'company'     => $this->trim_chars( $params['order']->get_billing_company(), 50 ),
                'address'     => $this->get_address( $params['order'], 'billing' ),
                'city'        => $this->trim_chars( $params['order']->get_billing_city(), 40 ),
                'state'       => $this->trim_chars( $params['order']->get_billing_state(), 40 ),
                'zip'         => $this->trim_chars( $params['order']->get_billing_postcode(), 20 ),
                'country'     => $this->trim_chars( $params['order']->get_billing_country(), 60 ),
                'phoneNumber' => $this->trim_chars( $params['order']->get_billing_phone(), 25 ),
            ];
        }

        if ( get_option( 'woo_mp_authorize_net_include_shipping_details', 'yes' ) === 'yes' ) {
            $request['createTransactionRequest']['transactionRequest']['shipTo'] = [
                'firstName' => $this->trim_chars( $params['order']->get_shipping_first_name(), 50 ),
                'lastName'  => $this->trim_chars( $params['order']->get_shipping_last_name(), 50 ),
                'company'   => $this->trim_chars( $params['order']->get_shipping_company(), 50 ),
                'address'   => $this->get_address( $params['order'], 'shipping' ),
                'city'      => $this->trim_chars( $params['order']->get_shipping_city(), 40 ),
                'state'     => $this->trim_chars( $params['order']->get_shipping_state(), 40 ),
                'zip'       => $this->trim_chars( $params['order']->get_shipping_postcode(), 20 ),
                'country'   => $this->trim_chars( $params['order']->get_shipping_country(), 60 ),
            ];
        }

        $request = apply_filters( 'woo_mp_authorize_net_charge_request', $request, $params['order']->get_core_order() );

        $response = $this->request( $request );

        return [
            'trans_id'        => $response['response']['transactionResponse']['transId'],
            'held_for_review' => $response['response']['transactionResponse']['responseCode'] === '4',
        ];
    }

    /**
     * Get an address to send to Authorize.Net.
     *
     * @param  object $order The order.
     * @param  string $type  The address type. This can be 'shipping' or 'billing'.
     * @return string        The address.
     */
    private function get_address( $order, $type ) {
        $address   = $order->{"get_{$type}_address_1"}();
        $address_2 = $order->{"get_{$type}_address_2"}();

        // Authorize.Net doesn't have an address line 2 field.
        if ( $address_2 ) {
            $address .= ' | ' . $address_2;
        }

        $address = $this->trim_chars( $address, 60 );

        return $address;
    }

    /**
     * Truncate a string to a given length and add
     * an ellipsis if the string needed to be truncated.
     * The last 3 characters are reserved for the 3 dots.
     * Authorize.Net imposes tight character limits on fields.
     *
     * @param  string $str    The string to truncate.
     * @param  int    $length The character limit.
     * @return string         The truncated string.
     */
    private function trim_chars( $str, $length ) {
        if ( mb_strlen( $str ) > $length ) {
            return trim( substr( $str, 0, $length - 3 ) ) . '...';
        }

        return $str;
    }

}
