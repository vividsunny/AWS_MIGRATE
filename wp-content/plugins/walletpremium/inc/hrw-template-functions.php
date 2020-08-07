<?php

/*
 * Template functions
 */

if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists ( 'hrw_get_dashboard_menus' ) ) {

    function hrw_get_dashboard_menus() {

        return apply_filters ( 'hrw_frontend_dashboard_menu' , array (
            'overview' => array (
                'label' => get_option ( 'hrw_dashboard_customization_appointments_label' , 'Overview' ) ,
                'code'  => 'fa fa-eye' ,
            ) ,
            'activity' => array (
                'label' => get_option ( 'hrw_dashboard_customization_profile_label' , 'Wallet Activity' ) ,
                'code'  => 'fa fa-bar-chart'
            ) ,
            'topup'    => array (
                'label' => get_option ( 'hrw_dashboard_customization_profile_label' , 'Top-up Form' ) ,
                'code'  => 'fa fa-file-text-o'
            ) ,
            'profile'  => array (
                'label' => get_option ( 'hrw_dashboard_customization_profile_label' , 'Profile' ) ,
                'code'  => 'fa fa-user'
            ) ,
                )
                ) ;
    }

}

if ( ! function_exists ( 'hrw_get_dashboard_menu_classes' ) ) {

    function hrw_get_dashboard_menu_classes( $menu ) {
        global $hrw_current_menu ;

        $classes = array (
            'hrw_dashboard_menu_link' ,
            'hrw_dashboard_menu_link_' . $menu ,
                ) ;

        if ( $menu == $hrw_current_menu ) {

            $classes[] = 'current' ;
        }

        $classes = apply_filters ( 'hrw_frontend_dashboard_menu_classes' , $classes ) ;

        return implode ( ' ' , array_map ( 'sanitize_html_class' , $classes ) ) ;
    }

}

if ( ! function_exists ( 'hrw_get_dashboard_submenu_classes' ) ) {

    function hrw_get_dashboard_submenu_classes( $submenu ) {
        global $hrw_current_submenu ;

        $classes = array (
            'hrw_dashboard_submenu_link' ,
            'hrw_dashboard_submenu_link_' . $submenu ,
                ) ;

        if ( $submenu == $hrw_current_submenu ) {
            $classes[] = 'current' ;
        }

        $classes = apply_filters ( 'hrw_frontend_dashboard_submenu_classes' , $classes ) ;

        return implode ( ' ' , array_map ( 'sanitize_html_class' , $classes ) ) ;
    }

}