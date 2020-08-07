<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( !defined( 'YITH_WCACT_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Auctions_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 *
 */

if ( !class_exists( 'YITH_Auction_Admin_Premium' ) ) {
    /**
     * Class YITH_Auctions_Admin
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
     */
    class YITH_Auction_Admin_Premium extends YITH_Auction_Admin
    {

        /**
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function __construct()
        {
            /* === Register Panel Settings === */
            $this->show_premium_landing = false;

            add_filter('yith_wcact_admin_tabs',array($this,'yith_add_tabs'));
            add_filter('yith_wcact_settings_options',array($this,'yith_add_data_panel_settings_options'));

            add_action('yith_before_auction_tab',array($this,'yith_before_auction_tab'));
            add_action('yith_after_auction_tab',array($this,'yith_after_auction_tab'));

            if ( isset($_REQUEST['yith-wcact-action']) && 'regenerate_auction_prices' === $_REQUEST['yith-wcact-action'] ) {
                add_action('init', array($this, 'regenerate_auction_prices'));
            }

            /* Register plugin to licence/update system */
            add_action('wp_loaded', array($this, 'register_plugin_for_activation'), 99);
            add_action('admin_init', array($this, 'register_plugin_for_updates'));

            //Save data products
            add_action( 'woocommerce_admin_process_product_object', array( $this, 'set_product_meta_before_saving' ) );
            add_action('woocommerce_process_product_meta_' . self::$prod_type, array($this, 'save_auction_data'));

            //product columns
            add_action('yith_wcact_render_product_columns_auction_status', array($this, 'render_product_columns_premium'), 10, 3);

            add_action('pre_get_posts', array($this, 'auction_orderby'));

            add_filter('manage_edit-product_sortable_columns', array($this, 'product_sorteable_columns'));

            add_action('restrict_manage_posts', array($this, 'add_post_formats_filter'));
            add_action('pre_get_posts', array($this, 'filter_by_auction_status'));

            add_action( 'add_meta_boxes', array( $this, 'admin_list_bid' ), 30 );

            /*Duplicate products*/
            add_action('woocommerce_product_duplicate', array($this,'duplicate_products'),10,2);

            //Profile Screen Update methods
            add_action( 'show_user_profile', array( $this, 'render_auction_extra_fields' ), 20 );
            add_action( 'edit_user_profile', array( $this, 'render_auction_extra_fields' ), 20 );
            add_action( 'personal_options_update', array( $this, 'save_auction_extra_fields' ) );
            add_action( 'edit_user_profile_update', array( $this, 'save_auction_extra_fields' ) );

            if ( isset($_REQUEST['yith-wcact-action-resend-email']) && 'send_auction_winner_email' === $_REQUEST['yith-wcact-action-resend-email'] ) {
                add_action('admin_init', array($this, 'yith_wcact_send_auction_winner_email'));
            }

            add_action('woocommerce_process_product_meta',array($this,'check_if_an_auction_product'),10,2);

            add_action('woocommerce_admin_field_yith_wcact_html', array($this, 'yith_regenerate_prices'));


            add_action('init',array($this,'gutengerg_support'));



            parent::__construct();
        }

        public function gutengerg_support() {
            /* === Gutenberg Support === */
            $blocks = array(

                'yith-wcact-auction-products' => array(
                    'style'          => 'yith-wcat-auction-products',
                    'title'          => esc_html_x( 'Auction products', '[gutenberg]: block name', 'yith-auctions-for-woocommerce' ),
                    'label'          => esc_html_x( 'Print auction products', '[gutenberg]: block description', 'yith-auctions-for-woocommerce' ),
                    'shortcode_name' => 'yith_auction_products',
                    'keywords'       => array(
                    )
                ),
                'yith-wcact-auction-out-of-date' => array(
                    'style'          => 'yith-wcat-auction-out-of-date',
                    'title'          => esc_html_x( 'Auction out of date', '[gutenberg]: block name', 'yith-auctions-for-woocommerce' ),
                    'label'          => esc_html_x( 'Print out of date auction products ', '[gutenberg]: block description', 'yith-auctions-for-woocommerce' ),
                    'shortcode_name' => 'yith_auction_out_of_date',
                    'keywords'       => array(
                    )
                ),
                'yith-wcact-auction-current' => array(
                    'style'          => 'yith-wcat-auction-current',
                    'title'          => esc_html_x( 'Auction current', '[gutenberg]: block name', 'yith-auctions-for-woocommerce' ),
                    'label'          => esc_html_x( 'Print current auction products ', '[gutenberg]: block description', 'yith-auctions-for-woocommerce' ),
                    'shortcode_name' => 'yith_auction_current',
                    'keywords'       => array(
                    )
                ),
                'yith-wcact-auction-show-list-bid' => array(
                    'style'          => 'yith-wcact-auction-show-list-bid',
                    'title'          => esc_html_x( 'Auction Show list bid', '[gutenberg]: block name', 'yith-auctions-for-woocommerce' ),
                    'label'          => esc_html_x( 'Show the list bid of an auction product', '[gutenberg]: block description', 'yith-auctions-for-woocommerce' ),
                    'shortcode_name' => 'yith_auction_show_list_bid',
                    'keywords'       => array(
                    ),
                    'attributes' => array(
                        'id'                => array(
                            'type'    => 'text',
                            'label'   => esc_html_x( 'Product id to show list bid', '[gutenberg]: Option title', 'yith-auctions-for-woocommerce' ),
                            'default' => '',
                        ),
                    ),
                ),
            );

            yith_plugin_fw_gutenberg_add_blocks( $blocks );
        }

        /**
         * yith add tabs
         *
         * Add tab Appearance in yith admin settings
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return array
         */
        public function yith_add_tabs($tabs) {
            $tabs['appearance'] = esc_html__('Appearance', 'yith-auctions-for-woocommerce');
            return $tabs;
        }

        /**
         * yith_add_data_panel_settings_options
         *
         * Add premium options in setings tab
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0.11
         * @return array
         */
        public function yith_add_data_panel_settings_options($panel) {
            $regenerate_price_url = '';
            $resend_winner_email_url = add_query_arg(array('yith-wcact-action-resend-email' => 'send_auction_winner_email'));


            $panel_premium = array(
                /* General settings */
                'settings_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_options_start'
                ),

                'settings_options_title'    => array(
                    'title' => esc_html_x( 'General settings', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_options_title'
                ),

                'settings_show_auctions_shop_page' => array(
                    'title'   => esc_html_x( 'Show auctions on shop page', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to show auctions on shop page', 'Admin option description: Check this option to show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_show_auctions_shop_page',
                    'default' => 'yes'
                ),
                'settings_hide_auctions_out_of_stock' => array(
                    'title'   => esc_html_x( 'Hide out-of-stock auctions', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to hide out-of-stock auctions on shop page', 'Admin option description: Check this option to show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_hide_auctions_out_of_stock',
                    'default' => 'no'
                ),

                'settings_hide_auctions_closed' => array(
                    'title'   => esc_html_x( 'Hide closed auctions', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to hide closed auctions on shop page', 'Admin option description: Check this option to show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_hide_auctions_closed',
                    'default' => 'no'
                ),
                'settings_hide_auctions_not_started' => array(
                    'title'   => esc_html_x( 'Hide not started auctions', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to hide not started auctions on shop page', 'Admin option description: Check this option to show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_hide_auctions_not_started',
                    'default' => 'no'
                ),

                'settings_tab_auction_show_button_add_to_cart_instead_of_pay_now' => array(
                    'title'   => esc_html_x( 'Possibility to add auction product to cart ', 'Admin option: Posibility to add to cart auction product', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to allow adding auction product and other products to cart in the same order', 'Admin option description: Check this option to show pay now button in product when the auction ends', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_tab_auction_show_add_to_cart_in_auction_product',
                    'default' => 'no'
                ),

                'settings_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_options_end'
                ),
            );

            $panel_premium = array_merge($panel_premium,$panel);

            $panel_premium2 = array(
                /* Cron settings */

                'settings_cron_auction_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_cron_auction_start'
                ),

                'settings_cron_auction_options_title'    => array(
                    'title' => esc_html_x( 'Cron settings', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_cron_auction_title'
                ),

                'settings_cron_auction_send_emails' => array(
                    'title'   => esc_html_x( 'Send emails', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to send emails before auctions end', 'Admin option description: Check this option to show full Username in bid tab', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_cron_auction_send_emails',
                    'default' => 'no'
                ),
                'settings_cron_auction_number_days' => array(
                    'title'   => esc_html_x( 'Number of days/hours', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Number of days/hours before auction end date for notification', 'Admin option description: number of days', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_cron_auction_number_days',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '1'
                    ),
                    'default'           => '1'
                ),
                'settings_cron_auction_type_numbers' => array(
                    'title'   => esc_html_x( 'Select unit', 'Admin option: Select days/hours/minutes', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'select',
                    'id'      => 'yith_wcact_settings_cron_auction_type_numbers',
                    'class'   => 'wc-enhanced-select',
                    'options' => array(
                        'days' => esc_html_x('days','Admin option: days','yith-auctions-for-woocommerce'),
                        'hours'  => esc_html_x('hours','Admin option: hours','yith-auctions-for-woocommerce'),
                        'minutes' => esc_html_x('minutes','Admin option: hours','yith-auctions-for-woocommerce')
                    ),
                    'default' => 'days',
                ),
                'settings_tab_auction_show_watchlist' => array(
                    'title'   => esc_html_x( 'Allow subscribe auction', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to allow users to subscribe to an auction and be notified when it is about to end', 'Admin option description: Check this option to allow users to subscribe to an auction and be notified when it is about to end', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_tab_auction_allow_subscribe',
                    'default' => 'no'
                ),
                'settings_tab_auction_no_winner_email' => array(
                    'title'   => esc_html_x( 'Send email to customers who lost auction', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => esc_html_x( 'Check this option to send an email to users who did not win the auction after it is finished', 'Admin option description: Check this option to send an email to users who did not win the auction after it is finished', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_tab_auction_no_winner_email',
                    'default' => 'no'
                ),
                'settings_cron_auction_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_cron_auction_end'
                ),
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /* Automatic auction rescheduling */
                'settings_automatic_reschedule_auctions_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_automatic_reschedule_auctions_start'
                ),

                'settings_automatic_reschedule_auctions_title'    => array(
                    'title' => esc_html_x( 'Automatic auction rescheduling', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_automatic_reschedule_auctions_title'
                ),
                'settings_automatic_reschedule_auctions_number' => array(

                    'title'   => esc_html_x( 'Number of days/hours/minutes', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Number of days/hours/minutes to reschedule auction without bids automatically ', 'Admin option description: number of days', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_automatic_reschedule_auctions_number',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '0'
                    ),
                    'default'           => '0'
                ),
                'settings_automatic_reschedule_auctions_unit' => array(
                    'title'   => esc_html_x( 'Select unit for automatic rescheduling', 'Admin option: Select days/hours/minutes', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'select',
                    'id'      => 'yith_wcact_settings_automatic_reschedule_auctions_unit',
                    'class'   => 'wc-enhanced-select',
                    'options' => array(
                        'days' => esc_html_x('days','Admin option: days','yith-auctions-for-woocommerce'),
                        'hours'  => esc_html_x('hours','Admin option: hours','yith-auctions-for-woocommerce'),
                        'minutes' => esc_html_x('minutes','Admin option: hours','yith-auctions-for-woocommerce')
                    ),
                    'default' => 'days',
                ),

                'settings_automatic_reschedule_auctions_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_automatic_reschedule_auctions_end'
                ),

                ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /* Overtime settings */
                'settings_overtime_auction_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_overtime_auction_start'
                ),
                'settings_overtime_auction_title'    => array(
                    'title' => esc_html_x( 'Overtime settings', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_overtime_auction_title'
                ),
                'settings_overtime_auction_number_minutes' => array(
                    'title'   => esc_html_x( 'Time to add overtime', 'Admin option: Time to add overtime', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Number of minutes before auction ends to check if added overtime', 'Admin option description: Number of minutes before auction ends to check if added overtime', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_overtime_option',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '0'
                    ),
                    'default'           => '0'
                ),
                'settings_overtime_auction_overtime' => array(
                    'title'   => esc_html_x( 'Overtime', 'Admin option: Overtime', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Number of minutes for which the auction will be extended', 'Admin option description: Number of minutes for which the auction will be extended', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_overtime',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '0'
                    ),
                    'default'           => '0'
                ),
                'settings_overtime_auction_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_overtime_auction_end'
                ),
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /* Regenerate auction prices */
                'settings_regenerate_auction_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_regenerate_auction_start'
                ),

                'settings_regenerate_auction_options_title'    => array(
                    'title' => esc_html_x( 'Regenerate auction prices', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_regenerate_auction_title'
                ),
                'settings_regenerate_auction_options_button' => array(
                    'title' => esc_html__( 'Regenerate auction prices', 'yith-auctions-for-woocommerce' ),
                    'id'    => 'yith_wcact_settings_regenerate_auction_button',
                    'desc'  => esc_html__( 'Click this button to regenerate all auction prices', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'yith_wcact_html',
                    'html'  => '<a class="button" href="'.$regenerate_price_url.'">'.__( 'Regenerate auction prices', 'yith-auctions-for-woocommerce' ).'</a>',
                ),
                'settings_regenerate_auction_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_regenerate_auction_end'
                ),
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////
                /* Live auctions */
                'settings_live_auction_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_live_auction_start'
                ),

                'settings_live_auction_options_title'    => array(
                    'title' => esc_html_x( 'Live Auctions', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_live_auction_title'
                ),
                'settings_live_auction_my_auctions' => array(
                    'title'   => esc_html_x( 'Live auctions on My auctions', 'Admin option: Overtime', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Seconds to pass before checking new auction changes on My account > My auctions. Set to "0" to disable live auction on My auctions', 'Admin option description: Seconds to pass before checking new auction changes on My account > My auctions. Set to "0" to disable live auction on My auctions', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_live_auction_my_auctions',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '0'
                    ),
                    'default'           => '0'
                ),
                'settings_live_auction_product_page' => array(
                    'title'   => esc_html_x( 'Live auctions on auction product page', 'Admin option: Live auctions on auction product page', 'yith-auctions-for-woocommerce' ),
                    'type'    => 'number',
                    'desc'    => esc_html_x( 'Seconds to pass before checking new auction changes on auction product page. Set to "0" to disable live auction on product page', 'Admin option description: Seconds to pass before checking new auction changes on auction product page. Set to "0" to disable live auction on product page', 'yith-auctions-for-woocommerce' ),
                    'id'      => 'yith_wcact_settings_live_auction_product_page',
                    'custom_attributes' => array(
                        'step' => '1',
                        'min'  => '0'
                    ),
                    'default'           => '0'
                ),
                'settings_live_auction_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_regenerate_auction_end'
                ),

                /* Regenerate auction prices */
                'settings_resend_winner_auction_options_start'    => array(
                    'type' => 'sectionstart',
                    'id'   => 'yith_wcact_settings_resend_winner_auction_start'
                ),

                'settings_resend_winner_auction_options_title'    => array(
                    'title' => esc_html_x( 'Resend winner email', 'Panel: page title', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'yith_wcact_settings_resend_winner_auction_title'
                ),
                'settings_resend_winner_auction_options_button' => array(
                    'title' => esc_html__( 'Resend winner email', 'yith-auctions-for-woocommerce' ),
                    'id'    => 'yith_wcact_settings_resend_winner_auction_button',
                    'desc'  => esc_html__( 'Click to resend the email to the winner in case the sending failed', 'yith-auctions-for-woocommerce' ),
                    'type'  => 'yith_wcact_html',
                    'html'  => '<a class="button" href="'.$resend_winner_email_url.'">'.esc_html__( 'Resend winner email', 'yith-auctions-for-woocommerce' ).'</a>',
                ),
                'settings_resend_winner_auction_options_end'      => array(
                    'type' => 'sectionend',
                    'id'   => 'yith_wcact_settings_resend_winner_auction_end'
                ),
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////
            );
            $panel_premium2 = array_merge($panel_premium,$panel_premium2);
            return $panel_premium2;
        }

        /**
         * YITH before auction tab
         *
         * Add input in auction tab
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0.11
         */

        public function yith_before_auction_tab($post_id) {
            $product = wc_get_product($post_id);

            $auction_product = ( $product && 'auction' == $product->get_type() ) ? true : false;


            woocommerce_wp_text_input( array(
                'id'                => '_yith_auction_start_price',
                'name'              => '_yith_auction_start_price',
                'class'             => 'wc_input_price short',
                'label'             => esc_html__( 'Starting Price', 'yith-auctions-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'value'             =>  $product && $auction_product ? $product->get_start_price('edit') : '',
                'data_type'         => 'price',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0'
                )
            ) );

            woocommerce_wp_text_input( array(
                'id'                => '_yith_auction_bid_increment',
                'name'              => '_yith_auction_bid_increment',
                'class'             => 'wc_input_price short',

                'label'             => esc_html__( 'Bid up', 'yith-auctions-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'value'             => $product && $auction_product ? $product->get_bid_increment('edit') : '',
                'data_type'         => 'price',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0'
                )
            ) );

            woocommerce_wp_text_input( array(
                'id'                => '_yith_auction_minimum_increment_amount',
                'name'              => '_yith_auction_minimum_increment_amount',
                'class'             => 'wc_input_price short',
                'label'             => esc_html__( 'Minimum increment amount', 'yith-auctions-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'value'             => $product && $auction_product ? $product->get_minimum_increment_amount('edit'): '',
                'desc_tip'      => 'true',
                'description'   => esc_html__( 'Minimum amount to increase manual bids', 'yith-auctions-for-woocommerce' ),
                'data_type'         => 'price',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0'
                )
            ) );

            woocommerce_wp_text_input( array(
                'id'                => '_yith_auction_reserve_price',
                'name'              => '_yith_auction_reserve_price',
                'class'             => 'wc_input_price short',
                'label'             => esc_html__( 'Reserve price', 'yith-auctions-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'value'             => $product && $auction_product ? $product->get_reserve_price('edit') : '',
                'data_type'         => 'price',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0'
                )
            ) );

            woocommerce_wp_text_input( array(
                'id'                => '_yith_auction_buy_now',
                'name'              => '_yith_auction_buy_now',
                'class'             => 'wc_input_price short',
                'label'             => esc_html__( 'Buy it now price', 'yith-auctions-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'value'             => $product && $auction_product ? $product->get_buy_now('edit') : '',
                'data_type'         => 'price',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min'  => '0'
                )
            ) );
        }

        /**
         * YITH after auction tab
         *
         * Add input in auction tab
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0.11
         */

        public function yith_after_auction_tab($post_id) {

            $product = wc_get_product($post_id);

            $auction_product = ( $product &&'auction' == $product->get_type() ) ? true : false;

            woocommerce_wp_text_input(array(
                'id' => '_yith_check_time_for_overtime_option',
                'name' => '_yith_check_time_for_overtime_option',
                'class' => 'wc_input_price short',
                'label' => esc_html__('Time to add overtime', 'yith-auctions-for-woocommerce'),
                'value' => $product && $auction_product ? $product->get_check_time_for_overtime_option('edit') : '',
                'data_type' => 'decimal',
                'description' => esc_html__('Number of minutes before auction ends to check if overtime added. (Override the settings option)', 'yith-auctions-for-woocommerce'),
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '0'
                )
            ));

            woocommerce_wp_text_input(array(
                'id' => '_yith_overtime_option',
                'name' => '_yith_overtime_option',
                'class' => 'wc_input_price short',
                'label' => esc_html__('Overtime', 'yith-auctions-for-woocommerce'),
                'value' => $product && $auction_product ?  $product->get_overtime_option('edit') : '',
                'data_type' => 'decimal',
                'description' => esc_html__('Number of minutes by which the auction will be extended. (Overrride the settings option)', 'yith-auctions-for-woocommerce'),
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '0'
                )
            ));

            /*Automatic re-schedule*/
            woocommerce_wp_text_input( array(
                'id'                => '_yith_wcact_auction_automatic_reschedule',
                'value'             => $product && $auction_product ? $product->get_automatic_reschedule('edit') : '',
                'label'             => esc_html_x( 'Time value for automatic rescheduling', 'Admin option: Show auctions on shop page', 'yith-auctions-for-woocommerce' ),
                'desc_tip'          => true,
                'description'       => esc_html_x( 'Number of days/hours/minutes to reschedule auction without bids automatically (Override the settings option)', 'Admin option description: number of days. (Override the settings option)', 'yith-auctions-for-woocommerce' ),
                'custom_attributes' => array(
                    'step'          => 'any',
                    'min'           => '0',
                ),
                'data_type'         => 'decimal',
            ) );

            woocommerce_wp_select(array(
                'id' => '_yith_wcact_automatic_reschedule_auction_unit',
                'name' => '_yith_wcact_automatic_reschedule_auction_unit',
                'label' => esc_html_x( 'Select unit for automatic rescheduling', 'Admin option: Select days/hours/minutes', 'yith-auctions-for-woocommerce' ),
                'options' => array(
                    'days' => esc_html_x('days','Admin option: days','yith-auctions-for-woocommerce'),
                    'hours'  => esc_html_x('hours','Admin option: hours','yith-auctions-for-woocommerce'),
                    'minutes' => esc_html_x('minutes','Admin option: hours','yith-auctions-for-woocommerce')
                ),
                'value' => $product && $auction_product ? $product->get_automatic_reschedule_auction_unit('edit') : '',
            ));

            woocommerce_wp_checkbox(
                array(
                    'id'            => '_yith_wcact_show_upbid',
                    'label'         => esc_html('Show bid up', 'yith-auctions-for-woocommerce' ),
                    'desc_tip'      => 'true',
                    'description'   => esc_html__( 'Check this option to show Bid up on product page', 'yith-auctions-for-woocommerce' ),
                    'value'         => $product && $auction_product ? $product->get_upbid_checkbox('edit') : '',
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id'            => '_yith_wcact_show_overtime',
                    'label'         => esc_html__('Show overtime', 'yith-auctions-for-woocommerce' ),
                    'desc_tip'      => 'true',
                    'description'   => esc_html__( 'Check this option to show overtime on product page', 'yith-auctions-for-woocommerce' ),
                    'value'         => $product && $auction_product ? $product->get_overtime_checkbox('edit') :'',
                )
            );

            if( $product && 'auction' == $product->get_type() && ( $product->is_closed() ||   'outofstock' == $product->get_stock_status() ) ) {
                echo '<div id="yith-reshedule">';
                echo '<p class="form-field wc_auction_reshedule"><input type="button" class="button" id="reshedule_button" value="' . esc_html__('Re-schedule', 'yith-auctions-for-woocommerce') . '"></p>';
                echo '<p class="form-field" id="yith-reshedule-notice-admin">' . esc_html__(' Change the dates and click on the update button to re-schedule the auction', 'yith-auctions-for-woocommerce') . '</p>';
                echo '</div>';
            }
        }
        /**
         * Save the data input into the auction product box
         *
         * @author   Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since    1.0.11
         */
        public function save_auction_data($post_id)
        {
            $product = wc_get_product($post_id);
            $product_type = empty($_POST['product-type']) ? 'simple' : sanitize_title(stripslashes($_POST['product-type']));

            if ( $product && 'auction' == $product_type && !$product->get_is_closed_by_buy_now()  ) {

                if (isset($_POST['_yith_auction_to'])) {

                    $bids = YITH_Auctions()->bids;
                    $exist_auctions = $bids->get_max_bid($post_id);

                    //Clear all Product CronJob
                    if (wp_next_scheduled('yith_wcact_send_emails', array($post_id))) {
                        wp_clear_scheduled_hook('yith_wcact_send_emails', array($post_id));

                    }
                    //Create the CronJob //when the auction is about to end
                    do_action('yith_wcact_register_cron_email', $post_id);

                    //Clear all Product CronJob
                    if ( wp_next_scheduled('yith_wcact_send_emails_auction', array($post_id) ) ) {

                        wp_clear_scheduled_hook('yith_wcact_send_emails_auction', array($post_id));
                    }
                    //Create the CronJob //when the auction end, winner and vendors
                    do_action('yith_wcact_register_cron_email_auction', $post_id);

                    //Prevent issues with orderby in shop loop
                    if ( !$exist_auctions) {
                        yit_save_prop($product, '_price',$_POST['_yith_auction_start_price']);
                    }

	                $product->set_stock_status('instock');

                }



                $product->save();
            }
        }

        /**
         * render products columns
         *
         * Add content to Start date and Close date
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0.11
         * @return void
         */
        public function render_product_columns_premium($column, $type,$product)
        {
            if($column == 'yith_auction_status') {
                switch ($type) {
                    case 'started-reached-reserve' :
                        echo '<span class="yith-wcact-auction-status yith-auction-started-reached-reserve tips" data-tip="' . esc_attr__('Started and not exceeded the reserve price', 'yith-auctions-for-woocommerce') . '">';
                        break;
                    case 'finished-reached-reserve' :
                        echo '<span class="yith-wcact-auction-status yith-auction-finished-reached-reserve tips" data-tip="' . esc_attr__('Finished and not exceeded the reserve price', 'yith-auctions-for-woocommerce') . '"></span>';
                        break;
                    case 'finnish-buy-now' :
                        echo '<span class="yith-wcact-auction-status yith-auction-finnish-buy-now tips" data-tip="' . esc_attr__('Purchased through buy-now', 'yith-auctions-for-woocommerce') . '"></span>';
                        break;
                }
            }


        }

        /**
         * Auction Order By
         *
         * Order by start date or end date in datatable products
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function auction_orderby($query)
        {

            if (!is_admin())
                return;

            $orderby = $query->get('orderby');

            switch ($orderby) {
                case 'yith_auction_start_date':
                    $query->set('meta_key', '_yith_auction_for');
                    $query->set('orderby', 'meta_value');
                    break;
                case 'yith_auction_end_date':
                    $query->set('meta_key', '_yith_auction_to');
                    $query->set('orderby', 'meta_value');
                    break;
            }
        }

        /**
         * Sorteable columns
         *
         * convert "Start Date" and "End Date" in sorteable columns product datatable
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function product_sorteable_columns($columns)
        {
            $columns['yith_auction_start_date'] = 'yith_auction_start_date';
            $columns['yith_auction_end_date'] = 'yith_auction_end_date';

            return $columns;
        }


        /**
         * Register plugins for activation tab
         *
         * @return void
         * @since    2.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_activation()
        {
            if (!class_exists('YIT_Plugin_Licence')) {
                require_once YITH_WCACT_PATH . '/plugin-fw/licence/lib/yit-licence.php';
                require_once YITH_WCACT_PATH . '/plugin-fw/licence/lib/yit-plugin-licence.php';
            }
            YIT_Plugin_Licence()->register(YITH_WCACT_INIT, YITH_WCACT_SECRETKEY, YITH_WCACT_SLUG);

        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since    2.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_updates()
        {
            if (!class_exists('YIT_Upgrade')) {
                require_once(YITH_WCACT_PATH . '/plugin-fw/lib/yit-upgrade.php');
            }
            YIT_Upgrade()->register(YITH_WCACT_SLUG, YITH_WCACT_INIT);
        }

        /**
         * Add new filter in product tab
         *
         * @return void
         * @since    1.0.7
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function add_post_formats_filter($post_type)
        {
            if ( $post_type == 'product' ) {
                $output = '<select name="auction_type" id="dropdown_auction_type">';
                $output .= '<option value="">' . __('Show all auction statuses', 'yith-auctions-for-woocommerce') . '</option>';
                $output .= '<option value="non-started">' . __('Not Started', 'yith-auctions-for-woocommerce') . '</option>';
                $output .= '<option value="started">' . __('Started', 'yith-auctions-for-woocommerce') . '</option>';
                $output .= '<option value="finished">' . __('Finished', 'yith-auctions-for-woocommerce') . '</option>';
                $output .= '</option>';
                $output .= '</select>';
                echo apply_filters('yith_wcact_woocommerce_auction_filters', $output);
            }
        }

        /**
         * Filter by auction status
         *
         * @return void
         * @since    1.0.7
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function filter_by_auction_status($query)
        {
            global $post_type;

            if (!is_admin())
                return;

            if ($post_type == 'product') {

                if (isset($_GET['auction_type'])) {

                    $orderby = $_GET['auction_type'];

                    switch ( $orderby ) {
                        case 'non-started' :
                            $query->set('meta_query', array(
                                array(
                                    'key' => '_yith_auction_for',
                                    'value' => strtotime('now'),
                                    'compare' => '>'
                                )
                            ));
                            break;
                        case 'started' :
                            $query->set('meta_query', array(
                                'relation' => 'AND',
                                array(
                                    'key' => '_yith_auction_for',
                                    'value' => strtotime('now'),
                                    'compare' => '<'
                                ),
                                array(
                                    'key' => '_yith_auction_to',
                                    'value' => strtotime('now'),
                                    'compare' => '>'
                                )
                            ));

                            break;
                        case 'finished' :
                            $query->set('meta_query', array(
                                array(
                                    'key' => '_yith_auction_to',
                                    'value' => strtotime('now'),
                                    'compare' => '<'
                                )
                            ));
                            break;
                    }
                }
            }
        }

        /**
         * Create metabox for auction product
         *
         * @return void
         * @since    1.0.14
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function admin_list_bid($post_type) {
            global $post;

            if( isset($post) ) {

                $post_types = array('product');     //limit meta box to certain post types
                $product = wc_get_product($post->ID);
                if ( in_array( $post_type, $post_types ) && ( $product->get_type() == 'auction') ) {

                    add_meta_box('yith-wcgpf-auction-bid-list', esc_html__('Auction bid list', 'yith-auctions-for-woocommerce'), array($this, 'auction_bid_list'), $post_type, 'normal', 'low');
                    add_meta_box('yith-wcgpf-auction-information', esc_html__('Auction status', 'yith-auctions-for-woocommerce'), array($this, 'auction_bid_status'), $post_type, 'side', 'low');

                }
            }
        }
        /**
         * Create metabox with list of bid for each product
         *
         * @return void
         * @since    1.0.14
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        function auction_bid_list($post) {

            $args = array(
                'post_id' => $post->ID
            );
            wc_get_template('admin-list-bids.php', $args , '', YITH_WCACT_TEMPLATE_PATH . 'admin/');
        }
        /**
         * Create metabox with Auction information
         *
         * @return void
         * @since    2.0.1
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        function auction_bid_status( $post ) {
            $args = array(
                'post_id' => $post->ID,
                'post'    => $post
            );
            wc_get_template('admin-auction-status.php', $args , '', YITH_WCACT_TEMPLATE_PATH . 'admin/');
        }

        /**
         * Control overtime product
         *
         * @return void
         * @since    1.0.14
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function duplicate_products( $product_new,$product ) {

            if( $product_new && 'auction' == $product_new->get_type() ) {

	            $is_in_overtime = $product_new->get_is_in_overtime();

	            if ( $is_in_overtime ) {

		            $product_new->set_is_in_overtime( false );

		            $product_new->save();
	            }
            }
        }


        /**
         * Regenerate auction prices
         *
         * regenerate auction prices for each product
         *
         * @return void
         * @since    1.2.2
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function yith_wcact_send_auction_winner_email()
        {
            if ( current_user_can('manage_options') ) {
                $args = array(
                    'post_type'   => 'product',
                    'numberposts' => -1,
                    'fields'      => 'ids',
                    'meta_query'  => array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'yith_wcact_winner_email_is_not_send',
                            'value'   => '1',
                            'compare' => '='
                        ),
                        array(
                            'key'     => '_yith_auction_to',
                            'value'   => strtotime( 'now' ),
                            'compare' => '<='
                        )
                    ));
                // Get all Auction ids
                $auction_ids = get_posts( $args );

                if ( $auction_ids ) {

                    foreach ( $auction_ids as $auction_id ) {

                        $product = wc_get_product($auction_id);
                        $instance = YITH_Auctions()->bids;
                        $max_bidder = $instance->get_max_bid($product->get_id());

                        if( $max_bidder ) {
                            $user = get_user_by('id', $max_bidder->user_id);
                            $product->set_send_winner_email( false );
                            yit_delete_prop($product,'yith_wcact_winner_email_is_not_send',false);

                            $product->save();

                            WC()->mailer();

                            do_action('yith_wcact_auction_winner', $product, $user);
                        }
                    }
                }
            }
        }
        /**
         * Plugin Row Meta
         *
         *
         * @return void
         * @since    1.2.3
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WCACT_INIT' ) {
            $new_row_meta_args = parent::plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file );

            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ){
                $new_row_meta_args['is_premium'] = true;
            }

            return $new_row_meta_args;
        }
        /**
         * Regenerate auction prices
         *
         * Action Links
         *
         * @return void
         * @since    1.2.3
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function action_links( $links ) {
            $links = yith_add_action_links( $links, $this->_panel_page, true );
            return $links;
        }


        /* === EDIT PROFILE METHODS === */

        /**
         * Render auction fields on user profile
         *
         * @param $user \WP_User User object
         * @return void
         * @since  1.0.0
         */
        public function render_auction_extra_fields( $user ) {

            if( ! current_user_can( apply_filters( 'yith_wcact_panel_capability', 'manage_woocommerce' ) ) ){
                return;
            }

            $is_banned          =    get_user_meta( $user->ID, '_yith_wcact_user_ban', true );
            $ban_message        =    get_user_meta( $user->ID, '_yith_wcact_ban_message', true );
            ?>
            <hr />
            <h3><?php esc_html_e( 'Auction details', 'yith-auctions-for-woocommerce' )?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="banned"><?php esc_html_e( 'Banned', 'yith-auctions-for-woocommerce' )?></label></th>
                    <td>
                        <input type="checkbox" name="yith_wcact_banned" id="yith_wcact_banned" value="1" <?php checked( $is_banned, true ) ?> />
                        <span class="description"><?php esc_html_e( 'Check this option if you want to ban user from bidding', 'yith-auctions-for-woocommerce' ) ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="yith_wcact_banned_message"><?php esc_html_e( 'Ban Message', 'yith-auctions-for-woocommerce' )?></label></th>
                    <td>
                        <textarea name="yith_wcact_banned_message" id="yith_wcact_banned_message" cols="50" rows="10"><?php echo $ban_message ?></textarea>
                        <p class="description"><?php esc_html_e( 'Optionally you can show a message, explaining why her/his was banned', 'yith-auctions-for-woocommerce' ) ?></p>
                    </td>
                </tr>
            </table>
            <?php
        }

        /**
         * Save auction fields on user profile
         *
         * @param $user_id int User id
         * @return bool Whether method actually saved option or not
         * @since  1.0.0
         */
        public function save_auction_extra_fields( $user_id ) {

            if( ! current_user_can( apply_filters( 'yith_wcact_panel_capability', 'manage_woocommerce' ) ) ){
                return;
            }

            $is_banned = isset( $_POST['yith_wcact_banned'] ) ? $_POST['yith_wcact_banned'] : 0;
            update_user_meta( $user_id, '_yith_wcact_user_ban', $is_banned );
            update_user_meta( $user_id, '_yith_wcact_ban_message', wp_kses_post( $_POST['yith_wcact_banned_message'] ) );

        }

		/**
		 * If product is not an auction product, remove meta related to _yith_is_an_auction_product
		 *
		 * @param $post_id, $post

		 * @since  1.3.1
		 */
        public function check_if_an_auction_product( $post_id, $post) {

        		$product = wc_get_product($post_id);

        		if ( $product ) {

        			$is_an_auction_product = yit_get_prop($product,'_yith_is_an_auction_product',true);


        			if ( 'auction' != $product->get_type() && $is_an_auction_product ) {

						delete_post_meta($post_id,'_yith_is_an_auction_product',true);

					} else {

        				if( 'auction' == $product->get_type() && !$is_an_auction_product ) {

							yit_save_prop($post,'_yith_is_an_auction_product',true);

						}
					}

				}
		}


        /**
         * Set the product meta before saving the product
         *
         * @param WC_Product_Auction $product
         * @since  1.3.4
         */
        public function set_product_meta_before_saving( $product ) {

            if ( $product->is_type( self::$prod_type ) ) {

                try {
	                    /** @var YITH_WCACT_Product_Auction_Data_Store_CPT $data_store */
		                $data_store = WC_Data_Store::load( 'product-auction' );
		                $meta_key_to_props
		                            = $data_store->get_auction_meta_key_to_props();

		                foreach ( $meta_key_to_props as $key => $prop ) {

			                $setter = "set_{$prop}";

			                if ( is_callable( array( $product, $setter ) ) ) {

				                if ( $data_store->is_date_prop( $prop ) ) {

					                $gmt_date = ( isset( $_POST[ $key ] ) ? strtotime( get_gmt_from_date( $_POST[ $key ] ) ) : '' );
					                $product->$setter( $gmt_date );

				                } elseif ( $data_store->is_decimal_prop( $prop ) ) {

					                if ( isset( $_POST[ $key ] ) ) {

						                $product->$setter( wc_format_decimal( wc_clean( $_POST[ $key ] ) ) );
					                }

				                } elseif ( $data_store->is_yes_no_prop( $prop ) ) {

					                $value = isset( $_POST[ $key ] ) && ! empty( $_POST[ $key ] ) ? 'yes' : 'no';

					                $product->$setter( $value );

				                } else if ( isset( $_POST[ $key ] ) ) {
					                $product->$setter( $_POST[ $key ] );
				                }
			                }
		                }

                } catch ( Exception $e ) {
                    $message = sprintf( "Error when trying to set product meta before saving for auction product with id %s1. Exception: %s2", $product->get_id(), $e->getMessage() );
                }
            }
        }



    }
}
