<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if (!defined('YITH_WCACT_PATH')) {
    exit('Direct access forbidden.');
}
/**
 *
 *
 * @class      YITH_Auction_Shortcodes
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Rodriguez <carlos.rodriguez@yourinspiration.it>
 *
 */
if ( ! class_exists( 'YITH_WCACT_Auction_Shortcodes' ) ) {
/**
 * Class YITH_Auction_Shortcodes
 *
 * @author Carlos Rodriguez <carlos.rodriguez@yourinspiration.it>
 */
    class YITH_WCACT_Auction_Shortcodes
    {

        public static function init()
        {
            $shortcodes = array(
                'yith_auction_products' => __CLASS__ . '::yith_auction_products', // print auction products
                'yith_auction_out_of_date' => __CLASS__ . '::yith_auction_out_of_date',
                'yith_auction_show_list_bid' =>  __CLASS__ . '::yith_auction_show_list_bid',
                'yith_auction_current' => __CLASS__ . '::yith_auction_current',
                'yith_auction_non_started' => __CLASS__ . '::yith_auction_non_started',
                'yith_auction_form' => __CLASS__ .'::yith_auction_form',
            );


            foreach ($shortcodes as $shortcode => $function) {
                add_shortcode($shortcode, $function);
            }

            add_action( 'yith_wcact_pagination_nav', array( __CLASS__ , 'pagination_nav' ) );

            shortcode_atts( array('id' => ''), array(), 'yith_auction_show_list_bid');

        }

        /**
         * Loop over found products.
         * @param  array $query_args
         * @param  array $atts
         * @param  string $loop_name
         * @return string
         */
        private static function product_loop( $query_args, $atts, $loop_name ) {
            global $woocommerce_loop;
            $products                    = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts, $loop_name ) );
            $columns                     = absint( $atts['columns'] );
            $woocommerce_loop['columns'] = $columns;
            $woocommerce_loop['name']    = $loop_name;
            $orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
            $catalog_orderby_options = apply_filters( 'yith_wcact_shortcode_catalog_orderby', array(
                'menu_order' => esc_html__( 'Default sorting', 'yith-auctions-for-woocommerce' ),
                /*'price'      => esc_html__( 'Sort by price: low to high', 'yith-auctions-for-woocommerce' ),
                'price-desc' => esc_html__( 'Sort by price: high to low', 'yith-auctions-for-woocommerce' ),*/
                'auction_asc' => esc_html__('Sort auctions by end date (asc)', 'yith-auctions-for-woocommerce'),
                'auction_desc' => esc_html__('Sort auctions by end date (desc)', 'yith-auctions-for-woocommerce'),
            ),$query_args, $atts, $loop_name );
            ob_start();
            if(is_array($catalog_orderby_options)) {
                ?>

                <form class="woocommerce-ordering " method="get">
                    <select name="orderby" class="orderby">
                        <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                            <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php wc_query_string_form_fields(null, array('orderby', 'submit')); ?>
                </form>

                <?php
            }
            if ( $products->have_posts() ) {
                ?>

                <?php do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

                <?php if (  !isset($atts['pagination']) || 'no' != $atts['pagination'] ) { ?>

                    <?php do_action( 'yith_wcact_pagination_nav', $products->max_num_pages ); ?>

                <?php } ?>

                <?php
            } else {
                do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
            }

            wc_reset_loop();
            wp_reset_postdata();
            return woocommerce_catalog_ordering().'<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
        }


        /**
         * ShortCode for auction products
         *
         * @return void
         * @since 1.0.0
         */
        public static function yith_auction_products($atts)
        {
            $atts = shortcode_atts( array(
                'columns' => '4',
                'orderby' => '',
                'order'   => 'ASC',
                'ids'     => '',
                'skus'    => '',
                'posts_per_page' => '-1',
                'pagination' => 'yes'
            ), $atts,'yith_auction_products' );


            $ordering_args = self::get_catalog_ordering_args( $atts['orderby'], $atts['order'] );

            $query_args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             =>  $ordering_args['orderby'],
                'order'               =>  $ordering_args['order'],
                'posts_per_page'      =>  $atts['posts_per_page'],
                'paged' =>            ( get_query_var('paged') ) ? get_query_var('paged') : 1,
                'meta_query'          => WC()->query->get_meta_query(),
            );
            if ( isset( $ordering_args['meta_key'] ) ) {
                $query_args['meta_key'] = $ordering_args['meta_key'];
            }

            if ( $auction_term = get_term_by( 'slug', 'auction', 'product_type' ) ) {
                $posts_in = array_unique((array)get_objects_in_term($auction_term->term_id, 'product_type'));
                if (! empty ( $posts_in)) {

                    $query_args['post__in'] = array_map('trim', $posts_in ) ;

                    // Ignore catalog visibility
                    $query_args['meta_query'] = array_merge($query_args['meta_query'], isset( $ordering_args['meta_query'] ) ? $ordering_args['meta_query'] : array() );
                    global $wp_locale;
                    $date_params = array(
                        'format'       => get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ),
                        'month'        => $wp_locale->month,
                        'month_abbrev' => $wp_locale->month_abbrev,
                        'meridiem'     => $wp_locale->meridiem
                    );
                    wp_enqueue_style('yith-wcact-frontend-css');
                    wp_enqueue_script('yith_wcact_frontend_shop', YITH_WCACT_ASSETS_URL . '/js/fontend_shop-premium.js', array('jquery', 'jquery-ui-sortable'), YITH_WCACT_VERSION, true);
                    wp_localize_script('yith_wcact_frontend_shop', 'object', array(
                        'ajaxurl' => admin_url('admin-ajax.php')
                    ));
                    wp_localize_script( 'yith_wcact_frontend_shop', 'date_params', $date_params );

                    return self::product_loop( $query_args, $atts, 'yith_auction_products' );
                }
            }
            return '';
        }

        public static function get_catalog_ordering_args($orderby = '', $order = '') {
            if ( !$orderby ) {
                $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( (string) $_GET['orderby'] ) : apply_filters( 'yith_wcact_shortcode_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

                // Get order + orderby args from string
                $orderby_value = explode( '-', $orderby_value );
                $orderby       = esc_attr( $orderby_value[0] );
                $order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;
            }

            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );
            $args    = array();

            // default - menu_order
            $args['orderby']  = 'menu_order title';
            $args['order']    = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
            $args['meta_key'] = '';
            $args['join'] = '';
            
            switch ( $orderby ) {

               /* case 'price' :
                    if ( 'DESC' === $order ) {
                        $args['orderby']  = 'meta_value';
                        $args['order'] = 'ASC';
                        $args['meta_key'] = '_price';
                        //WC()->query->order_by_price_desc_post_clauses($args);

                    } else {
                        $args['orderby']  = 'meta_value';
                        $args['order'] = 'DESC';
                        $args['meta_key'] = '_price';
                        //WC()->query->order_by_price_asc_post_clauses($args);

                    }
                    break; */
                case 'auction_asc':
                    $args['orderby'] = 'meta_value';
                    $args['order'] = 'ASC';
                    $args['meta_key'] = '_yith_auction_to';
                    break;

                case 'auction_desc':
                    $args['orderby'] = 'meta_value';
                    $args['order'] = 'DESC';
                    $args['meta_key'] = '_yith_auction_to';
                    break;

                case 'rand':
                    $args['orderby'] = 'rand';
            }
            return apply_filters( 'kk
            ', $args,$orderby );
        }

        /**
         * ShortCode for auction products
         *
         * @return void
         * @since 1.0.0
         */
        public static function yith_auction_out_of_date($atts)
        {
            $atts = shortcode_atts( array(
                'columns' => '4',
                'orderby' => '',
                'order'   => 'ASC',
                'ids'     => '',
                'skus'    => '',
                'posts_per_page' => '-1',
                'pagination' => 'yes'
            ), $atts,'yith_auction_out_of_date' );

            $query_args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             => $atts['orderby'],
                'order'               => $atts['order'],
                'posts_per_page'      => $atts['posts_per_page'],
                'meta_query'          => WC()->query->get_meta_query()
            );

            if ( $auction_term = get_term_by( 'slug', 'auction', 'product_type' ) ) {
                $posts_in = array_unique((array)get_objects_in_term($auction_term->term_id, 'product_type'));
                if (! empty ( $posts_in)) {

                    $query_args['post__in'] = array_map('trim', $posts_in ) ;

                    $query_args['meta_query'][] = array(
                            'key'     => '_yith_auction_to',
                            'value'   => strtotime( 'now' ),
                            'compare' => '<'
                        );

                    // Ignore catalog visibility
                    $query_args['meta_query'] = array_merge($query_args['meta_query'], WC()->query->stock_status_meta_query());
                    global $wp_locale;
                    $date_params = array(
                        'format'       => get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ),
                        'month'        => $wp_locale->month,
                        'month_abbrev' => $wp_locale->month_abbrev,
                        'meridiem'     => $wp_locale->meridiem
                    );
                    wp_enqueue_style('yith-wcact-frontend-css');
                    wp_enqueue_script('yith_wcact_frontend_shop', YITH_WCACT_ASSETS_URL . '/js/fontend_shop-premium.js', array('jquery', 'jquery-ui-sortable'), YITH_WCACT_VERSION, true);
                    wp_localize_script('yith_wcact_frontend_shop', 'object', array(
                        'ajaxurl' => admin_url('admin-ajax.php')
                    ));
                    wp_localize_script( 'yith_wcact_frontend_shop', 'date_params', $date_params );


                    return self::product_loop( $query_args, $atts, 'yith_auction_out_of_date' );
                }
            }
            return '';
        }
        /**
         * ShortCode for show non started auction products
         *
         * @return void
         * @since 1.0.0
         */
        public static function yith_auction_non_started($atts) {

            $atts = shortcode_atts( array(
                'columns' => '4',
                'orderby' => '',
                'order'   => 'ASC',
                'ids'     => '',
                'skus'    => '',
                'posts_per_page' => '-1',
                'pagination' => 'yes'
            ), $atts,'yith_auction_non_started' );

            $query_args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             => $atts['orderby'],
                'order'               => $atts['order'],
                'posts_per_page'      => $atts['posts_per_page'],
                'meta_query'          => WC()->query->get_meta_query()
            );

            if ( $auction_term = get_term_by( 'slug', 'auction', 'product_type' ) ) {
                $posts_in = array_unique((array)get_objects_in_term($auction_term->term_id, 'product_type'));
                if (! empty ( $posts_in)) {

                    $query_args['post__in'] = array_map('trim', $posts_in ) ;

                    $query_args['meta_query'][] = array(
                        'key'     => '_yith_auction_for',
                        'value'   => strtotime( 'now' ),
                        'compare' => '>'
                    );

                    // Ignore catalog visibility
                    $query_args['meta_query'] = array_merge($query_args['meta_query'], WC()->query->stock_status_meta_query());
                    global $wp_locale;
                    $date_params = array(
                        'format'       => get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ),
                        'month'        => $wp_locale->month,
                        'month_abbrev' => $wp_locale->month_abbrev,
                        'meridiem'     => $wp_locale->meridiem
                    );
                    wp_enqueue_style('yith-wcact-frontend-css');
                    wp_enqueue_script('yith_wcact_frontend_shop', YITH_WCACT_ASSETS_URL . '/js/fontend_shop-premium.js', array('jquery', 'jquery-ui-sortable'), YITH_WCACT_VERSION, true);
                    wp_localize_script('yith_wcact_frontend_shop', 'object', array(
                        'ajaxurl' => admin_url('admin-ajax.php')
                    ));
                    wp_localize_script( 'yith_wcact_frontend_shop', 'date_params', $date_params );


                    return self::product_loop( $query_args, $atts, 'yith_auction_non_started' );
                }
            }
            return '';
        }

        /**
         * ShortCode show current auctions
         *
         * @return void
         * @since 1.0.0
         */

        public static function yith_auction_current($atts) {

            $atts = shortcode_atts( array(
                'columns' => '4',
                'orderby' => '',
                'order'   => 'ASC',
                'ids'     => '',
                'skus'    => '',
                'posts_per_page' => '-1',
                'pagination' => 'yes'
            ), $atts,'yith_auction_current' );

            $ordering_args = self::get_catalog_ordering_args( $atts['orderby'], $atts['order'] );

            $query_args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             =>  $ordering_args['orderby'],
                'order'               =>  $ordering_args['order'],
                'posts_per_page'      =>  $atts['posts_per_page'],
                'paged' =>            ( get_query_var('paged') ) ? get_query_var('paged') : 1,
                'meta_query'          => WC()->query->get_meta_query(),
            );

            if ( isset( $ordering_args['meta_key'] ) ) {
                $query_args['meta_key'] = $ordering_args['meta_key'];
            }

            if ( $auction_term = get_term_by( 'slug', 'auction', 'product_type' ) ) {
                $posts_in = array_unique((array)get_objects_in_term($auction_term->term_id, 'product_type'));
                if (! empty ( $posts_in)) {

                    $query_args['post__in'] = array_map('trim', $posts_in ) ;

                    $query_args['meta_query'][] = array(
                        'relation' => 'AND',
                        array(
                            'key'     => '_yith_auction_to',
                            'value'   => strtotime( 'now' ),
                            'compare' => '>='
                        ),
                        array(
                            'key'     => '_yith_auction_for',
                            'value'   => strtotime( 'now' ),
                            'compare' => '<='
                        )

                    );

                    // Ignore catalog visibility
                    $query_args['meta_query'] = array_merge($query_args['meta_query'], WC()->query->stock_status_meta_query());
                    global $wp_locale;
                    $date_params = array(
                        'format'       => get_option( 'yith_wcact_settings_date_format', 'j/n/Y h:i:s' ),
                        'month'        => $wp_locale->month,
                        'month_abbrev' => $wp_locale->month_abbrev,
                        'meridiem'     => $wp_locale->meridiem
                    );
                    wp_enqueue_style('yith-wcact-frontend-css');
                    wp_enqueue_script('yith_wcact_frontend_shop', YITH_WCACT_ASSETS_URL . '/js/fontend_shop-premium.js', array('jquery', 'jquery-ui-sortable'), YITH_WCACT_VERSION, true);
                    wp_localize_script('yith_wcact_frontend_shop', 'object', array(
                        'ajaxurl' => admin_url('admin-ajax.php'),
                    ));
                    wp_localize_script( 'yith_wcact_frontend_shop', 'date_params', $date_params );


                    return self::product_loop( $query_args, $atts, 'yith_auction_current' );
                }
            }
            return '';
        }

        /**
         * ShortCode show list bids
         *
         * @return void
         * @since 1.0.0
         */

        public static function yith_auction_show_list_bid($atts)
        {

            global $product;

            $auction_id = isset($atts['id']) ? $atts['id'] : 0;

            if (!$auction_id && $product && $product->get_id()) {
                $auction_id = $product->get_id();
            }

            if ($auction_id) {
                $auction_product = wc_get_product($auction_id);
                if ($auction_product && 'auction' == $auction_product->get_type()) {
                    $args = array(
                        'product' => $auction_product,
                        'currency' => get_woocommerce_currency(),
                    );

                    ob_start();
                    wc_get_template('list-bids.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'frontend/');
                    return ob_get_clean();

                }
            }
        }


        /**
         * Print Auction add to cart form
         *
         * @return void
         * @since 1.3.4
         */

        public static function yith_auction_form($atts) {

            global $product;
            ob_start();

            $auction_id = isset( $atts[ 'id' ] ) ? $atts[ 'id' ] : 0;

            if ( !$auction_id && $product && $product->get_id() ) {
                $auction_id = $product->get_id();
            }

            if ( $auction_id ) {
                $auction_product = wc_get_product( $auction_id );
                if ( $auction_product && 'auction' == $auction_product->get_type() ) {
                    global $product, $post;
                    $old_product = $product;
                    $old_post    = $post;
                    $post        = get_post( $auction_product->get_id() );
                    $product     = $auction_product;
                    wc_get_template('single-product/add-to-cart/auction.php', array(), '', YITH_WCACT_TEMPLATE_PATH . 'woocommerce/');
                    $product = $old_product;
                    $post    = $old_post;
                }
            }
            return ob_get_clean();

        }

        /**
         * Prints template for displaying navigation panel for pagination
         *
         * @param $max_num_pages
         */
        public static function pagination_nav( $max_num_pages ) {
            ob_start();
            wc_get_template( 'frontend/yith-auction-pagination-nav.php', array( 'max_num_pages' => $max_num_pages ), '', YITH_WCACT_TEMPLATE_PATH );
            echo ob_get_clean();
        }
    }
}