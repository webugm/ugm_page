<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2012-04-10
// $Id:$
// ------------------------------------------------------------------------- //

//需加入模組語系
define("_TAD_NEED_TADTOOLS"," 需要 tadtools 模組，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");


define("_TO_ADMIN_PAGE","管理介面");
define("_BP_SUCCESS","資料寫入成功");
define("_BP_DEL_SUCCESS","刪除成功");
define("_BP_DEL_CHK","確定要刪除此資料？");
define("_BP_FUNCTION","功能");
define("_BP_EDIT","編輯");
define("_BP_ENABLE","啟用");
define("_BP_ENABLE_0","停用");
define("_BP_DEL","刪除");
define("_BP_ADD","新增文章");
define("_BP_FOR","：");
define("_BP_ALL","全部");
define("_BP_GO","執行");




define("_MD_INPUT_FORM","輸入表單");
define("_MD_SAVE","儲存");
define("_MD_INPUT_VALID","「%s」欄位檢查");
define("_MD_INPUT_VALIDATOR","請輸入「%s」欄位");
define("_MD_INPUT_VALIDATOR_ERROR","「%s」資料不正確");
define("_MD_INPUT_VALIDATOR_CHK","最少 %s 個字，最多 %s 個字");
define("_MD_INPUT_VALIDATOR_MIN","最少 %s 個字");
define("_MD_INPUT_VALIDATOR_MAX","最多 %s 個字");
define("_MD_INPUT_VALIDATOR_EQUAL","限定 %s 個字");
define("_MD_INPUT_VALIDATOR_NEED","不可空白");
define("_MD_INPUT_VALIDATOR_RANGE","範圍： %s ～ %s");
define("_MD_INPUT_VALIDATOR_RANGE_MIN","最小： %s");
define("_MD_INPUT_VALIDATOR_RANGE_MAX","最大： %s");

define("_MD_UGMPAGE_SMNAME1", "模組首頁");
define("_MD_UGMPAGE_SMNAME2", "類別管理");
define("_MD_UGMPAGE_SMNAME3", "文章管理");
define("_MD_UGMPAGE_SMNAME4", "新增文章");
define("_MD_UGMPAGE_SMNAME5", "編輯文章");
define("_MD_UGMPAGE_SMNAME6", "選單管理");
define("_MD_UGMPAGE_SMNAME9", "佈景管理");

//---------------------cate.php--------------------------------
define("_MD_UGMPAGE_ADD_SUB", "在「%s」底下建立一個分類");
define("_MD_UGMPAGE_CATE_TITLE", "分類標題");
define("_MD_UGMPAGE_CATE_ENABLE", "啟用");
define("_MD_UGMPAGE_CATE_SORT", "排序");
define("_MD_UGMPAGE_CATE_OFCSN", "父層分類");
define("_MD_UGMPAGE_CATE_FORM", "分類表單");
define("_MD_UGMPAGE_MDMENU2", "類別管理");
define("_MD_UGMPAGE_MDMENU2_1", "佈景類別管理");
define("_BP_HOME", " 根目錄");
define("_BP_ADD_CATE", " 新增分類");
define("_BP_RESET_SORT", " 重新排序");
define("_BP_REQUIRED", "必填");
define("_BP_INSERT_SUCCESS", "寫入成功");
define("_BP_DEL_SUCCESS", "刪除成功");

//------------------- post.php ------------------------------------
define("_MD_UGMPAGE_MSN","msn");
define("_MD_UGMPAGE_CSN","分類");
define("_MD_UGMPAGE_TITLE","標題");
define("_MD_UGMPAGE_CONTENT","內容");
define("_MD_UGMPAGE_START_TIME","開始時間");
define("_MD_UGMPAGE_END_TIME","結束時間");
define("_MD_UGMPAGE_ENABLE","啟用");
define("_MD_UGMPAGE_TOP","置頂");
define("_MD_UGM_PAGE_MAIN_FORM","輸入表單");
define("_MD_UGMPAGE_COUNTER","人氣");
define("_MD_UGMPAGE_DATE","發佈時間");
define("_MD_UGMPAGE_UID","發佈者");
define("_MD_UGMPAGE_TITLE_LINK","相關文章");
define("_MD_UGMPAGE_UPDATE_SORT_LIST","更新分類「%s」的排序");

define("_MD_UGMPAGE_SLIDER","滑動圖片");
define("_MD_UGMPAGE_SLIDER_TXT","摘要說明");
define("_MD_UGMPAGE_SHOW_INPUT_FORM","進階設定");
define("_MD_UGMPAGE_EDITOR","可編輯者");


//滑動圖片 slider 摘要說明 slider_txt

//------------------- menu.php ----------------------------------------
define("_MD_UGMPAGE_MENU_ADD_SUB","在「%s」底下建立一個選單");
define("_MD_UGMPAGE_MENU_NO_DEL_SUB","尚有子類，無法刪除");
define("_MD_UGMPAGE_MENU_TIP","提示");
define("_MD_UGMPAGE_MENU_URL","網址");
define("_MD_UGMPAGE_MENU_NEW","新視窗");

define("_MD_UGMPAGE_MENU_ALL","全部");
define("_MD_UGMPAGE_MENU_ADD_CATE","新增分類");
define("_MD_UGMPAGE_MENU_HOME","根目錄");
define("_MD_UGMPAGE_MENU_URL","網址 / 選項");
define("_MD_UGMPAGE_MENU_TIP","提示");


define("_MD_UGMPAGE_MENU_T1_TITLE","伸縮選單");
define("_MD_UGMPAGE_MENU_T2_TITLE","圖片輪播");
define("_MD_UGMPAGE_MENU_T3_TITLE","跑馬燈");
define("_MD_UGMPAGE_MENU_T4_TITLE","下拉選單");
define("_MD_UGMPAGE_MENU_T5_TITLE","單層選單");
define("_MD_UGMPAGE_MENU_T6_TITLE","樹狀選單");
define("_MD_UGMPAGE_MENU_FORM","表單");
// ----------- 變數管理 ---------------------------------------------

define("_MA_UGMPAGE_VAR_CSN","類別");
define("_MA_UGMPAGE_VAR_SORT","排序");
define("_MA_UGMPAGE_VAR_ENABLE","啟用");
define("_MA_UGMPAGE_VAR_TYPE","類型");
define("_MA_UGMPAGE_VAR_VAR_TITLE","標題");
define("_MA_UGMPAGE_VAR_VAR_NAME","變數名稱");
define("_MA_UGMPAGE_VAR_VAR_TIP","提示");
define("_MA_UGMPAGE_VAR_VAR_VALUE","變數值");
