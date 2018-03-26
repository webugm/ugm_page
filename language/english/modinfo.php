<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/modules/tadtools/language/{$xoopsConfig['language']}/modinfo_common.php";

define("_MI_UGMPAGE_NAME","UGM_PAGE");
define("_MI_UGMPAGE_AUTHOR","UGM");
define("_MI_UGMPAGE_CREDITS","UGM");
define("_MI_UGMPAGE_DESC","Generate Custom Block 2013 - 07-24 - Version 2.0");
define("_BACK_MODULES_PAGE","Back Module Home");
//-----------後台選單 --------------------- 
define("_MI_UGMPAGE_HOME", "Home");
define("_MI_UGMPAGE_ADMENU1", "Permission Management");
define("_MI_UGMPAGE_ADMENU2", "Category Management");
define("_MI_UGMPAGE_ADMENU3", "Article Management");
define("_MI_UGMPAGE_ADMENU4", "ADD Article");
define("_MI_UGMPAGE_ADMENU5", "MENU Management");
define("_MI_UGMPAGE_ADMENU6", "Image carousel");
define("_MI_UGMPAGE_ADMENU7", "Marquee");
define("_MI_UGMPAGE_ADMENU8", "Drop-down menu");
define("_MI_UGMPAGE_ADMENU9", "Theme Admin");
//-----------------------------------------
define("_MI_UGMPAGE_TEMPLATE_DESC1", "ugm_page_index_tpl.html The template file");
define("_MI_UGMPAGE_TEMPLATE_DESC2", "ugm_page_cate_tpl.html The template file。");
define("_MI_UGMPAGE_TEMPLATE_DESC3", "ugm_page_post_tpl.html The template file");
define("_MI_UGMPAGE_TEMPLATE_DESC4", "ugm_page_menu_tpl.html The template file"); 
define("_MI_UGMPAGE_TEMPLATE_DESC5", "ugm_page_var_tpl.html The template file");
define("_MI_UGMPAGE_SMNAME2", "Category Management");
define("_MI_UGMPAGE_SMNAME3", "Article Management");
//------------------ 區塊 ---------------------------------------------------*/
define("_MI_UGMPAGE_BNAME1","Article Category");
define("_MI_UGMPAGE_BDESC1","Article Category(ugm_page_b_cate)");

define("_MI_UGMPAGE_BNAME2","Shows a single article");
define("_MI_UGMPAGE_BDESC2","Shows a single article(ugm_page_show_page)");

define("_MI_UGMPAGE_BNAME3","Article Category3");
define("_MI_UGMPAGE_BDESC3","Article Category3(ugm_page_b_cate3)");

define("_MI_UGMPAGE_BNAME4","Retractable Menu1");
define("_MI_UGMPAGE_BDESC4","Retractable Menu1(ugm_page_b_menu_t1_1)");

define("_MI_UGMPAGE_BNAME5","Retractable Menu2");
define("_MI_UGMPAGE_BDESC5","Retractable Menu2(ugm_page_b_menu_t1_2)");

# 滑動圖片區塊 
define("_MI_UGMPAGE_COIN_SLIDER","Sliding Picture");
define("_MI_UGMPAGE_COIN_SLIDER_BDESC","Sliding Picture(ugm_page_coin_slider)");
# 圖片輪播區塊 
define("_MI_UGMPAGE_COIN_SLIDER_PIC","Image carousel");
define("_MI_UGMPAGE_COIN_SLIDER_PIC_BDESC","Image carousel(ugm_page_coin_slider_pic)");
# 跑馬燈
define("_MI_UGMPAGE_MARQUEE","Marquee");
define("_MI_UGMPAGE_MARQUEE_BDESC","Marquee(ugm_page_marquee.php)");
#下拉選單
define("_MI_UGMPAGE_SMOOTHMENU","Drop-down menu");
define("_MI_UGMPAGE_SMOOTHMENU_BDESC","Drop-down menu(ugm_page_smoothmenu)");      

