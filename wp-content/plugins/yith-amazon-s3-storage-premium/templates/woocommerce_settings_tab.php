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

$result = YITH_WC_amazon_s3_check_connection_success();

if ( isset( $_POST['YITH_WC_amazon_s3_storage_woocommerce_settings_field'] ) && wp_verify_nonce( $_POST['YITH_WC_amazon_s3_storage_woocommerce_settings_field'], 'YITH_WC_amazon_s3_storage_woocommerce_settings_action' ) ) {

	if ( isset( $_POST['YITH_WC_amazon_s3_storage_time_valid_number'] ) ) {
		update_option( 'YITH_WC_amazon_s3_storage_time_valid_number', $_POST['YITH_WC_amazon_s3_storage_time_valid_number'] );
	}

	if ( isset( $_POST['YITH_WC_amazon_s3_storage_private_public_radio_button'] ) ) {
		update_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button', $_POST['YITH_WC_amazon_s3_storage_private_public_radio_button'] );
	}

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_order_link_checkbox'] ) ) {
        update_option( 'YITH_WC_amazon_s3_storage_order_link_checkbox', 'yes' );
    } else {
        update_option( 'YITH_WC_amazon_s3_storage_order_link_checkbox', 'no' );
    }

    if ( isset( $_POST['YITH_WC_amazon_s3_storage_textarea_email_link'] ) ) {
        update_option( 'YITH_WC_amazon_s3_storage_textarea_email_link', $_POST['YITH_WC_amazon_s3_storage_textarea_email_link'] );
    }

	?>

	<div class="updated settings-error notice is-dismissible">

		<p><strong><?php _e( 'Settings saved.', 'yith-amazon-s3-storage' ); ?></strong></p>

	</div>

	<?php

}

$Time_Valid = ( get_option( 'YITH_WC_amazon_s3_storage_time_valid_number' ) ? get_option( 'YITH_WC_amazon_s3_storage_time_valid_number' ) : '5' );

$radio = ( get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) ? get_option( 'YITH_WC_amazon_s3_storage_private_public_radio_button' ) : 'private' );

$default_text_email_link = _x( 'The above link is temporary, so in case it is not working you can click here to go to your order under your account and download the file', 'S3 File Manager', 'yith-amazon-s3-storage' );

$order_link_checkbox = ( get_option( 'YITH_WC_amazon_s3_storage_order_link_checkbox' ) != 'no' ? 'checked="checked"' : '' );

$var = get_option( 'YITH_WC_amazon_s3_storage_order_link_checkbox' );

$text_email_link = ( get_option( 'YITH_WC_amazon_s3_storage_textarea_email_link' ) ? get_option( 'YITH_WC_amazon_s3_storage_textarea_email_link' ) : '' );

if ( strlen( $text_email_link ) == 0 ){
    update_option( 'YITH_WC_amazon_s3_storage_textarea_email_link', $default_text_email_link );
    $text_email_link = $default_text_email_link;
}

$Path_ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

?>

<div id="yith_wc_as3s_main_div">

<form action="" method="post">


	<?php wp_nonce_field( 'YITH_WC_amazon_s3_storage_woocommerce_settings_action', 'YITH_WC_amazon_s3_storage_woocommerce_settings_field' ); ?>

	<p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

		<span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Permissions', 'yith-amazon-s3-storage' ); ?></span>

		<label>

			<input class="YITH_WC_amazon_s3_storage_input_text" type="radio" name="YITH_WC_amazon_s3_storage_private_public_radio_button" checked="checked" value="private" <?php echo( ( $radio == "private" ) ? 'checked="checked"' : '' ); ?>/>

            <span class="Yith_wc_amazon_s3_Storage_margin_right">
                <?php _e( 'Private ', 'yith-amazon-s3-storage' ); ?>
            </span>

		</label>

		<label>
			<input class="YITH_WC_amazon_s3_storage_input_text" type="radio" name="YITH_WC_amazon_s3_storage_private_public_radio_button" value="public" <?php echo( ( $radio == "public" ) ? 'checked="checked"' : '' ); ?>/>

            <span class="Yith_wc_amazon_s3_Storage_margin_right">
                <?php _e( 'Public ', 'yith-amazon-s3-storage' ); ?>
            </span>
		</label>

		<span class="YITH_WC_amazon_s3_storage_description"><?php _e( 'By setting the files as public, anyone who knows the S3 URL will
		have complete access to it', 'yith-amazon-s3-storage' ); ?></span>

	</p>

	<p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

		<label>

			<span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'URL validity period', 'yith-amazon-s3-storage' ); ?></span>

			<input class="YITH_WC_amazon_s3_storage_input_number" type="number" name="YITH_WC_amazon_s3_storage_time_valid_number" value="<?php echo $Time_Valid; ?>">

			<span class="YITH_WC_amazon_s3_storage_description"><?php _e( 'Set the time (minutes) during which the URL is valid for download',
                    'yith-amazon-s3-storage' ); ?></span>

		</label>

	</p>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Send order link', 'yith-amazon-s3-storage' ); ?></span>

            <span>

                <input class="YITH_WC_amazon_s3_storage_input_text" type="checkbox" name="YITH_WC_amazon_s3_storage_order_link_checkbox" <?php echo $order_link_checkbox; ?>>

                <?php _e( "link of the order under the user's account", 'yith-amazon-s3-storage' ); ?>

            </span>

            <span class="YITH_WC_amazon_s3_storage_description_checkbox">
                <?php _e( 'This link shows up in the email sent after purchasing and leads to the order under the account of the user. If the client does not have an account the link will not be sent', 'yith-amazon-s3-storage' ); ?>
            </span>

        </label>

    </p>

    <p class="YITH_WC_amazon_s3_storage_admin_parent_wrap">

        <label>

            <span class="YITH_WC_amazon_s3_storage_title"><?php _e( 'Link of the email sent', 'yith-amazon-s3-storage' ); ?></span>

            <textarea class="YITH_WC_amazon_s3_storage_textarea" type="number" name="YITH_WC_amazon_s3_storage_textarea_email_link"><?php echo $text_email_link; ?></textarea>

            <span class="YITH_WC_amazon_s3_storage_description"><?php _e( 'Edit the text of the link.',
                    'yith-amazon-s3-storage' ); ?></span>

        </label>

    </p>

	<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'yith-amazon-s3-storage' ); ?>">

</form>

</div>