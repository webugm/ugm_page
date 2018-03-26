<?php
include '../../../include/cp_header.php';
//include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.php";
//include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";
//引入XOOPS的權限表單物件檔
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
#-----------------------groupperm.php-----------------------------------------#
//





//取得本模組編號
$module_id = $xoopsModule->getVar('mid');

//權限項目陣列
$item_list = array(
	'1' => _MA_UGMPAGE_GROUPPERM_1,
  '2' => _MA_UGMPAGE_GROUPPERM_2,
  '3' => _MA_UGMPAGE_GROUPPERM_3,
  '4' => _MA_UGMPAGE_GROUPPERM_4
);

//頁面標題
$title_of_form = _MA_UGMPAGE_GROUPPERM_TITLE;

//權限名稱
$perm_name = "ugm_page";

//權限描述
$perm_desc = _MA_UGMPAGE_GROUPPERM_DESC;

//建立XOOPS權限表單
$formi = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);

//將權限項目設進表單中
foreach ($item_list as $item_id => $item_name) {
	$formi->addItem($item_id, $item_name);
}

xoops_cp_header();
//loadModuleAdminMenu(2);
echo $formi->render();
xoops_cp_footer();
?>
