<?php
/**
 * this class is handle all the master_products 
 */
class popupMasterProduct{
	var $tablename = 'master_products';
	function __construct(){
	
	}

	public function insert_data( $args ){
		$insert_arr = array(
			'product_name' => $args['FULL_TITLE'],
			'description'   => $args['PreviewHTML'],
			'excerpt' => $args['MAIN_DESC'],
			'diamond_number' => $args['DIAMD_NO'],
			'stock_number' => $args['STOCK_NO'],
			'series_code' => $args['SERIES_CODE'],
			'issue_number' => $args['ISSUE_NO'],
			'issue_sequence_number' => $args['ISSUE_SEQ_NO'],
			'price' => $args['PRICE'],
			'publisher' => $args['PUBLISHER'],
			'upc_number' => $args['UPC_NO'],
			'cards_per_pack' => $args['CARDS_PER_PACK'],
			'pack_per_box' => $args['PACK_PER_BOX'],
			'box_per_case' => $args['BOX_PER_CASE'],
			'discount_code' => $args['DISCOUNT_CODE'],
			'increment' => $args['INCREMENT'],
			'print_date' => $args['PRNT_DATE'],
			'foc_vendor' => $args['FOC_VENDOR'],
			'available' => $args['SHIP_DATE'],
			'srp' => $args['SRP'],
			'category' => $args['CATEGORY'],
			'mature' => $args['MATURE'],
			'adult' => $args['ADULT'],
			'oa' => $args['OA'],
			'caut1' => $args['CAUT1'],
			'caut2' => $args['CAUT2'],
			'caut3' => $args['CAUT3'],
			'resol' => $args['RESOL'],
			'note_price' => $args['NOTE_PRICE'],
			'order_form_notes' => $args['ORDER_FORM_NOTES'],
			'page' => $args['PAGE'],
			'foc_date' => $args['FOC_DATE'],
			'preview_html' => $args['PreviewHTML'],
			'image_path' => $args['ImagePath'],
			'genre' => $args['GENRE'],
			'brand_code' => $args['BRAND_CODE'],
			'writer' => $args['WRITER'],
			'artist' => $args['ARTIST'],
			'covert_artist' => $args['COVER_ARTIST'],
			'variant_desc' => $args['VARIANT_DESC'],
			'short_isbn_no' => $args['SHORT_ISBN_NO'],
			'ean_no' => $args['EAN_NO'],
			'colorist' => $args['COLORIST'],
			'alliance_sku' => $args['ALLIANCE_SKU'],
			'volume_tag' => $args['VOLUME_TAG'],
			'parent_item_no_alt' => $args['PARENT_ITEM_NO_ALT'],
			'offered_day' => $args['OFFERED_DATE'],
			'max_issue' => $args['MAX_ISSUE'],
			'cost' => $args['PRICE'],
			'stockid' => $args['STOCK_NO'],
		);
		global $wpdb;
		
		//checking the image in aws
		$aws_arr = $this->popup_check_image_on_aws( $insert_arr['diamond_number'] );
		if( !empty( $aws_arr ) && is_array( $aws_arr ) ){
			$insert_arr = array_merge( $insert_arr, $aws_arr );
		}

		//remove the word between the breckets ( )
		$insert_arr['product_name'] = PopupComicsUploader::popup_clean_title( $insert_arr['product_name'] );
		if( $results = $this->master_product_check( $insert_arr['diamond_number'] ) ){
			$prod_id = $results->id;
			$where = array( 'id' =>  $prod_id );
			$updated = $wpdb->update( $this->tablename, $insert_arr, $where );
			if ( false === $updated ) {
			    // There was an error.
			   	$return['failed'] = 1;
			   	$message = $insert_arr['product_name'].' - failed to update '.$updated;
			} else {
			    // No error. You can check updated to see how many rows were changed.
			    $return['update'] = 1;
				$message = $insert_arr['product_name'].' - updated ';
			}
		}else{
			$inserted = $wpdb->insert( $this->tablename, $insert_arr );	
			if ( false === $inserted ) {
			    // There was an error.
			   	$return['failed'] = 1;
			   	$message = $insert_arr['product_name'].' - failed to insert ';
			} else {
			    // No error. You can check updated to see how many rows were changed.
			    $return['insert'] = 1;
				$message = $insert_arr['product_name'].' - insert ';
			}
		}
		$return['message'] = $message;
		return $return;
	}
	public function master_product_check( $diamond_number ){
		global $wpdb;
		$query = "SELECT * FROM $this->tablename WHERE diamond_number = '$diamond_number' ";
		$results = $wpdb->get_row($query);
		if( !empty( $results ) ){
			return $results;
		}
		return false;
	}
	public function popup_check_image_on_aws( $diamond_number ){
		#check image on aws
        $va_aws = new va_aws('AKIAWAJYLDONJ4G3V3NJ','/eyNvfA2161TSB3+7q4JBzYvnFtTpnemwvPgNJYv');
        $s3path = 'zip-image/';
        $file_name = $diamond_number.'.jpg';
        $_key = $s3path.$file_name;
        $bucket_name  = 'darksidecomics';
        $aws_image = $va_aws->aws_check_image( $bucket_name, $_key );
        if( $aws_image ){
            $return['aws_image'] = 'yes';
            $return['aws_key'] = $_key;
            $return['aws_bucketname'] = $bucket_name;
        }else{
        	$return['aws_image'] = 'no';
        }
        return $return;
	}
}

?>