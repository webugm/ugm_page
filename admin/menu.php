<?php

$adminmenu = array();
$icon_dir=substr(XOOPS_VERSION,6,3)=='2.6'?"":"images/";
$i = 1;
$adminmenu[$i]['title'] = _MI_UGMPAGE_HOME ;
$adminmenu[$i]['link'] = 'admin/index.php' ;
$adminmenu[$i]['desc'] = _MI_UGMPAGE_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/home.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU1;
$adminmenu[$i]['link'] = "admin/groupperm.php";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU1_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/index1.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU2;
$adminmenu[$i]['link'] = "cate.php";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU2_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/cate.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU3;
$adminmenu[$i]['link'] = "post.php";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU3_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/post.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU4;
$adminmenu[$i]['link'] = "post.php?op=ugm_page_main_form";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU4_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/post_form.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU5;
$adminmenu[$i]['link'] = "menu.php?menu_type=1";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU5_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/menu_1.png' ;


$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU6;
$adminmenu[$i]['link'] = "menu.php?menu_type=2";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU6_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/menu_2.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU7;
$adminmenu[$i]['link'] = "menu.php?menu_type=3";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU7_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/menu_3.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU8;
$adminmenu[$i]['link'] = "menu.php?menu_type=4";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU8_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/menu_4.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMPAGE_ADMENU9;
$adminmenu[$i]['link'] = "var.php";
$adminmenu[$i]['desc'] = _MI_UGMPAGE_ADMENU9_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/var.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] = 'images/admin/about.png';

?>
