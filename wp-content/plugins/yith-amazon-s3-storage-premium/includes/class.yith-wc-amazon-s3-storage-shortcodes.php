<?php
/**
 * Created by PhpStorm.
 * User: dssaez
 * Date: 7/06/17
 * Time: 12:36
 */
if ( ! defined( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'Amazon_S3_Storage_Shortcodes' ) ) {

	class Amazon_S3_Storage_Shortcodes {

		public static function init() {

			$shortcodes = array(
				'yith_wc_amazon_s3_storage' => __CLASS__ . '::Show_Presigned_URL'
			);

			foreach ( $shortcodes as $shortcode => $function ) {
				add_shortcode( $shortcode, $function );
			}

			shortcode_atts( array( 'key' => '', 'name' => '' ), array(), 'yith_wc_amazon_s3_storage' );

		}

		public static function Show_Presigned_URL( $atts ) {

			$key  = isset( $atts['key'] ) ? $atts['key'] : '';
			$Name = isset( $atts['name'] ) ? $atts['name'] : '';

			$Bucket_Selected = ( get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) : '' );

			$Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

			$Bucket = $Array_Bucket_Selected[0];
			$Region = $Array_Bucket_Selected[1];

			$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

			$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

			require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

			$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

			$download_url = $aws_s3_client->Get_Presigned_URL( $Bucket, $Region, $key );

            $content = "by_php";

            if ( apply_filters( 'yith_wc_amazon_s3_storage_download_from_s3_by_php', true ) ){

                // downloading by php

                $headers = get_headers( $download_url, true );
                $size = $headers[ 'Content-Length' ];
                $type = $headers[ 'Content-Type' ];

                if( $type == 'audio/mp3' ) {

                    $ch = curl_init($download_url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_NOBODY, 0);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $output = curl_exec($ch);
                    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($status == 200) {
                        header( "X-Robots-Tag: noindex, nofollow", true );
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=\"" . $Name . "\";");
                        if ( $size ) {
                            header( "Content-Length: " . $size );
                        }
                        echo $output;
                        die();
                    }
                }
                header( "X-Robots-Tag: noindex, nofollow", true );
                header( "Content-Type: " . $type );
                header( "Content-Description: File Transfer" );
                header( "Content-Disposition: attachment; filename=\"" . $Name . "\";" );
                header( "Content-Transfer-Encoding: binary" );

                if ( $size ) {
                    header( "Content-Length: " . $size );
                }

                header( 'Location: ' . $download_url );

            }

            if ( apply_filters( 'yith_wc_amazon_s3_storage_download_from_s3_by_js', false ) )
            {

                // downloading by javascript
                ob_start();

                ?>

                <a id="YITH_WC_amazon_s3_storage_fake_dondload_link" href="<?php echo $download_url; ?>"></a>

                <script>

                    document.getElementById( "YITH_WC_amazon_s3_storage_fake_dondload_link" ).click();

                    setTimeout(function () {
                        window.history.back();
                    }, <?php echo apply_filters( 'yith_wc_amazon_s3_storage_download_from_s3_by_js_timeout', 1000 ); ?> );


                </script>

                <?php

                $content = ob_get_clean();

            }

			return $content;

		}

	}

}
