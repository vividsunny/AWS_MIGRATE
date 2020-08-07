<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Day
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Day extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '60.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 05' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 05' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 15' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '612.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Aug 14' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 16' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '160.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Aug 15' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 17' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '170.00', $price );


        $from  = BK_Helper_Date::create_next_year_date( 'Aug 28' );
        $to    = BK_Helper_Date::create_next_year_date( 'Sep 03' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '312.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Sep 06' );
        $to    = BK_Helper_Date::create_next_year_date( 'Sep 16' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '520.00', $price );


        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges( array( 'day', 'block' ) ) );


        $from  = strtotime( 'next friday', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to    = strtotime( 'next monday', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '170.00', $price );

        $from  = strtotime( 'next saturday', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to    = strtotime( 'next tuesday', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '175.00', $price );


        $from  = strtotime( 'next monday', BK_Helper_Date::create_next_year_date( 'Jan 01' ) );
        $to    = strtotime( '+10 days', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '435.00', $price ); // 10[base] + ( (50 * 8 + 55 * 2[Sat Sun]) / 1.2 [block discount] )
    }

    /**
     * Test custom daily booking with persons
     */
    function test_custom_daily_booking_with_persons() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );


        $args  = array(
            'from'    => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'      => BK_Helper_Date::create_next_year_date( 'Jan 04' ),
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
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 02' ),
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
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
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
            'from'         => BK_Helper_Date::create_next_year_date( 'Jun 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jun 05' ),
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
     * Test custom daily booking with person types
     */
    function test_extra_price_per_person() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( false );
        $product->set_multiply_fixed_base_fee_by_number_of_people( false );
        $product->set_enable_people_types( true );
        $product->set_people_types( array(
                                        $adult->ID    => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                        $teenager->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '' ),
                                        $child->ID    => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '0', ),
                                    ) );

        $product->set_extra_price_per_person( '10.00' );
        $product->set_extra_price_per_person_greater_than( 3 );

        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 02' ),
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
        $this->assertEquals( '60.00', $price ); // Base Fee 10.00 + Base Price 50.00 [Child Free]


        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 3 ),
            array( 'id' => $teenager->ID, 'number' => 2 ),
            array( 'id' => $child->ID, 'number' => 3 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '80.00', $price ); // Child Free + Base Fee 10.00 + Base Price 50.00 + 2 Extra People 20.00 [Child Free]


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 )
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '210.00', $price ); // Base Fee 10.00 + ( Base Price 50.00 * 4 )

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $teenager->ID, 'number' => 1 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '210.00', $price ); // Base Fee 10.00 + ( Base Price 50.00  * 4 ) [Child Free]


        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 3 ),
            array( 'id' => $teenager->ID, 'number' => 2 ),
            array( 'id' => $child->ID, 'number' => 3 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '290.00', $price ); // Child Free + Base Fee 10.00 + ( ( Base Fee 50.00 + 2 Extra People 20.00 ) * 4 ) + [Child Free]
    }
}
