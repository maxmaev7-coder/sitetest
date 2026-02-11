<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$ad_id = (int)$_POST['ad_id'];
$additional_services_total_price = 0;
$booking_hour_count = (int)$_POST['booking_hour_count'] ?: 1;
$booking_hour_start = clear($_POST['booking_hour_start']) ?: '12:00';

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getAd = $Ads->get("ads_id=?",[$ad_id]);

if(!$getAd) exit();

$booking_date_start = $_POST['booking_date_start'] ? date('d.m.Y', strtotime($_POST['booking_date_start'])) : date('d.m.Y');

if($_POST['booking_date_end']){
	$booking_date_end = date('d.m.Y', strtotime($_POST['booking_date_end']));
}else{
	if($getAd["ads_booking_min_days"]){ 
	    $booking_date_end = date('d.m.Y', strtotime('+'.$getAd["ads_booking_min_days"].' days')); 
	}else{ 
	    $booking_date_end = date('d.m.Y', strtotime('+1 days')); 
	}
}

$difference_days = difference_days($booking_date_end,$booking_date_start) ?: 1;

$booking_additional_services = json_decode($getAd["ads_booking_additional_services"], true);

if($_POST['booking_additional_services'] && $getAd["ads_booking_additional_services"]){
	foreach ($_POST['booking_additional_services'] as $key => $value) {
	    if($booking_additional_services[$key]){
	        $additional_services_total_price += $booking_additional_services[$key]['price'];
	    }
	}
}

if($getAd['ads_price_measure'] == 'hour'){
	$total = ($booking_hour_count * $getAd["ads_price"]) + $additional_services_total_price;
	$prepayment = calcPercent($booking_hour_count * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}else{
	$total = ($difference_days * $getAd["ads_price"]) + $additional_services_total_price;
	$prepayment = calcPercent($difference_days * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}

echo json_encode(['days'=>$difference_days,'total_amount'=>apiPrice($total), 'prepayment_amount'=>apiPrice($prepayment)]);

?>