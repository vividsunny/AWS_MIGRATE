<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Hour
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Hour extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom hourly booking
     */
    function test_custom_hourly_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '60.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01 8:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 01 18:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '612.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Aug 15 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 15 11:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '120.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Sep 15 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Sep 15 15:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '265.00', $price );


        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges( array( 'day', 'block' ) ) );


        $from  = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next monday 18:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '426.67', $price );


        $from  = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next saturday 10:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '125.00', $price ); // 15[base] + (55 * 2)

        $from  = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next saturday 18:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '473.33', $price ); // 15[base] + ( (55 * 10) / 1.2 [block discount] )

        $from  = strtotime( 'next friday 22:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( '+4 hours', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '220.00', $price ); // 10[base] + (50 * 2) [fri] + (55 * 2) [sat]

        $from  = strtotime( 'next friday 22:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( '+10 hours', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '460.00', $price ); // 10[base] + ( ( (50 * 2) [fri] + (55 * 8) [sat] ) / 1.2 [block discount])
    }

    function test_custom_hourly_booking_with_time_slot_price_rules() {
        // ticket 140396
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_price_rules( array(
                                       array(
                                           'type'                => 'time',
                                           'from'                => '10:00',
                                           'to'                  => '15:00',
                                           'base_price_operator' => 'mul',
                                           'base_price'          => '1.2',
                                           'base_fee_operator'   => 'mul',
                                           'base_fee'            => '1.2',
                                       ),
                                       array(
                                           'type'                => 'time',
                                           'from'                => '17:00',
                                           'to'                  => '08:00',
                                           'base_price_operator' => 'mul',
                                           'base_price'          => '1.5',
                                           'base_fee_operator'   => 'mul',
                                           'base_fee'            => '1.5',
                                       ),
                                   ) );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 09:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '60.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '72.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 18:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 19:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '90.00', $price );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 01:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 02:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '90.00', $price );
    }

    /**
     * Test custom hourly booking with persons
     */
    function test_custom_hourly_booking_with_persons() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );


        $args  = array(
            'from'    => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
            'to'      => BK_Helper_Date::create_next_year_date( 'Jan 01 13:00' ),
            'persons' => 2,
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '320.00', $price ); // ( 10.00 + (50.00 * 3) ) * 2

        $args[ 'persons' ] = 5;
        $price             = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '800.00', $price ); // ( 10.00 + (50.00 * 3) ) * 5
    }

    /**
     * Test custom hourly booking with person types
     */
    function test_custom_hourly_booking_with_person_types() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'hour' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );
        $product->set_enable_people_types( true );
        $product->set_people_types( array(
                                        $adult->ID    => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                        $teenager->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '30', ),
                                        $child->ID    => array( 'enabled' => 'yes', 'base_cost' => '0', 'block_cost' => '10', ),
                                    ) );


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 )
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '60.00', $price );

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $teenager->ID, 'number' => 1 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '170.00', $price ); // Adults 120.00 + Teenager 40.00 + Child 10


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 01 14:00' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 )
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '210.00', $price ); // 10.00 + ( 50.00 * 4 )

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $teenager->ID, 'number' => 1 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '590.00', $price ); // Adults 420.00 + Teenager 130.00 + Child 40


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jun 01 10:00' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jun 01 14:00' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 )
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '252.00', $price ); // ( 10.00 + ( 50.00 * 4 ) ) * 1.2

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $teenager->ID, 'number' => 1 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '708.00', $price ); // ( Adults 420.00 + Teenager 130.00 + Child 40 ) * 1.2
    }

    /**
     * Test custom hourly booking
     */
    function test_fixed_duration_hourly_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'fixed' );
        $product->set_duration( 2 );
        $product->set_duration_unit( 'hour' );
        $product->set_base_price( '100.00' );

        // ticket #139394
        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to, 'duration' => 2 ) ) );
        $this->assertEquals( '100.00', $price );
    }
}
