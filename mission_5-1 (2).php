<?php
    //データベースに接続
    $dsn =
	$user =
	$password =
	$pdo = new PDO($dsn, $user, $password, 
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//データベースにテーブルを作る
	$sql = "CREATE TABLE IF NOT EXISTS tbtest2"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "sousin_pass char(32),"
	. "time char(32)"
	.");";
	$stmt = $pdo->query($sql);
	
	//定義つけ
	$name=$_POST["name"];
    $comment=$_POST["comment"];
    $time=date('Y年m月d日 H:i:s');
         
    $sakujyo=$_POST["sakujyo"]; //削除フォームに送信されたもの
    $hensyuu=$_POST["hensyuu"];//編集フォームに送信されたもの
    $hensyuu_num=$_POST["hensyuunumber"];//編集番号を表示するフォーム
            
    $sousin_pass=$_POST["sousin_pass"]; //受信フォームのパスワード
    $sakujyo_pass=$_POST["sakujyo_pass"];//削除フォームのパスワード
    $hensyuu_pass=$_POST["hensyuu_pass"];//編集フォームのパスワード
            
	
	//編集選択
	if(!empty($hensyuu && $hensyuu_pass)){
	    $id=$hensyuu;
        $sql = 'SELECT * FROM tbtest2 where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
	    echo "編集選択";
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($hensyuu_pass == $row["sousin_pass"]){
	            $hensyuu_bango=$row["id"];
	            $hensyuu_name=$row["name"];
	            $hensyuu_comment=$row["comment"];
	        }
	    }
	        
	    
	}
	
	//データベースへの登録
	if(!empty($name && $comment && $sousin_pass)){
        if(!empty($hensyuu_num && $sousin_pass)){
            echo "編集";
            $id=$hensyuu_num;
            $sql = 'SELECT * FROM tbtest2 where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	   
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
	            if($sousin_pass == $row["sousin_pass"]){
	                echo "完成";
	                $sql = 'UPDATE tbtest2 SET name=:name,comment=:comment,
	                       sousin_pass=:sousin_pass,time=:time where id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	                $stmt->bindParam(':sousin_pass', $sousin_pass, PDO::PARAM_STR);
	                $stmt->bindParam(':time', $time, PDO::PARAM_STR);
	                $stmt->execute();
	                echo "終了";
	            }
	        }
        }else{
            echo "投稿";
            $sql = $pdo -> prepare("INSERT INTO tbtest2 (name, comment,sousin_pass,time) 
	        VALUES (:name, :comment, :sousin_pass, :time)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':sousin_pass', $sousin_pass, PDO::PARAM_STR);
	        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	        $sql -> execute();
	
	        }
	}
	    
	//削除(空じゃなかったら)
	if(!empty($sakujyo && $sakujyo_pass)){
	    $id=$sakujyo;
	    $sql = 'SELECT * FROM tbtest2 where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);//番号が一致したら
	    $stmt->execute();
	    
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($sakujyo_pass == $row["sousin_pass"]){
	            $sql = 'delete from tbtest2 where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
	        }
		
	    }
    }
    
    
    
	
	
	
	//編集（空じゃなったら）=hiddenのフォームに番号が入ってたら
	/*if(!empty($name && $comment)){
        if(!empty($hensyuu_num && $hensyuu_pass)){
            echo "編集";
            $id=$hensyuu_num;
            $sql = 'SELECT * FROM tbtest where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
	    
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
	            if($hensyuu_pass == $row["sousin_pass"]){
	                $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->bindParam(':name',$name, PDO::PARAM_STR);
	                $stmt->bindParam(':comment',$comment, PDO::PARAM_STR);
	                $stmt->bindParam(':sousin_pass',$sousin_pass, PDO::PARAM_STR);
	                $stmt->bindParam(':time',$time, PDO::PARAM_STR);
	                $stmt->execute();
	            }
	        }
        }
	}*/

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset ="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
    <h2>今まで読んだ本や漫画の中で一番印象に残ったものはありますか？
    ぜひ教えてください！！</h2>
        <form action ="" method ="post">
            <input type ="text" name="name" placeholder="名前"
            value="<?php echo $hensyuu_name;?>"><br>
            <input type ="text" name="comment" placeholder="コメント"
            value="<?php echo $hensyuu_comment;?>"><br>
            <input type ="password" autocomplete="new-password"
            name="sousin_pass" placeholder="パスワード">
            <input type ="submit" name="sousin" value="送信">
            <input type ="hidden" name="hensyuunumber"
            value="<?php echo $hensyuu_bango;?>"><br>
            <br>
            <br>
            <input type ="number" name="sakujyo" placeholder="削除対象番号"><br>
            <input type ="password" autocomplete="new-password"  name="sakujyo_pass" placeholder="パスワード">
            <input type ="submit" value="削除">
            <br>
            <br>
            <input type ="number" name="hensyuu" placeholder="編集対象番号"><br>
            <input type ="password" autocomplete="new-password"   name="hensyuu_pass" placeholder="パスワード">
            <input type ="submit" value="編集"><br>
        </form>
    <h4>【投稿一覧】</h4>    
</html>
<?php
    //テーブルの中身を表示する
	$sql = 'SELECT * FROM tbtest2';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		/*echo $row['sousin_pass'].',';*/
		echo $row['time']. '<br>';
		
	    echo "<hr>";
	}
    //終了
	$stmt = null;
	$pdo = null;
?>