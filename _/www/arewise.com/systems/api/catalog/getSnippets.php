<?php

$idCat = (int)$_GET["id"];

$results = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(count($getCategoryBoard)){

	if($idCat){

		if (isset($getCategoryBoard["category_board_id_parent"][$idCat])) {
			foreach ($getCategoryBoard['category_board_id_parent'][$idCat] as $key => $value) {
				
				$results[] = [
					'category_board_id' => $value['category_board_id'],
					'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
					'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
					'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
					'breadcrumb' => breadcrumbCategories($getCategoryBoard,$idCat),
				];

			}
		}else{

          $id_parent = $getCategoryBoard["category_board_id"][$idCat];

          if(isset($getCategoryBoard["category_board_id_parent"][$id_parent["category_board_id_parent"]])){
            foreach ($getCategoryBoard["category_board_id_parent"][$id_parent["category_board_id_parent"]] as $value) {

				$results[] = [
					'category_board_id' => $value['category_board_id'],
					'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
					'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
					'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
					'breadcrumb' => breadcrumbCategories($getCategoryBoard,$idCat),
				];

            }
          }

		}

		echo json_encode(['categories'=>$results ?: null]);

	}else{

		foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {
			
			$results[] = [
				'category_board_id' => $value['category_board_id'],
				'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
				'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
				'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
				'breadcrumb' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			];

		}

		echo json_encode(['categories'=>$results ?: null]);

	}

}else{

	echo json_encode(['categories'=>null]);
	
}

?>