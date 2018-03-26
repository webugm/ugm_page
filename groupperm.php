<?php
defined('XOOPS_ROOT_PATH') || die("XOOPS root path not defined");
//---- 權限設定 ---- */
$module_id = $xoopsModule->getVar('mid');
$isAdmin=false;
if ($xoopsUser){
  //判斷是否對該模組有管理權限
  $isAdmin=$xoopsUser->isAdmin($module_id);
}

//權限項目陣列(x軸)（編號超級重要！設定後，以後切勿隨便亂改。）
# title 只有在後台設定時用到
$gperm_itemid_arr = array(
  '1' => array("title"=>_ADD,"anonymous"=>false),
  '2' => array("title"=>_EDIT,"anonymous"=>false),
  '3' => array("title"=>_DELETE,"anonymous"=>false),
  '4' => array("title"=>_MA_GROUPPERM_VIEW,"anonymous"=>false)
  //'5' => array("title"=>_MA_GROUPPERM_PRINT,"anonymous"=>false)
);
//權限名稱陣列(y軸)
$gperm_name_arr = array(
  "ugm_page_post" => _MD_UGMPAGE_SMNAME3,
  "ugm_page_cate" => _MD_UGMPAGE_SMNAME2,
  "ugm_page_menu" => _MD_UGMPAGE_SMNAME6
);


function get_gperm($module_id,$isAdmin,$gperm_itemid_arr,$gperm_name_arr){
	global $xoopsUser,$xoopsDB;
	$gperm = array();
	//權限設定($gperm_name_arr權限名稱、$gperm_itemid_arr權限項目 )
	if($gperm_name_arr and $gperm_itemid_arr){
	  $gperm_handler =& xoops_gethandler('groupperm');
	  #取得群組
	  $groups =($xoopsUser)? $xoopsUser->getGroups():XOOPS_GROUP_ANONYMOUS;
	  //權限設定
	  foreach($gperm_name_arr as $gperm_name =>$gperm_name_title){
	    foreach($gperm_itemid_arr as $gperm_itemid =>$gperm_itemid_v){
	      if($isAdmin){
	        #管理員
	        $gperm[$gperm_name][$gperm_itemid]=true;
	      }else{
	        #非管理員
	        if($gperm_itemid_v['anonymous']){
	          #訪客有權限
	          #檢查是是否開放全部
	          $sql = "select gperm_id
	                  from `".$xoopsDB->prefix('group_permission')."`
	                  where `gperm_groupid` = '0'  and `gperm_modid`='{$module_id}' and `gperm_itemid`='{$gperm_itemid}' and `gperm_name`='{$gperm_name}'";
	          
						$result = $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, web_error());
						$row = $xoopsDB->fetchArray($result);
	          if($row['gperm_id']){
	            $gperm[$gperm_name][$gperm_itemid]=true;
	            continue;
	          }
	        }
	        if($gperm_handler->checkRight($gperm_name, $gperm_itemid, $groups, $module_id)){
	          //若有權限要做的動作
	          $gperm[$gperm_name][$gperm_itemid]=true;
	        }else{
	          //若沒有權限要做的動作
	          $gperm[$gperm_name][$gperm_itemid]=false;
	        }
	      }

	    }
	  }
	}
	return $gperm;
}

