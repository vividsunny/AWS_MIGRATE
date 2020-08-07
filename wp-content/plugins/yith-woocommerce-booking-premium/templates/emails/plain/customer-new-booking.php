<?php
!defined( 'ABSPATH' ) && exit;

echo "= " . $email_heading . " =\n\n";

echo sprintf( __( '%s has been created. It is now %s', 'yith-booking-for-woocommerce' ), $booking->get_name(), $booking->get_status_text() ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'yith_wcbk_email_booking_details', $booking, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
