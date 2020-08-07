<?php
if ( ! defined ( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists ( 'YWAU_Rules' ) ) {

	/**
	 *
	 * @class   YWAU_Rules
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Lorenzo Giuffrida
	 */
	class YWAU_Rules {

		private $items;

		public function __construct() {
			$this->items = array();
			$rules       = get_option ( "ywau_multi_upload_settings", array() );

			/* filter only valid rules */
			if ( $rules ) {

				/** @var YWAU_Rule $single_rule */
				foreach ( $rules as $single_rule ) {

					if ( "visible" == $single_rule["status"] ) {
						$rule_id                 = $single_rule["rule-id"];
						$this->items[ $rule_id ] = $single_rule;
					}
				}
			}

		}

		/**
		 * Retrieve rule list for global settings
		 *
		 * @param int  $product_id   the product id to use
		 * @param int  $variation_id the variation id to use
		 * @param bool $strict_mode  In strict_mode only rule of the product can be used. If strict_mode is off then the global rules are used
		 *
		 * @return mixed|void
		 */
		public function get_product_rules_settings( $product_id, $variation_id = 0, $strict_mode = false ) {
			$global_rules = array();


			//  check if upload is disabled for current product and retrieve an empty rule set
			$product_id      = apply_filters ( 'yith_ywau_get_product_id_before_rules_checking', $product_id );
			if ($product_id) {
				$product         = wc_get_product ( $product_id );
				$upload_disabled = yit_get_prop ( $product, "_ywau_disabled_upload" );

				if ( $upload_disabled ) {
					return array();
				}
			}

			//  Get variation rules, if current product is
			$is_variation = $variation_id > 0;

			$variation_rules = array();
			if ( $is_variation ) {
				$product         = wc_get_product ( $variation_id );
				$metas           = yit_get_prop ( $product, '_variation_rules' );
				$variation_rules = is_array ( $metas ) ? $metas : array();
			}

			$product_rules     = array();
			$product_has_rules = false;

			foreach ( $this->items as $key => $value ) {
				$rule = new YWAU_Rule( $value );

				if ( ! $rule->is_visible () ) {
					continue;
				}

				if ( get_option( 'ywau_enable_product_variations_upload' ) == 'yes' ){
                    if ( $is_variation && ! empty( $variation_rules ) && ! array_key_exists ( $rule->rule_id, $variation_rules ) ) {
                        continue;
                    }
                }
                else{
                    if ( $is_variation && array_key_exists ( $rule->rule_id, $variation_rules ) ) {
                        continue;
                    }
                }

				//  While product_rules is empty, build the global rules array so it can be retrieved if the product
				//  doesn't have specific rules
				if ( ! $product_has_rules && $rule->is_global_rule () ) {
					$global_rules[] = $rule;
				} else if ( $rule->is_product_rule () && ( $rule->product_id == $product_id ) ) {
					$product_has_rules = true;
					$product_rules[]   = $rule;
				}
			}

			if ( $product_has_rules ) {

				return $product_rules;
			}

			//  In strict_mode only rule of the product can be used. If strict_mode is off then the global rules
			//  are used

			if ( $strict_mode ) {
				return array();
			}

			return $global_rules;
		}

		/**
		 * Retrieve rule settings based on the upload type
		 *
		 * @param $upload_type
		 *
		 * @return array
		 */
		public function get_global_rules_settings() {
			$valid_rules = array();

			if ( ! $this->items ) {
				return $valid_rules;
			}

			foreach ( $this->items as $key => $value ) {

				$rule = new YWAU_Rule( $value );

				if ( $rule->is_visible () && $rule->is_global_rule () ) {
					$valid_rules[] = $rule;
				}
			}

			return $valid_rules;
		}

		/**
		 * Retrieve rule settings based on the upload type
		 *
		 * @param $upload_type
		 *
		 * @return array
		 */
		public function get_rules_settings_by_type( $type, $product_id = 0, $strict_mode = false ) {
			if ( "global" === $type ) {
				return $this->get_global_rules_settings ();
			} else if ( "product" === $type ) {
				return $this->get_product_rules_settings ( $product_id, 0, $strict_mode );
			}

			return array();
		}

		public function get_rule( $product_id, $rule_id ) {
			$product_rules = $this->get_product_rules_settings ( $product_id );

			/** @var $product_rule YWAU_Rule */

			if ( $product_rules != null ) {
				foreach ( $product_rules as $product_rule ) {
					if ( $product_rule->rule_id == $rule_id ) {
						return $product_rule;
					}
				}
			}

			return null;
		}

		public function add_item_from_array( $args ) {
			$rule_id                 = $args["rule-id"];
			$this->items[ $rule_id ] = $args;
		}

		public function save() {
			update_option ( "ywau_multi_upload_settings", $this->items );
		}
	}
}
