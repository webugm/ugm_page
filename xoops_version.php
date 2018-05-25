<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_UGMPAGE_NAME;
$modversion['version'] = '3.00';
$modversion['description'] = _MI_UGMPAGE_DESC;
$modversion['author'] = _MI_UGMPAGE_AUTHOR;
$modversion['credits'] = _MI_UGMPAGE_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['status_version'] = '3.0';
$modversion['release_date'] = '2014-05-27';
$modversion['module_website_url'] = 'http://www.ugm.com.tw/';
$modversion['module_website_name'] = 'UGM';
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://www.ugm.com.tw/';
$modversion['author_website_name'] = 'UGM';
$modversion['min_php']=5.2;
$modversion['min_xoops']='2.57';


//---paypal資訊---//
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'tawan158@gmail.com';
$modversion['paypal']['item_name']     = 'Donation :' . _MI_UGMPAGE_AUTHOR;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';


//---後台使用系統選單---//
$modversion['system_menu'] = 1;


//---模組資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "ugm_page_cate";
$modversion['tables'][2] = "ugm_page_main";
$modversion['tables'][3] = "ugm_page_menu";
$modversion['tables'][4] = "ugm_page_files_center";
$modversion['tables'][5] = "ugm_page_var";


//---後台管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';


//---前台主選單設定---//
$modversion['hasMain'] = 1;
//$modversion['sub'][2]['name'] =_MI_UGMPAGE_SMNAME2;
//$modversion['sub'][2]['url'] = "cate.php";
//$modversion['sub'][3]['name'] =_MI_UGMPAGE_SMNAME3;
//$modversion['sub'][3]['url'] = "post.php";



//---模組自動功能---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";


//---偏好設定---//
$modversion['config'] = array();
$i=1;
$modversion['config'][$i]['name'] = 'show_num';//相關文章分頁數量，「0」為不需要相關文章，預設「10」
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE1';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC1';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;
$i++;
$modversion['config'][$i]['name'] = 'cate_level';//類別層數
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE2';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC2';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'fck_width';//fck寬度
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE3';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC3';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 600;
$i++;
$modversion['config'][$i]['name'] = 'fck_height';//fck高度
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE4';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC4';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 400;
$i++;
$modversion['config'][$i]['name'] = 'display_date';//顯示時間列
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE5';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC5';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$i++;
$modversion['config'][$i]['name'] = 'main_border';//模組文章外框
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE6';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC6';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "corner";
$modversion['config'][$i]['options']	= array('_MI_UGMPAGE_SHOW_PAGE_OP6_NO' => 'no','_MI_UGMPAGE_SHOW_PAGE_OP6_CORNER' => 'corner','_MI_UGMPAGE_SHOW_PAGE_OP6_SHADOW' => 'shadow');
$i++;
$modversion['config'][$i]['name'] = 'link_border'; //相關文章外框
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_TITLE7';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_DESC7';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "corner";
$modversion['config'][$i]['options']	= array('_MI_UGMPAGE_SHOW_PAGE_OP6_NO' => 'no','_MI_UGMPAGE_SHOW_PAGE_OP6_CORNER' => 'corner','_MI_UGMPAGE_SHOW_PAGE_OP6_SHADOW' => 'shadow');
$i++;
$modversion['config'][$i]['name'] = 'use_social_tools'; //推文工具
$modversion['config'][$i]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][$i]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';
$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_amount';//輪撥圖片數量
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_AMOUNT_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5;

$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_width';//輪撥圖片寬度
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 890;
$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_height';//輪撥圖片高度
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_HEIGHT_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 250;
$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_nav';//輪撥圖片導航開關(on,off)
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_NAV';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_NAV_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'off';
$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_readme';//輪撥圖片文字說明開關(on,off)
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_README';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_README_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'on';
$i++;
$modversion['config'][$i]['name'] = 't2_pic_slider_border';//輪撥圖片外框 1.空 2.corner 3.shadow
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T2_PIC_SLIDER_BORDER';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T2_PIC_SLIDER_BORDER_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'no';
$i++;
$modversion['config'][$i]['name'] = 't4_fontsize';//下拉選單字型大小
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_FONTSIZE';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_FONTSIZE_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 16;
$i++;
$modversion['config'][$i]['name'] = 't4_color';//下拉選單字型顏色
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_COLOR';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_COLOR_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#fff';
$i++;
$modversion['config'][$i]['name'] = 't4_background';//下拉選單背景顏色
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_BACKGROUND';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_BACKGROUND_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'none';
$i++;
$modversion['config'][$i]['name'] = 't4_width';//整個下拉選單寬度
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_WIDTH';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 860;
$i++;
$modversion['config'][$i]['name'] = 't4_left_right_margin';//下拉選單左右邊界
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_LEFT_RIGHT_MARGIN_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$i++;
$modversion['config'][$i]['name'] = 't4_top_bottom_margin';//下拉選單上下邊界
$modversion['config'][$i]['title'] = '_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN';
$modversion['config'][$i]['description'] = '_MI_UGMPAGE_T4_TOP_BOTTOM_MARGIN_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3;

