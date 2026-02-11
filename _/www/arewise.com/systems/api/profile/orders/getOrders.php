<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$buy = [];
$sell = [];

$getOrdersBuy = getAll("select * from uni_clients_orders where clients_orders_from_user_id=? order by clients_orders_id desc", [$idUser]);

if(count($getOrdersBuy)){
	foreach ($getOrdersBuy as $value) {		
		$getOrder = findOne('uni_secure', 'secure_id_order=?', [$value["clients_orders_uniq_id"]]);
		if($getOrder){
			$buy[] = [
				"date" => datetime_format($getOrder["secure_date"], true),
				"status" => $getOrder["secure_status"],
				"status_name" =>apiSecureStatusLabel($getOrder,$idUser),
				"order_id" => $value["clients_orders_uniq_id"],
			];
		}
	}
}

$getOrdersSell = getAll("select * from uni_clients_orders where clients_orders_to_user_id=? order by clients_orders_id desc", [$idUser]);

if(count($getOrdersSell)){
	foreach ($getOrdersSell as $value) {
		$getOrder = findOne('uni_secure', 'secure_id_order=?', [$value["clients_orders_uniq_id"]]);
		if($getOrder){
			$sell[] = [
				"date" => datetime_format($getOrder["secure_date"], true),
				"status" => $getOrder["secure_status"],
				"status_name" => apiSecureStatusLabel($getOrder,$idUser),
				"order_id" => $value["clients_orders_uniq_id"],
			];
		}
	}
}

echo json_encode(['buy'=>$buy?:null, 'sell'=>$sell?:null]);

?>