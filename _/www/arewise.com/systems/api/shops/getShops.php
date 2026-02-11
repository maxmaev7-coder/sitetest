<?php

$page = (int)$_GET["page"];
$cat_id = (int)$_GET["cat_id"];

$results = [];
$output = 10;

$query = "";

$totalCount = (int)getOne("select count(*) as total from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query}")["total"];

$getShops = getAll("select * from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query} order by clients_shops_id desc".navigation_offset(["count"=>$totalCount, "output"=>$output, "page"=>$page]));

if($getShops){
	foreach ($getShops as $key => $value) {

		$sliders = [];

		$getCountAds = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$value["clients_shops_id_user"]."'");
		$getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_shops_id_user']]);
		$getSliders = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$value["clients_shops_id"]]);

		if(count($getSliders)){
			foreach ($getSliders as $slider) {
				if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"])){
					$sliders[] = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"];
				}
			}
		}

		$results[] = [
			"id" => $value['clients_shops_id'],
			"title" => $value['clients_shops_title'],
			"desc" => $value['clients_shops_desc'],
			"logo" => Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]),
			"count_ads" => $getCountAds .' '.ending($getCountAds, apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')),
			"sliders" => $sliders ?: null,
			"user" => [
				"id" => $getUser['clients_id'],
				"rating" => $Profile->ratingBalls($getUser['clients_id']),
			],
		];

	}
}

echo json_encode(['data'=>$results, 'count'=>$totalCount .' '.ending($totalCount, apiLangContent('магазин'), apiLangContent('магазина'), apiLangContent('магазинов')), 'pages'=>getCountPage($totalCount,$output)]);

?>