<?php 
//**********************SQL実行関数**********************
function _mysqlquery($sql){
	global $mysqli;
	if($result = $mysqli->query($sql)){
		return $result;
	}else{
		echo $sql;
		echo $mysqli->error;
		die;
	}
}

function _mysqlfetcharray($result){
	$col = $result->fetch_array();
	return $col;
}

function _mysqlnumrows($result){
	return $result->num_rows;
}

//**********************SQL実行関数**********************

//ログインのチェック
function _getlogin($login_id,$login_password){
	$sql = "SELECT * FROM `shop` WHERE `delete_date` IS NULL
			AND `login_id`='$login_id'
			AND `login_password`='$login_password'";
	$res = _mysqlquery($sql);
	$row = _mysqlnumrows($res);
	if($row>0){
		$col = _mysqlfetcharray($res);
		$_SESSION["texi_shop_login"] = 1;
		$_SESSION["shop_id"] = $col["shop_id"];
		$_SESSION["shop_name"] = $col["shop_name"];
		return 1;
	}else{
		return 2;
	}
}

//商品一覧を取得する
function _getMenuList($shop_id){
	$sql = "SELECT * FROM `menu` 
			LEFT JOIN (SELECT * FROM `stop_reservation` WHERE date(`stop_menu_date`)=date(NOW())) AS `stop_reservation`
			ON `stop_menu_id` = `menu_id`
			WHERE `shop_id`='$shop_id' 
			ORDER BY `menu_order`";
	$res = _mysqlquery($sql);
	$list = array();
	while($col = $res->fetch_array()){
		array_push($list, $col);
	}
	return $list;
}

function _HtmlSelectBusinessHours($type,$hour = '08:00'){
	for ($h=0; $h <24 ; $h++) { 
		$h2 = sprintf('%02d',$h);
		for ($m=0; $m <60 ; $m = $m+15) { 
			$m2 = sprintf('%02d',$m);
			$time = $h2.":".$m2;
			$html2 .= '<option value="'.$time.'" '.($hour==$time?"selected='selected'":"").'>'.$time.'</option>';
		}
	}
	$html = "<select name='business_hours_".$type."'>".$html2."</select>";
	return $html;
}

function _HtmlSelectTakeoutTimes($type,$time = 0){
	$html2 .= '<option value="0" '.($time==0?"selected='selected'":"").'>即時</option>';
	for ($m=10; $m <=60 ; $m = $m+10) { 
		$html2 .= '<option value="'.$m.'" '.($m==$time?"selected='selected'":"").'>'.$m.'分後</option>';
	}
	$html = "<select name='takeout_times".$type."'>".$html2."</select>";
	return $html;
}

function _HtmlSelectTakeoutTimesToday($time = 0){
	$html2 .= '<option value="0" '.($time==0?"selected='selected'":"").'>即時</option>';
	for ($m=10; $m <=60 ; $m = $m+10) { 
		$html2 .= '<option value="'.$m.'" '.($m==$time?"selected='selected'":"").'>'.$m.'分後</option>';
	}
	$html = "<select name='today_takeout_times'>".$html2."</select>";
	return $html;
}

function _getTakeOutTime($menu_id){
	$week_no = date("w");
	$shop_id = $_SESSION["shop_id"];
	$sql = "SELECT * FROM `store_week_time` 
			LEFT JOIN `today_takeout_time`
			ON `today_takeout_menu_id` = $menu_id
			AND `today_takeout_date`=date(NOW())
			WHERE `shop_id`='$shop_id' AND `week_no`=$week_no ";
	$res = _mysqlquery($sql);
	$col = _mysqlfetcharray($res);
	if($col["today_takeout_times"] || $col["today_takeout_times"]==="0"){
		return $col["today_takeout_times"];
	}else{
		return $col["takeout_times"];
		
	}
}

//メニュー情報を取得する
function _getMenuInfo($menu_id){
	$sql = "SELECT * FROM `menu` 
			WHERE `menu_id` = $menu_id";
	$res = _mysqlquery($sql);
	$col = $res->fetch_array();
	return $col;
}

 ?>