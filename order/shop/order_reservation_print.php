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
	
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://kit.fontawesome.com/639d74e288.js" crossorigin="anonymous"></script>
	<style>
		.content {
			width: 300px;
			margin: 10px auto;
		}
		.table-sm {
			width: 100%;
		}
		.text-right {
			text-align: right;
		}
		.table-sm td {
			border-top: 1px dotted;
		}
	</style>
</head>
<?php 
	$order_no = $_GET["order_no"];
	$sql = "SELECT * FROM `order_reservation` WHERE `order_no`=$order_no";
	$res = _mysqlquery($sql);

	$sql2 = "SELECT * FROM `order_reservation_no` WHERE `order_reservation_no`=$order_no";
	$res2 = _mysqlquery($sql2);	
	$col2 = _mysqlfetcharray($res2);
 ?>
<body>
	<div class="content">
		 <div class="text-center">
			 <h2><?php echo $col2["shop_name"] ?></h2>
			 <p>受付番号<?php echo $col2["order_reservation_no"] ?></p>
			 <p><?php echo $col2["phone_number"] ?></p>
			 <label>受取予定時間</label>
			 <h1><?php echo date("H:i",strtotime($col2["take_out_time"])) ?></h1>
			 <p style="color:red">※この画面をスクリーンショット等で必ず保存して下さい</p>
		 </div>
			 <table class="table table-sm">
			 	<tr>
			 		<th class="text-center">商品名</th>
			 		<th class="text-center">数量</th>
			 		<th class="text-center">金額</th>
			 	</tr>
			 	<?php 
			 	while ($col = _mysqlfetcharray($res)) {
			 		$menu_id = $col["menu_id"];
			 		$count = $col["count"];
			 		$info = _getMenuInfo($menu_id); 
			 		$take_out_time[] = _getTakeOutTime($menu_id,$shop_id);
			 	?>
			 	<tr>
			 		<td>
			 			<?php echo $info["menu_name"] ?>
			 		</td>
			 		<td class="text-right"><?php echo $count ?></td>
			 		<td class="text-right"><?php echo number_format($count*$info["menu_price"]); $sum += $count*$info["menu_price"]; ?></td>
			 	</tr>
			 	<?php }
			 		$take_out_time = max($take_out_time);
			 	 ?>
			 	<tr class="sum_row">
			 		<td colspan="2">合計</td>
			 		<td class="text-right"><?php echo number_format($sum) ?></td>
			 	</tr>
			 </table>
		
	</div>
	
</body>
</html>