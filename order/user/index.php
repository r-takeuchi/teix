<?php 
session_start();
require "mod/db.php";
require "mod/func.php";
require "mod/config.php";
require "../vulnerability_check.php";
$page = $_GET["page"]?$_GET["page"]:0;
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>店舗選択</title>
	<script src="https://kit.fontawesome.com/639d74e288.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script type="text/javascript" src="js/thickbox/thickbox.js"></script>
	<link rel="stylesheet" href="js/thickbox/thickbox.css">
	<link ref="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
</head>
	<style>
		.shop_icon {
			width: 100%;
		}
		body {
			font-family: 'Noto Sans JP', sans-serif;
		}
	</style>
<body>
<?php require "header.php"; ?>
<div class="container">
<?php 
if($_POST["confirm"]){
	$shop_id = $_POST["shop_id"];
	$sql = "SELECT * FROM `shop` WHERE `shop_id`=$shop_id";
	$res = _mysqlquery($sql);
	$col = _mysqlfetcharray($res);	
 ?>
<form action="mod/regist_order_reservation.php" class="needs-validation" method="post">
	<?php _getToken(); ?>
	<input type="hidden" name="shop_id" value="<?php echo $shop_id ?>">
	<input type="hidden" name="shop_name" value="<?php echo $col["shop_name"] ?>">
 <p><?php echo $col["shop_name"] ?></p>
	 <table class="table table-sm">
	 	<tr>
	 		<th class="text-center">商品名</th>
	 		<th class="text-center">数量</th>
	 		<th class="text-center">金額</th>
	 	</tr>
	 	<?php 
		foreach ($_POST["count"] as $menu_id => $count) {
			if($count==0)continue;
	 		$info = _getMenuInfo($menu_id); 
	 		$take_out_time[] = _getTakeOutTime($menu_id,$shop_id);
	 	?>
	 	<tr>
	 		<td>
	 			<?php echo $info["menu_name"] ?>
	 			<input type="hidden" name="menu_count[<?php echo $menu_id ?>]" value="<?php echo $count ?>">
	 		</td>
	 		<td class="text-right"><?php echo $count ?></td>
	 		<td class="text-right"><?php echo number_format($count*$info["menu_price"]); $sum += $count*$info["menu_price"]; ?></td>
	 	</tr>
	 	<?php }
	 		$take_out_time = max($take_out_time);
	 	 ?>
	 	<tr>
	 		<td colspan="2">合計</td>
	 		<td class="text-right"><?php echo number_format($sum) ?></td>
	 	</tr>
	 </table>
 	<input type="hidden" name="take_out_time" value="<?php echo $take_out_time ?>">
	 	<div class="mt-3">
			<label for="">受取希望時間</label>
			<div class="input-group form-group">
				<?php echo _HtmlSelectTakeoutTimesToday($take_out_time); ?>
			</div>
	 	</div>
	 	<div class="mt-3">
			<label for="">携帯番号入力</label>
			<div class="input-group">
				<input type="text" name="phone_number" class="form-control" required>
			</div>
			<small id="emailHelp" class="form-text text-muted">ハイフン抜きで入力してください</small>
	 	</div>
	 	<div class="mt-3 message">
	 	</div>
	 		<script>
	 			$(function(){
	 				$("#checktime").click(function(event) {
		 				var date1 = new Date();
		 				var today_takeout_times = $("select[name='today_takeout_times']").val();
		 				var addtime = date1.setMinutes(date1.getMinutes() + parseInt(today_takeout_times));
		 				var diff = <?php echo strtotime(date("Y-m-d ")._getBusinessHoursTo($shop_id))*1000 ?>-addtime;
		 				if(diff>0){
		 					$(this).closest('form').submit();
		 				}else{
		 					$(".message").text('受付時間超過:ご指定の時間ではご予約できません<br />本日の営業時間　<?php echo _getBusinessHoursFrom($shop_id) ?>～<?php echo _getBusinessHoursTo($shop_id) ?>');
		 				}
	 				});
	 			});
	 		</script>
	 		
	  <div class="text-center mt-5">
	    <button class="btn btn-primary" type="button" id="checktime">予約送信</button>
	  </div>		
	</form>
<?php 
}elseif($_GET["order_no"]){
	$order_no = $_GET["order_no"];
	$sql = "SELECT * FROM `order_reservation` WHERE `order_no`=$order_no";
	$res = _mysqlquery($sql);

	$sql2 = "SELECT * FROM `order_reservation_no` WHERE `order_reservation_no`=$order_no";
	$res2 = _mysqlquery($sql2);	
	$col2 = _mysqlfetcharray($res2);
 ?>
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
	 	<tr>
	 		<td colspan="2">合計</td>
	 		<td class="text-right"><?php echo number_format($sum) ?></td>
	 	</tr>
	 </table>
<?php 
}elseif($_GET["shop_id"]){
	$shop_id = $_GET["shop_id"];
	$shop_menu_list = _getMenuList($shop_id);
	$sql = "SELECT * FROM `shop` WHERE `shop_id`=$shop_id";
	$res = _mysqlquery($sql);
	$col = _mysqlfetcharray($res);
	 ?>
	 <h1 class="text-center">
	 	<img src="../pics/<?php echo $col["shop_pics"] ?>" alt="" width="36px">
	 	menu
	 </h1>
	 <script>
	 	$(function(){
	 		$(".countbtn").click(function(event) {
	 			var $target_span = $(this).closest('td').find("span.number"),
	 				$target_input = $(this).closest('td').find("input[name^='count']"),
	 				count = parseInt($target_input.val());
	 			if($(this).hasClass('countup')){
	 				$target_span.text(count+1);
	 				$target_input.val(count+1);
	 			}else if(count>0 && $(this).hasClass('countdown')){
	 				$target_span.text(count-1);
	 				$target_input.val(count-1);	 				
	 			}
	 		});
	 	});
	 </script>
	 <form action="" method="post">
		<?php _getToken(); ?>
	 	<input type="hidden" name="confirm" value="1">
	 	<input type="hidden" name="shop_id" value="<?php echo $shop_id ?>">
	 	<input type="hidden" name="shop_name" value="<?php echo $col["shop_name"] ?>">
		<table class="table table-sm">
			<?php $no = 1;foreach ($shop_menu_list as $key => $value) { ?>
				<tr>
					<td class="align-middle">
						<a href="menu_describe.php?id=<?php echo $value["menu_id"] ?>&KeepThis=true&TB_iframe=true&height=400&width=300" class="thickbox">
							<img src="../pics/<?php echo $value["menu_pict"] ?>" alt="" width="60px">
						</a>
					</td>
					<td class="align-middle">
						<?php echo number_format($value["menu_price"]); ?>円
					</td>
					<td class="align-middle">
						<button class="btn btn-link countbtn countdown" type="button"><i class="fas fa-minus-square"></i></button>
						<span class="number">0</span>
						<input type="hidden" name="count[<?php echo $value["menu_id"] ?>]" value="0">
						<button class="btn btn-link countbtn countup" type="button"><i class="fas fa-plus-square"></i></button>
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="mt-5">
			<button class="btn btn-primary btn-lg btn-block">確認</button>
		</div>
	 </form>
	 
<?php }else{ ?>
		<div class="">
		  <div class="row">
			<?php $shoplist = _getShopList();
			foreach ($shoplist as $shopinfo) :?>
			<div class="col-6 col-lg-3">
				<a href="index.php?shop_id=<?php echo $shopinfo["shop_id"] ?>">
					<img src="../pics/<?php echo $shopinfo["shop_pics"] ?>" class="shop_icon" >
				</a>
			</div>		
			<?php endforeach;
			 ?>
		  </div>
		</div>	
	<?php } ?>
	</div>	
</body>
</html>