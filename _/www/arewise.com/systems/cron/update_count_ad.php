<?php
defined('unisitecms') or exit();

if($settings['display_count_ads_categories']){

   $Cache->update( "count_ads" );

   $getAds = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now()");

   $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

   if(count($getAds)){
      foreach ($getAds as $ad_value) {

         $ids_cat = explode(',', $CategoryBoard->reverseId($getCategories,$ad_value['ads_id_cat']));

         if( count($ids_cat) ){
             foreach ($ids_cat as $cat_value) {

                 if($cat_value){

                    $ads['geo'][$cat_value][$ad_value['ads_country_id']] += 1;
                    $ads['geo'][$cat_value][$ad_value['ads_region_id']] += 1;
                    $ads['geo'][$cat_value][$ad_value['ads_city_id']] += 1;
                    $ads['user'][$cat_value][$ad_value['ads_id_user']] += 1;
                    $ads['category'][$cat_value] += 1;

                 }

             }
         }


      }
   }

   if( count($ads) ){
       $Cache->set([ "table" => "count_ads", "key" => "count_ads", "data" => $ads ]);
   }

}

?>