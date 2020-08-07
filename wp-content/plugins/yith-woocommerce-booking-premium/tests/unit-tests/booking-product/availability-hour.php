<?php
/**
 * Class BK_Tests_Booking_Product_Availability_Hour
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Availability_Hour extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom hourly booking
     */
    function test_custom_hourly_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 00:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 24:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to   = strtotime( 'next monday 16:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

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


        $from = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Apr 01' ) );
        $to   = strtotime( 'next monday 09:00', BK_Helper_Date::create_next_year_date( 'Apr 01' ) );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Apr 01' ) );
        $to   = strtotime( 'next saturday 09:00', BK_Helper_Date::create_next_year_date( 'Apr 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test custom hourly booking multiple hours
     */
    function test_custom_hourly_booking_multiple_hours() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 16:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test fixed hourly booking
     */
    function test_fixed_hourly_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 15:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test fixed hourly booking multiple hours
     */
    function test_fixed_hourly_booking_multiple_hours() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );


        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 3 );
        $product->set_duration_unit( 'hour' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_hourly_availability_ranges() );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 16:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    /**
     * Test availability with existing bookings
     */
    function test_with_existing_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->save();


        $booking = $this->create_and_store_booking( array(
                                                        'product_id' => $product->get_id(),
                                                        'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
                                                        'to'         => BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' ),
                                                    ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 04 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 05 12:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 05 13:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

    }

    /**
     * Test Max Bookings per unit option with existing bookings
     */
    function test_max_bookings_per_unit_with_existing_bookings() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_max_bookings_per_unit( 2 );
        $product->save();

        $booking_1 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' ),
                                                      ) );

        $booking_2 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' ),
                                                      ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }


    /**
     * Test availability over midnight
     */
    function test_availability_over_midnight() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );

        $availability_ranges = array(
            array(
                'type'         => 'month',
                'from'         => '1',
                'to'           => '12',
                'bookable'     => 'no',
                'days_enabled' => 'no',
                'days'         => array(),
            ),
            array(
                'type'          => 'month',
                'from'          => '1',
                'to'            => '12',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled' => 'yes',
                'days'          => array(
                    1 => 'no',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'yes',
                    7 => 'yes',
                ),
                'day_time_from' => array(
                    1 => '02:00',
                    2 => '20:00',
                    3 => '20:00',
                    4 => '20:00',
                    5 => '20:00',
                    6 => '00:00',
                    7 => '20:00',
                ),
                'day_time_to'   => array(
                    1 => '00:00',
                    2 => '00:00',
                    3 => '00:00',
                    4 => '00:00',
                    5 => '00:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
            ),
            array(
                'type'          => 'month',
                'from'          => '1',
                'to'            => '12',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled' => 'yes',
                'days'          => array(
                    1 => 'yes',
                    2 => 'disabled',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'yes',
                    7 => 'yes',
                ),
                'day_time_from' => array(
                    1 => '00:00',
                    2 => '00:00',
                    3 => '00:00',
                    4 => '00:00',
                    5 => '00:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
                'day_time_to'   => array(
                    1 => '02:00',
                    2 => '00:00',
                    3 => '02:00',
                    4 => '02:00',
                    5 => '02:00',
                    6 => '00:00',
                    7 => '02:00',
                ),
            ),

        );

        $availability_ranges = array_map( 'yith_wcbk_availability_rule', $availability_ranges );
        $product->set_availability_rules( $availability_ranges );
        $product->save();


        $from = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( 'next monday 16:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next monday 20:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( 'tomorrow midnight ', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next sunday 20:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( 'tomorrow midnight ', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next sunday 20:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( 'tomorrow 02:00 ', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next wednesday 20:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( 'tomorrow 04:00 ', $from );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = strtotime( 'next friday 20:00', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to   = strtotime( '+2 days 02:00 ', $from );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }

    public function test_disable_specific_time_ranges() {
        // ticket #136861

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );

        $availability_ranges = array(
            array(
                'type'         => 'month',
                'from'         => '1',
                'to'           => '12',
                'bookable'     => 'no',
                'days_enabled' => 'no',
                'days'         => array(),
            ),
            array(
                'type'          => 'month',
                'from'          => '1',
                'to'            => '12',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled' => 'yes',
                'days'          => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'yes',
                    7 => 'yes',
                ),
                'day_time_from' => array(
                    1 => '08:00',
                    2 => '08:00',
                    3 => '08:00',
                    4 => '08:00',
                    5 => '08:00',
                    6 => '08:00',
                    7 => '08:00',
                ),
                'day_time_to'   => array(
                    1 => '09:00',
                    2 => '09:00',
                    3 => '09:00',
                    4 => '09:00',
                    5 => '09:00',
                    6 => '09:00',
                    7 => '09:00',
                ),
            ),
            array(
                'type'          => 'month',
                'from'          => '1',
                'to'            => '12',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled' => 'yes',
                'days'          => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'yes',
                    7 => 'yes',
                ),
                'day_time_from' => array(
                    1 => '12:00',
                    2 => '12:00',
                    3 => '12:00',
                    4 => '12:00',
                    5 => '12:00',
                    6 => '12:00',
                    7 => '12:00',
                ),
                'day_time_to'   => array(
                    1 => '13:00',
                    2 => '13:00',
                    3 => '13:00',
                    4 => '13:00',
                    5 => '13:00',
                    6 => '13:00',
                    7 => '13:00',
                ),
            ),

            array(
                'type'          => 'month',
                'from'          => '6',
                'to'            => '6',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled' => 'yes',
                'days'          => array(
                    1 => 'no',
                    2 => 'no',
                    3 => 'no',
                    4 => 'no',
                    5 => 'no',
                    6 => 'no',
                    7 => 'no',
                ),
                'day_time_from' => array(
                    1 => '12:00',
                    2 => '12:00',
                    3 => '12:00',
                    4 => '12:00',
                    5 => '12:00',
                    6 => '12:00',
                    7 => '12:00',
                ),
                'day_time_to'   => array(
                    1 => '13:00',
                    2 => '13:00',
                    3 => '13:00',
                    4 => '13:00',
                    5 => '13:00',
                    6 => '13:00',
                    7 => '13:00',
                ),
            ),

        );

        $product->set_availability_rules( $availability_ranges );
        $product->save();


        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 08:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 09:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jun 01 08:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 01 09:00' );
        $this->assertTrue( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );

        $from = BK_Helper_Date::create_next_year_date( 'Jun 01 12:00' );
        $to   = BK_Helper_Date::create_next_year_date( 'Jun 01 12:00' );
        $this->assertFalse( $product->is_available( array( 'from' => $from, 'to' => $to ) ) );
    }


    /**
     * Test day disabled issue when booking on midnight
     * ticket #145474
     */
    function test_day_disabled_issue_when_booking_on_midnight() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->save();

        $booking_1 = $this->create_and_store_booking( array(
                                                          'product_id' => $product->get_id(),
                                                          'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01 00:00' ),
                                                          'to'         => BK_Helper_Date::create_next_year_date( 'Jan 01 01:00' ),
                                                      ) );

        $next_year = BK_Helper_Date::create_next_year_date( 'Jan 01 00:00', 'Y' );

        $not_available_dates = $product->get_not_available_dates( $next_year, 1, $next_year, 2, 'day', false, false, false );

        $this->assertFalse( in_array( "{$next_year}-01-01", $not_available_dates ) );
    }
}
