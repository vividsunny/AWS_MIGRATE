/* global  hrrp_form_params */

jQuery( function ( $ ) {

    var HRRP_Request = {

        init : function () {
            //Reply to Conversation.
            $ ( '#hrr-conversation-form' ).on ( 'submit' , this.reply_message ) ;
            //Unsubscribe email from Refund.
            $( document ).on( 'change' , '#hrr_refund_unsubscribed_id' , this.unsubscribe_email ) ;

            //Update Refund Total.
            $( document ).on( 'hrr_update_refund_total' , this.update_total ) ;
            //Update Refund Total while Quantity Update.
            $( document ).on( 'change' , 'input#hrr_refund_item_qty' , this.update_qty ) ;
            //Update Refund Total when all product is selected.
            $( document ).on( 'change' , 'input.hrr_refund_select_all_product' , this.select_all_products ) ;
            //Update Refund Total when individual product is selected.
            $( document ).on( 'change' , 'input#hrr_refund_enable_product' , this.selected_products ) ;
        } ,
        reply_message : function ( event ) {
            event.preventDefault() ;
            var con = confirm( hrrp_form_params.refund_save_message ) ;
            HRRP_Request.block( '.hrr_refund_reply_request' ) ;
            if ( con ) {
                var form_data = new FormData ( this ) ;
                form_data.append ( 'action' , 'hrr_refund_save_message' ) ;
                form_data.append ( 'request_id' , $ ( '#hrr_refund_reply_request_id' ).val () ) ;
                form_data.append ( 'message' , $ ( '.hrr-refund-reply-content' ).val () ) ;
                form_data.append ( 'hrr_security' , hrrp_form_params.request_message_security ) ;
                
                $.ajax ( {
                    type : 'POST' ,
                    url : hrrp_form_params.ajaxurl ,
                    data : form_data ,
                    dataType : 'json' ,
                    processData : false ,
                    contentType : false ,
                    success : function ( response , status ) {
                        if ( response.success ) {
                            window.location.href = window.location.href ;
                        } else {
                            window.alert ( response.data.error ) ;
                        }
                        HRRP_Request.unblock ( '.hrr_refund_reply_request' ) ;
                    } ,

                } ) ;
            } else {
                HRRP_Request.unblock( '.hrr_refund_reply_request' ) ;
            }
        } ,
        unsubscribe_email : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var con = confirm( hrrp_form_params.refund_unsubcribe_message ) ;
            HRRP_Request.block( '.hrr_refund_unsubscribe' ) ;
            if ( con ) {
                var data = {
                    action : 'hrr_refund_unsubscribe' ,
                    dataclicked : $( $this ).is( ':checked' ) ? 'false' : 'true' ,
                    hrr_security : hrrp_form_params.hrr_unsubcribe_nonce
                } ;
                $.post( hrrp_form_params.ajax_url , data , function ( response ) {
                    if ( true === response.success ) {
                        window.alert( response.data.alert_message ) ;
                        HRRP_Request.unblock( '.hrr_refund_unsubscribe' ) ;
                    } else {
                        window.alert( response.data.error ) ;
                        HRRP_Request.unblock( '.hrr_refund_unsubscribe' ) ;
                    }
                } ) ;
            } else {
                HRRP_Request.unblock( '.hrr_refund_unsubscribe' ) ;
            }
        } ,
        update_total : function ( event ) {
            event.preventDefault() ;
            var refund_total = 0 ;
            var $refund_total_text = $( 'td.hrr_refund_item_total_value span.amount' ) ;
            var $buttons_text = $( 'button.hrr_refund_request_button span.amount' ) ;
            var $refund_total_value = $( 'input#hrr_refund_total' ) ;
            //get total value
            $( 'input.hrr_refund_request_subtotal' ).each( function ( i ) {
                var checkbox = $( this ).closest( 'tr.hrr_refund_items' ).find( 'input.hrr_refund_enable_product' ) ;
                if ( checkbox.length <= 0 || checkbox.is( ":checked" ) ) {
                    refund_total += parseFloat( $( this ).val() ) ;
                }
            } ) ;

            //Update values.
            $refund_total_value.val( refund_total ) ;
            //Format Price.
            refund_total = HRRP_Request.format_price( refund_total ) ;
            //Display text.
            $buttons_text.text( refund_total ) ;
            $refund_total_text.text( refund_total ) ;
        } ,
        update_qty : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var $row = $( $this ).closest( 'tr' ) ;
            var item_qty = parseInt( $( $this ).val() ) ;
            var $refund_data = $row.find( 'td.hrr_refund_item_data' ) ;
            var item_value = parseFloat( $refund_data.find( 'input.hrr_refund_request_price' ).val() ) ;
            var tax_value = parseFloat( $refund_data.find( 'input.hrr_refund_request_tax_value' ).val() ) ;
            var refund_subtotal = ( item_value + tax_value ) * item_qty ;
            $refund_data.find( 'input.hrr_refund_request_qty' ).val( item_qty ) ;
            $refund_data.find( 'input.hrr_refund_request_subtotal' ).val( refund_subtotal ) ;

            var $refund_subtotal = $row.find( 'td.hrr_refund_item_subtotal' ) ;
            refund_subtotal = HRRP_Request.format_price( refund_subtotal ) ;
            $refund_subtotal.text( refund_subtotal ) ;

            $( this ).trigger( 'hrr_update_refund_total' ) ;
        } ,
        select_all_products : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var $refund_subtotal = $( 'td.hrr_refund_item_subtotal' ) ;
            $( 'input.hrr_refund_select' ).each( function () {
                if ( $( $this ).is( ":checked" ) ) {
                    $( this ).attr( "checked" , true ) ;
                    $( 'tr.hrr_refund_items' ).each( function ( ) {
                        var checkbox = $( this ).find( 'input.hrr_refund_enable_product' ) ;
                        if ( checkbox.length <= 0 || checkbox.is( ":checked" ) ) {
                            var refund_subtotal = HRRP_Request.format_price( $( this ).find( 'input.hrr_refund_request_subtotal' ).val() ) ;
                            $( this ).find( 'td.hrr_refund_item_subtotal' ).text( refund_subtotal ) ;
                        }
                    } ) ;
                } else {
                    $( this ).attr( "checked" , false ) ;
                    var refund_subtotal = HRRP_Request.format_price( 0 ) ;
                    $refund_subtotal.text( refund_subtotal ) ;
                }
            } ) ;

            $( this ).trigger( 'hrr_update_refund_total' ) ;
        } ,
        selected_products : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            var $row = $( $this ).closest( 'tr' ) ;
            if ( $( $this ).is( ":checked" ) ) {
                $row.find( 'input.hrr_refund_item_qty' ).removeAttr( "readonly" ) ;
                var item_qty = parseInt( $row.find( 'input.hrr_refund_item_qty' ).val() ) ;
                var $refund_data = $row.find( 'td.hrr_refund_item_data' ) ;
                var item_value = parseFloat( $refund_data.find( 'input.hrr_refund_request_price' ).val() ) ;
                var tax_value = parseFloat( $refund_data.find( 'input.hrr_refund_request_tax_value' ).val() ) ;
                var refund_subtotal = ( item_value + tax_value ) * item_qty ;
                var $refund_subtotal = $row.find( 'td.hrr_refund_item_subtotal' ) ;
                refund_subtotal = HRRP_Request.format_price( refund_subtotal ) ;
                $refund_subtotal.text( refund_subtotal ) ;
            } else {
                var $refund_subtotal = $row.find( 'td.hrr_refund_item_subtotal' ) ;
                var refund_subtotal = HRRP_Request.format_price( 0 ) ;
                $refund_subtotal.text( refund_subtotal ) ;
                $row.find( 'input.hrr_refund_item_qty' ).attr( "readonly" , "readonly" ) ;
            }
            $( this ).trigger( 'hrr_update_refund_total' ) ;
        } ,
        format_price : function ( price ) {
            price = accounting.formatMoney( price , {
                symbol : hrrp_form_params.currency_symbol ,
                decimal : hrrp_form_params.decimal_seperator ,
                thousand : hrrp_form_params.thousand_seperator ,
                precision : hrrp_form_params.price_decimals ,
                format : hrrp_form_params.currency_format
            } ) ;
            return price ;
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
    HRRP_Request.init() ;
} ) ;
