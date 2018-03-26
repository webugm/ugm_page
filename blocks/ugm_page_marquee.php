<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$     "
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php";
############################################################################
#  跑馬燈區塊主函式
#  ugm_page_marquee
#  "||corner|0|180|26|small|16|black|red|transparent"
#
#
############################################################################
function ugm_page_marquee($options){
	global $xoopsDB;
	if(empty($options[1])) return;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.選擇出現類別(單選)    
  $options[2]=(empty($options[2]))?"corner":$options[2];        # 3.外框 1.空 2.corner 3.shadow
  $options[3]=(empty($options[3]))?0:$options[3];               # 4.文章數
  $options[4]=(empty($options[4]))?180:$options[4];             # 5.寬度
  $options[5]=(empty($options[5]))?26:$options[5];              # 6.高度  
  $options[6]=(empty($options[6]))?"small":$options[6];         # 7.排序方式(單選)小->大 大->小
  $options[7]=(empty($options[7]))?16:$options[7];              # 8.字型大小
  $options[8]=(empty($options[8]))?"black":$options[8];         # 9.字型顏色
  $options[9]=(empty($options[9]))?"red":$options[9];           # 10.hove 顏色
  $options[10]=(empty($options[10]))?"transparent":$options[10];# 11.背景顏色
  
  $path=XOOPS_URL."/modules/ugm_page";
  $img_path=XOOPS_URL."/uploads/ugm_page/image";
	# --------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
  # --------------------- 排序 --------------------------------------------   
  $options[6]=($options[6]=="big")?" order by `menu_sort` desc":" order by `menu_sort` ";
  # --------------------- 數量 --------------------------------------------   
  $options[3]=($options[3]=="0")?"":" limit {$options[3]} ";
  
  
  //$counter=($counter==0)?"":" limit {$counter}" ;
  # ------------------------  head  ---------------------------------------
    $block="
  	{$jquery_path}
  	<script type='text/javascript' src='{$path}/class/marquee/jquery.marquee.js'></script>  	
  	<style type='text/css'>
  	ul.marquee {
    	/* required styles */
    	display: block;
    	padding: 0;
    	margin: 0;
    	list-style: none;
    	line-height: 1;
    	position: relative;
    	overflow: hidden;
    
    	/* optional styles for appearance */
    	width: {$options[4]}px;
    	height: {$options[5]}px; /* height should be included to reserve visual space for the marquee */   
    	background-color: {$options[10]}; /*背景顏色#f2f2ff*/
    	/* border: 1px solid #08084d; /*框線*/  
    }     
    ul.marquee li {
    	/* required styles */
    	position: absolute;
    	top: -999em;
    	left: 0;
    	display: block;
    	white-space: nowrap; /* keep all text on a single line */     
    	/* optional styles for appearance */
    	font: {$options[7]}px Arial, Helvetica, sans-serif;
    	padding: 3px 5px;      
    }
    
    ul.marquee li a {
      color: {$options[8]}; /* 字型顏色 */
      text-decoration: none  
    }
    ul.marquee li a:visited {
      color: {$options[8]}; /* 滑過顏色 */
      text-decoration: none  
    }
    ul.marquee li a:hover {
      color: {$options[9]}; /* 滑過顏色 */
      text-decoration: none  
    }   
  	</style>
  
    <script type='text/javascript'>
    $(document).ready(function (){
      //$('#marquee_{$options[0]}').marquee();
      $('#marquee_{$options[0]}').marquee({yScroll: 'bottom'});
    });
    </script>
    <div class='examples'>
      <ul id='marquee_{$options[0]}' class='marquee'>";  
  
  # ------------------- body --------------------------------------------------------
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_ofsn`='{$options[1]}' {$options[6]}$options[3]";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
  //$total=$xoopsDB->getRowsNum($result); #記錄筆數
  //if($total==0)return;
  
  $block_body="";
  
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $target=($menu_new==1)?" target='_brank'":"";
    $block_body.="<li><a href='{$menu_url}' {$target}>{$menu_title}</a></li>";      
     
  }
  if (empty($block_body)) return;
  $block.=$block_body;
  
  # ---------footer -----------------------------------------------------------------
  $block.="</ul></div>";
  
  # ------------外框 -------------------------------------------  
  if($options[2]=="corner"){
    $block=block_ugm_div("",$block,"",$options[4]+20);  
  }elseif($options[2]=="shadow"){
    $block=block_ugm_div("",$block,"shadow",$options[4]+36);
  }
  
	return $block;
}
############################################################################
#  跑馬燈編輯函數
#  ugm_page_marquee_edit
#
#
#
############################################################################
function ugm_page_marquee_edit($options){
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.選擇出現類別(單選)    
  $options[2]=(empty($options[2]))?"corner":$options[2];        # 3.外框 1.空 2.corner 3.shadow
  $options[3]=(empty($options[3]))?0:$options[3];               # 4.文章數
  $options[4]=(empty($options[4]))?180:$options[4];             # 5.寬度
  $options[5]=(empty($options[5]))?26:$options[5];              # 6.高度  
  $options[6]=(empty($options[6]))?"small":$options[6];         # 7.排序方式(單選)小->大 大->小
  $options[7]=(empty($options[7]))?16:$options[7];              # 8.字型大小
  $options[8]=(empty($options[8]))?"black":$options[8];         # 9.字型顏色
  $options[9]=(empty($options[9]))?"red":$options[9];           # 10.hove 顏色
  $options[10]=(empty($options[10]))?"transparent":$options[10];# 11.背景顏色
  # 得到類別
  $get_cate_marquee=get_cate_marquee($options[1]);
  # 編輯表單
  $form="
  <table style='width:auto;'> 
     <tr><th>1.</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> <p>"._MB_UGMPAGE_B_ID_PS."</p></td></tr>
     <tr><th>2.</th><th>"._MB_UGMPAGE_B_CATE."</th><td>{$get_cate_marquee}</td></tr>
     <tr><th>3.</th><th>"._MB_UGMPAGE_B_BORDER."</th><td><select name='options[2]' size=1>
       <option value='no' ".chk($options[2],"no",0,"selected").">"._MB_UGMPAGE_B_NO_BORDER."</option>
       <option value='corner' ".chk($options[2],"corner",1,"selected").">"._MB_UGMPAGE_B_CORNER_BORDER."</option>
       <option value='shadow' ".chk($options[2],"shadow",0,"selected").">"._MB_UGMPAGE_B_SHADOW_BORDER."</option>
       </select></td>
     </tr>   
     <tr><th>4.</th><th>"._MB_UGMPAGE_B_COUNT."</th><td><INPUT type='text' name='options[3]' value='{$options[3]}' size=3><p>"._MB_UGMPAGE_B_COUNT_PS."</p></td></tr>     
     <tr><th>5.</th><th>"._MB_UGMPAGE_B_WIDTH."</th><td><INPUT type='text' name='options[4]' value='{$options[4]}' size=3> px</td></tr>
	   <tr><th>6.</th><th>"._MB_UGMPAGE_B_HEIGHT."</th><td><INPUT type='text' name='options[5]' value='{$options[5]}' size=3> px</td></tr>  
     <tr><th>7.</th><th>"._MB_UGMPAGE_B_SORT."</th><td><input type='radio' name='options[6]' value='small' ".chk($options[6],"small",1).">"._MB_UGMPAGE_B_SORT_SMALL."<input type='radio' name='options[6]' value='big' ".chk($options[6],"big",0).">"._MB_UGMPAGE_B_SORT_BIG."</td></tr>     
     <tr><th>8.</th><th>"._MB_UGMPAGE_B_FONTSIZE."</th><td><INPUT type='text' name='options[7]' value='{$options[7]}' size=3> px</td></tr>     
     <tr><th>9.</th><th>"._MB_UGMPAGE_B_CORLOR."</th><td><INPUT type='text' name='options[8]' value='{$options[8]}' size=10></td></tr>     
     <tr><th>10.</th><th>"._MB_UGMPAGE_B_HOVE_CORLOR."</th><td><INPUT type='text' name='options[9]' value='{$options[9]}' size=10></td></tr>     
     <tr><th>11.</th><th>"._MB_UGMPAGE_B_BACKGROUND_CORLOR."</th><td><INPUT type='text' name='options[10]' value='{$options[10]}' size=10></td></tr>  
  </table>
  ";
  
        
	return $form;
}
 
###############################################################################
#  得到類別選單(單選)
#  `menu_enable`=1 and `menu_type`=2 and `menu_ofsn`=0
#
#
###############################################################################
if(!function_exists("get_cate_marquee")){
function get_cate_marquee($csn_chk){
  global $xoopsDB;
  //不考慮多層cate 
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_type`=3 and `menu_ofsn`=0 order by `menu_sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }    
    $checkbox_cate.="<input type='radio' name='options[1]' value='{$menu_sn}' ".chk($csn_chk,$menu_sn)."><label>{$menu_title}</label>";     
  }
  return  $checkbox_cate;
}
}
  
?>