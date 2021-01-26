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
			$(".upbtn").first().prop('disabled', true);
			$(".downbtn").last().prop('disabled', true);
			$(".updownbtn").click(function(event) {
				$(".updownbtn").prop('disabled', false);
				if($(this).hasClass('upbtn')){
					$(this).closest('tr').insertBefore($(this).closest('tr').prev("tr"));
				}else{
					$(this).closest('tr').insertAfter($(this).closest('tr').next("tr"));
				}
				$(".upbtn").first().prop('disabled', true);
				$(".downbtn").last().prop('disabled', true);
				$(".Num").each(function(index, el) {
					$(el).find(".Number").text(index+1);
				});
				$("#menu_order").append($("[name^='menu_id']"));
				$("#menu_order").submit();
			});
			$(".showlongtext").click(function(event) {
				$(this).closest('td').find(".longtext").show();
				$(this).closest('td').find(".shorttext").hide();
			});
			$(".weekcheck").click(function(event) {
				var wk = $(this).val();
				if($(this).prop('checked')){
					$(".weekcheck"+wk).prop('checked', true);	
				} else{
					$(".weekcheck"+wk).prop('checked', false);	
				}
			});
			$("[name='today_takeout_times']").change(function(event) {
				if(confirm("受取時間を延長しますか？")){
					$(this).closest('form').submit();
				}else{
					return false;					
				}
			});
		});
	</script>
