<?php
/**
 * Class BK_Tests_Booking_Product_Availability
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Availability_Day extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'May 20' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 03' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );
        $to   = strtotime( 'next saturday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );
        $to   = strtotime( 'next monday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Aug 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 30' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Aug 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Aug 15' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

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

        $from = strtotime( 'next wednesday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next thursday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next thursday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next saturday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Sep 01' ) );
        $to   = strtotime( 'next wednesday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Oct 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 31' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test custom daily booking allowed start days
     */
    function test_custom_daily_booking_allowed_start_days() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        $product->set_allowed_start_days( array( 1, 3 ) );


        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Oct 01' ) );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 31' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next wednesday', BK_Helper_Date::create_next_year_date( 'Oct 01' ) );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 31' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Oct 01' ) );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 31' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next sunday', BK_Helper_Date::create_next_year_date( 'Oct 01' ) );
        $to   = BK_Helper_Date::create_next_year_date( 'Dec 31' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test custom daily booking multiple days
     */
    function test_custom_daily_booking_multiple_days() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 06' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'May 31' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 06' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $product = $this->create_and_store_booking_product();

        $product->set_duration_type( 'customer' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 06' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 07' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

    }

    /**
     * Test fixed daily booking
     */
    function test_fixed_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 03' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 10' );
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
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 03' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to, 'debug' => true ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'May 31' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 02' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 07' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

    }

    /**
     * Test availability with existing bookings
     */
    function test_with_existing_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );


        $booking = $this->create_and_store_booking( array(
                                                        'product_id' => $product->get_id(),
                                                        'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
                                                        'to'         => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
                                                    ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 06' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

    }

    /**
     * Test Max Bookings per unit option with existing bookings
     */
    function test_max_bookings_per_unit_with_existing_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_max_bookings_per_unit( 3 );


        $booking_1 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 10' ),
                                                      ) );

        $booking_2 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 07' ),
                                                      ) );
        $booking_3 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 08' ),
                                                      ) );
        $booking_4 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 11' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 15' ),
                                                      ) );

        $booking_5 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 15' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 16' ),
                                                      ) );

        $booking_6 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 16' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 18' ),
                                                      ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 10' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 07' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 07' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 08' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 10' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 09' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 20' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $booking_7 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 12' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 15' ),
                                                      ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 09' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 20' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $booking_8 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 12' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 14' ),
                                                      ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 09' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 20' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        // Test Unlimited (Max per block = 0) | Ticket 121554
        $product->set_max_bookings_per_unit( 0 );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $common_args = array(
            'product_id' => $product->get_id(),
            'from'       => BK_Helper_Date::create_next_year_date( 'Jan 12' ),
            'to'         => BK_Helper_Date::create_next_year_date( 'Jan 14' ),
        );

        $booking_9  = $this->create_and_store_booking( $common_args );
        $booking_10 = $this->create_and_store_booking( $common_args );
        $booking_11 = $this->create_and_store_booking( $common_args );
        $booking_12 = $this->create_and_store_booking( $common_args );
        $booking_13 = $this->create_and_store_booking( $common_args );
        $booking_14 = $this->create_and_store_booking( $common_args );
        $booking_15 = $this->create_and_store_booking( $common_args );

        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test Count persons as Bookings
     */
    function test_count_persons_as_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_max_bookings_per_unit( 30 );
        $product->set_enable_people( true );
        $product->set_count_people_as_separate_bookings( true );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );

        $booking_1 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => $from,
                                                          'to'         => $to,
                                                          'persons'    => 10
                                                      ) );

        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to, 'persons' => 10 ) ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to, 'persons' => 20 ) ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to, 'persons' => 21 ) ) );
    }

    /**
     * Test Count persons as Bookings
     */
    function test_count_persons_as_bookings_with_min_persons_set() {
        # Ticket 131260
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_max_bookings_per_unit( 20 );
        $product->set_enable_people( true );
        $product->set_count_people_as_separate_bookings( true );
        $product->set_minimum_number_of_people( 15 );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 02' );

        $booking_1 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => $from,
                                                          'to'         => $to,
                                                          'persons'    => 15
                                                      ) );

        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to, 'persons' => 5 ) ) );
    }


    /**
     * Test rule overriding
     */
    function test_rule_overriding() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
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
                                                  'type'         => 'month',
                                                  'from'         => '7',
                                                  'to'           => '7',
                                                  'bookable'     => 'yes',
                                                  'days_enabled' => 'yes',
                                                  'days'         => array(
                                                      1 => 'yes',
                                                      2 => 'yes',
                                                      3 => 'yes',
                                                      4 => 'yes',
                                                      5 => 'yes',
                                                      6 => 'yes',
                                                      7 => 'no',
                                                  ),
                                              ),
                                          ) );


        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jul 01' ) );

        $to = strtotime( '+1 day', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $to = strtotime( '+3 days', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $to = strtotime( 'next sunday', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $to = strtotime( 'next monday', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    public function test_specific_days_availability() {

        // ticket 140213

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( array( array(
                                                     'type'         => 'month',
                                                     'from'         => '1',
                                                     'to'           => '12',
                                                     'bookable'     => 'yes',
                                                     'days_enabled' => 'yes',
                                                     'days'         => array(
                                                         1 => 'no',
                                                         2 => 'no',
                                                         3 => 'no',
                                                         4 => 'no',
                                                         5 => 'yes',
                                                         6 => 'yes',
                                                         7 => 'yes',
                                                     ),
                                                 ), ) );


        $from = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( '+1 day', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to, 'exclude_time' => true ) ) );

        $from = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( '+1 day', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to, 'exclude_time' => true ) ) );
    }
}
