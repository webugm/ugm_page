<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// 伸縮選單區塊type=1
// ------------------------------------------------------------------------- //
include_once "ugm_page_block_function.php"; 
//區塊主函式 (自訂頁面分類(ugm_page_b_cate))
function ugm_page_smoothmenu($options){
	global $xoopsDB;
	if(empty($options[1])) return;
	$options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.選擇出現類別(單選) 
  $options[2]=(empty($options[2]))?720:$options[2];             # 3.寬度
	# ---------------------------------------------------------------------------
	$path=XOOPS_URL."/modules/ugm_page";
  $img_path=XOOPS_URL."/uploads/ugm_page/image";
	# ---------------------------------------------------------------------------
	# --------------------- 引入jquery -------------------------------------
	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
  # ----------------------- head  ------------------------------------------ 
  
  
  $block="     
    <link rel='stylesheet' type='text/css' href='{$path}/class/smoothmenu/ddsmoothmenu1.css' />  
    <style>
      #smoothmenu_{$options[0]}.ddsmoothmenu{
        width: {$options[2]}px; /* 寬度 */
        position: absolute;
      }
    </style>
    {$jquery_path}
    <script type='text/javascript' src='{$path}/class/smoothmenu/ddsmoothmenu.js.php'>
    
    /***********************************************
    * Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * This notice MUST stay intact for legal use
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
    ***********************************************/
    
    </script>
    
    <script type='text/javascript'>
    
    ddsmoothmenu.init({
    	mainmenuid: 'smoothmenu_{$options[0]}', //menu DIV id
    	orientation: 'h', //Horizontal or vertical menu: Set to 'h' or 'v'
    	classname: 'ddsmoothmenu', //class added to menu's outer DIV
    	//customtheme: ['#1c5a80', '#18374a'],
    	//customtheme: ['#414141', 'black'], //[背景,hove]
    	contentsource: 'markup' //'markup' or ['container_id', 'path_to_menu_file']
    })
    
    
    </script>
    
    <div id='smoothmenu_{$options[0]}' class='ddsmoothmenu' style='background: none; '>
     ";
    # get body
    $block_body=get_b_smoothmenu_body($options[1]);
    if(empty($block_body))return;
    $block.=$block_body;   
    
    $block.="
    <br style='clear: left' />
    </div>
  ";
  
  return $block;
}
//".chk($options[2],"shadow",0,"selected")."
//區塊編輯函式
function ugm_page_smoothmenu_edit($options){
  $options[0]=(empty($options[0]))?strtotime("now"):$options[0];# 1.ID
  $options[1]=(empty($options[1]))?0:$options[1];               # 2.選擇出現類別(單選) 
  $options[2]=(empty($options[2]))?720:$options[2];             # 3.寬度
  # 得到類別
  $get_cate_smoothmenu=get_cate_smoothmenu($options[1]);
  # 編輯表單
  $form="
  <table style='width:auto;'> 
     <tr><th>1.</th><th>ID</th><td><input type='text' name='options[0]' value='{$options[0]}' size=12> <p>"._MB_UGMPAGE_B_ID_PS."</p></td></tr>
     <tr><th>2.</th><th>"._MB_UGMPAGE_B_CATE."</th><td>{$get_cate_smoothmenu}</td></tr>
     <tr><th>3.</th><th>"._MB_UGMPAGE_B_WIDTH."</th><td><INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px</td></tr>  
  </table>  
  
  
  
  
  ";
	return $form;
}

###############################################################################
#  得到類別選單(單選)
#  `menu_enable`=1 and `menu_type`=4 and `menu_ofsn`=0
#
#
###############################################################################
if(!function_exists("get_cate_smoothmenu")){
function get_cate_smoothmenu($csn_chk){
  global $xoopsDB;
  //不考慮多層cate 
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_enable`=1 and `menu_type`=4 and `menu_ofsn`=0 order by `menu_sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error()); 
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }    
    $checkbox_cate.="<input type='radio' name='options[1]' value='{$menu_sn}' ".chk($csn_chk,$menu_sn)."><label>{$menu_title}</label>";     
  }
  return  $checkbox_cate;
}
}

