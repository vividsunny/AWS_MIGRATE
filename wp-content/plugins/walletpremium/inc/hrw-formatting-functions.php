<?php

/*
 * Formatting functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'hrw_sanitize_text_field' ) ) {

    function hrw_sanitize_text_field( $value , $wp_unslash = true ) {

        if ( $wp_unslash )
            $value = wp_unslash( $value ) ;

        if ( is_array( $value ) )
            return array_map( 'hrw_sanitize_text_field' , $value ) ;

        return is_scalar( $value ) ? sanitize_text_field( $value ) : $value ;
    }

}

if ( ! function_exists( 'hrw_sanitize_text_area' ) ) {

    function hrw_sanitize_text_area( $value , $wp_unslash = true ) {

        if ( $wp_unslash )
            $value = wp_unslash( $value ) ;

        return implode( "\n" , array_map( 'hrw_sanitize_text_field' , explode( "\n" , $value ) ) ) ;
    }

}
