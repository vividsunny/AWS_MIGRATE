<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Admin_Assets' ) ) {

    /**
     * Class.
     */
    class HRW_Admin_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
        }

        /**
         * Enqueue external css files
         */
        public static function external_css_files() {

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            $screen_ids   = hrw_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            wp_enqueue_style( 'hrw-admin' , HRW_PLUGIN_URL . '/assets/css/backend/admin.css' , array() , HRW_VERSION ) ;

            if ( ! in_array( $screenid , $screen_ids ) )
                return ;

            wp_enqueue_style( 'font-awesome' , HRW_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'hrw-submenu' , HRW_PLUGIN_URL . '/assets/css/backend/submenu.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'jquery-ui' , HRW_PLUGIN_URL . '/assets/css/jquery-ui' . $suffix . '.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'hrw-posttable' , HRW_PLUGIN_URL . '/assets/css/backend/post-table.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'hrw-modules' , HRW_PLUGIN_URL . '/assets/css/backend/module.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'hrw-notification' , HRW_PLUGIN_URL . '/assets/css/backend/notification.css' , array() , HRW_VERSION ) ;

            do_action( 'hrw_admin_after_enqueue_css' ) ;
        }

        /**
         * Enqueue external js files
         */
        public static function external_js_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            $screen_ids   = hrw_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            $enqueue_array = array(
                'hrw-admin'   => array(
                    'callable' => array( 'HRW_Admin_Assets' , 'admin' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                'hrw-select2' => array(
                    'callable' => array( 'HRW_Admin_Assets' , 'select2' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                'hrw-upgrade' => array(
                    'callable' => array( 'HRW_Admin_Assets' , 'upgrade' ) ,
                    'restrict' => true ,
                ) ,
                    ) ;

            $enqueue_array = apply_filters( 'hrw_admin_assets' , $enqueue_array ) ;
            if ( ! hrw_check_is_array( $enqueue_array ) )
                return ;

            foreach ( $enqueue_array as $key => $enqueue ) {
                if ( ! hrw_check_is_array( $enqueue ) )
                    continue ;

                if ( $enqueue[ 'restrict' ] )
                    call_user_func_array( $enqueue[ 'callable' ] , array( $suffix ) ) ;
            }
        }

        /**
         * Enqueue Admin end required JS files
         */
        public static function admin( $suffix ) {

            //Settings
            wp_enqueue_script( 'hrw-settings' , HRW_PLUGIN_URL . '/assets/js/admin/settings.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-settings' , 'hrw_settings_params' , array(
                'notification_nonce'        => wp_create_nonce( 'hrw-notification-nonce' ) ,
                'module_nonce'              => wp_create_nonce( 'hrw-module-nonce' ) ,
                'wallet_product_nonce'      => wp_create_nonce( 'hrw-wallet-product-nonce' ) ,
                'wallet_credit_debit_nonce' => wp_create_nonce( 'hrw-wallet-credit-debit-nonce' ) ,
                    )
            ) ;
            //Wallet
            wp_enqueue_script( 'hrw-wallet' , HRW_PLUGIN_URL . '/assets/js/admin/wallet.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-wallet' , 'hrw_wallet_params' , array(
                'wallet_user_id'     => get_current_user_id() ,
                'credit_debit_nonce' => wp_create_nonce( 'hrw-wallet-credit-debit-nonce' ) ,
                    )
            ) ;
        }

        /**
         * Enqueue upgrade
         */
        public static function upgrade( $suffix ) {
            //Block UI
            wp_register_script( 'blockUI' , HRW_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;

            //Upgrade
            wp_enqueue_script( 'hrw-upgrade' , HRW_PLUGIN_URL . '/assets/js/admin/upgrade.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-upgrade' , 'hrw_upgrade_params' , array(
                'upgrade_nonce' => wp_create_nonce( 'hrw-upgrade-nonce' ) ,
                'empty_msg'     => esc_html__( 'Please provide License key' , HRW_LOCALE ) ,
                    )
            ) ;
        }

        /**
         * Enqueue select2 scripts and CSS
         */
        public static function select2( $suffix ) {
            wp_enqueue_style( 'select2' , HRW_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;

            wp_register_script( 'select2' , HRW_PLUGIN_URL . '/assets/js/select2/select2.full' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
            wp_enqueue_script( 'hrw-enhanced' , HRW_PLUGIN_URL . '/assets/js/hrw-enhanced.js' , array( 'jquery' , 'select2' , 'jquery-ui-datepicker' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-enhanced' , 'hrw_enhanced_select_params' , array(
                'search_nonce' => wp_create_nonce( 'hrw-search-nonce' ) ,
                'ajaxurl'      => HRW_ADMIN_AJAX_URL
                    )
            ) ;
        }

    }

    HRW_Admin_Assets::init() ;
}
