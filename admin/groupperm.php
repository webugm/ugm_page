<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once "../function.php";
//引入權限設定語系(x軸)
include_once XOOPS_ROOT_PATH."/modules/ugm_tools2/language/{$xoopsConfig['language']}/groupperm.php";
//群組選擇使用
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
#引入權限檔
include_once XOOPS_ROOT_PATH . "/modules/ugm_page/groupperm.php";

# ---- 模組名稱 ----
$DIRNAME=$xoopsModule->getVar('dirname');
# ---- 程式名稱(含副檔名) ----
$file_name = basename(__FILE__);
//取得本模組編號
$gperm_modid = $xoopsModule->getVar('mid');

/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?'':$_REQUEST['op'];

if($op=="op_insert"){
	op_insert();
	redirect_header($_SERVER['PHP_SELF'],3,_MA_PERM_SUCCESS);
}

$main="
<!-- groupperm begin -->

<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/tadtools/bootstrap3/css/bootstrap.css'>
<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ugm_tools2/css/xoops_adm3.css'>
<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ugm_tools2/css/module_b3.css'>

<div class='CPbigTitle' style='background-image: url(".XOOPS_URL."/modules/ugm_tools2/images/admin/button.png); background-repeat: no-repeat; background-position: left; padding-left: 50px;''><strong>"._MA_GROUPPERM_TITLE."</strong>
</div>
<br />

<style>
	#form_table th{text-align: center;}
	#form_table td{padding:20px 10px;}
</style>

<div id='save_msg'></div>

<div>
	<a class='tooltip' href='".XOOPS_URL."/modules/system/admin.php?fct=groups' title='"._MA_GROUPPERM_GROUP."'>
		<img src='".XOOPS_URL."/modules/system/themes/default/icons/groups.png' alt='' title=''>
	</a>
	<a class='tooltip' href='".XOOPS_URL."/modules/system/admin.php?fct=users' title='"._MEMBERS."'>
		<img src='".XOOPS_URL."/modules/system/themes/default/icons/edituser.png' alt='' title=''>
	</a>
</div>

<form action='{$file_name}' method='post' id='myForm' class='form-inline'>
	<table id='form_table' class='table table-bordered table-hover table-condensed'>
		<!--標題-->
		<thead>
			<tr>
				<th></th>";


foreach($gperm_itemid_arr as $x){
	$main.="
		<th>{$x['title']}</th>";
}

$main.="
			</tr>
		</thead>";
foreach($gperm_name_arr as $gperm_name=>$gperm_name_title){
	$main.="
			<tr>
				<td>{$gperm_name_title}</td>";

	foreach($gperm_itemid_arr as $gperm_itemid=>$x){
		#預設值(陣列)get_gpermDB_arr($modules_id,$gperm_name,$gperm_itemid)
		$gpermDB_arr=get_gpermDB_arr($gperm_modid,$gperm_name,$gperm_itemid);
		//
		# ---- XoopsFormSelectGroup('標題', 'form_name',訪客權限, '預設值(陣列)', 高度 ,多選)
		$SelectGroup_name = new XoopsFormSelectGroup("", "gperm[{$gperm_name}][{$gperm_itemid}]", false,$gpermDB_arr, 4, true);
		if($x['anonymous']){
			$SelectGroup_name->addOption(0, _MA_GROUPPERM_ALL, false);
		}
		$SelectGroup_name->setExtra("class='col-md-12'");
		$main.="<td>".$SelectGroup_name->render()."</td>";
	}
	$main.="</tr>";
}

$main.="
		<tfoot>
			<tr>
			<td colspan=6 class='text-center'>
			<input type='hidden' name='op' value='op_insert'>
			<input type='hidden' name='gperm_modid' value='{$gperm_modid}'>
";

foreach($gperm_name_arr as $gperm_name=>$v){
	$main.="
	<input type='hidden' name='gperm_name[]' value='{$gperm_name}'>";
}

$main.="
					<button type='submit' class='btn btn-primary'>"._SUBMIT."</button>
				</td>
			</tr>
		</tfoot>
	</table>
</form>

<!-- groupperm end -->
";

echo $main;
include_once "footer.php";
/*-----------秀出結果區--------------*/

#insert
function op_insert(){
	global $xoopsDB;
	//---- 過濾資料 -----------------------------------------
	$myts =& MyTextSanitizer::getInstance();
	$gperm_modid=intval($_POST['gperm_modid']);//模組編號

	//---- 刪除設定權限資料 $gperm_modid
	foreach ($_POST['gperm_name'] as $gperm_name){
		//權限名稱
		$gperm_name=$myts->addSlashes($gperm_name);
		$sql = "delete from ".$xoopsDB->prefix("group_permission")."
		where gperm_modid='{$gperm_modid}' and gperm_name='{$gperm_name}' ";//die($sql);
		$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
	}

	//---- 寫入選擇的權限設定
	//gperm[ugmmrp_customer][1][]
	foreach ($_POST['gperm'] as $gperm_name => $gperm_itemid_arr){
		$gperm_name=$myts->addSlashes($gperm_name);//權限名稱
		foreach ($gperm_itemid_arr as $gperm_itemid => $gperm_groupid_arr){
			$gperm_itemid=intval($gperm_itemid);//權限項目編號
			foreach ($gperm_groupid_arr as $gperm_groupid){
				$gperm_groupid=intval($gperm_groupid);//群組編號

				#---------寫入-------------------------
				#gperm_id gperm_groupid gperm_itemid gperm_modid gperm_name
				$sql = "insert into 
							 ".$xoopsDB->prefix("group_permission")."
							 (`gperm_groupid` ,`gperm_itemid` , `gperm_modid` , `gperm_name`)
							 values
							 ('{$gperm_groupid}' , '{$gperm_itemid}' ,'{$gperm_modid}' , '{$gperm_name}' )";
				$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, web_error());
			}
		}
	}
	return;
}

#取得資料表中的設定("模組 id",$gperm_name,$gperm_itemid)
#返回陣列 [0]=>群組id [1]=>=>群組id
function get_gpermDB_arr($gperm_modid,$gperm_name,$gperm_itemid){
	global $xoopsDB;

	$sql="select gperm_groupid
		from ".$xoopsDB->prefix("group_permission")."
		where gperm_modid='{$gperm_modid}' and gperm_name='{$gperm_name}' and gperm_itemid='{$gperm_itemid}'
		order by gperm_groupid
	";//die($sql);
	$result=$xoopsDB->query($sql);
	$gperm_groupids="";
	while(list($gperm_groupid)=$xoopsDB->fetchRow($result)){
	$gperm_groupids[]=$gperm_groupid;
	}
	return $gperm_groupids;
}