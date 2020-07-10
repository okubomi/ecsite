<?php

/**
 * @author 大久保満
 * XSS対策用のhtmlspesicalcharsラッパー関数
 * HTML内にphp変数の埋め込みを行う場合はこの関数を必ず使用すること。
 * 例）<input type="text" name="user_id" value="<? echo h($user_id) ?>" />
 *
 * @param string $str
 * @return htmlspecialchars($str, ENT_QUOTES, 'UTF-8')エスケープされた文字列
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// セッション開始
@session_start();

// ログインしていなければ login.php に遷移
if (! isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

/**
 * * データベースからユーザ情報を削除する **
 */
// データベース接続
$conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
// 文字コード指定
mysqli_set_charset($conn, 'utf8');
// ユーザを削除するSQLを作成する
$sql = 'DELETE FROM users WHERE user_id =  \'' . $_SESSION['id'] . '\'';
// クエリ実行
$result = mysqli_query($conn, $sql);
// データベース接続を閉じる
mysqli_close($conn);

// 削除結果(true:成功 false:失敗)
if ($result) {
    // セッション用Cookieの破棄
    setcookie(session_name(), '', 1);
    // セッションファイルの破棄
    session_destroy();
} else {
    // 前画面に遷移
    header('Location: withdrawConfirm.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>退会完了</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3><?php echo h($_POST['name'])?>さん、退会処理を完了しました。</h3>
	<h3>またのご利用をお待ちしております。</h3>
	<a href='login.php'>ログイン画面</a>へ
	<br />
</body>
</html>