</head>
<body>
<?php require "header.php"; ?>
<?php require "menu.php"; ?>
	<div class="content">
		<h1><?php echo date("Y年n月j日")." ".$week_array[date("w")]."曜日" ?></h1>
		<h2><?php echo $page_array[$page]; ?></h2>
		<?php if($page==1): ?>
		<div class="page1">
			<?php 
				$shop_menu_list = _getMenuList($_SESSION["shop_id"]);
				if(count($shop_menu_list)>0):
			 ?>
			<form action="mod/regist_menu_order.php" method="post" id="menu_order">
				<?php _getToken(); ?>
			</form>
				<table class="main_table">
					<tr>
						<th>No</th>
						<th>並べ替え</th>
						<th>商品名</th>
						<th width="30%">商品紹介</th>
						<th>編集</th>
					</tr>
					<?php $no = 1;foreach ($shop_menu_list as $key => $value) { ?>
						<tr>
							<td class="Num">
								<div class="Number">
									<?php echo $no++; ?>
								</div>
								<input type="hidden" name="menu_id[]" value="<?php echo $value["menu_id"] ?>">
							</td>
							<td align="center">
								<button class="updownbtn upbtn"><i class="fas fa-sort-up"></i>上へ</button>
								<button class="updownbtn downbtn"><i class="fas fa-sort-down"></i>下へ</button>
							</td>
							<td>
								<img src="../pics/<?php echo $value["menu_pict"] ?>" alt="">
								<?php echo $value["menu_name"] ?>
							</td>
							<td>
								<div class="longtext">
									<?php echo nl2br($value["menu_text"]) ?>
								</div>
								<div class="shorttext">
									<?php echo nl2br(mb_substr($value["menu_text"], 0,20) ) ?>
									<?php echo mb_strlen($value["menu_text"])>20?"<span class='showlongtext'>...続きを表示</span>":""; ?>
								</div>
							</td>
							<td align="center">
								<form action="menu_describe.php" method="post">
									<?php _getToken(); ?>
									<input type="hidden" name="id" value="<?php echo $value["menu_id"] ?>">
									<button>編集</button>
								</form>
							</td>
						</tr>
					<?php } ?>
				</table>
			<?php endif; ?>
		<div>
			<form action="menu_describe.php" method="post">
				<?php _getToken(); ?>
				<button>メニュー追加</button>
			</form>
		</div>			
		</div>
		<?php elseif($page==2): ?>
		<div class="page2">
			<h2>休日設定</h2>
			<?php
				if($_GET["month"]){
					$month_array = explode("-", $_GET["month"]);
					$year = $month_array[0];
					$month = $month_array[1];
				}else{
					$year = date('Y');
					$month = date('n');
				}
				// 月末日を取得
				$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
				$calendar = array();
				$j = 0;
				// 月末日までループ
				for ($i = 1; $i < $last_day + 1; $i++) {
				    // 曜日を取得
				    $week = date('w', mktime(0, 0, 0, $month, $i, $year));
				    // 1日の場合
				    if ($i == 1) {
				        // 1日目の曜日までをループ
				        for ($s = 1; $s <= $week; $s++) {
				            // 前半に空文字をセット
				            $calendar[$j]['day'] = '';
				            $j++;
				        }
				    }
				    // 配列に日付をセット
				    $calendar[$j]['day'] = $i;
				    $j++;
				 
				    // 月末日の場合
				    if ($i == $last_day) {
				        // 月末日から残りをループ
				        for ($e = 1; $e <= 6 - $week; $e++) {
				            // 後半に空文字をセット
				            $calendar[$j]['day'] = '';
				            $j++;
				        }
				    }
				}
				$prev_month = ($month==1?$year-1:$year)."-".sprintf('%02d', ($month==1?12:$month-1));
				$next_month = ($month==12?$year+1:$year)."-".sprintf('%02d', ($month==12?1:$month+1));

				$now_month = $year."-".sprintf('%02d',$month);
				//登録済みの店休日を取得する
				$sql = "SELECT * FROM `store_holiday` WHERE `shop_id`=$shop_id AND `date` LIKE '$now_month%'";
				$res = _mysqlquery($sql);
				$store_holiday_array = array();
				while($col = $res->fetch_array()){
					$store_holiday_array[] = $col["date"];
				}
				 
			?>			
			<a href="top_page.php?page=2&month=<?php echo $prev_month ?>"><i class="fas fa-caret-left"></i>前月</a>
			<?php echo $year; ?>年<?php echo $month; ?>月
			<a href="top_page.php?page=2&month=<?php echo $next_month ?>">次月<i class="fas fa-caret-right"></i></a>
			<br>
			<br>
			<style type="text/css">
			table {
			    width: 100%;
			}
			table th {
			    background: #EEEEEE;
			}
			table th,
			table td {
			    border: 1px solid #CCCCCC;
			    text-align: center;
			    padding: 5px;
			}
			</style>
			<input type="checkbox" checked="checked" style="pointer-events: none;">：営業日

			<form action="mod/regist_store_holiday.php" method="post">
				<?php _getToken(); ?>
				<input type="hidden" name="month" value="<?php echo $now_month ?>">
			<table>
			    <tr>
			    	<?php foreach ($week_array as $key => $value) { ?>
				        <th>
				        	<?php echo $value; ?>
				        	<input type="checkbox" class="weekcheck" value="<?php echo $key ?>" checked="checked">
				        </th>
			    	<?php } ?>
			    </tr>
			    <tr>
			    <?php $cnt = 0; ?>
			    <?php foreach ($calendar as $key => $value): ?>
			        <td>
				        <?php $cnt++; ?>
				        <?php echo $value['day']; ?>
				        <?php 
				        if($value['day']):
				        	$date = date("Y-m-d",mktime(0, 0, 0, $month, $value['day'], $year));
				        	$date_w = date("w",mktime(0, 0, 0, $month, $value['day'], $year));
				         ?>
				         <input type="hidden" name="month_calendar[]" value="<?php echo $date ?>">
				         <input type="checkbox" class="weekcheck<?php echo $date_w ?>" name="business_day[]" value="<?php echo $date ?>" <?php echo in_array($date, $store_holiday_array)?"":'checked="checked"'; ?>>
				        <?php endif; ?>
			        </td>
			    <?php if ($cnt == 7): ?>
			    </tr>
			    <tr>
				    <?php $cnt = 0; ?>
				    <?php endif; ?>
				    <?php endforeach; ?>
			    </tr>
			</table>
			<button>保存</button>
			</form>

			<?php 
				//登録済みの営業時間・予約可能時間を取得する
				$sql = "SELECT * FROM `store_week_time` WHERE `shop_id`=$shop_id ";
				$res = _mysqlquery($sql);
				$swt_array = array();
				while($col = $res->fetch_array()){
					$swt_array[$col["week_no"]] = array(
						  "from" => $col["business_hours_from"]
						, "to" => $col["business_hours_to"]
						, "times" => $col["takeout_times"]);
				}
			 ?>
			<form action="mod/regist_store_holiday.php" method="post">
				<?php _getToken(); ?>
				<input type="hidden" name="form_type" value="store_week_time">
			<h2>営業時間・予約可能時間</h2>
			<table>
				<tr>
					<th>曜日</th>
					<th>営業時間</th>
					<th>受取可能時間</th>
				</tr>
		    	<?php foreach ($week_array as $key => $value) { ?>
		    	<tr>
			        <td><?php echo $value; ?></td>
			        <td>
			        	<?php echo _HtmlSelectBusinessHours('from['.$key.']',$swt_array[$key]["from"]>0?$swt_array[$key]["from"]:'08:00'); ?>
			        	～
			        	<?php echo _HtmlSelectBusinessHours('to['.$key.']',$swt_array[$key]["to"]>0?$swt_array[$key]["to"]:'18:00'); ?>
			        </td>
			        <td>
			        	<?php echo _HtmlSelectTakeoutTimes('['.$key.']',$swt_array[$key]["times"]>0?$swt_array[$key]["times"]:'0'); ?>
			        </td>
		    	</tr>
		    	<?php } ?>				
			</table>
			<button>保存</button>
			</form>

		</div>	
		<?php elseif($page==3): ?>
		<div class="page2">
			<ul class="nav nav-tabs">
			  <li class="nav-item">
			    <a href="top_page.php?page=3">当日</a>
			  </li>
			  <li class="nav-item">
			    <a href="top_page.php?page=3&month=1">当月</a>
			  </li>
			</ul>			
		</div>
		<?php 
			$sql = "SELECT * FROM `order_reservation_no` WHERE `shop_id`=$shop_id";
			if($_GET["month"]){
				$sql .= " AND year(`created`)=year(NOW()) AND month(`created`)=month(NOW())";
			}else{
				$sql .= " AND date(`created`)=date(NOW())";
			}
			$res = _mysqlquery($sql);
		 ?>
		<div>
			<table>
				<tr>
					<th>注文日</th>
					<th>注文時間</th>
					<th>受付番号</th>
					<th>受取予定時間</th>
					<th>商品名　数量</th>
					<th>携帯電話</th>
					<th>印刷</th>
				</tr>
				<?php while($col = _mysqlfetcharray($res)){
					$order_no = $col["order_reservation_no"];
					$sql1 = "SELECT * FROM `order_reservation` WHERE `order_no`='$order_no'";
					$res1 = _mysqlquery($sql1);
					$num = _mysqlnumrows($res1);
					if(!$num)continue;
				 ?>
					<tr>
						<td><?php echo date("m/d",strtotime($col["created"])) ?></td>
						<td><?php echo date("H:i",strtotime($col["created"])) ?></td>
						<td><?php echo $col["order_reservation_no"] ?></td>
						<td><?php echo date("H:i",strtotime($col["take_out_time"])) ?></td>
						<td>
						 <table class="table table-sm">
						 	<?php 
						 	while ($col1 = _mysqlfetcharray($res1)) {
						 		$menu_id = $col1["menu_id"];
						 		$count = $col1["count"];
						 		$info = _getMenuInfo($menu_id); 
						 		$take_out_time[] = _getTakeOutTime($menu_id,$shop_id);
						 	?>
						 	<tr>
						 		<td>
						 			<?php echo $info["menu_name"] ?>
						 		</td>
						 		<td>×</td>
						 		<td class="text-right"><?php echo $count ?></td>
						 	</tr>
						 	<?php }
						 	 ?>
						 </table>							
						</td>
						<td><?php echo $col["phone_number"] ?></td>
						<td><button type="button" onclick="window.open('order_reservation_print.php?order_no=<?php echo $col["order_reservation_no"] ?>', '_blank');">印刷</button></td>
					</tr>
				<?php } ?>
			</table>		
		</div>
		<?php else: ?>
		<div class="page0">
			<?php 
				$shop_menu_list = _getMenuList($_SESSION["shop_id"]);
				if(count($shop_menu_list)>0):
			 ?>
			<table class="main_table">
				<tr>
					<th>画像</th>
					<th>商品名<br>価格</th>
					<th>予約停止</th>
					<th>受取時間延長</th>
				</tr>
				<?php foreach ($shop_menu_list as $key => $value) { ?>
					<tr>
						<td>
							<img src="../pics/<?php echo $value["menu_pict"] ?>" alt="">
						</td>
						<td>
							<?php echo $value["menu_name"] ?><br>
							<?php echo number_format($value["menu_price"]) ?>円
						</td>
						<td align="center">
							<form action="mod/regist_stop_reservation.php" method="post">
								<?php _getToken(); ?>
							<?php if($value["stop_reservation_id"]): ?>
								<input type="hidden" name="stop_reservation_id" value="<?php echo $value["stop_reservation_id"] ?>">
								<button>予約停止中</button>
							<?php else: ?>
								<input type="hidden" name="menu_id" value="<?php echo $value["menu_id"] ?>">
								<button>予約停止</button>
							<?php endif; ?>
							</form>
						</td>
						<td align="center">
							<form action="mod/regist_today_takeout_time.php" method="post">
								<?php _getToken(); ?>
								<input type="hidden" name="menu_id" value="<?php echo $value["menu_id"] ?>">
								<?php 
									$takeout_times = _getTakeOutTime($value["menu_id"]);
									echo _HtmlSelectTakeoutTimesToday($takeout_times);
								 ?>
							</form>	
						</td>
					</tr>
				<?php } ?>
			</table>
			<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>
</body>
</html>