###############################################################################
#  得到選單
#  
#
#
###############################################################################
if(!function_exists("get_b_smoothmenu_body")){
function get_b_smoothmenu_body($menu_ofsn=0){
  global $xoopsDB;
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	$total=$xoopsDB->getRowsNum($result); #記錄筆數
	if($total==0)return;
	
  $main="<ul>";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    $main.="<li><a href='{$menu_url}'{$target}>{$menu_title}</a>".get_b_smoothmenu_body($menu_sn)."</li>";
  }
  $main.="</ul>";
  return $main;
}
}
###############################################################################
#  得到佈景選單
#
#
#
###############################################################################
if(!function_exists("get_theme_smoothmenu_body")){
function get_theme_smoothmenu_body($menu_ofsn=0,$level=0){
  global $xoopsDB;
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	$total=$xoopsDB->getRowsNum($result); #記錄筆數
	if($total==0)return;

  $main=($level!=0)?"<ul>":"";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    $main.="<li><a href='{$menu_url}'{$target}>{$menu_title}</a>".get_theme_smoothmenu_body($menu_sn,1)."</li>";
  }
  $main.=($level!=0)?"</ul>":"";
  return $main;
}
}
###############################################################################
#  得到佈景選單 -> css3menu1
#  已輸出完整的code
#
#
###############################################################################
if(!function_exists("get_theme_css3menu1")){
function get_theme_css3menu1($menu_ofsn=0,$level=0){
  global $xoopsDB;
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	$total=$xoopsDB->getRowsNum($result); #記錄筆數
	if($total==0)return;
  $i=0;
  $main=($level!=0)?"<ul>":
  "<link rel='stylesheet' href='".XOOPS_URL."/modules/ugm_page/css3menu1.css' type='text/css' />
   <div style='margin:20px;'><ul id='css3menu1' class='topmenu'>";
  
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $i++;
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    if($i==1 and $level==0){
      $class="class='topfirst'";
    }elseif($i==$total and $level==0){
      $class="class='toplast'";
    }elseif($level==0){
      $class="class='topmenu'";
    }else{
      $class="";
    }
    $main.="<li {$class}><a href='{$menu_url}'{$target}>{$menu_title}</a>".get_theme_css3menu1($menu_sn,1)."</li>";
  }
  $main.=($level!=0)?"</ul>":"</ul></div>";
  return $main;
}
}
###############################################################################
#  得到佈景選單(自行設定背景)
#
#
#
###############################################################################
if(!function_exists("get_all")){
function get_all(){ 
  
    global $xoopsDB;
      $sql = "select `menu_sn`  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='0' order by `menu_sort` limit 1 ";//die($sql);
	  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, "");//web_error()
      list($menu_sn)=$xoopsDB->fetchRow($result);
      if($menu_sn){      
        $main= get_all_body($menu_sn);
        print_r($main);
        die();
      } 
}
}

function get_all_body($menu_ofsn=0,$level=0){
      global $xoopsDB;
      
      $sql = "select *  
              from ".$xoopsDB->prefix("ugm_page_menu")." 
              where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' 
              order by `menu_sort` ";//die($sql);
      $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
      $total=$xoopsDB->getRowsNum($result); #記錄筆數
      if($total==0)return;
      while($all=$xoopsDB->fetchArray($result)){
    	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
        foreach($all as $k=>$v){
          $$k=$v;
        }
          $main[$menu_ofsn][$menu_sn]['menu_title']=$menu_title;          
          $main[$menu_ofsn][$menu_sn]['menu_new']=$menu_new;           
          $main[$menu_ofsn][$menu_sn]['menu_url']=$menu_url;
          
          $sql_0 = "select *  
                    from ".$xoopsDB->prefix("ugm_page_menu")." 
                    where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_sn}' 
                    order by `menu_sort` ";//die($sql);
          $result_0 = $xoopsDB->query($sql_0) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
          $total_0=$xoopsDB->getRowsNum($result_0); #記錄筆數
          if($total_0){             
             # ----------------------------------
             while($all_0=$xoopsDB->fetchArray($result_0)){
    	          //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
                foreach($all_0 as $k=>$v){
                  $$k=$v;
                }
                  $main[$menu_ofsn][$menu_sn]['menu_title']=$menu_title;          
                  $main[$menu_ofsn][$menu_sn]['menu_new']=$menu_new;           
                  $main[$menu_ofsn][$menu_sn]['menu_url']=$menu_url;
                  
                  $sql_1 = "select *  
                            from ".$xoopsDB->prefix("ugm_page_menu")." 
                            where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_sn}' 
                            order by `menu_sort` ";//die($sql);
                  $result_1 = $xoopsDB->query($sql_1) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
                  $total_1=$xoopsDB->getRowsNum($result_1); #記錄筆數
                  if($total_1){  
                     # ----------------------------------
                     while($all_1=$xoopsDB->fetchArray($result_1)){
            	          //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
                        foreach($all_1 as $k=>$v){
                          $$k=$v;
                        }
                          $main[$menu_ofsn][$menu_sn]['menu_title']=$menu_title;          
                          $main[$menu_ofsn][$menu_sn]['menu_new']=$menu_new;           
                          $main[$menu_ofsn][$menu_sn]['menu_url']=$menu_url;
                      } 
                     # -----------------------------------
                  } 
                }
              }
             # -----------------------------------          
       }
          
          
  
      return $main;
}
    
    



