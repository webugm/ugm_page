<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// 伸縮選單區塊type=1
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_b_menu_t1_2($options){
	global $xoopsDB;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID  
  $options[1]=(empty($options[1]))?5:$options[1];//文章數
  $options[2]=(empty($options[2]))?180:$options[2];//寬度
  $options[3]=(empty($options[3]))?"click":$options[3];//滑鼠點擊樣式 'click', or 'clickgo' or 'mouseover'
  $path=XOOPS_URL."/modules/ugm_page/class/menu_t1_2";
		//--------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可 
  //-------------------------------------------------------------------
  $css="<link rel='stylesheet' type='text/css' href='{$path}/ddaccordion.css' />" ;
  $block="
    {$jquery_path}
    <script type='text/javascript' src='{$path}/ddaccordion.js'>
      /***********************************************
      * Accordion Content script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
      * Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
      * This notice must stay intact for legal use
      ***********************************************/
    </script>
    <script type='text/javascript'>
      //Initialize Arrow Side Menu:
      ddaccordion.init({
      	headerclass: 'menuheaders_{$options[0]}', //第1層->有連結
      	contentclass: 'menucontents_{$options[0]}', //第2層->ul
      	revealtype: '{$options[3]}', //Reveal content when user clicks or onmouseover the header? Valid value: 'click', or 'clickgo' or 'mouseover'
      	mouseoverdelay: 200, //if revealtype='mouseover', set delay in milliseconds before header expands onMouseover
      	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
      	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
      	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
      	animatedefault: false, //Should contents open by default be animated into view?
      	persiststate: true, //persist state of opened contents within browser session?
      	toggleclass: ['unselected', 'selected'], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ['class1', 'class2']
      	togglehtml: ['none', '', ''], //Additional HTML added to the header when it's collapsed and expanded, respectively  ['position', 'html1', 'html2'] (see docs)
      	animatespeed: 500, //speed of animation: integer in milliseconds (ie: 200), or keywords 'fast', 'normal', or 'slow'
      	oninit:function(expandedindices){ //custom code to run when headers have initalized
    		//do nothing
    	  },
    	  onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    		//do nothing
    	}
    })
    </script>
    {$css}
    <div class='ugm_page_menu_t1_2' style='width: {$options[2]}px;'>
    ";
   //不考慮多層cate 
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_type`=1 and `menu_ofsn`={$options[4]} order by `menu_sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    //get得到第一層
    $block_body=get_b_menu_t1_2_body($menu_sn,$options[1]);
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    if(empty($block_body)){
      //沒有選單
      $block.="<div><a href='{$menu_url}'{$target}>{$menu_title}</a></div>" ;
    }else{
      //有文章
      $block.="
        <div class='menuheaders_{$options[0]}'><a href='{$menu_url}'{$target}>{$menu_title}</a></div>
          <ul class='menucontents_{$options[0]}'>
             {$block_body}
          </ul>
      ";
    }
	}
  $block.="</div>";
	return $block;
}
//".chk($options[2],"shadow",0,"selected")."
//區塊編輯函式
function ugm_page_b_menu_t1_2_edit($options){
  $options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID  
  $options[1]=(empty($options[1]))?0:$options[1];//選單數 ;「0」為不限制
  $options[2]=(empty($options[2]))?180:$options[2];//寬度
  $options[3]=(empty($options[3]))?"click":$options[3];//滑鼠點擊樣式 'click', or 'clickgo' or 'mouseover'
  $options[4]=(empty($options[4]))?"":$options[4];//選擇類別
  //0.ID 1.文章數 2.寬度 
  $form="
  <table style='width:auto;'> 
    <tr><th>1</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> "._MB_UGMPAGE_B_CATE_OP0."</td></tr>
    <tr><th>2.</th><th>"._MB_UGMPAGE_B_MENU_T1_OP1."</th><td><INPUT type='text' name='options[1]' value='{$options[1]}' size=3> "._MB_UGMPAGE_B_MENU_T1_OP1_R."</td></tr>
	  <tr><th>3.</th><th>"._MB_UGMPAGE_B_CATE_OP2."</th><td><INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px</td></tr>
	  <tr><th>4.</th><th>"._MB_UGMPAGE_B_CATE_OP3."</th><td><select name='options[3]' size=1>
      <option value='click' ".chk($options[3],"click",0,"selected").">click</option>
      <option value='clickgo' ".chk($options[3],"clickgo",0,"selected").">clickgo</option>
      <option value='mouseover' ".chk($options[3],"mouseover",0,"selected").">mouseover</option>
      </select></td></tr>
	  <tr><th>5.</th><th>"._MB_UGMPAGE_B_MENU_T1_OP4."</th><td><select name='options[4]' size=1>
      ".get_ugmpage_menu_t1_2_cate(1,0,$options[4])."
      </select></td></tr>
  </table>
  ";
	return $form;
}

if(!function_exists("get_ugmpage_menu_t1_2_cate")){
function get_ugmpage_menu_t1_2_cate($menu_type=0,$menu_ofsn=0,$menu_sn_chk=0){
  global $xoopsModule,$xoopsDB;
  $sql = "select `menu_sn`,`menu_title` from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='{$menu_type}' and `menu_ofsn`='{$menu_ofsn}' and `menu_enable`='1' order by `menu_sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  while(list($menu_sn,$menu_title)=$xoopsDB->fetchRow($result)){
    $main.="
      <option value='{$menu_sn}' ".chk($menu_sn_chk,$menu_sn," selected").">{$menu_title}</option>
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
if(!function_exists("get_b_menu_t1_2_body")){
function get_b_menu_t1_2_body($menu_ofsn=0,$counter=0){
  global $xoopsDB;
  //$url=XOOPS_URL."/modules/ugm_page/index.php?msn=";
  $counter=intval($counter);
  $counter=($counter==0)?"":" limit {$counter}" ;
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='1' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` {$counter}";
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