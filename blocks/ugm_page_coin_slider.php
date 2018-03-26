<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$     "|0|565|290||date|on|on|corner"
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_coin_slider($options){
	global $xoopsDB;
	if(empty($options[4])) return;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.文章數
  $options[2]=(empty($options[2]))?565:$options[2];             # 3.寬度
  $options[3]=(empty($options[3]))?290:$options[3];             # 4.高度
  $options[4]=(empty($options[4]))?0:$options[4];               # 5.選擇出現類別(單選) 
  $options[5]=(empty($options[5]))?"date":$options[5];          # 6.排序方式(單選)
  $options[6]=(empty($options[6]))?"on":$options[6];            # 7.導航開關(單選)
  $options[7]=(empty($options[7]))?"on":$options[7];            # 8.文字說明(單選)
  $options[8]=(empty($options[8]))?"corner":$options[8];        # 9.外框 1.空 2.corner 3.shadow
  $path=XOOPS_URL."/modules/ugm_page";
  $img_path=XOOPS_URL."/uploads/ugm_page/image";
	# --------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可 
  
  # ----  導航開關   --------------------------------------------------
    $navigation=($options[6]=="on")?"":" ,navigation: false";    
  # -------------------------------------------------------------------
  # head
  $block="
    {$jquery_path}
    <script type='text/javascript' src='{$path}/class/coin_slider/coin-slider.js'></script>
    <link rel='stylesheet' type='text/css' href='{$path}/class/coin_slider/coin-slider-styles.css' />
    <style>
    .cs-title { width: {$options[2]}px; padding: 10px; background-color: #000000; color: #FFFFFF; }
    </style>
    <!-- 控制寬度及背景-->
    <div style='width:{$options[2]}px;background-image: none;background-color:transparent;'>
    <!-- id-->
		<div id='coin_slider_{$options[0]}'>
		";
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_main")." where `enable`=1 and `csn`='{$options[4]}'order by `{$options[5]}` desc";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
  //$total=$xoopsDB->getRowsNum($result); #記錄筆數
  //if($total==0)return;
  
  $block_body="";
  
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `msn`, `csn`, `title`, `content`, `start_time`, `end_time`, `enable`, `counter`, `top`, `date`, `uid`, `sort`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $sql_1 = "select `file_name`,`description`  from ".$xoopsDB->prefix("ugm_page_files_center")." where `col_sn`='{$msn}' and `col_name`='slider_pic'";
    
	  $result_1 = $xoopsDB->query($sql_1) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
    list($file_name,$description)=$xoopsDB->fetchRow($result_1);
   
    if(!empty($file_name)){
       $block_body.="
        <a href='{$path}/index.php?msn={$msn}&csn={$csn}'>
  				<img src='{$img_path}/{$file_name}' alt='' />
      ";
    
      if($options[7]=="on" and !empty($description)){
        $block_body.="
          <span>
  					<b>{$title}</b><br />
  					".nl2br($description)."
  				</span>
  		 </a>
        ";
      }else{
        $block_body.="</a>";
      }
    }   
  }
  if (empty($block_body)) return;
  $block=$block.$block_body;
  
 # foot 
 $block.="
    </div>
    </div>
    <script>
      $(document).ready(function() {
    	   $('#coin_slider_{$options[0]}').coinslider({ hoverPause: true,width: {$options[2]},height: {$options[3]} {$navigation} });
      });
    </script>
  ";  
  
  # ------------外框 -------------------------------------------  
  if($options[8]=="corner"){
    $block=block_ugm_div("",$block,"",$options[2]+20);  
  }elseif($options[8]=="shadow"){
    $block=block_ugm_div("",$block,"shadow",$options[2]+36);
  }  
  # ------------------------------------------------------------   
  
	return $block;
}
//".chk($options[2],"shadow",0,"selected")."
//區塊編輯函式
function ugm_page_coin_slider_edit($options){
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.文章數
  $options[2]=(empty($options[2]))?565:$options[2];             # 3.寬度
  $options[3]=(empty($options[3]))?290:$options[3];             # 4.高度
  $options[4]=(empty($options[4]))?0:$options[4];               # 5.選擇出現類別(單選) 
  $options[5]=(empty($options[5]))?"date":$options[5];          # 6.排序方式(單選)
  $options[6]=(empty($options[6]))?"on":$options[6];            # 7.導航開關(單選)
  $options[7]=(empty($options[7]))?"on":$options[7];            # 8.文字說明(單選)
  $options[8]=(empty($options[8]))?"corner":$options[8];        # 9. 外框 1.空 2.corner 3.shadow
  $get_cate_coin_slider=get_cate_coin_slider($options[4]);      # 得到類別
  $form="
  <table style='width:auto;'> 
    <tr><th>1.</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> "._MB_UGMPAGE_B_CATE_OP0."</td></tr>
    <tr><th>2.</th><th>"._MB_UGMPAGE_B_CATE_OP1."</th><td><INPUT type='text' name='options[1]' value='{$options[1]}' size=3>"._MB_UGMPAGE_B_CATE_OP1_PS."</td></tr>
	  <tr><th>3.</th><th>"._MB_UGMPAGE_B_CATE_OP2."</th><td><INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px</td></tr>
	  <tr><th>4.</th><th>"._MB_UGMPAGE_COIN_SLIDER_HEIGHT."</th><td><INPUT type='text' name='options[3]' value='{$options[3]}' size=3> px</td></tr>
	  <tr><th>5.</th><th>"._MB_UGMPAGE_B_CATE_OP4."</th><td>{$get_cate_coin_slider}</td></tr>
	  <tr><th>6.</th><th>"._MB_UGMPAGE_COIN_SLIDER_SORT_M."</th><td><input type='radio' name='options[5]' value='date' ".chk($options[5],"date",1).">"._MB_UGMPAGE_COIN_SLIDER_DATE."<input type='radio' name='options[5]' value='sort' ".chk($options[5],"sort",0).">"._MB_UGMPAGE_COIN_SLIDER_SORT."</td></tr>
	  <tr><th>7.</th><th>"._MB_UGMPAGE_COIN_SLIDER_NAVIGATION."</th><td><input type='radio' name='options[6]' value='on' ".chk($options[6],"on",1).">"._MB_UGMPAGE_COIN_SLIDER_ON."<input type='radio' name='options[6]' value='off' ".chk($options[6],"off",0).">"._MB_UGMPAGE_COIN_SLIDER_OFF."</td></tr>
	  <tr><th>8.</th><th>"._MB_UGMPAGE_COIN_SLIDER_DESC."</th><td><input type='radio' name='options[7]' value='on' ".chk($options[7],"on",1).">"._MB_UGMPAGE_COIN_SLIDER_ON."<input type='radio' name='options[7]' value='off' ".chk($options[7],"off",0).">"._MB_UGMPAGE_COIN_SLIDER_OFF."</td></tr>
	  <tr><th>9.</th><th>"._MB_UGMPAGE_SHOW_PAGE_OP2."</th><td><select name='options[8]' size=1>
    <option value='no' ".chk($options[8],"no",0,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_NO."</option>
    <option value='corner' ".chk($options[8],"corner",1,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_CORNER."</option>
    <option value='shadow' ".chk($options[8],"shadow",0,"selected").">"._MB_UGMPAGE_SHOW_PAGE_OP2_SHADOW."</option>
    </select></td></tr>
  </table>
  ";
	return $form;
}
 
###############################################################################
#  得到類別選單(單選)
#  
#
#
###############################################################################
if(!function_exists("get_cate_coin_slider")){
function get_cate_coin_slider($csn_chk){
  global $xoopsDB;
  //不考慮多層cate 
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_cate")." where `enable`=1  order by `sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `csn`, `of_csn`, `title`, `sort`, `enable`, `type`
    foreach($all as $k=>$v){
      $$k=$v;
    }    
    $checkbox_cate.="<input type='radio' name='options[4]' value='{$csn}' ".chk($csn_chk,$csn)."><label>{$title}</label>";     
  }
  return  $checkbox_cate;
}
}
  
?>