<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>"mission_5-01"</title>
        <strong>僕の今週の献立考えてください</strong>
    </head>
    <body>
        
            <form action=""method="post" id="form1">
            </form>
            <form action=""method="post" id="form2">
            </form>
            <form action=""method="post" id="form3">
            </form>
            <div>
            <input type="text" name="name" placeholder="名前を入力" form="form1">
            <input type="text" name="comment" placeholder="コメント入力" form="form1">
            <input type="password" name="password" placeholder="パスワード" form="form1">
            <input type="submit" name="submit" value="送信" form="form1">
            </div>
            <div>
                <input type="number" name="deletenumber" placeholder="削除対象番号" form="form2">
                <input type="password" name="password_del" placeholder="パスワード" form="form2">
                <input type="submit" name="del" value="削除" form="form2">
            </div>
            <div>
                <input type="text" name="editnumber" placeholder="編集対象番号" form="form3">
                <input type="password" name="password_edit" placeholder="パスワード" form="form3">
                <input type="submit" name="edit" value="編集" form="form3">
            </div>
            <?php
                $dsn='データベース名';
                $user='ユーザー名';
                $password='パスワード';
                $pdo= new PDO($dsn,$user,$password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                $sql="CREATE TABLE IF NOT EXISTS tbtable1"
                ." ("
                ."id INT AUTO_INCREMENT PRIMARY KEY,"
                ."name char(32),"
                ."comment TEXT,"
                ."password TEXT,"
                ."date DATETIME"
                .");";
                $stmt=$pdo->query($sql);
//既存投稿//
                if(empty($_POST["deletenumber"]) && empty($_POST["editnumber"]) && empty($_POST["edit_num"])){
                    $sql='SELECT * FROM tbtable1';
                    $stmt=$pdo->query($sql);
                    $results=$stmt->fetchALL();
                    foreach($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                    echo "<hr>";
                    }
                }
//新規投稿
                if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) && empty($_POST["edit_num"])){
                    $sql=$pdo -> prepare("INSERT INTO tbtable1 (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $name=$_POST["name"];
                    $comment=$_POST["comment"];
                    $password=$_POST["password"];
                    $date=date("Y/m/d H:i:s");
                    $sql -> execute();
                    $sql='SELECT * FROM tbtable1 WHERE id=(SELECT MAX(id) FROM tbtable1)';
                    $stmt=$pdo->query($sql);
                    $results=$stmt->fetchALL();
                    foreach($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    }
                } 
//削除
                if(!empty($_POST["deletenumber"]) && !empty($_POST["password_del"])){
                    $deletenumber=$_POST["deletenumber"];
                    $password_del=$_POST["password_del"];
                    $id=$deletenumber;
                    $sql='SELECT * FROM tbtable1 WHERE id=:id ';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':id', $id,PDO::PARAM_INT);
                    $stmt->execute();
                    $results=$stmt->fetchALL();
                        foreach($results as $row){
                            if($row['password'] == $password_del){
                                $sql='delete from tbtable1 where id=:id';
                                $stmt=$pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                            }else{
                                echo "パスワードが違います<br>";
                            }
                        }
                    $sql='SELECT * FROM tbtable1';
                    $stmt=$pdo->query($sql);
                    $results=$stmt->fetchALL();
                    foreach($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    }
                }
//編集用
                if(!empty($_POST["editnumber"]) && !empty($_POST["password_edit"])){
                    $editnumber=$_POST["editnumber"];
                    $password_edit=$_POST["password_edit"];
                    $id=$editnumber;
                    $sql='SELECT * FROM tbtable1 WHERE id=:id ';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $results=$stmt->fetchALL();
                        foreach($results as $row){
                            if($password_edit == $row['password']){
                                echo $row['id'].',';
                                echo $row['name'].',';
                                echo $row['comment'].'<br>';
                                echo "<hr>";
                            }else{
                                echo "パスワードが違います<br>";
                            }
                        }   
                }
//編集後表示
                if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) && !empty($_POST["edit_num"])){
                    $id=$_POST["edit_num"];
                    $name=$_POST["name"];
                    $comment=$_POST["comment"];
                    $password=$_POST["password"];
                    $date=date("Y/m/d H:i:s");
                    $sql='UPDATE tbtable1 SET name=:name,comment=:comment,password=:password, date=:date WHERE id=:id';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $sql='SELECT * FROM tbtable1';
                    $stmt=$pdo->query($sql);
                    $results=$stmt->fetchALL();
                    foreach($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    }
                }
                ?>
        
        <input action="text" name="edit_num" 
         value="<?php if(!empty($_POST["editnumber"]) && !empty($_POST["password_edit"])){
             $id=$_POST["editnumber"];
             $sql='SELECT * FROM tbtable1 WHERE id=:id ';
             $stmt=$pdo->prepare($sql);
             $stmt->bindParam(':id', $id, PDO::PARAM_INT);
             $stmt->execute();
             $results=$stmt->fetchALL();
             foreach($results as $row){
                 if($row['password'] == $_POST["password_edit"]){
                     echo $_POST["editnumber"];
                 }
             }
         }?>" form="form1">
    </body>
</html>