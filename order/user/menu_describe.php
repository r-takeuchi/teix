<?php 
	session_start();
	require "mod/db.php";
	require "mod/func.php";
	require "mod/config.php";
	require "../vulnerability_check.php";

	//商品情報
	$id = $_GET["id"]?$_GET["id"]:$_POST["id"];
	$sql = "SELECT * FROM `menu` WHERE 1 AND `menu_id`='$id'";
	$res = _mysqlquery($sql);
	$col = _mysqlfetcharray($res);
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="css/basic.css">
	<title>店舗選択</title>
	<script src="https://kit.fontawesome.com/639d74e288.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script type="text/javascript" src="js/thickbox/thickbox.js"></script>	
	<link rel="stylesheet" href="js/thickbox/thickbox.css">
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
	<div class="container">	
		<div class="row">
			<div class="col text-center">
				<img src="../pics/<?php echo $col["menu_pict"] ?>" alt="" class="w-75">
			</div>
		</div>
		<div class="row border-bottom">
			<div class="col">
				<div>
					<h4><?php echo $col["menu_name"] ?></h4>
				</div>
				<div>
					<h1 class="card-title pricing-card-title"><?php echo $col["menu_price"] ?>円</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<p><?php echo $col["menu_text"] ?></p>
			</div>
		</div>
	</div>
</body>
</html>