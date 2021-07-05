<?php
require_once("script_smartwall/smartwall.php");
$smart_config_file = __DIR__ . '/script_smartwall/config.php';
$configRed = include $smart_config_file;
$smartwall=new smartwall("");
if(isset($_GET["need_update_now"]))
    $smartwall->needUpdateNow($configRed);

$smartwall->work($configRed);


