jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Frontend = {

        init : function () {
            // view filter dropdown
            $( document ).on( 'click' , 'input.hrw_topup_prefilled_amount' , this.select_topup_amount ) ;
            // Toggle Partial form in checkout
            $( document ).on( 'click' , 'a.hrw_partial_wallet' , this.toggle_partial_form_checkout ) ;

            $( document ).on( 'click' , '.hrw_frontend_dashboard_mobile_menu' , this.display_dashboard_mobile_menu ) ;

            $( ".single_add_to_cart_button , .product_type_simple" ).on( 'click' , this.restrict_add_to_cart ) ;


        } , select_topup_amount : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ,
                    $div = $( $this ).closest( '.hrw_topup_amount_buttons' ) ;

            $div.find( '.hrw_topup_amount' ).val( $( $this ).data( 'amount' ) ) ;
            $div.find( '.hrw_topup_prefilled_amount' ).removeClass( 'hrw_active_topup_amount' ) ;
            $( $this ).addClass( 'hrw_active_topup_amount' ) ;

        } , toggle_partial_form_checkout : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ,
                    $div = $( $this ).closest( '.hrw_partial_form_wrapper' ) ;

            $div.find( '.hrw_partial_usage_content' ).slideToggle( 400 , function () {
                $div.find( '.hrw_partial_usage_content' ).find( '.hrw_partial_usage' ).focus() ;
            } ) ;

            return false ;
        } , display_dashboard_mobile_menu : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ,
                    $div = $( $this ).closest( '.hrw_menus_wrapper' ) ;

            $div.find( '.hrw_menu_ul' ).toggle() ;
        } , restrict_add_to_cart : function ( event ) {
            if ( hrw_frontend_params.alert_message == 'yes' && hrw_frontend_params.low_wallet_amount >= hrw_frontend_params.wallet_amount ) {
                if ( ! confirm( hrw_frontend_params.popup_low_wallet_msg ) ) {
                    event.preventDefault() ;
                    return false ;
                }
            }

            return true ;
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
    HRW_Frontend.init() ;
} ) ;
