<?php

/**
 * Class BK_Helper_Date.
 *
 * This helper class should ONLY be used for unit tests!.
 */
class BK_Helper_Date {

    /**
     * return the next year date by day and month
     *
     * @param string $date
     * @param string $return
     *
     * @return false|int|string
     */
    public static function create_next_year_date( $date, $return = 'timestamp' ) {
        $date = strtotime( '+1 year', strtotime( $date ) );

        switch ( $return ) {
            case 'timestamp':
                break;
            case 'date':
                $date = date( 'Y-m-d', $date );
                break;
            case 'datetime':
                $date = date( 'Y-m-d H:i:s', $date );
                break;
            default:
                $date = date( $return, $date );

                break;
        }

        return $date;
    }
}
