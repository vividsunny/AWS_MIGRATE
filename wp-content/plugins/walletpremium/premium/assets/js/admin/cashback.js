/* global hrw_cashback_params, ajaxurl */
jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Cashback = {

        init : function ( ) {
            this.trigger_on_load_function( ) ;

            $( document ).on( 'change' , '#hrw_cashback_issue_type' , this.promocode_type ) ;
            $( document ).on( 'change' , '.hrw_cashback_rule_type' , this.cashback_rule_type ) ;
            $( document ).on( 'change' , '.hrw_user_filter_type' , this.user_filter_type ) ;
            $( document ).on( 'change' , '.hrw_product_filter_type' , this.product_filter_type ) ;
            $( document ).on( 'change' , '.hrw_purchase_history_type' , this.purchase_history_type ) ;

            $( document ).on( 'click' , '.hrw_toggle_rule' , this.toggle_rule ) ;
            $( document ).on( 'click' , '.hrw_add_cashback_popup' , this.add_cashback_popup ) ;
            $( document ).on( 'click' , '.hrw_close_popup_wrapper' , this.close_cashback_popup ) ;
            $( document ).on( 'click' , '.hrw_add_cashback_rule' , this.add_cashback_rule ) ;
            $( document ).on( 'click' , '.hrw_add_cashback_product_rule' , this.add_cashback_product_rule ) ;
            $( document ).on( 'click' , '.hrw_add_cashback_order_rule' , this.add_cashback_order_rule ) ;
            $( document ).on( 'click' , '.hrw_add_cashback_wallet_rule' , this.add_cashback_wallet_rule ) ;
            $( document ).on( 'click' , '.hrw_remove_cashback_rule' , this.remove_local_rule ) ;
            $( document ).on( 'click' , '.hrw_delete_cashback_rule' , this.delete_cashback_rule ) ;
        } , trigger_on_load_function : function ( ) {
            $( '.hrw_cashback_rule_type' ).each( function () {
                HRW_Cashback.show_or_hide_rule_table( $( this ) ) ;
            } ) ;

            $( '.hrw_user_filter_type' ).each( function () {
                HRW_Cashback.show_or_hide_user_filter( $( this ) ) ;
            } ) ;

            $( '.hrw_product_filter_type' ).each( function () {
                HRW_Cashback.show_or_hide_product_filter( $( this ) ) ;
            } ) ;

            $( '.hrw_purchase_history_type' ).each( function () {
                HRW_Cashback.show_or_hide_purchase_history_type( $( this ) ) ;
            } ) ;

            $( '.hrw_cashback_rule_content_wrapper' ).each( function () {
                HRW_Cashback.toggle_rule_content( $( this ) ) ;
            } ) ;

            HRW_Cashback.promocode_type() ;
        } , toggle_rule : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Cashback.toggle_rule_content( $this ) ;

        } , toggle_rule_content : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_cashback_rule' ) ;
            rule_div.find( '.hrw_cashback_rule_content_wrapper' ).toggle() ;
        } , promocode_type : function ( ) {
            if ( $( '#hrw_cashback_issue_type' ).val() == '1' ) {
                $( '#hrw_cashback_promocode' ).closest( 'tr' ).hide() ;
            } else {
                $( '#hrw_cashback_promocode' ).closest( 'tr' ).show() ;
            }
        } , cashback_rule_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Cashback.show_or_hide_rule_table( $this ) ;
        } , show_or_hide_rule_table : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_cashback_rule' ) ;
            if ( $( $this ).val( ) == '1' ) {
                rule_div.find( '.hrw_cashback_order_table' ).show() ;
                rule_div.find( '.hrw_hide_for_topup' ).show() ;
                HRW_Cashback.show_or_hide_product_filter( '.hrw_product_filter_type' ) ;
                rule_div.find( '.hrw_cashback_wallet_table' ).hide() ;
                rule_div.find( '.hrw_local_rule_priority' ).show() ;
                rule_div.find( '.hrw_cashback_order_total_type' ).show() ;
            } else if ( $( $this ).val( ) == '2' ) {
                rule_div.find( '.hrw_cashback_order_table' ).hide() ;
                rule_div.find( '.hrw_hide_for_topup' ).hide() ;
                rule_div.find( '.hrw_local_rule_priority' ).show() ;
                rule_div.find( '.hrw_cashback_wallet_table' ).show() ;
                rule_div.find( '.hrw_cashback_order_total_type' ).hide() ;
            }
        } , user_filter_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Cashback.show_or_hide_user_filter( $this ) ;
        } , show_or_hide_user_filter : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_cashback_rule' ) ;
            if ( $( $this ).val( ) == '1' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '2' ) {
                rule_div.find( '.hrw_inc_user_selection' ).show() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '3' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).show() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '4' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).show() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).show() ;
            }
        } , product_filter_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Cashback.show_or_hide_product_filter( $this ) ;
        } , show_or_hide_product_filter : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_cashback_rule' ) ;
            if ( ( $( $this ).val( ) == '1' ) || ( $( $this ).val( ) == '4' ) || ( $( $this ).val( ) == '7' ) ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '2' ) {
                rule_div.find( '.hrw_inc_product_selection' ).show() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '3' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).show() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '5' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).show() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '6' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).show() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '8' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).show() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).show() ;
            }
        } , purchase_history_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Cashback.show_or_hide_purchase_history_type( $this ) ;
        } , show_or_hide_purchase_history_type : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_cashback_rule' ) ;
            if ( $( $this ).val( ) == '1' ) {
                rule_div.find( '.hrw_no_of_order' ).show() ;
                rule_div.find( '.hrw_total_amount' ).hide() ;
            } else {
                rule_div.find( '.hrw_no_of_order' ).hide() ;
                rule_div.find( '.hrw_total_amount' ).show() ;
            }
        } , add_cashback_popup : function ( event ) {
            event.preventDefault( ) ;
            $( '.hrw_cashback_rule_popup_wrapper' ).find( '#hrw_rule_name' ).val( '' ) ;
            $( '.hrw_cashback_rule_popup_wrapper' ).show( ) ;
        } , close_cashback_popup : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ,
                    popup = $( $this ).closest( 'div.hrw_cashback_rule_popup_wrapper' ) ;
            popup.hide( ) ;
        } , add_cashback_rule : function ( event ) {
            event.preventDefault( ) ;
            HRW_Cashback.block( '.hrw_cashback_rule_wrapper' ) ;
            var data = {
                action : 'hrw_add_cashback_rule' ,
                rule_name : $( '#hrw_rule_name' ).val( ) ,
                hrw_security : hrw_cashback_params.cashback_nonce
            } ;
            $.post( hrw_cashback_params.ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( '.hrw_cashback_rule_wrapper' ).append( res.data.field ) ;
                    $( '.hrw_close_popup_wrapper' ).closest( 'div.hrw_cashback_rule_popup_wrapper' ).hide( ) ;
                    HRW_Cashback.promocode_type() ;
                    $( '.hrw_cashback_rule_type' ).each( function () {
                        HRW_Cashback.show_or_hide_rule_table( $( this ) ) ;
                    } ) ;
                    HRW_Cashback.show_or_hide_user_filter( '.hrw_user_filter_type' ) ;
                    HRW_Cashback.show_or_hide_product_filter( '.hrw_product_filter_type' ) ;
                    HRW_Cashback.show_or_hide_purchase_history_type( '.hrw_purchase_history_type' ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                $( document.body ).trigger( 'hrw-enhanced-init' ) ;
                HRW_Cashback.unblock( '.hrw_cashback_rule_wrapper' ) ;
            } ) ;
        } , add_cashback_product_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Cashback.block( '.hrw_cashback_product_table_wrapper' ) ;
            var data = {
                action : 'hrw_add_cashback_product_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_cashback_params.cashback_nonce
            } ;
            $.post( hrw_cashback_params.ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_cashback_product_table' ).find( '.hrw_cashback_product_table_wrapper' ).append( res.data.field ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                $( document.body ).trigger( 'hrw-enhanced-init' ) ;
                HRW_Cashback.unblock( '.hrw_cashback_product_table_wrapper' ) ;
            } ) ;
        } , add_cashback_order_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Cashback.block( '.hrw_cashback_order_table_wrapper' ) ;
            var data = {
                action : 'hrw_add_cashback_order_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_cashback_params.cashback_nonce
            } ;
            $.post( hrw_cashback_params.ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_cashback_order_table' ).find( '.hrw_cashback_order_table_wrapper' ).append( res.data.field ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                HRW_Cashback.unblock( '.hrw_cashback_order_table_wrapper' ) ;
            } ) ;
        } , add_cashback_wallet_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Cashback.block( '.hrw_cashback_wallet_table_wrapper' ) ;
            var data = {
                action : 'hrw_add_cashback_wallet_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_cashback_params.cashback_nonce
            } ;
            $.post( hrw_cashback_params.ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_cashback_wallet_table' ).find( '.hrw_cashback_wallet_table_wrapper' ).append( res.data.field ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                HRW_Cashback.unblock( '.hrw_cashback_wallet_table_wrapper' ) ;
            } ) ;
        } , delete_cashback_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Cashback.block( '.hrw_cashback_rule_wrapper' ) ;
            var data = {
                action : 'hrw_delete_cashback_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_cashback_params.cashback_nonce
            } ;
            $.post( hrw_cashback_params.ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_cashback_rule' ).remove( ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                HRW_Cashback.unblock( '.hrw_cashback_rule_wrapper' ) ;
            } ) ;
        } , remove_local_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            $( $this ).closest( 'tr' ).remove( ) ;
        } , block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $( id ).unblock( ) ;
        }
    } ;
    HRW_Cashback.init( ) ;
} ) ;