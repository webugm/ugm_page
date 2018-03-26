<?php
//------------  cate use --------------------------------
define("_STOP_LEVEL",$xoopsModuleConfig['cate_level']);
define("_DB_CATE_TABLE","ugm_page_cate");

#引入權限檔
include_once "groupperm.php";
$gperm = get_gperm($module_id,$isAdmin,$gperm_itemid_arr,$gperm_name_arr);
//---- 權限設定 ---- */

#工具列設定
$mod_name   = $xoopsModule->name();//模組中文名
$moduleName = $xoopsModule->dirname();//模組 
$i = 0;
if($gperm['ugm_page_cate'][4] or $gperm['ugm_page_post'][4] or $gperm['ugm_page_menu'][4]){ 
  $moduleMenu[$i]['url']="index.php";
  $moduleMenu[$i]['title']=_TAD_HOME;
  $moduleMenu[$i]['icon']="fa-home";
}

if($gperm['ugm_page_cate'][4]){
  $i++;
  $moduleMenu[$i]['url']="cate.php";
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME2;//類別管理
  $moduleMenu[$i]['icon']="fa-folder-open-o";
}

if($gperm['ugm_page_post'][4]){
  $i++;
  $moduleMenu[$i]['url']="post.php";
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME3;//文章管理
  $moduleMenu[$i]['icon']="fa-bars";
}

if($gperm['ugm_page_post'][1]){
  $i++;
  $moduleMenu[$i]['url']="post.php?op=ugm_page_main_IForm";
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME4;//新增文章
  $moduleMenu[$i]['icon']="fa-plus";
}

if(!empty($_REQUEST['msn']) and $gperm['ugm_page_post'][2]){
  $i++;
  $moduleMenu[$i]['url']="post.php?op=ugm_page_main_UForm&msn=".intval($_REQUEST['msn']);
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME5;//編輯文章，有權限即可，不一定同人
  $moduleMenu[$i]['icon']="fa-bars";
}
if($gperm['ugm_page_menu'][1]){
  $i++;
  $moduleMenu[$i]['url']="menu.php?menu_type=1";
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME6;//選單管理
  $moduleMenu[$i]['icon']="fa-server";
}


if($isAdmin) {
  $i++;
  $moduleMenu[$i]['url']="var.php";
  $moduleMenu[$i]['title']=_MD_UGMPAGE_SMNAME9;//佈景管理
  $moduleMenu[$i]['icon']="fa-tachometer ";
  $i++;
  $moduleMenu[$i]['url']="admin/index.php";
  $moduleMenu[$i]['title']=sprintf(_TAD_ADMIN,$mod_name);//管理後台
  $moduleMenu[$i]['icon']="fa-wrench";
  $i++;
  $moduleMenu[$i]['url']=XOOPS_URL."/modules/system/admin.php?fct=preferences&op=showmod&mod={$module_id}";
  $moduleMenu[$i]['title']=sprintf(_TAD_CONFIG,$mod_name);//」偏好設定
  $moduleMenu[$i]['icon']="fa-edit";
  $i++;
  $moduleMenu[$i]['url']=XOOPS_URL."/modules/system/admin.php?fct=modulesadmin&op=update&module={$moduleName}";
  $moduleMenu[$i]['title']=sprintf(_TAD_UPDATE,$mod_name);//更新
  $moduleMenu[$i]['icon']="fa-refresh";
  $i++;
  $moduleMenu[$i]['url']=XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&op=list&filter=1&selgen={$module_id}&selmod=-2&selgrp=-1&selvis=-1";
  $moduleMenu[$i]['title']=sprintf(_TAD_BLOCKS,$mod_name);//」區塊管理
  $moduleMenu[$i]['icon']="fa-th";
}