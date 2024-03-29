<?php

// XSS対策
function h($str){
   return htmlspecialchars($str, ENT_QUOTES);
}



//1.  DB接続します
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError'.$e->getMessage());
}

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_bm_table;");
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
    // elseの中は、SQL実行成功した場合
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // stftの部分で、1行取得して、それをresultに配列として格納している。中身がなくなったら終了。
    // $view .= '<tr><td>' . $result['id'] . '</td><td>' . h($result['bookName']) . '</td><td>' . h($result['bookURL']) . '</td><td>' . h($result['bookComment']) . '</td><td>' . $result['date'] . '</td></tr>';

    $bookName = h($result['bookName']);
    $bookURL = h($result['bookURL']);
    $bookComment = h($result['bookComment']);

    $view .= "
        <tr>
            <td>{$result['id']}</td>
            <td>{$bookName}</td>
            <td>{$bookURL}</td>
            <td>{$bookComment}</td>
            <td>{$result['date']}</td>
        </tr>";
    // viewにresultの中のnameを格納
  }

}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ブックマークされた書籍一覧</title>
    <link rel="stylesheet" href="css/range.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
    div{
        padding: 10px;
        font-size: 16px;
    }
    h1{
        font-size: 24px;
        color: white;
    }

    table{
        border-collapse: collapse;
    }
    th,
    td {
        border: 1px solid #333;
        padding: 5px 10px;
    }
    thead,
    tfoot {
        background-color: #333;
        color: #fff;
    }
    th[scope="col"] {
        background-color: #696969;
        color: #fff;
    }
    th[scope="row"] {
        background-color: #d7d9f2;
    }

    </style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
        <h1>ブックマークアプリ</h1>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class>
        <table>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">書籍名</th>
            <th scope="col">書籍URL</th>
            <th scope="col">書籍コメント</th>
            <th scope="col">登録日時</th>
        </tr>
        <?= $view ?>
        </table>
    </div>
    <div class="wire_area">
        <button class="lead-wire" onclick="location.href='./index.php'">書籍を追加登録する</button>
    </div>
</div>


<!-- Main[End] -->

</body>
</html>
