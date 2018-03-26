<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-15
// $Id:$
// ------------------------------------------------------------------------- //

/*
需有 class/multiple-file-upload、upload、lytebox

include_once("up_file.php");

#上傳檔案必須引入
include_once(XOOPS_ROOT_PATH."/modules/ugm_page/up_file.php");

表單：
 enctype='multipart/form-data'
 
<script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/jquery/jquery.js'></script>
<script src='".XOOPS_URL."/modules/tadtools/multiple-file-upload/jquery.MultiFile.js'></script>
<input type='file' name='upfile[]' class='multi'>".list_del_file("news_sn",$news_sn)."

儲存：upload_file($col_name,$col_sn);

顯示：show_files($col_name,$col_sn,true,false);  //是否縮圖,顯示模式

刪除：del_files($files_sn,$col_name,$col_sn);

種類：img,file
資料表：
CREATE TABLE `files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '檔案流水號',
  `col_name` varchar(255) NOT NULL COMMENT '欄位名稱',
  `col_sn` smallint(5) unsigned NOT NULL COMMENT '欄位編號',
  `sort` smallint(5) unsigned NOT NULL COMMENT '排序',
  `kind` enum('img','file') NOT NULL COMMENT '檔案種類',
  `file_name` varchar(255) NOT NULL COMMENT '檔案名稱',
  `file_type` varchar(255) NOT NULL COMMENT '檔案類型',
  `file_size` int(10) unsigned NOT NULL COMMENT '檔案大小',
  `description` text NOT NULL COMMENT '檔案說明',
  `counter` mediumint(8) unsigned NOT NULL COMMENT '下載人次',
  PRIMARY KEY (`files_sn`)
) TYPE=MyISAM COMMENT='檔案資料表';
*/

//取得模組目錄名稱
$MDIR="ugm_page";


if(!empty($xoopsModuleConfig['pic_width'])){
	define("_FC_THUMB_WIDTH",$xoopsModuleConfig['pic_width']);
	define("_FC_THUMB_SMALL_WIDTH",$xoopsModuleConfig['thumb_width']);
}else{
	define("_FC_THUMB_WIDTH",560);
	define("_FC_THUMB_SMALL_WIDTH",100);
}

//檔案中心實體位置
define("_FILES_CENTER_DIR",XOOPS_ROOT_PATH."/uploads/{$MDIR}/file");
define("_FILES_CENTER_URL",XOOPS_URL."/uploads/{$MDIR}/file");
//檔案中心圖片實體位置
define("_FILES_CENTER_IMAGE_DIR",XOOPS_ROOT_PATH."/uploads/{$MDIR}/image");
define("_FILES_CENTER_IMAGE_URL",XOOPS_URL."/uploads/{$MDIR}/image");
//檔案中心縮圖實體位置
define("_FILES_CENTER_THUMB_DIR",XOOPS_ROOT_PATH."/uploads/{$MDIR}/image/.thumbs");
define("_FILES_CENTER_THUMB_URL",XOOPS_URL."/uploads/{$MDIR}/image/.thumbs");



