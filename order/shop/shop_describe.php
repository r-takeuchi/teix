<?php 
session_start();
require "mod/db.php";
require "mod/func.php";
require "mod/config.php";
require "../vulnerability_check.php";
if(!$_SESSION["texi_shop_login"]){
	header("HTTP/1.1 301 Moved Permanentry");
	header("Location:" . "login.php");
	exit;		
}
$shop_id = $_SESSION["shop_id"];
$sql = "SELECT * FROM `shop` WHERE `shop_id`=$shop_id";
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
		<h1>店舗情報編集</h1>
		<form action="mod/regist_shop_describe.php" method="post" enctype="multipart/form-data">
			<?php _getToken(); ?>
			<div>
				<label for="">店舗名</label>
				<input type="text" name="shop_name" value="<?php echo $col["shop_name"] ?>" size="40">
			</div>
			<div>
				<?php if($col["shop_pics"]): ?>
				<div><img src="../pics/<?php echo $col["shop_pics"] ?>" alt="" width="200px" ></div>
				<?php endif; ?>
				<div id="preview_area"><img src="" alt="" id="preview" width="200px"></div>
				<label for="">店舗画像添付</label>
				<input type="file" name="shop_pics" accept='image/*' onchange="previewImage(this);"  >
				<div>
				掲載できる画像は1:1のサイズです<br>
				</div>
			</div>
			<div>
				<label for="">メールアドレス</label>
				<input type="text" name="shop_mail" value="<?php echo $col["shop_mail"] ?>" size="40">
			</div>			
			<div>
				<button>保存</button>
			</div>
		</form>
	</div>
</body>
</html>