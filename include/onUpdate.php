<?php
function xoops_module_update_ugm_page(&$module, $old_version) {
    GLOBAL $xoopsDB;
		if(!chk_chk1()) go_update1();//新增選單資料表
		if(!chk_chk2()) go_update2();//新增主檔排序
		if(!chk_chk3()) go_update3();//新增「檔案資料表」2012-09-11
    if(!chk_chk4()) go_update4();//新增「變數管理資料表」 2013-09-14
    if(!chk_chk5()) go_update5();//新增「文章編輯者」欄位 2016-04-26
    return true;
}

# ------------- 新增選單資料表    ----------------------------------------------
function chk_chk1(){
global $xoopsDB;
	$sql="select count(`menu_sn`) from ".$xoopsDB->prefix("ugm_page_menu");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}

function go_update1(){
	global $xoopsDB;
	$sql="CREATE TABLE  ".$xoopsDB->prefix("ugm_page_menu")." (
    `menu_sn` smallint(5) unsigned NOT NULL auto_increment,
    `menu_type` tinyint unsigned NOT NULL ,
    `menu_ofsn` smallint unsigned NOT NULL ,
    `menu_sort` smallint(5) unsigned NOT NULL,
    `menu_title` varchar(255) NOT NULL,
    `menu_op` varchar(255) NOT NULL,
    `menu_tip` varchar(255) NOT NULL,
    `menu_enable` enum('1','0') NOT NULL default '1',
    `menu_new` enum('0','1') NOT NULL default '0',
    `menu_url` varchar(255) NOT NULL,
    `menu_date` varchar(255) NOT NULL,
    `menu_uid` smallint(5) unsigned NOT NULL,
    PRIMARY KEY  (`menu_sn`)
  );";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  web_error());
	return true;
}
# -------新增主檔排序   ugm_page_main-> sort   ----------------------------------------------------
function chk_chk2(){
	global $xoopsDB;
	$sql="select count(`sort`) from ".$xoopsDB->prefix("ugm_page_main");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}
function go_update2(){
	global $xoopsDB;
	$sql="ALTER TABLE ".$xoopsDB->prefix("ugm_page_main")." ADD `sort` smallint(5) unsigned NOT NULL default '0'";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  web_error());
}

# ------------- 新增「檔案資料表」    ----------------------------------------------
function chk_chk3(){
global $xoopsDB;
	$sql="select count(`files_sn`) from ".$xoopsDB->prefix("ugm_page_files_center");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}

function go_update3(){
	global $xoopsDB;
  mk_dir(XOOPS_ROOT_PATH."/uploads/ugm_page");
	mk_dir(XOOPS_ROOT_PATH."/uploads/ugm_page/cate");
	mk_dir(XOOPS_ROOT_PATH."/uploads/ugm_page/file");
	mk_dir(XOOPS_ROOT_PATH."/uploads/ugm_page/image");
	mk_dir(XOOPS_ROOT_PATH."/uploads/ugm_page/image/.thumbs");



	$sql="CREATE TABLE  ".$xoopsDB->prefix("ugm_page_files_center")." (
    `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    `col_name` varchar(255) NOT NULL DEFAULT '',
    `col_sn` smallint(5) unsigned NOT NULL,
    `sort` smallint(5) unsigned NOT NULL,
    `kind` enum('img','file') NOT NULL,
    `file_name` varchar(255) NOT NULL DEFAULT '',
    `file_type` varchar(255) NOT NULL DEFAULT '',
    `file_size` int(10) unsigned NOT NULL,
    `description` text NOT NULL,
    `counter` mediumint(8) unsigned NOT NULL,
    PRIMARY KEY (`files_sn`)
);";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  web_error());
	return true;
}
//---------- 新增「變數管理資料表」 ----///
function chk_chk4(){
	global $xoopsDB;
	$sql="select count(`sn`) from ".$xoopsDB->prefix("ugm_page_var");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}

function go_update4(){
	global $xoopsDB;
  $sql="CREATE TABLE  ".$xoopsDB->prefix("ugm_page_var")." (
  `sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT comment 'sn',
  `csn` smallint(5) unsigned NOT NULL default '0' comment '類別',
  `sort` smallint(5) unsigned NOT NULL default '0' comment '排序',
  `enable` enum('1','0') NOT NULL default '1' comment '啟用',
  `type` tinyint unsigned NOT NULL  comment '類型',
  `var_title` varchar(255) NOT NULL comment '標題',
  `var_name` varchar(255) NOT NULL comment '變數名稱',
  `var_value` text NOT NULL comment '變數值',
  `var_tip` varchar(255) NOT NULL comment '提示',
  PRIMARY KEY (`sn`)
  )";
	$xoopsDB->queryF($sql);
	return true;
}

# -------新增主檔編輯者   ugm_page_main-> editor   ----------------------------------------------------
function chk_chk5(){
  global $xoopsDB;
  $sql="select count(`editor`) from ".$xoopsDB->prefix("ugm_page_main");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}
function go_update5(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("ugm_page_main")." ADD `editor` varchar(255) NOT NULL default '' ";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  web_error());
}

# ---------------------------------------------------------------------------
function mk_dir($dir=""){
    //
    if(empty($dir))return;
    //
    if (!is_dir($dir)) {
        umask(000);
        //
        mkdir($dir, 0777);
    }
}
?>
