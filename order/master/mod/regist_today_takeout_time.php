<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";
	//sessionの定義
	$menu_id = $_POST["menu_id"];
	$today_takeout_times = $_POST["today_takeout_times"];
    $now = date("Y-m-d H:i:s");
	
	if($menu_id){
    	$sql = "DELETE FROM `today_takeout_time` WHERE `today_takeout_menu_id`=$menu_id";
		$res = _mysqlquery($sql);
    	$sql = "INSERT INTO `today_takeout_time`(`today_takeout_menu_id`, `today_takeout_date`, `today_takeout_times`) VALUES ($menu_id,'$now',$today_takeout_times)";
		$res = _mysqlquery($sql);
		
	}

	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../top_page.php?page=0");
	exit;		    

 ?>