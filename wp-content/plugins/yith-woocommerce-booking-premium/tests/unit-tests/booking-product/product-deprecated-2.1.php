<?php

/**
 * Class BK_Tests_Booking_Product_Deprecated_2_1
 * test for deprecated product functions and methods
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */
class BK_Tests_Booking_Product_Deprecated_2_1 extends BK_Unit_Test_Case_With_Store {

    /**
     * Test the old property "multiply_costs_by_persons", since 2.1 split in 2 different properties
     */
    function test_old_multiply_costs_by_persons() {
        $product = $this->create_and_store_booking_product();

        delete_post_meta( $product->get_id(), '_yith_booking_multiply_base_price_by_number_of_people' );
        delete_post_meta( $product->get_id(), '_yith_booking_multiply_fixed_base_fee_by_number_of_people' );
        update_post_meta( $product->get_id(), '_yith_booking_multiply_costs_by_persons', 'yes' );

        /** @var WC_Product_Booking $product */
        $product = wc_get_product( $product->get_id() );

        $this->assertTrue( $product->get_multiply_fixed_base_fee_by_number_of_people() );
        $this->assertTrue( $product->get_multiply_base_price_by_number_of_people() );

        delete_post_meta( $product->get_id(), '_yith_booking_multiply_base_price_by_number_of_people' );
        delete_post_meta( $product->get_id(), '_yith_booking_multiply_fixed_base_fee_by_number_of_people' );
        update_post_meta( $product->get_id(), '_yith_booking_multiply_costs_by_persons', 'no' );

        /** @var WC_Product_Booking $product */
        $product = wc_get_product( $product->get_id() );

        $this->assertFalse( $product->get_multiply_fixed_base_fee_by_number_of_people() );
        $this->assertFalse( $product->get_multiply_base_price_by_number_of_people() );
    }

    /**
     * Test the old property "multiply_costs_by_persons", since 2.1 split in 2 different properties
     */
    function test_deprecated_get_booking_prop() {
        $product = $this->create_and_store_booking_product();

        $product->set_location( 'Rome' );
        $product->set_max_bookings_per_unit( 5 );
        $product->set_check_in( '10:00' );
        $product->set_full_day( true );

        $this->assertEquals( $product->get_location(), $product->get_booking_prop( 'location' ) );
        $this->assertEquals( $product->get_max_bookings_per_unit(), $product->get_booking_prop( 'max_per_block' ) );
        $this->assertEquals( $product->get_check_in(), $product->get_booking_prop( 'checkin' ) );
        $this->assertEquals( $product->get_full_day(), $product->get_booking_prop( 'all_day' ) );
    }
}
