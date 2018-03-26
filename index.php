<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "ugm_page_index_tpl.html";
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"main":$_REQUEST['op'];
#類別
$csn=(empty($_REQUEST['csn']))?0:intval($_REQUEST['csn']);
#文章
$msn=(empty($_REQUEST['msn']))?0:intval($_REQUEST['msn']);
switch($op){
  case "show_cate_ugm_page":
  	$main.=get_cate_title_show($csn);
  break;

  default:
  	$main=show_one_ugm_page_main($csn,$msn);
  	if($xoopsModuleConfig['show_num']>0){
      //SHOW_相關文章數
  	  $main.=get_cate_title_link($csn,$msn);
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
$xoTheme->addStylesheet(XOOPS_URL . "/modules/ugm_page/css/module_b3.css");
$xoopsTpl->assign( "moduleMenu" , $moduleMenu) ;
$xoopsTpl->assign( "content" , $main) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;//interface_menu.php
$xoopsTpl->assign( "op" , $op) ;
include_once XOOPS_ROOT_PATH . '/footer.php';
/*-----------function區--------------*/
################################################################################
#  show出類別文章
################################################################################
function get_cate_title_show($csn=0){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$xoopsOption;
	if($csn==0)return; //沒有傳類別跳回
  $DBV=get_sn_one("select `title` from ".$xoopsDB->prefix("ugm_page_cate")." where `csn`='{$csn}'");//得到類別標題
  $xoopsOption['xoops_pagetitle']=$DBV['title'];//指定標題(記得global)
  //die($xoopsOption['xoops_pagetitle']);

  $sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where csn='{$csn}' and `enable`='1' order by `date` desc ";
  # ---------- 分頁 -------------------------------------------------
  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];//頁數
  # ------------------------------------------------------------------
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	#$total=$xoopsDB->getRowsNum($result);

  //if ($total<=1)return;//大於2才顯示相關文章

	$main="<table border='0' cellspacing='0' cellpadding='0' class='ugm_tb'>
  <tr><th>"._MD_UGMPAGE_TITLE_LINK."</th><th>"._MD_UGMPAGE_DATE."</th><th>"._MD_UGMPAGE_COUNTER."</th></tr>";

  while($all=$xoopsDB->fetchArray($result)){
  	//以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid
  	foreach($all as $k=>$v){
  		$$k=$v;
  	}
    $main.="
      <tr class='oddalt'>
        <td><a href='index.php?csn={$csn}&msn={$msn}'>{$title}</a></td>
        <td>".date("Y-m-d H:i:00",xoops_getUserTimestamp($date))." </td>
        <td>{$counter}</td>
      </tr>
    ";
  }
  $push=ugm_div("",push_url(""),"shadow");
  $main.=($total>=1)?"<tr><th colspan=3 >{$bar}</th></tr></table>":"<tr class='oddalt'><td colspan=3 >尚無資料</td></tr></table>";
  if($xoopsModuleConfig['link_border']=="corner"){
    $main=$push.ugm_div("",$main,"");
  }elseif($xoopsModuleConfig['link_border']=="shadow"){
    $main=$push.ugm_div("",$main,"shadow");
  }
  return $main;
}



################################################################################
#  相關文章
################################################################################
function get_cate_title_link($csn=0,$msn_chk=0){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig;
	if($csn==0)return; //沒有傳入類別

  $sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where csn='{$csn}' and `enable`='1' and `msn`!='{$msn_chk}' order by `date` ";

  # ---------- 分頁 -------------------------------------------------
  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,$xoopsModuleConfig['show_num'],10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];//筆數
  # ------------------------------------------------------------------
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	//$total=$xoopsDB->getRowsNum($result);
  if($total==0)return;//沒有相關資料
	$main="<table id='form_table' class='table table-bordered table-condensed table-hover'>
  <tr><th>"._MD_UGMPAGE_TITLE_LINK."</th><th>"._MD_UGMPAGE_DATE."</th><th>"._MD_UGMPAGE_COUNTER."</th></tr>";

  while($all=$xoopsDB->fetchArray($result)){
  	//以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid
  	foreach($all as $k=>$v){
  		$$k=$v;
  	}

    $main.="
      <tr class='oddalt'>
        <td><a href='index.php?csn={$csn}&msn={$msn}'>{$title}</a></td>
        <td>".date("Y-m-d H:i:00",xoops_getUserTimestamp($date))." </td>
        <td>{$counter}</td>
      </tr>
    ";
  }
  # ----分頁工具列 --------------
  $main.=($total>$xoopsModuleConfig['show_num'])?"<tr><th colspan=3 >{$bar}</th></tr>":"";

  $main.="</table>";
  if($xoopsModuleConfig['link_border']=="corner"){
    $main=ugm_div("",$main,"");
  }elseif($xoopsModuleConfig['link_border']=="shadow"){
    $main=ugm_div("",$main,"shadow");
  }
  return $main;
}
################################################################################
# 以流水號秀出某筆ugm_page_main資料內容
################################################################################
function show_one_ugm_page_main($csn=0,$msn=0){
	global $xoopsDB,$xoopsModule,$xoopsOption,$xoopsUser,$module_menu,$xoopsModuleConfig,$gperm;

  if($msn!=0){
	  add_counter($msn);
    $sql = "select * 
            from ".$xoopsDB->prefix("ugm_page_main")." 
            where msn='{$msn}' and `enable`='1'";//die($sql);
  }elseif($csn!=0){
    $sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where csn='{$csn}' `enable`='1' order by `date` desc limit 1";
  }else{
    $sql = "select * from ".$xoopsDB->prefix("ugm_page_main")." where `enable`='1' order by `date` desc limit 1";
  }

	$main_menu="";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	//$row=$xoopsDB->fetchRow($result);
	$total=$xoopsDB->getRowsNum($result);
	$all=$xoopsDB->fetchArray($result);

  if($total==0){
    $main=$module_menu;
  }else{
  	//以下會產生這些變數： $msn , $csn , $title , $content , $start_time , $end_time , $enable , $counter , $top , $date , $uid ,$editor
  	foreach($all as $k=>$v){
  		$$k=$v;
  	}

    #檢查是否有編輯文章的權限

    $edit_perm = edit_perm($msn);
    //-------------------
  	if($gperm['ugm_page_post'][4] or $edit_perm){

      if($gperm['ugm_page_post'][2] or $edit_perm){
        $edit=" | <a href='post.php?op=ugm_page_main_UForm&msn={$msn}'> "._MD_UGMPAGE_SMNAME5."</a>";
      }else{
        $edit="";
      }

      $insert=$gperm['ugm_page_post'][1]?" | <a href='post.php?op=ugm_page_main_IForm'>"._BP_ADD."</a> ":"";
      $post=$gperm['ugm_page_post'][4]?" | <a href='post.php?op=ugm_page_main_IForm'>"._BP_ADD."</a> ":"";
      $disp_admin="
        <div class='ugm_page_admin'>
          "._MD_UGMPAGE_COUNTER._BP_FOR."{$counter}{$post}{$insert}{$edit}
        </div>
      ";
    }


    $main_menu=$module_menu;
  	//---------------------
  	//$enable=($enable==1)?"<img src='images/on.png' />":"<img src='images/off.png' />";//啟用
    $date=date("Y-m-d H:i:00",xoops_getUserTimestamp($date));
    $user_name=XoopsUser::getUnameFromId($uid,0);// 取得該使用者的帳號

    if($xoopsModuleConfig['use_social_tools'] or $xoopsModuleConfig['display_date']){
      # 推文工具
      $push=push_url($xoopsModuleConfig['use_social_tools']);
      $display_date=($xoopsModuleConfig['display_date'])?"
          <div class='col-sm-4'>"._MD_UGMPAGE_DATE._BP_FOR."<strong>{$date}</strong></div>":"";
      $display_tools="
        <div class='row'>
          {$display_date}
          <div class='col-sm-6'>$push</div>
          <div class='col-sm-2'>"._MD_UGMPAGE_COUNTER._BP_FOR."{$counter}</div>
        </div>";
    }

  	$data="
     	<div>
        <h1>{$title}</h1>
      	{$display_tools}
      	<div style='border:1px solid #efefef; background-color: #fefefe; padding: 30px; margin:10px auto; line-height: 2em; font-weight: normal; '>
      	  {$content}
      	</div>
    	  {$disp_admin}
      </div>";
    //$facebook_comments=facebook_comments();
	  //$push=ugm_div("",push_url(""),"shadow");
    $main=$data;
  	//$main=$main_menu.ugm_div("",$data,"");
  	$xoopsOption['xoops_pagetitle']=$title;//指定標題
	}
	return $main;
}
################################################################################
# 計數器
################################################################################
function add_counter($msn){
	global $xoopsDB;
	$sql = "update ".$xoopsDB->prefix("ugm_page_main")." set  counter = counter + 1 where msn='$msn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	return $msn;
}