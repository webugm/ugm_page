<?php
include_once "header.php";
$xoopsOption['template_main'] = "ugm_page_index_tpl.html";
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];
$csn = (!isset($_REQUEST['csn']))? 0:intval($_REQUEST['csn']);
$of_csn = (!isset($_REQUEST['of_csn']))? 0:intval($_REQUEST['of_csn']);
$type = (!isset($_REQUEST['type']))? 0:intval($_REQUEST['type']);

switch($op){
	case "op_form":
    #權限檢查
    if(!$gperm['ugm_page_cate'][1] and !$gperm['ugm_page_cate'][2])redirect_header(XOOPS_URL,3,_NOPERM);
	  $main=op_form($csn,$of_csn,$type);
	break;
	case "op_list":
    #權限檢查
    if(!$gperm['ugm_page_cate'][4])redirect_header(XOOPS_URL,3,_NOPERM);
	  $main=op_list($type);
	break;
	case "op_insert": //新增、編輯
    #權限檢查
    if(!$gperm['ugm_page_cate'][1] and !$gperm['ugm_page_cate'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    op_insert($csn);
	  redirect_header($_SERVER['PHP_SELF']."?type={$type}",3, _BP_INSERT_SUCCESS);
	break;
	case "op_update_enable": //更新啟用
    #權限檢查
    if(!$gperm['ugm_page_cate'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    op_update_enable($csn,$type);
	  redirect_header($_SERVER['PHP_SELF']."?type={$type}",3, _BP_INSERT_SUCCESS);
	break;
	case "op_update_sort": //更新排序
    #權限檢查
    if(!$gperm['ugm_page_cate'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    op_update_sort($type);
	  redirect_header($_SERVER['PHP_SELF']."?type={$type}",3, _BP_INSERT_SUCCESS);
	break;
	case "op_del":
    #權限檢查
    if(!$gperm['ugm_page_cate'][3])redirect_header(XOOPS_URL,3,_NOPERM);
	  op_del($csn);
	  redirect_header($_SERVER['PHP_SELF']."?type={$type}",3, _BP_DEL_SUCCESS);
	break;
	default:
    #權限檢查
    if(!$gperm['ugm_page_cate'][4])redirect_header(XOOPS_URL,3,_NOPERM);
	  $main=op_list($of_csn,$csn,$type);
	break;

	/*---判斷動作請貼在上方---*/
}

#相容JQUERY
$ver = intval(str_replace('.', '', substr(XOOPS_VERSION, 6, 5)));
if ($ver >= 259) {
  $xoTheme->addScript('modules/tadtools/jquery/jquery-migrate-3.0.0.min.js');
} else {
  $xoTheme->addScript('modules/tadtools/jquery/jquery-migrate-1.4.1.min.js');
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "content" , $main) ;
$xoTheme->addStylesheet(XOOPS_URL . "/modules/ugm_page/css/module_b3.css");
$xoopsTpl->assign( "moduleMenu" , $moduleMenu) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;//interface_menu.php
$xoopsTpl->assign( "op" , $op) ;
include_once XOOPS_ROOT_PATH . '/footer.php';


/*-----------function區--------------*/

###############################################################################
#  op_list列表
#
#
###############################################################################
function op_list($of_csn=0,$csn=0,$type=0){
  global $xoopsDB,$xoopsModule,$xoopsOption,$xoopsModuleConfig;

  if($type==1){
    $xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_MDMENU2_1;//指定標題
  }else{
    $xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_MDMENU2;//指定標題
  }
	$DIRNAME=$xoopsModule->getVar('dirname');
	//$main=ugm_javascript(0);

	if($type==1){
    $stop_level=1;
    $cate_select="";
  }else{
    $stop_level=$xoopsModuleConfig['cate_level'];//結束層
    $cate_select="
      <div class='row' style='margin-bottom: 10px;'>
        <div class='col-sm-3'>
          <form action='{$_SERVER['PHP_SELF']}' method='post'>
            <select name='of_csn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?type={$type}&of_csn=\"+this.value'  class='form-control'>
              <option value='0'{$selected}>"._BP_ALL."</option>".get_group_option(0,$of_csn,0,$stop_level-1,0,$type)."
            </select>
          </form>
        </div>
      </div>
       ";
  }

  $level=0;//
  $selected=($of_csn==0)?" selected=selected":"";
  $group_direct_head="<a href='{$_SERVER['PHP_SELF']}?of_csn=0&type={$type}' class='Button' ><span><i class='fa fa-home'></i>"._BP_HOME."</span></a>"; //路徑選單的頭
  $group_direct=($of_csn==0)?"":get_group_direct($of_csn);
  $main.="
    <script>
  	function delete_{$DIRNAME}_func(csn){
  		var sure = window.confirm('"._BP_DEL_CHK."');
  		if (!sure)	return;
  		location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&type={$type}&csn=\" + csn;
  	}
  	</script>
  	<div class='list_head'>
      {$cate_select}
      {$group_direct_head}
      {$group_direct}
    <a href='{$_SERVER['PHP_SELF']}?op=op_form&of_csn={$of_csn}&type={$type}' class='Button' ><span><i class='fa fa-plus-square'></i> "._BP_ADD_CATE."</span></a>
    <a href='{$_SERVER['PHP_SELF']}?op=op_update_sort&type={$type}' class='Button' ><span><i class='fa fa-sort'></i> "._BP_RESET_SORT."</span></a>
    </div>
    <div id='{$DIRNAME}_table'>
  	  <table id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th class='col-sm-8'>"._MD_UGMPAGE_CATE_TITLE."</th>
        	<th class='col-sm-1 text-center'>"._MD_UGMPAGE_CATE_ENABLE."</th>
          <th class='col-sm-1 text-center'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th class='col-sm-2 text-center'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($of_csn,$csn,$stop_level,$level,$type);
  	$main.="</table></div>";
   //$main=ugm_div($xoopsOption['xoops_pagetitle'],$main);
   return $main;
}
################################################################################
#   表單身體
#   // get_list_body `csn`, `of_csn`, `title`, `sort`, `enable`
#
#
################################################################################
function get_list_body($of_csn=0,$csn_chk=0,$stop_level=2,$level=0,$type=0){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  if($level>=$stop_level)return;
  //$level_mark=($level==0)?"":" style='text-indent:".(($level)*12)."pt;'";//縮排ugm_indent
  $level_mark=($level==0)?"":"class='ugm_indent'";
  $level_css=" class='level_{$level}'";
  $level++;
  //group_title	group_readme	image_counter
  $sql = "select * from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `of_csn`='{$of_csn}' and `type`='{$type}' order by `sort` ";//die($sql);
  //***************PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);***/

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
  $total=$xoopsDB->getRowsNum($result);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $main="";
  while(list($csn,$of_csn,$title,$sort,$enable)=$xoopsDB->fetchRow($result) ){
	  $enable=($enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&type={$type}&csn={$csn}&of_csn={$csn_chk}&enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&type={$type}&csn={$csn}&of_csn={$csn_chk}&enable=1'><img src='images/off.png' /></a>";//啟用
    $chk_level=chk_level($csn);
    $create_sub=($stop_level>$chk_level)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&type={$type}&of_csn={$csn}'><img src='images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_ADD_SUB,$title)."'></a> ":"";//是否可以新增
    $title=($stop_level>$chk_level)?"<a href='{$_SERVER['PHP_SELF']}?of_csn={$csn}&type={$type}'>{$title}</a>": $title;
    $main.="
      <tr{$level_css}>
        <td {$level_mark}>{$create_sub}{$title}</td>\n
        <td class='text-center'>{$enable}</td>\n
        <td class='text-center'>{$sort}</td>\n
        <td class='text-center'><a href='{$_SERVER['PHP_SELF']}?op=op_form&type={$type}&csn={$csn}&of_csn={$of_csn}'><img src='images/i_edit.gif' /></a>&nbsp;&nbsp;<a href=\"javascript:delete_{$DIRNAME}_func($csn);\"><img src='images/i_del.gif' /></a></td>\n

      	</tr>".get_list_body($csn,$csn_chk,$stop_level,$level,$type);
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
  $type=intval($_POST['type']);
  /****************************************************************/
  if($csn==0){
    //新增
    $sql = "insert into ".$xoopsDB->prefix(_DB_CATE_TABLE)."(`of_csn`,`title`,`enable`,`sort`,`type`) values ('{$of_csn}','{$title}','{$enable}','{$sort}','{$type}')";
  }else{
    //編輯
    $sql = "update ".$xoopsDB->prefix(_DB_CATE_TABLE)." set  `of_csn` = '{$of_csn}', `title` = '{$title}', `enable` = '{$enable}', `sort` = '{$sort}', `type` = '{$type}' where `csn`='{$csn}'";

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
  $sql = "update ".$xoopsDB->prefix(_DB_CATE_TABLE)." set  `enable` = '{$enable}' where `csn`='{$csn}'";
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
  $sql = "delete from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `csn`='{$csn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  //更新
  $sql = "update ".$xoopsDB->prefix(_DB_CATE_TABLE)." set  `of_csn` = '0' where `of_csn`='{$csn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  return;
}
//op_update_enable
###############################################################################
#  更新排序
#
#
###############################################################################
function op_update_sort($type=0,$of_csn=0){
  global $xoopsDB;
  $sql = "select `csn`,`of_csn`  from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `of_csn`='{$of_csn}' and `type`={$type} order by `sort` ";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $i=1;
  while(list($csn)=$xoopsDB->fetchRow($result) ){
    $sql1 = "update ".$xoopsDB->prefix(_DB_CATE_TABLE)." set  `sort` = '{$i}' where `csn`='{$csn}'";
    $xoopsDB->queryF($sql1) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    op_update_sort($type,$csn);
    $i++;
  }
  return;
}
###############################################################################
#  ugmmr_group編輯表單
###############################################################################
function op_form($csn=0,$of_csn=0,$type){
	global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsOption,$xoopsModuleConfig;
  if($type==1){
    $stop_level=1;
    $xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_MDMENU2_1;//指定標題
  }else{
    $stop_level=$xoopsModuleConfig['cate_level'];
    $xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_MDMENU2;//指定標題
  }
  $DIRNAME=$xoopsModule->getVar('dirname');

  //抓取預設值
	if($csn!=0){
	  //編輯
	  $sql = "select * from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where csn='{$csn}' and `type`='{$type}'";//die($sql);
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
	$sort=(!isset($DBV['sort']))?get_max_sort($of_csn,$type):$DBV['sort'];  //排序
	$selected=($of_csn==0)?" selected=selected":"";                   //根目錄
	//----------------------------------------------------
	//---------------------  驗証    -------------------
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#{$DIRNAME}_Form",true);
  $formValidator_code=$formValidator->render();
  //----------------------------------------------------

  $main.=$formValidator_code."
  	  <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form'>
  	    <table id='form_table' class='table table-bordered table-condensed table-hover'>
  	      <tr><th colspan=2>"._MD_UGMPAGE_CATE_FORM."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_CATE_TITLE."</th>
    	      <td><label for='title'></label><input type='text' name='title' size='40' value='{$title}' id='title' class='validate[required]' ></td>
          </tr>

          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
    	        <td><input type='radio' name='enable' id='enable_1' value='1' ".chk($enable,'1')."><label for='enable_1'>"._YES."</label>
                  <input type='radio' name='enable' id='enable_0' value='0' ".chk($enable,'0')."><span><label for='enable_0'>"._NO."</label></span>
              </td>
          </tr>

          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
    	      <td><label for='sort'></label><input type='text' name='sort' size='10' value='{$sort}' id='sort'></td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_OFCSN."</th>
    	      <td><label for='of_csn'>
                <select name='of_csn' size=1>
                  <option value='0'{$selected}></option>".get_group_option(0,$of_csn,$csn,$stop_level-1,0,$type)."
                </select>
            </label></td>
          </tr>

  	      <tr><th colspan='2'>
  	        <input type='hidden' name='csn' value='{$csn}'>
  	        <input type='hidden' name='type' value='{$type}'>
        	  <input type='hidden' name='op' value='op_insert'>
          	<input type='submit' name='submit' value='"._SUBMIT."'>
          </th></tr>
  	    </table>
  	   </form>
     ";
     //<input type='hidden' name='of_csn' value='{$of_csn}'>

  $main=ugm_div($xoopsOption['xoops_pagetitle'],$main);
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
    <script type='text/javascript' src='class/validation/jquery.validate.min.js'></script>
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
function get_max_sort($of_csn=0,$type=0){
	global $xoopsDB;
	$sql = "select max(`sort`) from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `of_csn`='{$of_csn}' and `type`='{$type}'";
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
  $DBV=get_sn_one("select `of_csn` from ".$xoopsDB->prefix(_DB_CATE_TABLE)." where `csn`={$of_csn_chk} ");
  if($DBV['of_csn']!=0){
    $level++;
    $level=chk_level($DBV['of_csn'],$level);
  }
  return $level;
 }


