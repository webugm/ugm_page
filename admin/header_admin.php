<?php
define("TADTOOLS_PATH",XOOPS_ROOT_PATH."/modules/tadtools");
define("TADTOOLS_URL",XOOPS_URL."/modules/tadtools");
//------------  cate use --------------------------------
define("_STOP_LEVEL",$xoopsModuleConfig['cate_level']);
define("_DB_CATE_TABLE","ugm_page_cate");

//引入共同函數檔
include_once "../function.php";
//後台模組工具選單
$module_menu=admin_toolbar();
//引入模組樣式檔
$module_css="<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
?>