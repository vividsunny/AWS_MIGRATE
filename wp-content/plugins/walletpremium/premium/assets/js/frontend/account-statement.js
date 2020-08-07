/* global hrw_account_statement, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Account_Statement = {
        init : function ( ) {
            this.trigger_onload_function() ;
            //statement menu
            $( document ).on( 'change' , '.hrw_wallet_statement_duration' , this.toggle_custom_duration ) ;
            $( document ).on( 'click' , '.hrw_wallet_statement_sent_email_btn' , this.sent_statement_to_email ) ;
        } , trigger_onload_function : function ( ) {
            this.custom_duration('.hrw_wallet_statement_duration') ;
        } , toggle_custom_duration : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            $($this).each(function(){
                HRW_Account_Statement.custom_duration( $this ) ;
            });
        } , custom_duration : function ( $this ) {
            if ( $( $this ).val() == 'custom' ) {
                $('.hrw_statement_custom_date_field').closest('p').show();
            } else {
                $('.hrw_statement_custom_date_field').closest('p').hide();
            }
        } , sent_statement_to_email : function ( event ) {
            event.preventDefault( ) ;
            HRW_Account_Statement.block( 'div.hrw_frontend_form' ) ;
            var option = $('.hrw_wallet_statement_duration:checked'). val();
            if (option == 'custom') {
                if ($('#hrw_statement_from_date').val() == '' || $('#hrw_statement_to_date').val() == ''){
                    alert(hrw_account_statement_obj.empty_from_to_interval);
                    location.reload( true ) ;
                }
                var from_date = $('#hrw_statement_from_date').val();
                var to_date = $('#hrw_statement_to_date').val();
                var option = from_date + '+' + to_date;
            }
            var data = {
                action : 'hrw_wallet_statement_email' ,
                interval: option ,
                hrw_security : hrw_account_statement_obj.wallet_statement_nonce
            }
            $.post( hrw_account_statement_obj.admin_url , data , function ( res ) {
                if ( true === res.success ) {
                    alert( res.data.msg ) ;
                } else {
                    alert( res.data.error ) ;
                }
		HRW_Account_Statement.unblock( 'div.hrw_frontend_form' ) ;
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
        } ,
    } ;
    HRW_Account_Statement.init( ) ;
} ) ;