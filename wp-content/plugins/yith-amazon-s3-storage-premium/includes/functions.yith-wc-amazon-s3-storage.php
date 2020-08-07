<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

function yith_wc_as3s_array_media_actions_function( $post_id ) {

	$array_aux = explode( '/', get_post_meta( $post_id, '_wp_attached_file', true ) );
	$main_file = array_pop( $array_aux );

	// Creating an array with all the files with different sizes.
	// The first element of the array is the folder content.
	// Second element is the main file with no personal size
	$array_files[] = implode( "/", $array_aux );
	$array_files[] = $main_file;

	// Getting the rest of the sizes of the file to add to the array
	$array_metadata = wp_get_attachment_metadata( $post_id );

	if ( ! empty( $array_metadata ) && isset( $array_metadata['sizes'] ) )
	{
		$array_metadata = $array_metadata['sizes'];
		foreach ( $array_metadata as $metadata ) {
			$array_files[] = $metadata['file'];
		}
	}

	$upload_dir = wp_upload_dir();

	$basedir_absolute = $upload_dir['basedir'];

	$Bucket_Selected = ( get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) : '' );

	$Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

	if ( count( $Array_Bucket_Selected ) == 2 ){
		$Bucket                = $Array_Bucket_Selected[0];
		$Region                = $Array_Bucket_Selected[1];
	}
	else{
		$Bucket                = 'none';
		$Region                = 'none';
	}

	if ( $Bucket == 'none' )
		die();

	$Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_access_key_text' ) : null );

	$Secret_Access_Key = ( get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_secret_access_key_text' ) : null );

	require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage-aws-s3-client.php' );

	$aws_s3_client = new YITH_WC_Amazon_S3_Storage_Aws_S3_Client( $Access_Key, $Secret_Access_Key );

	return array( $aws_s3_client, $Bucket, $Region, $array_files, $basedir_absolute );

}

/* ================================================================ */
/* ======================== COPY TO S3 ============================ */
/* ================================================================ */
function yith_wc_as3s_Copy_to_S3_function( $post_id, $private_or_public = 'public' ) {

	list( $aws_s3_client, $Bucket, $Region, $array_files, $basedir_absolute ) = yith_wc_as3s_array_media_actions_function( $post_id );

	if ( $result = $aws_s3_client->Upload_Media_File( $Bucket, $Region, $array_files, $basedir_absolute, $private_or_public ) ) {

		$url = $result['ObjectURL'];

		update_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', '1' );
		update_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', $url );


	}
}

/* ================================================================ */
/* ======================= REMOVE FROM S3 ========================= */
/* ================================================================ */
function yith_wc_as3s_Remove_from_S3_function( $post_id ) {

	list( $aws_s3_client, $Bucket, $Region, $array_files ) = yith_wc_as3s_array_media_actions_function( $post_id );

	if ( $result = $aws_s3_client->deleteObject_yith( $Bucket, $Region, $array_files ) ) {

		update_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', '_wp_yith_wc_as3s_s3_path_not_in_used' );

	}

}

/* ================================================================ */
/* ================== COPY TO SERVER FROM S3 ====================== */
/* ================================================================ */
function yith_wc_as3s_Copy_to_server_from_S3_function( $post_id ) {

	list( $aws_s3_client, $Bucket, $Region, $array_files, $basedir_absolute ) = yith_wc_as3s_array_media_actions_function( $post_id );

	if ( $result = $aws_s3_client->download_file( $Bucket, $Region, $array_files, $basedir_absolute ) ) {

		update_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', '1' );

	}

}

/* ================================================================ */
/* ===================== REMOVE FROM SEVER ======================== */
/* ================================================================ */
function yith_wc_as3s_Remove_from_server_function( $post_id ) {

	$File_Name = get_post_meta( $post_id, '_wp_attached_file', true );

	$upload_dir = wp_upload_dir();

	$basedir = $upload_dir['basedir'];

	$Path_To_File = $basedir . "/" . $File_Name;

	unlink( $Path_To_File );

	$array_aux = explode( '/', $File_Name );
	$File_Name = array_pop( $array_aux );

	$base_folder = implode( "/", $array_aux );

	// Getting the rest of the sizes of the file to add to the array
	$array_metadata = wp_get_attachment_metadata( $post_id );

	if ( ! empty( $array_metadata ) ){

		$array_metadata = $array_metadata['sizes'];
		foreach ( $array_metadata as $metadata ) {

			if ( $base_folder != '' ) {
                if ( file_exists($basedir . "/" . $base_folder . "/" . $metadata['file']) ) {
                    unlink($basedir . "/" . $base_folder . "/" . $metadata['file']);
                }
			} else {
			    if ( file_exists($basedir . "/" . $metadata['file']) ) {
                    unlink($basedir . "/" . $metadata['file']);
                }
			}

		}
	}

	update_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', '_wp_yith_wc_as3s_wordpress_path_not_in_used' );

}

