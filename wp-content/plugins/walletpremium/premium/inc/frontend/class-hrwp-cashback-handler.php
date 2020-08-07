<?php

/**
 *  Handles Cashback
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Cashback_Handler' ) ) {

    /**
     * Class
     */
    class HRWP_Cashback_Handler {

        /**
         * Matched Rules.
         */
        protected static $matched_rules ;

        /*
         * Get Matched Rule for Cashback
         */

        public static function get_matched_rules( $payment_method = '' ) {
            if ( self::$matched_rules )
                return self::$matched_rules ;

            self::$matched_rules = array() ;

            if ( ! is_user_logged_in() )
                return self::$matched_rules ;

            global $wpdb ;
            $query = new HRW_Query( $wpdb->posts , 'p' ) ;

            $cashback_rule_ids = $query->select( 'DISTINCT `p`.`ID`' )
                    ->where( '`p`.post_type' , 'hrw_cashback' )
                    ->where( '`p`.post_status' , 'publish' )
                    ->fetchArray() ;

            if ( ! hrw_check_is_array( $cashback_rule_ids ) )
                return self::$matched_rules ;

            $order_rule    = array() ;
            $wallet_rule   = array() ;
            $rule_priority = get_option( 'hrw_cashback_rule_priority' ) ;
            foreach ( $cashback_rule_ids as $ids ) {
                $cashback = hrw_get_cashback( $ids[ 'ID' ] ) ;

                if ( ! is_object( $cashback ) )
                    continue ;

                /* Rule Matches User Filter */
                if ( ! self::user_filter( $cashback ) )
                    continue ;

                /* Rule Matches Product Filter */
                if ( ! self::product_filter( $cashback ) )
                    continue ;

                /* Rule Matches Day Filter */
                if ( ! self::days_filter( $cashback ) )
                    continue ;

                /* Rule Matches Date Filter */
                if ( ! self::date_filter( $cashback ) )
                    continue ;

                /* Rule Matches Purchased Filter */
                if ( ! self::purchased_filter( $cashback ) )
                    continue ;

                if ( is_null( self::matched_rule( $cashback , $payment_method , $ids[ 'ID' ] ) ) )
                    continue ;

                /* Group Matched Rules */
                if ( $cashback->get_rule_type() == 1 ) {
                    $order_rule[ $ids[ 'ID' ] ] = self::matched_rule( $cashback , $payment_method , $ids[ 'ID' ] ) ;
                    if ( ! hrw_check_is_array( $order_rule ) )
                        continue ;

                    self::$matched_rules[ 'order_rule' ] = $order_rule ;
                } elseif ( $cashback->get_rule_type() == 2 ) {
                    $wallet_rule[ $ids[ 'ID' ] ] = self::matched_rule( $cashback , $payment_method , $ids[ 'ID' ] ) ;
                    if ( ! hrw_check_is_array( $wallet_rule ) )
                        continue ;

                    self::$matched_rules[ 'wallet_rule' ] = $wallet_rule ;
                }
            }

            /* Apply Global Rule Priority and Get Finally Matched Rule */
            if ( $rule_priority == '1' ) {
                if ( isset( self::$matched_rules[ 'order_rule' ] ) )
                    self::$matched_rules[ 'order_rule' ] = reset( self::$matched_rules[ 'order_rule' ] ) ;

                if ( isset( self::$matched_rules[ 'wallet_rule' ] ) )
                    self::$matched_rules[ 'wallet_rule' ] = reset( self::$matched_rules[ 'wallet_rule' ] ) ;
            } elseif ( $rule_priority == '2' ) {
                if ( isset( self::$matched_rules[ 'order_rule' ] ) )
                    self::$matched_rules[ 'order_rule' ] = end( self::$matched_rules[ 'order_rule' ] ) ;

                if ( isset( self::$matched_rules[ 'wallet_rule' ] ) )
                    self::$matched_rules[ 'wallet_rule' ] = end( self::$matched_rules[ 'wallet_rule' ] ) ;
            } elseif ( $rule_priority == '3' ) {
                if ( isset( self::$matched_rules[ 'order_rule' ] ) )
                    self::$matched_rules[ 'order_rule' ] = min( self::$matched_rules[ 'order_rule' ] ) ;

                if ( isset( self::$matched_rules[ 'wallet_rule' ] ) )
                    self::$matched_rules[ 'wallet_rule' ] = min( self::$matched_rules[ 'wallet_rule' ] ) ;
            } else {
                if ( isset( self::$matched_rules[ 'order_rule' ] ) )
                    self::$matched_rules[ 'order_rule' ] = max( self::$matched_rules[ 'order_rule' ] ) ;

                if ( isset( self::$matched_rules[ 'wallet_rule' ] ) )
                    self::$matched_rules[ 'wallet_rule' ] = max( self::$matched_rules[ 'wallet_rule' ] ) ;
            }

            return self::$matched_rules ;
        }

        /*
         * User Filter
         */

        public static function user_filter( $cashback ) {
            $user_id = get_current_user_id() ;

            $userrole = get_userdata( $user_id )->roles ;

            if ( $cashback->get_user_filter_type() == '1' ) {
                return true ;
            } elseif ( $cashback->get_user_filter_type() == '2' ) {
                if ( in_array( $user_id , $cashback->get_included_user() ) )
                    return true ;
            } elseif ( $cashback->get_user_filter_type() == '3' ) {
                if ( ! in_array( $user_id , $cashback->get_excluded_user() ) )
                    return true ;
            } elseif ( $cashback->get_user_filter_type() == '4' ) {
                $roles = array_intersect( $userrole , $cashback->get_included_user_roles() ) ;
                if ( hrw_check_is_array( $roles ) )
                    return true ;
            } else {
                $roles = array_intersect( $userrole , $cashback->get_excluded_user_roles() ) ;
                if ( ! hrw_check_is_array( $roles ) )
                    return true ;
            }

            return false ;
        }

        /*
         * Product Filter
         */

        public static function product_filter( $cashback ) {
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

            if ( $cashback->get_product_filter_type() == '1' ) {
                return true ;
            } elseif ( $cashback->get_product_filter_type() == '2' ) {
                $array_intersect = array_intersect( $product_ids , $cashback->get_included_products() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '3' ) {
                $array_intersect = array_intersect( $product_ids , $cashback->get_excluded_products() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '4' ) {
                if ( hrw_check_is_array( $catids ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '5' ) {
                $array_intersect = array_intersect( $catids , $cashback->get_included_category() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '6' ) {
                $array_intersect = array_intersect( $catids , $cashback->get_excluded_category() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '7' ) {
                if ( hrw_check_is_array( $tagids ) )
                    return true ;
            } elseif ( $cashback->get_product_filter_type() == '8' ) {
                $array_intersect = array_intersect( $tagids , $cashback->get_included_tag() ) ;
                if ( hrw_check_is_array( $array_intersect ) )
                    return true ;
            } else {
                $array_intersect = array_intersect( $tagids , $cashback->get_excluded_tag() ) ;
                if ( ! hrw_check_is_array( $array_intersect ) )
                    return true ;
            }
            return false ;
        }

        /**
         * Days Filter.
         */
        public static function days_filter( $cashback ) {
            if ( in_array( date( 'w' ) , $cashback->get_valid_days() ) )
                return true ;

            return false ;
        }

        /**
         * Date Filter.
         */
        public static function date_filter( $cashback ) {
            if ( (date( 'Y-m-d' ) >= $cashback->get_from_date()) && (date( 'Y-m-d' ) <= $cashback->get_to_date()) )
                return true ;

            return false ;
        }

        /*
         * Purchased History
         */

        public static function purchased_filter( $cashback ) {
            if ( $cashback->get_purchase_history_type() == 1 ) {
                $order_count = wc_get_customer_order_count( get_current_user_id() ) ;
                if ( $order_count >= $cashback->get_no_of_order() )
                    return true ;
            } else {
                $purchased_amount = wc_get_customer_total_spent( get_current_user_id() ) ;
                if ( $purchased_amount >= $cashback->get_total_amount() )
                    return true ;
            }
            return false ;
        }

        /*
         * Matched Rule
         */

        public static function matched_rule( $cashback , $payment_method , $rule_id ) {
            if ( $cashback->get_rule_type() == 1 ) {
                return self::get_matched_order_rule( $cashback ) ;
            } elseif ( $cashback->get_rule_type() == 2 ) {
                return self::get_matched_wallet_rule( $cashback ) ;
            }
        }

        /*
         * Matched Rule for Order
         */

        public static function get_matched_order_rule( $cashback ) {
            $wallet = hrw_topup_product_in_cart() ;
            if ( hrw_check_is_array( $wallet ) )
                return ;

            if ( ! hrw_check_is_array( $cashback->get_order_rule() ) )
                return ;

            $matched_values = array() ;
            $cart_subtotal = ( float ) WC()->cart->get_subtotal() ;
            $cart_total    = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() ;
            $total         = ($cashback->get_order_total_type() == 1) ? $cart_subtotal : ( float ) $cart_total ;
            
            foreach ( $cashback->get_order_rule() as $value ) {
                if ( $total >= $value[ 'min' ] && $total <= $value[ 'max' ] )
                    $matched_values[] = ($value[ 'type' ] == '1') ? ( float ) $value[ 'value' ] : ((( float ) $value[ 'value' ] / 100) * $cart_total) ;
            }

            return self::get_matched_cashback_value( $cashback , $matched_values ) ;
        }

        /*
         * Matched Rule for Wallet
         */

        public static function get_matched_wallet_rule( $cashback ) {
            $wallet = hrw_topup_product_in_cart() ;
            if ( ! hrw_check_is_array( $wallet ) )
                return ;

            if ( ! isset( $wallet[ 'price' ] ) )
                return ;

            if ( ! hrw_check_is_array( $cashback->get_wallet_rule() ) )
                return ;

            $matched_values = array() ;
            foreach ( $cashback->get_wallet_rule() as $value ) {
                if ( $wallet[ 'price' ] >= $value[ 'min' ] && $wallet[ 'price' ] <= $value[ 'max' ] )
                    $matched_values[] = ($value[ 'type' ] == '1') ? ( float ) $value[ 'value' ] : ((( float ) $value[ 'value' ] / 100) * $wallet[ 'price' ]) ;
            }

            return self::get_matched_cashback_value( $cashback , $matched_values ) ;
        }

        /*
         * Get Matched Cashback Value based on Local Rule Priority
         */

        public static function get_matched_cashback_value( $cashback , $matched_values ) {
            if ( ! hrw_check_is_array( $matched_values ) )
                return ;

            if ( $cashback->get_rule_priority() == 1 ) {
                $matched_value = reset( $matched_values ) ;
            } elseif ( $cashback->get_rule_priority() == 2 ) {
                $matched_value = end( $matched_values ) ;
            } elseif ( $cashback->get_rule_priority() == 3 ) {
                $matched_value = min( $matched_values ) ;
            } else {
                $matched_value = max( $matched_values ) ;
            }

            return $matched_value ;
        }

    }

}
