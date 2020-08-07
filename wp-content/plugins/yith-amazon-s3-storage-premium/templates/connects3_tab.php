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

if ( isset( $_POST['YITH_WC_amazon_s3_storage_settings_account_connection_field'] ) && wp_verify_nonce( $_POST['YITH_WC_amazon_s3_storage_settings_account_connection_field'], 'YITH_WC_amazon_s3_storage_settings_account_connection_action' ) ) {

	if ( isset( $_POST['YITH_WC_amazon_s3_storage_connection_access_key_text'] ) ) {
		update_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text', $_POST['YITH_WC_amazon_s3_storage_connection_access_key_text'] );
	}

	if ( isset( $_POST['YITH_WC_amazon_s3_storage_connection_secret_access_key_text'] ) ) {
		update_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text', $_POST['YITH_WC_amazon_s3_storage_connection_secret_access_key_text'] );
	}

    $Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

    $Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

    require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

    $aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

    $buckets = $aws_s3_client->Checking_Credentials();

	?>

	<div class="updated settings-error notice is-dismissible">

		<p><strong><?php _e( 'Settings saved.', 'yith-amazon-s3-storage' ); ?></strong></p>

	</div>

	<?php

}

$access_key_value = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

$secret_access_key_value = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

$Path_ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

?>

<div id="yith_wc_as3s_main_div">

	<form action="" method="post">


		<?php wp_nonce_field( 'YITH_WC_amazon_s3_storage_settings_account_connection_action', 'YITH_WC_amazon_s3_storage_settings_account_connection_field' ); ?>

		<p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

			<label>

				<span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Access key', 'yith-amazon-s3-storage' ); ?></span>

				<input class="YITH_WC_amazon_s3_storage_input_text" type="text" name="YITH_WC_amazon_s3_storage_connection_access_key_text" value="<?php echo $access_key_value; ?>">

				<span class="YITH_WC_amazon_s3_storage_description"><?php _e( 'Set the access key', 'yith-amazon-s3-storage' ); ?></span>

			</label>

		</p>

		<p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

			<label>

				<span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Secret access key', 'yith-amazon-s3-storage' ); ?></span>

				<input class="YITH_WC_amazon_s3_storage_input_text" type="text" name="YITH_WC_amazon_s3_storage_connection_secret_access_key_text" value="<?php echo $secret_access_key_value; ?>">

				<span class="YITH_WC_amazon_s3_storage_description"><?php _e( 'Set the secret access key', 'yith-amazon-s3-storage' ); ?></span>

            <span class="YITH_WC_amazon_s3_storage_description" style="margin-top: 10px;">

                <?php _e( "If you don't know where to search for your S3 credentials, ", 'yith-amazon-s3-storage' ); ?>
	            <a href="https://aws.amazon.com/blogs/security/wheres-my-secret-access-key/" target="_blank">
                    <?php _e( 'you can find them here', 'yith-amazon-s3-storage' ); ?>
                </a>

            </span>

			</label>

		</p>

		<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'yith-amazon-s3-storage' ); ?>">

	</form>

<?php $image_src = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . 'images/ajax-loader.gif' ; ?>

<div id='YITH_WC_amazon_s3_storage_Checking_Credentials_ID' style="display: none;">

    <div class='YITH_WC_amazon_s3_storage_AJAX_checking_credentials'> <p> <strong>CHECKING CREDENTIALS</strong> </p> <p> <img class='Ajax_Loader' src='<?php echo $image_src; ?>' alt='cerrar'> </p> </div>

</div>

<?php

echo "<div id='YITH_WC_amazon_s3_storage_connection_status'>";

if ( isset( $_POST['YITH_WC_amazon_s3_storage_settings_account_connection_field'] ) && wp_verify_nonce( $_POST['YITH_WC_amazon_s3_storage_settings_account_connection_field'], 'YITH_WC_amazon_s3_storage_settings_account_connection_action' ) ) {

    if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

        echo "<div>";

        echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

        $Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-ok.png';

        echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
        echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
        _e( 'Connection to Amazon S3 Storage was successful', 'yith-amazon-s3-storage' );
        echo "</span>";

        echo "</p>";

        echo "</div>";

    } else {

        echo "<div>";

        echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

        $Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-error-logs.png';

        echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
        echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
        _e( 'An error occurred while accessing, the credentials (access key or secret key) are NOT correct',
            'yith-amazon-s3-storage' );
        echo "</span>";

        echo "</p>";

        echo "</div>";

    }

} else if ( $access_key_value != null && $secret_access_key_value != null ) {

	if ( get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

		echo "<div>";

		echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

		$Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-ok.png';

		echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
		echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
		_e( 'Connection to Amazon S3 Storage was successful', 'yith-amazon-s3-storage' );
		echo "</span>";

		echo "</p>";

		echo "</div>";

	} else {

		echo "<div>";

		echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

		$Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-error-logs.png';

		echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
		echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
		_e( 'An error occurred while accessing, the credentials (access key or secret key) are NOT correct',
            'yith-amazon-s3-storage' );
		echo "</span>";

		echo "</p>";

		echo "</div>";

	}

}

echo "</div>";

echo "</div>";