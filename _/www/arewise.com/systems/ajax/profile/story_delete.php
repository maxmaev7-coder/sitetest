<?php

$id = (int)$_POST["story_id"];

$getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_user_id=?", array($id,$_SESSION["profile"]["id"]));

if($getStory){

$Main->returnPaymentStory($id);

if($getStory['clients_stories_media_status'] == 0 && $settings["user_stories_free_add"]){
    $getUser = findOne('uni_clients', 'clients_id=?', [$_SESSION["profile"]["id"]]);
    if($getUser['clients_count_story_publication']!=0){
        update('update uni_clients set clients_count_story_publication=clients_count_story_publication-1 where clients_id=?', [$_SESSION["profile"]["id"]]);
    }
}

@unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_name']);

if($getStory['clients_stories_media_preview']) @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_preview']);

update("delete from uni_clients_stories_media where clients_stories_media_id=?", array($id));

update('delete from uni_clients_stories_view where story_id=?', [$id]);

$checkStoryMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=?', [$getStory['clients_stories_media_user_id']]);

if(!$checkStoryMedia){
    update("delete from uni_clients_stories where clients_stories_user_id=?", array($getStory['clients_stories_media_user_id']));
}

}

?>