<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Person_Rules_Hotel
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Person_Rules_Hotel extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking with person types
     */
    function test_custom_daily_booking_with_person_types() {
        list( $product, $adult, $child ) = $this->create_product_and_person_types();

        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 02' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 1 )
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '100.00', $price );

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '150.00', $price );


        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $child->ID, 'number' => 1 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '160.00', $price ); // Adults 150.00 + Child 10.00


        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 2 ),
            array( 'id' => $child->ID, 'number' => 2 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '165.00', $price ); // Adults 150.00 + Children 15.00

        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 3 ),
            array( 'id' => $child->ID, 'number' => 2 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '205.00', $price ); // Adults 190.00 + Children 15.00


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jan 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jan 05' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 2 ),
                array( 'id' => $child->ID, 'number' => 1 ),
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '640.00', $price ); // ( Adults 150.00 + Child 10.00 ) * 4


        $args[ 'person_types' ] = array(
            array( 'id' => $adult->ID, 'number' => 3 ),
            array( 'id' => $child->ID, 'number' => 2 ),
        );
        $price                  = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        $this->assertEquals( '820.00', $price ); // ( Adults 190.00 + Children 15.00 ) * 4
    }


    /**
     * create product with this settings:
     * Costs
     * 1st Adult: 100$ /day
     * 2nd Adult: +50$ /day
     * 3rd Adult: +40$ /day
     * 1st child: +10 $ /day
     * 2nd child: +5 $ /day
     *
     * @return array
     */
    function create_product_and_person_types() {
        $adult = $this->create_and_store_person_type( 'Adult' );
        $child = $this->create_and_store_person_type( 'Child' );

        $cost_ranges = array(
            array(
                'type'                => 'person-type-' . $adult->ID,
                'from'                => '2',
                'to'                  => '3',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'add',
                'block_cost'          => '50',
            ),
            array(
                'type'                => 'person-type-' . $adult->ID,
                'from'                => '3',
                'to'                  => '3',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'add',
                'block_cost'          => '40',
            ),
            array(
                'type'                => 'person-type-' . $child->ID,
                'from'                => '1',
                'to'                  => '2',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'add',
                'block_cost'          => '10',
            ),
            array(
                'type'                => 'person-type-' . $child->ID,
                'from'                => '2',
                'to'                  => '2',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'add',
                'block_cost'          => '5',
            ),
        );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( $cost_ranges );
        $product->set_fixed_base_fee( '0.00' );
        $product->set_base_price( '100.00' );

        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( false );
        $product->set_multiply_fixed_base_fee_by_number_of_people( false );
        $product->set_enable_people_types( true );

        $product->set_people_types( array(
                                        $adult->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                        $child->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', )
                                    ) );

        return array( $product, $adult, $child );
    }
}
