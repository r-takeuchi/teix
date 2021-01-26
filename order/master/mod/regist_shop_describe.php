<?php 
	session_start();
	require 'db.php';
	require 'func.php';
	require "../../vulnerability_check.php";
	$shop_id = $_POST["shop_id"];
	if($_POST["delete"]){
		$now = date("Y-m-d");
		$sql = "UPDATE `shop` SET `delete_date` = '$now' WHERE `shop_id` ='$shop_id' ";
		$res = _mysqlquery($sql);
		header("HTTP/1.1 301 Moved Permanentry");
		header("Location:" . "../top_page.php");
		exit;		
	}

	
	//postの定義
	$shop_name = $_POST["shop_name"];
	$shop_mail = $_POST["shop_mail"];
	//ファイルアップロード処理
    if(is_uploaded_file($_FILES['shop_pics']['tmp_name'])){
    	$extension_array = explode(".", $_FILES['shop_pics']['name']);
    	$extension = $extension_array[count($extension_array)-1];
    	$shop_pics = "shop_pics_".date("YmdHis").".".$extension;
    	//正方形にトリミングする
/*    	$im = imagecreatefrompng($_FILES['shop_pics']['tmp_name']);
		$size = min(imagesx($im), imagesy($im));
		$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
		if ($im2 !== FALSE) {
		    imagepng($im2, $_FILES['shop_pics']['tmp_name']);
		    imagedestroy($im2);
		}
		imagedestroy($im);*/
        if(move_uploaded_file($_FILES['shop_pics']['tmp_name'],"../../pics/".$shop_pics)){
            $shop_pics_set = ",`shop_pics`='$shop_pics'";
        }else{
            //コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
            echo "error while saving.";
        }
    }

    if($_POST["shop_id"]){
		$now = date("Y-m-d H:i:s");
		$sql = "UPDATE `shop` SET 
			`shop_name` = '$shop_name'
			,`shop_mail` = '$shop_mail'
			$shop_pics_set
		WHERE `shop_id` ='$shop_id' ";
		$res = _mysqlquery($sql);
    }else{
		$sql = "INSERT INTO `shop` SET 
			`shop_name` = '$shop_name'
			,`shop_mail` = '$shop_mail'
			$shop_pics_set ";
		$res = _mysqlquery($sql);
    }
	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "../top_page.php");
	exit;		    

 ?>