//---搜尋---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/ugm_page_search.php";
$modversion['search']['func'] = "ugm_page_search";

//---區塊設定---//
$modversion['blocks'][1]['file'] = "ugm_page_b_cate.php";
$modversion['blocks'][1]['name'] = _MI_UGMPAGE_BNAME1;
$modversion['blocks'][1]['description'] = _MI_UGMPAGE_BDESC1;
$modversion['blocks'][1]['show_func'] = "ugm_page_b_cate";
$modversion['blocks'][1]['template'] = "ugm_page_b_cate.html";
$modversion['blocks'][1]['edit_func'] = "ugm_page_b_cate_edit";
$modversion['blocks'][1]['options'] = "|0|180|click||date";

$modversion['blocks'][2]['file'] = "ugm_page_show_page.php";
$modversion['blocks'][2]['name'] = _MI_UGMPAGE_BNAME2;
$modversion['blocks'][2]['description'] = _MI_UGMPAGE_BDESC2;
$modversion['blocks'][2]['show_func'] = "ugm_page_show_page";
$modversion['blocks'][2]['template'] = "ugm_page_show_page.html";
$modversion['blocks'][2]['edit_func'] = "ugm_page_show_page_edit";
$modversion['blocks'][2]['options'] = "||corner";

$modversion['blocks'][3]['file'] = "ugm_page_b_cate3.php";
$modversion['blocks'][3]['name'] = _MI_UGMPAGE_BNAME3;
$modversion['blocks'][3]['description'] = _MI_UGMPAGE_BDESC3;
$modversion['blocks'][3]['show_func'] = "ugm_page_b_cate3";
$modversion['blocks'][3]['template'] = "ugm_page_b_cate3.html";
$modversion['blocks'][3]['edit_func'] = "ugm_page_b_cate3_edit";
$modversion['blocks'][3]['options'] = "|0|180|click|date";

$modversion['blocks'][4]['file'] = "ugm_page_b_menu_t1_1.php";
$modversion['blocks'][4]['name'] = _MI_UGMPAGE_BNAME4;
$modversion['blocks'][4]['description'] = _MI_UGMPAGE_BDESC4;
$modversion['blocks'][4]['show_func'] = "ugm_page_b_menu_t1_1";
$modversion['blocks'][4]['template'] = "ugm_page_b_menu_t1_1.html";
$modversion['blocks'][4]['edit_func'] = "ugm_page_b_menu_t1_1_edit";
$modversion['blocks'][4]['options'] = "|0|180|click";

