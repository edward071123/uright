<?php
/* 初始化設置 */
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  10);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);
@ini_set('display_errors',true);
@ini_set('error_reporting', E_ALL);

$timezone	= "Asia/Taipei";
$languages	= "zh-TW";
$encode_string ="urightedwardurightedward";
/* 設定用戶時區 */
if (PHP_VERSION >= '5.1' && !empty($timezone)){
	date_default_timezone_set($timezone);
}

/* 載入初始設定檔 */
require 'MysqliDb.php';  /* 資料庫函式庫 */
//$db = new MysqliDb ('localhost', 'root', 'beckham07', 'twhelpnew');
$db = new MysqliDb ('mariadb', 'twhelpnew', 'twhelpnew', 'twhelpnew');
require 'lib_fct.php';  /* 公用函式庫 */
// header("Access-Control-Allow-Origin: http://test2016.world-link.org");
header("Access-Control-Allow-Origin: *");
?>
