<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";

	//sessionの定義
	$menu_id = $_POST["menu_id"];
	$stop_reservation_id = $_POST["stop_reservation_id"];
    $now = date("Y-m-d H:i:s");
	
	if($stop_reservation_id){
    	$sql = "DELETE FROM `stop_reservation` WHERE `stop_reservation_id`=$stop_reservation_id";
		$res = _mysqlquery($sql);
	}elseif($menu_id){
    	$sql = "INSERT INTO `stop_reservation`(`stop_menu_id`, `stop_menu_date`) VALUES ($menu_id,'$now')";
		$res = _mysqlquery($sql);
		
	}

	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../top_page.php?page=0");
	exit;		    

 ?>