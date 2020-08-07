<?php
/**
 * Class BK_Tests_Booking_Product
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

/**
 * Sample test case.
 */
class BK_Tests_Booking_Product extends BK_Unit_Test_Case_With_Store {

    /**
     * Test is booking product.
     */
    function test_is_booking_product() {
        $product = $this->create_and_store_booking_product();
        $this->assertTrue( yith_wcbk_is_booking_product( $product ) );
    }

    /**
     * Test is type fixed blocks.
     */
    function test_is_type_fixed_blocks() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );

        $this->assertFalse( $product->is_type_fixed_blocks() );

        $product->set_duration_type( 'fixed' );
        $this->assertTrue( $product->is_type_fixed_blocks() );
    }

    /**
     * Test is full day.
     */
    function test_is_all_day() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_unit( 'day' );
        $product->set_full_day( false );

        $this->assertFalse( $product->is_full_day() );

        $product->set_full_day( true );
        $this->assertTrue( $product->is_full_day() );

        $product->set_duration_unit( 'month' );
        $this->assertFalse( $product->is_full_day() );
    }
}
