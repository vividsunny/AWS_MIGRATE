<?php

/*
 * Discount
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_DISCOUNT' ) ) {

    /**
     * HRW_DISCOUNT Class.
     */
    class HRW_DISCOUNT extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::DISCOUNT_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'publish' ;

        /**
         * Rule Name
         */
        protected $rule_name ;

        /**
         * User Filter Type
         */
        protected $user_filter_type ;

        /**
         * Included User
         */
        protected $included_user = array() ;

        /**
         * Excluded User
         */
        protected $excluded_user = array() ;

        /**
         * Included User Role
         */
        protected $included_user_role = array() ;

        /**
         * Excluded User Role
         */
        protected $excluded_user_role = array() ;

        /**
         * Product Filter Type
         */
        protected $product_filter_type ;

        /**
         * Included Product
         */
        protected $included_product = array() ;

        /**
         * Excluded Product
         */
        protected $excluded_product = array() ;

        /**
         * Included Categories
         */
        protected $included_category = array() ;

        /**
         * Excluded Categories
         */
        protected $excluded_category = array() ;

        /**
         * Included Tag
         */
        protected $included_tag = array() ;

        /**
         * Excluded Tag
         */
        protected $excluded_tag = array() ;

        /**
         * Purchase History
         */
        protected $purchase_history ;

        /**
         * No of Order
         */
        protected $no_of_order ;

        /**
         * Total Amount
         */
        protected $total_amount ;

        /**
         * From Date
         */
        protected $from_date ;

        /**
         * To Date
         */
        protected $to_date ;

        /**
         * Rule Priority
         */
        protected $rule_priority ;

        /**
         * Rule Type
         */
        protected $rule_type ;
        
        /**
         * Order Total Type
         */
        protected $order_total_type ;

        /**
         * Valid Days
         */
        protected $valid_days = array() ;

        /**
         * Local Rules to get Discount Values
         */
        protected $local_rule = array() ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'rule_name'           => '' ,
            'user_filter_type'    => 1 ,
            'included_user'       => array() ,
            'excluded_user'       => array() ,
            'included_user_role'  => array() ,
            'excluded_user_role'  => array() ,
            'product_filter_type' => 1 ,
            'included_product'    => array() ,
            'excluded_product'    => array() ,
            'included_category'   => array() ,
            'excluded_category'   => array() ,
            'included_tag'        => array() ,
            'excluded_tag'        => array() ,
            'purchase_history'    => 1 ,
            'no_of_order'         => '' ,
            'total_amount'        => '' ,
            'from_date'           => '' ,
            'to_date'             => '' ,
            'rule_priority'       => 1 ,
            'rule_type'           => 1 ,
            'order_total_type'    => 1 ,
            'valid_days'          => array() ,
            'local_rule'          => array() ,
                ) ;

        /*
         * Set Rule Name
         */

        public function set_name( $value ) {
            $this->rule_name = $value ;
        }

        /*
         * Set User Filter Type
         */

        public function set_user_filter_type( $value ) {
            $this->user_filter_type = $value ;
        }

        /*
         * Set Product Filter Type
         */

        public function set_product_filter_type( $value ) {
            $this->product_filter_type = $value ;
        }

        /*
         * Set From Date
         */

        public function set_from_date( $value ) {
            $this->from_date = $value ;
        }

        /*
         * Set To Date
         */

        public function set_to_date( $value ) {
            $this->to_date = $value ;
        }

        /*
         * Set Rule Priority
         */

        public function set_rule_priority( $value ) {
            $this->rule_priority = $value ;
        }

        /*
         * Set Rule Type
         */

        public function set_rule_type( $value ) {
            $this->rule_type = $value ;
        }
        
        /*
         * Set Order Total Type
         */

        public function set_order_total_type( $value ) {
            $this->order_total_type = $value ;
        }

        /*
         * Set Valid Days
         */

        public function set_valid_days( $value ) {
            $this->valid_days = $value ;
        }

        /*
         * Set Local Rules
         */

        public function set_local_rule( $value ) {
            $this->local_rule = $value ;
        }

        /*
         * Get Rule Name
         */

        public function get_name() {
            return $this->rule_name ;
        }

        /*
         * Get User Filter Type
         */

        public function get_user_filter_type() {
            return $this->user_filter_type ;
        }

        /*
         * Get Included User
         */

        public function get_included_user() {
            return $this->included_user ;
        }

        /*
         * Get Excluded User
         */

        public function get_excluded_user() {
            return $this->excluded_user ;
        }

        /*
         * Get Included User Roles
         */

        public function get_included_user_roles() {
            return $this->included_user_role ;
        }

        /*
         * Get Excluded User Roles
         */

        public function get_excluded_user_roles() {
            return $this->excluded_user_role ;
        }

        /*
         * Get Product Filter Type
         */

        public function get_product_filter_type() {
            return $this->product_filter_type ;
        }

        /*
         * Get Included Products
         */

        public function get_included_products() {
            return $this->included_product ;
        }

        /*
         * Get Excluded Products
         */

        public function get_excluded_products() {
            return $this->excluded_product ;
        }

        /*
         * Get Included Category
         */

        public function get_included_category() {
            return $this->included_category ;
        }

        /*
         * Get Excluded Category
         */

        public function get_excluded_category() {
            return $this->excluded_category ;
        }

        /*
         * Get Included Tag
         */

        public function get_included_tag() {
            return $this->included_tag ;
        }

        /*
         * Get Excluded Tag
         */

        public function get_excluded_tag() {
            return $this->excluded_tag ;
        }

        /*
         * Get From Date
         */

        public function get_from_date() {
            return $this->from_date ;
        }

        /*
         * Get To Date
         */

        public function get_to_date() {
            return $this->to_date ;
        }

        /*
         * Get Rule Priority
         */

        public function get_rule_priority() {
            return $this->rule_priority ;
        }

        /*
         * Get Rule Type
         */

        public function get_rule_type() {
            return $this->rule_type ;
        }
        
        /*
         * Get Order Total Type
         */

        public function get_order_total_type() {
            return $this->order_total_type ;
        }

        /*
         * Get Applicable Type
         */

        public function get_purchase_history_type() {
            return $this->purchase_history ;
        }

        /*
         * Get No of Order
         */

        public function get_no_of_order() {
            return $this->no_of_order ;
        }

        /*
         * Get Total Amount
         */

        public function get_total_amount() {
            return $this->total_amount ;
        }

        /*
         * Get Valid Days
         */

        public function get_valid_days() {
            return $this->valid_days ;
        }

        /*
         * Get Local Rules
         */

        public function get_local_rule() {
            return $this->local_rule ;
        }

    }

}
    