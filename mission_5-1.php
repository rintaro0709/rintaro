<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset = "UTF-8">
    <title>5-1</title>
</head>

<body>
<!--このphpは，htmlで表示させるフォームよりも先に行いたい処理を書くよ-->
<?php
##データベースの場所を明示　最初にやること！##
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
##変数を定義する##
$error="";//ここで$errorと言う変数を作ってみる。今のところは空にしておく。
$deleteNo="パスワードが違います"; //削除できていないときに表示させたい
$editnumber="";
$editname="";
$editcomment="";

##編集の時，テキストボックスに入れる文字列を指定する##
if(isset($_POST["editnum"])&&isset($_POST["editpass"])){//$editと$editpassの両方に文字が入ったら
    ###変数を定義する###
    $edit=$_POST["editnum"];//編集フォームに入力された投稿番号
    $editpass=$_POST["editpass"];//編集フォーム・パスワード
    ###投稿を抽出する###
    $sql="SELECT * FROM table51";//table51を抽出
    $stmt=$pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){//各行の要素を抽出する
        $edID = $row["id"];
        $edNAME = $row["name"];
        $edCOMMENT = $row["comment"];
        $edPASS = $row["ps"];
        //echo $edID." ".$edNAME." ".$edCOMMENT." ".$edPASS."<br>";
        ####パスワードが正しいので操作####
        if($edID==$edit&&$edPASS==$editpass){
        /*もし$edID(=投稿番号)が$editと，$edPASS(=パスワード)が$editpassと
        両方とも一致していたら編集の手続きをとります。*/ 
            $editnumber=$edID;
            $editname=$edNAME;
            $editcomment=$edCOMMENT;
        }  
    }if($editnumber==""){//==にする！
        //もしeditnumberが空だったら
        /*$edID(=投稿番号)が$editと，$edPASS(=パスワード)が$editpassが
        一致するものが存在しなかったら表示させたい言葉を決めます。*/ 
        $error="パスワードが違います。";//空だった$errorに文字を代入する。
    } 
}  
?>
<!--ここまでで，編集ボタンを押した時に表示させたいものの定義が終了する-->
<!--ここから，フォームを作る-->
<h1>好きなスポーツは？</h1>
<h3>入力フォーム</h3>
<form action ="mission_5-1.php" method = "POST">
名前：<input type = "text" name ="name" placeholder="<?php if($editname==""){echo "お名前";}?>"value="<?php if($editname!=""){echo $editname;}?>"><br>    
コメント：<input type = "text" name ="comment" placeholder="<?php if($editcomment==""){echo "好きなスポーツ";}?>"value="<?php if($editname!=""){echo $editcomment;}?>"><br>
パスワード：<input type = "password" name ="sendpass">
<input type = "hidden" name ="hiddennum" value="<?php if($editnumber!=""){echo $editnumber;}?>"><br>   
<input type = "submit" name = "send" value ="送信">
</form>
<br>
<h3>削除番号指定用フォーム</h3>
<form action ="mission_5-1.php" method = "POST">
投稿番号：<input type = "number" name ="delete" placeholder="削除する番号"><br>
パスワード：<input type = "password" name ="ridpass"><br>
<input type = "submit"  name = "rid" value ="削除">
</form>
<br>
<h3>編集番号指定用フォーム</h3>
<form action ="mission_5-1.php" method = "POST">
投稿番号：<input type = "number" name ="editnum" placeholder="編集する番号"><br>
パスワード：<input type = "password" name ="editpass"><br>
<input type = "submit"  name = "edit" value ="編集">
</form>
<hr>
<?php
    if(isset($_POST["editnum"])&&isset($_POST["editpass"])){//$editと$editpassの両方に文字が入ったら
        ##変数を定義##
        $edit=$_POST["editnum"];//編集フォームに入力された投稿番号
        $editpass=$_POST["editpass"];//編集フォーム・パスワード
        ##テーブルの要素をとりだす##
        $sql="SELECT * FROM table51";//table51を抽出
        $stmt=$pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){//各行の要素を抽出する
            $edID = $row["id"];
            $edNAME = $row["name"];
            $edCOMMENT = $row["comment"];
            $edPASS = $row["ps"];
            ###パスワードが正しいので操作###
            if($edID==$edit&&$edPASS==$editpass){
            /*もし$edID(=投稿番号)が$editと，$edPASS(=パスワード)が$editpassと
            両方とも一致していたら編集の手続きをとります。*/
                $editnumber=$edID;
                $editname=$edNAME;
                $editcomment=$edCOMMENT;
            }  
        }if($editnumber==""){//==にする！
            //もしeditnumberが空だったら
            /*$edID(=投稿番号)が$editと，$edPASS(=パスワード)が$editpassが
            一致するものが存在しなかったら表示させたい言葉を決めます。*/
            $error="パスワードが違います。";//空だった$errorに文字を代入する。
        } 
    }##パスワードが一致しているので編集する場合##  
    if(!empty($_POST["hiddennum"])&&!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["sendpass"])){
        //もし編集する番号hiddennumと，名前とコメントが記入されていたら（その前にeditに記入されている）
        ###変数を定義### 
        $hiddennum=$_POST["hiddennum"];
        $name=$_POST["name"];//入力フォーム・お名前
        $comment = $_POST["comment"];//入力フォーム・コメント
        $timestamp=date("Y/m/d H:i:s");//新規投稿の日付取得
        $sendpass=$_POST["sendpass"];//入力フォーム・パスワード
        ###テーブルを取り出し編集する###
        $sql = 'UPDATE table51 SET name=:name,comment=:comment,ts=:ts,ps=:ps WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt -> bindParam(':ts',$timestamp, PDO::PARAM_STR);
        $stmt -> bindParam(':ps', $sendpass, PDO::PARAM_STR);
        $stmt -> bindParam(':id', $hiddennum, PDO::PARAM_INT);
        $stmt -> execute(); 
    }##単純に新規投稿の場合##
    elseif(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["sendpass"])){
        //もし($_POST["hiddennum"]が空欄でかつ)$name,$comment,$sendpassの全てが記入されていたら
        ###変数を定義###
        $name=$_POST["name"];//入力フォーム・お名前
        $comment = $_POST["comment"];//入力フォーム・コメント
        $timestamp=date("Y/m/d H:i:s");//新規投稿の日付取得
        $sendpass=$_POST["sendpass"];//入力フォーム・パスワード
        ###テーブルを作成する###
        $sql = "CREATE TABLE IF NOT EXISTS table51"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "ts TEXT,"
        . "ps TEXT"
        .");";
        $stmt = $pdo->query($sql);
        ###テーブルに書き込む###
        $sql = $pdo -> prepare("INSERT INTO table51 (name,comment,ts,ps) VALUES ('$name', '$comment', '$timestamp', '$sendpass')");
        $sql -> execute();
    }##投稿を削除する場合##
    elseif(!empty($_POST["delete"])&&!empty($_POST["ridpass"])){//もし削除する番号deleteと削除したい投稿番号のパスワードが記入されていたら
        ###変数を定義###
        $delete=$_POST["delete"];//削除フォームに入力された投稿番号
        $ridpass=$_POST["ridpass"];//削除フォーム・パスワード
        ###テーブルを取り出す###
        $sql="SELECT * FROM table51";//table51を抽出
        $result=$pdo->query($sql);
        foreach($result as $row){//各行の要素を抽出する
            $delID = $row["id"];
            $delPASS = $row["ps"];
            ###パスワードが正しいので操作###
            if($delID==$delete&&$delPASS==$ridpass){
                $deleteNo="" ;
                $sql = "DELETE FROM table51 where id=:id";
                //$sql = 'delete from table51 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                $stmt->execute();
                echo '<span style="color:red">削除しました</span>';
                echo "<hr>";
            } 
        }if($deleteNo!=""){//パスワードが一致しなかった場合
            echo '<span style="color:red">削除パスワードが違います</span>';
            echo "<hr>";
        }
    }##入力不備撃退シリーズ##
    ##投稿フォームにパスワード記入漏れの場合##
    if(!empty($_POST["name"])&&!empty($_POST["comment"])&&empty($_POST["sendpass"])){
        echo '<span style="color:red">パスワードを入力してください</span>';
        echo "<hr>";
    }##投稿フォームに名前記入漏れの場合（コメントまたはパスワードは記入されている）##
    elseif(empty($_POST["name"])&&(!empty($_POST["comment"])||isset($_POST["sendpass"]))){
        echo '<span style="color:red">名前を入力してください</span>';
        echo "<hr>";
    }##投稿フォームにコメント記入漏れの場合（名前またはパスワードは記入されている）##
    elseif(empty($_POST["comment"])&&(!empty($_POST["name"])&&isset($_POST["sendpass"]))){
        echo '<span style="color:red">コメントを入力してください</span>';
        echo "<hr>";
    }##削除フォームにパスワードが入力されなかった（投稿番号は入力された）場合##
    elseif(!empty($_POST["delete"])&&empty($_POST["ridpass"])){
        echo '<span style="color:red">削除したい投稿番号のパスワードを入力してください</span>';
        echo "<hr>";
    }##削除フォームに投稿番号が入力されなかった（パスワードは入力された場合）##
    elseif(empty($_POST["delete"])&&!empty($_POST["ridpass"])){
        echo '<span style="color:red">削除したい投稿番号を入力してください</span>';
        echo "<hr>";
    }##編集フォームにパスワードが入力されなかった（投稿番号は入力された）場合##
    elseif(!empty($_POST["editnum"])&&empty($_POST["editpass"])){
        echo '<span style="color:red">編集したい投稿番号のパスワードを入力してください</span>';
        echo "<hr>";
    }##編集フォームに投稿番号が入力されなかった（パスワードは入力された場合）##
    elseif(empty($_POST["editnum"])&&!empty($_POST["editpass"])){
        echo '<span style="color:red">編集したい投稿番号を入力してください</span>';
        echo "<hr>";
    }##パスワードが違うため編集できない場合##
    elseif($error!=""){//もし$errorが空じゃなかったら
        //つまり$edID(=投稿番号)が$editと，$edPASS(=パスワード)が$editpassのどちらか一方でも一致しなかったとき！
        echo '<span style="color:red">編集パスワードが違います</span>'; 
        echo "<hr>";
    }
?>
<h3>投稿一覧</h3>

<?php
    $sql="SELECT * FROM table51";
        $stmt=$pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            echo $row["id"]." ";
            echo $row["name"]." ";
            echo $row["comment"]." ";
            echo $row["ts"]." ";
            echo "<br>";      
        }
?>
</body>
</html>