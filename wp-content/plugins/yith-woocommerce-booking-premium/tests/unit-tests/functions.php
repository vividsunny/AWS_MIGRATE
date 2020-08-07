<?php
/**
 * Class BK_Tests_Booking_Product_Availability
 *
 * @package YITH Booking and Appointment for WooCommerce Premium
 */

class BK_Tests_Functions extends BK_Unit_Test_Case_With_Store {
    /**
     * Test person type parsing functions
     * yith_wcbk_booking_person_types_to_list and yith_wcbk_booking_person_types_to_id_number_array
     */
    function test_person_types_parsing_functions() {
        $adult    = $this->create_and_store_person_type( 'Adult' );
        $teenager = $this->create_and_store_person_type( 'Teenager' );
        $child    = $this->create_and_store_person_type( 'Child' );

        $original_person_types_id_number_array = array( $adult->ID => 2, $teenager->ID => 1, $child->ID => 0 );

        $person_types_list = yith_wcbk_booking_person_types_to_list( $original_person_types_id_number_array );

        $this->assertEquals( $person_types_list, array(
            array( 'id' => $adult->ID, 'number' => 2, 'title' => 'Adult' ),
            array( 'id' => $teenager->ID, 'number' => 1, 'title' => 'Teenager' ),
            array( 'id' => $child->ID, 'number' => 0, 'title' => 'Child' ),
        ) );

        $person_types_id_number_array = yith_wcbk_booking_person_types_to_id_number_array( $person_types_list );

        $this->assertEquals( $person_types_id_number_array, $original_person_types_id_number_array );
    }
}
