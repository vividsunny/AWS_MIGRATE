<?php
if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YWAU_Uploaded_Item' ) ) {

    /**
     *
     * @class   YWAU_Uploaded_Item
     * @package Yithemes
     * @since   1.0.0
     * @author  Lorenzo Giuffrida
     */
    class YWAU_Uploaded_Item {
        /**
         *
         * @var int
         */
        public $rule_id = 0;

        public $item_id = 0;

        public $order_id = 0;

        public $product_id = 0;

        public $variation_id = 0;

        public $note = '';

        public $upload_status = '';

        public $upload_message = '';

        public $upload_filepath = '';

        public $admin_response = '';

        public $admin_response_status = 'none';

        public $admin_response_date = '';

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
                'item_id'               => 0,
                'variation_id'          => 0,
                'rule_id'               => 0,
                'order_id'              => 0,
                'product_id'            => 0,
                'note'                  => '',
                'upload_status'         => '',
                'upload_message'        => '',
                'upload_filepath'       => '',
                'admin_response'        => '',
                'admin_response_status' => 'none',
                'admin_accepted'        => false,
                'admin_response_date'   => '',
            );

            $args = wp_parse_args ( $args, $defaults );

            $this->rule_id      = $args[ "rule_id" ];
            $this->order_id     = $args[ "order_id" ];
            $this->item_id      = $args[ "item_id" ];
            $this->variation_id = $args[ "variation_id" ];
            $this->product_id   = $args[ "product_id" ];
            $this->note         = $args[ "note" ];

            $this->upload_status         = $args[ "upload_status" ];
            $this->upload_message        = $args[ "upload_message" ];
            $this->upload_filepath       = $args[ "upload_filepath" ];
            $this->admin_response        = $args[ "admin_response" ];
            $this->admin_response_status = $args[ "admin_response_status" ];
            $this->admin_response_date   = $args[ "admin_response_date" ];
        }

        /**
         * Convert current object to equivalent array
         *
         * @return array
         */
        public function to_array () {
            return array (
                "item_id"               => $this->item_id,
                "rule_id"               => $this->rule_id,
                "order_id"              => $this->order_id,
                "product_id"            => $this->product_id,
                "variation_id"          => $this->variation_id,
                "note"                  => $this->note,
                "upload_status"         => $this->upload_status,
                "upload_message"        => $this->upload_message,
                "upload_filepath"       => $this->upload_filepath,
                "admin_response_status" => $this->admin_response_status,
                "admin_response"        => $this->admin_response,
                "admin_response_date"   => $this->admin_response_date,
            );
        }

        public function set_upload_failed ( $message ) {
            $this->upload_status  = "failed";
            $this->upload_message = $message;
        }

        public function is_upload_failed () {
            return "failed" == $this->upload_status;
        }

        public function set_upload_successful ( $message, $filepath ) {
            $this->upload_status   = "successful";
            $this->upload_message  = $message;
            $this->upload_filepath = $filepath;
        }

        public function is_upload_successful () {
            return "successful" == $this->upload_status;
        }

        /**
         * Check if is a rule that is validated and can be stored and used
         *
         * @return bool
         */
        public function is_validated () {
            return ! empty( $this->upload_status );
        }

        /**
         * Check if a file is attached to current item
         *
         * @return bool
         */
        public function has_file_uploaded () {
            return ! empty( $this->upload_filepath );
        }

        /**
         * Set item to accept status
         *
         * @param bool $force force status event on failed item or let it only if the item was in successful state
         */
        public function set_admin_accepted ( $force = false ) {
            if ( $force || ( $this->is_upload_successful () ) ) {
                $this->admin_response_status = "accepted";
                $this->admin_response_date   = date_i18n ( 'Y-m-d', time () );
            }
        }

        public function is_accepted () {
            return "accepted" == $this->admin_response_status;
        }

        public function set_admin_rejected ( $message ) {
            $this->admin_response_status = "rejected";
            $this->admin_response        = $message;
            $this->admin_response_date   = date_i18n ( 'Y-m-d', time () );
        }

        public function is_rejected () {
            return "rejected" == $this->admin_response_status;
        }

        public function is_waiting_response () {
            return $this->is_upload_successful () && ! $this->is_rejected () && ! $this->is_accepted ();
        }

        public function delete_file () {
            if ( file_exists ( $this->upload_filepath ) ) {
                unlink ( $this->upload_filepath );
            } else if ( file_exists ( YITH_YWAU_SAVE_DIR . $this->upload_filepath ) ) {
                unlink ( YITH_YWAU_SAVE_DIR . $this->upload_filepath );
            }
        }


        /**
         * Get the thumbnail path for current item if exists or the original image if a thumbnail doesn't exists
         *
         * @return string the thumbnail path
         */
        public function get_thumbnail_filepath () {
            $thumb_path = sprintf ( "%s/%s",
                dirname ( $this->upload_filepath ),
                $this->get_thumbnail_filename () );

            if ( ! file_exists ( YITH_YWAU_SAVE_DIR . $thumb_path ) ) {
                return "";
            }

            return $thumb_path;
        }

        /**
         * Get the thumbnail file name
         *
         * @return string the thumbnail file name
         */
        public function get_thumbnail_filename () {
            $thumb_name = pathinfo ( $this->upload_filepath, PATHINFO_FILENAME ) . "_thumb";
            $thumb_ext  = pathinfo ( $this->upload_filepath, PATHINFO_EXTENSION );

            return sprintf ( "%s.%s", $thumb_name, $thumb_ext );
        }
    }
}