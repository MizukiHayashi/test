<?php
//-------------------------------------------------
//DB接続準備
//-------------------------------------------------
$dsn  = 'mysql:dbname=noveldb;host=127.0.0.1';   //接続先
$user = 'root';         //MySQLのユーザーID
$pw   = 'H@chiouji1';   //MySQLのパスワード

$sql = 'UPDATE Save SET scn_id=:save_id';
$dbh = new PDO($dsn, $user, $pw);   //接続
$sth = $dbh->prepare($sql);         //SQL準備
$sth->bindValue(':save_id',   intval($_GET['saveid']),PDO::PARAM_INT);
$sth->execute();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<title>ノベルゲーム</title>
		<link rel="stylesheet" href="style.css" >
		<style>
			#back{
				width:100px;
				height:50px;
			}
		</style>
	</head>
	<body>
		<section id="novelwindow">
			<h1>セーブが完了しました</h1>
			<form>
				<button id="back" type="button" onclick="window.history.back();" >戻る</button>
			</form>
		</section>
	</body>
</html>