$modversion['blocks'][5]['file'] = "ugm_page_b_menu_t1_2.php";
$modversion['blocks'][5]['name'] = _MI_UGMPAGE_BNAME5;
$modversion['blocks'][5]['description'] = _MI_UGMPAGE_BDESC5;
$modversion['blocks'][5]['show_func'] = "ugm_page_b_menu_t1_2";
$modversion['blocks'][5]['template'] = "ugm_page_b_menu_t1_2.html";
$modversion['blocks'][5]['edit_func'] = "ugm_page_b_menu_t1_2_edit";
$modversion['blocks'][5]['options'] = "|0|180|click";
# 滑動圖片
$modversion['blocks'][6]['file'] = "ugm_page_coin_slider.php";
$modversion['blocks'][6]['name'] = _MI_UGMPAGE_COIN_SLIDER;
$modversion['blocks'][6]['description'] = _MI_UGMPAGE_COIN_SLIDER_BDESC;
$modversion['blocks'][6]['show_func'] = "ugm_page_coin_slider";
$modversion['blocks'][6]['template'] = "ugm_page_coin_slider.html";
$modversion['blocks'][6]['edit_func'] = "ugm_page_coin_slider_edit";
$modversion['blocks'][6]['options'] = "|0|565|290||date|on|on|corner";
# 圖片輪播
$modversion['blocks'][7]['file'] = "ugm_page_coin_slider_pic.php";
$modversion['blocks'][7]['name'] = _MI_UGMPAGE_COIN_SLIDER_PIC;
$modversion['blocks'][7]['description'] = _MI_UGMPAGE_COIN_SLIDER_PIC_BDESC;
$modversion['blocks'][7]['show_func'] = "ugm_page_coin_slider_pic";
$modversion['blocks'][7]['template'] = "ugm_page_coin_slider_pic.html";
$modversion['blocks'][7]['edit_func'] = "ugm_page_coin_slider_pic_edit";
$modversion['blocks'][7]['options'] = "|0|565|290||date|on|on|corner";
# 跑馬燈
$modversion['blocks'][8]['file'] = "ugm_page_marquee.php";
$modversion['blocks'][8]['name'] = _MI_UGMPAGE_MARQUEE;
$modversion['blocks'][8]['description'] = _MI_UGMPAGE_MARQUEE_BDESC;
$modversion['blocks'][8]['show_func'] = "ugm_page_marquee";
$modversion['blocks'][8]['template'] = "ugm_page_marquee.html";
$modversion['blocks'][8]['edit_func'] = "ugm_page_marquee_edit";
$modversion['blocks'][8]['options'] = "||corner|0|180|26|small|16|black|red|transparent";
# smoothmenu下拉選單
$modversion['blocks'][9]['file'] = "ugm_page_smoothmenu.php";
$modversion['blocks'][9]['name'] = _MI_UGMPAGE_SMOOTHMENU;
$modversion['blocks'][9]['description'] = _MI_UGMPAGE_SMOOTHMENU_BDESC;
$modversion['blocks'][9]['show_func'] = "ugm_page_smoothmenu";
$modversion['blocks'][9]['template'] = "ugm_page_smoothmenu.html";
$modversion['blocks'][9]['edit_func'] = "ugm_page_smoothmenu_edit";
$modversion['blocks'][9]['options'] = "||720";

#bootstrap accordion 伸縮選單
$modversion['blocks'][10]['file'] = "ugm_page_b_menu_t1_accordion.php";
$modversion['blocks'][10]['name'] = _MI_UGMPAGE_BNAME10;
$modversion['blocks'][10]['description'] = _MI_UGMPAGE_BDESC10;
$modversion['blocks'][10]['show_func'] = "ugm_page_b_menu_t1_accordion";
$modversion['blocks'][10]['template'] = "ugm_page_b_menu_t1_accordion.html";
$modversion['blocks'][10]['edit_func'] = "ugm_page_b_menu_t1_accordion_edit";
$modversion['blocks'][10]['options'] = "|";


//---樣板設定---//
$modversion['templates'] = array();
$modversion['templates'][1]['file'] = 'ugm_page_index_tpl.html';
$modversion['templates'][1]['description'] = _MI_UGMPAGE_TEMPLATE_DESC1;
$modversion['templates'][2]['file'] = 'ugm_page_cate_tpl.html';
$modversion['templates'][2]['description'] = _MI_UGMPAGE_TEMPLATE_DESC2;
$modversion['templates'][3]['file'] = 'ugm_page_post_tpl.html';
$modversion['templates'][3]['description'] = _MI_UGMPAGE_TEMPLATE_DESC3;
$modversion['templates'][4]['file'] = 'ugm_page_menu_tpl.html';
$modversion['templates'][4]['description'] = _MI_UGMPAGE_TEMPLATE_DESC4;
$modversion['templates'][5]['file'] = 'ugm_page_var_tpl.html';
$modversion['templates'][5]['description'] = _MI_UGMPAGE_TEMPLATE_DESC5;


//---評論---//
//$modversion['hasComments'] = 1;
//$modversion['comments']['pageName'] = '單一頁面.php';
//$modversion['comments']['itemName'] = '主編號';

//---通知---//
//$modversion['hasNotification'] = 1;



?>
