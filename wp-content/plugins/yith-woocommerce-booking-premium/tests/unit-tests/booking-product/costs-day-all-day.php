<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Day_All_Day
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Day_All_Day extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_full_day( true );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '60.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Aug 14' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 15' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '160.00', $price );
    }

    /**
     * Test custom daily booking with persons
     */
    function test_custom_daily_booking_with_persons() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_full_day( true );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );

        $args  = array(
            'from'    => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'      => BK_Helper_Date::create_next_year_date( 'Jan 03' ),
            'persons' => 2,
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '320.00', $price ); // ( 10.00 + (50.00 * 3) ) * 2

        $args[ 'persons' ] = 5;
        $price             = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '800.00', $price ); // ( 10.00 + (50.00 * 3) ) * 5
    }

    /**
     * Test custom daily booking with person types
     */
    function test_custom_daily_booking_with_person_types() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_full_day( true );
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
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 04' ),
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
    }
}
