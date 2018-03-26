<?php
################################################################################
#   佈景管理器 index.php
#   版權：育將電腦工作室
#   日期：2011-09-28
#   1->伸縮選單 2->圖片輪撥 3->跑馬燈 4->下拉選單
#
################################################################################
/*-----------引入檔案區--------------*/
include_once "header.php";
#上傳檔案必須引入
include_once(XOOPS_ROOT_PATH."/modules/ugm_page/up_file.php");
$xoopsOption['template_main'] = "ugm_page_menu_tpl.html";
$xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_SMNAME6;//指定標題
include XOOPS_ROOT_PATH."/header.php";
define("_DB_MENU_TABLE","ugm_page_menu");

/*-----------執行動作判斷區----------*/
//if(!$isPageMenu)redirect_header("index.php",3, _NOPERM);//沒有權限
$op      = (!isset($_REQUEST['op']) or strlen($_REQUEST['op']) > 50)? "main":$_REQUEST['op'];
$menu_sn     = (!isset($_REQUEST['menu_sn']))? "0":intval($_REQUEST['menu_sn']);
$menu_type   = (!isset($_REQUEST['menu_type']))? "0":intval($_REQUEST['menu_type']);
$menu_ofsn   = (!isset($_REQUEST['menu_ofsn']))? "0":intval($_REQUEST['menu_ofsn']);

