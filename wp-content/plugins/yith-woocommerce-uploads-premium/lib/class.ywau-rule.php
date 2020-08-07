<?php
if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YWAU_Rule' ) ) {

    /**
     *
     * @class   YWAU_Rule
     * @package Yithemes
     * @since   1.0.0
     * @author  Lorenzo Giuffrida
     */
    class YWAU_Rule {
        /**
         *
         * @var int
         */
        public $rule_id = 0;

        /**
         * @var string
         */
        public $upload_type = 'global';

        /**
         * @var int
         */
        public $product_id = 0;

        /**
         * @var int
         */
        public $variation_id = 0;

        /**
         * @var string
         */
        public $status;

        /**
         * @var string
         */
        public $label;

        /**
         * @var string
         */
        public $extension;


        /**
         * @var int
         */
        public $file_max_size = 0;

        /**
         * @var bool
         */
        public $enable_note = false;

        /**
         * Constructor
         *
         * Initialize rule object
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        public function __construct ( $args ) {
            $this->from_array ( $args );
        }

        /**
         * Build rule from an array parameter
         *
         * @param $args
         */
        public function from_array ( $args ) {
            $defaults = array (
                'rule-id'        => 0,
                'upload-type'    => 'none',
                'product_id'     => 0,
                'variation_id'   => 0,
                'status'         => 'visible',
                'rule-label'     => '',
                'rule-extension' => '',
                'rule-note'      => '',
                'rule-max-size'  => 0,
            );

            // Backward compatibility for previous key item_id now called product_id
            if ( isset( $args[ "item_id" ] ) ) {
                $args[ "product_id" ] = $args[ "item_id" ];
            }

            $args = wp_parse_args ( $args, $defaults );

            $this->extension     = $args[ "rule-extension" ];
            $this->file_max_size = $args[ "rule-max-size" ];
            $this->product_id    = $args[ "product_id" ];
            $this->variation_id  = $args[ "variation_id" ];
            $this->label         = $args[ "rule-label" ];
            $this->rule_id       = $args[ "rule-id" ];
            $this->status        = $args[ "status" ];
            $this->upload_type   = $args[ "upload-type" ];
            $this->enable_note   = $args[ "rule-note" ];
        }

        /**
         * Convert current object to equivalent array
         *
         * @return array
         */
        public function to_array () {
            return array (
                "rule-extension" => $this->extension,
                "rule-max-size"  => $this->file_max_size,
                "product_id"     => $this->product_id,
                "variation_id"   => $this->variation_id,
                "rule-label"     => $this->label,
                "rule-id"        => $this->rule_id,
                "status"         => $this->status,
                "upload-type"    => $this->upload_type,
                "rule-note"      => $this->enable_note,
            );
        }

        public function is_visible () {
            return "visible" == $this->status;
        }

        public function is_global_rule () {
            return "global" == $this->upload_type;
        }

        public function is_product_rule () {
            return "product" == $this->upload_type;
        }

    }
}