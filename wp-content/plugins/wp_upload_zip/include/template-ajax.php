<?php

add_action( 'wp_ajax_aws_upload_function', 'va_aws_upload_function' );
add_action( 'wp_ajax_nopriv_aws_upload_function', 'va_aws_upload_function' );
function va_aws_upload_function(){

	$wp_upload = new WP_Upload_Handler();

	$location 		= $_POST['_url'];
	$_plugin_dir 	= $_POST['_plugin_dir'];
	$_title 		= $_POST['_title'];


	$zip_path 	= $_plugin_dir.'/php/files/'.$_title;
	$zip_path   = '/'.$wp_upload->get_absolute_path( $zip_path );

	$extract_path = $_plugin_dir.'/php/files/extract';
	$extract_path = '/'.$wp_upload->get_absolute_path( $extract_path );
	if ( ! is_dir( $extract_path ) ) {
		 wp_mkdir_p( $extract_path, 0777 );
	}

	$xml_path 	= $_plugin_dir.'/php/files/';
	$xml_path   = '/'.$wp_upload->get_absolute_path( $xml_path );

	$json_file 	= $_plugin_dir.'/php/files/';
	$json_file   = $wp_upload->get_absolute_path( $json_file );

	$zip = zip_open( $zip_path );

	if ($zip)
	{
		$zip_obj = new ZipArchive;
        if($zip_obj->open($zip_path))
        {
            $zip_obj->extractTo($extract_path);
            $zip_obj->close();
        }

        $files = scandir($extract_path);

        foreach($files as $file) {


        	$string = $file;
        	$strings = explode('.', $string);
        	$file_ext = end($strings);

        	$allowed_ext = array('xml');
        	if(in_array($file_ext, $allowed_ext))
        	{
        		$xml_path = $xml_path;
        		copy($xml_path.'/extract/'.$file, $xml_path .'/'. $file);
                unlink($xml_path.'/extract/'.$file);
        	}
        }

		$zip_entry = zip_read($zip);
		if(is_resource($zip))
		{
			$img_arr = array();
			while($zip_entry = zip_read($zip))
			{
				$content_path = $_plugin_dir.'/php/files/extract/'. zip_entry_name( $zip_entry );

				$file = '/'.$wp_upload->get_absolute_path( $content_path );
				$strings = explode('.', $file);
                $file_ext = end($strings);

                $dir = explode('\\', $file);
                $dir_name = end($dir);

                $allowed_ext = array('jpg');

                $allowed_ext_xml = array('xml');
                if(in_array($file_ext, $allowed_ext_xml))
                {
                	$xml_path = $xml_path;
                	$file_name     = wp_basename( $file );
                	copy($file, $xml_path .'/'. $file_name);
                	unlink($file);
                }

                if(in_array($file_ext, $allowed_ext))
                {
                	$file_name     = wp_basename( $file );
                	$content_type  = wp_check_filetype($file);

		     		$type 			= $content_type['type'];
		     		$ext 			= '.'.$content_type['ext'];
		     		$bucket_name 	= 'darksidecomics';
		     		$org_file_path 	= $file;

		     		$s3path = 'zip-image/';
		     		$orignal_key = $s3path.$file_name;
		     		array_push($img_arr, $org_file_path);

                }


			}

			$dir = $wp_upload->plugin_dir();
        	$plugin_url = $wp_upload->plugin_url();

			$temp_name = "image";
			$json_file_dir = $extract_path."/".$temp_name.'.json';
			$json_file_move = $xml_path."/".$temp_name.'.json';
			$json_file_ = $plugin_url."php/files/".$temp_name.'.json';
			$json_cat = json_encode($img_arr);
			chmod($json_file_dir,0777);
        	file_put_contents($json_file_dir, $json_cat);


        	copy($json_file_dir, $json_file_move);
            unlink($json_file_dir);

			zip_close($zip);
		}
		echo $json_file_;
	}
	//$dir = deleteDirectory( $extract_path );

	wp_die();
}


function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

