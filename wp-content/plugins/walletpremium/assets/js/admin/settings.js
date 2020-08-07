/* global hrw_settings_params, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Settings = {
        init : function ( ) {
            this.trigger_onload_function() ;
            //Hide Message
            $( document ).on( 'click' , '.hrw_save_close' , this.hide_message_popup ) ;
            //toggle section
            $( document ).on( 'click' , 'div.hrw_section_start h2' , this.toggle_section ) ;
            //enable/disable notifications
            $( document ).on( 'change' , '.hrw_notifications_enabled' , this.toggle_notifications_enabled ) ;
            //enable/disable modules
            $( document ).on( 'change' , '.hrw_modules_enabled' , this.toggle_module_enabled ) ;
            //general setting
            $( document ).on( 'change' , '#hrw_general_topup_product_type' , this.toggle_product_selection ) ;
            $( document ).on( 'change' , '#hrw_advanced_round_off_type' , this.toggle_decimal_points ) ;
            $( document ).on( 'click' , '#hrw_create_product_btn' , this.create_topup_product ) ;
            //advance setting
            $( document ).on( 'change' , '#hrw_advanced_topup_user_restriction_type' , this.toggle_topup_allowed_user_restriction ) ;
            $( document ).on( 'change' , '#hrw_advanced_wallet_usage_product_restriction_type' , this.toggle_wallet_balance_purchasing_restriction ) ;
            $( document ).on( 'change' , '#hrw_advanced_wallet_usage_user_restriction_type' , this.toggle_wallet_usage_restriction ) ;
            $( document ).on( 'change' , '#hrw_general_enable_partial_payment' , this.toggle_partial_payment ) ;
            $( document ).on( 'change' , '#hrw_general_topup_amount_type' , this.toggle_wallet_fund_type ) ;
            //credit debit tab
            $( document ).on( 'click' , '#hrw_credit_debit_btn' , this.wallet_credit_debit ) ;
        } , trigger_onload_function : function ( ) {
            HRW_Settings.product_selection( '#hrw_general_topup_product_type' ) ;
            HRW_Settings.topup_allowed_user_restriction( '#hrw_advanced_topup_user_restriction_type' ) ;
            HRW_Settings.wallet_balance_purchasing_restriction( '#hrw_advanced_wallet_usage_product_restriction_type' ) ;
            HRW_Settings.decimal_points( '#hrw_advanced_round_off_type' ) ;
            HRW_Settings.wallet_usage_restriction( '#hrw_advanced_wallet_usage_user_restriction_type' ) ;
            HRW_Settings.partial_payment( '#hrw_general_enable_partial_payment' ) ;
            HRW_Settings.wallet_fund_type( '#hrw_general_topup_amount_type' ) ;
        } , hide_message_popup : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            $( $this ).closest( 'div.hrw_save_msg' ).remove() ;

        } , wallet_credit_debit : function ( event ) {
            event.preventDefault( ) ;
            HRW_Settings.block( 'div.hrw_section_start' ) ;
            var data = {
                action : 'hrw_wallet_credit_debit' ,
                wallet_id : $( '#hr_select_customers' ).val() ,
                type : $( '#hr_wallet_send_funds_type' ).val() ,
                amount : $( '#hr_wallet_send_funds_value' ).val() ,
                event : $( '#hr_wallet_send_reason' ).val() ,
                hrw_security : hrw_settings_params.wallet_credit_debit_nonce
            }
            $.post( ajaxurl , data , function ( res ) {
                HRW_Settings.unblock( 'div.hrw_section_start' ) ;
                if ( true === res.success ) {
                    alert( res.data.msg ) ;
                    location.reload( true ) ;
                } else {
                    alert( res.data.error ) ;
                }
            } ) ;
        } , create_topup_product : function ( event ) {
            event.preventDefault( ) ;

            var wallet_product_name = $( '#hrw_general_topup_product_name' ).val() ;

            if ( wallet_product_name == '' ) {
                wallet_product_name = 'Wallet Product' ;
            }

            var data = {
                action : 'hrw_create_topup_product' ,
                wallet_product_name : wallet_product_name ,
                hrw_security : hrw_settings_params.wallet_product_nonce
            }
            $.post( ajaxurl , data , function ( res ) {
                if ( true === res.success ) {
                    location.reload( true ) ;
                } else {
                    alert( res.data.error ) ;
                }
            } ) ;
        } , toggle_section : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            $( $this ).nextUntil().toggle() ;

        } , toggle_notifications_enabled : function ( event ) {

            event.preventDefault( ) ;

            var $this = $( event.currentTarget ) ,
                    type = $( $this ).is( ':checked' ) ,
                    closest = $( $this ).closest( 'div.hrw_notifications_grid' ) ,
                    name = closest.find( '.hrw_notification_name' ).val( ) ,
                    grid_inner = closest.find( '.hrw_notifications_grid_inner' ) ;

            HRW_Settings.block( closest ) ;

            var data = {
                action : 'hrw_toggle_notifications' ,
                enabled : type ,
                notification_name : name ,
                hrw_security : hrw_settings_params.notification_nonce
            } ;

            $.post( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    if ( type ) {
                        closest.find( '.hrw_settings_link' ).show( ) ;
                        grid_inner.removeClass( 'hrw_notification_inactive' ).addClass( 'hrw_notification_active' ) ;
                    } else {
                        closest.find( '.hrw_settings_link' ).hide( ) ;
                        grid_inner.removeClass( 'hrw_notification_active' ).addClass( 'hrw_notification_inactive' ) ;
                    }
                } else {
                    window.alert( res.data.error ) ;
                }

                HRW_Settings.unblock( closest ) ;

            } ) ;
        } , toggle_module_enabled : function ( event ) {

            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ,
                    type = $( $this ).is( ':checked' ) ,
                    closest = $( $this ).closest( 'div.hrw_modules_grid' ) ,
                    name = closest.find( '.hrw_module_name' ).val( ) ,
                    grid_inner = closest.find( '.hrw_modules_grid_inner' ) ;

            HRW_Settings.block( closest ) ;

            var data = {
                action : 'hrw_toggle_module' ,
                enabled : type ,
                module_name : name ,
                hrw_security : hrw_settings_params.module_nonce
            } ;
            $.post( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    if ( type ) {
                        closest.find( '.hrw_settings_link' ).show( ) ;
                        grid_inner.removeClass( 'hrw_module_inactive' ).addClass( 'hrw_module_active' ) ;
                    } else {
                        closest.find( '.hrw_settings_link' ).hide( ) ;
                        grid_inner.removeClass( 'hrw_module_active' ).addClass( 'hrw_module_inactive' ) ;
                    }
                } else {
                    window.alert( res.data.error ) ;
                }

                HRW_Settings.unblock( closest ) ;

            } ) ;
        } , toggle_product_selection : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.product_selection( $this ) ;
        } ,toggle_decimal_points : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.decimal_points( $this ) ;
        } ,
        toggle_topup_allowed_user_restriction : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.topup_allowed_user_restriction( $this ) ;
        } ,
        toggle_wallet_balance_purchasing_restriction : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.wallet_balance_purchasing_restriction( $this ) ;
        } ,
        toggle_wallet_usage_restriction : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.wallet_usage_restriction( $this ) ;
        } ,
        toggle_partial_payment : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.partial_payment( $this ) ;
        } ,
        toggle_wallet_fund_type : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Settings.wallet_fund_type( $this ) ;
        } ,
        product_selection : function ( $this ) {
            $( '.hrw_product_selection' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '1' ) {
                $( '#hrw_general_topup_product_name' ).closest( 'tr' ).show() ;
                $( '#hrw_create_product_btn' ).closest( 'tr' ).show() ;
            } else {
                $( '#hrw_general_topup_product_id' ).closest( 'tr' ).show() ;
            }
        } ,
        topup_allowed_user_restriction : function ( $this ) {
            $( '.hrw_topup_allowed_users_restriction' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '2' ) {
                $( '#hrw_advanced_topup_user_restriction' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '3' ) {
                $( '#hrw_advanced_topup_user_role_restriction' ).closest( 'tr' ).show() ;
            }
        } ,
        wallet_balance_purchasing_restriction : function ( $this ) {
            $( '.hrw_wallet_bal_pruchase_restriction' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '2' ) {
                $( '#hrw_advanced_wallet_usage_product_restriction' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '4' ) {
                $( '#hrw_advanced_wallet_usage_category_restriction' ).closest( 'tr' ).show() ;
            }
        } ,
        decimal_points : function ( $this ) {
            $( '.hrw_decimal_points' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '1' ) {
                $( '#hrw_advanced_round_off_type' ).closest( 'tr' ).show() ;
                $( '#hrw_advanced_round_off_method' ).closest( 'tr' ).hide() ;
            } else if ( $( $this ).val() === '2' ) {
                $( '#hrw_advanced_round_off_type' ).closest( 'tr' ).show() ;
                $( '#hrw_advanced_round_off_method' ).closest( 'tr' ).show() ;
            } else {
                $( '#hrw_advanced_round_off_type' ).closest( 'tr' ).show() ;
                $( '#hrw_advanced_round_off_method' ).closest( 'tr' ).hide() ;
            }
        } ,
        wallet_usage_restriction : function ( $this ) {
            $( '.hrw_usage_allowed_restriction' ).closest( 'tr' ).hide() ;
            if ( $( $this ).val() === '2' ) {
                $( '#hrw_advanced_wallet_usage_user_restriction' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() === '3' ) {
                $( '#hrw_advanced_wallet_usage_user_role_restriction' ).closest( 'tr' ).show() ;
            }
        } ,
        partial_payment : function ( $this ) {
            $( '.hrw_partial_payment' ).closest( 'tr' ).hide() ;
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.hrw_partial_payment' ).closest( 'tr' ).show() ;
            }
        } ,
        wallet_fund_type : function ( $this ) {
            $( '.hrw_prefilled_amount' ).closest( 'tr' ).show() ;
            $( '.hrw_prefilled_amount_min_max' ).closest( 'tr' ).show() ;

            if ( $( $this ).val() === '1' ) {
                $( '#hrw_general_topup_prefilled_amount' ).closest( 'tr' ).hide() ;
            } else if ( $( $this ).val() === '3' || $( $this ).val() === '4' ) {
                $( '.hrw_prefilled_amount_min_max' ).closest( 'tr' ).hide() ;
            }
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
    HRW_Settings.init( ) ;
} ) ;
