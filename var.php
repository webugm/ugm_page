<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
#上傳檔案必須引入
include_once(XOOPS_ROOT_PATH."/modules/ugm_page/up_file.php");
$xoopsOption['template_main'] = "ugm_page_var_tpl.html";
$xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_SMNAME9;//指定標題
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
//if(!$isPageTheme)redirect_header("index.php",3, _NOPERM);//沒有權限
$op    = (!isset($_REQUEST['op']) or strlen($_REQUEST['op']) > 50)? "main":$_REQUEST['op'];
$sn    = (!isset($_REQUEST['sn']))? "":intval($_REQUEST['sn']);
$csn   = (!isset($_REQUEST['csn']))? 0:intval($_REQUEST['csn']);

switch($op){
  //新增資料、編輯資料
	case "op_insert":
    $sn=op_insert($sn);
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;
	# ---- 變數表單 ----
	case "op_form":
	$main=op_form($sn);
	break;

  # ---- 更新租用狀態 --------
	case "op_update_enable":
  op_update_enable();
	redirect_header($_SERVER['PHP_SELF'],3, _BP_SUCCESS);

	//刪除資料
	case "op_delete":
	  op_delete($sn);
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;

	default:
	//$xoopsOption['show_lblock'] = 0;
	$main=op_list($csn,$sn);
	break;
}

#相容JQUERY
$ver = intval(str_replace('.', '', substr(XOOPS_VERSION, 6, 5)));
if ($ver >= 259) {
  $xoTheme->addScript('modules/tadtools/jquery/jquery-migrate-3.0.0.min.js');
} else {
  $xoTheme->addScript('modules/tadtools/jquery/jquery-migrate-1.4.1.min.js');
}

/*-----------秀出結果區--------------*/
$xoTheme->addStylesheet(XOOPS_URL . "/modules/ugm_page/css/module_b3.css");
$xoopsTpl->assign( "moduleMenu" , $moduleMenu) ;
$xoopsTpl->assign( "content" , $main) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;//interface_menu.php
$xoopsTpl->assign( "op" , $op) ;
include_once XOOPS_ROOT_PATH . '/footer.php';


/*-----------function區--------------*/
###############################################################################
#  更新啟用狀態
#
#
#
###############################################################################
function op_update_enable(){
  global $xoopsDB,$xoopsUser;
  /***************************** 過瀘資料 *************************/
  $enable=intval($_GET['enable']);
  $sn=intval($_GET['sn']);
  /****************************************************************/
  //更新
  $sql = "update ".$xoopsDB->prefix("ugm_page_var")." set  `enable` = '{$enable}' where `sn`='{$sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  return ;
}
###############################################################################
#  刪除ugm_page_var某筆資料資料
#
#
#
###############################################################################
function op_delete($sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ugm_page_var")." where `sn`='{$sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	return;
}
#################################################################################
# List 「ugm_page_var」
#
#
#
#
#################################################################################

