<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2008-06-25
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //
/*
define("_MI_UGMPAGE_ADMENU1", " ");
define("_BP_HOME", " 根目錄");
define("_BP_ADD_CATE", " 新增分類");
define("_BP_RESET_SORT", " 重新排序");
define("_BP_REQUIRED", "必填");
define("_MA_UGMPAGE_CATE_TITLE", "分類標題");
define("_MA_UGMPAGE_CATE_ENABLE", "啟用");
define("_MA_UGMPAGE_CATE_SORT", "排序");
define("_MA_UGMPAGE_CATE_OFCSN", "父層分類");
define("_MA_UGMPAGE_CATE_FORM", "分類表單");
*/
/*-----------引入檔案區--------------*/
include_once "header_admin.php";
/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];
$csn = (!isset($_REQUEST['csn']))? 0:intval($_REQUEST['csn']);
$of_csn = (!isset($_REQUEST['of_csn']))? 0:intval($_REQUEST['of_csn']);


define("_STOP_LEVEL",$xoopsModuleConfig['cate_level']);
define("_DATA_TABLE","ugm_page_cate");
//
switch($op){
	case "op_form":
	  $main=op_form($csn,$of_csn);
	break;
	case "op_list":
	  $main=op_list();
	break;
	case "op_insert": //新增、編輯
    op_insert($csn);
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;
	case "op_update_enable": //更新啟用
    op_update_enable($csn);
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;
	case "op_update_sort": //更新排序
    op_update_sort();
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;
	case "op_del":
	  op_del($csn);
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_DEL_SUCCESS);
	break;
	default:
	  $main=op_list($of_csn,$csn);
	break;

	/*---判斷動作請貼在上方---*/
}


/*-----------秀出結果區--------------*/
module_admin_footer($main,0);
/*-----------function區--------------*/
//模組頁尾
function module_admin_footer($main="",$n=""){
  xoops_cp_header();
  admin_toolbar($n);
  echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
  echo $main;
  xoops_cp_footer();
}



