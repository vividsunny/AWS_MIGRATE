<?php
/**
 * Class BK_Tests_Booking_Product_Availability_Minute
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Availability_Minute extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom minutely booking
     */
    function test_custom_minutely_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 15 );
        $product->set_duration_unit( 'minute' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 10:15' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 10:16' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 00:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 24:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next monday 16:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday 15:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next monday 16:15', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next saturday 16:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next tuesday 07:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next tuesday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next friday 12:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday 15:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next friday 16:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday 16:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next monday 17:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test fixed minutely booking
     */
    function test_fixed_minutely_booking(){
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 15 );
        $product->set_duration_unit( 'minute' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 10:15' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 10:30' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 15:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

}
