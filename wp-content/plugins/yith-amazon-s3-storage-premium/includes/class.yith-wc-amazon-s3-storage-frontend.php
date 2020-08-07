<?php
/*
 * This file belongs to the YITH Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_WC_FAVORITOS_Frontend
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author
 *
 */

if ( ! class_exists( 'YITH_WC_Amazon_S3_Storage_Frontend' ) ) {
	/**
	 * Class YITH_WC_Favoritos_Frontend
	 *
	 * @author
	 */
	class YITH_WC_Amazon_S3_Storage_Frontend {

		const PLUGIN_AMAZON_S3_STORAGE_DB_VERSION = '1.0.0';

		/**
		 * Construct
		 *
		 * @author
		 * @since 1.0
		 */

		protected $ajax;

		public function __construct() {

			/* ====== Sessions initation for ajax to include the classes with "Fronted" value ====== */
			$this->yith_woocommerce_sessions();

			add_filter( 'upload_dir', array( $this, 'yith_wc_as3s_upload_dir' ), 10, 1 );

			add_filter( 'wp_get_attachment_url', array( $this, 'yith_wc_as3s_wp_get_attachment_url' ), 10, 2 );

            add_filter( 'wp_calculate_image_srcset', array( $this, 'yith_wc_as3s_wp_calculate_image_srcset' ), 10, 5 );

			// Filter to check the content for possible images

            add_filter( 'the_content', array( $this, 'yith_as3s_woocommerce_check_the_content' ), 10, 3 );

        }

        /* ================================================================ */
        /* =============== MODIFYING THE URL OF THE SRCSET ================ */
        /* ================================================================ */
        public function yith_wc_as3s_wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

            if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

                if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) ) {

                    $replace = false;

                    $s3_path = get_post_meta( $attachment_id, '_wp_yith_wc_as3s_s3_path', true );

                    if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

                        $replace = true;

                    } else {//Integration with WPML for images

                        global $wpml_post_translations;

                        $id = $attachment_id;
                        if ($wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id)) {
                            $s3_path = get_post_meta($parent_id, '_wp_yith_wc_as3s_s3_path', true);
                            if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

                                $replace = true;

                            }
                        }
                    }

                    if( $replace ) {

                        $aws_Base_url = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

                        $upload_dir = wp_upload_dir();

                        foreach ( $sources as $key => $source ) {
                            $source[ 'url' ] = str_replace( $upload_dir['baseurl'], $aws_Base_url, $source[ 'url' ] );
                            $sources[ $key ] = $source;
                        }
                    }

                }
            }

            return $sources;

        }

        /* ================================================================ */
        /* === Checking the content === */
        /* ================================================================ */
        public function yith_as3s_woocommerce_check_the_content( $content ) {

            if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

                //$matches will be an array with all images
                preg_match_all("/<img[^>]+\>/i", $content, $matches );
                $matches = $matches[0];

                //remove all images form content
                //$content = preg_replace("/<img[^>]+\>/i", "", $content );

                $array_old_img = array();
                $array_new_img = array();

                //append the images to the end of the content
                foreach( $matches as $img ) {

                    $array_aux = explode( 'wp-image-', $img );
                    $array_aux = ( is_array($array_aux) && isset($array_aux[1]) ) ? explode( '"', $array_aux[1] ) : '';

                    if( is_array($array_aux) ) {

                        $post_id = $array_aux[0];


                        $wordpress_path = get_post_meta($post_id, '_wp_yith_wc_as3s_wordpress_path', true);

                        if (get_option('YITH_WC_amazon_s3_storage_replace_url_checkbox') || ($wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null)) {

                            $s3_path = get_post_meta($post_id, '_wp_yith_wc_as3s_s3_path', true);

                            if (($s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null)) {

                                $aws_Base_url = get_option('YITH_WC_amazon_s3_storage_connection_bucket_base_url');

                                $upload_dir = wp_upload_dir();

                                $array_old_img[] = $img;
                                $array_new_img[] = str_replace($upload_dir['baseurl'], $aws_Base_url, $img);

                            }

                        }
                    }

                }

                $i = 0;
                foreach ( $array_new_img as $new_img ){

                    $content = str_replace( $array_old_img[$i], $new_img, $content );
                    $i++;

                }

            }

            return $content;

        }

		/* ================================================================ */
		/* =============== MODIFYING THE URL OF ATTACHMENT ================ */
		/* ================================================================ */
		public function yith_wc_as3s_wp_get_attachment_url( $url, $post_id ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) ) {

			    $replace = false;

				$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

				    $replace = true;

				} else { //Integration with WPML for images

                    global $wpml_post_translations;

                    $id = $post_id;
                    if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id) ) {

                        $s3_path = get_post_meta($parent_id, '_wp_yith_wc_as3s_s3_path', true);

                        if (($s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null)) {

                           $replace = true;
                        }
                    }
                }

				if( $replace ) {

                    $aws_Base_url = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

                    $upload_dir = wp_upload_dir();

                    $url = str_replace( $upload_dir['baseurl'], $aws_Base_url, $url );
                }

			}
			return apply_filters( 'yith_wc_as3s_attachment_url_filter', $url, $post_id );

		}

		/* ================================================================ */
		/* ============= MODIFYING THE URL OF UPLOADED FILES ============== */
		/* ================================================================ */
		public function yith_wc_as3s_upload_dir( $cache_key ) {

			if ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) ) {

				$post_id = get_the_ID();

				$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {

					$cache_key['baseurl'] = get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url' );

				}



			}

			return apply_filters( 'yith_wc_as3s_upload_dir_filter', $cache_key );
		}

		public function yith_woocommerce_sessions() {

			$_SESSION[ 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_current_ajax' ] = "Frontend";

		}

	}
}
