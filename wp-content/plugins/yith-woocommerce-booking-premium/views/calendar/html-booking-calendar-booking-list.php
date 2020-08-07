<?php
/**
 * Booking List in Calendar page html
 *
 * @var YITH_WCBK_Booking_Abstract[]|YITH_WCBK_Booking[]|YITH_WCBK_Booking_External[] $bookings
 */
!defined( 'YITH_WCBK' ) && exit; // Exit if accessed directly

$bookings = !!$bookings ? $bookings : array();
$bookings = (array) apply_filters( 'yith_wcbk_calendar_booking_list_bookings', $bookings );
?>

<?php
foreach ( $bookings as $booking ) {
    if ( $booking instanceof YITH_WCBK_Booking_Abstract ) {
        $id = !$booking->is_external() ? $booking->get_id() : 'external-' . $booking->get_id();
        ?>
        <div class="yith-wcbk-booking-calendar-single-booking yith-wcbk-booking-calendar-single-booking-<?php echo $id; ?> <?php echo $booking->get_status(); ?>"
             data-booking-id="<?php echo $id; ?>"
             data-booking-class="yith-wcbk-booking-calendar-single-booking-<?php echo $id; ?>"
            <?php if ( $booking->is_external() ) : ?> data-external-host="<?php echo $booking->get_source_slug(); ?>" <?php endif; ?>>

            <div class="yith-wcbk-booking-calendar-single-booking-title">
                <h3><?php
                    if ( $booking->is_external() ) {
                        $calendar_name = $booking->get_calendar_name();
                        if ( !!$calendar_name ) {
                            echo "<span class='yith-wcbk-booking-calendar-single-booking-title__external-calendar'>$calendar_name</span>";
                        }
                    }

                    echo apply_filters( 'yith_wcbk_calendar_booking_title', $booking->get_title(), $booking );
                    ?></h3>
            </div>
            <div class="yith-wcbk-booking-calendar-single-booking-data">
                <?php
                include( 'html-booking-calendar-single-booking-data.php' );
                ?>
            </div>
        </div>
        <?php
    }
}
?>
