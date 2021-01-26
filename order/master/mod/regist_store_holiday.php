<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";
	$now = date("Y-m-d H:i:s");

	//sessionの定義
	$shop_id = $_SESSION["shop_id"];

	if($_POST["form_type"]=="store_week_time"){
		foreach ($_POST["business_hours_from"] as $week_no => $business_hours_from) {
			$business_hours_to = $_POST["business_hours_to"][$week_no];
			$takeout_times = $_POST["takeout_times"][$week_no];
			$insert .= "('$shop_id', '$week_no', '$business_hours_from', '$business_hours_to', '$takeout_times'),";
		}
		if($insert){
			$sql = "DELETE FROM `store_week_time` WHERE `shop_id`=$shop_id";
			$res = _mysqlquery($sql);			
			$sql = "INSERT INTO `store_week_time`(`shop_id`, `week_no`, `business_hours_from`, `business_hours_to`, `takeout_times`) VALUES ".rtrim($insert,",");
			$res = _mysqlquery($sql);
		}
	}else{
		$store_holiday = array_diff($_POST["month_calendar"], $_POST["business_day"]);
		foreach ($store_holiday as $key => $value) {
			$insert .= "( $shop_id, '$value', '$now'),";
		}

		if($insert){
			//前回の情報を削除
			$sql = "DELETE FROM `store_holiday` WHERE `shop_id`=$shop_id AND `date` LIKE '".$_POST["month"]."%'";
			$res = _mysqlquery($sql);
			$sql = "INSERT INTO `store_holiday`( `shop_id`, `date`, `created`) VALUES ".rtrim($insert,",");
			$res = _mysqlquery($sql);
		}

	}


    
   

	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../top_page.php?page=2&month=".$_POST["month"]);
	exit;

 ?>