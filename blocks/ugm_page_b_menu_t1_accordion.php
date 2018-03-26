<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2013-08-31
// 伸縮選單區塊type=1(bootstrap accordion 伸縮選單)
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_b_menu_t1_accordion($options){
	global $xoopsDB;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID 
	if(empty($options[1]))return;
  $block="
    <style>
      #menu_block_{$options[0]} {line-height: 100%;}
      #menu_block_{$options[0]} ul{padding: 0;margin: 0 0 0px 0px;list-style:none;font-size: 1.2em;}
      #menu_block_{$options[0]}  a{text-decoration: none;}
      #menu_block_{$options[0]}  a:hover{text-decoration: none;}
      #menu_block_{$options[0]} .icon-chevron-right {
        float: right;
        margin-top: 2px;
        margin-right: -8px;
        opacity: .25;
      }
      .accordion-heading .accordion-toggle{padding: 8px;}
      .accordion-inner {padding: 8px 4px;border-top: 1px solid #e5e5e5;font-size: 9px;}
    </style>
    <div class='accordion' id='menu_block_{$options[0]}'>";

   //不考慮多層cate 

  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_type`=1 and `menu_ofsn`={$options[1]} order by `menu_sort`";//die($sql);

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    //get得到第一層
    $block_body=get_ugm_page_b_menu_t1_accordion_body($menu_sn);
    if(empty($block_body)){
      //沒有選單
      $block.="
        <div class='accordion-group'>
          <div class='accordion-heading'>
            <a class='accordion-toggle' data-toggle='collapse' data-parent='#menu_block_{$options[0]}' href='#menu_sn_{$menu_sn}'>{$menu_title}</a>
          </div>
        </div>
      " ;
    }else{
      //有選單
      $block.="
        <div class='accordion-group'>
          <div class='accordion-heading'>
            <a class='accordion-toggle collapsed' data-toggle='collapse' data-parent='#menu_block_{$options[0]}' href='#menu_sn_{$menu_sn}'><i class='icon-chevron-right'></i>{$menu_title}</a>
          </div>
          <div id='menu_sn_{$menu_sn}' class='accordion-body collapse'>
            <div class='accordion-inner'>
              <ul>
              {$block_body}
              </ul>
            </div>
          </div>
        </div>";
    }
	}
  $block.="</div>";

	return $block;
}
//".chk($options[2],"shadow",0,"selected")."
//區塊編輯函式
function ugm_page_b_menu_t1_accordion_edit($options){

  $options[0]=$_REQUEST["bid"];//ID
  $options[1]=(empty($options[1]))?"":$options[1];//選擇類別
  //0.ID 1.文章數 2.寬度 
  $form="
  <table style='width:auto;'> 
    <tr><th>1</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12 readonly> "._MB_UGMPAGE_B_CATE_OP0."</td></tr>
	  <tr><th>2.</th><th>"._MB_UGMPAGE_B_MENU_T1_OP4."</th><td><select name='options[1]' size=1>
      ".get_ugm_page_b_menu_t1_accordion_cate(1,0,$options[1])."
      </select></td></tr>
  </table>
  ";
  //ugm_page_b_menu_t1_accordion
	return $form;
}

if(!function_exists("get_ugm_page_b_menu_t1_accordion_cate")){
function get_ugm_page_b_menu_t1_accordion_cate($menu_type=0,$menu_ofsn=0,$menu_sn_chk=0){
  global $xoopsModule,$xoopsDB;
  $sql = "select `menu_sn`,`menu_title` from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='{$menu_type}' and `menu_ofsn`='{$menu_ofsn}' and `menu_enable`='1' order by `menu_sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
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
#  
#
#
###############################################################################
if(!function_exists("get_ugm_page_b_menu_t1_accordion_body")){
function get_ugm_page_b_menu_t1_accordion_body($menu_ofsn=0){
  global $xoopsDB;
  //$url=XOOPS_URL."/modules/ugm_page/index.php?msn=";
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='1' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $main="";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    $main.="<li><a href='{$menu_url}'{$target}>{$menu_title}</a></li>";
  }
  return $main;
}
}
?>