//上傳圖檔，$col_name=對應欄位名稱,$col_sn=對應欄位編號,$種類：img,file,$sort=圖片排序,$files_sn="更新編號"
function upload_file($upfile='upfile',$col_name="",$col_sn="",$files_sn="",$sort="",$desc=null,$thum=true){
	global $xoopsDB,$xoopsUser,$xoopsModule,$MDIR;
  
	//引入上傳物件
  include_once XOOPS_ROOT_PATH."/modules/tadtools/upload/class.upload.php";

	//取消上傳時間限制
  set_time_limit(0);
  //設置上傳大小
  ini_set('memory_limit', '80M');
  
  //刪除勾選檔案
  if(!empty($_POST['del_file'])){
    foreach($_POST['del_file'] as $del_files_sn){
			del_files($del_files_sn);
		}
	}
	  
  $files = array();
  foreach ($_FILES[$upfile] as $k => $l) {
    foreach ($l as $i => $v) {
      if (!array_key_exists($i, $files)){
        $files[$i] = array();
			}
      $files[$i][$k] = $v;
    }
  }
  
  foreach ($files as $file) {
    if(empty($file['name']))continue;
    
		//先刪除舊檔
		if(!empty($sort)){
	  	del_files(null,$col_name,$col_sn,$sort);
	  }else{
			$sort=auto_sort($col_name,$col_sn);
		}
    
		//取得檔案
	  $file_handle = new upload($file,"zh_TW");

	  if ($file_handle->uploaded) {
	      //取得副檔名
	      $ext=strtolower($file_handle->file_src_name_ext);
	      //判斷檔案種類
	      if($ext=="jpg" or $ext=="jpeg" or $ext=="png" or $ext=="gif"){
					$kind="img";
				}else{
					$kind="file";
				}

	      $file_handle->file_safe_name = false;
	      $file_handle->file_overwrite = true;
	      $file_handle->file_new_name_body   = "{$col_name}_{$col_sn}_{$sort}";
	      //若是圖片才縮圖
	      if($kind=="img" and $thum==true){
	        //增加 $thumbnail=true=true 才縮圖
	       
	        if($file_handle->image_src_x > _FC_THUMB_WIDTH ){
			      $file_handle->image_resize         = true;
			      $file_handle->image_x              = _FC_THUMB_WIDTH;
			      $file_handle->image_ratio_y         = true;
		      }
	      }
	      $path=($kind=="img")?_FILES_CENTER_IMAGE_DIR:_FILES_CENTER_DIR;
	      $file_handle->process($path);
	      $file_handle->auto_create_dir = true;

	      //若是圖片才製作小縮圖
	      if($kind=="img"){
		      $file_handle->file_safe_name = false;
		      $file_handle->file_overwrite = true;
		      $file_handle->file_new_name_body   = "{$col_name}_{$col_sn}_{$sort}";
		      if($file_handle->image_src_x > _FC_THUMB_SMALL_WIDTH){
			      $file_handle->image_resize         = true;
			      $file_handle->image_x              = _FC_THUMB_SMALL_WIDTH;
			      $file_handle->image_ratio_y         = true;
					}
		      $file_handle->process(_FILES_CENTER_THUMB_DIR);
		      $file_handle->auto_create_dir = true;
				}

				//上傳檔案
	      if ($file_handle->processed) {
	          $file_handle->clean();
	          $file_name="{$col_name}_{$col_sn}_{$sort}.{$ext}";
            $description=empty($desc)?$file['name']:$desc;
	          if(empty($files_sn)){
	  	        $sql = "insert into ".$xoopsDB->prefix("{$MDIR}_files_center")." (`col_name`,`col_sn`,`sort`,`kind`,`file_name`,`file_type`,`file_size`,`description`) values('$col_name','$col_sn','$sort','{$kind}','{$file_name}','{$file['type']}','{$file['size']}','{$description}')";
	  					
              $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	          }else{
	            $sql = "replace into ".$xoopsDB->prefix("{$MDIR}_files_center")." (`files_sn`,`col_name`,`col_sn`,`sort`,`kind`,`file_name`,`file_type`,`file_size`,`description`) values('{$files_sn}','$col_name','$col_sn','$sort','{$kind}','{$file_name}','{$file['type']}','{$file['size']}','{$description}')";
	  					$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	          }
	      } else {
						redirect_header($_SERVER['PHP_SELF'],3, "Error:".$file_handle->error);
	      }
	  }
	  $sort="";
  }
  
  
  if(!is_null($desc)){
     $sql = "update ".$xoopsDB->prefix("{$MDIR}_files_center")." set `description`='{$desc}' where col_name='{$col_name}' and col_sn='{$col_sn}'";
    $xoopsDB->queryF($sql);
  }
}

