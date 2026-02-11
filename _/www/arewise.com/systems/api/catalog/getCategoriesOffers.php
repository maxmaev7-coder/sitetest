<?php

$idUser = (int)$_GET["id_user"];
$results = [];

$getShops = getAll("select * from uni_clients_shops where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=? order by rand()", [1]);

if($getShops){
    foreach ($getShops as $key => $value) {

		$getCountAds = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$value["clients_shops_id_user"]."'");
		$getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_shops_id_user']]);
		$getSlider = findOne("uni_clients_shops_slider","clients_shops_slider_id_shop=?", [$value["clients_shops_id"]]);

		$results['shops'][] = [
			"id" => $value['clients_shops_id'],
			"title" => $value['clients_shops_title'],
			"logo" => Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]),
			"count_ads" => $getCountAds .' '.ending($getCountAds, apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')),
			"slider" => $getSlider ? $config["urlPath"] . "/" . $config["media"]["users"] . "/" . $getSlider["clients_shops_slider_image"] : null,
			"user" => [
				"id" => $getUser['clients_id'],
				"rating" => $Profile->ratingBalls($getUser['clients_id']),
			],
		];

    }
}


if($idUser){

	$getViewAds = getAll("select * from uni_ads_views_user where user_id=?", [$idUser]);

	if($getViewAds){

		$viewAds = [];

		foreach ($getViewAds as $key => $value) {
			$viewAds[] = $value["ads_id"];
		}

		$getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id IN(".implode(",", $viewAds).")"]);

	    $results['ads'] = apiArrayDataAds($getAds,$idUser); 

	}

	$getFavorites = getAll("select * from uni_favorites where favorites_from_id_user=?", [$idUser]);

	if($getFavorites){

		$favoritesAds = [];

		foreach ($getFavorites as $key => $value) {
			$favoritesAds[] = $value["favorites_id_ad"];
		}

		$getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id IN(".implode(",", $favoritesAds).")"]);

	    $results['favorites'] = apiArrayDataAds($getAds,$idUser); 

	}

}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {
	
	$results["categories"][] = [
		'category_board_id' => $value['category_board_id'],
		'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
		'category_board_name_word_wrap' => $value['category_board_name_word_wrap'] ? explode("|", $value['category_board_name_word_wrap']) : null,
		'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
		'category_board_id_parent' => $value['category_board_id_parent'],
		'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
		'breadcrumb' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
	];

}


echo json_encode(['ads'=>$results['ads'] ?: null, 'shops'=>$results['shops'] ?: null, 'categories'=>$results['categories'] ?: null, 'favorites'=>$results['favorites'] ?: null]); 

?>