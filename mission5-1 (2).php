<?php


	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, 
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
    //編集機能
if(!empty($_POST["number"]) && !empty($_POST["wordpass"]) ){
    $editnumber=$_POST["number"];
    $sql="SELECT * FROM tbtest_final";
    $stmt=$pdo ->query($sql);
    $results=$stmt ->fetchAll();
    foreach($results as $row){
        if($editnumber==$row["id"] &&
        $_POST["wordpass"]==$row["pass"]){
            $send_editnumber=$row["id"];
            $editname=$row["name"];
            $editcomment=$row["comment"];
            break;
        }else{
            $editname="";
            $editcomment="";
        }
    }
}	

    //編集機能
if(!empty($_POST["name"])&&!empty($_POST["text"])){
if(!empty($_POST["send_editnumber"])){
	$editname = $_POST["name"];
	$editcomment = $_POST["text"]; //変更したい名前、変更したいコメントは自分で決めること
    $date=date("Y年/m月/n日 H時/i分/s秒");
    
    $sql="SELECT * FROM tbtest_final";
    $stmt=$pdo ->query($sql);
    $results=$stmt ->fetchAll();
    foreach($results as $row){
        $id=$_POST["send_editnumber"];
        $name=$editname;
        $comment=$editcomment;
        $date=$date;
        

	$sql = 'UPDATE tbtest_final SET name=:name,
	comment=:comment,date=:date WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}
}else {//新規投稿
    if(!empty($_POST["password"])){
        $postpassword=$_POST["password"];
    }
    $postname=$_POST["name"];
    $postcomment=$_POST["text"];
    $postdate=date("Y年/m月/n日 H時/i分/s秒");
    
    $sql = $pdo -> prepare("INSERT INTO tbtest_final 
	(name, comment, date, pass) 
	VALUES (:name, :comment, :date, :pass)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$name = $postname;
	$comment = $postcomment; 
	$date=$postdate;
	$pass=$postpassword;
	$sql -> execute();
}
}
    
	
	//削除機能
if(!empty($_POST["delno"]) && !empty($_POST["pass"])){
    $delnumber=$_POST["delno"];
    $delpass=$_POST["pass"];
    
    $sql = 'SELECT * FROM tbtest_final';
	$stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
	foreach ($results as $row){
	    if($delnumber==$row["id"] && $delpass==$row["pass"]){
	        
	$id=$delnumber;        
	$sql = 'delete from tbtest_final where id=:id';
	$stmt = $pdo->prepare($sql);
	
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}
}
}
   
	$sql = 'SELECT * FROM tbtest_final';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row["comment"]. ",";
		echo $row["date"].",";
		echo $row["pass"]."<br>";
	    echo "<hr>";
	}
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission5-1</title>
</head>
<body>
<form action="" method="post">
    <!--POSTに渡すことを意味する-->
    <!--名前、コメント入力＋パスワード欄-->
        
        <input type="hidden" name="send_editnumber"
        value="<?php if(!empty($_POST["number"])){
            echo $send_editnumber;
        }
        ?>">
        
        <label for="name">名前</label>
        <input type="name" name="name" 
        value="<?php 
                 if(!empty($_POST["number"])){
                     echo $editname;
                 }
                 ?>">
        
        <label for="text">コメント</label>
        <input type="text" name="text" 
        value="<?php
                  if(!empty($_POST["number"])){
                      echo $editcomment;
                  } 
                   ?>">
        
        <label for="password">パスワード</label>
        <input type="text" name="password" value="">
        <input type="submit" name="normal">
</form>
<br>

<form action="" method="post">
    <!--削除番号入力フォーム＋パスワード欄-->
    
        <input type="text" name="delno">
        <label for="pass">パスワード</label>
        <input type="text" name="pass">
        <input type="submit" name="delete" value="削除">
</form>
<br>

<form action="" method="post">
     <!--編集番号入力フォーム＋パスワード欄-->
     
        <input type="text" name="number">
        <label for="wordpass">パスワード</label>
        <input type="text" name="wordpass">
        <input type="submit" name="edit" value="編集">
</form>
</body>
</html>