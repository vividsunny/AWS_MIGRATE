<?php

/**
 * Class BK_Helper_Booking.
 *
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Booking {
    /**
     * Create booking.
     *
     * @param array $args
     *
     * @return YITH_WCBK_Booking
     */
    public static function create_booking( $args = array() ) {
        if ( !isset( $args[ 'title' ] ) && isset( $args[ 'product_id' ] ) && $product = wc_get_product( $args[ 'product_id' ] ) ) {
            $args[ 'title' ] = $product->get_title();
        }

        return new YITH_WCBK_Booking( '', $args );
    }

    /**
     * delete a booking
     *
     * @param int|YITH_WCBK_Booking $booking
     */
    public static function delete_booking( $booking ) {
        $post_id = $booking instanceof YITH_WCBK_Booking ? $booking->get_id() : $booking;
        wp_delete_post( $post_id );
    }
}
