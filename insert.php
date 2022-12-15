
<?php

//1. POSTデータ取得
$bookName = $_POST['bookName'];
$bookURL = $_POST['bookURL'];
$bookComment = $_POST['bookComment'];


//2. DB接続します
try {
  //ID:'root', Password: xamppは 空白 ''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//３．データ登録SQL作成

// 1. SQL文を用意
$stmt = $pdo->prepare("INSERT INTO
     gs_bm_table(id, bookName, bookURL, bookComment, date) 
     VALUES(NULL, :bookName, :bookURL, :bookComment, sysdate() ); ");

//  2. バインド変数を用意 ※SQLインジェクション防止。直接SQLに$nameを入れても動くことには動く
// Integer 数値の場合 PDO::PARAM_INT
// String文字列の場合 PDO::PARAM_STR

$stmt->bindValue(':bookName', $bookName, PDO::PARAM_STR);
$stmt->bindValue(':bookURL', $bookURL, PDO::PARAM_STR);
$stmt->bindValue(':bookComment', $bookComment, PDO::PARAM_STR);

//  3. 実行
$status = $stmt->execute();
// statusには、うまくいったらtrue、だめだったらfaleseが返ってくる

//４．データ登録処理後
if($status === false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit('ErrorMessage:'.$error[2]);
}else{
    header('Location: complete.php');
}
?>