###############################################################################
#  得到佈景選單(自行設定背景)
#
#
#
###############################################################################
if(!function_exists("get_all_theme_smoothmenu_body")){
function get_all_theme_smoothmenu_body($menu_ofsn=0,$level=0){
  global $xoopsDB;
  $sql = "select *  from ".$xoopsDB->prefix("ugm_page_menu")." where `menu_type`='4' and `menu_enable`='1' and `menu_ofsn`='{$menu_ofsn}' order by `menu_sort` ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
  $total=$xoopsDB->getRowsNum($result); #記錄筆數
  if($total==0)return;
  # --------------------- 引入jquery -------------------------------------
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
  
  
  //--------  由區塊取得徧好設定 -----------------
  # 得到指定模組 mid

  $module_handler =& xoops_gethandler('module');
  $xoopsModule =& $module_handler->getByDirname('ugm_page');
  $mid = $xoopsModule->getVar('mid');
  # 取得該 mid 的模組參數
  $config_handler = & xoops_gethandler('config');
  $xoopsModuleConfig = & $config_handler->getConfigsByCat(0, $mid);
  //----------------------------------------------------------------
  $t4_fontsize=isset($xoopsModuleConfig['t4_fontsize'])?$xoopsModuleConfig['t4_fontsize']:16;                            //字型大小
  $t4_color=isset($xoopsModuleConfig['t4_color'])?$xoopsModuleConfig['t4_color']:"#fff";                                 //字型顏色
  $t4_background=isset($xoopsModuleConfig['t4_background'])?$xoopsModuleConfig['t4_background']:"none";                  //下拉選單背景
  $t4_width=isset($xoopsModuleConfig['t4_width'])?$xoopsModuleConfig['t4_width']:860;                                    //寬度
  $t4_left_right_margin=isset($xoopsModuleConfig['t4_left_right_margin'])?$xoopsModuleConfig['t4_left_right_margin']:20; //左右邊界
  $t4_top_bottom_margin=isset($xoopsModuleConfig['t4_top_bottom_margin'])?$xoopsModuleConfig['t4_top_bottom_margin']:3;  //上下邊界
  # ----------------------- ------------------------------------------ 

  
  $main=($level!=0)?"<ul>":"
    <link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/ugm_page/class/smoothmenu/themes.css' />  
    <style>
      #smoothmenu_themes.ddsmoothmenu{
        width: {$t4_width}px;  /*1_寬度 */
        position: absolute;
      }
	  .ddsmoothmenu ul li a:link, .ddsmoothmenu ul li a:visited{
        color: {$t4_color};               /*字型顏色*/
	  }
 
    </style>
    {$jquery_path}
    <script type='text/javascript' src='".XOOPS_URL."/modules/ugm_page/class/smoothmenu/ddsmoothmenu.js.php'>
    /***********************************************
    * Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * This notice MUST stay intact for legal use
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
    ***********************************************/
    </script>
    <script type='text/javascript'>
    ddsmoothmenu.init({
    	mainmenuid: 'smoothmenu_themes', //menu DIV id
    	orientation: 'h', //Horizontal or vertical menu: Set to 'h' or 'v'
    	classname: 'ddsmoothmenu', //class added to menu's outer DIV
    	//customtheme: ['#1c5a80', '#18374a'],
    	//customtheme: ['#4284A1', 'black'], //[背景,hove]
    	contentsource: 'markup' //'markup' or ['container_id', 'path_to_menu_file']
    })
    </script>
	<div id='smoothmenu_themes' class='ddsmoothmenu' style='font-size:{$t4_fontsize}px ;background:{$t4_background}; margin:{$t4_top_bottom_margin}px {$t4_left_right_margin}px;'>
     <ul>
  ";
  while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數：  `menu_sn`, `menu_type`, `menu_ofsn`, `menu_sort`, `menu_title`, `menu_op`, `menu_tip`, `menu_enable`, `menu_new`, `menu_url`, `menu_date`, `menu_uid`
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $menu_url=empty($menu_url)?"#":$menu_url;
    $target=($menu_new==1)?" target='_blank'":"";
    $main.="<li><a href='{$menu_url}'{$target}>{$menu_title}</a>".get_all_theme_smoothmenu_body($menu_sn,1)."</li>";
  }
  $main.=($level!=0)?"</ul>":"
    </ul>
    <div style='clear: left' ></div>
    </div>
  ";
  return $main;
}
}

?>