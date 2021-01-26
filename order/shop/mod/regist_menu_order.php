<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";

	//sessionの定義
	$shop_id = $_SESSION["shop_id"];
	

    $now = date("Y-m-d H:i:s");
	foreach ($_POST["menu_id"] as $menu_order => $menu_id) {
	    $sql = "UPDATE `menu` SET 
			 `modified` = '$now'
			,`menu_order` = '$menu_order'
			,`shop_id` = '$shop_id'
			WHERE `menu_id` ='$menu_id'
		    ";
		$res = _mysqlquery($sql);
	}
	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../top_page.php?page=1");
	exit;		    

 ?>