<?php

/**
 *  Handles Discount
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Discount_Handler' ) ) {

    /**
     * Class
     */
    class HRWP_Discount_Handler {

        /**
         * Matched Rules.
         */
        protected static $matched_rules ;

        /*
         * Get Matched Rule for Cashback
         */

        public static function get_matched_rules() {
            if ( self::$matched_rules )
                return self::$matched_rules ;

            self::$matched_rules = array() ;

            if ( ! is_user_logged_in() )
                return self::$matched_rules ;

            global $wpdb ;
            $query = new HRW_Query( $wpdb->posts , 'p' ) ;

            $discount_rule_ids = $query->select( 'DISTINCT `p`.`ID`' )
                    ->where( '`p`.post_type' , 'hrw_discount' )
                    ->where( '`p`.post_status' , 'publish' )
                    ->fetchArray() ;

            if ( ! hrw_check_is_array( $discount_rule_ids ) )
                return self::$matched_rules ;

            $local_rule    = array() ;
            $rule_priority = get_option( 'hrw_discount_rule_priority' ) ;
            foreach ( $discount_rule_ids as $ids ) {
                $discount = hrw_get_discount( $ids[ 'ID' ] ) ;

                if ( ! is_object( $discount ) )
                    continue ;

                /* Rule Matches User Filter */
                if ( ! self::user_filter( $discount ) )
                    continue ;

                /* Rule Matches Product Filter */
                if ( ! self::product_filter( $discount ) )
                    continue ;

                /* Rule Matches Day Filter */
                if ( ! self::days_filter( $discount ) )
                    continue ;

                /* Rule Matches Date Filter */
                if ( ! self::date_filter( $discount ) )
                    continue ;

                /* Rule Matches Purchased Filter */
                if ( ! self::purchased_filter( $discount ) )
                    continue ;

                if ( is_null( self::matched_rule( $discount , $ids[ 'ID' ] ) ) )
                    continue ;

                $local_rule[ $ids[ 'ID' ] ] = self::matched_rule( $discount , $ids[ 'ID' ] ) ;
            }

            /* Apply Global Rule Priority and Get Finally Matched Rule */
            if ( $rule_priority == '1' ) {
                self::$matched_rules = reset( $local_rule ) ;
            } elseif ( $rule_priority == '2' ) {
                self::$matched_rules = end( $local_rule ) ;
            } elseif ( $rule_priority == '3' ) {
                self::$matched_rules = min( $local_rule ) ;
            } else {
                self::$matched_rules = max( $local_rule ) ;
            }

            return self::$matched_rules ;
        }

        /*
         * User Filter
         */

        public static function user_filter( $discount ) {
            $user_id = get_current_user_id() ;

            $userrole = get_userdata( $user_id )->roles ;

            if ( $discount->get_user_filter_type() == '1' ) {
                return true ;
            } elseif ( $discount->get_user_filter_type() == '2' ) {
                if ( in_array( $user_id , $discount->get_included_user() ) )
                    return true ;
            } elseif ( $discount->get_user_filter_type() == '3' ) {
                if ( ! in_array( $user_id , $discount->get_excluded_user() ) )
                    return true ;
            } elseif ( $discount->get_user_filter_type() == '4' ) {
                $roles = array_intersect( $userrole , $discount->get_included_user_roles() ) ;
                if ( hrw_check_is_array( $roles ) )
                    return true ;
            } else {
                $roles = array_intersect( $userrole , $discount->get_excluded_user_roles() ) ;
                if ( ! hrw_check_is_array( $roles ) )
                    return true ;
            }

            return false ;
        }

        /*
         * Product Filter
         */

        public static function product_filter( $discount ) {
            if ( ! hrw_check_is_array( WC()->cart->get_cart() ) )
                return true ;

            $product_ids = array() ;
            $catids      = array() ;
            $tagids      = array() ;
            foreach ( WC()->cart->get_cart() as $item ) {
                $product_ids[] = empty( $item[ 'variation_id' ] ) ? $item[ 'product_id' ] : $item[ 'variation_id' ] ;
                $cat_list      = get_the_terms( $item[ 'product_id' ] , 'product_cat' ) ;
                $tag_list      = get_the_terms( $item[ 'product_id' ] , 'product_tag' ) ;
                if ( hrw_check_is_array( $cat_list ) ) {
                    foreach ( $cat_list as $cat ) {
                        $catids[] = $cat->term_id ;
                    }
                }

                if ( hrw_check_is_array( $tag_list ) ) {
                    foreach ( $tag_list as $tag ) {
                        $tagids[] = $tag->term_id ;
                    }
                }
            }

            if ( $discount->get_product_filter_type() == '1' ) {
                return true ;
            } elseif ( $discount->get_product_filter_type() == '2' ) {
                $array_intersect = array_intersect( $product_ids , $discount->get_included_products() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '3' ) {
                $array_intersect = array_intersect( $product_ids , $discount->get_excluded_products() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '4' ) {
                if ( hrw_check_is_array( $catids ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '5' ) {
                $array_intersect = array_intersect( $catids , $discount->get_included_category() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '6' ) {
                $array_intersect = array_intersect( $catids , $discount->get_excluded_category() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '7' ) {
                if ( hrw_check_is_array( $tagids ) )
                    return true ;
            } elseif ( $discount->get_product_filter_type() == '8' ) {
                $array_intersect = array_intersect( $tagids , $discount->get_included_tag() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } else {
                $array_intersect = array_intersect( $tagids , $discount->get_excluded_tag() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            }
            return false ;
        }

        /**
         * Days Filter.
         */
        public static function days_filter( $discount ) {
            if ( in_array( date( 'w' ) , $discount->get_valid_days() ) )
                return true ;

            return false ;
        }

        /**
         * Date Filter.
         */
        public static function date_filter( $discount ) {
            if ( (date( 'Y-m-d' ) >= $discount->get_from_date()) && (date( 'Y-m-d' ) <= $discount->get_to_date()) )
                return true ;

            return false ;
        }

        /*
         * Purchased History
         */

        public static function purchased_filter( $discount ) {
            $args      = array(
                'posts_per_page' => -1 ,
                'post_type'      => 'shop_order' ,
                'post_status'    => array_keys( wc_get_order_statuses() ) ,
                'meta_query'     => array(
                    'relation' => 'AND' ,
                    array(
                        'key'   => '_customer_user' ,
                        'value' => get_current_user_id() ,
                    ) ,
                    array(
                        'key'     => 'hr_wallet_topup_fund' ,
                        'compare' => 'NOT EXISTS'
                    )
                ) ,
                'fields'         => 'ids'
                    ) ;
            $order_ids = get_posts( $args ) ;
            if ( $discount->get_purchase_history_type() == 1 ) {
                if ( count( $order_ids ) >= $discount->get_no_of_order() )
                    return true ;
            } else {
                $total_spent = array() ;
                if ( hrw_check_is_array( $order_ids ) ) {
                    foreach ( $order_ids as $id ) {
                        $total_spent[] = get_post_meta( $id , '_order_total' , true ) ;
                    }
                }
                $purchased_amount = array_sum( $total_spent ) ;
                if ( $purchased_amount >= $discount->get_total_amount() )
                    return true ;
            }
            return false ;
        }

        /*
         * Matched Rule
         */

        public static function matched_rule( $discount , $rule_id ) {
            $wallet = hrw_topup_product_in_cart() ;
            if ( hrw_check_is_array( $wallet ) )
                return ;

            if ( ! hrw_check_is_array( $discount->get_local_rule() ) )
                return ;

            $matched_values = array() ;

            $cart_subtotal = ( float ) WC()->cart->get_subtotal() ;
            $cart_total    = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() ;
            $total         = ($discount->get_order_total_type() == 1) ? $cart_subtotal : ( float ) $cart_total ;

            foreach ( $discount->get_local_rule() as $value ) {
                if ( $total >= $value[ 'min' ] && $total <= $value[ 'max' ] )
                    $matched_values[] = ($value[ 'type' ] == '1') ? ( float ) $value[ 'value' ] : ((( float ) $value[ 'value' ] / 100) * $cart_subtotal) ;
            }

            return self::get_matched_discount_value( $discount , $matched_values ) ;
        }

        /*
         * Get Matched Discount Value based on Local Rule Priority
         */

        public static function get_matched_discount_value( $discount , $matched_values ) {
            if ( ! hrw_check_is_array( $matched_values ) )
                return ;

            if ( $discount->get_rule_priority() == 1 ) {
                $matched_value = reset( $matched_values ) ;
            } elseif ( $discount->get_rule_priority() == 2 ) {
                $matched_value = end( $matched_values ) ;
            } elseif ( $discount->get_rule_priority() == 3 ) {
                $matched_value = min( $matched_values ) ;
            } else {
                $matched_value = max( $matched_values ) ;
            }

            return $matched_value ;
        }

    }

}