/* ================================================================ */
/* ====================== SHOW AJAX LOADER ========================= */
/* ================================================================ */
function yith_wc_as3s_show_ajax_loader_function( $doaction ) {

	?>

	<div class='YITH_WC_amazon_s3_storage_sub_button_AJAX'>

		<h1>
			<?php

			switch ( $doaction ) {

				case 'Copy_to_S3':

					echo _x( 'Copying to S3', 'yith-amazon-s3-storage' );

					break;

				case 'Remove_from_S3':

					echo _x( 'Removing from S3', 'Button action mode grid', 'yith-amazon-s3-storage' );

					break;

				case 'Copy_to_server_from_S3':

					echo _x( 'Copying to server from S3', 'Button action mode grid', 'yith-amazon-s3-storage' );

					break;

				case 'Remove_from_server':

					echo _x( 'Removing from server', 'Button action mode grid', 'yith-amazon-s3-storage' );

					break;
			}

			?>
		</h1>

		<?php

		$ajax_loader = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/ajax-loader-bar.gif';

		?>
		<img class='YITH_WC_amazon_s3_storage_button_action_ajax_loader' src='<?php echo $ajax_loader; ?>' />

	</div>

	<?php

}

/* ================================================================ */
/* ====================== DO BULK ACTIONS ========================= */
/* ================================================================ */
function yith_wc_as3s_do_bulk_actions_extra_options_function( $doaction, $post_ids ) {

	switch ( $doaction ) {

		case 'Copy_to_S3':

			foreach ( $post_ids as $post_id ) {
				$s3_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );
				if ( $s3_path == '_wp_yith_wc_as3s_s3_path_not_in_used' || $s3_path == null ) {
					yith_wc_as3s_Copy_to_S3_function( $post_id );
				}
			}

			break;

		case 'Remove_from_S3':

			foreach ( $post_ids as $post_id ) {

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
				$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					yith_wc_as3s_Remove_from_S3_function( $post_id );
				}


			}

			break;

		case 'Copy_to_server_from_S3':

			foreach ( $post_ids as $post_id ) {

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
				$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );
				if ( ( $wordpress_path == '_wp_yith_wc_as3s_wordpress_path_not_in_used' || $wordpress_path == null ) && ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) ) {
					yith_wc_as3s_Copy_to_server_from_S3_function( $post_id );
				}

			}

			break;

		case 'Remove_from_server':

			foreach ( $post_ids as $post_id ) {

				$wordpress_path = get_post_meta( $post_id, '_wp_yith_wc_as3s_wordpress_path', true );
				$s3_path        = get_post_meta( $post_id, '_wp_yith_wc_as3s_s3_path', true );

				if ( ( $s3_path != '_wp_yith_wc_as3s_s3_path_not_in_used' && $s3_path != null ) && ( $wordpress_path != '_wp_yith_wc_as3s_wordpress_path_not_in_used' && $wordpress_path != null ) ) {
					yith_wc_as3s_Remove_from_server_function( $post_id );
				}

			}

			break;
	}

}

/* ================================================================ */
/* ========== Checking connection success to amazon s3 ============ */
/* ================================================================ */
function YITH_WC_amazon_s3_check_connection_success() {

	if ( ! get_option( 'YITH_WC_amazon_s3_storage_connection_success' ) ) {

		echo "<div>";

		echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

		$Path_warning_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/Warning.png';

		echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_warning_image'/>";
		echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
		_e( 'You have to configure your Access Key and Secret Access Key correctly in the "Connect to your s3 amazon account" tab',
            'yith-amazon-s3-storage' );
		echo "</span>";

		echo "</p>";

		echo "<br>";

		echo "</div>";

		return 0;

	}
	else
	{

		$Bucket_Selected = ( get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) : '' );

		$Array_Bucket_Selected = explode( "_yith_wc_as3s_separator_", $Bucket_Selected );

		if ( count( $Array_Bucket_Selected ) != 2 ){

			echo "<div>";

			echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

			$Path_warning_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/Warning.png';

			echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_warning_image'/>";
			echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
			_e( 'You have to choose a bucket in the "Setting" tab in the Amazon S3 admin panel', 'yith-amazon-s3-storage' );
			echo "</span>";

			echo "</p>";

			echo "<br>";

			echo "</div>";

			return 0;

		}
		else
			return 1;
	}

}