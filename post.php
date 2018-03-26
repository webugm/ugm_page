<?php
include_once "header.php";
include_once(XOOPS_ROOT_PATH."/modules/ugm_page/up_file.php");
$xoopsOption['template_main'] = "ugm_page_post_tpl.html";
include_once XOOPS_ROOT_PATH . "/header.php";
$xoopsOption['xoops_pagetitle']=_MD_UGMPAGE_SMNAME3;//文章管理
/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$csn=(empty($_REQUEST['csn']))?0:intval($_REQUEST['csn']);
$msn=(empty($_REQUEST['msn']))?0:intval($_REQUEST['msn']);

#檢查是否有編輯文章的權限
$edit_perm=edit_perm($msn);


switch($op){
  //更新啟用
	case "op_update_enable":
    #權限檢查
    if(!$gperm['ugm_page_post'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    update_ugm_page_main($msn);
    op_update_enable($msn);
  	redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;

	case "op_update_sort_list": //更新排序 (拉動排序)
    #權限檢查
    if(!$gperm['ugm_page_post'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    update_ugm_page_main($msn);
    $main=op_update_sort_list($csn);
	break;
	case "op_update_sort": //更新排序 (寫人資料表)
    #權限檢查
    if(!$gperm['ugm_page_post'][2])redirect_header(XOOPS_URL,3,_NOPERM);
    update_ugm_page_main($msn);
    op_update_sort();
	  redirect_header($_SERVER['PHP_SELF'],3, _BP_INSERT_SUCCESS);
	break;

  //新增資料
  case "insert_ugm_page_main":
    #權限檢查
    if(!$gperm['ugm_page_post'][1])redirect_header(XOOPS_URL,3,_NOPERM);
    $msn=insert_ugm_page_main();
    redirect_header("index.php?csn={$csn}&msn={$msn}",3, _BP_INSERT_SUCCESS);
  break;

  //更新資料
  case "update_ugm_page_main":
    #權限檢查
    if(!$gperm['ugm_page_post'][2] and !$edit_perm)redirect_header(XOOPS_URL,3,_NOPERM);
    update_ugm_page_main($msn);
    redirect_header("index.php?csn={$csn}&msn={$msn}",3, _BP_INSERT_SUCCESS);
  break;

  //新增表單
  case "ugm_page_main_IForm":
    #權限檢查
    if(!$gperm['ugm_page_post'][1])redirect_header(XOOPS_URL,3,_NOPERM);
    $main=ugm_page_main_form();
  break;

  //編輯表單
  case "ugm_page_main_UForm":
    #權限檢查
    if(!$gperm['ugm_page_post'][2]  and !$edit_perm)redirect_header(XOOPS_URL,3,_NOPERM);
    $main=ugm_page_main_form($msn);
  break;

  //刪除資料
  case "delete_ugm_page_main":
    #權限檢查
    if(!$gperm['ugm_page_post'][3])redirect_header(XOOPS_URL,3,_NOPERM);
    delete_ugm_page_main($msn);
    redirect_header($_SERVER['PHP_SELF'],3, _BP_DEL_SUCCESS);
  break;

  //預設動作
  default:
    #權限檢查
    if(!$gperm['ugm_page_post'][4])redirect_header(XOOPS_URL,3,_NOPERM);
    if(empty($msn)){
    	$main=list_ugm_page_main();
    	//$main.=ugm_page_main_form($msn);
    }else{
    	$main=show_one_ugm_page_main($msn);
    }
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
$xoopsTpl->assign( "content" , $main) ;
$xoTheme->addStylesheet(XOOPS_URL . "/modules/ugm_page/css/module_b3.css");
$xoopsTpl->assign( "moduleMenu" , $moduleMenu) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;//interface_menu.php
$xoopsTpl->assign( "op" , $op) ;
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

###############################################################################
#  更新啟用
#
#
#
###############################################################################
function op_update_enable($msn=0){
  global $xoopsDB,$xoopsUser;
  if(!$xoopsUser or $msn==0)return;
  $uid=$xoopsUser->getVar('uid');
  /***************************** 過瀘資料 *************************/
  $enable=intval($_GET['enable']);
  /****************************************************************/
  //更新
  $sql = "update ".$xoopsDB->prefix("ugm_page_main")." set  `enable` = '{$enable}' where `msn`='{$msn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return ;
}

###############################################################################
#  更新排序
#
#
###############################################################################
function op_update_sort(){
  global $xoopsDB;
  $sort=$_POST['total'];
  foreach($_POST['sort'] as $msn => $v){
    $sql = "update ".$xoopsDB->prefix("ugm_page_main")." set  `sort` = '{$sort}' where `msn`='{$msn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $sort--;
  }
  return;
}

################################################################################
# 列出所有ugm_page_main資料
#
#
#
################################################################################
function op_update_sort_list($csn=0){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsOption;
	$DIRNAME=$xoopsModule->getVar('dirname');
  if($csn==0)redirect_header($_SERVER['PHP_SELF'],1,"");
	//------------------------------------------------------
	$sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where `csn`='{$csn}' order by sort desc";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$total=$xoopsDB->getRowsNum($result); #記錄筆數
	if($total==0){
	  $main="此類別尚無資料";


  }else{
  	//--------------------- 引入jquery -------------------------------------
  	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
      redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
    $jquery_path = get_jquery(); //一般只要此行即可
    $tablednd="
      <script type='text/javascript' src='class/tablednd/js/jquery.tablednd.0.7.min.js'></script>
      <script type='text/javascript'>
      $(document).ready(function(){
          $('.ugm_tb').tableDnD({
            onDragClass:'myDragClass',
            onDragStart: function(table) {
              $('#saveArea').html(\"<input type='hidden' name='total' value='{$total}'><input type='hidden' name='op' value='op_update_sort'><input type='submit' value='"._SUBMIT."'>\");
            }
          });

      });
      </script>
      <style>
    .myDragClass {  color: yellow;  background-color: black;}
  </style>
    ";

  	$main=$jquery_path.ugm_javascript(1).$tablednd."
      <form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form'>
    	<table border='0' cellspacing='0' cellpadding='0'  class='ugm_tb'>
      	<tr>
        	<th style='width:160px;'>"._MD_UGMPAGE_DATE."</th>
        	<th style='width:40px;'>"._MD_UGMPAGE_CATE_SORT."</th>
        	<th>"._MD_UGMPAGE_TITLE."</th>
        	<th style='width:45px;'>"._MD_UGMPAGE_ENABLE."</th>
        	<th style='width:50px;'>"._MD_UGMPAGE_COUNTER."</th>
        	<th style='width:80px;'>"._MD_UGMPAGE_UID."</th>
        </tr>";
    while($all=$xoopsDB->fetchArray($result)){
  	  //以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid
      foreach($all as $k=>$v){
        $$k=$v;
      }
  		$date=date("Y-m-d H:i:00",xoops_getUserTimestamp($date));
  		$DBV=get_sn_one("select `title` from ".$xoopsDB->prefix("ugm_page_cate")." where `csn`='{$csn}'");
  		$user_name=XoopsUser::getUnameFromId($uid,0);;// 取得該使用者的帳號
  		$enable=($enable==1)?"<img src='images/on.png' />":"<img src='images/off.png' />";//啟用
  		$main.="<tr>
    		<td>{$date}</td>
    		<td class='c'>{$sort}<input type='hidden' name='sort[$msn]'></td>
    		<td>{$title}</td>
    		<td class='c'>{$enable}</td>
    		<td class='c'>{$counter}</td>
    		<td class='c'>{$user_name}</td>
  		</tr>";
  	}
    $main.="
    	</table><div id='saveArea' style='text-align:center;'></div></form>
    ";
  }
	//raised,corners,inset
	$main=ugm_div("",$main,"");
	$xoopsOption['xoops_pagetitle']=sprintf(_MD_UGMPAGE_UPDATE_SORT_LIST,$DBV['title']);;//指定標題
	return $main;
}

//取得ugm_apply_commodity_kind分類選單的選項（單層選單）
function get_ugm_apply_commodity_kind_menu_options($default_sn="0"){
	global $xoopsDB,$xoopsModule;
	$sql = "select sn,title from ".$xoopsDB->prefix("ugm_apply_commodity_kind")." ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$option="";
	while(list($sn,$title)=$xoopsDB->fetchRow($result)){
 		$selected=($sn==$default_sn)?"selected=selected":"";
		$option.="<option value=$sn $selected>{$title}</option>";

	}
	return $option;
}



###############################################################################
#  ugm_page_main_form  編輯表單
###############################################################################
function ugm_page_main_form($msn=0){
	global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$gperm;
  $DIRNAME=$xoopsModule->getVar('dirname');
  $stop_level=$xoopsModuleConfig['cate_level'];
  //$csn = (!isset($_GET['csn']))? 0:intval($_GET['csn']);
	//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	$slider_pic="";
	$slider_txt="";
	if(!empty($msn)){
		$DBV=get_ugm_page_main($msn);
		# 處理滑動圖片
		$slider_pic=get_one_pic("slider_pic",$msn,"small");
		$slider_pic=(empty($slider_pic))?"":"<tr><td colspan=2>{$slider_pic}</td></tr>";
		$slider_txt=get_one_description("slider_pic",$msn,"small");
	}else{
		$DBV=array();
	}
  //預設值設定

	//設定「msn」欄位預設值
	$msn=(!isset($DBV['msn']))?"":$DBV['msn'];

	//設定「csn」欄位預設值
	$csn=(!isset($DBV['csn']))?0:$DBV['csn'];

	//設定「title」欄位預設值
	$title=(!isset($DBV['title']))?"":$DBV['title'];

	//設定「content」欄位預設值
	$content=(!isset($DBV['content']))?"":$DBV['content'];

	//設定「start_time」欄位預設值
	$start_time=(!isset($DBV['start_time']))?"":$DBV['start_time'];

	//設定「end_time」欄位預設值
	$end_time=(!isset($DBV['end_time']))?"":$DBV['end_time'];

	//設定「enable」欄位預設值
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];

	//設定「counter」欄位預設值
	$counter=(!isset($DBV['counter']))?"":$DBV['counter'];

	//設定「top」欄位預設值
	$top=(!isset($DBV['top']))?"":$DBV['top'];

	//設定「date」欄位預設值
	$date=(!isset($DBV['date']))?"":$DBV['date'];

	//設定「uid」欄位預設值
	$uid=(!isset($DBV['uid']))?"":$DBV['uid'];

	//設定「sort」欄位預設值
	$sort=(!isset($DBV['sort']))?0:$DBV['sort'];  //排序

  //設定「editor」欄位預設值
  $editor=(!isset($DBV['editor']))?null:$DBV['editor'];
  $editor=$editor?json_decode($editor):null;

	$op=(empty($msn))?"insert_ugm_page_main":"update_ugm_page_main";

	//--------------------- 引入jquery -------------------------------------
  	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
      redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
    }
    $jquery_path = get_jquery(); //一般只要此行即可
  //---------------------  驗証    ------------------------------------------------
	if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#{$DIRNAME}_Form",true);
  $formValidator_code=$formValidator->render();
  //---------------------  多檔上傳 ----------------------------------------------
	$multiple_file_upload_code="<script src='".XOOPS_URL."/modules/tadtools/multiple-file-upload/jquery.MultiFile.js'></script>";
	//--------------------------------------------------------------------------------
	$adv_setup="
  <script type=\"text/javascript\">
	  $(document).ready(function() {
      //隱藏「進階設定」
		  $('#input_form').hide();
		  // 「進階設定」的開關
		  $('#show_input_form').click(function() {
			if ($('#input_form').is(':visible')) {
	       $('#input_form').slideUp();
			} else{
	       $('#input_form').slideDown();
			}
	  });
			//由分類決定排序
			$.post('ajax.php' , {op: 'get_csn_select',stop_level:'{$stop_level}',csn:'{$csn}'},
        function(data){
          $('#csn').html(data); //分類
          $.post('ajax.php' , {op: 'csn_count',csn:$('#csn').val(),sort:{$sort}},
            function(data){
              $('#sort_form').html(data); //排序
          });
      });
      //分類改變，排序改變
      $('#csn').change(function(){
        $.post('ajax.php' , {op: 'csn_count',csn:$('#csn').val(),sort:{$sort}},
            function(data){
              $('#sort_form').html(data); //排序
         });
      });
	  });
	</script>";
	$main="
	$jquery_path
	$formValidator_code
	$multiple_file_upload_code
	$adv_setup
	<form action='{$_SERVER['PHP_SELF']}' method='post' id='{$DIRNAME}_Form' enctype='multipart/form-data' class='form-inline'>
	<table id='{$DIRNAME}_table' border='0' cellspacing='3' cellpadding='3' class='ugm_tb'>
	<!--msn-->
	<input type='hidden' name='msn' value='{$msn}'>

	<!--分類-->
	<tr class='oddalt'>
    <td>"._MD_UGMPAGE_CSN._BP_FOR."
      <select name='csn' size=1 id='csn'>

      </select>

      &nbsp;&nbsp;&nbsp;&nbsp;"._MD_UGMPAGE_ENABLE._BP_FOR."<label for='enable_1'><input type='radio' name='enable' id='enable_1' value='1'  ".chk($enable,'1','1').">"._YES."</label>&nbsp;<label for='enable_0'><input type='radio' name='enable' id='enable_0' value='0'  ".chk($enable,'0')."><span>"._NO."</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
      "._MD_UGMPAGE_CATE_SORT._BP_FOR."<span id='sort_form'> </span>
    </td>
  </tr>
	<!--標題-->
	<tr class='oddalt'><td>"._MD_UGMPAGE_TITLE._BP_FOR."<input type='text' name='title' size='40' value='{$title}' id='title' class='validate[required]'></tr>";

 //  //----------------------- fck -------------------------------------------
	// if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/fck.php")){
	// 	redirect_header("index.php",3, _MD_NEED_TADTOOLS);
	// }
	// include_once XOOPS_ROOT_PATH."/modules/tadtools/fck.php";
	// $fck=new FCKEditor264("ugm_page","content",$content);
	// //$fck->setWidth($xoopsModuleConfig['fck_width']);
	// //$fck->setHeight($xoopsModuleConfig['fck_height']);
	// $content_editor=$fck->render();
 //  //------------------------------------------------------------------------


  //內容#資料放「content」
  # ======= ckedit====
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/ck.php")){
    redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50" , 3 , _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/ck.php";
  $ck=new CKEditor("ugm_page","content",$content);
  $ck->setHeight(300);
  $content_editor=$ck->render();
  #-------------------------------------


  if($gperm['ugm_page_post'][1] or $gperm['ugm_page_post'][2]){

    $sql    = "select uid,uname,name from " . $xoopsDB->prefix("users") . " order by uname";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $editor_form = "<select name='editor[]' multiple size=5>";
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name     = empty($name) ? "" : "（{$name}）";
        $selected = (in_array($uid,$editor)) ? "selected" : "";
        $editor_form .= "<option value='$uid' $selected>{$uname} {$name}</option>";
    }
    $editor_form .= "</select>";

    $editor_form="<tr><td>"._MD_UGMPAGE_EDITOR._BP_FOR."</td><td>{$editor_form}</td></tr>";

  }else{
    $editor_form = "";
  }

	$main.="
	<!--內容-->

	<tr class='oddalt'>
      <td>{$content_editor}</td>
  </tr>
  <tr class='oddalt'>
	  <td>
      <div id='input_form'>
         <table>
           <tr><td>"._MD_UGMPAGE_SLIDER._BP_FOR."</td><td><input type='file' name='slider[]' maxlength='1' accept='gif|jpg|png'></td></tr>
           {$slider_pic}
           <tr><td>"._MD_UGMPAGE_SLIDER_TXT._BP_FOR."</td><td><textarea name='slider_txt' style='width:550px;height:80px;font-size:12px;'>$slider_txt</textarea></td></tr>
           {$editor_form}
         </table>
      </div>
    </td>
	</tr>

	<tr class='oddalt'><td >
	<input type='hidden' name='op' value='{$op}'>
	<input type='button' value='"._MD_UGMPAGE_SHOW_INPUT_FORM."' id='show_input_form'>
	<input type='submit' value='"._MD_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=ugm_div(_MD_UGM_PAGE_MAIN_FORM,$main,"");

	return $main;
}
###############################################################################
#  新增資料到ugm_page_main中
###############################################################################
function insert_ugm_page_main(){
	global $xoopsDB,$xoopsUser;

	$slider_txt=SqlFilter($_POST['slider_txt'],"trim,addslashes");
  $title=SqlFilter($_POST['title'],"trim,addslashes,strip_tags");
  $content=SqlFilter($_POST['content'],"addslashes");
  $enable=intval($_POST['enable']);
  $date=strtotime("now");//現在時間
  $csn=intval($_POST['csn']);
  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):-1;
  $sort=intval($_POST['sort']) ;//排序
  $editor=$_POST['editor']?json_encode($_POST['editor']):"";
  //$editor=$_POST['editor']?implode(",",$_POST['editor']):"";
	$sql = "insert into ".$xoopsDB->prefix("ugm_page_main")."
	(`csn` , `title` , `content` , `enable` , `date` , `uid` , `sort`,`editor`)
	values('{$csn}' , '{$title}' , '{$content}' ,  '{$enable}' , '{$date}' , '{$uid}', '{$sort}','{$editor}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$msn=$xoopsDB->getInsertId();


	//處理上傳的檔案
	upload_file('slider',"slider_pic",$msn,null,1,$slider_txt,false); //(表單名稱,col_name,col_sn,,sort,description,是否縮圖)
	return $msn;
}
###############################################################################
#  更新ugm_page_main某一筆資料
###############################################################################
function update_ugm_page_main($msn=""){
	global $xoopsDB,$xoopsUser,$gperm;
	$slider_txt=SqlFilter($_POST['slider_txt'],"trim,addslashes");
  $title=SqlFilter($_POST['title'],"trim,addslashes,strip_tags");
  $content=SqlFilter($_POST['content'],"addslashes");
  $enable=intval($_POST['enable']);
  $date=strtotime("now");//現在時間
  $csn=intval($_POST['csn']);
  $sort=intval($_POST['sort']) ;//排序
  $uid=($xoopsUser)?$xoopsUser->getVar('uid'):-1;
  $editor="";
  if($gperm['ugm_page_post'][2]){
    $editor=$_POST['editor']?json_encode($_POST['editor']):"";
    $editor=", `editor` = '{$editor}'";
  }

	$sql = "update ".$xoopsDB->prefix("ugm_page_main")." set
	 `csn` = '{$csn}' ,
	 `title` = '{$title}' ,
	 `content` = '{$content}' ,
	 `enable` = '{$enable}' ,
	 `date` = '{$date}' ,
	 `sort` = '{$sort}' ,
	 `uid` = '{$uid}'
   {$editor}
	where msn='$msn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	//處理上傳的檔案
	upload_file('slider',"slider_pic",$msn,null,1,$slider_txt,false); //(表單名稱,col_name,col_sn,,sort,description,是否縮圖)
	return $msn;
}
################################################################################
# 列出所有ugm_page_main資料
################################################################################
function list_ugm_page_main($show_function=1){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$gperm;
	//-----------------------下拉選單------------------------
	$csn = (!isset($_GET['csn']))? 0:intval($_GET['csn']);
	$stop_level=$xoopsModuleConfig['cate_level'];
	$selected=($csn==0)?" selected=selected":"";
  $groud_option="<select name='csn' size=1 onChange='location.href=\"{$_SERVER['PHP_SELF']}?csn=\"+this.value' class='form-control' >
       <option value='0'{$selected}></option>".get_group_option(0,$csn,0,$stop_level)."
    </select>";
  $and_key=($csn==0)?"":" where `csn`='{$csn}' ";
	//------------------------------------------------------
	$sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." {$and_key} order by date desc";
	//getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//--------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
 # --------------------------------------------------------------------
  $reset_sort=($csn!=0 and $gperm['ugm_page_post'][2])?"
        <a href='{$_SERVER['PHP_SELF']}?op=op_update_sort_list&csn={$csn}' class='bc_separator' ><img src='images/i_sort14.png' /> "._BP_RESET_SORT."</a>":"";
  $csn=($csn!=0)?"&csn={$csn}":"";
  //-------------------------------------------------------------------
  //<a href='{$_SERVER['PHP_SELF']}?op=op_update_sort' class='Button' ><span><img src='images/i_sort14.png' /> "._BP_RESET_SORT."</span></a>
  $ugm_direct="
    <h1>"._MD_UGMPAGE_SMNAME3."</h1>
    <hr>
    <div class='row' style='margin-bottom: 10px;'>
      <div class='col-sm-3'>
        {$groud_option}
      </div>";
  if($gperm['ugm_page_post'][1]){
    $ugm_direct.="
      <div class='col-sm-9'>
        <a href='{$_SERVER['PHP_SELF']}?op=ugm_page_main_IForm{$csn}' class='bc_separator' ><img src='images/i_new_cate.gif' /> "._BP_ADD."</a>
        {$reset_sort}
      </div>  
    </div>";
  }
  //------------------------------------------------------------------
  $delete_code=$gperm['ugm_page_post'][3]?"
    <script>
     //刪除確認的JS
      function delete_ugm_page_main_func(msn){
        var sure = window.confirm('"._BP_DEL_CHK."');
        if (!sure)  return;
        location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ugm_page_main&msn=\" + msn;
      }
    </script>
  ":"";

	$main="
  	{$delete_code}
    {$ugm_direct}
  	<table id='form_table' class='table table-bordered table-condensed table-hover'>
    	<tr>
      	<th style='width:160px;'>"._MD_UGMPAGE_DATE."</th>
      	<th style='width:40px;'>"._MD_UGMPAGE_CATE_SORT."</th>
      	<th style='width:80px;'>"._MD_UGMPAGE_CSN."</th>
      	<th>"._MD_UGMPAGE_TITLE."</th>
      	<th style='width:45px;'>"._MD_UGMPAGE_ENABLE."</th>
      	<th style='width:50px;'>"._MD_UGMPAGE_COUNTER."</th>
      	<th style='width:80px;'>"._MD_UGMPAGE_UID."</th>
      	<th style='width:80px;'>"._BP_FUNCTION."</th>
      </tr>";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $edit_button=$del_button="";
    if($gperm['ugm_page_post'][2]){
      $edit_button="<a href='{$_SERVER['PHP_SELF']}?op=ugm_page_main_UForm&msn=$msn'><img src='images/i_edit.gif' /></a>";
    }
    if($gperm['ugm_page_post'][3]){
      $del_button="<a href=\"javascript:delete_ugm_page_main_func($msn);\" ><img src='images/i_del.gif' /></a>";
    }
		$fun="
		<td class='align_r'>
      {$edit_button}
      {$del_button}
		</td>";
		$date=date("Y-m-d H:i:00",xoops_getUserTimestamp($date));
		$DBV=get_sn_one("select `title` from ".$xoopsDB->prefix("ugm_page_cate")." where `csn`='{$csn}'");
		$user_name=XoopsUser::getUnameFromId($uid,0);;// 取得該使用者的帳號
		$enable=($enable==1)?"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&msn={$msn}&enable=0'><img src='images/on.png' /></a>":"<a href='{$_SERVER['PHP_SELF']}?op=op_update_enable&msn={$msn}&enable=1'><img src='images/off.png' /></a>";//啟用
		$main.="<tr>
  		<td>{$date}</td>
  		<td class='c'>{$sort}</td>
  		<td class='c'>{$DBV['title']}</td>
  		<td><a href='index.php?msn={$msn}'>{$title}</a></td>
  		<td class='c'>{$enable}</td>
  		<td class='c'>{$counter}</td>
  		<td class='c'>{$user_name}</td>
  		$fun
		</tr>";
	}
  $main.="
   	<tr>
    	<th colspan=8 >
    	  {$bar}
      </th>
    </tr>
  	</table>
  ";

	return $main;
}
################################################################################
# 以流水號秀出某筆ugm_page_main資料內容
#
#
#
################################################################################

function show_one_ugm_page_main($msn=""){
	global $xoopsDB,$xoopsModule,$xoopsOption;
	if(empty($msn)){
		return;
	}else{
		$msn=intval($msn);
	}
	$sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where msn='{$msn}'";//die($sql);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);

	//以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid
	foreach($all as $k=>$v){
		$$k=$v;
	}
	$enable=($enable==1)?"<img src='images/on.png' />":"<img src='images/off.png' />";//啟用
  $date=date("Y-m-d H:i:00",xoops_getUserTimestamp($date));
  $user_name=XoopsUser::getUnameFromId($uid,0);;// 取得該使用者的帳號
	$data="
	<table id='form_table' class='table table-bordered table-condensed table-hover'>
	<tr class='alt'>
  <td>"._MD_UGMPAGE_DATE._BP_FOR."<span>{$date}</span>&nbsp;&nbsp;
      "._MD_UGMPAGE_ENABLE._BP_FOR."{$enable}&nbsp;&nbsp;
      "._MD_UGMPAGE_UID._BP_FOR."{$user_name}&nbsp;&nbsp;
      "._MD_UGMPAGE_COUNTER._BP_FOR."{$counter}</td>
  </tr>
	<tr><td>{$content}</td></tr>
	</table>";
	//$main=ugm_div($title,$data,"");
	return $main;
}

//以流水號取得某筆ugm_page_main資料
function get_ugm_page_main($msn=""){
	global $xoopsDB;
	if(empty($msn))return;
	$sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where msn='$msn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//刪除ugm_page_main某筆資料資料
function delete_ugm_page_main($msn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ugm_page_main")." where msn='$msn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

################################################################################
//自動取得sort的最新排序
function get_max_sort($csn=0){
	global $xoopsDB;
	$sql = "select max(`sort`) from ".$xoopsDB->prefix("ugm_page_main")." where `csn`='{$csn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}

?>
