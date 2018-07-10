<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/modules/tadtools/language/{$xoopsConfig['language']}/modinfo_common.php";

define("_MI_UGMPAGE_NAME","UGM_自訂頁面");
define("_MI_UGMPAGE_AUTHOR","育將電腦工作室");
define("_MI_UGMPAGE_CREDITS","UGM");
define("_MI_UGMPAGE_DESC","產生自訂區塊，2013-07-25-2.0版");
define("_BACK_MODULES_PAGE","回模組首頁");
//-----------後台選單 --------------------- 
define("_MI_UGMPAGE_HOME", "首頁");
define("_MI_UGMPAGE_ADMENU1", "權限管理");
define("_MI_UGMPAGE_ADMENU2", "分類管理");
define("_MI_UGMPAGE_ADMENU3", "文章管理");
define("_MI_UGMPAGE_ADMENU4", "新增文章");
define("_MI_UGMPAGE_ADMENU5", "選單管理");
define("_MI_UGMPAGE_ADMENU6", "圖片輪播");
define("_MI_UGMPAGE_ADMENU7", "跑馬燈");
define("_MI_UGMPAGE_ADMENU8", "下拉選單");
define("_MI_UGMPAGE_ADMENU9", "佈景管理");
//-----------------------------------------
define("_MI_UGMPAGE_TEMPLATE_DESC1", "ugm_page_index_tpl.html的樣板檔。");
define("_MI_UGMPAGE_TEMPLATE_DESC2", "ugm_page_cate_tpl.html的樣板檔。");
define("_MI_UGMPAGE_TEMPLATE_DESC3", "ugm_page_post_tpl.html的樣板檔。");
define("_MI_UGMPAGE_TEMPLATE_DESC4", "ugm_page_menu_tpl.html的樣板檔。"); 
define("_MI_UGMPAGE_TEMPLATE_DESC5", "ugm_page_var_tpl.html的樣板檔。");
define("_MI_UGMPAGE_SMNAME2", "類別管理");
define("_MI_UGMPAGE_SMNAME3", "文章管理");
//------------------ 區塊 ---------------------------------------------------*/
define("_MI_UGMPAGE_BNAME1","自訂頁面分類");
define("_MI_UGMPAGE_BDESC1","自訂頁面分類(ugm_page_b_cate)");

define("_MI_UGMPAGE_BNAME2","顯示單一文章");
define("_MI_UGMPAGE_BDESC2","顯示單一文章(ugm_page_show_page)");

define("_MI_UGMPAGE_BNAME3","自訂頁面分類3");
define("_MI_UGMPAGE_BDESC3","自訂頁面分類3(ugm_page_b_cate3)");

define("_MI_UGMPAGE_BNAME4","伸縮選單1");
define("_MI_UGMPAGE_BDESC4","伸縮選單1(ugm_page_b_menu_t1_1)");

define("_MI_UGMPAGE_BNAME5","伸縮選單2");
define("_MI_UGMPAGE_BDESC5","伸縮選單2(ugm_page_b_menu_t1_2)");

# 滑動圖片區塊 
define("_MI_UGMPAGE_COIN_SLIDER","滑動圖片");
define("_MI_UGMPAGE_COIN_SLIDER_BDESC","滑動圖片(ugm_page_coin_slider)");
# 圖片輪播區塊 
define("_MI_UGMPAGE_COIN_SLIDER_PIC","圖片輪播");
define("_MI_UGMPAGE_COIN_SLIDER_PIC_BDESC","圖片輪播(ugm_page_coin_slider_pic)");
# 跑馬燈
define("_MI_UGMPAGE_MARQUEE","跑馬燈");
define("_MI_UGMPAGE_MARQUEE_BDESC","跑馬燈(ugm_page_marquee.php)");
#下拉選單
define("_MI_UGMPAGE_SMOOTHMENU","下拉選單");
define("_MI_UGMPAGE_SMOOTHMENU_BDESC","下拉選單(ugm_page_smoothmenu)");

define("_MI_UGMPAGE_BNAME10","伸縮選單(bootstrap)");
define("_MI_UGMPAGE_BDESC10","伸縮選單(bootstrap)");

