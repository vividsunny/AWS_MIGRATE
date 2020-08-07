/* Refund Tab */
jQuery( function ( $ ) {

    var HRR_Refund_Tab = {
        init : function () {
            this.trigger_on_page_load() ;
            //General Settings
            $( document ).on( 'change' , '#hrr_refund_refundanable_product' , this.toggle_refundable_product_type ) ;
            $( document ).on( 'change' , '#hrr_refund_request_time_period' , this.toggle_request_time_period ) ;
            $( document ).on( 'change' , '#hrr_refund_refundable_user' , this.toggle_refundable_type ) ;
            $( document ).on( 'change' , '#hrr_refund_enable_reason_in_detail' , this.toggle_reason_field_mandatory ) ;
            $( document ).on( 'change' , '#hrr_refund_enable_attachment' , this.toggle_attachment_field ) ;

            //Email Settings
            $( document ).on( 'change' , '#hrr_refund_enable_unsubscribe_option' , this.toggle_get_enable_unsubscription_option ) ;
            $( document ).on( 'change' , '#hrr_refund_new_request_user_notification' , this.toggle_get_enable_request_sent_user_option ) ;
            $( document ).on( 'change' , '#hrr_refund_new_request_admin_notification' , this.toggle_get_enable_request_sent_admin_option ) ;
            $( document ).on( 'change' , '#hrr_refund_refund_conversation_user_notification' , this.toggle_get_enable_request_reply_receive_user_option ) ;
            $( document ).on( 'change' , '#hrr_refund_refund_conversation_admin_notification' , this.toggle_get_enable_request_reply_receive_admin_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_accepted_user_notification' , this.toggle_get_enable_request_accept_user_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_accepted_admin_notification' , this.toggle_get_enable_request_accept_admin_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_rejected_user_notification' , this.toggle_get_enable_request_reject_user_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_rejected_admin_notification' , this.toggle_get_enable_request_reject_admin_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_status_update_user_notification' , this.toggle_get_enable_request_status_change_user_option ) ;
            $( document ).on( 'change' , '#hrr_refund_request_status_update_admin_notification' , this.toggle_get_enable_request_status_change_admin_option ) ;
        } ,
        trigger_on_page_load : function () {
            //General Settings
            this.refundable_product_type( '#hrr_refund_refundanable_product' ) ;
            this.request_time_period( '#hrr_refund_request_time_period' ) ;
            this.refundable_type( '#hrr_refund_refundable_user' ) ;
            this.reason_field_mandatory( '#hrr_refund_enable_reason_in_detail' ) ;
            this.attachment_field( '#hrr_refund_enable_attachment' ) ;

            //Email Settings
            this.get_enable_unsubscription_option( '#hrr_refund_enable_unsubscribe_option' ) ;
            this.get_enable_request_sent_user_option( '#hrr_refund_new_request_user_notification' ) ;
            this.get_enable_request_sent_admin_option( '#hrr_refund_new_request_admin_notification' ) ;
            this.get_enable_request_reply_receive_user_option( '#hrr_refund_refund_conversation_user_notification' ) ;
            this.get_enable_request_reply_receive_admin_option( '#hrr_refund_refund_conversation_admin_notification' ) ;
            this.get_enable_request_accept_user_option( '#hrr_refund_request_accepted_user_notification' ) ;
            this.get_enable_request_accept_admin_option( '#hrr_refund_request_accepted_admin_notification' ) ;
            this.get_enable_request_reject_user_option( '#hrr_refund_request_rejected_user_notification' ) ;
            this.get_enable_request_reject_admin_option( '#hrr_refund_request_rejected_admin_notification' ) ;
            this.get_enable_request_status_change_user_option( '#hrr_refund_request_status_update_user_notification' ) ;
            this.get_enable_request_status_change_admin_option( '#hrr_refund_request_status_update_admin_notification' ) ;
        } ,
        toggle_refundable_product_type : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.refundable_product_type( $this ) ;
        } ,
        toggle_refundable_type : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.refundable_type( $this ) ;
        } ,
        toggle_reason_field_mandatory : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.reason_field_mandatory( $this ) ;
        } ,
        toggle_attachment_field : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            HRR_Refund_Tab.attachment_field ( $this ) ;
        } ,
        toggle_request_time_period : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.request_time_period( $this ) ;
        } ,
        refundable_product_type : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hrr_refund_included_product' ).closest( 'tr' ).show() ;
                $( '#hrr_refund_included_category' ).closest( 'tr' ).hide() ;
            } else if ( $( $this ).val() == '4' ) {
                $( '#hrr_refund_included_product' ).closest( 'tr' ).hide() ;
                $( '#hrr_refund_included_category' ).closest( 'tr' ).show() ;
            } else {
                $( '#hrr_refund_included_product' ).closest( 'tr' ).hide() ;
                $( '#hrr_refund_included_category' ).closest( 'tr' ).hide() ;
            }
        } ,
        refundable_type : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hrr_refund_included_user_role' ).closest( 'tr' ).hide() ;
                $( '#hrr_refund_included_user' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() == '4' ) {
                $( '#hrr_refund_included_user_role' ).closest( 'tr' ).show() ;
                $( '#hrr_refund_included_user' ).closest( 'tr' ).hide() ;
            } else {
                $( '#hrr_refund_included_user_role' ).closest( 'tr' ).hide() ;
                $( '#hrr_refund_included_user' ).closest( 'tr' ).hide() ;
            }
        } ,
        reason_field_mandatory : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_refund_mandatory_reason_field' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_refund_mandatory_reason_field' ).closest( 'tr' ).hide() ;
            }
        } ,
        request_time_period : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hrr_refund_request_time_period_value' ).closest( 'tr' ).show() ;
            } else {
                $( '#hrr_refund_request_time_period_value' ).closest( 'tr' ).hide() ;
            }
        } ,
         attachment_field : function ( $this ) {
            if ( $ ( $this ).is ( ':checked' ) == true ) {
                $ ( '.hrr_file_uploads' ).closest ( 'tr' ).show ( ) ;
            } else {
                $ ( '.hrr_file_uploads' ).closest ( 'tr' ).hide ( ) ;
            }
        } ,
        toggle_get_enable_unsubscription_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_unsubscription_option( $this ) ;
        } ,
        toggle_get_enable_request_sent_user_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_sent_user_option( $this ) ;
        } ,
        toggle_get_enable_request_sent_admin_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_sent_admin_option( $this ) ;
        } ,
        toggle_get_enable_request_reply_receive_user_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_reply_receive_user_option( $this ) ;
        } ,
        toggle_get_enable_request_reply_receive_admin_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_reply_receive_admin_option( $this ) ;
        } ,
        toggle_get_enable_request_accept_user_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_accept_user_option( $this ) ;
        } ,
        toggle_get_enable_request_accept_admin_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_accept_admin_option( $this ) ;
        } ,
        toggle_get_enable_request_reject_user_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_reject_user_option( $this ) ;
        } ,
        toggle_get_enable_request_reject_admin_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_reject_admin_option( $this ) ;
        } ,
        toggle_get_enable_request_status_change_user_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_status_change_user_option( $this ) ;
        } ,
        toggle_get_enable_request_status_change_admin_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_Refund_Tab.get_enable_request_status_change_admin_option( $this ) ;
        } ,
        get_enable_unsubscription_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_refund_unsubscription' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_refund_unsubscription' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_sent_user_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_new_request_user_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_new_request_user_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_sent_admin_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_new_request_admin_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_new_request_admin_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_reply_receive_user_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_refund_conversation_user_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_refund_conversation_user_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_reply_receive_admin_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_refund_conversation_admin_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_refund_conversation_admin_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_accept_user_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_accepted_user_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_accepted_user_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_accept_admin_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_accepted_admin_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_accepted_admin_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_reject_user_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_rejected_user_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_rejected_user_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_reject_admin_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_rejected_admin_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_rejected_admin_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_status_change_user_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_status_update_user_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_status_update_user_notification' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_enable_request_status_change_admin_option : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrr_req_status_update_admin_notification' ).closest( 'tr' ).show() ;
            } else {
                $( '.hrr_req_status_update_admin_notification' ).closest( 'tr' ).hide() ;
            }
        }
    } ;
    HRR_Refund_Tab.init() ;
} ) ;
