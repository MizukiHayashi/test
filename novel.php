<?php
//-------------------------------------------------
//DB接続準備
//-------------------------------------------------
$dsn  = 'mysql:dbname=noveldb;host=127.0.0.1';   //接続先
$user = 'root';         //MySQLのユーザーID
$pw   = 'H@chiouji1';   //MySQLのパスワード

//-------------------------------------------------
//ユーザー名処理
//-------------------------------------------------
if(array_key_exists('playername',$_GET)){
	$sql = 'UPDATE Save SET name=:pname,scn_id=0';
	$dbh = new PDO($dsn, $user, $pw);   //接続
	$sth = $dbh->prepare($sql);         //SQL準備
	$sth->bindValue(':pname',   $_GET['playername'],PDO::PARAM_STR);
	$sth->execute();
	
	$playername = $_GET['playername'];
	$saveID=0;
	$load=false;
}
else{
	$sql = 'SELECT name,scn_id FROM Save';
	
	$dbh = new PDO($dsn, $user, $pw);   //接続
	$sth = $dbh->prepare($sql);         //SQL準備
	$sth->execute();                    //実行
	
	 $buff = $sth->fetch(PDO::FETCH_ASSOC);	//1レコード取得
	 $playername=$buff['name']; 
	 $saveID=$buff['scn_id'];
	 $load = true;
}

//-------------------------------------------------
//シナリオ準備
//-------------------------------------------------
$scenario = 'SELECT id,cmd,value FROM Scenario';
$sth = $dbh->prepare($scenario);    //SQL準備
$sth->execute();                    //実行

while(true){
	$buff = $sth->fetch(PDO::FETCH_ASSOC);	//1レコード取得
	if($buff===false){
		break;
	}
	$id[]=$buff['id'];
	$command[] = $buff['cmd'];
	$value[]   = $buff['value'];
 };
 $id = implode(',',$id);
 $command = implode(',', $command);
 $value	= implode(',', $value);
 $jsonID = json_encode($id);
 $jsonCommand = json_encode($command);
 $jsonValue   = json_encode($value);
 
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<title>Novel</title>
		<style>
			#novelwindow{
				width:      800px;
				height:     600px;
				border: 	  1px solid gray;
			
				background-image: url(image/zimusyo.png);
				background-size: 800px 600px;
			}
			#save{
				width:100px;
				height:50px;
			}
			#title{
				width:100px;
				height:50px;
			}
			#message{
				position: absolute;
				top: 350px;
				left: 75px;
				z-index: 1;
				
				border: 2px solid blue;
				width: 650px;
				height: 200px;
				
				font-size: 24pt;
				padding: 5px;
				
				background-color: rgba(204, 252, 255, 0.7);
			}
			
			#Tantei{
				position: absolute;
				top: 50px;
				left: 50px;
				height: 500px;
				visibility: visible;
			}
			#Zyosyu{
				position: absolute;
				top: 60px;
				left: 580px;
				height: 480px;
				visibility: visible;
			}
			#Keibu{
				position: absolute;
				top: 85px;
				left: 520px;
				height: 460px;		
				visibility: hidden;
			}
			#Wainshou{
				position: absolute;
				top: 50px;
				left: 480px;
				height: 490px;
				visibility: hidden;
			}
			#Tuma{
				position: absolute;
				top: 110px;
				left: 350px;
				height: 430px;
				visibility: hidden;
			}
			#Keikan{
				position: absolute;
				top: 100px;
				left: 580px;
				height: 450px;
				visibility: hidden;
			}
		</style>

	</head>
	<body>
		<audio id="bgm" src="image/zimusyo.mp3" preload outplay loop></audio>
		<div id="novelwindow">
			<img id="Tantei" src="image/tantei.png">
			<img id="Zyosyu" src="image/zyoshu.png">
			<img id="Keibu" src="image/keibu.png">
			<img id="Wainshou" src="image/wainshow.png">
			<img id="Tuma" src="image/tuma.png">
			<img id="Keikan" src="image/keikan.png">
			<div id="message">
			ここは古びた探偵事務所
			</div>
			<form action="save.php" method = "GET">
				<input id="saveid" type="hidden" name="saveid" value="0">
				<button id="save"><strong>セーブ</strong></button>
			</form>
			<form action="title.html">
				<button id="title"><strong>タイトルへ</strong></button>
			</form>
		</div>
				
		<script>
			var saveID = <?= $saveID ?>;
			var load= '<?= $load ?>';
			
			var playername= "<?= $playername ?>";
			
			//-----JSONで値渡し--------------------------------//
			var jsonid=JSON.parse('<?= $jsonID ?>');
			var jsoncom=JSON.parse('<?= $jsonCommand ?>');
			var jsonval=JSON.parse('<?= $jsonValue ?>');
			//---------------------------------------------------//
			
			var id=jsonid.split(',');
			var com=jsoncom.split(',');
			var val=jsonval.split(',');
			
			var bgm = document.getElementById("bgm");
			var msg	= document.getElementById("message");
			var tantei = document.getElementById("Tantei");
			var zyosyu = document.getElementById("Zyosyu");
			var keibu = document.getElementById("Keibu");
			var wainshou = document.getElementById("Wainshou");
			var tuma = document.getElementById("Tuma");
			var keikan = document.getElementById("Keikan");
			var novelwindow = document.getElementById("novelwindow");
			var saveid=document.getElementById("saveid");
			var i=0;

			if(load==true){
				while(i<saveID){
					var command=com[i];
					var value = val[i];
				
					switch(command){
						case "TXT":
							value = value.replace(/##NAME##/g,
													  "<span style='color:red'>"+playername+"</span>"
													  );
							msg.innerHTML = value;
							break;
						case "Tantei":
							tantei.style.visibility=value;
							break;
						case "TTMove":
							tantei.style.left = value;
							break;
						case "Zyosyu":
							zyosyu.style.visibility=value;
							break;
						case "ZSMove":
							zyosyu.style.left = value;
							break;
						case "Keibu":
							keibu.style.visibility=value;
							break;
						case "Wainshou":
							wainshou.style.visibility=value;
							break;
						case "Tuma":
							tuma.style.visibility=value;
							break;
						case "Keikan":
							keikan.style.visibility=value;
							break;
						case "BACK":
							novelwindow.style.backgroundImage=value;
							break;
						case "BGM":
							bgm.src=value;
							break;
					}
					i++;
				}
				load=false;
			}
			if(load==false){
			msg.addEventListener("click",function(){
				var command=com[i];
				var value = val[i];
				
				switch(command){
					case "TXT":
						value = value.replace(/##NAME##/g,
												  "<span style='color:red'>"+playername+"</span>"
												  );
						msg.innerHTML = value;
						break;
					case "Tantei":
						tantei.style.visibility=value;
						break;
					case "TTMove":
						tantei.style.left = value;
					case "Zyosyu":
						zyosyu.style.visibility=value;
						break;
					case "ZSMove":
						zyosyu.style.left = value;
						break;
					case "Keibu":
						keibu.style.visibility=value;
						break;
					case "Wainshou":
						wainshou.style.visibility=value;
						break;
					case "Tuma":
						tuma.style.visibility=value;
						break;
					case "Keikan":
						keikan.style.visibility=value;
						break;
					case "BACK":
						novelwindow.style.backgroundImage=value;
						break;
					case "BGM":
						bgm.src=value;
						break;
				}
				i++;
				saveid.value=i;
			});
		
			}
			</script>
			
		
	</body>

</html>