define("_MI_UGMPAGE_BNAME11","樹狀選單");
define("_MI_UGMPAGE_BDESC11","樹狀選單");
//-------------------偏好設定--------------------------------------------
define("_MI_UGMPAGE_TITLE1","<b>相關文章分頁數量？</b>");
define("_MI_UGMPAGE_DESC1","模組首頁下方，相關文章的分頁數量，「0」不要出現、10為10篇一頁，預設為「10」");
define("_MI_UGMPAGE_TITLE2","<b>分類層數</b>");
define("_MI_UGMPAGE_DESC2","設定分類的層數，預設為1層");
define("_MI_UGMPAGE_TITLE3","<b>網頁編輯的寬度</b>");
define("_MI_UGMPAGE_DESC3","設定網頁編輯的寬度，預設為[600]px");
define("_MI_UGMPAGE_TITLE4","<b>網頁編輯的高度</b>");
define("_MI_UGMPAGE_DESC4","設定網頁編輯的高度，預設為[400]px");
define("_MI_UGMPAGE_TITLE5","<b>是否顯示時間列</b>");
define("_MI_UGMPAGE_DESC5","設定是否顯示時間列，預設為顯示");

define("_MI_UGMPAGE_TITLE6","<b>模組文章外框</b>");
define("_MI_UGMPAGE_DESC6","設定模組文章外框，預設為「圓角」");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_NO","無框");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_CORNER","圓角");
define("_MI_UGMPAGE_SHOW_PAGE_OP6_SHADOW","陰影");

define("_MI_UGMPAGE_TITLE7","<b>相關文章外框</b>");
define("_MI_UGMPAGE_DESC7","設定相關文章外框，預設為「圓角」");

//define("_MI_FBCOMMENT_TITLE","是否使用 facebook 留言功能？");
//define("_MI_FBCOMMENT_TITLE_DESC","若選是則會開啟 facebook 的留言功能");

define("_MI_SOCIALTOOLS_TITLE","是否使用推文工具?");
define("_MI_SOCIALTOOLS_TITLE_DESC","若選是則會秀出facebook等推文功能");

define("_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT","輪撥圖片的數量");
define("_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT_DESC","設定輪撥圖片的數量,例「5」，單位：張");

define("_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH","輪撥圖片的寬度");
define("_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH_DESC","設定輪撥圖片的寬度,例「890」，單位：px");

define("_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT","輪撥圖片的高度");
define("_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT_DESC","設定輪撥圖片的高度,例「250」，單位：px");

define("_MI_UGMPAGE_T2_PIC_SLIDER_NAV","輪撥圖片的導航開關");
define("_MI_UGMPAGE_T2_PIC_SLIDER_NAV_DESC","設定輪撥圖片的導航開關,例「on」或「off」");

define("_MI_UGMPAGE_T2_PIC_SLIDER_README","輪撥圖片的文字說明開關");
define("_MI_UGMPAGE_T2_PIC_SLIDER_README_DESC","設定輪撥圖片的文字說明開關,例「on」或「off」");

define("_MI_UGMPAGE_T2_PIC_SLIDER_BORDER","輪撥圖片的外框");
define("_MI_UGMPAGE_T2_PIC_SLIDER_BORDER_DESC","設定輪撥圖片的外框,例「no」「corner」或「shadow」");

define("_MI_UGMPAGE_T4_FONTSIZE","下拉選單的字型大小?");
define("_MI_UGMPAGE_T4_FONTSIZE_DESC","設定下拉選單的字型大小，例「16」，單位：px");

define("_MI_UGMPAGE_T4_COLOR","下拉選單的字型顏色?");
define("_MI_UGMPAGE_T4_COLOR_DESC","設定下拉選單的字型顏色,例「#fff」");

define("_MI_UGMPAGE_T4_BACKGROUND","下拉選單背景顏色?");
define("_MI_UGMPAGE_T4_BACKGROUND_DESC","設定下拉選單背景顏色,例「#000」、「none」");

define("_MI_UGMPAGE_T4_WIDTH","下拉選單的寬度?");
define("_MI_UGMPAGE_T4_WIDTH_DESC","設定下拉選單寬度,例「860」，單位：px");

define("_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN","下拉選單的左右邊界");
define("_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN_DESC","設定下拉選單的左右邊界,例「20」，單位：px");

define("_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN","下拉選單的上下邊界");
define("_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN_DESC","設定下拉選單的上下邊界,例「3」，單位：px");

?>
