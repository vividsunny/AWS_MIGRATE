/* global hrw_modules_params, ajaxurl */
jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Modules = {

        init : function ( ) {
            this.trigger_on_page_load() ;

            //SMS Module
            $( document ).on( 'change' , '#hrw_sms_api_method' , this.show_sms_api_method ) ;

            //Fund Transfer Module
            $( document ).on( 'change' , '#hrw_fund_transfer_enable_transfer_fund' , this.toggle_fund_transfer_options ) ;
            $( document ).on( 'change' , '#hrw_fund_transfer_enable_request_fund' , this.toggle_fund_request_options ) ;
            $( document ).on( 'change' , '#hrw_fund_transfer_enable_transfer_fee' , this.toggle_transfer_fee_options ) ;
            $( document ).on( 'change' , '.hrw_user_selection_type' , this.toggle_user_selection_options ) ;
            $( document ).on( 'change' , '.hrw_site_activity_type' , this.toggle_site_activity_options ) ;
            $( document ).on( 'change' , '#hrw_fund_transfer_enable_email_otp' , this.toggle_email_otp_options ) ;
            $( document ).on( 'change' , '#hrw_fund_transfer_enable_sms_otp' , this.toggle_sms_otp_options ) ;

            //Withdrawal Module
            $( document ).on( 'change' , '#hrw_wallet_withdrawal_enable_withdrawal_fee' , this.toggle_withdrawal_fee_options ) ;
	    $( document ).on( 'change' , '#hrw_wallet_withdrawal_enable_email_otp' , this.toggle_withdrawal_email_otp_options ) ;
            $( document ).on( 'change' , '#hrw_wallet_withdrawal_enable_sms_otp' , this.toggle_withdrawal_sms_otp_options ) ;

        } ,
        trigger_on_page_load : function () {
            //SMS Module
            this.show_or_hide_api_method( '#hrw_sms_api_method' ) ;

            //Fund Transfer Module
            this.handle_fund_transfer_options( '#hrw_fund_transfer_enable_transfer_fund' ) ;
            this.handle_fund_request_options( '#hrw_fund_transfer_enable_request_fund' ) ;
            this.handle_user_selection_options( '.hrw_user_selection_type' ) ;
            this.handle_site_activity_options( '.hrw_site_activity_type' ) ;
            this.handle_email_otp_options( '#hrw_fund_transfer_enable_email_otp' ) ;
            this.handle_sms_otp_options( '#hrw_fund_transfer_enable_sms_otp' ) ;

            //Withdrawal Module
            this.handle_withdrawal_fee_options( '#hrw_wallet_withdrawal_enable_withdrawal_fee' ) ;
	    this.handle_withdrawal_email_otp_options( '#hrw_wallet_withdrawal_enable_email_otp' ) ;
            this.handle_withdrawal_sms_otp_options( '#hrw_wallet_withdrawal_enable_sms_otp' ) ;
            this.payment_gateways_sortable() ;
        } , toggle_withdrawal_fee_options : function ( event ) {

            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_withdrawal_fee_options( $this ) ;

        } , toggle_fund_transfer_options : function ( event ) {

            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_fund_transfer_options( $this ) ;

        } , toggle_transfer_fee_options : function ( event ) {

            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_transfer_fee_options( $this ) ;
        } , toggle_fund_request_options : function ( event ) {

            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_fund_request_options( $this ) ;
        } , toggle_user_selection_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_user_selection_options( $this ) ;
        } , toggle_site_activity_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_site_activity_options( $this ) ;
        } , toggle_withdrawal_email_otp_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_withdrawal_email_otp_options( $this ) ;
        } , toggle_withdrawal_sms_otp_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_withdrawal_sms_otp_options( $this ) ;
        } , toggle_sms_otp_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_sms_otp_options( $this ) ;
        } , toggle_email_otp_options : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.handle_email_otp_options( $this ) ;
        } , show_sms_api_method : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            HRW_Modules.show_or_hide_api_method( $this ) ;
        } , handle_withdrawal_fee_options : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrw_withdrawal_fee_options' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.hrw_withdrawal_fee_options' ).closest( 'tr' ).hide( ) ;
            }
        } , handle_fund_transfer_options : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrw_fund_transfer_options' ).closest( 'tr' ).show( ) ;
                //handle transfer fee
                HRW_Modules.handle_transfer_fee_options( '#hrw_fund_transfer_enable_transfer_fee' ) ;
            } else {
                $( '.hrw_fund_transfer_options' ).closest( 'tr' ).hide( ) ;
            }
        } , handle_transfer_fee_options : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrw_transfer_fee_options' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.hrw_transfer_fee_options' ).closest( 'tr' ).hide( ) ;
            }
        } , handle_fund_request_options : function ( $this ) {
            if ( $( $this ).is( ":checked" ) ) {
                $( '.hrw_fund_request_options' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.hrw_fund_request_options' ).closest( 'tr' ).hide( ) ;
            }
        } , handle_user_selection_options : function ( $this ) {
            $( '.hrw_user_selection' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '2' ) {
                $( '.hrw_selected_users' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '3' ) {
                $( '.hrw_selected_user_roles' ).closest( 'tr' ).show() ;
            }
        } , handle_site_activity_options : function ( $this ) {
            $( '.hrw_site_activity' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '1' ) {
                $( '.hrw_registered_days_site_activity' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '2' ) {
                $( '.hrw_purchase_amount_site_activity' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '3' ) {
                $( '.hrw_order_placed_site_activity' ).closest( 'tr' ).show() ;
            }
        } , handle_email_otp_options : function ( $this ) {

            if ( !$( $this ).is( ':checked' ) && $( $this ).length ) {
                $( '.hrw_fund_transfer_otp_email' ).closest( 'tr' ).hide() ;
            } else {
                $( '.hrw_fund_transfer_otp_email' ).closest( 'tr' ).show() ;
            }
        } , handle_sms_otp_options : function ( $this ) {
            $( '.hrw_fund_transfer_otp_sms' ).closest( 'tr' ).hide() ;
            if ( $( $this ).is( ':checked' ) ) {
                $( '.hrw_fund_transfer_otp_sms' ).closest( 'tr' ).show() ;
            }
        } ,handle_withdrawal_email_otp_options : function ( $this ) {

            if ( ! $( $this ).is( ':checked' ) && $( $this ).length ) {
                $( '.hrw_withdrawal_otp_email' ).closest( 'tr' ).hide() ;
            } else {
                $( '.hrw_withdrawal_otp_email' ).closest( 'tr' ).show() ;
            }
        } , handle_withdrawal_sms_otp_options : function ( $this ) {
            $( '.hrw_withdrawal_otp_sms' ).closest( 'tr' ).hide() ;
            if ( $( $this ).is( ':checked' ) ) {
                $( '.hrw_withdrawal_otp_sms' ).closest( 'tr' ).show() ;
            }
        } , show_or_hide_api_method : function ( $this ) {

            if ( $( $this ).val( ) == "1" ) {
                $( '#hrw_sms_twilio_account_sid' ).closest( 'tr' ).show( ) ;
                $( '#hrw_sms_twilio_account_auth_token' ).closest( 'tr' ).show( ) ;
                $( '#hrw_sms_nexmo_key' ).closest( 'tr' ).hide( ) ;
                $( '#hrw_sms_nexmo_secret' ).closest( 'tr' ).hide( ) ;
            } else {
                $( '#hrw_sms_nexmo_key' ).closest( 'tr' ).show( ) ;
                $( '#hrw_sms_nexmo_secret' ).closest( 'tr' ).show( ) ;
                $( '#hrw_sms_twilio_account_sid' ).closest( 'tr' ).hide( ) ;
                $( '#hrw_sms_twilio_account_auth_token' ).closest( 'tr' ).hide( ) ;
            }
        } , payment_gateways_sortable : function () {
            $( 'table #hrw_payment_settings_table' ).sortable( {
                items : 'tr' ,
                handle : '.hrw_payments_sort_handle' ,
                axis : 'y' ,
                containment : $( 'table #hrw_payment_settings_table' ).closest( 'table' ) ,
            } ) ;
        } ,

        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $( id ).unblock() ;
        }
    } ;
    HRW_Modules.init( ) ;
} ) ;