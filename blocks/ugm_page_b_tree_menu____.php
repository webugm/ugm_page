<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2018-07-07
// 伸縮選單區塊type=6(樹狀選單)
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_b_tree_menu($options){
  global $xoopsDB;  
	#---- 過濾讀出的變數值 ----
	$myts = MyTextSanitizer::getInstance();
	$options[0]=(empty($options[0]))?$_REQUEST["bid"]:$options[0];//ID 
  if(empty($options[1]))return;
  #取得標題
  $sql = "select `menu_title`  from ".$xoopsDB->prefix("ugm_page_menu")." 
          where `menu_sn`={$options[1]}";//die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error($sql));
  $row=$xoopsDB->fetchArray($result);
  $block['title'] = $myts->htmlSpecialChars($row['menu_title']);

  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_type`=6 and `menu_ofsn`={$options[1]} order by `menu_sort`";//die($sql);

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error($sql));
  $rows=array();
	while($row=$xoopsDB->fetchArray($result)){
    $row['menu_sn'] = intval($row['menu_sn']); 
    $row['menu_ofsn'] = 0;   
    $row['menu_title'] = $myts->htmlSpecialChars($row['menu_title']);
    $row['menu_new'] = intval($row['menu_new']);  
    $row['menu_url'] = $row['menu_url'] ? $myts->htmlSpecialChars($row['menu_url']):"#";
	  //以下會產生這些變數： ``, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    $row['sub']=get_tree_menu_down($row['menu_sn']);
    $rows[] = $row;
  }
  $block['options'] = $options;
  $block['rows'] = $rows;
	return $block;
}
//".chk($options[2],"shadow",0,"selected")."
//區塊編輯函式
function ugm_page_b_tree_menu_edit($options){

  $options[0]=$_REQUEST["bid"];//ID
  $options[1]=(empty($options[1]))?"":$options[1];//選擇類別
  //0.ID 1.文章數 2.寬度 
  $form="    
    <style>
      .block_edit tr{height:30px;}
      .block_edit td{vertical-align: middle;}
    </style>
    <table style='width:auto;' class='block_edit'> 
      <tr>
        <th>1</th>
        <th>ID</th>
        <td><input type='text' name='options[0]' value='{$options[0]}' size=12 readonly> "._MB_UGMPAGE_B_CATE_OP0."</td>
      </tr>
      <tr>
        <th>2.</th>
        <th>"._MB_UGMPAGE_B_TREE_MENU_OP1."</th>
        <td>
          <select name='options[1]' size=1>
          ".get_ugm_page_b_tree_menu_cate(6,0,$options[1])."
          </select>
        </td>
      </tr>
    </table>
  ";
  //ugm_page_b_tree_menu
	return $form;
}

if(!function_exists("get_ugm_page_b_tree_menu_cate")){
function get_ugm_page_b_tree_menu_cate($menu_type=0,$menu_ofsn=0,$menu_sn_chk=0){
  global $xoopsModule,$xoopsDB;
  $sql = "select `menu_sn`,`menu_title` from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='{$menu_type}' and `menu_ofsn`='{$menu_ofsn}' and `menu_enable`='1' order by `menu_sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error($sql));
  while(list($menu_sn,$menu_title)=$xoopsDB->fetchRow($result)){
    $main.="
      <option value='{$menu_sn}' ".chk($menu_sn_chk,$menu_sn,0," selected").">{$menu_title}</option>
    ";
  }
  return $main;
}
}


###############################################################################
#  得到選單第二層
###############################################################################
if(!function_exists("get_tree_menu_down")){
function get_tree_menu_down($menu_ofsn=0){
  global $xoopsDB;   
	#---- 過濾讀出的變數值 ----
	$myts = MyTextSanitizer::getInstance();
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='6' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error($sql));
  
  $rows=array();
  while($row=$xoopsDB->fetchArray($result)){
    $row['menu_sn'] = intval($row['menu_sn']);   
    $row['menu_title'] =  $myts->htmlSpecialChars($row['menu_title']);
    $row['menu_new'] = intval($row['menu_new']);  
    $row['menu_url'] = $row['menu_url'] ? $myts->htmlSpecialChars($row['menu_url']):"#";
    $row['sub']=get_tree_menu_down($row['menu_sn']);
    $rows[] = $row;    
  }
  return $rows;
}
}