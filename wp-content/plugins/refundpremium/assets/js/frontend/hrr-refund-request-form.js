/* global  hrr_form_params */

jQuery( function ( $ ) {

    var HRR_Request_form = {
        init : function () {
            $( '#hrr-refund-form' ).on( 'submit' , this.save_refund_request_form ) ;
        } ,
        save_refund_request_form : function ( event ) {
            event.preventDefault() ;
            if (hrr_form_params.mandatory_reason_field == '1' &&  $( 'textarea#hrr_refund_form_details' ).val() == '' ) {
                alert( hrr_form_params.refund_reason_message ) ;
                return false;
            }
            
            var con = confirm( hrr_form_params.refund_request_message ) ;
            if ( con ) {
                $( 'submit#hrr_refund_submit' ).css( 'disabled' , 'disabled' ) ;
                $( 'img#hrr_refund_img' ).css( 'display' , 'block' ) ;
                var data = new FormData ( this ) ;
                // Get refund items
                var total = 0 ;
                var line_items = { } ;
                var line_item_ids = { } ;
                $( "tr.hrr_refund_items td.hrr_refund_item_data" ).each( function ( index , item ) {
                    var checkbox = $( item ).closest( 'tr.hrr_refund_items' ).find( 'input.hrr_refund_enable_product' ) ;
                    if ( checkbox.length <= 0 || checkbox.is( ":checked" ) ) {
                        total = total + 1 ;
                        item_id = $( item ).find( '.hrr_refund_request_item_id' ).val() ;
                        item_qty = parseInt( $( item ).find( '.hrr_refund_request_qty' ).val() ) ;
                        line_items[item_id] = item_qty ;
                        line_item_ids[item_id] = item_id ;
                    }
                } ) ;

                if ( total <= 0 ) {
                    $( 'submit#hrr_refund_submit' ).attr( 'disabled' , 'disabled' ) ;
                    $( 'img#hrr_refund_img' ).css( 'display' , 'none' ) ;
                    alert( 'Please Select a Product to refund' ) ;
                } else {                    
                    data.append ( 'action' , 'hrr_refund_request' ) ;
                    data.append ( 'line_item_ids' , JSON.stringify ( line_item_ids ) ) ;
                    data.append ( 'line_items' , JSON.stringify ( line_items ) ) ;
                    data.append ( 'hrr_security' , hrr_form_params.request_form_security ) ;

                    var obj = $.ajax( {
                        type : 'POST' ,
                        url : hrr_form_params.ajax_url ,
                        data : data ,
                        dataType : 'json' ,
                        processData : false ,
                        contentType : false ,
                        success : function ( response , status ) {
                            if ( response.success ) {
                                $( 'submit#hrr_refund_submit' ).attr( 'disabled' , 'disabled' ) ;
                                $( 'img#hrr_refund_img' ).css( 'display' , 'none' ) ;
                                $( 'p#hrr_refund_message' ).css( 'display' , 'block' ) ;
                                window.location.href = hrr_form_params.redirect_url ;
                            } else {
                                window.alert ( response.data.error ) ;
                            }

                        } ,
                    } ) ;
                }
            }
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
        }
    } ;
    HRR_Request_form.init() ;
} ) ;
