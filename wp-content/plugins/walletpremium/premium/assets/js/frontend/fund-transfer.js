/* global hrw_fund_transfer_params */

jQuery( function ( $ ) {
    var $form = $( 'form.hrw_frontend_form' ) ,
            $transactions_wrapper = $( '.fund_transfer_transactions' ) ;


    var HRW_Fund_Transfer = {
        init : function () {

            $transactions_wrapper.on( 'click' , '.hrw_accept_fund_request' , this.accept_fund_request ) ;
            $transactions_wrapper.on( 'click' , '.hrw_decline_fund_request' , this.decline_fund_request ) ;
            $transactions_wrapper.on( 'click' , '.hrw_cancel_fund_request' , this.cancel_fund_request ) ;
            $form.on( 'click' , '.hrw_fund_request_button' , this.validate_fund_request ) ;
            $form.on( 'change keyup' , '.hrw_fund_transfer_amount' , this.calculate_transfer_fee ) ;

        } , validate_fund_request : function ( event ) {

            if ( !confirm( hrw_fund_transfer_params.fund_request_alert_msg ) ) {
                event.preventDefault() ;
                return false ;
            }

            return true ;

        } , calculate_transfer_fee : function ( event ) {
            var $this = $( event.currentTarget ) ,
                    $fee = 0 ;

            if ( $this.val() ) {
                //Prepare argument to transfer amount
                if ( hrw_fund_transfer_params.enable_transfer_fee == 'yes' ) {
                    var $fee_value = hrw_fund_transfer_params.transfer_fee_value ;
                    if ( hrw_fund_transfer_params.transfer_fee_type == '2' ) {
                        var $fee = ( $fee_value ) ? ( $fee_value / 100 ) * $this.val() : $fee_value ;
                    } else {
                        var $fee = $fee_value ;
                    }
                }
            }

            $form.find( '.hrw_fund_transfer_fee' ).val( $fee ) ;

        } , accept_fund_request : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var div = $( $this ).closest( '.hrw_fund_transfer_transaction' ) ;

            if ( !confirm( hrw_fund_transfer_params.fund_request_transfer_alert_msg ) ) {
                return false ;
            }

            HRW_Fund_Transfer.block( div ) ;

            var data = {
                action : 'hrw_response_fund_request' ,
                type : 'transfer' ,
                transaction_id : div.find( '.hrw_fund_transfer_transaction_id' ).val() ,
                hrw_security : hrw_fund_transfer_params.fund_transfer_nonce
            } ;

            $.post( hrw_fund_transfer_params.ajax_url , data , function ( res ) {
                if ( true === res.success ) {
                    div.html( res.data.html ) ;
                } else {
                    alert( res.data.error ) ;
                }

                HRW_Fund_Transfer.unblock( div ) ;
            } ) ;
        } , decline_fund_request : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var div = $( $this ).closest( '.hrw_fund_transfer_transaction' ) ;

            if ( !confirm( hrw_fund_transfer_params.fund_request_decline_alert_msg ) ) {
                return false ;
            }

            HRW_Fund_Transfer.block( div ) ;

            var data = {
                action : 'hrw_response_fund_request' ,
                type : 'decline' ,
                transaction_id : div.find( '.hrw_fund_transfer_transaction_id' ).val() ,
                hrw_security : hrw_fund_transfer_params.fund_transfer_nonce
            } ;

            $.post( hrw_fund_transfer_params.ajax_url , data , function ( res ) {
                if ( true === res.success ) {
                    div.html( res.data.html ) ;
                } else {
                    alert( res.data.error ) ;
                }

                HRW_Fund_Transfer.unblock( div ) ;
            } ) ;
        } , cancel_fund_request : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var div = $( $this ).closest( '.hrw_fund_transfer_transaction' ) ;

            if ( !confirm( hrw_fund_transfer_params.fund_request_cancel_alert_msg ) ) {
                return false ;
            }

            HRW_Fund_Transfer.block( div ) ;

            var data = {
                action : 'hrw_response_fund_request' ,
                type : 'cancel' ,
                transaction_id : div.find( '.hrw_fund_transfer_transaction_id' ).val() ,
                hrw_security : hrw_fund_transfer_params.fund_transfer_nonce
            } ;

            $.post( hrw_fund_transfer_params.ajax_url , data , function ( res ) {
                if ( true === res.success ) {
                    div.html( res.data.html ) ;
                } else {
                    alert( res.data.error ) ;
                }

                HRW_Fund_Transfer.unblock( div ) ;
            } ) ;
        } , block : function ( id ) {
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
    HRW_Fund_Transfer.init() ;
} ) ;