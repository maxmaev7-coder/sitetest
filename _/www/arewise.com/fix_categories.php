<?php
define('unisitecms', true);
session_start();

// Пытаемся подключить конфиг
if(file_exists("./config.php")){
    $config = require "./config.php";
} else {
    die("Ошибка: файл config.php не найден в этой папке.");
}

require_once( $config["basePath"] . "/systems/unisite.php");

$Admin = new Admin();

echo "<h2>Диагностика категорий (Сервер: ".$_SERVER['HTTP_HOST'].")</h2>";

// 1. Ищем объявления без категории или в 0 категории
$lost_ads = getAll("SELECT ads_id, ads_title, ads_id_cat FROM uni_ads WHERE ads_id_cat = 0 OR ads_id_cat IS NULL");

echo "<h3>Найдено " . count($lost_ads) . " объявлений без категории (в 'Главной'):</h3>";

if(count($lost_ads) > 0) {
    echo "<ul>";
    foreach($lost_ads as $ad){
        echo "<li>ID: <strong>{$ad['ads_id']}</strong> - {$ad['ads_title']} (Текущая категория: {$ad['ads_id_cat']})</li>";
    }
    echo "</ul>";

    echo "<hr>";
    echo "<p>Чтобы перенести их в категорию 'Разное' (например, ID 999), раскомментируйте код в файле fix_categories.php на сервере.</p>";

    /* // РАСКОММЕНТИРОВАТЬ ЭТОТ БЛОК ДЛЯ ИСПРАВЛЕНИЯ:
    $new_category_id = 999; 
    update("UPDATE uni_ads SET ads_id_cat = ? WHERE ads_id_cat = 0 OR ads_id_cat IS NULL", [$new_category_id]);
    echo "<h3 style='color:green;'>Успешно перенесено " . count($lost_ads) . " объявлений в категорию ID $new_category_id!</h3>";
    */

} else {
    echo "<h3 style='color:green;'>Ошибок не найдено. База объявлений чистая.</h3>";
}
?>