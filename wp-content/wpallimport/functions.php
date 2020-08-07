<?php
function convertDate($date) {
	$now = date('Y-m-d\TH:i:s');
   	$today = DateTime::createFromFormat('Y-m-d\TH:i:s', $now);
    $newDate = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);

    if($today < $newDate) {
      return $newDate ? $newDate->getTimestamp() : $newDate; 
    }
    else {
      return "";
    }
}
function enablePreOrder($date) {
	$now = date('Y-m-d\TH:i:s');
   	$today = DateTime::createFromFormat('Y-m-d\TH:i:s', $now);
    $newDate = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);

    if($today < $newDate) {
      return "yes"; 
    }
    else {
      return "no";
    }
}
function getImage($url) {
	$str = explode("\\",$url);
	return $str[count($str)-1];
}
?>