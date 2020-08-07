<?php

/**
 * Class BK_Helper_Prices.
 *
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Prices {

    /**
     * format the price as 1234.00
     *
     * @param $price
     *
     * @return string
     */
    public static function format_price( $price ) {
        return number_format( $price, 2, '.', '' );
    }

    /**
     * Create daily cost ranges.
     *
     * @param array $extra_ranges
     *
     * @return array
     */
    public static function create_daily_cost_ranges( $extra_ranges = array() ) {
        $ranges = array(
            array(
                'type'                => 'month',
                'from'                => '6',
                'to'                  => '7',
                'base_cost_operator'  => 'mul',
                'base_cost'           => '1.2',
                'block_cost_operator' => 'mul',
                'block_cost'          => '1.2',
            ),
            array(
                'type'                => 'custom',
                'from'                => BK_Helper_Date::create_next_year_date( 'Aug 15', 'date' ),
                'to'                  => BK_Helper_Date::create_next_year_date( 'Aug 15', 'date' ),
                'base_cost_operator'  => 'mul',
                'base_cost'           => '2',
                'block_cost_operator' => 'mul',
                'block_cost'          => '2',
            ),
            array(
                'type'                => 'custom',
                'from'                => BK_Helper_Date::create_next_year_date( 'Sep 01', 'date' ),
                'to'                  => BK_Helper_Date::create_next_year_date( 'Sep 15', 'date' ),
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'add',
                'block_cost'          => '1',
            ),
        );

        if ( in_array( 'day', $extra_ranges ) ) {
            $ranges[] = array(
                'type'                => 'day',
                'from'                => '6',
                'to'                  => '7',
                'base_cost_operator'  => 'add',
                'base_cost'           => '5',
                'block_cost_operator' => 'add',
                'block_cost'          => '5',
            );
        }

        if ( in_array( 'person', $extra_ranges ) ) {
            $ranges[] = array(
                'type'                => 'person',
                'from'                => '5',
                'to'                  => '10',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'div',
                'block_cost'          => '1.2',
            );
        }

        if ( in_array( 'block', $extra_ranges ) ) {
            $ranges[] = array(
                'type'                => 'block',
                'from'                => '5',
                'to'                  => '10',
                'base_cost_operator'  => 'add',
                'base_cost'           => '0',
                'block_cost_operator' => 'div',
                'block_cost'          => '1.2',
            );
        }

        return $ranges;
    }
}
