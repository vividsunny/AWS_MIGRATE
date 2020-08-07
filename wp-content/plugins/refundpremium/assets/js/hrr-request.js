/* global  hrr_refund_request_form */

jQuery( function ( $ ) {

    var HRR_Request_view = {

        init : function () {
            $( document ).on( 'click' , 'input#hrr_submit' , this.change_status ) ;
            $( 'div.hrr-refund-request' ).on( 'click' , 'button.hrr_refund_request_button' , this.do_refund_request ) ;
        } ,

        do_refund_request : function ( event ) {
            event.preventDefault() ;
            var con = confirm( hrr_request_params.refund_request_message ) ;
            if ( con ) {
                // Get line item refunds
                var line_item_qtys = { } ;
                var line_item_totals = { } ;
                var line_item_tax_totals = { } ;

                $( 'tr.hrr_refund_items td.hrr_refund_item_data' ).each( function ( index , item ) {
                    $row = $( item ).closest( 'tr' ) ;
                    var $item_id = $row.data( 'order_item_id' ) ;
                    var total = 0 ;
                    if ( $item_id ) {
                        var checkbox = $row.find( 'input.hrr_refund_enable_product' ) ;
                        if ( checkbox.length <= 0 || checkbox.is( ":checked" ) ) {
                            total = total + 1 ;
                            item_qty = parseInt( $( item ).find( '#hrr_refund_request_qty' ).val() ) ;
                            item_value = parseFloat( $( item ).find( '.hrr_refund_request_price' ).val() ) ;
                            item_total = item_value * item_qty ;

                            line_item_qtys[ $item_id ] = item_qty ;
                            line_item_totals[ $item_id ] = accounting.unformat( item_total , hrr_request_params.mon_decimal_point ) ;
                            line_item_tax_totals [ $item_id ] = { } ;

                            item_taxes = $( item ).find( '.hrr_refund_request_tax' ).each( function ( index , tax ) {
                                var tax_id = $( tax ).data( 'tax_id' ) ;
                                var selected_qty_tax_value = parseFloat( tax.value ) * item_qty ;
                                line_item_tax_totals [ $item_id ][ tax_id ] = accounting.unformat( selected_qty_tax_value , hrr_request_params.mon_decimal_point ) ;
                            } ) ;
                        }
                    }
                } ) ;

                if ( $( '#hr_refund_total' ).val() == '0' ) {
                    window.alert( hrr_request_params.refund_product_message ) ;
                    return ;
                }

                HRR_Request_view.block( '.hrr-refund-request' ) ;
                var data = {
                    action : 'hrr_manual_refund' ,
                    order_id : $( '#hrr_order_id' ).val() ,
                    request_id : $( '#hrr_post_id' ).val() ,
                    refund_amount : $( '#hrr_refund_total' ).val() ,
                    line_item_qtys : JSON.stringify( line_item_qtys , null , '' ) ,
                    line_item_totals : JSON.stringify( line_item_totals , null , '' ) ,
                    line_item_tax_totals : JSON.stringify( line_item_tax_totals , null , '' ) ,
                    api_refund : $( this ).attr( 'data-paytype' ) ,
                    restock_refunded_items : $( '#hrr_restock_products:checked' ).length ? 'true' : 'false' ,
                    hrr_security : hrr_request_params.button_nonce
                } ;

                $.post( ajaxurl , data , function ( response ) {
                    if ( true === response.success ) {
                        HRR_Request_view.unblock( '.hrr-refund-request' ) ;
                        window.location.href = window.location.href ;
                    } else {
                        window.alert( response.data.error ) ;
                        HRR_Request_view.unblock( '.hrr-refund-request' ) ;
                    }
                } ) ;
            }
        } ,
        change_status : function ( event ) {
            event.preventDefault() ;
            HRR_Request_view.block( '.hrr_refund_status_change' ) ;
            var data = {
                action : 'hrr_update_status' ,
                request_id : $( '#post_ID' ).val() ,
                status : $( '#hrr_status' ).val() ,
                hrr_security : hrr_request_params.status_nonce
            } ;

            $.post( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    HRR_Request_view.unblock( '.hrr_refund_status_change' ) ;
                    window.location.href = window.location.href ;
                } else {
                    window.alert( response.data.error ) ;
                    HRR_Request_view.unblock( '.hrr_refund_status_change' ) ;
                }
            } ) ;
        } ,
        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        unblock : function ( id ) {
            $( id ).unblock() ;
        } ,
    } ;
    HRR_Request_view.init() ;
} ) ;
