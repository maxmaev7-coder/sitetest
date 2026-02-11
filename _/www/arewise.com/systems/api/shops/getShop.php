<?php
$id = (int)$_GET['id'];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$results = [];
$sliders = [];
$pages = [];

$getShop = findOne('uni_clients_shops', 'clients_shops_id=?', [$id]);

if(!$getShop){
	http_response_code(500); exit('Shop not found');
}

$getUser = findOne('uni_clients', 'clients_id=?', [$getShop["clients_shops_id_user"]]);

if($idUser && $tokenAuth){
	if(checkTokenAuth($tokenAuth, $idUser) == true){
		if($idUser == $getShop['clients_shops_id_user']){
			$owner = true;
		}
	}
}


$getSliders = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$getShop["clients_shops_id"]]);

if(count($getSliders)){
	foreach ($getSliders as $slider) {
		if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"])){
			$sliders[] = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"];
		}
	}
}

$getPages = getAll('select * from uni_clients_shops_page where clients_shops_page_id_shop=? and clients_shops_page_status=?', [$id,1]);

if(count($getPages)){
	foreach ($getPages as $page) {
		$pages[] = [
			"id"=>$page['clients_shops_page_id'],
			"name"=>$page['clients_shops_page_name'],
			"text"=>html_entity_decode(strip_tags($page['clients_shops_page_text'])),
		];
	}
}

$getCountAds = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$getShop["clients_shops_id_user"]."'");
$getSubscribers = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$getShop["clients_shops_id_user"]])["total"];
$getInSubscribe = findOne('uni_clients_subscriptions', 'clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?', [$idUser,$getShop["clients_shops_id_user"]]);

$results = [
	"owner"=>$owner ? true : false,
	"id" => $getShop['clients_shops_id'],
	"title" => $getShop['clients_shops_title'],
	"desc" => $getShop['clients_shops_desc'],
	"logo" => Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_image"]),
	"count_ads" => $getCountAds .' '.ending($getCountAds, apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')),
	"sliders" => $sliders ?: null,
	"pages" => $pages ?: null,
	"subscribers_count" => $getSubscribers,
	"in_subscribers" => $getInSubscribe ? true : false,
    "user" => apiArrayDataUser($getUser, true),
];


echo json_encode($results);

?>