###############################################################################
#  op_list列表
#
#
###############################################################################
function op_list($of_csn=0,$csn=0){
  global $xoopsDB,$xoopsModule;
	$DIRNAME=$xoopsModule->getVar('dirname');
	$main=ugm_javascript(0);
  $stop_level=_STOP_LEVEL;//結束層
  $level=0;
  $selected=($of_csn==0)?" selected=selected":"";
  $group_direct_head="<a href='{$_SERVER['PHP_SELF']}?of_csn=0' class='Button' ><span><img src='../images/home14.png' />"._BP_HOME."</span></a>"; //路徑選單的頭
  $group_direct=($of_csn==0)?"":get_group_direct($of_csn);
  $main.="
    <script>
  	function delete_{$DIRNAME}_func(csn){
  		var sure = window.confirm('"._BP_DEL_CHK."');
  		if (!sure)	return;
  		location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&csn=\" + csn;
  	}
  	</script>
  	<div class='list_head'  >
      <form action='{$_SERVER['PHP_SELF']}' method='post' >
        <select name='of_csn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?of_csn=\"+this.value' >
          <option value='0'{$selected}></option>".get_group_option(0,$of_csn,0,$stop_level-1)."
        </select>
      </form>
    {$group_direct_head}{$group_direct}
    <a href='{$_SERVER['PHP_SELF']}?op=op_form&of_csn={$of_csn}' class='Button' ><span><img src='../images/i_new_cate.gif' />"._BP_ADD_CATE."</span></a>
    <a href='{$_SERVER['PHP_SELF']}?op=op_update_sort' class='Button' ><span><img src='../images/i_sort14.png' /> "._BP_RESET_SORT."</span></a>
    </div>
    <div id='{$DIRNAME}_table'>
  	  <table border='0' cellspacing='0' cellpadding='0' class='ugm_tb'>
      	<tr>
        	<th style='width:250px;'>"._MA_UGMPAGE_CATE_TITLE."</th>
        	<th style='width:40px;'>"._MA_UGMPAGE_CATE_ENABLE."</th>
          <th style='width:40px;'>"._MA_UGMPAGE_CATE_SORT."</th>
        	<th style='width:60px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($of_csn,$csn,$stop_level,$level);
  	$main.="</table></div>";
   $main=ugm_div(_MI_UGMPAGE_ADMENU4,$main);
   return $main;
}
################################################################################
#   表單身體
#   // get_list_body `csn`, `of_csn`, `title`, `sort`, `enable`
#
#
################################################################################
function get_list_body($of_csn=0,$csn_chk=0,$stop_level=2,$level=0){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  if($level>=$stop_level)return;
  //$level_mark=($level==0)?"":" style='text-indent:".(($level)*12)."pt;'";//縮排ugm_indent
  $level_mark=($level==0)?"":"class='ugm_indent'";
  $level_css=" class='level_{$level}'";
  $level++;
  //group_title	group_readme	image_counter
  $sql = "select * from ".$xoopsDB->prefix(_DATA_TABLE)." where `of_csn`={$of_csn} order by `sort` ";

  //***************PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);***/

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
  $total=$xoopsDB->getRowsNum($result);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $main="";
  while(list($csn,$of_csn,$title,$sort,$enable)=$xoopsDB->fetchRow($result) ){
	  $enable=($enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&csn={$csn}&of_csn={$csn_chk}&enable=0'><img src='../images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&csn={$csn}&of_csn={$csn_chk}&enable=1'><img src='../images/off.png' /></a>";//啟用
    $chk_level=chk_level($csn);
    $create_sub=($stop_level>$chk_level)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&of_csn={$csn}'><img src='../images/i_new_cate.gif' title='".sprintf(_MA_TAD_FORM_ADD_SUB,$title)."'></a> ":"";//是否可以新增
    $title=($stop_level>$chk_level)?"<a href='{$_SERVER['PHP_SELF']}?of_csn={$csn}'>{$title}</a>": $title;
    $main.="
      <tr{$level_css}>
        <td {$level_mark}>{$create_sub}{$title}</td>\n
        <td class='align_c'>{$enable}</td>\n
        <td class='align_c'>{$sort}</td>\n
        <td><a href='{$_SERVER['PHP_SELF']}?op=op_form&csn={$csn}&of_csn={$of_csn}'><img src='../images/i_edit.gif' /></a>&nbsp;&nbsp;<a href=\"javascript:delete_{$DIRNAME}_func($csn);\"><img src='../images/i_del.gif' /></a></td>\n

      	</tr>".get_list_body($csn,$csn_chk,$stop_level,$level);
  }
  return $main ;
}






###############################################################################
#  op_insert新增、編輯
#
#
#
###############################################################################
function op_insert($csn=0){
  global $xoopsDB,$xoopsUser,$xoopsModule;
  if(!$xoopsUser)return;
  $uid=$xoopsUser->getVar('uid');
  $DIRNAME=$xoopsModule->getVar('dirname');
  /***************************** 過瀘資料 *************************/

  $of_csn=intval($_POST['of_csn']);
  $title=SqlFilter($_POST['title'],"trim,addslashes,strip_tags");
  $enable=intval($_POST['enable']);
  $sort=intval($_POST['sort']);
  /****************************************************************/
  if($csn==0){
    //新增
    $sql = "insert into ".$xoopsDB->prefix(_DATA_TABLE)."(`of_csn`,`title`,`enable`,`sort`) values ('{$of_csn}','{$title}','{$enable}','{$sort}')";
  }else{
    //編輯
    $sql = "update ".$xoopsDB->prefix(_DATA_TABLE)." set  `of_csn` = '{$of_csn}', `title` = '{$title}', `enable` = '{$enable}', `sort` = '{$sort}' where `csn`='{$csn}'";

  }
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  if($csn==0){
		//取得最後新增資料的流水編號(新增)
		$csn=$xoopsDB->getInsertId();
	}
  return  $csn;
}
###############################################################################
#  更新啟用
#
#
#
###############################################################################
function op_update_enable($csn=0){
  global $xoopsDB,$xoopsUser;
  if(!$xoopsUser)return;
  $uid=$xoopsUser->getVar('uid');
  /***************************** 過瀘資料 *************************/
  $enable=intval($_GET['enable']);
  /****************************************************************/
  //更新
  $sql = "update ".$xoopsDB->prefix(_DATA_TABLE)." set  `enable` = '{$enable}' where `csn`='{$csn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  return ;
}
###############################################################################
#  op_del刪除分類
#  並將以csn為of_csn值改為0
#
###############################################################################
function op_del($csn=0){
  global $xoopsDB,$xoopsUser;
  if(!$xoopsUser)return;
  $uid=$xoopsUser->getVar('uid');
  $sql = "delete from ".$xoopsDB->prefix(_DATA_TABLE)." where `csn`='{$csn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF']."?mtype={$mtype}",3, web_error());
  //更新
  $sql = "update ".$xoopsDB->prefix(_DATA_TABLE)." set  `of_csn` = '0' where `of_csn`='{$csn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  return;
}
//op_update_enable
###############################################################################
#  更新排序
#
#
###############################################################################
function op_update_sort($of_csn=0){
  global $xoopsDB;
  $sql = "select `csn`,`of_csn`  from ".$xoopsDB->prefix(_DATA_TABLE)." where `of_csn`='{$of_csn}' order by `sort` ";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $i=1;
  while(list($csn)=$xoopsDB->fetchRow($result) ){
    $sql1 = "update ".$xoopsDB->prefix(_DATA_TABLE)." set  `sort` = '{$i}' where `csn`='{$csn}'";
    $xoopsDB->queryF($sql1) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    op_update_sort($csn);
    $i++;
  }
  return;
}
###############################################################################
#  ugmmr_group編輯表單
#
#
#
###############################################################################

function op_form($csn=0,$of_csn=0){
	global $xoopsDB,$xoopsUser,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $stop_level=_STOP_LEVEL;
  //抓取預設值
	if($csn!=0){
	  //編輯
	  $sql = "select * from ".$xoopsDB->prefix(_DATA_TABLE)." where csn='{$csn}'";
		$DBV=get_sn_one($sql);
	}else{
	  //新增
		$DBV=array();
	}
	//-------------預設值設定-----------------------------
	$csn=(!isset($DBV['csn']))?0:$DBV['csn'];                         //本層
	$of_csn=(!isset($DBV['of_csn']))?$of_csn:$DBV['of_csn'];          //父層
	$title=(!isset($DBV['title']))?"":$DBV['title'];                  //標題
	$enable=(!isset($DBV['enable']))?1:$DBV['enable'];                //啟用
	$sort=(!isset($DBV['sort']))?get_max_sort($of_csn):$DBV['sort'];  //排序
	$selected=($of_csn==0)?" selected=selected":"";                   //根目錄
	//----------------------------------------------------

  $main.=ugm_validation()."
  	  <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_form' class='form-inline'>
  	    <table id='{$DIRNAME}_table' border='0' cellspacing='3' cellpadding='3' class='ugm_tb'>
  	      <tr><th colspan='2'>"._MA_UGMPAGE_CATE_FORM."</th></tr>
          <tr><th style='width:120px;'>"._MA_UGMPAGE_CATE_TITLE."</th>
    	      <td><label for='title'></label><input type='text' name='title' size='40' value='{$title}' id='title'></td>
          </tr>

          <tr><th>"._MA_UGMPAGE_CATE_ENABLE."</th>
    	        <td><input type='radio' name='enable' id='enable_1' value='1' ".chk($enable,'1')."><label for='enable_1'>"._MA_YES."</label>
                  <input type='radio' name='enable' id='enable_0' value='0' ".chk($enable,'0')."><span><label for='enable_0'>"._MA_NO."</label></span>
              </td>
          </tr>

          <tr><th>"._MA_UGMPAGE_CATE_SORT."</th>
    	      <td><label for='sort'></label><input type='text' name='sort' size='10' value='{$sort}' id='sort'></td>
          </tr>
          <tr><th>"._MA_UGMPAGE_CATE_OFCSN."</th>
    	      <td><label for='of_csn'>
                <select name='of_csn' size=1>
                  <option value='0'{$selected}></option>".get_group_option(0,$of_csn,$csn,$stop_level-1)."
                </select>
            </label></td>
          </tr>

  	      <tr><th colspan='2'>
  	        <input type='hidden' name='csn' value='{$csn}'>
        	  <input type='hidden' name='op' value='op_insert'>
          	<input type='submit' name='submit' value='"._MA_SAVE."'>
          </th></tr>
  	    </table>
  	   </form>
     ";
     //<input type='hidden' name='of_csn' value='{$of_csn}'>

  $main=ugm_div(_MI_UGMPAGE_ADMENU4,$main);
	return $main;
}


###############################################################################
# 表單驗證
#
#
#
###############################################################################
function ugm_validation(){
	global $xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $main="
    <script type='text/javascript' src='../class/validation/jquery.validate.min.js'></script>
		<script type='text/javascript'>
		  $(document).ready(function(){
			$('#{$DIRNAME}_form').validate({
				//驗證規則
        rules: {
				  title: {
						required: true
					}
				},
				//錯誤訊息
				messages: {
					title: {
						required: '"._BP_REQUIRED."'
					}
				},
				errorElement: 'em', //可以用其他標籤，記住把樣式也對應修改
				success: function(label) {
					//label指向上面那個錯誤提示資訊標籤em
					label.text(' ')				//清空錯誤提示消息
					.addClass('success');	//加上自定義的success類
				}
			  });
		  });
		</script>
		<style>
			em.error{
			  color:#ff0000;
				padding:5px 5px 5px 16px;
				font-size:12px;
				vertical-align:middle;
			}
		</style>
  ";
  return $main;
}
################################################################################
//自動取得msort的最新排序
function get_max_sort($of_csn=0){
	global $xoopsDB;
	$sql = "select max(`sort`) from ".$xoopsDB->prefix(_DATA_TABLE)." where `of_csn`='{$of_csn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}

################################################################################
#   表單
#
#
#
#
################################################################################
//確定層次
function chk_level($of_csn_chk=0,$level=1){
  global $xoopsDB;
  $DBV=get_sn_one("select `of_csn` from ".$xoopsDB->prefix(_DATA_TABLE)." where `csn`={$of_csn_chk} ");
  if($DBV['of_csn']!=0){
    $level++;
    $level=chk_level($DBV['of_csn'],$level);
  }
  return $level;
 }



#########################################################################
#得到類別路徑
#
#########################################################################
function get_group_direct($of_csn_chk=0,$level=0){
  global $xoopsDB,$xoopsModule;
  if($of_csn_chk==0)return;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $DBV=get_sn_one("select `of_csn`,`csn`,`title` from ".$xoopsDB->prefix("tad_form_cate")." where `csn`='{$of_csn_chk}' ");
  if($level==0){
    //第一次且最後一層不須連結
    $group_direct="<div class='Button'><span><font color='red'>{$DBV['title']}</span></font></div>";
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

?>
