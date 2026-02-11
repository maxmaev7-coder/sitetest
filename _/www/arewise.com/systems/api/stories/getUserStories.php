<?php

$idUser = (int)$_GET["id"];
$idUserAuth = (int)$_GET["id_user_auth"];

$results = [];
$stories = [];

$getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);

if($getUser){

	if($idUserAuth == $idUser){
		$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_id desc', [$idUser,1]);
	}else{
		$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? order by clients_stories_media_id desc', [$idUser,1,1]);
	}
	
	if(count($getStoriesMedia)){
		foreach ($getStoriesMedia as $value) {

			if($value['clients_stories_media_ad_id']){
				$getAd = findOne("uni_ads","ads_id=?", [$value['clients_stories_media_ad_id']]);
				$stories[] = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров')),'ad'=>['id'=>$getAd['ads_id'], 'title'=>$getAd['ads_title'], 'price'=>apiPrice($getAd['ads_price'])]];
			}else{
				$stories[] = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров'))];
			}

			$results['user_stories'][] = ['id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser),'stories'=>$stories, 'timestamp'=>strtotime($value['clients_stories_media_timestamp'])];

		}
	}

}

echo json_encode(['user'=>$results['user_stories']]);

?>