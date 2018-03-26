<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2010-04-10
// $Id: block_function.php,
// ------------------------------------------------------------------------- //
###############################################################################
#  ugm_div($data,$corners)
#  圓角
#
#
###############################################################################
if(!function_exists("block_ugm_div")){
function block_ugm_div($title="",$data="",$corners="",$width=""){
  $main="<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ugm_page/module.css' /> ";
  $width=empty($width)? "":" style='width:{$width}px;'" ;
  if($corners=="shadow"){
    $title=empty($title)?"":"<div class='Block1Header'><h1>{$title}</h1></div>";
    $main.="
      <div class='Block1Border' {$width}><div class='Block1BL'><div></div></div><div class='Block1BR'><div></div></div><div class='Block1TL'></div><div class='Block1TR'><div></div></div><div class='Block1T'></div><div class='Block1R'><div></div></div><div class='Block1B'><div></div></div><div class='Block1L'></div><div class='Block1C'></div><div class='Block1'>{$title}
            <div class='Block1ContentBorder' >{$data}
            </div>
        </div></div>
    ";
  }else{
    $main.="<div class='BlockBorder'  {$width}><div class='BlockBL'><div></div></div><div class='BlockBR'><div></div></div><div class='BlockTL'></div><div class='BlockTR'><div></div></div><div class='BlockT'></div><div class='BlockR'><div></div></div><div class='BlockB'><div></div></div><div class='BlockL'></div><div class='BlockC'></div><div class='Block'>\n
    {$data}\n
    </div></div>\n";
   $main=empty($title)? $main:"<span class='title'>{$title}</span>".$main; 
    
  }
  return $main;
}
}

###############################################################################
#  單選回復原始資料函數
#  chk($DBV="",$NEED_V="",$defaul="",$return="checked='checked'");
#      資料庫,表單的值,預設,回傳種類
#
###############################################################################

//單選回復原始資料函數
if(!function_exists('chk')){
  function chk($DBV="",$NEED_V="",$defaul="",$return="checked='checked'"){
  	if($DBV==$NEED_V){
  		return $return;
  	}elseif(empty($DBV) && $defaul=='1'){
  		return $return;
  	}
  	return "";
  }
}

###############################################################################
#  單選回復原始資料函數
#  chk($DBV="",$NEED_V="",$defaul="",$return="checked='checked'");
#      資料庫,表單的值,預設,回傳種類
#
###############################################################################

//單選回復原始資料函數
if(!function_exists('chk3')){
  function chk3($DBV="",$NEED_V="",$defaul="",$return="checked='checked'"){
  	if($DBV==$NEED_V){
  		return $return;
  	}elseif(empty($DBV) && $defaul=='1'){
  		return $return;
  	}
  	return "";
  }
}
###############################################################################
#  複選回復原始資料函數
#  chk2($defaul_array="",$NEED_V="",$defaul=1);
#
#
###############################################################################
//複選回復原始資料函數
if(!function_exists('chk2')){
  function chk2($default_array="",$NEED_V="",$default=0){
  	if(in_array($NEED_V,$default_array)){
  		return "checked";
  	}elseif(empty($default_array) && $default=='1'){
  		return "checked";
  	}

  	return "";
  }
}



?>
