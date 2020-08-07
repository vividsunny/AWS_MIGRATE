<?php
/**
 * Class BK_Tests_Booking_Product_Costs_Extra_Costs
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Booking_Product_Costs_Extra_Costs extends BK_Unit_Test_Case_With_Store {
    /**
     * Test custom daily booking
     */
    function test_custom_daily_booking() {
        $product = $this->create_and_store_booking_product();
        $product->set_duration_type( 'customer' );
        $product->set_duration( 1 );
        $product->set_duration_unit( 'day' );
        $product->set_fixed_base_fee( '10.00' );
        $product->set_base_price( '50.00' );
        $product->set_enable_people( true );

        $cleaning_fee_post = $this->create_and_store_extra_cost( 'Cleaning Fee' );
        $tax_post          = $this->create_and_store_extra_cost( 'Tax' );

        $cleaning_fee = yith_wcbk_product_extra_cost( array( 'id' => $cleaning_fee_post->ID ) );
        $cleaning_fee->set_cost( '40.00' );

        $tax = yith_wcbk_product_extra_cost( array( 'id' => $tax_post->ID ) );
        $tax->set_cost( '3.00' );
        $tax->set_multiply_by_duration( true );
        $tax->set_multiply_by_number_of_people( true );

        $product->set_extra_costs( array( $cleaning_fee, $tax ) );


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 02' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '103.00', $price ); // 10.00 + 50.00 + Cleaning Fee 40.00 + Tax 3.00


        $from  = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to    = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $price = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to ) ) );
        $this->assertEquals( '209.00', $price ); // 10.00 + ( 50.00 * 3 ) + Cleaning Fee 40.00 + ( Tax 3.00 * 3 )


        $from   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to     = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $people = 2;
        $price  = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to, 'persons' => $people ) ) );
        $this->assertEquals( '218.00', $price ); //  10.00 + ( 50.00 * 3 ) + [ ( Tax 3.00 * 3 ) ] * 2 + Cleaning Fee 40.00


        $product->set_multiply_fixed_base_fee_by_number_of_people( true );
        $product->set_multiply_base_price_by_number_of_people( true );

        $from   = BK_Helper_Date::create_next_year_date( 'Jan 01' );
        $to     = BK_Helper_Date::create_next_year_date( 'Jan 04' );
        $people = 2;
        $price  = BK_Helper_Prices::format_price( $product->calculate_price( array( 'from' => $from, 'to' => $to, 'persons' => $people ) ) );
        $this->assertEquals( '378.00', $price ); //  [ 10.00 + ( 50.00 * 3 ) + ( Tax 3.00 * 3 ) ] * 2 + Cleaning Fee 40.00

    }
}
