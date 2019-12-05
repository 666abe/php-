<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
</head>
<title>やばい掲示板</title>

<?php
//mysql接続
$dsn = 'mysql:dbname=*********;host=*********';
$user = '*******';
$password = '********';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); //鍵的なもの

//createテーブル作成
$sql = "CREATE TABLE IF NOT EXISTS board"
	." (" //テーブルカラム
	. "id INT AUTO_INCREMENT PRIMARY KEY," //id
	. "name char(32)," //name
	. "comment TEXT," //comment
        . "d TEXT" //date
	.");";
$stmt = $pdo->query($sql);

/*
//テーブル一覧表示
$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";

//テーブルの中身を確認
$sql ='SHOW CREATE TABLE board';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";
*/
?>

<!-- HTML領域 -->
<h1 style="font-size: 20px;">やばい掲示板</h1>
<form action="board.php" method="post">
  <dl>
    パス：
    <input style="background-color: #FFDDDD;" type="text" name="pass" value= "" /><br>
    名前：
    <input type="text" name="name" value= "" /><br>
    コメ：
    <input type="text" name="comment" value= "" />
    <input type="submit" /><br>
    削除：
    <input type="text" name="del" value="" />
    <input type="submit" value="削除" /><br>
    編集：
    <input type="text" name="edit" value="" />
    <input type="hidden" name="subnum" value= "<?php if(!empty($_POST["edit"])){echo $_POST["edit"];} ?>" />
    <input type="submit" value="編集" />
    <!-- リロード -->
    <input type="submit" name="re" value="🔁" /><br>
  </dl>
</form>
<hr>
<!-- HTML領域 -->

<!-- PHP領域 -->	
<?php
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && ($_POST["pass"]==="アーイ")){
  //編集番号を受け付けたときの処理
  if(!empty($_POST["edit"])){
    echo "****************名前・コメントを変更しました****************"."<br>";

    //updateで編集
    $id = $_POST["edit"];       //変更する投稿番号
    $name = $_POST["name"];       //変更したい名前
    $comment = $_POST["comment"]; //変更したい名前  を変数で代入
    $d = date("Y/m/d H:i:s");     //年月日時間

    $sql = 'update board set name=:name,comment=:comment,d=:d where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':d', $d, PDO::PARAM_STR);
    $stmt->execute();

	  
  //新規投稿コメントを受け付けたときの処理
  }else{
    echo "****************コメントを受け付けました****************"."<br>";

    //insertでデータ入力
    $name = $_POST["name"]; //名前
    $comment = $_POST["comment"]; //コメント
    $d = date("Y/m/d H:i:s");     //年月日時間

    $sql = $pdo -> prepare("INSERT INTO board (id,name,comment,d) VALUES (:id,:name,:comment,:d)");
    $sql -> bindParam(':id', $id, PDO::PARAM_STR);
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':d', $d, PDO::PARAM_STR);
    $sql -> execute();
  }
	
	
  //コメントがないときの処理
}else{
  echo "****************名前とコメントを入力！****************"."<br>";

  //削除対象番号を受け付けたときの処理
  if(!empty($_POST["del"])&& ($_POST["pass"]==="アーイ")){

    //deleteで削除 selectで確認
    $id = $_POST["del"];
    $sql = 'delete from board where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
  }
}

	
//表示
//入力データをselectで表示
$sql = 'SELECT * FROM board';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){

	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
        echo $row['d'].'<br>';
	echo "<hr>";
}
?>
</html>