//刪除實體檔案
function del_files($files_sn="",$col_name="",$col_sn="",$sort=""){
	global $xoopsDB,$xoopsUser,$MDIR;
	
	if(!empty($files_sn)){
		$del_what="`files_sn`='{$files_sn}'";
	}elseif(!empty($col_name) and !empty($col_sn)){
	  $and_sort=(empty($sort))?"":"and `sort`='{$sort}'";
		$del_what="`col_name`='{$col_name}' and `col_sn`='{$col_sn}' $and_sort";
	}

	$sql = "select * from ".$xoopsDB->prefix("{$MDIR}_files_center")." where $del_what";
  
 	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()."<br>".$sql);
 	
 	while(list($files_sn,$col_name,$col_sn,$sort,$kind,$file_name,$file_type,$file_size,$description)=$xoopsDB->fetchRow($result)){
 	
 	  $del_sql = "delete  from ".$xoopsDB->prefix("{$MDIR}_files_center")." where files_sn='{$files_sn}'";
 		$xoopsDB->queryF($del_sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
 	
		if($kind=="img"){
			unlink(_FILES_CENTER_IMAGE_DIR."/$file_name");
			unlink(_FILES_CENTER_THUMB_DIR."/$file_name");
		}else{
			unlink(_FILES_CENTER_DIR."/$file_name");
		}
	}
}

//取得檔案 $kind=images（大圖）,thumb（小圖），$mode=link（完整連結）or array（路徑陣列）
function get_file($col_name="",$col_sn="",$sort=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$MDIR;
	
  
	$and_sort=(!empty($sort))?" and `sort`='{$sort}'":"";
	
	$sql = "select * from ".$xoopsDB->prefix("{$MDIR}_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' $and_sort order by sort";

 	$result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
 	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    $files[$files_sn]['kind']=$kind;
    $files[$files_sn]['sort']=$sort;
    $files[$files_sn]['file_name']=$file_name;
    $files[$files_sn]['file_type']=$file_type;
    $files[$files_sn]['file_size']=$file_size;
    $files[$files_sn]['description']=$description;
    
    if($kind=="img"){
			$files[$files_sn]['link']="<a href='{$_SERVER['PHP_SELF']}?fop=dl&files_sn=$files_sn' title='{$description}' rel='lyteshow[pic]'><img src='"._FILES_CENTER_IMAGE_URL."/{$file_name}' alt='{$description}' title='{$description}'></a>";
			$files[$files_sn]['path']=_FILES_CENTER_IMAGE_URL."/{$file_name}";
			$files[$files_sn]['url']="<a href='{$_SERVER['PHP_SELF']}?fop=dl&files_sn=$files_sn' title='{$description}' target='_blank'>{$description}</a>";

			$thumb_pic=(file_exists(_FILES_CENTER_THUMB_DIR."/{$file_name}"))?_FILES_CENTER_THUMB_URL."/{$file_name}":XOOPS_URL."/modules/tadtools/multiple-file-upload/no_thumb.gif";
			
			$files[$files_sn]['tb_link']="<a href='{$_SERVER['PHP_SELF']}?fop=dl&files_sn=$files_sn' title='{$description}' rel='lyteshow[pic]'><img src='$thumb_pic' alt='{$description}' title='{$description}'></a>";
			$files[$files_sn]['tb_path']=_FILES_CENTER_THUMB_URL."/{$file_name}";
			$files[$files_sn]['tb_url']="<a href='{$_SERVER['PHP_SELF']}?fop=dl&files_sn=$files_sn' title='{$description}' rel='lyteshow[pic]'>{$description}</a>";
		}else{
			//$files[$files_sn]['link']="<a href='"._FILES_CENTER_URL."/{$file_name}'>{$description}</a>";
			$files[$files_sn]['link']="<a href='{$_SERVER['PHP_SELF']}?fop=dl&files_sn=$files_sn'>{$description}</a>";
			$files[$files_sn]['path']=_FILES_CENTER_URL."/{$file_name}";
		}
	}
	return $files;
}

//列出可刪除檔案
function list_del_file($col_name="",$col_sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$MDIR;

  $files="<div>Delete:<br>";

	$sql = "select * from ".$xoopsDB->prefix("{$MDIR}_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";

 	$result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
 	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $files.="<input type='checkbox' name='del_file[]' value='{$files_sn}'> $description<br>";
	}
	$files.="</div>";
	return $files;
}

