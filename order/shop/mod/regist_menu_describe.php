<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";

	//sessionの定義
	$shop_id = $_SESSION["shop_id"];
	
	//postの定義
	$menu_name = $_POST["menu_name"];
	$menu_price = $_POST["menu_price"];
	$menu_text = $_POST["menu_text"];
	$id = $_POST["menu_id"];
	//ファイルアップロード処理
    if(is_uploaded_file($_FILES['menu_pict']['tmp_name'])){
    	$extension_array = explode(".", $_FILES['menu_pict']['name']);
    	$extension = $extension_array[count($extension_array)-1];
    	$menu_pict = "menu_pict_".date("YmdHis").".".$extension;
        if(move_uploaded_file($_FILES['menu_pict']['tmp_name'],"../../pics/".$menu_pict)){
            $menu_pict_set = ",`menu_pict`='$menu_pict'";
        }else{
            //コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
            echo "error while saving.";
        }
    }

    $now = date("Y-m-d H:i:s");
    if($id){
	    $sql = "UPDATE `menu` SET 
			 `modified` = '$now'
			,`menu_name` = '$menu_name'
			,`menu_price` = '$menu_price'
			,`menu_text` = '$menu_text'
			$menu_pict_set
			WHERE `menu_id` ='$id'
		    ";
		$res = _mysqlquery($sql);
    }else{
	    $sql = "INSERT INTO `menu` SET 
		    `created` = '$now'
			,`modified` = '$now'
			,`shop_id` = '$shop_id'
			,`menu_name` = '$menu_name'
			,`menu_price` = '$menu_price'
			,`menu_text` = '$menu_text'
			$menu_pict_set
		    ";
		$res = _mysqlquery($sql);
		$id = mysqli_insert_id();
    	
    }
	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../menu_describe.php?id=".$id);
	exit;		    

 ?>