//-------------------偏好設定--------------------------------------------
define("_MI_UGMPAGE_TITLE1","<b>Related Articles page number?</b>");
define("_MI_UGMPAGE_DESC1","Modular home below the number of pages related articles，「0」Do not appear、10 to 10 a，Default[10]");
define("_MI_UGMPAGE_TITLE2","<b>Category layers</b>");
define("_MI_UGMPAGE_DESC2","Classification set of layers, the default is a layer");
define("_MI_UGMPAGE_TITLE3","<b>Edit the width of the web</b>");
define("_MI_UGMPAGE_DESC3","Set the width of web editor，default is [600]px");
define("_MI_UGMPAGE_TITLE4","<b>Edit the Height of the web</b>");
define("_MI_UGMPAGE_DESC4","Set the Height of web editor，default is[400]px");
define("_MI_UGMPAGE_TITLE5","<b>Whether to display the time column</b>");
define("_MI_UGMPAGE_DESC5","Sets whether to display the time column is displayed by default");

define("_MI_UGMPAGE_TITLE6","<b>Module article outline</b>");
define("_MI_UGMPAGE_DESC6","Article outlines setting module, the default is 'rounded'");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_NO","NO");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_CORNER","CORNER");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_SHADOW","SHADOW");

define("_MI_UGMPAGE_TITLE7","<b>Related articles outline</b>");
define("_MI_UGMPAGE_DESC7","Related Articles frame set, the default is 'rounded'");

//define("_MI_FBCOMMENT_TITLE","是否使用 facebook 留言功能？");
//define("_MI_FBCOMMENT_TITLE_DESC","若選是則會開啟 facebook 的留言功能");

define("_MI_SOCIALTOOLS_TITLE","Whether Tweets tools?");
define("_MI_SOCIALTOOLS_TITLE_DESC","If the election is facebook will show off features such as Tweets");

define("_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT","Round dial the number of pictures");
define("_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT_DESC","Setting wheel dial the number of pictures, for example, '5', Unit: Piece");

define("_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH","Round dial width of the image");
define("_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH_DESC","Set the width of the picture round dial, for example, '890' units: px");

define("_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT","Round dial picture height");
define("_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT_DESC","Setting wheel dial picture height, for example, '250' units: px");

define("_MI_UGMPAGE_T2_PIC_SLIDER_NAV","Image navigation wheel dial switch");
define("_MI_UGMPAGE_T2_PIC_SLIDER_NAV_DESC","Picture setting wheel navigation dial switch case 'on' or 'off'");

define("_MI_UGMPAGE_T2_PIC_SLIDER_README","Round dial switches the picture captions");
define("_MI_UGMPAGE_T2_PIC_SLIDER_README_DESC","Setting wheel dial picture captions switch case 'on' or 'off'");

define("_MI_UGMPAGE_T2_PIC_SLIDER_BORDER","Round dial picture frame");
define("_MI_UGMPAGE_T2_PIC_SLIDER_BORDER_DESC","Setting wheel dial picture frame, for example 'no' 'corner' or 'shadow'");

define("_MI_UGMPAGE_T4_FONTSIZE","Drop-down menu font size?");
define("_MI_UGMPAGE_T4_FONTSIZE_DESC","Set the font size drop-down menu, for example, '16', unit: px");

define("_MI_UGMPAGE_T4_COLOR","Font Color drop-down menu?");
define("_MI_UGMPAGE_T4_COLOR_DESC","Set the font color drop-down menu, for example, '#fff'");

define("_MI_UGMPAGE_T4_BACKGROUND","Background Color drop-down menu?");
define("_MI_UGMPAGE_T4_BACKGROUND_DESC","Set the background color drop-down menu, for example:[#000] [none]");

define("_MI_UGMPAGE_T4_WIDTH","The width of the drop-down menu?");
define("_MI_UGMPAGE_T4_WIDTH_DESC","Set the width of the drop-down menu, for example:[860]，unit: px");

define("_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN","Drop-down menu left and right borders");
define("_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN_DESC","Set the drop-down menu to the left and right borders,for example:[20]」，unit: px");

define("_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN","The upper and lower boundaries of the drop-down menu");
define("_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN_DESC","Set the upper and lower boundaries of the drop-down menu,for example:[3]，unit: px");

?>
