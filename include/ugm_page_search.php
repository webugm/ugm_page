<?php
  //ugm_自訂頁面搜尋程式
function ugm_page_search($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB;
	//處理許功蓋
	if(get_magic_quotes_gpc()){
		foreach($queryarray as $k=>$v){
			$arr[$k]=addslashes($v);
		}
		$queryarray=$arr;
	}
	$sql = "SELECT `msn`,`title`,`date`, `uid` FROM ".$xoopsDB->prefix("ugm_page_main")." WHERE enable='1'";
	if ( $userid != 0 ) {
		$sql .= " AND uid=".$userid." ";
	}
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((`title` LIKE '%{$queryarray[0]}%'  OR `content` LIKE '%{$queryarray[0]}%' OR  `uid` LIKE '%{$queryarray[0]}%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(`title` LIKE '%{$queryarray[$i]}%' OR  `content` LIKE '%{$queryarray[$i]}%'  OR  `uid` LIKE '%{$queryarray[$i]}%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY  `date` DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;
 	while($myrow = $xoopsDB->fetchArray($result)){
		$ret[$i]['image'] = "images/application_home.png";
		$ret[$i]['link'] = "index.php?msn=".$myrow['msn'];
		$ret[$i]['title'] = $myrow['title'];
		$ret[$i]['time'] = strtotime($myrow['date']);
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
	}
	return $ret;
}
?>