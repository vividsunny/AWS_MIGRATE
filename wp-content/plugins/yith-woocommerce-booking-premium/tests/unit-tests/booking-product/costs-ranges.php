<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Ranges
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Ranges extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking with person types
     */
    function test_cost_rules_with_percentages() {
        $cost_ranges = array(
            array(
                'type'                => 'month',
                'from'                => '6',
                'to'                  => '7',
                'base_cost_operator'  => 'add-percentage',
                'base_cost'           => '50',
                'block_cost_operator' => 'add-percentage',
                'block_cost'          => '50',
            ),
            array(
                'type'                => 'month',
                'from'                => '9',
                'to'                  => '10',
                'base_cost_operator'  => 'sub-percentage',
                'base_cost'           => '10',
                'block_cost_operator' => 'sub-percentage',
                'block_cost'          => '10',
            ),
        );

        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_price_rules( $cost_ranges );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '200.00' );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '210.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Jun 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jun 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '315.00', $price );

        $from  = BK_Helper_Date::create_next_year_date( 'Sep 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Sep 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '189.00', $price );
    }
}
