<?php

$results = [];

$results['links'][] = ['name'=>'Правила подачи объявлений', 'link'=>_link('rules')];
$results['links'][] = ['name'=>'Пользовательское соглашение', 'link'=>_link('polzovatelskoe-soglashenie')];
$results['links'][] = ['name'=>'Запрещенные к публикации товары/услуги', 'link'=>_link('prohibited')];
$results['links'][] = ['name'=>'Политики конфиденциальности', 'link'=>_link('privacy-policy')];

echo json_encode(['blog'=>$results['blog'], 'links'=>$results['links']]);

?>