
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>index</title>
	<link rel="stylesheet" href="">
</head>
<body>
<?php 
$mysqli = new mysqli("mysql12001.xserver.jp", "xs233340_teixs", "teixteix", "xs233340_teixsi");
$mysqli->set_charset("utf8");

//**********************SQL実行関数**********************
function _mysqlquery($sql){
	global $mysqli;
	if($result = $mysqli->query($sql)){
		return $result;
	}else{
		echo $sql;
		echo $mysqli->error;
		die;
	}
}

function _mysqlfetcharray($result){
	$col = $result->fetch_array();
	return $col;
}

function _mysqlnumrows($result){
	return $result->num_rows;
}

//**********************SQL実行関数**********************


//shop一覧を取得する
$sql = "SELECT * FROM `shop`";
$res = _mysqlquery($sql);
while($col = $res->fetch_array()){
	$list[] = $col["shop_name"];
}
 ?>	
	インデックスページです<br>
	<?php foreach ($list as $shop_name ) {
		echo $shop_name."<br>";
	} ?>
</body>
</html>