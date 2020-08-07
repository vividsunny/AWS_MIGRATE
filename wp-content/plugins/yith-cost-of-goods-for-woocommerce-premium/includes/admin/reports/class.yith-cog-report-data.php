<?php

defined( 'ABSPATH' ) or exit;



if ( ! class_exists( 'YITH_COG_Admin_Report' ) ) {
    require_once( YITH_COG_PATH . 'includes/admin/reports/abstract-yith-cog-admin-report.php' );
}

/**
 * @class      YITH_COG_Report_Data
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Francisco Mendoza
 *
 */
class YITH_COG_Report_Data extends YITH_COG_Admin_Report {

    /**
     * Date variables
     */
    public $start_date;
    public $end_date;

    /**
     * Main Instance
     *
     * @var YITH_COG_Report_Data
     * @since 1.0
     */
    protected static $_instance = null;

    /**
     * Construct
     *
     * @since 1.0
     */
    public function __construct() {}


    /**
     * Return the currently selected date range for the report
     */
    protected function get_current_range() {

        return ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';
    }


    /**
     * Render the report data, including legend and chart
     *
     * @since 1.0
     */
    public function output_report() {

        $current_range = $this->get_current_range();

        if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ), true ) ) {
            $current_range = '7day';
        }

        $this->calculate_current_range( $current_range );

        // used in view
        $ranges = array(
            'year'         => esc_html__( 'Year', 'yith-cost-of-goods-for-woocommerce' ),
            'last_month'   => esc_html__( 'Last Month', 'yith-cost-of-goods-for-woocommerce' ),
            'month'        => esc_html__( 'This Month', 'yith-cost-of-goods-for-woocommerce' ),
            '7day'         => esc_html__( 'Last 7 Days', 'yith-cost-of-goods-for-woocommerce' )
        );

        include( YITH_COG_TEMPLATE_PATH . '/html/html-report-by-date.php');
    }


    /**
     * Render the report data, including legend and chart
     *
     * @since 1.0
     */
    public function output_report_secondary() {

        $current_range = $this->get_current_range();

        if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ), true ) ) {
            $current_range = '7day';
        }

        $this->calculate_current_range( $current_range );

        // used in view
        $ranges = array(
            'year'         => esc_html__( 'Year', 'yith-cost-of-goods-for-woocommerce' ),
            'last_month'   => esc_html__( 'Last Month', 'yith-cost-of-goods-for-woocommerce' ),
            'month'        => esc_html__( 'This Month', 'yith-cost-of-goods-for-woocommerce' ),
            '7day'         => esc_html__( 'Last 7 Days', 'yith-cost-of-goods-for-woocommerce' )
        );
    }


    /**
     * Render the export CSV button
     */
    public function output_export_button( $args = array() ) {

        $current_range = $this->get_current_range();
        ?>
        <a
                href="#"
                download="report-<?php echo 'YITH_COG_by_date_' . esc_attr( $current_range ); ?>-<?php echo date_i18n( 'Y-m-d', current_time( 'timestamp' ) ); ?>.csv"
                class="yith_export_csv export_csv"
                data-export="table"
        >
            <?php _e( 'Export CSV', 'yith-cost-of-goods-for-woocommerce' ); ?>
        </a>
        <?php
    }


    /**
     * Render the "Export to CSV" button
     */
    public function get_export_button() {
        $this->output_export_button();
    }


    /**
     * Get the data for the Report
     */
    public function get_report_data()
    {

        if ( !empty( $this->report_data ) ) {
            return $this->report_data;
        }

        $this->report_data = new stdClass();


        $order_status_option = get_option( 'yith_cog_order_status_report' );
        if ( $order_status_option == '0' ){
            $order_status = array( 'completed', 'refunded' );
        }
        else if ( $order_status_option == '1' ){
            $order_status = array( 'completed', 'processing', 'refunded' );
        }
        else{
            $order_status = array( 'completed', 'processing', 'on-hold', 'refunded' );
        }


        //Data Query
        $this->report_data->sales = $this->get_order_report_data(array(
            'data' => array(
                'ID' => array(
                    'type' => 'post_data',
                    'function' => 'DISTINCT',
                    'name' => 'order_id',
                ),
                'post_date' => array(
                    'type' => 'post_data',
                    'function' => '',
                    'name' => 'post_date',
                ),
                '_variation_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'variation_id',
                ),
                '_product_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'product_id',
                ),
                '_yith_cog_item_price' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'item_price',
                ),
                '_yith_cog_item_cost' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'item_cost',
                ),
                '_qty' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'item_count',
                ),
                'order_item_id' => array(
                    'type' => 'order_item',
                    'function' => '',
                    'name' => 'item_id',
                ),
            ),
            'query_type' => 'get_results',
            'filter_range' => true,
            'nocache' => true,
            'order_status'  => $order_status,
        ));

        $this->report_data->product_ids = wp_list_pluck($this->report_data->sales, 'product_id');
        $this->report_data->variation_ids = wp_list_pluck($this->report_data->sales, 'variation_id');
        $this->report_data->item_count = wp_list_pluck($this->report_data->sales, 'item_count');
        $this->report_data->item_id = wp_list_pluck($this->report_data->sales, 'item_id');
        $this->report_data->item_price = wp_list_pluck($this->report_data->sales, 'item_price');
        $this->report_data->item_cost = wp_list_pluck($this->report_data->sales, 'item_cost');
        $this->report_data->order_id = wp_list_pluck($this->report_data->sales, 'order_id');


        /* Taxes ****************************/
        $this->report_data->taxes = $this->get_order_report_data(array(
            'data' => array(
                'ID' => array(
                    'type' => 'post_data',
                    'function' => 'DISTINCT',
                    'name' => 'order_id',
                ),
                'order_item_id' => array(
                    'type' => 'order_item',
                    'function' => '',
                    'name' => 'item_id',
                ),
                'post_date' => array(
                    'type' => 'post_data',
                    'function' => '',
                    'name' => 'post_date',
                ),
                '_variation_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'variation_id',
                ),
                '_product_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'product_id',
                ),
                '_yith_cog_item_tax' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'item_tax',
                ),
            ),
            'query_type' => 'get_results',
            'filter_range' => true,
            'nocache' => true,
            'order_status'  => $order_status,
        ));

        $this->report_data->item_tax = wp_list_pluck($this->report_data->taxes, 'item_tax');


        //apply cost if no set or overriding
        $this->report_data->set_cost = $this->get_order_report_data(array(
            'data' => array(
                'ID' => array(
                    'type' => 'post_data',
                    'function' => 'DISTINCT',
                    'name' => 'order_id',
                ),
                'order_item_id' => array(
                    'type' => 'order_item',
                    'function' => '',
                    'name' => 'item_id',
                ),
                '_product_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'product_id',
                ),
                '_variation_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'variation_id',
                ),
            ),
            'query_type' => 'get_results',
            'nocache' => true,
            'order_status'  => array( 'completed', 'processing', 'on-hold'),
        ));

        $this->report_data->item_id_ = wp_list_pluck($this->report_data->set_cost, 'item_id');
        $this->report_data->product_ids_ = wp_list_pluck($this->report_data->set_cost, 'product_id');
        $this->report_data->variation_ids_ = wp_list_pluck($this->report_data->set_cost, 'variation_id');
        $this->report_data->order_id_ = wp_list_pluck($this->report_data->set_cost, 'order_id');

        /* Operations for the totals table *************************/
        $item_qty_array = $this->report_data->item_count;
        $item_taxes_array = $this->report_data->item_tax;
        $item_price_array = $this->report_data->item_price;
        $item_cost_array = $this->report_data->item_cost;
        $order_id_array = $this->report_data->order_id;
        $order_id_array_unique = array_unique($order_id_array);

        $total_shipping = 0;
        foreach ( $order_id_array_unique as $order_id ){
            $order = wc_get_order( $order_id );
            $order_shipping = $order->get_shipping_total();
            $total_shipping += (int)$order_shipping;
        }
        foreach ( $order_id_array_unique as $order_id ){
            $order = wc_get_order( $order_id );
            $order_fees = $order->get_fees();
        }

        if (! empty($order_fees)){
            $total_fees = array_sum($order_fees);
        }
        else{
            $total_fees = 0;
        }

        foreach ($item_price_array as $key => $value){

            $item_id = $this->report_data->item_id[$key];
            $item_price_array[$key] = $item_qty_array[$key] * apply_filters( 'yith_cog_convert_amounts', $value, $item_id );
        }
        foreach ($item_cost_array as $key => $value){
                $item_cost_array[$key] = $item_qty_array[$key] * (float)$value;
        }

        $this->report_data->total_fees = 0;
        $this->report_data->report_total_sales = array_sum( $item_qty_array );
        $this->report_data->report_total_prices = array_sum( $item_price_array );
        $this->report_data->report_total_cost = array_sum( $item_cost_array);
        $this->report_data->report_total_taxes = array_sum( $item_taxes_array);

        if ( ! $this->exclude_shipping() ){
            $this->report_data->report_total_cost += $total_shipping;
        }
        if ( ! $this->exclude_taxes() ){
            $this->report_data->report_total_cost += $this->report_data->report_total_taxes;
        }
        if ( ! $this->exclude_fees() ){
            $this->report_data->report_total_cost += $total_fees;
        }

        $this->report_data->report_total_profit = $this->report_data->report_total_prices - $this->report_data->report_total_cost;

        return apply_filters( 'yith_cog_report_data', $this->report_data, $this->report_data->product_ids, $this );
    }

}
