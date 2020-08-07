<?php

/**
 * Class BK_Helper_Booking_Product.
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Booking_Product {
    /**
     * Create booking product.
     *
     * @return WC_Product_Booking
     */
    public static function create_booking_product() {
        $product = new WC_Product_Booking();
        $product->set_props( array(
                                 'name'                             => 'Dummy Product',
                                 'sku'                              => 'DUMMY SKU',
                                 'manage_stock'                     => false,
                                 'tax_status'                       => 'taxable',
                                 'virtual'                          => false,
                                 'maximum_advance_reservation'      => 5,
                                 'maximum_advance_reservation_unit' => 'year'
                             ) );

        $product->save();

        /** @var WC_Product_Booking $product */
        $product = wc_get_product( $product->get_id() );

        return $product;
    }

    /**
     * delete a product
     *
     * @param int|WC_Product $product
     */
    public static function delete_product( $product ) {
        $product = wc_get_product( $product );
        $product && $product->delete( true );
    }
}
