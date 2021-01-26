<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";
	//ログインチェック
	$logincheck = _getlogin($_POST["login"],$_POST["password"]);
	if($_POST["login"] && $_POST["password"] && $logincheck==1){ //OK
		header("HTTP/1.1 301 Moved Permanentry");
		header("Location:" . "../top_page.php");
		exit;
	}else{ //NG
		$_SESSION["login_error"] = 1;
		header("HTTP/1.1 301 Moved Permanentry");
		header("Location:" . "../login.php");
		exit;		
	}
 ?>