add_action( 'wp_ajax_popupcomicshops_product_import', 'va_popupcomicshops_product_import' );
add_action( 'wp_ajax_nopriv_popupcomicshops_product_import', 'va_popupcomicshops_product_import' );
function va_popupcomicshops_product_import(){

	$obj_class = new WP_Upload_Handler();

	$file_url = $_POST['file_url'];
	$startpos = $_POST['startpos'];
	$iod_date = $_POST['iod_date'];

	//$startpos = 0;
	// $xmldata  = simplexml_load_file($file_url);
	$xmldata = new SimpleXMLElement(file_get_contents($file_url));
	$total_data = count( $xmldata );

	$json     = json_encode($xmldata);
    $configData = json_decode($json, true);

    $d = date("j-M-Y H:i:s");
    $final_data = $configData['EXPORT_FILE'][$startpos];

    // vivid( $final_data ); exit;
    $end_pos = $startpos+1;
    $total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);

    if($total_data < $startpos){
    	$message = '['.$d.'] - Done';
    	wp_send_json_success(
	    	array(
	    		'pos' => 'done',
	    		'file_path' => $file_url,
	    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
	    		'message' => $message,
	    		'iod_date' => $iod_date,
	    		//'failed'    => $failed_count,
	    	)
	    );
    }else{

    	//$message = '['.$d.'] - '.$sku . PHP_EOL;
    	$message = '['.$d.'] - '.$obj_class->va_import_xml_product( $final_data, $iod_date );

    	wp_send_json_success(
	    	array(
	    		'pos' => $end_pos,
	    		'file_path' => $file_url,
	    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
	    		'message' => $message,
	    		'iod_date' => $iod_date,
	    		//'failed'    => $failed_count,
	    	)
	    );
    }


	wp_die();
}


add_action( 'wp_ajax_aws_image_import', 'va_aws_image_import' );
add_action( 'wp_ajax_nopriv_aws_image_import', 'va_aws_image_import' );
function va_aws_image_import(){

	$obj_class = new WP_Upload_Handler();

	$_plugin_dir 	= $_POST['_plugin_dir'];
	$_zip_file_url 	= $_POST['_zip_file_url'];

	$extract_path = $_plugin_dir.'/php/files/extract';
	$extract_path = '/'.$obj_class->get_absolute_path( $extract_path );

	$xml_path 	= $_plugin_dir.'/php/files/';
	$xml_path   = '/'.$obj_class->get_absolute_path( $xml_path );

	$file_url = $_POST['file_url'];
	//$file_url = $obj_class->get_absolute_path( $_POST['file_url'] );

	$startpos = $_POST['startpos'];
	$_parent = $_POST['_parent'];
	$json_data = vvd_json_file_get_contents($file_url);

	if(!empty($json_data)){

		$json_data = json_decode($json_data);

		$total_data = count( $json_data );
		;
		//$total_data = 6;

		//vivid($total_data);
		$d = date("j-M-Y H:i:s");
		$final_data = $json_data[$startpos];


		//$final_data = $obj_class->get_absolute_path( $final_data );



		$end_pos = $startpos+1;
		$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);

		if($total_data < $startpos){

	    	$message = '['.$d.'] - Done ';
	    	unlink($xml_path.'/image.json');
	    	//unlink();
	    	$dir = deleteDirectory( $extract_path );
		    $modify_arr = get_option('vvd_last_edit_file');

		    if(empty($modify_arr)){
		    	$modify_arr = array();
		    	$modify_arr[$_zip_file_url] = $d;

		    }else{

		    	$modify_arr[$_zip_file_url] = $d;

		    }
		    update_option('vvd_last_edit_file',$modify_arr);

		    // Add last_edit option


	    	wp_send_json_success(
		    	array(
		    		'pos' => 'done',
		    		'file_path' => $file_url,
		    		'_parent' => $_parent,
		    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
		    		'message' => $message,
		    		'_zip_file_url' => $_zip_file_url,

		    	)
		    );

	    }else{

	    	//$message = '['.$d.'] - '.$final_data . PHP_EOL;
	    	$message = '['.$d.'] - '.$obj_class->vvd_upload_image_aws($final_data);

	    	wp_send_json_success(
		    	array(
		    		'pos' => $end_pos,
		    		'file_path' => $file_url,
		    		'_parent' => $_parent,
		    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
		    		'message' => $message,
		    		'_zip_file_url' => $_zip_file_url,
		    	)
		    );
	    }
	}



	wp_die();
}

function vvd_json_file_get_contents($url){

	$curl = curl_init();
	//vivid( $url );
	//exit;
	//$endpoint = urlencode( $url );
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://popupcomicshop.wpengine.com/wp-content/plugins/wp_upload_zip/php/files/image.json",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"Accept: */*",
			"Accept-Encoding: gzip, deflate",
			"Cache-Control: no-cache",
			"Connection: keep-alive",
			"Host: popupcomicshop.wpengine.com",
			"Postman-Token: 70e9d631-1c64-472d-8d7f-a26f53fed9bd,41158ee9-0d72-414c-a512-79ebb4736aba",
			"User-Agent: PostmanRuntime/7.15.2",
			"cache-control: no-cache"
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$result = "cURL Error #:" . $err;
	} else {
		$result = $response;
	}

	return $result;
}

