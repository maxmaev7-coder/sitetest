<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php $ULang->t("Объявление готово к публикации"); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>"  data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container minheight500" >
       
        <div class="row" >
            <div class="col-lg-12" >

              <div class="mt25 bg-container " style="text-align: center;" >

                <?php if( $data["category_board_count_free"] ){ ?>

                  <h3 class="mt20" ><strong><?php echo $ULang->t("Ваше объявление перемещено в архив"); ?></strong></h3>

                  <h5><?php echo $ULang->t("Вы уже опубликовали в категории"); ?> «<?php echo $data["category_board_name"]; ?>» <?php echo $data["category_board_count_free"]; ?> <?php echo ending($data["category_board_count_free"],$ULang->t("объявление"),$ULang->t("объявления"),$ULang->t("объявлений")) ?> <?php echo $ULang->t("бесплатно"); ?>.</h5>

                  <h5><?php echo $ULang->t("Стоимость размещения в данную категорию"); ?> <?php echo $Main->price($data["category_board_price"]); ?></h5>

                <?php 
 
                }else{

                  ?>
                  <h3 class="mt20" ><strong><?php echo $ULang->t("Ваше объявление перемещено в архив"); ?></strong></h3>
                  <h5><?php echo $ULang->t("Стоимость размещения в категорию") . " «" . $data["category_board_name"] . "»" . " " . $Main->price($data["category_board_price"]); ?></h5>
                  <?php

                }

                ?>

                <span class="btn-custom btn-color-green ads-cat-pay-publication mt25 schema-color-button" style="display: inline-block;" data-id="<?php echo $data["ads_id"]; ?>" ><?php echo $ULang->t("Опубликовать за"); ?> <?php echo $Main->price($data["category_board_price"]); ?></span>

                <div class="mt20" ></div>               
                  
              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>