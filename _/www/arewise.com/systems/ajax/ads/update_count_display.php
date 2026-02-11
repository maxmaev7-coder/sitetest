<?php
if($_SESSION['count_display_ads']){
    
    $insert_list = [];

	 foreach ($_SESSION['count_display_ads'] as $id => $id_user) {
	     $insert_list[] = "($id,$id_user)";
	 }
	 
	 insert("INSERT INTO uni_ads_views_display_temp(ad_id,user_id)VALUES ".implode(",",$insert_list));

	 unset($_SESSION['count_display_ads']);

}
?>