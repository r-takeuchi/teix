<?php 
	session_start();
	require "mod/db.php";
	require "mod/func.php";
	require "mod/config.php";
	if(!$_SESSION["texi_shop_login"]){
		header("HTTP/1.1 301 Moved Permanentry");
		header("Location:" . "login.php");
		exit;		
	}
	//商品情報
	$id = $_GET["id"]?$_GET["id"]:$_POST["id"];
	$shop_id = $_SESSION["shop_id"];
	$sql = "SELECT * FROM `menu` WHERE `shop_id`='$shop_id' AND `menu_id`='$id'";
	$res = _mysqlquery($sql);
	$col = _mysqlfetcharray($res);
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="css/basic.css">
	<script>
	function previewImage(obj)
	{
		var fileReader = new FileReader();
		fileReader.onload = (function() {
			document.getElementById('preview').src = fileReader.result;
		});
		fileReader.readAsDataURL(obj.files[0]);
	}
	</script>	
</head>
<body>
<?php require "header.php"; ?>
<?php require "menu.php"; ?>	
	<div class="content">	
		<h1>メニュー編集</h1>
		<form action="mod/regist_menu_describe.php" method="post" enctype="multipart/form-data">
			<?php if($id): ?><input type="hidden" name="menu_id" value="<?php echo $id ?>"><?php endif; ?>
			<div>
				<?php if($col["menu_pict"]): ?>
				<div><img src="../pics/<?php echo $col["menu_pict"] ?>" alt="" width="200px" ></div>
				<?php endif; ?>
				<div id="preview_area"><img src="" alt="" id="preview" width="200px"></div>
				<label for="">商品写真添付</label>
				<input type="file" name="menu_pict" accept='image/*' onchange="previewImage(this);"  >
			</div>
			<div>
				<label for="">商品名　入力</label>
				<input type="text" maxlength="100" name="menu_name" value="<?php echo $col["menu_name"] ?>">
			</div>
			<div>
				<label for="">値段　入力</label>
				<input type="number" name="menu_price" value="<?php echo $col["menu_price"] ?>">
			</div>
			<div>
				<label for="">紹介文 入力</label>
				<textarea name="menu_text" id="" cols="30" rows="10"><?php echo $col["menu_text"] ?></textarea>
			</div>
			<div>
				<button>保存</button>
			</div>
		</form>
	</div>
</body>
</html>