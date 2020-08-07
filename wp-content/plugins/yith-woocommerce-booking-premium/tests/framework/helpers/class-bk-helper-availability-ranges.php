<?php

/**
 * Class BK_Helper_Availability_Ranges.
 *
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Availability_Ranges {
    /**
     * Create daily availability ranges.
     *
     * @return YITH_WCBK_Availability_Rule[]
     */
    public static function create_daily_availability_ranges() {
        $ranges = array(
            array(
                'type'         => 'month',
                'from'         => '6',
                'to'           => '6',
                'bookable'     => 'no',
                'days_enabled' => 'no',
                'days'         => array(),
            ),
            array(
                'type'         => 'month',
                'from'         => '7',
                'to'           => '7',
                'bookable'     => 'no',
                'days_enabled' => 'yes',
                'days'         => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'no',
                    7 => 'no',
                ),
            ),
            array(
                'type'         => 'custom',
                'from'         => BK_Helper_Date::create_next_year_date( 'Aug 15', 'date' ),
                'to'           => BK_Helper_Date::create_next_year_date( 'Aug 15', 'date' ),
                'bookable'     => 'no',
                'days_enabled' => 'no',
            ),
            array(
                'type'         => 'custom',
                'from'         => BK_Helper_Date::create_next_year_date( 'Sep 01', 'date' ),
                'to'           => BK_Helper_Date::create_next_year_date( 'Sep 15', 'date' ),
                'bookable'     => 'yes',
                'days_enabled' => 'yes',
                'days'         => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'no',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'yes',
                    7 => 'no',
                ),
            )
        );

        return array_map( 'yith_wcbk_availability_rule', $ranges );
    }


    /**
     * Create hourly availability ranges.
     *
     * @return YITH_WCBK_Availability_Rule[]
     */
    public static function create_hourly_availability_ranges() {
        $ranges = array(
            array(
                'type'         => 'month',
                'from'         => '7',
                'to'           => '8',
                'bookable'     => 'no',
                'days_enabled' => 'no',
                'days'         => array(),
            ),
            array(
                'type'          => 'month',
                'from'          => '2',
                'to'            => '3',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'days'          => array(
                    1 => 'no',
                    2 => 'no',
                    3 => 'no',
                    4 => 'no',
                    5 => 'no',
                    6 => 'no',
                    7 => 'no',
                ),
            ),
            array(
                'type'          => 'month',
                'from'          => '2',
                'to'            => '3',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled'  => 'yes',
                'days'          => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'no',
                    7 => 'no',
                ),
                'day_time_from' => array(
                    1 => '08:00',
                    2 => '08:00',
                    3 => '08:00',
                    4 => '08:00',
                    5 => '08:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
                'day_time_to'   => array(
                    1 => '16:00',
                    2 => '16:00',
                    3 => '16:00',
                    4 => '16:00',
                    5 => '12:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
            ),
            array(
                'type'          => 'month',
                'from'          => '4',
                'to'            => '5',
                'bookable'      => 'yes',
                'days_enabled'  => 'yes',
                'times_enabled'  => 'yes',
                'days'          => array(
                    1 => 'yes',
                    2 => 'yes',
                    3 => 'yes',
                    4 => 'yes',
                    5 => 'yes',
                    6 => 'no',
                    7 => 'no',
                ),
                'day_time_from' => array(
                    1 => '08:00',
                    2 => '08:00',
                    3 => '08:00',
                    4 => '08:00',
                    5 => '08:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
                'day_time_to'   => array(
                    1 => '16:00',
                    2 => '16:00',
                    3 => '16:00',
                    4 => '16:00',
                    5 => '12:00',
                    6 => '00:00',
                    7 => '00:00',
                ),
            )
        );

        return array_map( 'yith_wcbk_availability_rule', $ranges );
    }
}