function op_list($csn=0,$sn=0){
	global $xoopsDB,$xoopsModule,$xoopsConfig;
	$DIRNAME=$xoopsModule->getVar('dirname');
  # --- 下拉選單----
	$csn = (!isset($_GET['csn']))? 0:intval($_GET['csn']);
	$stop_level=1;
	$selected=($csn==0)?" selected=selected":"";
  $groud_option=_MD_UGMPAGE_CSN._BP_FOR."<select name='csn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?csn=\"+this.value' >
       <option value='0'{$selected}>"._BP_ALL."</option>".get_group_option(0,$csn,0,$stop_level,0,1)."
    </select>";
  $and_key=($csn==0)?"":" where a.`csn`='{$csn}' ";
	//------------------------------------------------------
	$sql = "select a.*,b.`title` as `cate_title`
          from ".$xoopsDB->prefix("ugm_page_var")." as a
          left join ".$xoopsDB->prefix("ugm_page_cate")." as b on a.`csn`=b.`csn`
          {$and_key}
          order by a.`sort`"; //die($sql);

	//getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$count=20;
  $PageBar=getPageBar($sql,$count,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  # ------------ 是否出現分頁 ------------------------------------
  $bar=($total>$count)?"<tr><th colspan=8>{$bar}</th></tr>":"";
  # ---------------------------------------------------------------
	$all_content="";
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $sn , $csn , $sort , $enable , $type , $var_title , $var_name , $var_value , $var_tip
    foreach($all as $k=>$v){
      $$k=$v;
    }

    # ----- 啟用狀態 ----------------------------------------------------------------
		$enable=($enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&sn={$sn}&enable=0' title='"._BP_ENABLE_0."' atl='"._BP_ENABLE_0."'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&sn={$sn}&enable=1' title='"._BP_ENABLE."' atl='"._BP_ENABLE."'><img src='images/off.png'/></a>";
		# -------------------------------------------------------------------------------
		$fun="
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=op_form&sn=$sn' class='btn btn-info btn-small'>"._BP_EDIT."</a>
		<a href=\"javascript:op_delete_func($sn);\" class='btn btn-danger btn-small'>"._BP_DEL."</a>
		</td>";
		$all_content.="<tr>
		<td class='c'>{$enable}</td>
		<td>{$var_title}</td>
		<td><{\$ugm_page_{$sn}}></td>
		<td>{$var_value}</td>
		<td>{$var_tip}</td>
  $fun
		</tr>";
	}
	//--------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
 # --------------------------------------------------------------------
  //刪除確認的JS
  $data=$jquery_path.ugm_javascript(1)."
	<script>
	function op_delete_func(sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=op_delete&sn=\" + sn;
	}
	</script>

  <table border='0' cellspacing='0' cellpadding='0' class='ugm_tb table' style='word-wrap:break-word; word-break:break-all;table-layout:fixed;'>
	<tr>
	<th style='width:60px;'>"._MA_UGMPAGE_VAR_ENABLE."</th>
	<th>"._MA_UGMPAGE_VAR_VAR_TITLE."</th>
	<th>"._MA_UGMPAGE_VAR_VAR_NAME."</th>
	<th>"._MA_UGMPAGE_VAR_VAR_VALUE."</th>
	<th>"._MA_UGMPAGE_VAR_VAR_TIP."</th>
	<th>"._BP_FUNCTION."</th>
  </tr>
	<tbody>
	$all_content
	$bar
	</tbody>
	</table>";

	//raised,corners,inset
	$main=ugm_div("",$groud_option.$data,"");
	return op_menu().$main;
}


#################################################################################
#  「op_insert」
#
#
#
#
#################################################################################
function op_insert($sn){
	global $xoopsDB,$xoopsUser;
  $myts =& MyTextSanitizer::getInstance();
	$_POST['sort']     = intval($_POST['sort']);
	$_POST['enable']   = intval($_POST['enable']);
	$_POST['csn']      = intval($_POST['csn']);
	$_POST['var_title']= $myts->addSlashes($_POST['var_title']);
	$_POST['var_value']= $myts->addSlashes($_POST['var_value']);
	$_POST['var_tip']  = $myts->addSlashes($_POST['var_tip']);
  if(empty($_POST['sn'])){
    # 新增sn sn	csn 類別	sort 排序	enable 啟用	var_title 標題	var_name 變數名稱	var_value 變數值	var_tip 提示
    $sql = "insert into ".$xoopsDB->prefix("ugm_page_var")."
  	(`csn`,`sort`,`enable`,`var_title`,`var_value`,`var_tip`)
	  values
    ('{$_POST['csn']}','{$_POST['sort']}','{$_POST['enable']}','{$_POST['var_title']}','{$_POST['var_value']}','{$_POST['var_tip']}')";//die($sql);
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    //取得最後新增資料的流水編號
	  $sn=$xoopsDB->getInsertId();
  }else{
    # 編輯
    $sql = "update ".$xoopsDB->prefix("ugm_page_var")." set
           `csn`       = '{$_POST['csn']}'       ,
           `sort`      = '{$_POST['sort']}'      ,
           `var_title` = '{$_POST['var_title']}' ,
           `var_value` = '{$_POST['var_value']}' ,
           `var_tip`   = '{$_POST['var_tip']}'
           where  `sn` = '{$_POST['sn']}'";
   	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  }
	return $sn;
}

###############################################################################
#  類別下拉選單的選項
#  csn_select_option
#
#
###############################################################################
function csn_select_option($default=""){
	global $xoopsDB,$xoopsModuleConfig;
  $sql="select `csn`,`title` from ".$xoopsDB->prefix("ugm_page_cate")." where `enable`='1' and `type`='1' order by `sort`";
	$result=$xoopsDB->query($sql);
	while(list($csn,$title)=$xoopsDB->fetchRow($result)){
	  $selected=($default==$csn)?"selected":"";
    $option.="<option value='{$csn}' $selected>{$title}</option>";
  }
  return $option;
}
###############################################################################
#  自動取得 「變數」sort的最新排序
#
#
#
###############################################################################
//自動取得msort的最新排序
function get_max_sort(){
	global $xoopsDB;
	$sql = "select max(`sort`) from ".$xoopsDB->prefix("ugm_page_var")." ";//die($sql);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}
###############################################################################
#  以流水號取得某筆ugm_page_var資料
#  變數
#
#
###############################################################################
function get_ugm_page_var($sn=""){
	global $xoopsDB;
	if(empty($sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ugm_page_var")." where `sn`='{$sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

#################################################################################
# ugm_page_var 編輯表單
#
#
#
#
#################################################################################
function op_form($sn=""){
	global $xoopsDB,$xoopsUser;
	//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");
	//抓取預設值
	if(!empty($sn)){
		$DBV=get_ugm_page_var($sn);
	}else{
		$DBV=array();
	}
	//預設值設定

	//設定「sn」欄位預設值
	$sn=(!isset($DBV['sn']))?"":$DBV['sn'];

	//設定「csn」欄位預設值
	$csn=(!isset($DBV['csn']))?"":$DBV['csn'];

	//設定「sort」欄位預設值
	$sort=(!isset($DBV['sort']))?get_max_sort():$DBV['sort'];//

	//設定「enable」欄位預設值
	$enable=(!isset($DBV['enable']))?1:$DBV['enable'];

	//設定「var_title」欄位預設值
	$var_title=(!isset($DBV['var_title']))?"":$DBV['var_title'];

	//設定「var_name」欄位預設值
	$var_name=(!isset($DBV['var_name']))?"":$DBV['var_name'];

	//設定「var_value」欄位預設值
	$var_value=(!isset($DBV['var_value']))?"":$DBV['var_value'];

	//設定「var_tip」欄位預設值
	$var_tip=(!isset($DBV['var_tip']))?"":$DBV['var_tip'];

	//$op="replace_ugm_page_var";
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();

  //enctype='multipart/form-data'


	//--------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
 # ---- 類別選單   ----------------------------------------------------
  $csn_select_option=csn_select_option($csn);

	$main=$jquery_path.ugm_javascript(0)."
	$formValidator_code

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' class='form-inline'>
  <table border='0' cellspacing='0' cellpadding='0' class='ugm_tb'>


	<!--sn-->
	<input type='hidden' name='sn' value='{$sn}'>

	<!--類別-->
	<tr class='alt'>
    <th style='width:100px;'>"._MA_UGMPAGE_VAR_CSN."</th>
	  <td>
      <select name='csn' size=1 >{$csn_select_option}</option></select>
    </td>
  </tr>

	<!--排序-->
	<tr class='alt'><th>"._MA_UGMPAGE_VAR_SORT."</th>
	<td><input type='text' name='sort' size='20' value='{$sort}' id='sort' ></td></tr>

	<!--啟用-->
	<tr class='alt'><th>"._MA_UGMPAGE_VAR_ENABLE."</th>
	<td>
	<input type='radio' name='enable' id='enable_1' value='1'  ".chk($enable,'1')."><label for='enable_1'>"._YES."</label>&nbsp;
	<input type='radio' name='enable' id='enable_0' value='0'  ".chk($enable,'0')."><label for='enable_0'>"._NO."</label></td></tr>

	<!--標題-->
	<tr class='alt'><th>"._MA_UGMPAGE_VAR_VAR_TITLE."</th>
	<td><input type='text' name='var_title' size='20' value='{$var_title}' id='var_title' ></td></tr>

	<!--變數值-->
	<tr class='alt'><th>"._MA_UGMPAGE_VAR_VAR_VALUE."</th>
	<td><textarea name='var_value' id='var_value' style='height:50px;width:600px;'>{$var_value}</textarea></td></tr>

	<!--提示-->
	<tr class='alt'><th>"._MA_UGMPAGE_VAR_VAR_TIP."</th>
	<td><input type='text' name='var_tip' size='20' value='{$var_tip}' id='var_tip' ></td></tr>
	<tr><th colspan='2'>
	<input type='hidden' name='op' value='op_insert'>
	<input type='submit' value='"._MD_SAVE."'></th></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=ugm_div("新增佈景變數",$main,"");

	return op_menu().$main;
}

#################################################################################
#  「menu」
#
#
#
#
#################################################################################
function op_menu(){
  $menu="
  <div class='btn-group'>
      <button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>"._MD_UGMPAGE_SMNAME9."<span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <li><a href='{$_SERVER['PHP_SELF']}?op=op_form' >新增變數</a></li>
        <li class='divider'></li>
        <li><a href='cate.php?type=1' >管理類別</a></li>
      </ul>
  </div>
  ";
  return $menu;
}