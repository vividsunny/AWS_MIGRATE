<?php
/**
 * Class BK_Tests_Booking_Product_Availability
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Availability_Day_All_day extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'May 20' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );
        $to   = strtotime( 'next saturday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );
        $to   = strtotime( 'next friday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );
        $to   = strtotime( 'next monday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Aug 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 30' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Aug 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 14' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Aug 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 15' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Aug 15' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 17' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Aug 16' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 30' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = strtotime( 'next tuesday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next thursday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next monday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next tuesday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next wednesday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next wednesday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next thursday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next thursday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next saturday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next tuesday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Oct 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 12' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test custom daily booking multiple days
     */
    function test_custom_daily_booking_multiple_days() {

        // # 2 days
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'May 31' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 03' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        // # 3 days
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 06' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 07' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test fixed daily booking
     */
    function test_fixed_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test fixed daily booking multiple days
     */
    function test_fixed_daily_booking_multiple_days() {

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        

        // # 2 days
        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 03' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'May 31' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        // # 3 days
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 03' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 06' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to, 'debug' => true ) ) );
    }


    /**
     * Test availability with existing bookings
     */
    function test_with_existing_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( array(
                                              array(
                                                  'type'         => 'month',
                                                  'from'         => '1',
                                                  'to'           => '12',
                                                  'bookable'     => 'no',
                                                  'days_enabled' => 'no',
                                                  'days'         => array(),
                                              ),
                                              array(
                                                  'type'         => 'custom',
                                                  'from'         => BK_Helper_Date::create_next_year_date( 'Mar 18', 'date' ),
                                                  'to'           => BK_Helper_Date::create_next_year_date( 'Mar 18', 'date' ),
                                                  'bookable'     => 'yes',
                                                  'days_enabled' => 'no',
                                              ),
                                              array(
                                                  'type'         => 'custom',
                                                  'from'         => BK_Helper_Date::create_next_year_date( 'May 13', 'date' ),
                                                  'to'           => BK_Helper_Date::create_next_year_date( 'May 13', 'date' ),
                                                  'bookable'     => 'yes',
                                                  'days_enabled' => 'no',
                                              ),
                                              array(
                                                  'type'         => 'custom',
                                                  'from'         => BK_Helper_Date::create_next_year_date( 'Oct 31', 'date' ),
                                                  'to'           => BK_Helper_Date::create_next_year_date( 'Oct 31', 'date' ),
                                                  'bookable'     => 'yes',
                                                  'days_enabled' => 'no',
                                              ),
                                          ) );
        


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Mar 18' );
        $to   = BK_Helper_Date::create_next_year_date( 'Mar 18' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'May 13' );
        $to   = BK_Helper_Date::create_next_year_date( 'May 13' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Oct 31' );
        $to   = BK_Helper_Date::create_next_year_date( 'Oct 31' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        // without setting $to
        $from = BK_Helper_Date::create_next_year_date( 'Mar 18' );
        $this->assertTrue( $product->is_available( array( 'from' => $from ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'May 13' );
        $this->assertTrue( $product->is_available( array( 'from' => $from ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Oct 31' );
        $this->assertTrue( $product->is_available( array( 'from' => $from ) ) );

    }


    function test_fixed_dates() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        


        $booking = $this->create_and_store_booking( array(
                                                        'product_id' => $product->get_id(),
                                                        'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
                                                        'to'         => BK_Helper_Date::create_next_year_date( 'Jan 04 23:59:59' ),
                                                        'all_day'    => 'yes'
                                                    ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

    }
}
