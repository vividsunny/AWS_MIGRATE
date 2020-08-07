<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Day_Discounts
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Day_Discounts extends BK_Unit_Test_Case_With_Store {
    /**
     * Test weekly discount
     */
    function test_weekly_discount() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_weekly_discount( '20.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '110.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 03' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 08' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '570.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 7 ) - 20% )

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 10' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '770.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 7 ) - 20% ) + ( 100.00 * 2 )

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 18' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '1430.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 14 ) - 20% ) + ( 100.00 * 3 )

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 29' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '2250.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 28 ) - 20% )

        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Feb 04' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '2850.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 28 ) - 20% ) + ( 100.00 * 6 )
    }

    /**
     * Test monthly discount
     */
    function test_monthly_discount() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_monthly_discount( '20.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '110.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 03' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 30' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '2910.00', $price ); // Base Fee 10.00 + ( 100.00 * 29 )

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 01' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '2410.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 30 ) - 20% )

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 11' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '3410.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 30 ) - 20% ) + ( 100.00 * 10 )

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 30' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '5310.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 30 ) - 20% ) + ( 100.00 * 29 )

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 31' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '4810.00', $price ); // Base Fee 10.00 + ( ( 100.00 * 30 ) - 20% ) + ( ( 100.00 * 30 ) - 20% )
    }

    /**
     * Test weekly and monthly discount
     */
    function test_weekly_and_monthly_discount() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_weekly_discount( '10.00' );
        $product->set_monthly_discount( '20.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '110.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 03' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 01' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '2186.00', $price ); // Base Fee 10.00 +  { [ ( 100.00 * 28 - 10% ) + 100.00 * 2 ] - 20% }

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jul 10' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '3016.00', $price ); // Base Fee 10.00 +  { [ ( 100.00 * 28 - 10% ) + 100.00 * 2 ] - 20% } + ( 100.00 * 7 - 10% ) + ( 100.00 * 2 )
    }

    /**
     * Test weekly and monthly discount with people
     */
    function test_weekly_and_monthly_discount_with_people() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_weekly_discount( '10.00' );
        $product->set_monthly_discount( '20.00' );
        $product->set_enable_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );
        $product->set_multiply_fixed_base_fee_by_number_of_people( true );
        $product->set_enable_people_types( true );
        $product->set_people_types( array(
                                        $adult->ID    => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '', ),
                                        $teenager->ID => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '50' ),
                                        $child->ID    => array( 'enabled' => 'yes', 'base_cost' => '', 'block_cost' => '10', ),
                                    ) );


        $args  = array(
            'from'         => BK_Helper_Date::create_next_year_date( 'Jun 01' ),
            'to'           => BK_Helper_Date::create_next_year_date( 'Jul 10' ),
            'person_types' => array(
                array( 'id' => $adult->ID, 'number' => 2 ),
                array( 'id' => $teenager->ID, 'number' => 1 ),
                array( 'id' => $child->ID, 'number' => 1 ),
            ),
        );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( $args ) );
        // [Adults      6032.00] [[ Base Fee 10.00 +  { [ ( 100.00 * 28 - 10% ) + 100.00 * 2 ] - 20% } + ( 100.00 * 7 - 10% ) + ( 100.00 * 2 ) ]] * 2   +
        // [Teenager    1513.00] [[ Base Fee 10.00 +  { [ ( 50.00 * 28 - 10% ) + 50.00 * 2 ] - 20% } + ( 50.00 * 7 - 10% ) + ( 50.00 * 2 ) ]]           +
        // [Child        310.60] [[ Base Fee 10.00 +  { [ ( 10.00 * 28 - 10% ) + 10.00 * 2 ] - 20% } + ( 10.00 * 7 - 10% ) + ( 10.00 * 2 ) ]]
        $this->assertEquals( '7855.60', $price );
    }

    function test_weekly_discounts_in_combination_with_cost_rules() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( BK_Helper_Prices::create_daily_cost_ranges() );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_weekly_discount( '20.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'May 30' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 06' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        // [ Base Fee ] 10.00
        // [ Week ] May 100.00 * 2 | Jun ( 100.00 * 5 ) * 1.2
        // Total = Base Fee + [ ( Week ) - 20% ]
        $this->assertEquals( '650.00', $price );


        $from  = BK_Helper_Date::create_next_year_date( 'May 30' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 08' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        // [ Base Fee ] 10.00
        // [ Week ] May 100.00 * 2 | Jun ( 100.00 * 5 ) * 1.2
        // [ Additional Days ] ( 100.00 * 2 ) * 1.2
        // Total = Base Fee + [ ( Week ) - 20% ] + Additional Days
        $this->assertEquals( '890.00', $price );


        $from  = BK_Helper_Date::create_next_year_date( 'Jul 29' );
        $to    = BK_Helper_Date::create_next_year_date( 'Aug 21' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        // [ Base Fee ] Jul 10.00 * 1.2                                                              12.00
        // [ Week-1 ] Jul ( 100.00 * 3 ) * 1.2 | Aug 100.00 * 4                 Jul 29 - Aug 5      760.00
        // [ Week-2 ] 100.00 * 7                                                Aug 5  - Aug 12     700.00
        // [ Week-3 ] ( 100.00 * 6 ) + Aug-15 ( 100 * 2 )                       Aug 12 - Aug 19     800.00
        // [ Additional Days ] ( 100.00 * 2 )                                   Aug 20 - Aug 21     200.00
        // Total = Base Fee + [ ( Week-1 ) - 20% ] + [ ( Week-2 ) - 20% ] + [ ( Week-3 ) - 20% ] + Additional Days
        $this->assertEquals( '2020.00', $price );

    }

    /**
     * Test last minute discount
     */
    function test_last_minute_discount() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '100.00' );
        $product->set_last_minute_discount( 20 );
        $product->set_last_minute_discount_days_before_arrival( 2 );


        $from  = strtotime( 'now' );
        $to    = strtotime( '+1 day', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '88.00', $price ); // 110.00 - 20%

        $from  = strtotime( '+1 day' );
        $to    = strtotime( '+1 day', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '88.00', $price ); // 110.00 - 20%

        $from  = strtotime( '+2 day' );
        $to    = strtotime( '+1 day', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '88.00', $price ); // 110.00 - 20%

        $from  = strtotime( '+2 day' );
        $to    = strtotime( '+3 day', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '248.00', $price ); // ( 100.00 x 3 + 10.00) - 20%

        $from  = strtotime( '+3 days' );
        $to    = strtotime( '+1 day', $from );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '110.00', $price ); // No discount

    }
}