//取得附檔或附圖$show_mode=filename、num
function show_files($col_name="",$col_sn="",$thumb=false,$show_mode=""){
	global $MDIR;
	if($show_mode==""){
		$all_files="<script type='text/javascript' language='javascript' src='".XOOPS_URL."/modules/tadtools/lytebox/lytebox.js'></script>
	<link rel='stylesheet' href='".XOOPS_URL."/modules/tadtools/lytebox/lytebox.css' type='text/css' media='screen' />";
	}else{
    $all_files="";
	}
	$file_arr="";
	$file_arr=get_file($col_name,$col_sn);

	if($file_arr){
	  $i=1;
		foreach($file_arr as $files_sn => $file_info){

			if($show_mode=="filename"){
			  if($file_info['kind']=="file"){
					$all_files.="<div>({$i}) {$file_info['link']}</div>";
				}else{
					$all_files.="<div>({$i}) {$file_info['url']}</div>";
				}
			}else{
			  if($file_info['kind']=="file"){
     			$linkto=$file_info['path'];
					$description=$file_info['description'];
					$thumb_pic=XOOPS_URL."/modules/tadtools/multiple-file-upload/downloads.png";
					$rel="";
				}else{
					$linkto=$file_info['path'];
					$description=$file_info['description'];
					$thumb_pic=$file_info['tb_path'];
					$rel="rel='lyteshow[pic]' title='{$description}'";
				}
				
				$all_files.="<div style='border:0px solid gray;width:110px;height:113px;float:left;display:inline;margin:2px;'>
					<a href='{$linkto}' $rel>
					<div align='center' style=\"border:1px solid #CFCFCF;width:110px;height:90px;overflow:hidden;margin:2px auto;background-image:url('{$thumb_pic}');background-repeat: no-repeat;background-position: center center;cursor:pointer;\">
					</div>
				</a>
				<div style='text-align:center;line-height:150%;'><a href='{$linkto}' $rel style='font-size:11px;font-weight:normal;text-align:left;'>({$i}){$description}</a></div>
				</div>";
			}

      $i++;
		}
	}else{
    $all_files="";
	}
	return $all_files;
}

//取得單一檔案資料
function get_one_file($files_sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$MDIR;

	$sql = "select * from ".$xoopsDB->prefix("{$MDIR}_files_center")." where `files_sn`='{$files_sn}'";

 	$result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
 	$all=$xoopsDB->fetchArray($result);
 	return $all;
}

//自動編號
function auto_sort($col_name="",$col_sn=""){
	global $xoopsDB,$xoopsUser,$MDIR;

	$sql = "select max(sort) from ".$xoopsDB->prefix("{$MDIR}_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}'";

 	$result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
 	list($max)=$xoopsDB->fetchRow($result);
	return ++$max;
}

//下載並新增計數器
function add_file_counter($files_sn=""){
	global $xoopsDB,$MDIR;

  $file=get_one_file($files_sn);
	$sql = "update ".$xoopsDB->prefix("{$MDIR}_files_center")." set `counter`=`counter`+1 where `files_sn`='{$files_sn}'";

 	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());

	if($file['kind']=="img"){
		header("location:"._FILES_CENTER_IMAGE_URL."/{$file['file_name']}");
	}else{
		header("location:"._FILES_CENTER_URL."/{$file['file_name']}");
	}
}

if($_GET['fop']=="dl"){
  add_file_counter($_GET['files_sn']);
}
##########################################################################
#  取出圖片(欄位名稱,流水號,大小模式,css)
#  get_one_pic($col_name="",$col_sn="",$mode="big",$style="")
#
#
##########################################################################
function get_one_pic($col_name="",$col_sn="",$mode="big",$style=""){
  $files=get_file($col_name,$col_sn,"0");
  if(empty($files))return;
  foreach($files as $files_sn => $pic)
  if($mode!="big"){
    return "<img src='{$pic['tb_path']}' alt='{$pic['description']}' title='{$pic['description']}' style='$style'>";
  }else{
    return "<img src='{$pic['path']}' alt='{$pic['description']}' title='{$pic['description']}' style='$style'>";
  }
}
##########################################################################
#  取出圖片(欄位名稱,流水號,大小模式,css)
#  get_one_pic($col_name="",$col_sn="",$mode="big",$style="")
#
#
##########################################################################
function get_one_description($col_name="",$col_sn=""){
  $files=get_file($col_name,$col_sn,"0");
  if(empty($files))return;
  foreach($files as $files_sn => $pic)
  return $pic['description'];
}
?>
