<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //
//引入TadTools的函式庫
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";
include_once "block_function.php";

/********************* 自訂函數 *********************/

##############################################################################
#  檢查是否有單獨文章編輯權限
###############################################################################
function edit_perm($msn){
  global $xoopsDB,$xoopsUser;
  if(!$xoopsUser or $msn==0)return;
  $sql = "select `editor` from ".$xoopsDB->prefix("ugm_page_main")." where `msn`='{$msn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  list($editor)=$xoopsDB->fetchRow($result);
  $editor=$editor?json_decode($editor):"";
  if($editor){
    $uid=$xoopsUser->getVar('uid');
    if(in_array($uid,$editor))return true;
  }
  return false;
}



/********************* 預設函數 *********************/
//圓角文字框
function div_3d($title="",$main="",$kind="raised",$style="",$other=""){
	$main="<table style='width:auto;{$style}'><tr><td>
	<div class='{$kind}'>
	<h1>$title</h1>
	$other
	<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
	<div class='boxcontent'>
 	$main
	</div>
	<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	</td></tr></table>";
	return $main;
}
/*********************UGM自訂函數 **************************/
###############################################################################
#  ugm_div($data,$corners)
#  圓角
#
#
###############################################################################
function ugm_div($title="",$data="",$corners=""){

  if($corners=="shadow"){
    $title=empty($title)?"":"<h2> {$title}</h2>";
    $main="
      <div class='Block1Border'><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1'>{$title}
            <div class='Block1ContentBorder'>{$data}
            </div>
        </div></div>
    ";
  }elseif($corners=="shadow1"){
    $title=empty($title)?"":"<h2> {$title}</h2>";
    $main="
      <div class='Block1Border'><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1' style='padding:3px 3px 3px 3px;'>{$title}
            <div class='Block1ContentBorder'>{$data}
            </div>
        </div></div>
    ";
  }else{
    $title=empty($title)?"":"<h2> {$title}</h2>";
    $main="<div class='BlockBorder'><div class='BlockBL'><div></div></div><div class='BlockBR'><div></div></div><div class='BlockTL'></div><div class='BlockTR'><div></div></div><div class='BlockT'></div><div class='BlockR'><div></div></div><div class='BlockB'><div></div></div><div class='BlockL'></div><div class='BlockC'></div><div class='Block'>\n
    {$data}\n
    </div></div>\n";
   //$main=empty($title)? $main:"<span class='title'>{$title}</span>".$main;
    $main=$title.$main;

  }

  return $main;
}
function ugm_style_css(){
  global $xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $main="
    <style>
      .ugm_tb{margin: 0;width: 100%;border: none;border-collapse: separate /*collapse*/;border-spacing: 1px;border-image: initial;}
      .ugm_tb input{font-size:16px;}
      .ugm_tb div{float : left}
      .ugm_tb th{background-image:none;background-color: #B5CBE6;padding:5px;font-size:16px;text-align:center;color: #039;}
      .ugm_tb td{padding:5px;font-size:12px}
      .ugm_tb span{color: Red;}
      .ugm_tb textarea{width:100%;}
      .ugm_tb td.bar{text-align:center;clear: both;}
      .ugm_tb td img{vertical-align: middle;}
      .ugm_tb td.align_c{text-align:center;}
      .ugm_tb .bar{width: 100%;text-align:center;background: #fff;margin-top: 6px;}
      .ugm_tb span.title{margin:0;padding:5px;width:98%;font-size:16px;text-align:center;color: #039;}



      .ugm_tb tr.level_0{background-color: #eed2c9;font-size:12px;}
      .ugm_tb tr.level_1,tr.level_3,tr.level_5{background-color: #eeeeee;font-size:12px;}
      .ugm_tb tr.level_2,tr.level_4,tr.level_6{background-color: #e0ffb2;font-size:12px;}
      .ugm_tb td.align_c{text-align:center;}
      .ugm_tb tr.oddalt{background-color: #eed2c9;font-size:12px;}
      .ugm_tb tr.alt{background-color: #eeeeee;font-size:12px;}
      .ugm_tb tr.over{background-color: #BDF5BF;font-size:12px;}
    </style>
  ";

  return $main;
}
//ugm_javascript
//
function ugm_javascript($op=0){
	//$op=0  $op=1 隔行變色
	global $xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $change_line=($op==0)?"":"
     $('.ugm_tb tr:odd').addClass('oddalt'); //給class為ugm_tb的表格的奇數行添加class值為oddalt
     $('.ugm_tb tr:even').addClass('alt'); //給class為ugm_tb的表格的偶數行添加class值為alt
	";
  $main="
    <script language='javascript'>
      $(function(){
      	$('li').find('ul').prev().click(function(){
      		$(this).next().toggle();
      	});
      	$('li:has(ul)').find('ul').hide();
      	//--------------table隔行變色--------------------------------
      	$('.ugm_tb tr').mouseover(function(){ //如果鼠標移到class為ugm_tb的表格的tr上時，執行函數
        $(this).addClass('over');}).mouseout(function(){ //給這行添加class值為over，並且當鼠標一出該行時執行函數
        $(this).removeClass('over');}) //移除該行的class
        {$change_line}
      	//-----------------------------------------------------------
      });
    </script>
  ";
  return $main;
}

###############################################################################
#  資料過瀘
#
#
#  SqlFilter($Variable='',"trim,addslashes,strip_tags,htmlspecialchars,intval")
###############################################################################
function SqlFilter($Variable='',$method='text'){

  if(empty($Variable))return '';
  $methods = explode(",", $method);
  foreach($methods as $method){
    switch($method){
  	//去除前後空白
    case "trim":
  	  $Variable=trim($Variable);
  	break;
  	//特殊字符轉義
  	case "addslashes":
  	  $Variable=(! get_magic_quotes_gpc()) ? addslashes($Variable) : $Variable;
  	break;
  	//去除html、php標籤
  	case "strip_tags":
  	  $Variable=strip_tags($Variable);
  	break;
  	//轉換特殊字元成為HTML實體
  	case "htmlspecialchars":
  	  $Variable=htmlspecialchars($Variable);
  	break;
    case "intval":
  	  $Variable=intval($Variable);
  	break;
  	default:
  	break;
    }
  }
  return  $Variable ;
}
################################################################################
#-- 取得分類選項get_group_option
#-- 從of_csn=0開始
# 1.of_csn
# 2.of_csn_chk 標記類別
# 3.csn_chk    標記本身(編輯用)
# 4.stop_level 層數
# 5.level      遞回用
# 6.type      1.文章 2.佈景
################################################################################
function get_group_option($of_csn=0,$of_csn_chk=0,$csn_chk=0,$stop_level=2,$level=0,$type=0){
  global $xoopsDB;

  if($level>=$stop_level)return;
  $level++;
  $sql = "select `csn`,`title` from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `of_csn`='{$of_csn}' and `type`='{$type}'  order by `sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $i=0;
  $level_mark="";
  while ($level-$i>0){
    $level_mark.="- ";
    $i++;
  }
  while($all=$xoopsDB->fetchArray($result) ){
  //以下會產生這些變數： 【$csn,$title】
    foreach($all as $k=>$v){
      $$k=$v;
    }
    if($csn_chk!=0 and $csn==$csn_chk){
      //避開選擇自已，於form
    }else{
      $selected=($csn==$of_csn_chk)?" selected=selected":"";
      $main.="<option value={$csn}{$selected}>{$level_mark}{$title}</option>";
    }
    $main.=get_group_option($csn,$of_csn_chk,$csn_chk,$stop_level,$level,$type);
  }

  return $main;
}
#########################################################################
#得到類別路徑
#
#########################################################################
function get_group_direct($of_csn_chk=0,$level=0){
  global $xoopsDB,$xoopsModule;
  if($of_csn_chk==0)return;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $DBV=get_sn_one("select `of_csn`,`csn`,`title` from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `csn`='{$of_csn_chk}' ");
  if($level==0){
    //第一次且最後一層不須連結
    $group_direct="<span style='color:red;margin-right: 10px;'> / {$DBV['title']}</span>";
  }else{
    $group_direct="<a href='{$_SERVER['PHP_SELF']}?of_csn={$DBV['csn']}' class='Button'><span>{$DBV['title']}</span></a>
    ";
  }
  if($DBV['of_csn']!=0){
    //不是最上層
    $level++;
    $group_direct=get_group_direct($DBV['of_csn'],$level).$group_direct;
  }
  return $group_direct;
 }

################################################################################
#   以條件取得單筆[資料庫]資料
#
#
#
#
################################################################################
//--------------------------  ---------------------
function get_sn_one($sql){
	global $xoopsDB;
  //$sql = "select * from ".$xoopsDB->prefix("u_menu")." where class_sn='$class_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	return $xoopsDB->fetchArray($result);
}
//--------------------ugm end-------------------------
################################################################################
#  收合js
#
#
#
###############################################################################

if(!function_exists("ddaccordion")){
function ddaccordion(){
  global $xoopsModule;
  # ---- 取得模組目錄名稱 ---------------
  $MDIR=$xoopsModule->getVar('dirname');
  # -------------------------------------
  $main="
    <script type='text/javascript' src='".XOOPS_URL."/modules/{$MDIR}/class/ddaccordion/ddaccordion.js'>
    /***********************************************
    * Accordion Content script- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com/dynamicindex17/ddaccordionmenu-glossy.htm)
    * Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
    * This notice must stay intact for legal use
    ***********************************************/
    </script>
    <script type='text/javascript'>
    ddaccordion.init({
    	headerclass: 'submenuheader', //Shared CSS class name of headers group
    	contentclass: 'submenu', //Shared CSS class name of contents group
    	revealtype: 'click', //顯示內容的方法， Valid value: 'click', 'clickgo', or 'mouseover'
    	mouseoverdelay: 200, //if revealtype='mouseover', set delay in milliseconds before header expands onMouseover
    	collapseprev: true, //是否只展開一個? true/false
    	defaultexpanded: [0], //展開第幾個，不填則不展開，index of content(s) open by default [index1, index2, etc] [] denotes no content
    	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
    	animatedefault: false, //Should contents open by default be animated into view?
    	persiststate: true, //persist state of opened contents within browser session?
    	toggleclass: ['', ''], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ['class1', 'class2']
    	togglehtml: ['suffix', \"<img src='".XOOPS_URL."/modules/{$MDIR}/class/ddaccordion/plus.gif' class='statusicon' />\", \"<img src='".XOOPS_URL."/modules/{$MDIR}/class/ddaccordion/minus.gif' class='statusicon' />\"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ['position', 'html1', 'html2'] (see docs)
    	animatespeed: 'fast', //speed of animation: integer in milliseconds (ie: 200), or keywords 'fast', 'normal', or 'slow'
    	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
    		//do nothing
    	},
    	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    		//do nothing
    	}
    })
    </script>
    <style type='text/css'>
    .glossymenu{
    margin: 5px 0;
    padding: 0;
    width: 100%; /*width of menu*/
    border: 1px solid #9A9A9A;
    border-bottom-width: 0;
    }

    .glossymenu a.menuitem{
    background: black url(".XOOPS_URL."/modules/{$MDIR}/class/ddaccordion/glossyback.gif) repeat-x bottom left;
    font: bold 14px 'Lucida Grande', 'Trebuchet MS', Verdana, Helvetica, sans-serif;
    color: white;
    display: block;
    position: relative; /*To help in the anchoring of the '.statusicon' icon image*/
    width: auto;
    padding: 4px 0;
    padding-left: 20px;
    text-decoration: none;
    }


    .glossymenu a.menuitem:visited, .glossymenu .menuitem:active{
    color: white;
    }

    .glossymenu a.menuitem .statusicon{ /*CSS for icon image that gets dynamically added to headers*/
    position: absolute;
    top: 5px;
    left: 5px;
    border: none;
    }

    .glossymenu a.menuitem:hover{
    background-image: url(".XOOPS_URL."/modules/{$MDIR}/class/ddaccordion/glossyback2.gif);
    }

    .glossymenu div.submenu{ /*DIV that contains each sub menu*/
    background: white;
    }

    .glossymenu div.submenu ul{ /*UL of each sub menu*/
    list-style-type: none;
    margin: 0;
    padding: 0;
    }

    .glossymenu div.submenu ul li{
    border-bottom: 1px solid blue;
    }

    .glossymenu div.submenu ul li a{
    display: block;
    font: normal 13px 'Lucida Grande', 'Trebuchet MS', Verdana, Helvetica, sans-serif;
    color: black;
    text-decoration: none;
    padding: 2px 0;
    padding-left: 10px;
    }

    .glossymenu div.submenu ul li a:hover{
    background: #DFDCCB;
    colorz: white;
    }

    </style>
  ";
  return $main;
}
}
?>
