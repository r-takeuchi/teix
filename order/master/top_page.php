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
	
	$page = $_GET["page"]?$_GET["page"]:0;
	$shop_id = $_SESSION["shop_id"];
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="css/basic.css">
	<title></title>
	<link rel="stylesheet" href="">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://kit.fontawesome.com/639d74e288.js" crossorigin="anonymous"></script>
	<script>
		$(function(){
			$(".confirm").click(function(event) {
				if(confirm("店舗を終了しますか？")){
					$(this).closest('form').submit();
				}
			});
		});
	</script>
</head>
<body>
<?php require "header.php"; ?>
	<div class="content">
		<h1><?php echo date("Y年n月j日")." ".$week_array[date("w")]."曜日" ?></h1>
		<h2><?php echo $page_array[$page]; ?></h2>
		<div class="page1">
			<?php 
				$shop_list = _getShopList();
				if(count($shop_list)>0):
			 ?>
			<form action="mod/regist_menu_order.php" method="post" id="menu_order">
				<?php _getToken(); ?>
			</form>
				<table class="main_table">
					<tr>
						<th>No</th>
						<th>店舗名</th>
						<th>編集</th>
					</tr>
					<?php $no = 1;foreach ($shop_list as $key => $value) { ?>
						<tr>
							<td class="Num">
								<div class="Number">
									<?php echo $no++; ?>
								</div>
							</td>
							<td align="center">
								<img src="../pics/<?php echo $value["shop_pics"] ?>" alt="">
								<?php echo $value["shop_name"] ?>
							</td>
							<td align="center">
								<form action="mod/regist_shop_describe.php" method="post">
									<?php _getToken(); ?>
									<input type="hidden" name="shop_id" value="<?php echo $value["shop_id"] ?>">
									<input type="hidden" name="delete" value="1">
									<button type="button" class="confirm">終了</button>
								</form>
							</td>
						</tr>
					<?php } ?>
				</table>
			<?php endif; ?>
		<div>
			<form action="shop_describe.php" method="post">
				<?php _getToken(); ?>
				<button>店舗追加</button>
			</form>
		</div>			
		</div>

	</div>
</body>
</html>