/* global hrw_wallet_params, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Wallet = {
        init : function ( ) {
            this.onload_function() ;
            //credit debit metabox
            $( document ).on( 'click' , '#hrw_wallet_credit_debit_btn' , this.wallet_credit_debit ) ;
            var $hrw_post_status = $( '.hrw_wallet_details_status_wrapper' ) ;
            $hrw_post_status.on( 'change' , '#hrw_wallet_status' , this.wallet_status_change ) ;
            $hrw_post_status.on( 'change' , '#hrw_wallet_lock_type' , this.wallet_lock_type ) ;

            $hrw_post_status.on( 'click' , '.hrw_display_edit_status' , this.display_edit_status ) ;
            $hrw_post_status.on( 'click' , '.hrw_cancel_edit_status' , this.cancel_edit_status ) ;
        } , onload_function : function ( ) {
            HRW_Wallet.wallet_status_toggle( "#hrw_wallet_status" ) ;
            $( '.hrw_wallet_details_status_wrapper' ).find( ".hrw_wallet_status_row" ).slideUp() ;
        } , wallet_credit_debit : function ( event ) {
            event.preventDefault( ) ;
            HRW_Wallet.block( 'div.hrw_wallet_credit_debit_wrapper' ) ;
            var data = {
                action : "hrw_wallet_credit_debit" ,
                wallet_id : $( '#hrw_wallet_user_id' ).val() ,
                type : $( '#hrw_wallet_fund_type' ).val() ,
                amount : $( '#hrw_wallet_fund_val' ).val() ,
                event : $( '#hrw_wallet_fund_reason' ).val() ,
                hrw_security : hrw_wallet_params.credit_debit_nonce
            }
            $.post( ajaxurl , data , function ( res ) {
                HRW_Wallet.unblock( 'div.hrw_wallet_credit_debit_wrapper' ) ;
                if ( true === res.success ) {
                    alert( res.data.msg ) ;
                    $( '.hrw_wallet_fund_fields' ).val( '' ) ;
                } else {
                    alert( res.data.error ) ;
                }
            } ) ;
        } , wallet_status_change : function ( e ) {
            var $this = $( e.currentTarget ) ;
            HRW_Wallet.wallet_status_toggle( $this ) ;
        } , wallet_status_toggle : function ( $this ) {
            if ( $( $this ).val() == 'hrw_blocked' ) {
                $( ".hrw_wallet_lock_type_row" ).slideDown() ;
                HRW_Wallet.wallet_lock_type_toggle( "#hrw_wallet_lock_type" ) ;
            } else {
                $( ".hrw_wallet_lock_status_row" ).slideUp() ;
            }
        } , wallet_lock_type : function ( e ) {
            var $this = $( e.currentTarget ) ;
            HRW_Wallet.wallet_lock_type_toggle( $this ) ;
        } , wallet_lock_type_toggle : function ( $this ) {
            if ( $( $this ).val() == '1' ) {
                $( ".hrw_schedule_lock" ).slideUp() ;
            } else {
                $( ".hrw_schedule_lock" ).slideDown() ;
            }
        } , display_edit_status : function ( e ) {
            $( '.hrw_wallet_details_status_wrapper' ).find( ".hrw_wallet_status_content" ).slideDown( 'fast' ) ;
            HRW_Wallet.wallet_status_toggle( "#hrw_wallet_status" ) ;
            $( this ).hide() ;
            $( '.hrw_cancel_edit_status' ).show() ;
        } ,
        cancel_edit_status : function ( e ) {
            $( '.hrw_wallet_details_status_wrapper' ).find( ".hrw_wallet_status_row" ).slideUp( 'fast' ) ;
            $( this ).hide() ;
            $( '.hrw_display_edit_status' ).show() ;
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
        } ,
    } ;
    HRW_Wallet.init( ) ;
} ) ;