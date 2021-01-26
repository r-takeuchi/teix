<?php 
session_start();
require "../vulnerability_check.php";
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="css/basic.css">
	<title>texi（テイクス）管理者ログインページ</title>
	<link rel="stylesheet" href="">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://kit.fontawesome.com/639d74e288.js" crossorigin="anonymous"></script>
	<style>
		.content {
			width: 450px;
			background-color: #FFF;
			margin-top: 50px;
			padding: 15px;
		}
		.login_title {
			margin-top: 1em;
			text-align: center;			
		}
		.login_form input {
			font-size: 20px;
			width: -webkit-fill-available;
			width: -moz-available;
			padding: 5px 10px;
		}
		.login_btn_area {
			text-align: center;
			margin:30px auto; 
			width: 95%;
		}
		.login_ipt_area {
			width: 95%;
			margin: 5px auto;
		}
		.error {
		    background-color: pink;
		    color: red;
		    padding: 5px;
		    border-radius: 10px;
		    text-align: center;
		}
		.logopics {
			text-align: center;
		}
		.logopics img {
			width: 40%;
		}
	</style>	
</head>
<body>
	<div class="content">
		<div class="login_form">
			<div class="logopics">
				<img src="../common_pics/logo.png" alt="">
			</div>
			<h1 class="login_title">管理者ログイン</h1>
			<?php if($_SESSION["login_error"]): unset($_SESSION["login_error"]); ?>
				<div class="error">ログインIDとパスワードの組み合わせが間違っています</div>
			<?php endif; ?>
			<form action="mod/regist_login.php" method="post" autocomplete="off">
				<?php _getToken(); ?>
				<div class="login_ipt_area">
					<label for="">ログインID</label>
					<input type="text" name="login" value="">
				</div>
				<div class="login_ipt_area">
					<label for="">パスワード</label>
					<input type="password" name="password" value="">		
				</div>
				<div class="login_btn_area">
					<button>ログイン</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>