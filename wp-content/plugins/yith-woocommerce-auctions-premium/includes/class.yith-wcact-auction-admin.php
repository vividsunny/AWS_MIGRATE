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

if ( !class_exists( 'YITH_Auction_Admin' ) ) {
    /**
     * Class YITH_Auctions_Admin
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
     */
    class YITH_Auction_Admin
    {

        /**
         * @var Panel object
         */
        protected $_panel = null;


        /**
         * @var Panel page
         */
        protected $_panel_page = 'yith_wcact_panel_product_auction';

        /**
         * @var bool Show the premium landing page
         */
        public $show_premium_landing = true;

        /**
         * @var string Official plugin documentation
         */
        protected $_official_documentation = 'https://docs.yithemes.com/yith-woocommerce-auctions/';

        /**
         * @var string
         */
        protected $_premium_landing_url = 'http://yithemes.com/themes/plugins/yith-woocommerce-auctions/';

        /**
         * Single instance of the class
         *
         * @var \YITH_Auction_Admin
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Booking Product Type Name
         *
         * @type string
         */
        public static $prod_type = 'auction';

        public $product_meta_array = array();

        /**
         * Returns single instance of the class
         *
         * @return \YITH_Auction_Admin
         * @since 1.0.0
         */
        public static function get_instance() {
            $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

            if ( is_null( $self::$instance ) ) {
                $self::$instance = new $self;
            }

            return $self::$instance;
        }


        /**
         * Construct
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         */
        public function __construct()
        {
            /* === Register Panel Settings === */
            add_action('admin_menu', array($this, 'register_panel'), 5);
            /* === Premium Tab === */
            add_action( 'yith_wcact_premium_tab', array( $this, 'show_premium_landing' ) );
            
            // Enqueue Scripts
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

            // Add Auction product to WC product type selector
            add_filter('product_type_selector', array($this, 'product_type_selector'));

            // Add tabs for product auction
            add_filter('woocommerce_product_data_tabs', array($this, 'product_auction_tab'));

            // Add options to general product data tab
            add_action('woocommerce_product_data_panels', array($this, 'add_product_data_panels'));



            //product columns
            add_filter('manage_product_posts_columns', array($this, 'product_columns'));
            add_action('manage_product_posts_custom_column', array($this, 'render_product_columns'), 10, 2);

            add_filter('woocommerce_free_price_html', array($this, 'change_free_price_product'), 10, 2);


            /* === Show Plugin Information === */
            add_filter( 'plugin_action_links_' . plugin_basename( YITH_WCACT_PATH . '/' . basename( YITH_WCACT_FILE ) ), array( $this, 'action_links' ) );
            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author   Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel()
        {

            if (!empty($this->_panel)) {
                return;
            }

            $admin_tabs = apply_filters('yith_wcact_admin_tabs', array(
                    'settings' => esc_html__('Settings', 'yith-auctions-for-woocommerce'),
                )
            );

            $args = array(
                'create_menu_page' => true,
                'parent_slug' => '',
                'page_title' => 'YITH Auctions for WooCommerce',
                'menu_title' => 'Auctions',
                'capability' => 'manage_options',
                'plugin_description' =>esc_html__('Your customers can purchase products at the best price ever taking full advantage of the online auction system as the most popular portals, such as eBay, can do.', 'yith-auctions-for-woocommerce'),
                'parent' => '',
                'parent_page' => 'yith_plugin_panel',
                'class'            => yith_set_wrapper_class(),
                'page' => $this->_panel_page,
                'admin-tabs' => $admin_tabs,
                'options-path' => YITH_WCACT_OPTIONS_PATH,
                'links' => $this->get_sidebar_link()
            );


            /* === Fixed: not updated theme/old plugin framework  === */
            if (!class_exists('YIT_Plugin_Panel_WooCommerce')) {
                require_once('plugin-fw/lib/yit-plugin-panel-wc.php');
            }


            $this->_panel = new YIT_Plugin_Panel_WooCommerce($args);

            add_action('woocommerce_admin_field_yith_auctions_upload', array($this->_panel, 'yit_upload'), 10, 1);
        }


        /**
         * Sidebar links
         *
         * @return   array The links
         * @since    1.2.1
         * @author   Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function get_sidebar_link()
        {
            $links = array(
                array(
                    'title' => esc_html__('Plugin documentation', 'yith-auctions-for-woocommerce'),
                    'url' => $this->_official_documentation,
                ),
                array(
                    'title' => esc_html__('Help Center', 'yith-auctions-for-woocommerce'),
                    'url' => 'http://support.yithemes.com/hc/en-us/categories/202568518-Plugins',
                ),
                array(
                    'title' => esc_html__('Support platform', 'yith-auctions-for-woocommerce'),
                    'url' => 'https://yithemes.com/my-account/support/dashboard/',
                ),
                array(
                    'title' => sprintf('%s (%s %s)', esc_html__('Changelog', 'yith-auctions-for-woocommerce'), esc_html__('current version', 'yith-auctions-for-woocommerce'), YITH_WCACT_VERSION),
                    'url' => 'https://docs.yithemes.com/yith-woocommerce-auctions/category/changelog/',
                ),
            );

            return $links;
        }


        /**
         * Enqueue Scripts
         *
         * Register and enqueue scripts for Admin
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return void
         */

        public function enqueue_scripts()
        {

            $screen = get_current_screen();
            $is_product = $screen->id === 'product';
            global $post;
            /* === CSS === */
            wp_register_style('yith-wcact-admin-css', YITH_WCACT_ASSETS_URL . 'css/admin.css');
            wp_register_style('yith-wcact-timepicker-css', YITH_WCACT_ASSETS_URL . 'css/timepicker.css');
            /* === Script === */
            wp_register_script('yith-wcact-datepicker', YITH_WCACT_ASSETS_URL . 'js/datepicker.js', array('jquery', 'jquery-ui-datepicker'), YITH_WCACT_VERSION, 'true');
            wp_register_script('yith-wcact-timepicker', YITH_WCACT_ASSETS_URL . 'js/timepicker.js', array('jquery', 'jquery-ui-datepicker'), YITH_WCACT_VERSION, 'true');

            $premium_suffix = defined( 'YITH_WCACT_PREMIUM' ) && YITH_WCACT_PREMIUM ? '-premium' : '';
            wp_register_script( 'yith-wcact-admin', YITH_WCACT_ASSETS_URL . 'js/admin' . $premium_suffix . '.js', array( 'jquery' ), YITH_WCACT_VERSION, true );

            wp_localize_script('yith-wcact-admin', 'object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'confirm_delete_bid' => esc_html__('Are you sure you want to delete the customer\'s bid?','yith-auctions-for-woocommerce'),
                'id' => $post,
            ));
            wp_enqueue_style('yith-wcact-auction-font', YITH_WCACT_ASSETS_URL . '/fonts/icons-font/style.css');

            if ($is_product) {
                /* === CSS === */
                wp_enqueue_style('yith-wcact-timepicker-css');

                /* === Script === */

                wp_enqueue_script('yith-wcact-datepicker');
                wp_enqueue_script('yith-wcact-timepicker');
                wp_enqueue_script('yith-wcact-admin');
                wp_deregister_script('acf-timepicker');


            }
            wp_enqueue_style('yith-wcact-admin-css');

            do_action('yith_wcact_enqueue_scripts');

        }


        /**
         * Add Auction Product type in product type selector [in product wc-metabox]
         *
         * @access   public
         * @since    1.0.0
         * @return   array
         * @author   Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function product_type_selector($types)
        {
            $types['auction'] = esc_html_x('Auction', 'Admin: product type', 'yith-auctions-for-woocommerce');

            return apply_filters('yith_wcact_product_type_selector',$types);
        }


        /**
         * Add tab for auction products
         *
         * @param $tabs
         *
         * @return array
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function product_auction_tab($tabs)
        {

            $new_tabs = array(
                'yith_Auction' => array(
                    'label' => esc_html__('Auction', 'yith-auctions-for-woocommerce'),
                    'target' => 'yith_auction_settings',
                    'class' => array('show_if_auction active'),
                    'priority' => 15,
                ),
            );
            $tabs = array_merge($new_tabs,$tabs);

            return $tabs;
        }


        /**
         * Add panels for auction products
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function add_product_data_panels()
        {
            global $post;

            $tabs = array(
                'auction' => 'yith_auction_settings',

            );

            foreach ($tabs as $key => $tab_id) {

                echo "<div id='{$tab_id}' class='panel woocommerce_options_panel'>";
                include(YITH_WCACT_TEMPLATE_PATH . 'admin/product-tabs/' . $key . '-tab.php');
                echo '</div>';
            }
        }


        /**
         * Add columns product datatable
         *
         * Add columns Start Date and close Date in Products datatable
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return array
         */
        public function product_columns($existing_columns)
        {
            $existing_columns['yith_auction_start_date'] = esc_html__('Start Date', 'yith-auctions-for-woocommerce');
            $existing_columns['yith_auction_end_date'] = esc_html__('End Date', 'yith-auctions-for-woocommerce');
            $existing_columns['yith_auction_status'] = '<span class="yith-wcact-auction-status yith-auction-status-column tips" data-tip="' . esc_attr__('Auction status', 'yith-auctions-for-woocommerce') . '"></span>';
            return apply_filters('yith_wcact_product_columns',$existing_columns);
        }


        /**
         * render products columns
         *
         * Add content to Start date and Close date
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return void
         */
        public function render_product_columns($column, $product_id)
        {
            $auction_product = wc_get_product($product_id);
            if (  'auction' === $auction_product->get_type()) {
                switch ($column) {
                    case 'yith_auction_start_date' :
                        $dateinic = apply_filters( 'yith_wcact_render_product_columns_dateinic' , $auction_product->get_start_date(), $product_id );

                        if ( $dateinic ) {

                            $date = get_date_from_gmt( date( 'Y-m-d H:i:s', $dateinic ), get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ) );
                            echo $date;
                        }
                        break;
                    case 'yith_auction_end_date' :

                        $dateclose = apply_filters( 'yith_wcact_render_product_columns_dateclose' , $auction_product->get_end_date(), $product_id );

                        if ( $dateclose ) {

                            $date = get_date_from_gmt( date( 'Y-m-d H:i:s', $dateclose ), get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ) );
                            echo $date;
                        }
                        break;
                    case 'yith_auction_status':
                        $type = $auction_product->get_auction_status();
                        switch ($type) {
                            case 'non-started' :
                                echo '<span class="yith-wcact-auction-status yith-auction-non-start tips" data-tip="' . esc_attr__('Not Started', 'yith-auctions-for-woocommerce') . '"></span>';
                                break;
                            case 'started' :
                                echo '<span class="yith-wcact-auction-status yith-auction-started tips" data-tip="' . esc_attr__('Started', 'yith-auctions-for-woocommerce') . '"></span>';
                                break;
                            case 'finished' :
                                echo '<span class="yith-wcact-auction-status yith-auction-finished tips" data-tip="' . esc_attr__('Finished', 'yith-auctions-for-woocommerce') . '"></span>';
                                break;
                        }
                        do_action('yith_wcact_render_product_columns_auction_status',$column,$type,$auction_product);
                        break;
                    default  : do_action('yith_wcact_render_product_columns',$column,$product_id);
                        break;
                }
            }

        }

        /**
         * Change price product
         *
         * Change price Free to 0.00 in admin product datatable
         *
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         * @since 1.0
         * @return $price
         */
        public function change_free_price_product($price, $product)
        {
            if ('auction' == $product->get_type()) {
                $price = wc_price(0);
            }

            return $price;
        }

        /**
         * Create link that regenerate auction prices
         *
         * @return void
         * @since    1.0.11
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function yith_regenerate_prices($value)
        {
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
                </th>
                <td class="forminp forminp-<?php echo sanitize_title($value['type']) ?>">
                    <?php echo $value['html'] ?>
                    <span>
                        <?php echo $value['desc'] ?>
                    </span>
                </td>
            </tr>
            <?php
        }

        /**
         * Regenerate auction prices
         *
         * regenerate auction prices for each product
         *
         * @return void
         * @since    1.0.11
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function regenerate_auction_prices()
        {
            if (current_user_can('manage_options')) {
                if ($auction_term = get_term_by('slug', 'auction', 'product_type')) {
                    $auction_ids = array_unique((array)get_objects_in_term($auction_term->term_id, 'product_type'));
                    if ($auction_ids) {
                        foreach ($auction_ids as $auction_id) {
                            $auction_product = wc_get_product($auction_id);
                            $actual_price = $auction_product->get_current_bid();
                            $auction_product->set_price($actual_price);
                            $auction_product->save();
                        }
                    }
                }
            }
        }
        /**
         * Action links
         *
         *
         * @return void
         * @since    1.2.3
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function action_links( $links ) {
            $links = yith_add_action_links( $links, $this->_panel_page, false );
            return $links;
        }
        /**
         * Plugin Row Meta
         *
         *
         * @return void
         * @since    1.2.3
         * @author   Carlos Rodríguez <carlos.rodriguez@youirinspiration.it>
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WCACT_FREE_INIT' ) {
            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
                $new_row_meta_args['slug'] = YITH_WCACT_SLUG;
            }

            return $new_row_meta_args;
        }
    }
}