switch($op){
	//新增資料、編輯資料
	case "op_insert":
    #權限檢查
    if(!$gperm['ugm_page_menu'][1] and !$gperm['ugm_page_menu'][2])redirect_header(XOOPS_URL,3,_NOPERM);
	  $menu_sn=op_insert($menu_type,$menu_ofsn,$menu_sn);
	  redirect_header($_SERVER['PHP_SELF']."?menu_type={$menu_type}&menu_sn={$_SESSION['ugm_return']}",3, _BP_INSERT_SUCCESS);
	break;
	//輸入表單
	case "op_form":
    #權限檢查
    if(!$gperm['ugm_page_menu'][1] and !$gperm['ugm_page_menu'][2])redirect_header(XOOPS_URL,3,_NOPERM);
	   $main=op_form($menu_type,$menu_ofsn,$menu_sn);
	break;
	case "op_update_enable": //更新啟用
    #權限檢查
    if(!$gperm['ugm_page_menu'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    op_update_enable($menu_sn);
	  redirect_header($_SERVER['PHP_SELF']."?menu_type={$menu_type}",3, _BP_INSERT_SUCCESS);
	break;
	//刪除資料
	case "op_del":
    #權限檢查
    if(!$gperm['ugm_page_menu'][3])redirect_header(XOOPS_URL,3,_NOPERM);
	  op_delete($menu_sn);

	break;

	default:
	//$xoopsOption['show_lblock'] = 0;
    #權限檢查
    if(!$gperm['ugm_page_menu'][4])redirect_header(XOOPS_URL,3,_NOPERM);
	  $main=op_list($menu_type,$menu_ofsn,$menu_sn);
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
$xoopsTpl->assign( "css" , $module_css) ;
$xoopsTpl->assign( "content" , $main) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;//interface_menu.php
$xoopsTpl->assign( "op" , $op) ;
include_once XOOPS_ROOT_PATH . '/footer.php';


/*-----------function區--------------*/
//模組頁尾
function module_footer1($main=""){
  global $xoopsTpl,$module_css,$module_menu;
  include XOOPS_ROOT_PATH."/header.php";
  $xoopsTpl->assign( "css" , $module_css) ;
  $xoopsTpl->assign( "toolbar" , $module_menu) ;
  $xoopsTpl->assign( "content" , $main) ;
  include_once XOOPS_ROOT_PATH.'/footer.php';
}


#######################################################################################
//刪除u_menu某筆資料資料
function op_delete($menu_sn=""){
	global $xoopsDB,$xoopsModule;
	$DIRNAME=$xoopsModule->getVar('dirname');
	//檢查型態
	$DVB=get_sn_one("select `menu_type` from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where`menu_sn`='{$menu_sn}'");
	$menu_type=$DVB['menu_type'];
  if(cdk_sub_level($menu_sn))redirect_header($_SERVER['PHP_SELF']."?menu_type={$menu_type}",3,_MD_UGMPAGE_MENU_NO_DEL_SUB);
  $sql = "delete from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='{$menu_sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF']."?menu_type={$menu_type}",3, web_error());
  redirect_header($_SERVER['PHP_SELF']."?menu_type={$menu_type}",3, _BP_DEL_SUCCESS);
}
################################################################################
//----------------偵測是否有小類
function cdk_sub_level($menu_sn=0){
  global $xoopsDB;
  $sql = "select `menu_sn` from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where`menu_ofsn`='{$menu_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $total=$xoopsDB->getRowsNum($result);
  if($total>0)return true;
  return false;
}
###############################################################################
#  更新啟用
#
#
#
###############################################################################
function op_update_enable($menu_sn=0){
  global $xoopsDB,$xoopsUser;
  if(!$xoopsUser)return;
  $uid=$xoopsUser->getVar('uid');
  /***************************** 過瀘資料 *************************/
  $menu_enable=intval($_GET['menu_enable']);
  /****************************************************************/
  //更新
  $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_enable` = '{$menu_enable}' where `menu_sn`='{$menu_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  return ;
}
################################################################################
//自動取得menu_sort的最新排序
function get_max_menu_sort($menu_type=0,$menu_ofsn=0){
	global $xoopsDB;
	$sql = "select max(`menu_sort`) from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where `menu_type`='{$menu_type}' and `menu_ofsn`='{$menu_ofsn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	list($menu_sort)=$xoopsDB->fetchRow($result);
	return ++$menu_sort;
}
################################################################################
#   表單
#   //ugm_form
#
#
#
################################################################################
function op_form($menu_type=0,$menu_ofsn=0,$menu_sn=0){
  global $xoopsModule,$xoopsDB,$xoopsConfig;
  $DIRNAME=$xoopsModule->getVar('dirname');
  //$main.=ugm_style_css();

  //---------------------  驗証 (會自已引入jquery)   ------------------------------------------------
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#{$DIRNAME}_Form",true);
  $formValidator_code=$formValidator->render();
  //------------------------ enctype='multipart/form-data' ----------------------------------------------------
  switch($menu_type){
  case "5": //單層選單(2層)第一層是類別
  case "1": //伸縮選單(3層)第一層是類別
  	//抓取預設值
  	if(!$menu_sn==0){
			//編輯
			$DBV=get_sn_one("select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='$menu_sn'");
  	}else{
  		$DBV=array();
  	}
	  //預設值設定
  	$menu_title    =(!isset($DBV['menu_title']))   ?"":$DBV['menu_title'];     //標題
  	$menu_enable   =(!isset($DBV['menu_enable']))  ?"1":$DBV['menu_enable'];   //顯示
  	$menu_new      =(!isset($DBV['menu_new']))     ?"0":$DBV['menu_new'];      //開新視窗
  	$menu_url      =(!isset($DBV['menu_url']))     ?"":$DBV['menu_url'];;      //網址
  	$menu_sort    =(!isset($DBV['menu_sort']))     ?get_max_menu_sort($menu_type,$menu_ofsn):$DBV['menu_sort'];
  	$menu_tip      =(!isset($DBV['menu_tip']))     ?"":$DBV['menu_tip'];
    //=========================================================================
    $menu_ofsn_0_form="";
    if($menu_ofsn!=0){
      $menu_ofsn_0_form="
        <tr><th>"._MD_UGMPAGE_MENU_TIP."</th>
      	    <td><label for='menu_tip'></label><input type='text' name='menu_tip' size='40' value='{$menu_tip}' id='menu_tip'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_URL."</th>
      	    <td><label for='menu_url'></label><input type='text' name='menu_url' size='40' value='{$menu_url}' id='menu_url'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_NEW."</th>
      	    <td><input type='radio' name='menu_new' id='menu_new_1' value='1' ".chk($menu_new,'1')."><label for='menu_new_1'>"._YES."</label>
                <input type='radio' name='menu_new' id='menu_new_0' value='0' ".chk($menu_new,'0')."><span><label for='menu_new_0'>"._NO."</label></span>
            </td>
        </tr>
      ";
    }
    $main.="{$formValidator_code}
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form' class='form-inline' class='form-inline'>
  	    <table id='form_table' class='table table-bordered table-condensed table-hover'>
   	      <tr><th colspan='2'>"._MD_UGMPAGE_MENU_T1_TITLE."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_TITLE."</th>
      	    <td><label for='menu_title'></label><input type='text' name='menu_title' size='40' value='{$menu_title}' id='menu_title' class='validate[required]'></td>
      	  {$menu_ofsn_0_form}
          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
      	      <td><input type='radio' name='menu_enable' id='menu_enable_1' value='1' ".chk($menu_enable,'1')."><label for='menu_enable_1'>"._YES."</label>
                  <input type='radio' name='menu_enable' id='menu_enable_0' value='0' ".chk($menu_enable,'0')."><span><label for='menu_enable_0'>"._NO."</label></span>
              </td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
      	      <td><label for='menu_sort'></label><input type='text' name='menu_sort' size='10' value='{$menu_sort}' id='menu_sort'></td>
          </tr>
    	    <tr><th colspan='2'>
    	      <input type='hidden' name='menu_sn' value='{$menu_sn}'>
    	      <input type='hidden' name='menu_type' value='{$menu_type}'>
    	      <input type='hidden' name='menu_ofsn' value='{$menu_ofsn}'>
          	<input type='hidden' name='op' value='op_insert'>
            <input type='submit' name='submit' value='"._MD_SAVE."'>
            </th></tr>
    	 </table>
    </form>
  ";
  if($menu_type==1){
    $main_title=_MD_UGMPAGE_MENU_T1_TITLE;
  }elseif($menu_type==5){
    $main_title=_MD_UGMPAGE_MENU_T5_TITLE;
  }
  //$main=ugm_div($main_title,$main);
  break;

  //============================================================================
  case "2": //圖片輪撥(2層)第一層是類別

    //---------------------  多檔上傳 ----------------------------------------------
  	$multiple_file_upload_code="<script src='".XOOPS_URL."/modules/tadtools/multiple-file-upload/jquery.MultiFile.js'></script>";
  	//--------------------------------------------------------------------------------
    //抓取預設值
    $slider_link_pic="";
	  $slider_link_txt="";
  	if(!$menu_sn==0){
			//編輯
			$DBV=get_sn_one("select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='$menu_sn'");
			# 處理滑動圖片
  		$slider_link_pic=get_one_pic("slider_link_pic",$menu_sn,"small");
  		//$slider_link_pic=(empty($slider_link_pic))?"":"<tr><td colspan=2>{$slider_link_pic}</td></tr>";
  		$slider_link_txt=get_one_description("slider_link_pic",$menu_sn,"small");


  	}else{
  		$DBV=array();
  	}
	  //預設值設定
  	$menu_title    =(!isset($DBV['menu_title']))   ?"":$DBV['menu_title'];     //標題
  	$menu_enable   =(!isset($DBV['menu_enable']))  ?"1":$DBV['menu_enable'];   //顯示
  	$menu_new      =(!isset($DBV['menu_new']))     ?"0":$DBV['menu_new'];      //開新視窗
  	$menu_url      =(!isset($DBV['menu_url']))     ?"":$DBV['menu_url'];;      //網址
  	$menu_sort    =(!isset($DBV['menu_sort']))     ?get_max_menu_sort($menu_type,$menu_ofsn):$DBV['menu_sort'];
  	$menu_tip      =(!isset($DBV['menu_tip']))     ?"":$DBV['menu_tip'];
    //=========================================================================
    $menu_ofsn_0_form=$menu_type2_upload_file="";
    if($menu_ofsn!=0){
      $menu_ofsn_0_form="
        <tr><th>"._MD_UGMPAGE_MENU_TIP."</th>
      	    <td><label for='menu_tip'></label><input type='text' name='menu_tip' size='40' value='{$menu_tip}' id='menu_tip'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_URL."</th>
      	    <td><label for='menu_url'></label><input type='text' name='menu_url' size='40' value='{$menu_url}' id='menu_url'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_NEW."</th>
      	    <td><input type='radio' name='menu_new' id='menu_new_1' value='1' ".chk($menu_new,'1')."><label for='menu_new_1'>"._YES."</label>
                <input type='radio' name='menu_new' id='menu_new_0' value='0' ".chk($menu_new,'0')."><span><label for='menu_new_0'>"._NO."</label></span>
            </td>
        </tr>
      ";


     $menu_type2_upload_file="
           <tr><th>"._MD_UGMPAGE_SLIDER."</th>
               <td><input type='file' name='slider_link_pic[]' maxlength='2' accept='gif|jpg|png' ><br>{$slider_link_pic}</td>
           </tr>

           <tr><th>"._MD_UGMPAGE_SLIDER_TXT."</th>
               <td><textarea name='slider_link_txt' style='width:550px;height:80px;font-size:12px;'>$slider_link_txt</textarea></td>
           </tr>
     ";

    }
    $main.="{$formValidator_code}{$multiple_file_upload_code}
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form' enctype='multipart/form-data' class='form-inline'>
  	    <table  id='form_table' class='table table-bordered table-condensed table-hover'>
   	      <tr><th colspan='2'>"._MD_UGMPAGE_MENU_T2_TITLE._MD_UGMPAGE_MENU_FORM."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_TITLE."</th>
      	    <td><label for='menu_title'></label><input type='text' name='menu_title' size='40' value='{$menu_title}' id='menu_title' class='validate[required]'></td>
      	  {$menu_ofsn_0_form}
          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
      	      <td><input type='radio' name='menu_enable' id='menu_enable_1' value='1' ".chk($menu_enable,'1')."><label for='menu_enable_1'>"._YES."</label>
                  <input type='radio' name='menu_enable' id='menu_enable_0' value='0' ".chk($menu_enable,'0')."><span><label for='menu_enable_0'>"._NO."</label></span>
              </td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
      	      <td><label for='menu_sort'></label><input type='text' name='menu_sort' size='10' value='{$menu_sort}' id='menu_sort'></td>
          </tr>
          {$menu_type2_upload_file}
    	    <tr><th colspan='2'>
    	      <input type='hidden' name='menu_sn' value='{$menu_sn}'>
    	      <input type='hidden' name='menu_type' value='{$menu_type}'>
    	      <input type='hidden' name='menu_ofsn' value='{$menu_ofsn}'>
          	<input type='hidden' name='op' value='op_insert'>
            <input type='submit' name='submit' value='"._MD_SAVE."'>
            </th></tr>
    	 </table>
    </form>
       ";
  //$main=ugm_div(_MD_UGMPAGE_MENU_T2_TITLE,$main);
  break;

  //============================================================================
  case "3": //跑馬燈(2層)第一層是類別 (不傳檔案)
  	//抓取預設值
  	if(!$menu_sn==0){
			//編輯
			$DBV=get_sn_one("select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='$menu_sn'");
  	}else{
  		$DBV=array();
  	}
	  //預設值設定
  	$menu_title    =(!isset($DBV['menu_title']))   ?"":$DBV['menu_title'];     //標題
  	$menu_enable   =(!isset($DBV['menu_enable']))  ?"1":$DBV['menu_enable'];   //顯示
  	$menu_new      =(!isset($DBV['menu_new']))     ?"0":$DBV['menu_new'];      //開新視窗
  	$menu_url      =(!isset($DBV['menu_url']))     ?"":$DBV['menu_url'];;      //網址
  	$menu_sort    =(!isset($DBV['menu_sort']))     ?get_max_menu_sort($menu_type,$menu_ofsn):$DBV['menu_sort'];
  	$menu_tip      =(!isset($DBV['menu_tip']))     ?"":$DBV['menu_tip'];
    //=========================================================================
    $menu_ofsn_0_form="";
    if($menu_ofsn!=0){
      $menu_ofsn_0_form="
        <tr><th>"._MD_UGMPAGE_MENU_TIP."</th>
      	    <td><label for='menu_tip'></label><input type='text' name='menu_tip' size='40' value='{$menu_tip}' id='menu_tip'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_URL."</th>
      	    <td><label for='menu_url'></label><input type='text' name='menu_url' size='40' value='{$menu_url}' id='menu_url'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_NEW."</th>
      	    <td><input type='radio' name='menu_new' id='menu_new_1' value='1' ".chk($menu_new,'1')."><label for='menu_new_1'>"._YES."</label>
                <input type='radio' name='menu_new' id='menu_new_0' value='0' ".chk($menu_new,'0')."><span><label for='menu_new_0'>"._NO."</label></span>
            </td>
        </tr>
      ";
    }
    $main.="{$formValidator_code}
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form' class='form-inline'>
  	    <table  id='form_table' class='table table-bordered table-condensed table-hover'>
   	      <tr><th colspan='2'>"._MD_UGMPAGE_MENU_T3_TITLE._MD_UGMPAGE_MENU_FORM."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_TITLE."</th>
      	    <td><label for='menu_title'></label><input type='text' name='menu_title' size='40' value='{$menu_title}' id='menu_title' class='validate[required]'></td>
      	  {$menu_ofsn_0_form}
          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
      	      <td><input type='radio' name='menu_enable' id='menu_enable_1' value='1' ".chk($menu_enable,'1')."><label for='menu_enable_1'>"._YES."</label>
                  <input type='radio' name='menu_enable' id='menu_enable_0' value='0' ".chk($menu_enable,'0')."><span><label for='menu_enable_0'>"._NO."</label></span>
              </td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
      	      <td><label for='menu_sort'></label><input type='text' name='menu_sort' size='10' value='{$menu_sort}' id='menu_sort'></td>
          </tr>
    	    <tr><th colspan='2'>
    	      <input type='hidden' name='menu_sn' value='{$menu_sn}'>
    	      <input type='hidden' name='menu_type' value='{$menu_type}'>
    	      <input type='hidden' name='menu_ofsn' value='{$menu_ofsn}'>
          	<input type='hidden' name='op' value='op_insert'>
            <input type='submit' name='submit' value='"._MD_SAVE."'>
            </th></tr>
    	 </table>
    </form>
       ";
  //$main=ugm_div(_MD_UGMPAGE_MENU_T3_TITLE,$main);
  break;
  //============================================================================
  case "4": //下拉選單(4層)第一層是類別
  	//抓取預設值
  	if(!$menu_sn==0){
			//編輯
			$DBV=get_sn_one("select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='$menu_sn'");
  	}else{
  		$DBV=array();
  	}
	  //預設值設定
  	$menu_title    =(!isset($DBV['menu_title']))   ?"":$DBV['menu_title'];     //標題
  	$menu_enable   =(!isset($DBV['menu_enable']))  ?"1":$DBV['menu_enable'];   //顯示
  	$menu_new      =(!isset($DBV['menu_new']))     ?"0":$DBV['menu_new'];      //開新視窗
  	$menu_url      =(!isset($DBV['menu_url']))     ?"":$DBV['menu_url'];;      //網址
  	$menu_sort    =(!isset($DBV['menu_sort']))     ?get_max_menu_sort($menu_type,$menu_ofsn):$DBV['menu_sort'];
  	$menu_tip      =(!isset($DBV['menu_tip']))     ?"":$DBV['menu_tip'];
    //=========================================================================
    $menu_ofsn_0_form="";
    if($menu_ofsn!=0){
      $menu_ofsn_0_form="
        <tr><th>"._MD_UGMPAGE_MENU_TIP."</th>
      	    <td><label for='menu_tip'></label><input type='text' name='menu_tip' size='40' value='{$menu_tip}' id='menu_tip'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_URL."</th>
      	    <td><label for='menu_url'></label><input type='text' name='menu_url' size='40' value='{$menu_url}' id='menu_url'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_NEW."</th>
      	    <td><input type='radio' name='menu_new' id='menu_new_1' value='1' ".chk($menu_new,'1')."><label for='menu_new_1'>"._YES."</label>
                <input type='radio' name='menu_new' id='menu_new_0' value='0' ".chk($menu_new,'0')."><span><label for='menu_new_0'>"._NO."</label></span>
            </td>
        </tr>
      ";
    }
    $main.="{$formValidator_code}
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form' class='form-inline'>
  	    <table id='{$DIRNAME}_table' border='0' cellspacing='3' cellpadding='3' class='ugm_tb'>
   	      <tr><th colspan='2'>"._MD_UGMPAGE_MENU_T4_TITLE."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_TITLE."</th>
      	    <td><label for='menu_title'></label><input type='text' name='menu_title' size='40' value='{$menu_title}' id='menu_title' class='validate[required]'></td>
      	  {$menu_ofsn_0_form}
          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
      	      <td><input type='radio' name='menu_enable' id='menu_enable_1' value='1' ".chk($menu_enable,'1')."><label for='menu_enable_1'>"._YES."</label>
                  <input type='radio' name='menu_enable' id='menu_enable_0' value='0' ".chk($menu_enable,'0')."><span><label for='menu_enable_0'>"._NO."</label></span>
              </td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
      	      <td><label for='menu_sort'></label><input type='text' name='menu_sort' size='10' value='{$menu_sort}' id='menu_sort'></td>
          </tr>
    	    <tr><th colspan='2'>
    	      <input type='hidden' name='menu_sn' value='{$menu_sn}'>
    	      <input type='hidden' name='menu_type' value='{$menu_type}'>
    	      <input type='hidden' name='menu_ofsn' value='{$menu_ofsn}'>
          	<input type='hidden' name='op' value='op_insert'>
            <input type='submit' name='submit' value='"._MD_SAVE."'>
            </th></tr>
    	 </table>
    </form>
       ";
  //$main=ugm_div(_MD_UGMPAGE_MENU_T4_TITLE,$main);
  break;
  /*
  case "5": //單層選單(2層)第一層是類別
  	//抓取預設值
  	if(!$menu_sn==0){
			//編輯
			$DBV=get_sn_one("select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where menu_sn='$menu_sn'");
  	}else{
  		$DBV=array();
  	}
	  //預設值設定
  	$menu_title    =(!isset($DBV['menu_title']))   ?"":$DBV['menu_title'];     //標題
  	$menu_enable   =(!isset($DBV['menu_enable']))  ?"1":$DBV['menu_enable'];   //顯示
  	$menu_new      =(!isset($DBV['menu_new']))     ?"0":$DBV['menu_new'];      //開新視窗
  	$menu_url      =(!isset($DBV['menu_url']))     ?"":$DBV['menu_url'];;      //網址
  	$menu_sort    =(!isset($DBV['menu_sort']))     ?get_max_menu_sort($menu_type,$menu_ofsn):$DBV['menu_sort'];
  	$menu_tip      =(!isset($DBV['menu_tip']))     ?"":$DBV['menu_tip'];
    //=========================================================================
    $menu_ofsn_0_form="";
    if($menu_ofsn!=0){
      $menu_ofsn_0_form="
        <tr><th>"._MD_UGMPAGE_MENU_TIP."</th>
      	    <td><label for='menu_tip'></label><input type='text' name='menu_tip' size='40' value='{$menu_tip}' id='menu_tip'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_URL."</th>
      	    <td><label for='menu_url'></label><input type='text' name='menu_url' size='40' value='{$menu_url}' id='menu_url'></td>
        </tr>
        <tr><th>"._MD_UGMPAGE_MENU_NEW."</th>
      	    <td><input type='radio' name='menu_new' id='menu_new_1' value='1' ".chk($menu_new,'1')."><label for='menu_new_1'>"._YES."</label>
                <input type='radio' name='menu_new' id='menu_new_0' value='0' ".chk($menu_new,'0')."><span><label for='menu_new_0'>"._NO."</label></span>
            </td>
        </tr>
      ";
    }
    $main.="{$formValidator_code}
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form'>
  	    <table id='{$DIRNAME}_table' border='0' cellspacing='3' cellpadding='3' class='ugm_tb'>
   	      <tr><th colspan='2'>"._MD_UGMPAGE_MENU_T1_TITLE."</th></tr>
          <tr><th style='width:120px;'>"._MD_UGMPAGE_TITLE."</th>
      	    <td><label for='menu_title'></label><input type='text' name='menu_title' size='40' value='{$menu_title}' id='menu_title' class='validate[required]'></td>
      	  {$menu_ofsn_0_form}
          <tr><th>"._MD_UGMPAGE_CATE_ENABLE."</th>
      	      <td><input type='radio' name='menu_enable' id='menu_enable_1' value='1' ".chk($menu_enable,'1')."><label for='menu_enable_1'>"._YES."</label>
                  <input type='radio' name='menu_enable' id='menu_enable_0' value='0' ".chk($menu_enable,'0')."><span><label for='menu_enable_0'>"._NO."</label></span>
              </td>
          </tr>
          <tr><th>"._MD_UGMPAGE_CATE_SORT."</th>
      	      <td><label for='menu_sort'></label><input type='text' name='menu_sort' size='10' value='{$menu_sort}' id='menu_sort'></td>
          </tr>
    	    <tr><th colspan='2'>
    	      <input type='hidden' name='menu_sn' value='{$menu_sn}'>
    	      <input type='hidden' name='menu_type' value='{$menu_type}'>
    	      <input type='hidden' name='menu_ofsn' value='{$menu_ofsn}'>
          	<input type='hidden' name='op' value='op_insert'>
            <input type='submit' name='submit' value='"._MD_SAVE."'>
            </th></tr>
    	 </table>
    </form>
       ";
  $main=ugm_div(_MD_UGMPAGE_MENU_T1_TITLE,$main);
  break;
  */
  }
  return $main;
}
################################################################################
#   取得選單選項get_menu_option
#
#
#
#
################################################################################
function get_menu_option($menu_type=0,$menu_ofsn=0,$menu_sn_chk=0,$stop_level=1,$level=0){
  global $xoopsDB;
  if($level>=$stop_level)return;
  $level++;
  $sql = "select `menu_sn`,`menu_title` from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where `menu_type`='{$menu_type}' and `menu_ofsn`='{$menu_ofsn}'  order by `menu_sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $i=1;
  $level_mark="";
  while ($level-$i>0){
    $level_mark.="&nbsp;&nbsp;&nbsp;";
    $i++;
  }
  while($all=$xoopsDB->fetchArray($result) ){
  //以下會產生這些變數： 【$menu_sn,$title】
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $selected=($menu_sn==$menu_sn_chk)?" selected=selected":"";
    $main.="<option value={$menu_sn}{$selected}>{$level_mark}{$menu_title}</option>";
    $main.=get_menu_option($menu_type,$menu_sn,$menu_sn_chk,$stop_level,$level);
  }
  return $main;
}
//列出所有u_menu資料
function op_list($menu_type=0,$menu_ofsn=0,$menu_sn=0){
	global $xoopsDB,$xoopsModule,$xoopsConfig;
  $_SESSION['ugm_return']=$_GET['menu_sn']?$_GET['menu_sn']:"all";
  $DIRNAME=$xoopsModule->getVar('dirname');
	# 連結選單
  $head_ugm_direct="
     <li>
       <div class='bc_separator'>
         <select name='menu_type' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type=\"+this.value' style='width:100px;'>
            <option value='1' ".chk($menu_type,1,1,"selected").">"._MD_UGMPAGE_MENU_T1_TITLE."</option>
            <option value='2' ".chk($menu_type,2,0,"selected").">"._MD_UGMPAGE_MENU_T2_TITLE."</option>
            <option value='3' ".chk($menu_type,3,0,"selected").">"._MD_UGMPAGE_MENU_T3_TITLE."</option>
            <option value='4' ".chk($menu_type,4,0,"selected").">"._MD_UGMPAGE_MENU_T4_TITLE."</option>
            <option value='5' ".chk($menu_type,5,0,"selected").">"._MD_UGMPAGE_MENU_T5_TITLE."</option>
         </select>
       </div>
     </li>
   ";
  switch($menu_type){
  case "1": //伸縮選單(三層，第一層索引)
    $stop_level=3;
    $level=0;
    $last_url=0;//最後一層不須連結
    $selected=($menu_sn==0)?" selected=selected":"";//沒有傳選單，則選擇全部
    $direct_menu=($menu_sn==0)?"":direct_menu($menu_type,$menu_sn,$level,$last_url);
    //-------------------------------------------------------------------
    $ugm_direct="
      <ul class='ugm_direct'>
        {$head_ugm_direct}
        <li>
          <div class='bc_separator'>
            <select name='menu_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=\"+this.value'>
              <option value='all'{$selected}>"._MD_UGMPAGE_MENU_ALL."</option>".get_menu_option($menu_type,$menu_ofsn,$menu_sn,$stop_level-1)."
            </select>
          </div>
        </li>
        <li>
          <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn=0' class='bc_separator'>&nbsp;<img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='"._MD_UGMPAGE_MENU_ADD_CATE."' >"._MD_UGMPAGE_MENU_ADD_CATE."</a>
        </li>
        <li >
           <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=all' class='bc_separator'>"._MD_UGMPAGE_MENU_HOME." </a>
        </li>
        $direct_menu
      </ul>
      ";
    //$ugm_direct=ugm_div("",$ugm_direct,"shadow1");
  //------------------------------------------------------------------
    $main.="
      <script>
  	    function delete_{$DIRNAME}_func(menu_sn){
  		    var sure = window.confirm('"._BP_DEL_CHK."');
  		    if (!sure)	return;
  		    location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&menu_type={$menu_type}&menu_sn=\" + menu_sn;
  	    }
  	  </script>
      <div id='{$DIRNAME}_table'>
      <form action='{$_SERVER['PHP_SELF']}' method='post'>

      $ugm_direct
  	  <table  id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_MENU_NEW."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th style='width:200px'>"._MD_UGMPAGE_MENU_URL."</th>
        	<th style='width:80px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($menu_type,$menu_ofsn,$menu_sn,$stop_level,$level);//(type,of_sn,sn,level,stop_level)
  	$main.="</table></form></div>";
  	//$main=ugm_div(_MD_UGMPAGE_MENU_T1_TITLE,$main,"");
  break;

  case "2": //圖片輪撥(二層，第一層索引)
    $stop_level=2;
    $level=0;
    $last_url=0;//最後一層不須連結
    $selected=($menu_sn==0)?" selected=selected":"";//沒有傳選單，則選擇全部
    $direct_menu=($menu_sn==0)?"":direct_menu($menu_type,$menu_sn,$level,$last_url);
    //-------------------------------------------------------------------
    $ugm_direct="
      <ul class='ugm_direct'>
        {$head_ugm_direct}
        <li>
          <div class='bc_separator'>
            <select name='menu_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=\"+this.value'>
              <option value='all'{$selected}>"._MD_UGMPAGE_MENU_ALL."</option>".get_menu_option($menu_type,$menu_ofsn,$menu_sn,$stop_level-1)."
            </select>
          </div>
        </li>
        <li>
          <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn=0' class='bc_separator'>&nbsp;<img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='"._MD_UGMPAGE_MENU_ADD_CATE."' >"._MD_UGMPAGE_MENU_ADD_CATE."</a>
        </li>
        <li >
           <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=all' class='bc_separator'>"._MD_UGMPAGE_MENU_HOME." </a>
        </li>
        $direct_menu
      </ul>
      ";
    //$ugm_direct=ugm_div("",$ugm_direct,"shadow1");
  //------------------------------------------------------------------
    $main.="
      <script>
  	    function delete_{$DIRNAME}_func(menu_sn){
  		    var sure = window.confirm('"._BP_DEL_CHK."');
  		    if (!sure)	return;
  		    location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&menu_type={$menu_type}&menu_sn=\" + menu_sn;
  	    }
  	  </script>
      <div id='{$DIRNAME}_table'>
      <form action='{$_SERVER['PHP_SELF']}' method='post'>

      $ugm_direct
  	  <table  id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_MENU_NEW."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th style='width:200px'>"._MD_UGMPAGE_MENU_URL."</th>
        	<th style='width:80px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($menu_type,$menu_ofsn,$menu_sn,$stop_level,$level);//(type,of_sn,sn,level,stop_level)
  	$main.="</table></form></div>";
  	//$main=ugm_div(_MD_UGMPAGE_MENU_T2_TITLE,$main,"");
  break;

  case "3": //跑馬燈(二層，第一層索引)
    $stop_level=2;
    $level=0;
    $last_url=0;//最後一層不須連結
    $selected=($menu_sn==0)?" selected=selected":"";//沒有傳選單，則選擇全部
    $direct_menu=($menu_sn==0)?"":direct_menu($menu_type,$menu_sn,$level,$last_url);
    //-------------------------------------------------------------------
    $ugm_direct="
      <ul class='ugm_direct'>
        {$head_ugm_direct}
        <li>
          <div class='bc_separator'>
            <select name='menu_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=\"+this.value'>
              <option value='all'{$selected}>"._MD_UGMPAGE_MENU_ALL."</option>".get_menu_option($menu_type,$menu_ofsn,$menu_sn,$stop_level-1)."
            </select>
          </div>
        </li>
        <li>
          <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn=0' class='bc_separator'>&nbsp;<img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='"._MD_UGMPAGE_MENU_ADD_CATE."' >"._MD_UGMPAGE_MENU_ADD_CATE."</a>
        </li>
        <li >
           <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=all' class='bc_separator'>"._MD_UGMPAGE_MENU_HOME." </a>
        </li>
        $direct_menu
      </ul>
      ";
    //$ugm_direct=ugm_div("",$ugm_direct,"shadow1");
  //------------------------------------------------------------------
    $main.="
      <script>
  	    function delete_{$DIRNAME}_func(menu_sn){
  		    var sure = window.confirm('"._BP_DEL_CHK."');
  		    if (!sure)	return;
  		    location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&menu_type={$menu_type}&menu_sn=\" + menu_sn;
  	    }
  	  </script>
      <div id='{$DIRNAME}_table'>
      <form action='{$_SERVER['PHP_SELF']}' method='post'>

      $ugm_direct
  	  <table id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_MENU_NEW."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th style='width:200px'>"._MD_UGMPAGE_MENU_URL."</th>
        	<th style='width:80px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($menu_type,$menu_ofsn,$menu_sn,$stop_level,$level);//(type,of_sn,sn,level,stop_level)
  	$main.="</table></form></div>";
  	//$main=ugm_div(_MD_UGMPAGE_MENU_T3_TITLE,$main,"");
  break;

  case "4": //下拉選單(四層，第一層索引)
    $stop_level=4;
    $level=0;
    $last_url=0;//最後一層不須連結
    $selected=($menu_sn==0)?" selected=selected":"";//沒有傳選單，則選擇全部
    $direct_menu=($menu_sn==0)?"":direct_menu($menu_type,$menu_sn,$level,$last_url);
    //-------------------------------------------------------------------
    $ugm_direct="
      <ul class='ugm_direct'>
        {$head_ugm_direct}
        <li>
          <div class='bc_separator'>
            <select name='menu_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=\"+this.value' style='width:160px;'>
              <option value='all'{$selected}>"._MD_UGMPAGE_MENU_ALL."</option>".get_menu_option($menu_type,$menu_ofsn,$menu_sn,$stop_level-1)."
            </select>
          </div>
        </li>
        <li>
          <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn=0' class='bc_separator'>&nbsp;<img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='"._MD_UGMPAGE_MENU_ADD_CATE."' >"._MD_UGMPAGE_MENU_ADD_CATE."</a>
        </li>
        <li >
           <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=all' class='bc_separator'>"._MD_UGMPAGE_MENU_HOME." </a>
        </li>
        $direct_menu
      </ul>
      ";
    //$ugm_direct=ugm_div("",$ugm_direct,"shadow1");
  //------------------------------------------------------------------
    $main.="
      <script>
  	    function delete_{$DIRNAME}_func(menu_sn){
  		    var sure = window.confirm('"._BP_DEL_CHK."');
  		    if (!sure)	return;
  		    location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&menu_type={$menu_type}&menu_sn=\" + menu_sn;
  	    }
  	  </script>
      <div id='{$DIRNAME}_table'>
      <form action='{$_SERVER['PHP_SELF']}' method='post'>

      $ugm_direct
  	  <table  id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_MENU_NEW."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th style='width:200px'>"._MD_UGMPAGE_MENU_URL."</th>
        	<th style='width:80px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($menu_type,$menu_ofsn,$menu_sn,$stop_level,$level);//(type,of_sn,sn,level,stop_level)
  	$main.="</table></form></div>";
  	//$main=ugm_div(_MD_UGMPAGE_MENU_T4_TITLE,$main,"");
  break;

  case "5": //單層選單(2層，第一層索引)
    $stop_level=2;
    $level=0;
    $last_url=0;//最後一層不須連結
    $selected=($menu_sn==0)?" selected=selected":"";//沒有傳選單，則選擇全部
    $direct_menu=($menu_sn==0)?"":direct_menu($menu_type,$menu_sn,$level,$last_url);
    //-------------------------------------------------------------------
    $ugm_direct="
      <ul class='ugm_direct'>
        {$head_ugm_direct}
        <li>
          <div class='bc_separator'>
            <select name='menu_sn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=\"+this.value'>
              <option value='all'{$selected}>"._MD_UGMPAGE_MENU_ALL."</option>".get_menu_option($menu_type,$menu_ofsn,$menu_sn,$stop_level-1)."
            </select>
          </div>
        </li>
        <li>
          <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn=0' class='bc_separator'>&nbsp;<img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='"._MD_UGMPAGE_MENU_ADD_CATE."' >"._MD_UGMPAGE_MENU_ADD_CATE."</a>
        </li>
        <li >
           <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn=all' class='bc_separator'>"._MD_UGMPAGE_MENU_HOME." </a>
        </li>
        $direct_menu
      </ul>
      ";
    //$ugm_direct=ugm_div("",$ugm_direct,"shadow1");
  //------------------------------------------------------------------
    $main.="
      <script>
  	    function delete_{$DIRNAME}_func(menu_sn){
  		    var sure = window.confirm('"._BP_DEL_CHK."');
  		    if (!sure)	return;
  		    location.href=\"{$_SERVER['PHP_SELF']}?op=op_del&menu_type={$menu_type}&menu_sn=\" + menu_sn;
  	    }
  	  </script>
      <div id='{$DIRNAME}_table'>
      <form action='{$_SERVER['PHP_SELF']}' method='post'>

      $ugm_direct
  	  <table  id='form_table' class='table table-bordered table-condensed table-hover'>
      	<tr>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_MENU_NEW."</th>
        	<th style='width:30px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th style='width:200px'>"._MD_UGMPAGE_MENU_URL."</th>
        	<th style='width:80px;'>"._BP_FUNCTION."</th>
      	</tr>
      	";
  	$main.=get_list_body($menu_type,$menu_ofsn,$menu_sn,$stop_level,$level);//(type,of_sn,sn,level,stop_level)
  	$main.="</table></form></div>";
  	//$main=ugm_div(_MD_UGMPAGE_MENU_T5_TITLE,$main,"");
  break;


	default:
	  $main=$xoopsConfig['language'];
	break;
  }

	return $main;
}


################################################################################
#   表單身體
#   // ugm_list_body
#
#
#
################################################################################
function get_list_body($menu_type=0,$menu_ofsn=0,$menu_sn=0,$stop_level=1,$level=0){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  if($level>=$stop_level)return;
  $level++;
  if($menu_sn==0){
    $sql = "select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where `menu_ofsn`={$menu_ofsn} and `menu_type`={$menu_type}  order by `menu_sort`";
  }else{
    $sql = "select * from ".$xoopsDB->prefix(_DB_MENU_TABLE)."  where  `menu_sn`='{$menu_sn}' ";
  }
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  while($all=$xoopsDB->fetchArray($result) ){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    switch($menu_type){
      case "1": //伸縮選單(3層)
        $chk_level=chk_level($menu_sn);
        $create_sub=($chk_level<$stop_level-1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn={$menu_sn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_MENU_ADD_SUB,$menu_title)."'></a>":"";//是否可以新增
        if($chk_level==0){
          #第一層
					$menu_new="";
          $menu_url="";//選單選項
				}else{
          $menu_new=($menu_new==1)?"<span>"._YES."<span>":_NO;
				}
        $menu_enable=($menu_enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=1'><img src='images/off.png' /></a>";//啟用
		    //$menu_enable=($menu_enable==1)?_YES:"<span>"._NO."<span>";
    		$main.="<tr class='level_{$chk_level}'>
        <td style='text-indent:".($chk_level*12)."pt;'>{$create_sub}{$menu_title}</td>
        <td class='align_c'>{$menu_enable}</td>
        <td class='align_c'>{$menu_new}</td>
        <td class='align_c'>{$menu_sort}</td>
        <td>{$menu_url}</td>
    		<td>
		      <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_sn={$menu_sn}&menu_ofsn={$menu_ofsn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_edit.gif' title='"._BP_EDIT."'></a>
		      <a href=\"javascript:delete_{$DIRNAME}_func($menu_sn);\"><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_del.gif' title='"._BP_DEL."'></a>
    		</td>
    		</tr>";
        $main.=get_list_body($menu_type,$menu_sn,0,$stop_level,$level);
      break;

      case "2": //圖片輪撥(2層)
        $chk_level=chk_level($menu_sn);
        $create_sub=($chk_level<$stop_level-1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn={$menu_sn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_MENU_ADD_SUB,$menu_title)."'></a>":"";//是否可以新增
        if($chk_level==0){
          #第一層
					$menu_new="";
          $menu_url="";//選單選項
				}else{
          $menu_new=($menu_new==1)?"<span>"._YES."<span>":_NO;
				}
        $menu_enable=($menu_enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=1'><img src='images/off.png' /></a>";//啟用
		    //$menu_enable=($menu_enable==1)?_YES:"<span>"._NO."<span>";
    		$main.="<tr class='level_{$chk_level}'>
        <td style='text-indent:".($chk_level*12)."pt;'>{$create_sub}{$menu_title}</td>
        <td class='align_c'>{$menu_enable}</td>
        <td class='align_c'>{$menu_new}</td>
        <td class='align_c'>{$menu_sort}</td>
        <td>{$menu_url}</td>
    		<td>
		      <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_sn={$menu_sn}&menu_ofsn={$menu_ofsn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_edit.gif' title='"._BP_EDIT."'></a>
		      <a href=\"javascript:delete_{$DIRNAME}_func($menu_sn);\"><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_del.gif' title='"._BP_DEL."'></a>
    		</td>
    		</tr>";
        $main.=get_list_body($menu_type,$menu_sn,0,$stop_level,$level);
      break;

      case "3": //跑馬燈(2層)
        $chk_level=chk_level($menu_sn);
        $create_sub=($chk_level<$stop_level-1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn={$menu_sn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_MENU_ADD_SUB,$menu_title)."'></a>":"";//是否可以新增
        if($chk_level==0){
          #第一層
					$menu_new="";
          $menu_url="";//選單選項
				}else{
          $menu_new=($menu_new==1)?"<span>"._YES."<span>":_NO;
				}
        $menu_enable=($menu_enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=1'><img src='images/off.png' /></a>";//啟用
		    //$menu_enable=($menu_enable==1)?_YES:"<span>"._NO."<span>";
    		$main.="<tr class='level_{$chk_level}'>
        <td style='text-indent:".($chk_level*12)."pt;'>{$create_sub}{$menu_title}</td>
        <td class='align_c'>{$menu_enable}</td>
        <td class='align_c'>{$menu_new}</td>
        <td class='align_c'>{$menu_sort}</td>
        <td>{$menu_url}</td>
    		<td>
		      <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_sn={$menu_sn}&menu_ofsn={$menu_ofsn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_edit.gif' title='"._BP_EDIT."'></a>
		      <a href=\"javascript:delete_{$DIRNAME}_func($menu_sn);\"><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_del.gif' title='"._BP_DEL."'></a>
    		</td>
    		</tr>";
        $main.=get_list_body($menu_type,$menu_sn,0,$stop_level,$level);
      break;

      case "4": //下拉選單(4層)
        $chk_level=chk_level($menu_sn);
        $create_sub=($chk_level<$stop_level-1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn={$menu_sn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_MENU_ADD_SUB,$menu_title)."'></a>":"";//是否可以新增
        if($chk_level==0){
          #第一層
					$menu_new="";
          $menu_url="";//選單選項
				}else{
          $menu_new=($menu_new==1)?"<span>"._YES."<span>":_NO;
				}
        $menu_enable=($menu_enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=1'><img src='images/off.png' /></a>";//啟用
		    //$menu_enable=($menu_enable==1)?_YES:"<span>"._NO."<span>";
    		$main.="<tr class='level_{$chk_level}'>
        <td style='text-indent:".($chk_level*12)."pt;'>{$create_sub}{$menu_title}</td>
        <td class='align_c'>{$menu_enable}</td>
        <td class='align_c'>{$menu_new}</td>
        <td class='align_c'>{$menu_sort}</td>
        <td>{$menu_url}</td>
    		<td>
		      <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_sn={$menu_sn}&menu_ofsn={$menu_ofsn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_edit.gif' title='"._BP_EDIT."'></a>
		      <a href=\"javascript:delete_{$DIRNAME}_func($menu_sn);\"><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_del.gif' title='"._BP_DEL."'></a>
    		</td>
    		</tr>";
        $main.=get_list_body($menu_type,$menu_sn,0,$stop_level,$level);
      break;

      case "5": //單層選單(2層)
        $chk_level=chk_level($menu_sn);
        $create_sub=($chk_level<$stop_level-1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_ofsn={$menu_sn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_new_cate.gif' title='".sprintf(_MD_UGMPAGE_MENU_ADD_SUB,$menu_title)."'></a>":"";//是否可以新增
        if($chk_level==0){
          #第一層
					$menu_new="";
          $menu_url="";//選單選項
				}else{
          $menu_new=($menu_new==1)?"<span>"._YES."<span>":_NO;
				}
        $menu_enable=($menu_enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&menu_sn={$menu_sn}&menu_type={$menu_type}&menu_enable=1'><img src='images/off.png' /></a>";//啟用
		    //$menu_enable=($menu_enable==1)?_YES:"<span>"._NO."<span>";
    		$main.="<tr class='level_{$chk_level}'>
        <td style='text-indent:".($chk_level*12)."pt;'>{$create_sub}{$menu_title}</td>
        <td class='align_c'>{$menu_enable}</td>
        <td class='align_c'>{$menu_new}</td>
        <td class='align_c'>{$menu_sort}</td>
        <td>{$menu_url}</td>
    		<td>
		      <a href='{$_SERVER['PHP_SELF']}?op=op_form&menu_type={$menu_type}&menu_sn={$menu_sn}&menu_ofsn={$menu_ofsn}'><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_edit.gif' title='"._BP_EDIT."'></a>
		      <a href=\"javascript:delete_{$DIRNAME}_func($menu_sn);\"><img src='".XOOPS_URL."/modules/{$DIRNAME}/images/i_del.gif' title='"._BP_DEL."'></a>
    		</td>
    		</tr>";
        $main.=get_list_body($menu_type,$menu_sn,0,$stop_level,$level);
      break;

      default://預設動作
	    break;
    }
  }

  # 需分頁的type
  if($menu_type=="0" || $menu_type=="11"){
    $main.="<tr class='bar'><th colspan=6>{$bar}</th></tr>";
  }
  return $main;
}
################################################################################
#  儲存新增資料
#  2層2個項目
#  ugm_insert
################################################################################
function op_insert($menu_type=0,$menu_ofsn=0,$menu_sn=0){
  global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsConfig;
  if(!$xoopsUser)return;
  $menu_uid=$xoopsUser->getVar('uid');
  $DIRNAME=$xoopsModule->getVar('dirname');
  $menu_date=strtotime("now");//現在時間
  /***************************** 過瀘資料 *************************/
  $menu_title=SqlFilter($_POST['menu_title'],"trim,addslashes,strip_tags");
  $menu_enable=intval($_POST['menu_enable']);
  $menu_sort=intval($_POST['menu_sort']);
  /****************************************************************/
  switch($menu_type){
  case "5"://單層選單
	case "1"://伸縮選單
	  if($menu_ofsn==0){
      //--------------------   類別 --------------------------------------------------
      if($menu_sn==0){
        //新增
        $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}')";
			}else{
        //編輯
        $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}' where `menu_sn`='{$menu_sn}'";
      }
    }else{
     //-------------------   選單   ---------------------------------------------------
     /***************************** 過瀘資料 *************************/
     $menu_tip=SqlFilter($_POST['menu_tip'],"trim,addslashes,strip_tags");
     $menu_new=intval($_POST['menu_new']);
     $menu_url=SqlFilter($_POST['menu_url'],"trim,addslashes");
     /****************************************************************/
     if($menu_sn==0){
       //新增
       $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`,`menu_tip`,`menu_new`,`menu_url`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}','{$menu_tip}','{$menu_new}','{$menu_url}')";
		 }else{
       //編輯
       $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}', `menu_tip` = '{$menu_tip}', `menu_new` = '{$menu_new}', `menu_url` = '{$menu_url}' where `menu_sn`='{$menu_sn}'";
      }
    }
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    if($menu_sn==0 and $menu_ofsn==0){
  		//取得最後新增資料的流水編號(新增)
  		$menu_sn=$xoopsDB->getInsertId();
  	}else{
      $menu_sn=$menu_ofsn;
    }
    return $menu_sn;
	break;

	case "2"://圖片輪播
	  if($menu_ofsn==0){
      //--------------------   類別 --------------------------------------------------
      if($menu_sn==0){
        //新增
        $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}')";
			}else{
        //編輯
        $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}' where `menu_sn`='{$menu_sn}'";
      }
    }else{
     //-------------------   選單   ---------------------------------------------------
     /***************************** 過瀘資料 *************************/
     $menu_tip=SqlFilter($_POST['menu_tip'],"trim,addslashes,strip_tags");
     $menu_new=intval($_POST['menu_new']);
     $menu_url=SqlFilter($_POST['menu_url'],"trim,addslashes");
     /****************************************************************/
     if($menu_sn==0){
       //新增
       $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`,`menu_tip`,`menu_new`,`menu_url`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}','{$menu_tip}','{$menu_new}','{$menu_url}')";
		 }else{
       //編輯
       $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}', `menu_tip` = '{$menu_tip}', `menu_new` = '{$menu_new}', `menu_url` = '{$menu_url}' where `menu_sn`='{$menu_sn}'";
      }
    }
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    if($menu_sn==0){
  		//取得最後新增資料的流水編號(新增)
  		$menu_sn=$xoopsDB->getInsertId();
  	}
  	if($menu_ofsn!=0){
      //處理上傳的檔案
      $slider_link_txt=SqlFilter($_POST['slider_link_txt'],"trim,addslashes");
	    upload_file('slider_link_pic',"slider_link_pic",$menu_sn,null,1,$slider_link_txt,false); //(表單名稱,col_name,col_sn,,sort,description,是否縮圖)
    }

    return $menu_sn;
	break;

	case "3"://跑馬燈
	  if($menu_ofsn==0){
      //--------------------   類別 --------------------------------------------------
      if($menu_sn==0){
        //新增
        $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}')";
			}else{
        //編輯
        $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}' where `menu_sn`='{$menu_sn}'";
      }
    }else{
     //-------------------   選單   ---------------------------------------------------
     /***************************** 過瀘資料 *************************/
     $menu_tip=SqlFilter($_POST['menu_tip'],"trim,addslashes,strip_tags");
     $menu_new=intval($_POST['menu_new']);
     $menu_url=SqlFilter($_POST['menu_url'],"trim,addslashes");
     /****************************************************************/
     if($menu_sn==0){
       //新增
       $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`,`menu_tip`,`menu_new`,`menu_url`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}','{$menu_tip}','{$menu_new}','{$menu_url}')";
		 }else{
       //編輯
       $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}', `menu_tip` = '{$menu_tip}', `menu_new` = '{$menu_new}', `menu_url` = '{$menu_url}' where `menu_sn`='{$menu_sn}'";
      }
    }
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    if($menu_sn==0){
  		//取得最後新增資料的流水編號(新增)
  		$menu_sn=$xoopsDB->getInsertId();
  	}
    return $menu_sn;
	break;

	case "4"://下拉選單
	  if($menu_ofsn==0){
      //--------------------   類別 --------------------------------------------------
      if($menu_sn==0){
        //新增
        $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}')";
			}else{
        //編輯
        $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}' where `menu_sn`='{$menu_sn}'";
      }
    }else{
     //-------------------   選單   ---------------------------------------------------
     /***************************** 過瀘資料 *************************/
     $menu_tip=SqlFilter($_POST['menu_tip'],"trim,addslashes,strip_tags");
     $menu_new=intval($_POST['menu_new']);
     $menu_url=SqlFilter($_POST['menu_url'],"trim,addslashes");
     /****************************************************************/
     if($menu_sn==0){
       //新增
       $sql = "insert into ".$xoopsDB->prefix(_DB_MENU_TABLE)."(`menu_type`,`menu_ofsn`,`menu_sort`,`menu_date`,`menu_title`,`menu_enable`,`menu_uid`,`menu_tip`,`menu_new`,`menu_url`) values ('{$menu_type}','{$menu_ofsn}','{$menu_sort}','{$menu_date}','{$menu_title}','{$menu_enable}','{$menu_uid}','{$menu_tip}','{$menu_new}','{$menu_url}')";
		 }else{
       //編輯
       $sql = "update ".$xoopsDB->prefix(_DB_MENU_TABLE)." set  `menu_title` = '{$menu_title}', `menu_enable` = '{$menu_enable}', `menu_sort` = '{$menu_sort}', `menu_uid` = '{$menu_uid}', `menu_date` = '{$menu_date}', `menu_tip` = '{$menu_tip}', `menu_new` = '{$menu_new}', `menu_url` = '{$menu_url}' where `menu_sn`='{$menu_sn}'";
      }
    }
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
    if($menu_sn==0){
  		//取得最後新增資料的流水編號(新增)
  		$menu_sn=$xoopsDB->getInsertId();
  	}
    return $menu_sn;
	break;

	default:
	//$main=op_list($menu_type,$menu_ofsn,$menu_sn);
	break;
  }
}
################################################################################
#   確定層次
#
#
#
#
################################################################################
function chk_level($menu_sn=0,$level=0){

  global $xoopsDB;
  $DBV=get_sn_one("select `menu_ofsn` from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where `menu_sn`={$menu_sn} ");
  if($DBV['menu_ofsn']!=0){
    $level++;
    $level=chk_level($DBV['menu_ofsn'],$level);
  }
  return $level;
 }
###############################################################################
#   類別路徑
#
#
#
#
##############################################################################
function direct_menu($menu_type=0,$menu_sn=0,$level=0,$last_url=0){
  global $xoopsDB,$xoopsModule;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $DBV=get_sn_one("select `menu_ofsn`,`menu_sn`,`menu_title` from ".$xoopsDB->prefix(_DB_MENU_TABLE)." where `menu_sn`='{$menu_sn}' ");
  //$total=$xoopsDB->getRowsNum($result);
  if($level==0 and $last_url==0){
    //第一次且最後一層不須連結
    $direct_menu="
    <li>
      <div class='bc_separator'>
        {$DBV['menu_title']}
      </div>
    </li>";
  }else{
    $direct_menu="
    <li>
      <a href='{$_SERVER['PHP_SELF']}?menu_type={$menu_type}&menu_sn={$menu_sn}'  class='bc_separator'>{$DBV['menu_title']}
      </a>
    </li>
    ";
  }
  if($DBV['menu_ofsn']!=0){
    //不是最上層
    $level++;
    $direct_menu=direct_menu($menu_type,$DBV['menu_ofsn'],$level,$last_url).$direct_menu;
  }
  return $direct_menu;
 }
//新增資料到u_menu中
//op_insert_auto_form($class_type,$class_var_name,$class_url,$class_var,$class_name,$class_sort)
