<!doctype html>
<html>
  <head>
    <title>
      mission_5.php
    </title>
    <meta charset="utf-8">
  </head>
  <body>
    <?php
    //MySQLへ接続
    $dsn = 'mysql:dbname=tb******db;host=localhost';
    	$user = 'tb-******';
    	$password = '**********';
    	$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成（テーブル名：mission_5）
    $sql = "CREATE TABLE IF NOT EXISTS mission_5"
    	." ("
    	."id INT AUTO_INCREMENT PRIMARY KEY,"
    	."name char(32),"
    	."comment TEXT,"
      ."date DATETIME,"
      ."pass TEXT"
    	.");";
    	$stmt = $pdo->query($sql);
    // 投稿機能
    if(isset($_POST["messagebtn"])){  //  送信ボタンが押された場合
      if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["messagepass"])) {  //  名前とコメントとパスワードが空じゃない場合
        if(!empty($_POST["editnum"])){  //  編集モード
          $id = $_POST["editnum"];
          $name = $_POST["name"]; //  名前
          $comment = $_POST["comment"]; //  コメント
          $date = date("Y/m/d H:i:s");  //  日付け
          $pass = $_POST["messagepass"];  //  パスワード
          $sql='update mission_5 set name=:name,comment=:comment, date=:date, pass=:pass where id=:id';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':name', $name, PDO::PARAM_STR);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt->bindParam(':date', $date, PDO::PARAM_STR);
						$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
						$stmt->bindParam(':id', $id, PDO::PARAM_INT);
						$stmt->execute();
        }elseif(empty($_POST["editnum"])){  //  新規投稿モード
          $sql = $pdo->prepare("INSERT INTO mission_5 (name,comment,date,pass) VALUES (:name, :comment, :date, :pass)");
              $sql->bindParam(':name', $name, PDO::PARAM_STR);
              $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
              $sql->bindParam(':date', $date, PDO::PARAM_STR);
              $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
          $name = $_POST["name"]; //  名前
          $comment = $_POST["comment"]; //  コメント
          $date = date("Y/m/d H:i:s");  //  日付け
          $pass = $_POST["messagepass"];  //  パスワード
          $sql->execute();
        }
      }elseif(empty($_POST["name"]) || empty($_POST["comment"])){ //  名前かコメントが入力されなかった場合
        echo "名前またはコメントが入力されていません";
      }elseif(empty($_POST["messagepass"])){  //  パスワードが入力されなかった場合
        echo "パスワードを入力してください";
      }
    }
    // 削除機能
    if(isset(($_POST["deletebtn"]))){ //  削除ボタンが押された場合
      if(!empty($_POST["delete"]) && !empty($_POST["deletepass"])){ //  削除番号とパスワードが空じゃない場合
        $id = $_POST["delete"];
					$sql = 'SELECT * FROM mission_5';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach($results as $word){
						if($word["id"] == $id){
							if($word["pass"] == $_POST["deletepass"]){
								$sql = 'delete from mission5 where id=:id';
								$stmt = $pdo->prepare($sql);
								$stmt->bindParam(':id', $id, PDO::PARAM_INT);
								$stmt->execute();
              }else{
                echo "パスワードが違います";
              }
            }
          }
      }elseif(empty($_POST["delete"])){ //  削除番号が入力されなかった場合
        echo "削除番号を半角で入力してください";
      }elseif(empty($_POST["deletepass"])){ //  パスワードが入力されなかった場合
        echo "パスワードを入力してください";
      }
    }
    // 編集機能
    if(isset($_POST["editbtn"])){ //  編集ボタンが押された場合
      if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){ //  編集番号とパスワードが空じゃない場合
        $id = $_POST["edit"];
 					$sql = 'SELECT * FROM mission_5';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach($results as $word_1){
						if($word_1["id"] == $id){
							if($word_1["pass"] == $_POST["editpass"]){
								foreach($results as $word_2){
									if($word_2["id"] == $id){
										$editor_num = $word_2["id"];
										$editor_name = $word_2["name"];
										$editor_comment = $word_2["comment"];
									}
								}
								$stmt->execute();
							}else{
								echo "パスワードが違います";
              }
            }
          }
        }elseif(empty($_POST["edit"])){ //  編集番号が入力されなかった場合
          echo "編集番号を半角で入力してください";
        }elseif(empty($_POST["editpass"])){ //  パスワードが入力されなかった場合
          echo "パスワードを入力してください";
        }
      }
    ?>
  <form method="post" action="<?php print($_SERVER["PHP_SELF"]) ?>">
    <input type="hidden" name="editnum" value="<?php if(isset($_POST["editbtn"]) && !empty($editor_num)){print $editor_num;} ?>">
    <br>
    氏名
    <input type="text" placeholder="氏名" name="name" value="<?php if(isset($_POST["editbtn"]) && !empty($editor_name)){print $editor_name;} ?>">
    <br>
    <input type="text" placeholder="コメント" name="comment" value="<?php if(isset($_POST["editbtn"]) && !empty($editor_comment)){print $editor_comment;} ?>">
    <br>
    <input type="text" placeholder="パスワードを設定" name="messagepass">
    <input type="submit" value="送信" name="messagebtn">
    <br>
    削除
    <input type="text" placeholder="番号" name="delete">
    <br>
    <input type="text" placeholder="パスワード" name="deletepass">
    <input type="submit" value="削除" name="deletebtn">
    <br>
    編集番号
    <input type="text" placeholder="番号" name="edit">
    <br>
    <input type="text" placeholder="パスワード" name="editpass">
    <input type="submit" value="編集" name="editbtn">
    <br>
  </form>
  <?php
  //ブラウザ表示
  if(isset($_POST["messagebtn"]) || isset($_POST["deletebtn"]) || isset($_POST["editbtn"])){  //  送信ボタン、削除ボタン、編集ボタンのどれかが押された場合
    $dsn = 'mysql:dbname=tb******db;host=localhost';
    	$user = 'tb-******';
    	$password = '**********';
    	$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = 'SELECT * FROM mission_5';
  		$stmt = $pdo->query($sql);
  		$results = $stmt->fetchAll();
  		foreach($results as $row){
  			echo $row['id'].',';
  			echo $row['name'].',';
  			echo $row['comment'].',';
  			echo $row['date'].'<br>';
  			echo "<hr>";
  		}
  }
  ?>
  </body>
</html>