add_action( 'wp_ajax_product_image_scrapper', 'va_product_image_scrapper' );
add_action( 'wp_ajax_nopriv_product_image_scrapper', 'va_product_image_scrapper' );
function va_product_image_scrapper(){

	$MetaQuery[] = array(
	'key'     => 'aws_image',
	'value'   => 'no',
	'compare' => '=',
	);

	//$status = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');
	$status = array('publish', 'draft');
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => $status,
		'meta_key'     => 'aws_image',
		'meta_value'     => 'no',

	);

	$query = new WP_Query( $args );
	$all_post = $query->posts;

	$location 		= $_POST['_url'];
	$_plugin_dir 	= $_POST['_plugin_dir'];
	$_title 		= $_POST['_title'];

	$wp_upload = new WP_Upload_Handler();

	$json_path 	= $_plugin_dir.'/php/files/';
	$json_path   = '/'.$wp_upload->get_absolute_path( $json_path );

	$temp_name = "image_scrapper";
	$plugin_url = $wp_upload->plugin_url();

	if( !empty($all_post) ){
		$json_file_move = $json_path."/".$temp_name.'.json';
		$json_file_ = $plugin_url."php/files/".$temp_name.'.json';
		$json_cat = json_encode($all_post);
		file_put_contents($json_file_move, $json_cat);

		$json['success'] = true;
		$json['json_file'] =  $json_file_;

		echo json_encode($json);
	}else{
		$json['error'] = true;
		$json['message'] =  __("Product not found !", "popupcomics");

		echo json_encode($json);
	}


	wp_die();
}

add_action( 'wp_ajax_product_image_scrapper_aws_meta', 'va_product_image_scrapper_aws_meta' );
add_action( 'wp_ajax_nopriv_product_image_scrapper_aws_meta', 'va_product_image_scrapper_aws_meta' );
function va_product_image_scrapper_aws_meta(){
	$obj_class = new WP_Upload_Handler();

	$_plugin_dir 	= $_POST['_plugin_dir'];
	$startpos = $_POST['startpos'];
	$_parent = $_POST['_parent'];
	$file_url = $_POST['file_url'];

	$xml_path 	= $_plugin_dir.'/php/files/';
	$xml_path   = '/'.$obj_class->get_absolute_path( $xml_path );

	$json_data = vvd_json_file_get_image_scrapper($file_url);

	if(!empty($json_data)){
		$json_data = json_decode($json_data);

		$total_data = count( $json_data );

		$d = date("j-M-Y H:i:s");
		$final_data = $json_data[$startpos];

		$end_pos = $startpos+1;
		$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


		if($total_data < $startpos){
			$message = '['.$d.'] - Done ';

			unlink($xml_path.'/image_scrapper.json');
			wp_send_json_success(
		    	array(
		    		'pos' => 'done',
		    		'file_path' => $file_url,
		    		'_parent' => $_parent,
		    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
		    		'message' => $message,

		    	)
		    );
		}else{

			$message = '['.$d.'] - '.$obj_class->va_product_image_scrapper( $final_data );

	    	wp_send_json_success(
		    	array(
		    		'pos' => $end_pos,
		    		'file_path' => $file_url,
		    		'_parent' => $_parent,
		    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
		    		'message' => $message,
		    	)
		    );
		}
	}

	wp_die();
}

function vvd_json_file_get_image_scrapper($url){

	$curl = curl_init();
	//vivid( $url );
	//exit;
	//$endpoint = urlencode( $url );
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://popupcomicshop.wpengine.com/wp-content/plugins/wp_upload_zip/php/files/image_scrapper.json",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"Accept: */*",
			"Accept-Encoding: gzip, deflate",
			"Cache-Control: no-cache",
			"Connection: keep-alive",
			"Host: popupcomicshop.wpengine.com",
			"Postman-Token: 70e9d631-1c64-472d-8d7f-a26f53fed9bd,41158ee9-0d72-414c-a512-79ebb4736aba",
			"User-Agent: PostmanRuntime/7.15.2",
			"cache-control: no-cache"
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$result = "cURL Error #:" . $err;
	} else {
		$result = $response;
	}

	return $result;
}

