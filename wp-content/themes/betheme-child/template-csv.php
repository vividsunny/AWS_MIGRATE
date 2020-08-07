<?php
/*
Template name: CSV Cron
 */

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://demo1.popupcomicshops.com/wp-content/plugins/wp_upload_zip/php/files/201905.xml",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 1f609191-469e-43a9-b568-c32251f973ab",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  vivid(  $response );
}