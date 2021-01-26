<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";
	$menu_count = $_POST["menu_count"];
	$today_takeout_times = $_POST["today_takeout_times"];
	$phone_number = $_POST["phone_number"];
	$shop_id = $_POST["shop_id"];
	$shop_name = $_POST["shop_name"];
	$take_out_time = date("Y-m-d H:i:s",strtotime("+".$today_takeout_times." minute"));
    $now = date("Y-m-d H:i:s");

	$sql = "INSERT INTO `order_reservation_no`(`created`,`shop_id`,`shop_name`,`phone_number`,`take_out_time`) VALUES ('$now','$shop_id','$shop_name','$phone_number','$take_out_time')";
	$res = _mysqlquery($sql);
	$order_no = mysqli_insert_id($mysqli);

	$sql = "INSERT INTO `order_reservation`(`order_no`,`shop_id`,`menu_id`, `count`, `take_out_time`, `phone_number`, `created`) VALUES ";	

	foreach ($menu_count as $menu_id => $count) {
	    $values .= "('$order_no','$shop_id','$menu_id', '$count', '$take_out_time', '$phone_number', '$now'),";
	}
	$res = _mysqlquery($sql.rtrim($values,","));

	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../index.php?order_no=".$order_no);
	exit;		    

 ?>