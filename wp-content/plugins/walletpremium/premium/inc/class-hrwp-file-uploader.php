<?php

/*
 * File Uploader
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists ( 'HRW_File_Uploader' ) ) {

    /**
     * Class
     */
    class HRW_File_Uploader {
        /*
         * Upload Folder Name
         */

        protected $folder_name ;

        /*
         * Upload Directory
         */
        protected $directory ;

        /*
         * Key
         */
        protected $key ;

        /*
         * Baseurl
         */
        protected $baseurl ;

        /*
         * Constructor
         */

        public function __construct() {
            $this->folder_name = 'HRW_Wallet_Uploads' ;

            $this->make_directory () ;
        }

        /**
         * get directory to upload file
         */
        public function get_directory() {
            if ( $this->directory )
                return $this->directory ;
            
            $upload_dir         = wp_upload_dir () ;
            $this->directory    = $upload_dir[ 'basedir' ] . '/' . $this->folder_name . '/' . date( 'Y' ).'/'.date( 'm' ) ;

            return $this->directory ;
        }

        /**
         * get baseurl to upload file
         */
        public function get_baseurl() {
            if ( $this->baseurl )
                return $this->baseurl ;

            $upload_dir         = wp_upload_dir () ;            
            $this->baseurl      = $upload_dir[ 'baseurl' ] . '/HRW_Wallet_Uploads/' . date( 'Y' ).'/'.date( 'm' );

            return $this->baseurl ;
        }

        /**
         * Make directory to upload file
         */
        public function make_directory() {
            $upload_dir = wp_upload_dir () ;
            $basedir    = $upload_dir[ 'basedir' ] . '/'.$this->folder_name.'/' . date( 'Y' ) ;
            
            if ( ! file_exists( $basedir ) && ! is_dir( $basedir ) )
                wp_mkdir_p( $basedir ) ;
            
            $basedir = $basedir . '/' . date( 'm' ) ;

            if ( ! file_exists( $basedir ) && ! is_dir( $basedir ) )
                wp_mkdir_p( $basedir ) ;
            
            if ( ! file_exists ( $this->get_directory () ) )
                wp_mkdir_p ( $this->get_directory () ) ;
        }

        /**
         * Prepare file name
         */
        public function prepare_file_name( $file_name ) {
            return $this->get_directory () . '/' . $file_name ;
        }

        /**
         * Move a file to server
         */
        public function upload_files( $temp_file ) {

            $temp_name = $temp_file[ 'tmp_name' ] ;

            $file_url = array () ;
            if ( is_array ( $temp_name ) && ! empty ( $temp_name ) ) {
                $file_count = count ( $temp_name ) ;
                for ( $i = 0 ; $i < $file_count ; $i ++ ) {
                    $tmp_name_to_move = $temp_name[ $i ] ;
                    if ( move_uploaded_file ( $tmp_name_to_move , $this->prepare_file_name ( $temp_file[ 'name' ][ $i ] ) ) ) {
                        $file_url[ $temp_file[ 'name' ][ $i ] ] = $this->prepare_file_name ( $temp_file[ 'name' ][ $i ] ) ;
                    }
                }
            } else {
                if ( $temp_name != '' ) {
                    if ( move_uploaded_file ( $temp_name , $this->prepare_file_name ( $temp_file[ 'name' ] ) ) ) {
                        $file_url[ $temp_file[ 'name' ] ] = $this->prepare_file_name ( $temp_file[ 'name' ] ) ;
                    }
                }
            }

            return $file_url ;
        }

    }

}
    