add_action( 'wp_ajax_change_product_title_import', 'va_change_product_title_import' );
add_action( 'wp_ajax_nopriv_change_product_title_import', 'va_change_product_title_import' );
function va_change_product_title_import(){
	$obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	//$total_data = $total_data - 1;

	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		$header = fgetcsv( $handle, 0);

		$header = Array(
            '0' => 'item_code',
            '1' => 'old_title',
            '2' => 'new_title',
        );

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i];

            }
            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';
				unlink($file_url);
				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,

			    	)
			    );
            }else if( $row == $end_pos){

            	if(isset( $parse_data['item_code'] ) && !empty( $parse_data['item_code'] ) ){

            		$message = '['.$d.'] - '.$obj_class->va_change_title_product( $parse_data );

            		// $message = '['.$d.'] - '.$parse_data['item_code'].' - '.$parse_data['old_title'];
			    	wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}else{
            		$message = '['.$d.'] - NO Data Found!';

            		wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}

            }
			/*for ($c=0; $c < $num; $c++) {
				 echo $data[$c] . "<br />\n";
			}*/
		}
		fclose($handle);
	}
	wp_die();
}

add_action( 'wp_ajax_reproduct_cancel_import', 'va_reproduct_cancel_import' );
add_action( 'wp_ajax_nopriv_reproduct_cancel_import', 'va_reproduct_cancel_import' );
function va_reproduct_cancel_import(){
	$obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	//$total_data = $total_data - 1;

	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		$header = fgetcsv( $handle, 0);

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			/*foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i];

            }*/
            $parse_data = $data;

            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';

				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,

			    	)
			    );
            }else if( $row == $end_pos){

            	if(isset( $parse_data[2] ) && !empty( $parse_data[2] ) ){

            		//$message = '['.$d.'] - '.$obj_class->va_change_title_product( $parse_data );
            		$message = '['.$d.'] - '.$parse_data[2];

            		//$message = '['.$d.'] - '.$parse_data['old_title'];
			    	wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}

            }
			/*for ($c=0; $c < $num; $c++) {
				 echo $data[$c] . "<br />\n";
			}*/
		}
		fclose($handle);
	}
	wp_die();
}

add_action( 'wp_ajax_recreate_series_import', 'va_recreate_series_import' );
add_action( 'wp_ajax_nopriv_recreate_series_import', 'va_recreate_series_import' );
function va_recreate_series_import(){
	$obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];
	$delimiter = ! empty( $_POST['delimiter'] ) ? wc_clean( wp_unslash( $_POST['delimiter'] ) ) : ',';

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	//$total_data = $total_data - 1;
	//vivid( $delimiter );
	//$del = "$delimiter";
	$del = "\t";
	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		$header = fgetcsv( $handle, 0, $del);

		while (($data = fgetcsv($handle, 1000, $del)) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i];

            }
            //vivid($parse_data);
            //$parse_data = $data;
            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';

				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,
			    		'delimiter' => $delimiter,
			    	)
			    );
            }else if( $row == $end_pos){

            	if(isset( $parse_data['item_no'] ) && !empty( $parse_data['item_no'] ) ){

            		//$message = '['.$d.'] - '.$obj_class->va_change_title_product( $parse_data );
            		$message = '['.$d.'] - '.$parse_data['item_no'].' - '.$parse_data['description'];

            		//$message = '['.$d.'] - '.$parse_data['old_title'];
			    	wp_send_json_success(
				    	array(
				    		'pos' 	=> $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' 	=> $message,
			    			'delimiter' => $delimiter,
				    	)
				    );

            	}

            }
			/*for ($c=0; $c < $num; $c++) {
				 echo $data[$c] . "<br />\n";
			}*/
		}
		fclose($handle);
	}
	wp_die();
}

add_action( 'wp_ajax_create_aws_image_scraper_file', 'va_create_aws_image_scraper_file' );
add_action( 'wp_ajax_nopriv_create_aws_image_scraper_file', 'va_create_aws_image_scraper_file' );
function va_create_aws_image_scraper_file(){

    $obj_class = new WP_Upload_Handler();

	$status = array('publish', 'draft');
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => $status,
		'meta_query' => array(
			array(
				'key' => 'aws_image',
				'value' =>'no',
				'compare' => '=',
			)
		),
	);

	$query = new WP_Query( $args );
	$all_post = $query->posts;
	//vivid( count( $all_post ) );
	$json_file = $obj_class->asos_upload_json_file($all_post , 'test');
	//$json_file = "https://popupcomicshop.wpengine.com/wp-content/plugins/wp_upload_zip/temp/test-2019-09-06-05-25-09.json";

	$json['success'] = true;
	//$json['json_url'] =  $json_file;
	echo json_encode($json);

	wp_die();
}

