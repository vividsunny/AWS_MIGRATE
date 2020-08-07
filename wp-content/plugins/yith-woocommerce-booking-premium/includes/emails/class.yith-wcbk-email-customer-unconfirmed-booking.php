<?php
!defined( 'ABSPATH' ) && exit;

if ( !class_exists( 'YITH_WCBK_Email_Customer_Unconfirmed_Booking' ) ) :

    /**
     * Class YITH_WCBK_Email_Customer_Unconfirmed_Booking
     *
     * An email sent to the customer when a new booking is not confirmed
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    class YITH_WCBK_Email_Customer_Unconfirmed_Booking extends YITH_WCBK_Email {

        /**
         * Constructor.
         */
        public function __construct() {
            $this->id             = 'yith_wcbk_customer_unconfirmed_booking';
            $this->title          = __( 'Rejected Booking', 'yith-booking-for-woocommerce' );
            $this->description    = __( 'This email is sent to the customer when a booking is not confirmed.', 'yith-booking-for-woocommerce' );
            $this->heading        = __( 'Booking could not be confirmed', 'yith-booking-for-woocommerce' );
            $this->subject        = __( 'Booking #{booking_id} could not be confirmed', 'yith-booking-for-woocommerce' );
            $this->customer_email = true;

            $this->template_base  = YITH_WCBK_TEMPLATE_PATH;
            $this->template_html  = 'emails/customer-unconfirmed-booking.php';
            $this->template_plain = 'emails/plain/customer-unconfirmed-booking.php';

            // Triggers for this email
            add_action( 'yith_wcbk_booking_status_unconfirmed_notification', array( $this, 'trigger' ) );

            // Call parent constructor
            parent::__construct();
        }

        /**
         * Trigger.
         *
         * @param int $booking_id
         */
        public function trigger( $booking_id ) {
            if ( $booking_id ) {
                $this->object = yith_get_booking( $booking_id );
                if ( $this->object->is_valid() ) {
                    $this->find[ 'booking-id' ]        = '{booking_id}';
                    $this->find[ 'booking-status' ]    = '{status}';
                    $this->replace[ 'booking-id' ]     = absint( $booking_id );
                    $this->replace[ 'booking-status' ] = $this->object->get_status_text();


                    if ( $this->object->order_id && $order = wc_get_order( $this->object->order_id ) ) {
                        $this->recipient = yit_get_prop( $order, 'billing_email', true );
                    } elseif ( $this->object->user_id && $user_data = get_userdata( $this->object->user_id ) ) {
                        $this->recipient = $user_data->user_email;
                    }

                    if ( !$this->is_enabled() || !$this->get_recipient() ) {
                        return;
                    }
                    $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
                }
            }
        }
    }

endif;

return new YITH_WCBK_Email_Customer_Unconfirmed_Booking();
