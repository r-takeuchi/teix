<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";

	session_destroy();
	//logout処理
	if (isset($_COOKIE["PHPSESSID"])) {
	    setcookie("PHPSESSID", '', time() - 1800, '/');
	}	
	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../login.php");
	exit;
 ?>