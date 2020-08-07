jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Discount = {

        init : function () {
            this.trigger_on_page_load() ;
            $( '#order_review' ).on( 'change' , 'input[name="payment_method"]' , this.add_discount ) ;
        } , trigger_on_page_load : function () {
            HRW_Discount.add_fee() ;
        } , add_discount : function ( ) {
            HRW_Discount.add_fee() ;
        } , add_fee : function () {
            HRW_Discount.block( '#order_review' ) ;
            var gatewayid = $( '.payment_methods input[name="payment_method"]:checked' ).val() ;
            var data = ( {
                action : 'hrw_add_fee' ,
                gatewayid : gatewayid ,
                hrw_security : hrw_discount_params.discount_nonce
            } ) ;
            $.post( hrw_discount_params.ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $( 'body' ).trigger( 'update_checkout' ) ;
                } else {
                    window.alert( response.data.error ) ;
                }
                HRW_Discount.unblock( '#order_review' ) ;
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
    HRW_Discount.init() ;
} ) ;