add_action( 'wp_ajax_fun_img_scraper_script_ajax', 'va_fun_img_scraper_script_ajax' );
add_action( 'wp_ajax_nopriv_fun_img_scraper_script_ajax', 'va_fun_img_scraper_script_ajax' );
function va_fun_img_scraper_script_ajax(){

    $obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];

	$json_data = file_get_contents($file_url);

    if(!empty($json_data)){
    	$json_data = json_decode($json_data);

    	$total_data = count( $json_data );

    	$d = date("j-M-Y H:i:s");
    	$final_data = $json_data[$startpos];

    	$obj_class->product_import_log( print_r($final_data,true) );

    	$end_pos = $startpos+1;
    	$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


    	if($total_data <= $startpos){
    		$message = '['.$d.'] - Done ';

    		unlink($file_url);
    		wp_send_json_success(
    			array(
    				'pos' => 'done',
    				'file_path' => $file_url,
    				'_parent' => $_parent,
    				'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
    				'message' => $message,

    			)
    		);
    	}else{

    		//$message = '['.$d.'] - '.$final_data->ID.' -> '.$final_data->post_title;
    		$message = '['.$d.'] - '.$obj_class->fun_img_scraper_script( $final_data );

    		wp_send_json_success(
    			array(
    				'pos' => $end_pos,
    				'file_path' => $file_url,
    				'_parent' => $_parent,
    				'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
    				'message' => $message,
    			)
    		);
    	}
    }
	wp_die();
}


add_action( 'wp_ajax_change_product_price_import', 'va_change_product_price_import' );
add_action( 'wp_ajax_nopriv_change_product_price_import', 'va_change_product_price_import' );
function va_change_product_price_import(){
	$obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	//$total_data = $total_data - 1;

	$del = "\t";
	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		$header = fgetcsv( $handle, 0, $del);

		$header = Array(
            '0' => 'prod_title',
            '1' => 'item_code',
            '2' => 'old_price',
            '3' => 'new_price',
        );

		while (($data = fgetcsv($handle, 1000, $del)) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i];

            }
            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';
				unlink($file_url);
				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,

			    	)
			    );
            }else if( $row == $end_pos){

            	if(isset( $parse_data['item_code'] ) && !empty( $parse_data['item_code'] ) ){

            		$message = '['.$d.'] - '.$obj_class->va_change_price_product( $parse_data );

            		// $message = '['.$d.'] - '.print_r(  $parse_data, true );
			    	wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}else{
            		$message = '['.$d.'] - NO Data Found!';

            		wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}

            }
			// for ($c=0; $c < $num; $c++) {
			// 	 echo $data[$c] . "<br />\n";
			// }
		}
		fclose($handle);
	}
	wp_die();
}

add_action( 'wp_ajax_change_product_shipping_import', 'va_change_product_shipping_import' );
add_action( 'wp_ajax_nopriv_change_product_shipping_import', 'va_change_product_shipping_import' );
function va_change_product_shipping_import(){
	$obj_class = new WP_Upload_Handler();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	//$total_data = $total_data - 1;

	$del = "\t";
	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		$header = fgetcsv( $handle, 0, $del);

		$header = Array(
            '0' => 'prod_title',
            '1' => 'item_code',
            '2' => 'old_date',
            '3' => 'new_date',
        );

		while (($data = fgetcsv($handle, 1000, $del)) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i];

            }
            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';
				unlink($file_url);
				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,

			    	)
			    );
            }else if( $row == $end_pos){

            	if(isset( $parse_data['item_code'] ) && !empty( $parse_data['item_code'] ) ){

            		$message = '['.$d.'] - '.$obj_class->va_change_shipping_product( $parse_data );

            		// $message = '['.$d.'] - '.print_r(  $parse_data, true );
			    	wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}else{
            		$message = '['.$d.'] - NO Data Found!';

            		wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    	)
				    );

            	}

            }
			// for ($c=0; $c < $num; $c++) {
			// 	 echo $data[$c] . "<br />\n";
			// }
		}
		fclose($handle);
	}
	wp_die();
}