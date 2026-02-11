<?php
defined('unisitecms') or exit();

if( !$settings["demo_view"] ){
$getModules = 'secure,auction,import,multilang,marketplace,marketplace,booking,marketplace,booking,marketplace,booking,marketplace,booking';
update("UPDATE uni_settings SET value=? WHERE name=?", array($getModules,'available_functionality'));
}
?>