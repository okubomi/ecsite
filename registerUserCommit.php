<?php
/**
 * @author 大久保満
 */

// セッションの開始
@session_start();

// 登録ボタン押下時(POSTメソッドでリクエストが来たら)データベースに登録を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // セッションから登録情報を取得する(ついでにSQL文が見やすくなるように''で囲う)
    $id = $_SESSION['register_id'];
    $password = $_SESSION['register_password'];
    $name = $_SESSION['register_name'];
    $address = $_SESSION['register_address'];
    // データベース接続
    $conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
    // 文字コード指定
    mysqli_set_charset($conn, 'utf8');
    // 入力された情報をDBに登録するSQLを作成
    $sql = 'INSERT INTO users VALUES(\'' . $id . '\',\'' . $password . '\',\'' . $name . '\',\'' . $address . '\')';
    // クエリ実行
    $result = mysqli_query($conn, $sql);
    // データベース接続を閉じる
    mysqli_close($conn);

    // セッション情報の削除
    unset($_SESSION['register_id'], $_SESSION['register_password'], $_SESSION['register_name'], $_SESSION['register_address']);
}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>会員登録の完了</title>
<link rel='stylesheet' type='text/css' href='style.css' />
</head>
<body>
	<h3>会員登録が完了しました。</h3>
	<br />
	<a href='login.php'>ログイン</a>
	<br />
	<a href='index.php'>商品検索</a>へ
	<br />
</body>
</html>