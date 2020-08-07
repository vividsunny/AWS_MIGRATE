<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Minute
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Minute extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom minutely booking
     */
    function test_custom_hourly_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 15 );
        $product->set_duration_unit( 'minute' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_fixed_base_fee( '5.00' );
        $product->set_base_price( '10.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 10:15' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '15.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01 11:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '45.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 01 12:30' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '126.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Aug 15 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 15 10:15' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '30.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Sep 15 10:00' );
        $to    = BK_Helper_Date::create_next_year_date( 'Sep 15 11:00' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '49.00', $price );


        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges( array( 'day', 'block' ) ) );


        $from  = strtotime( 'next monday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next monday 10:30', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '88.33', $price );


        $from  = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next saturday 08:30', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '40.00', $price ); // 10[base] + (15 * 2)

        $from  = strtotime( 'next saturday 08:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( 'next saturday 10:30', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '135.00', $price ); // 10[base] + ( (15 * 10) / 1.2 [block discount] )

        $from  = strtotime( 'next friday 23:30', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( '+1 hours', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '55.00', $price ); // 5[base] + (10 * 2) [fri] + (15 * 2) [sat]

        $from  = strtotime( 'next friday 22:00', BK_Helper_Date::create_next_year_date( 'Feb 01' ) );
        $to    = strtotime( '+150 minutes', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '96.67', $price ); // 5[base] + ( ( (10 * 8) [fri] + (15 * 2) [sat] ) / 1.2 [block discount])
    }

    /**
     * Test custom minutely booking with persons
     */
    function test_custom_minutely_booking_with_persons() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 15 );
        $product->set_duration_unit( 'minute' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );


        $args  = array(
            'from'    => BK_Helper_Date::create_next_year_date( 'Jan 01 10:00' ),
            'to'      => BK_Helper_Date::create_next_year_date( 'Jan 01 10:45' ),
            'persons' => 2,
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '320.00', $price ); // ( 10.00 + (50.00 * 3) ) * 2

        $args[ 'persons' ] = 5;
        $price             = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '800.00', $price ); // ( 10.00 + (50.00 * 3) ) * 5
    }

    /**
     * Test custom minutely booking with person types
     */
    function test_custom_minutely_booking_with_person_types() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 30 );
        $product->set_duration_unit( 'minute' );
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
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 01 10:30' ),
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
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 01 12:00' ),
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
            'to'           => BK_Helper_Date::create_next_year_date( 'Jun 01 12:00' ),
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
}
