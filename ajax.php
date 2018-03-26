<?php
include_once "header.php";
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$stop_level=(empty($_REQUEST['stop_level']))?1:intval($_REQUEST['stop_level']);
$csn=(empty($_REQUEST['csn']))?0:intval($_REQUEST['csn']);
$sort=(empty($_REQUEST['sort']))?0:intval($_REQUEST['sort']);
$type=(empty($_REQUEST['type']))?0:intval($_REQUEST['type']);
switch($op){
  //得到分類選單
	case "get_csn_select": 
  $main="<option value='0'></option>".get_group_option(0,$csn,0,$stop_level,0,$type);
	break;
  //得到分類記錄數
	case "csn_count": 
  $main=csn_count($csn,$sort);
	break;
  //預設動作
  default:
  $main="";
  break;
}



echo $main;



function csn_count($csn=0,$sort=0){
	global $xoopsDB ;
	
	$sql = "select `msn`  from `".$xoopsDB->prefix("ugm_page_main")."` where `csn` = '{$csn}'"; 	
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'] , 3, web_error());   
  $total=$xoopsDB->getRowsNum($result); #記錄筆數
  
  if($sort==0){
     ++$total;
     $main="<input type='text' name='sort' size='4' value='{$total}' id='sort' > / {$total}";
  
  } else{
    $main="<input type='text' name='sort' size='4' value='{$sort}' id='sort' >/ {$total}";
  }
	return $main;
}
?>