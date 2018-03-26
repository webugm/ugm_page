<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php";
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_show_page($options){
	global $xoopsDB;
	$isPageMain=b_isPageMain();
	$sql="select `msn`,`title`,`content` from ".$xoopsDB->prefix("ugm_page_main")." where `msn`='{$options[1]}' and `enable`='1'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  if($xoopsDB->getRowsNum($result)==0)return; //撈不到資料返回
  list($msn,$title,$content)=$xoopsDB->fetchRow($result);
  $edit=($isPageMain)?"<br /><a href='".XOOPS_URL."/modules/ugm_page/post.php?op=ugm_page_main_UForm&msn={$msn}'>"._EDIT."</a>":"";

  $block="
    <style>
      #show_page_{$options[0]} ol{list-style-type: cjk-ideographic;margin:0 0 0 3em;padding:0 0 0 1em;}
      #show_page_{$options[0]} ol li{list-style-type: cjk-ideographic;font-size: 18px;font-weight: bold;}
      #show_page_{$options[0]} ol li ol{list-style-type: decimal;margin:0 0 0 1em;padding:0 0 0 1em;}
      #show_page_{$options[0]} ol li ol li{list-style-type: decimal;font-size: 16px;font-weight: 500;}
      #show_page_{$options[0]} ol li ol li ol{list-style-type: disc;margin:0 0 0 0em;padding:0 0 0 1em;}
      #show_page_{$options[0]} ol li ol li ol li{list-style-type: disc;font-size: 14px;font-weight: 200;}
    </style>
    <div id='show_page_{$options[0]}' class='ugm_page_show_page'>
      {$content}
      {$edit}
    </div>";
  if($options[2]=="corner"){
    $block=block_ugm_div("",$block,"");
  }elseif($options[2]=="shadow"){
    $block=block_ugm_div("",$block,"shadow");
  }
	return $block;
}

//區塊編輯函式
function ugm_page_show_page_edit($options){
  $options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID
  $options[1]=(empty($options[1]))?"":$options[1];//文章流水號
  $options[2]=(empty($options[2]))?"corner":$options[2];//外框 1.空 2.corner 3.shadow
  $form="
  <table style='width:auto;'>
    <tr><th>1</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> "._MB_UGMPAGE_B_CATE_OP0."</td></tr>
    <tr><th>2.</th><th>"._MB_UGMPAGE_SHOW_PAGE_OP1."</th><td><select name='options[1]' size=1>".get_main_msn_option($options[1])."</select></td></tr>
	  <tr><th>2.</th><th>"._MB_UGMPAGE_SHOW_PAGE_OP2."</th><td><select name='options[2]' size=1>
    <option value='no' ".chk($options[2],"no",0,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_NO."</option>
    <option value='corner' ".chk($options[2],"corner",1,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_CORNER."</option>
    <option value='shadow' ".chk($options[2],"shadow",0,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_SHADOW."</option>
    </select></td></tr>
  </table>
  ";
	return $form;
}

###############################################################################
#  得到文章標題
#
#
#
###############################################################################
if(!function_exists("get_main_msn_option")){
function get_main_msn_option($msn_chk=""){
  global $xoopsDB;
  $sql = "select `msn`,`title`  from ".$xoopsDB->prefix("ugm_page_main")." where `enable`='1' order by `date` desc ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $main="";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `msn`, `title`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="<option value='{$msn}' ".chk($msn,$msn_chk,0," selected").">{$title}</option>";
  }
  return $main;
}
}
###############################################################################
#  判斷是否有文章編輯權限
#
#
#
###############################################################################
if(!function_exists('b_isPageMain')){
function b_isPageMain(){
  global $xoopsUser;
  $isPageMain=false;
  $perm_name = 'ugm_page_post';//權限名稱
  $perm_itemid = 2;//權限項目編號  - 文章編輯
  if($xoopsUser){
    $groups = $xoopsUser->getGroups();
    //---------------- 在區塊須要多以下這二行 --------------------------
    $modhandler = &xoops_gethandler('module');
		$xoopsModule = &$modhandler->getByDirname("ugm_page");
		//------------------------------------------------------------------
		$module_id = $xoopsModule->getVar('mid');
    $gperm_handler =& xoops_gethandler('groupperm');
    if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)){
    	//若有權限要做的動作
    	$isPageMain=true;
    }
  }
  return $isPageMain;
}
}
?>