<?php
define("TADTOOLS_PATH",XOOPS_ROOT_PATH."/modules/tadtools");
define("TADTOOLS_URL",XOOPS_URL."/modules/tadtools");
//------------  cate use --------------------------------
define("_STOP_LEVEL",$xoopsModuleConfig['cate_level']);
define("_DB_CATE_TABLE","ugm_page_cate");
//-------------------------------------------------------
//判斷是否為管理員
function is_Admin(){
  global $xoopsUser,$xoopsModule;
  $isAdmin=false;
  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }
  return $isAdmin;
}
//判斷是否有類別編輯權限
function isPageCate(){
  global $xoopsUser,$xoopsModule;
  $isPageCate=false;
  $perm_name = 'ugm_page';//權限名稱
  $perm_itemid = intval(1);//權限項目編號  - 類別編輯
  if($xoopsUser){
    $groups = $xoopsUser->getGroups();
    $module_id = $xoopsModule->getVar('mid');
    $gperm_handler =& xoops_gethandler('groupperm');
    if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id) or isAdmin()){
    	//若有權限要做的動作
    	$isPageCate=true;
    }
  }
  return $isPageCate;
}


//判斷是否有選單管理權限
function isPageMenu(){
  global $xoopsUser,$xoopsModule;
  $isPageMenu=false;
  $perm_name = 'ugm_page';//權限名稱
  $perm_itemid = intval(3);//權限項目編號  - 文章編輯
  if($xoopsUser){
    $groups = $xoopsUser->getGroups();
    $module_id = $xoopsModule->getVar('mid');
    $gperm_handler =& xoops_gethandler('groupperm');
    if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id) or isAdmin()){
    	//若有權限要做的動作
    	$isPageMenu=true;
    }
  }
  return $isPageMenu;
}


//判斷是否有佈景管理權限
function isPageTheme(){
  global $xoopsUser,$xoopsModule;
  $isPageTheme=false;
  $perm_name = 'ugm_page';//權限名稱
  $perm_itemid = intval(4);//權限項目編號  - 佈景變數編輯
  if($xoopsUser){
    $groups = $xoopsUser->getGroups();
    $module_id = $xoopsModule->getVar('mid');
    $gperm_handler =& xoops_gethandler('groupperm');
    if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id) or isAdmin()){
    	//若有權限要做的動作
    	$isPageTheme=true;
    }
  }
  return $isPageTheme;
}



?>
