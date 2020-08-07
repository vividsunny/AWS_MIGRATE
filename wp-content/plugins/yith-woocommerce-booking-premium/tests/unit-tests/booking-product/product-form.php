<?php
/**
 * Class BK_Tests_Booking_Product_Form
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

/**
 * Sample test case.
 */
class BK_Tests_Booking_Product_Form extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $ajax          = YITH_WCBK_AJAX::get_instance();
        $ajax->testing = true;

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        $product->save();

        $booking_data = $ajax->get_booking_data( array() );
        $this->assertArrayHasKey( 'error', $booking_data );

        $request      = array(
            'product_id' => $product->get_id(),
        );
        $booking_data = $ajax->get_booking_data( $request );
        $this->assertArrayHasKey( 'error', $booking_data );


        $request = array(
            'product_id' => $product->get_id(),
            'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' ),
            'to'         => BK_Helper_Date::create_next_year_date( 'Jan 02', 'date' )
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertArrayHasKey( 'is_available', $booking_data );
        $this->assertArrayHasKey( 'price', $booking_data );
        $this->assertArrayHasKey( 'message', $booking_data );
        $this->assertTrue( $booking_data[ 'is_available' ] );

        $request = array(
            'product_id' => $product->get_id(),
            'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' ),
            'duration'   => '1'
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertTrue( $booking_data[ 'is_available' ] );
    }

    /**
     * Test custom daily booking all day
     */
    function test_custom_daily_booking_all_day() {
        $ajax          = YITH_WCBK_AJAX::get_instance();
        $ajax->testing = true;

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_full_day( true );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        $product->save();

        $request = array(
            'product_id' => $product->get_id(),
            'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' ),
            'to'         => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' )
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertTrue( $booking_data[ 'is_available' ] );

        $request = array(
            'product_id' => $product->get_id(),
            'from'       => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' ),
            'duration'   => '1'
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertTrue( $booking_data[ 'is_available' ] );
    }


    /**
     * Test custom daily booking with count people as separate bookings
     */
    function test_custom_daily_booking_with_count_people_as_separate_bookings() {
        $ajax          = YITH_WCBK_AJAX::get_instance();
        $ajax->testing = true;

        $adult = $this->create_and_store_person_type( 'Adult' );
        $child = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_availability_rules( BK_Helper_Availability_Ranges::create_daily_availability_ranges() );
        $product->set_max_bookings_per_unit( 1 );
        $product->set_enable_people( true );
        $product->set_enable_people_types( true );
        $product->set_count_people_as_separate_bookings( true );
        $product->set_people_types( array(
                                        $adult->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                        $child->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                    ) );
        $product->save();

        $request = array(
            'product_id'   => $product->get_id(),
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01', 'date' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 02', 'date' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 ),
                array( 'id' => $child->ID, 'number' => 0 ),
            )
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertArrayHasKey( 'is_available', $booking_data );
        $this->assertArrayHasKey( 'price', $booking_data );
        $this->assertArrayHasKey( 'message', $booking_data );
        $this->assertTrue( $booking_data[ 'is_available' ] );


        $request[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 1 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );

        $booking_data = $ajax->get_booking_data( $request );
        $this->assertArrayHasKey( 'is_available', $booking_data );
        $this->assertArrayHasKey( 'price', $booking_data );
        $this->assertArrayHasKey( 'message', $booking_data );
        $this->assertFalse( $booking_data[ 'is_available' ] );
    }
}
