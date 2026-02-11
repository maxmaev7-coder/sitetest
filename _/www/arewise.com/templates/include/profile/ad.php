<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-menu-tab" >
  <div data-id-tab="ad" <?php if($action == "ad" || !$action){ echo 'class="active"'; } ?> > <?php if($data["ad"]["count"]){ echo $data["ad"]["count"] . " " . $ULang->t('в продаже'); }else{ echo $ULang->t('В продаже'); } ?></div>
  <div data-id-tab="sold" > <?php if($data["sold"]["count"]){ echo $data["sold"]["count"] . " " . $ULang->t('продано'); }else{ echo $ULang->t('Продано'); } ?> </div>
  <?php if($data["advanced"]){ ?>
  <div data-id-tab="archive" > <?php if($data["archive"]["count"]){ echo $data["archive"]["count"] . " " . $ULang->t('в архиве'); }else{ echo $ULang->t('В архиве'); } ?> </div>
  <?php } ?>
</div>

<div class="user-menu-tab-content <?php if($action == "ad" || !$action){ echo 'active'; } ?>" data-id-tab="ad" >
     
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["ad"]["all"]){

         foreach ($data["ad"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
           <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["ad"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
           </ul>
         <?php

     }else{
        ?>
        <div class="user-block-no-result" >
           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все созданные объявления будут отображаться на этой странице."); ?></p>
        </div>
        <?php
     }
   ?>
   </div>
  
</div>

<div class="user-menu-tab-content <?php if($action == "sold"){ echo 'active'; } ?>" data-id-tab="sold" >
   
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["sold"]["all"]){

         foreach ($data["sold"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["sold"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все проданные товары будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
   </div>
  
</div>

<?php if($data["advanced"]){ ?>
<div class="user-menu-tab-content <?php if($action == "archive"){ echo 'active'; } ?>" data-id-tab="archive" >
   
   <?php
     if($data["archive"]["all"]){

         foreach ($data["archive"]["all"] as $key => $value) {
            include $config["template_path"] . "/include/user_ad_list.php";
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["archive"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все объявления помещенные в архив будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
  
</div>
<?php } ?>