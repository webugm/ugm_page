<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_b_cate3($options){
	global $xoopsDB;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID  
  $options[1]=(empty($options[1]))?0:$options[1];//文章數
  $options[2]=(empty($options[2]))?180:$options[2];//寬度
  $options[3]=(empty($options[3]))?"click":$options[3];//滑鼠點擊樣式 'click', or 'clickgo' or 'mouseover' 
  $options[4]=(empty($options[4]))?"":$options[4]; # 選擇出現類別(複選)
  $options[5]=(empty($options[5]))?"date":$options[5];  # 排序方式(單選)
  $path=XOOPS_URL."/modules/ugm_page";
  //$path=XOOPS_URL."/modules/ugm_page/class/page_b_cate3";
		//--------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可 
  //-------------------------------------------------------------------
  $css="<link rel='stylesheet' type='text/css' href='{$path}/class/page_b_cate3/ddaccordion.css' />" ;
  $block="
    {$jquery_path}
    <script type='text/javascript' src='{$path}/class/page_b_cate3/ddaccordion.js'>
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
    <div class='page_b_cate3' style='width: {$options[2]}px;'>
    ";
   //不考慮多層cate 
  $and_key="";
  $and_or=0;
  if(!empty($options[4])){
    $options[4]=explode(",",$options[4]);
    foreach($options[4] as $v){
      if($and_or==0){
        $and_key.="and (`csn`='{$v}' ";
      }else{
      
        $and_key.="or `csn`='{$v}' ";
      }
    
      $and_or=1;
    }
    $and_key.=")";
  
  }  
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_cate")." where `enable`=1 {$and_key} order by `sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `csn`, `of_csn`, `title`, `sort`, `enable`, `type`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    //get文章
    $block_body=get_main_body3($csn,$options[1],$options[5]);
    if(empty($block_body)){
      //沒有文章
      $block.="<div><a href='#'>{$title}</a></div>" ;
    }else{
      //有文章
      $block.="
        <div class='menuheaders_{$options[0]}'><a href='{$path}/index.php?op=show_cate_ugm_page&csn={$csn}'>{$title}</a></div>
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
function ugm_page_b_cate3_edit($options){
  $options[0]=(empty($options[0]))?strtotime("now"):$options[0];//ID  
  $options[1]=(empty($options[1]))?0:$options[1];//文章數
  $options[2]=(empty($options[2]))?180:$options[2];//寬度
  $options[3]=(empty($options[3]))?"click":$options[3];//滑鼠點擊樣式 'click', or 'clickgo' or 'mouseover' 
  $options[4]=(empty($options[4]))?"":$options[4]; # 選擇出現類別(複選)
  $get_ugm_page_b_cate=get_ugm_page_b_cate($options[4]);# 得到類別
  $options[5]=(empty($options[5]))?"date":$options[5];  # 排序方式(單選)
  //0.ID 1.文章數 2.寬度 
  $form="
  <table style='width:auto;'> 
    <tr><th>1</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> "._MB_UGMPAGE_B_CATE_OP0."</td></tr>
    <tr><th>2.</th><th>"._MB_UGMPAGE_B_CATE_OP1."</th><td><INPUT type='text' name='options[1]' value='{$options[1]}' size=3>"._MB_UGMPAGE_B_CATE_OP1_PS."</td></tr>
	  <tr><th>3.</th><th>"._MB_UGMPAGE_B_CATE_OP2."</th><td><INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px</td></tr>
	  <tr><th>4.</th><th>"._MB_UGMPAGE_B_CATE_OP3."</th><td><select name='options[3]' size=1>
      <option value='click' ".chk($options[3],"click",0,"selected").">click</option>
      <option value='clickgo' ".chk($options[3],"clickgo",0,"selected").">clickgo</option>
      <option value='mouseover' ".chk($options[3],"mouseover",0,"selected").">mouseover</option>
      </select></td></tr>
	  <tr><th>5.</th><th>"._MB_UGMPAGE_B_CATE_OP4."</th><td>{$get_ugm_page_b_cate}
      </td></tr>
	  <tr><th>6.</th><th>排序方式</th><td><input type='radio' name='options[5]' value='date' ".chk($options[5],"date",1).">日期 <input type='radio' name='options[5]' value='sort' ".chk($options[5],"sort",0).">排序</td></tr>
  </table>
  ";
	return $form;
}
 
###############################################################################
#  得到類別選單(複選)
#  
#
#
###############################################################################
if(!function_exists("get_ugm_page_b_cate")){
function get_ugm_page_b_cate($csns=array()){
  global $xoopsDB;
  $csns=explode(",",$csns);   
  //不考慮多層cate 
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_cate")." where `enable`=1  order by `sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `csn`, `of_csn`, `title`, `sort`, `enable`, `type`
    foreach($all as $k=>$v){
      $$k=$v;
    }    
    $checkbox_cate.="<input type='checkbox' name='options[4][]' value='{$csn}' ".chk2($csns,$csn)."><label>{$title}</label>";     
  }
  return  $checkbox_cate;
}
}
###############################################################################
#  得到文章標題 
#  
#
#
###############################################################################
if(!function_exists("get_main_body3")){
function get_main_body3($csn=0,$counter=0,$sort){
  global $xoopsDB;
  $url=XOOPS_URL."/modules/ugm_page/index.php?msn=";
  $counter=intval($counter);
  $counter=($counter==0)?"":" limit {$counter}" ;
  $sort=($sort=="sort")?"sort":"date";
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_main")." where `csn`={$csn} and `enable`=1 order by `{$sort}` desc {$counter}";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $main="";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `msn`, `csn`, `title`, `content`, `start_time`, `end_time`, `enable`, `counter`, `top`, `date`, `uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="<li><a href='{$url}{$msn}&csn={$csn}'>{$title}</a></li>";
  }
  return $main;
}
}
?>