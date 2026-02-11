<?php

$langIso = $_GET["lang_iso"];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$filterIdUser = (int)$_GET["filter_id_user"];
$search = clear($_GET["search"]);

$page = (int)$_GET["page"];
$cat_id = (int)$_GET["cat_id"];
$sorting = clear($_GET["sorting"]);
$only_auction = (int)$_GET["only_auction"];

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

$query = [];
$output = 30;

if($search && mb_strlen($search) >= 2){
	$query[] = $Filters->explodeSearch($search);
}

if($filterIdUser){

	if($sorting == 'active'){
		$query[] = "ads_id_user='".$filterIdUser."' and ads_status='1' and ads_period_publication > now()";
	}elseif($sorting == 'sold'){
		$query[] = "ads_id_user='".$filterIdUser."' and ads_status IN(5,4)";
	}elseif($sorting == 'archive'){
		$query[] = "ads_id_user='".$filterIdUser."' and (ads_status NOT IN(1,5,4) or ads_period_publication < now()) and ads_status!=8";
	}else{
		$query[] = "ads_id_user='".$filterIdUser."' and ads_status='1' and ads_period_publication > now()";
	}

	$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>"ORDER By ads_datetime_add DESC"]);

}else{

    if($settings["ads_sorting_variant"] == 0){
      $sorting = "order by ads_sorting desc, ads_id desc";
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sorting = "order by ads_sorting desc, ads_id asc";   
    }else{
      $sorting = "order by ads_sorting desc";
    }

	if(!$only_auction){
		if($city_id){
			$query[] = "ads_city_id='".$city_id."'";
		}elseif($region_id){
			$query[] = "ads_region_id='".$region_id."'";
		}elseif($country_id){
			$query[] = "ads_country_id='".$country_id."'";
		}
	}

	$query[] = "clients_status IN(0,1) and ads_status='1' and ads_period_publication > now()";

	if($only_auction){
		$query[] = "ads_auction='1'";
	}

	$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>$sorting]);

}


echo json_encode(['data'=>apiArrayDataAds($getAds,$idUser), 'count'=>$getAds['count'].' '.ending($getAds['count'], apiLangContent('объявление', $langIso), apiLangContent('объявления', $langIso), apiLangContent('объявлений', $langIso)), 'pages'=>getCountPage($getAds['count'],$output)]);
?>