<?php

defined( 'ABSPATH' ) or exit;

/*
 *  YITH WooCommerce Customer History Admin
 */

if ( ! class_exists( 'YITH_WCCH_Admin' ) ) {

    class YITH_WCCH_Admin {

        /*
         *  Constructor
         */

        function __construct() {

            /*
             *  Hooks
             */

            add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ), 1 );
            add_action( 'add_meta_boxes', array( &$this, 'yith_wcch_add_meta_boxes'), 100 );

        }

        /*
         *  Admin Enqueue Scripts
         */

        function admin_enqueue_scripts() {

            /*
             *  Js
             */

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'yith-wcch-scripts-admin', YITH_WCCH_URL . 'assets/js/yith-wcch-admin.js' );
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'yith-wcch-email.php' ) {
                wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.min.js', array( 'jquery' ) );
            }

            /*
             *  Css
             */

            // wp_enqueue_style( 'jquery-ui', YITH_WCCH_URL . 'assets/css/jquery-ui.min.css', false, '1.11.4' );
            wp_enqueue_style( 'font-awesome', YITH_WCCH_URL . 'assets/css/font-awesome.min.css', false, '4.5.0' );
            wp_enqueue_style( 'yith-wcch-style-admin', YITH_WCCH_URL . 'assets/css/yith-wcch-admin.css', false, '1.0.0' );

        }

        /*
         *  Admin Menu and Separator
         */

        function admin_menu() {

            global $menu;

            $capability = 'manage_woocommerce';

            add_menu_page( 'Customer History', 'Customer History', $capability, 'yith-wcch-customers.php', array( $this, 'admin_customers' ), 'dashicons-pressthis', '55.5' );

            add_submenu_page( 'yith-wcch-customers.php', __( 'Customers & Users', 'yith-woocommerce-customer-history' ), __( 'Customers & Users', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-customers.php', array( $this, 'admin_customers' ) );
            add_submenu_page( '', __( 'Customer', 'yith-woocommerce-customer-history' ), __( 'Customer', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-customer.php', array( $this, 'admin_customer' ) );
            add_submenu_page( 'p', __( 'Other Users', 'yith-woocommerce-customer-history' ), __( 'Other Users', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-users.php', array( $this, 'admin_users' ) );
            add_submenu_page( 'yith-wcch-customers.php', __( 'Live Sessions', 'yith-woocommerce-customer-history' ), __( 'Live Sessions', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-sessions.php', array( $this, 'admin_sessions' ) );
            add_submenu_page( '', __( 'Trash', 'yith-woocommerce-customer-history' ), __( 'Trash', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-trash.php', array( $this, 'admin_trash' ) );
            add_submenu_page( '', __( 'Session', 'yith-woocommerce-customer-history' ), __( 'Session', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-session.php', array( $this, 'admin_session' ) );
            add_submenu_page( 'yith-wcch-customers.php', __( 'Live Searches', 'yith-woocommerce-customer-history' ), __( 'Live Searches', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-searches.php', array( $this, 'admin_searches' ) );
            add_submenu_page( 'yith-wcch-customers.php', __( 'Statistics', 'yith-woocommerce-customer-history' ), __( 'Statistics', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-stats.php', array( $this, 'admin_stats' ) );
            add_submenu_page( '', __( 'Statistics - Search', 'yith-woocommerce-customer-history' ), __( 'Statistics - Search', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-stats-searches.php', array( $this, 'admin_stats_searches' ) );
            add_submenu_page( '', __( 'Statistics - Spent', 'yith-woocommerce-customer-history' ), __( 'Statistics - Search', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-stats-spent.php', array( $this, 'admin_stats_spent' ) );
            add_submenu_page( 'yith-wcch-customers.php', __( 'Emails', 'yith-woocommerce-customer-history' ), __( 'Emails', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-emails.php', array( $this, 'admin_emails' ) );
            add_submenu_page( '', __( 'Email', 'yith-woocommerce-customer-history' ), __( 'Email', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-email.php', array( $this, 'admin_email' ) );
            add_submenu_page( 'yith-wcch-customers.php', __( 'Settings', 'yith-woocommerce-customer-history' ), __( 'Settings', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-settings.php', array( $this, 'admin_settings' ) );

            global $admin_page_hooks;
            if( ! isset( $admin_page_hooks['yith_plugin_panel'] ) ){
                add_menu_page( 'yith_plugin_panel', __( 'YITH Plugins', 'yith-plugin-fw' ), 'manage_woocommerce', 'yith_plugin_panel', NULL, YIT_CORE_PLUGIN_URL . '/assets/images/yithemes-icon.png', '62.32' );
            }
            add_submenu_page( 'yith_plugin_panel', __( 'Customer History', 'yith-woocommerce-customer-history' ), __( 'Customer History', 'yith-woocommerce-customer-history' ), $capability, 'yith-wcch-customers.php', array( $this, 'admin_customers' ) );
            remove_submenu_page( 'yith_plugin_panel', 'yith_plugin_panel' );
            
        }

        /*
         *  Callback functions
         */

        function admin_customers() { require YITH_WCCH_TEMPLATE_PATH . '/backend/customers.php'; }
        function admin_customer() { require YITH_WCCH_TEMPLATE_PATH . '/backend/customer.php'; }
        function admin_users() { require YITH_WCCH_TEMPLATE_PATH . '/backend/users.php'; }
        function admin_sessions() { require YITH_WCCH_TEMPLATE_PATH . '/backend/sessions.php'; }
        function admin_trash() { require YITH_WCCH_TEMPLATE_PATH . '/backend/trash.php'; }
        function admin_searches() { require YITH_WCCH_TEMPLATE_PATH . '/backend/searches.php'; }
        function admin_stats() { require YITH_WCCH_TEMPLATE_PATH . '/backend/stats.php'; }
        function admin_stats_searches() { require YITH_WCCH_TEMPLATE_PATH . '/backend/stats-searches.php'; }
        function admin_stats_spent() { require YITH_WCCH_TEMPLATE_PATH . '/backend/stats-spent.php'; }
        function admin_emails() { require YITH_WCCH_TEMPLATE_PATH . '/backend/emails.php'; }
        function admin_email() { require YITH_WCCH_TEMPLATE_PATH . '/backend/email.php'; }
        function admin_settings() { require YITH_WCCH_TEMPLATE_PATH . '/backend/settings.php'; }

        /*
         *  Customer Orders list
         */

        function yith_wcch_add_meta_boxes() {
            add_meta_box( 'mv_other_fields',
                '<i class="fa fa-shopping-bag" aria-hidden="true"></i> ' . __('Latest Customer Orders','yith-woocommerce-customer-history'),
                array( &$this, 'yith_wcch_meta_box_orders' ),
                'shop_order',
                'normal',
                'high' );
        }
        function yith_wcch_meta_box_orders() {
            global $post;
            $order = new WC_Order( $post->ID );
            $user_id = $order->get_user_id();
            if ( $user_id > 0 ) {
                $customer_orders = get_posts( array(
                    'numberposts' => -1,
                    'meta_key'    => '_customer_user',
                    'meta_value'  => $user_id,
                    'post_type'   => wc_get_order_types(),
                    'post_status' => array_keys( wc_get_order_statuses() ),
                    // 'post_parent' => '0',
                ) );
                ?>
                <table class="wp-list-table widefat fixed striped posts">
                    <tr>
                        <th><?php echo __( 'Order', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Date', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Status', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Items', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Refunded', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Total', 'yith-woocommerce-customer-history' ); ?></th>
                        <th><?php echo __( 'Actions', 'yith-woocommerce-customer-history' ); ?></th>
                    </tr>
                    <?php foreach ( $customer_orders as $order ) :
                        $order = new WC_Order( $order->ID );
                        $order_id = yit_get_prop( $order, 'id' );
                        $order_date = yit_get_prop( $order, 'order_date' ); ?>
                        <tr>
                            <td><a href="post.php?post=<?php echo $order_id; ?>&action=edit">#<?php echo $order_id; ?></a></td>
                            <td><?php echo get_the_date( '', $order_id ); ?><br /><?php echo get_the_time( '', $order_id ); ?></td>
                            <td><?php echo ucfirst( str_replace( 'wc-', '', yit_get_prop( $order, 'post_status' ) ) ); ?></td>
                            <td><?php foreach ( $order->get_items() as $line ) { echo $line['qty'] . ' x <a href="post.php?post=' . $line['product_id'] . '&action=edit&KeepThis=true&TB_iframe=true&modal=false" onclick="return false;" class="thickbox">' . $line['name'] . '</a><br />'; } ?></td>
                            <td><?php echo wc_price( $order->get_total_refunded() ); ?></td>
                            <td><?php echo $order->get_formatted_order_total(); ?></td>
                            <td>
                                <a href="post.php?post=<?php echo $order_id; ?>&action=edit" class="button"><?php echo __( 'View', 'yith-woocommerce-customer-history' ); ?></a>
                                <a href="admin.php?page=yith-wcch-customer.php&user_id=<?php echo $user_id; ?>&action=edit" class="button"><?php echo __( 'Customer', 'yith-woocommerce-customer-history' ); ?></a>
                            </td>
                        </tr>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php
            } else {
                echo __( 'No orders for this user', 'yith-woocommerce-customer-history' );
            }
        }

    }

}
