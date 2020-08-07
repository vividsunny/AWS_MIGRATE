<?php


use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use ElementorPro\Modules\QueryControl\Module;

class YITH_WCACT_Current_Auctions_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'yith-wcact-current-auctions';
    }

    public function get_title() {
        return esc_html__( 'Current Auctions', 'yith-auctions-for-woocommerce' );
    }

    public function get_icon() {
        return 'fas fa-gavel';
    }

    public function get_categories() {
        return [ 'yith' ];
    }

    public function get_keywords() {
        return [ 'woocommerce', 'product', 'form', 'auction' ];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_order_header',
            array(
                'label' => esc_html__( 'Current Auctions', 'yith-auctions-for-woocommerce' ),
                'description' => esc_html__( 'Widget to show current auctions', 'yith-auctions-for-woocommerce' ),
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $product_id = !empty($settings['product_id']) ? $settings['product_id'] : '';

        echo '<div class="yith-wcact-current-auctions-elementor-widget">';

            echo is_callable('apply_shortcodes') ? apply_shortcodes('[yith_auction_current]') : do_shortcode( '[yith_auction_current]' );

        echo '</div>';
    }


}