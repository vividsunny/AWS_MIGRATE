/* global hrw_withdrawal_params */

jQuery( function( $ ) {

    var $form = $( 'form.hrw_frontend_form' ) ;

    var HRW_Withdrawal = {
        init : function() {

            this.trigger_onload_function() ;

            $( document ).on( 'click' , '.hrw_cancel_withdrawal' , this.cancel_withdrawal ) ;
            $form.on( 'change' , '.hrw_withdrawal_payment_method' , this.toggle_payment_method ) ;
            $form.on( 'change keyup' , '.hrw_withdrawal_amount' , this.calculate_transfer_fee ) ;
        } ,
        trigger_onload_function : function( ) {
            this.payment_method( '.hrw_withdrawal_payment_method' ) ;
        } ,
        calculate_transfer_fee : function( e ) {

            var $this = $( e.currentTarget ) ,
                    $fee = 0 ;

            if( $this.val() ) {
                //Prepare argument to Withdrawal amount
                if( hrw_withdrawal_params.enable_withdrawal_fee === 'yes' ) {
                    var $fee_value = hrw_withdrawal_params.withdrawal_fee_value ;
                    if( hrw_withdrawal_params.withdrawal_fee_type === '2' ) {
                        var $fee = ( $fee_value ) ? ( $fee_value / 100 ) * $this.val() : $fee_value ;
                    } else {
                        var $fee = $fee_value ;
                    }
                }
            }

            $form.find( '.hrw_withdrawal_fee' ).val( $fee ) ;
        } ,
        cancel_withdrawal : function( e ) {
            e.preventDefault() ;

            var $this = $( e.currentTarget ) ;

            if( confirm( hrw_withdrawal_params.withdrawal_alert_msg ) ) {
                HRW_Withdrawal.block( $this.closest( 'tr' ) ) ;
                var data = {
                    action : 'hrw_cancel_withdrawal' ,
                    post_id : $this.val() ,
                    hrw_security : hrw_withdrawal_params.withdrawal_nonce
                } ;
                $.post( hrw_withdrawal_params.ajax_url , data , function( response ) {
                    if( true === response.success ) {
                        HRW_Withdrawal.unblock( $this.closest( 'tr' ) ) ;
                        location.reload() ;
                    }
                } ) ;
            }
        }
        , toggle_payment_method : function( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            $( $this ).each( function( ) {
                HRW_Withdrawal.payment_method( $this ) ;
            } ) ;
        }
        , payment_method : function( $this ) {
            if( $( $this ).val() == 'bank_transfer' ) {
                $( '.hrw_withdrawal_bank_details' ).closest( 'p' ).show() ;
                $( '.hrw_withdrawal_paypal_details' ).closest( 'p' ).hide() ;
            } else {
                $( '.hrw_withdrawal_bank_details' ).closest( 'p' ).hide() ;
                $( '.hrw_withdrawal_paypal_details' ).closest( 'p' ).show() ;
            }
        }
        ,
        block : function( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        }
        , unblock : function( id ) {
            $( id ).unblock( ) ;
        }
    } ;
    HRW_Withdrawal.init( ) ;
} ) ;