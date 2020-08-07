<?php

if ( ! defined( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}


/*
* This file belongs to the YIT Framework.
*
* This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://www.gnu.org/licenses/gpl-3.0.txt
*/

if ( isset( $_POST['YITH_WC_amazon_s3_storage_general_settings_field'] ) && wp_verify_nonce( $_POST['YITH_WC_amazon_s3_storage_general_settings_field'], 'YITH_WC_amazon_s3_storage_general_settings_action' ) ) {

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_connection_bucket_selected_select'] ) ) {
        $Bucket_Selected = $_POST['YITH_WC_amazon_s3_storage_connection_bucket_selected_select'];
        update_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select', $Bucket_Selected );

        $Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

        if ( count( $Array_Bucket_Selected ) == 2 ){
            $Bucket                = $Array_Bucket_Selected[0];
            $Region                = $Array_Bucket_Selected[1];
        }
        else{
            $Bucket                = 'none';
            $Region                = 'none';
        }

        $Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

        $Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

        require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

        $aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

        $result = $aws_s3_client->delete_Objects_no_base_folder_yith( $Bucket, $Region, array( '5a90320d39a72_yith_wc_as3s_5a90320d39a8a.txt', '5a902e5124a80_yith_wc_as3s_5a902e5124a86.txt', '5a902be279c34_yith_wc_as3s_5a902be279c3btxt' ) );

        $Keyname = uniqid() . '_yith_wc_as3s_' . uniqid() . '.txt';

        $Base_url = $aws_s3_client->get_base_url( $Bucket, $Region, $Keyname );

        $result = $aws_s3_client->delete_Objects_no_base_folder_yith( $Bucket, $Region, array( $Keyname ) );

        update_option( 'YITH_WC_amazon_s3_storage_connection_bucket_base_url', $Base_url );
    }

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_copy_file_s3_checkbox'] ) ) {
        update_option( 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox', true );
    } else {
        update_option( 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox', false );
    }

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_replace_url_checkbox'] ) ) {
        update_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox', true );
    } else {
        update_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox', false );
    }

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_remove_from_server_checkbox'] ) ) {
        update_option( 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox', true );
    } else {
        update_option( 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox', false );
    }

    ?>

    <div class="updated settings-error notice is-dismissible">

        <p><strong><?php _e( 'Settings saved.', 'yith-amazon-s3-storage' ); ?></strong></p>

    </div>

    <?php

}

$result = YITH_WC_amazon_s3_check_connection_success();

$File_S3_checkbox = ( get_option( 'YITH_WC_amazon_s3_storage_copy_file_s3_checkbox' ) ? 'checked="checked"' : '' );

$Replace_URL_checkbox = ( get_option( 'YITH_WC_amazon_s3_storage_replace_url_checkbox' ) ? 'checked="checked"' : '' );

$Remove_From_Server_checkbox = ( get_option( 'YITH_WC_amazon_s3_storage_remove_from_server_checkbox' ) ? 'checked="checked"' : '' );

$Path_ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

?>

<div id="yith_wc_as3s_main_div">

<form action="" method="post">

    <?php wp_nonce_field( 'YITH_WC_amazon_s3_storage_general_settings_action', 'YITH_WC_amazon_s3_storage_general_settings_field' ); ?>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Set a bucket name', 'yith-amazon-s3-storage' ); ?></span>

            <span id="Yith_WC_as3s_Buckets_List_select_AJAX_MAIN">

                <?php

                if ( ! get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

                    ?>

                    <select class="YITH_WC_amazon_s3_storage_input_text Yith_WC_as3s_Buckets_List_select" name="YITH_WC_amazon_s3_storage_connection_bucket_selected_select">

                                <option value='0'><?php _e( 'No Buckets Found!!', 'yith-amazon-s3-storage' ) ?></option>

	                </select>

                    <?php

                } else {

                    //$Path_ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

                    ?>

                    <!-- <span class="YITH_WC_amazon_s3_storage_input_text" id="Yith_WC_as3s_Buckets_List_select_AJAX_ID">

                            <span style="vertical-align: middle"> <strong style="vertical-align: middle;">SEARCHING FOR BUCKETS</strong> <img style="vertical-align: middle; margin-left: 10px;" class='Ajax_Loader' src='<?php echo $Path_ajax_loader; ?>' alt='cerrar'> </span>

	                </span> -->

                    <?php

                        $Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

                        $Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

                        require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

                        $aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

                        echo '<select class="YITH_WC_amazon_s3_storage_input_text Yith_WC_as3s_Buckets_List_select" name="YITH_WC_amazon_s3_storage_connection_bucket_selected_select">';

                        echo $aws_s3_client->Show_Buckets();

                        echo '</select>';

                        ?>

                        <script>

                            jQuery(function ($) {

                                $(".YITH_WC_amazon_s3_storage_admin_parent_wrap .Yith_WC_as3s_Buckets_List_select").select2({
                                    placeholder: "Choose a bucket"
                                });

                            });

                        </script>

                    <?php

                }
                ?>

            </span>

            <span class="YITH_WC_amazon_s3_storage_description" style="margin-top: 10px;">

                <?php _e( "Select the S3 bucket name where to upload all the files. If you don't know how to create a bucket, ",
                    'yith-amazon-s3-storage' ); ?>
                <a href="http://docs.aws.amazon.com/AmazonS3/latest/gsg/CreatingABucket.html" target="_blank">
                    <?php _e( 'you can click here', 'yith-amazon-s3-storage' ); ?>
                </a>

            </span>

        </label>

    </p>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Copy file to S3', 'yith-amazon-s3-storage' ); ?></span>

            <span>

                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_copy_file_s3_checkbox" <?php echo $File_S3_checkbox; ?>>

                <?php _e( 'Copy files also to S3', 'yith-amazon-s3-storage' ); ?>

            </span>

            <span class="YITH_WC_amazon_s3_storage_description_checkbox">
                <?php _e( 'The files uploaded to the media library will be added automatically to S3. The files that are already in the media library
                will NOT be uploaded to S3', 'yith-amazon-s3-storage' ); ?>
            </span>

        </label>

    </p>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Replace URL', 'yith-amazon-s3-storage' ); ?></span>

            <span>

                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_replace_url_checkbox" <?php echo $Replace_URL_checkbox; ?>>

                <?php _e( 'Files will be served by S3', 'yith-amazon-s3-storage' ); ?>

            </span>

            <span class="YITH_WC_amazon_s3_storage_description_checkbox">
                <?php _e( "The URLs of the files will be automatically replaced to be downloaded from S3 and not from your WordPress installation",
                    'yith-amazon-s3-storage' ); ?>
            </span>

        </label>

    </p>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Remove from server', 'yith-amazon-s3-storage' ); ?></span>

            <span>

                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_remove_from_server_checkbox" <?php echo $Remove_From_Server_checkbox; ?>>

                <?php _e( 'Remove from the server', 'yith-amazon-s3-storage' ); ?>

            </span>

            <span class="YITH_WC_amazon_s3_storage_description_checkbox">
                <?php _e( "The files uploaded to S3 amazon will be deleted from your server", 'yith-amazon-s3-storage' ); ?>
            </span>

        </label>

    </p>

    <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'yith-amazon-s3-storage' ); ?>">

</form>

</div>