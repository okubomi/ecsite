<?php

/**
 * @author 大久保満
 * XSS対策用のhtmlspesicalcharsラッパー関数
 * HTML内にphp変数の埋め込みを行う場合はこの関数を必ず使用すること。
 * 例）<input type="text" name="user_id" value="<? echo h($user_id) ?>" />
 *
 * @param string $str
 * @return htmlspecialchars($str, ENT_QUOTES, 'UTF-8') エスケープされた文字列
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// セッションの開始
@session_start();

// 登録ボタン押下時(POSTメソッドでリクエストが来たら)登録値の検証を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力値をセッションに格納する
    $_SESSION['register_id'] = $_POST['id'];
    $_SESSION['register_password'] = $_POST['password1'];
    $_SESSION['register_name'] = $_POST['name'];
    $_SESSION['register_address'] = $_POST['address'];
    $masked_password = str_repeat('*', strlen($_POST['password1']));

    /**
     * * 入力内容の検証を行う **
     */
    // エラーメッセージ格納用の配列を用意
    $error_message_list = array();
    // データベース接続
    $conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
    // 文字コード指定
    mysqli_set_charset($conn, 'utf8');
    // 入力されたIDでDBを検索するSQLを作成
    $sql = 'SELECT * FROM users WHERE user_id = \'' . $_SESSION['register_id'] . '\'';
    // クエリ実行
    $query_result = mysqli_query($conn, $sql);
    // クエリ実行の結果数(何件あったか)を取得する
    $result = mysqli_num_rows($query_result);
    // データベース接続を閉じる
    mysqli_close($conn);

    // $resultの結果が1件以上あったら登録済みのエラーメッセージを格納
    if ($result > 0) {
        $error_message_list[] = '既に登録済みのIDです。';
    }
    // 入力されたパスワード1と2(確認用)が一致しなかったら不一致のエラーメッセージを格納
    if ($_POST['password1'] !== $_POST['password2']) {
        $error_message_list[] = 'パスワードが一致しません。';
    }

    // エラーがあった場合
    if (! empty($error_message_list)) {
        // セッションにエラー情報を格納
        $_SESSION['error_list'] = $error_message_list;
        // 前画面に戻る
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>会員登録の確認</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3>以下の情報で登録しますか？</h3>
	<br />
	<form action="registerUserCommit.php" method="POST">
		<table>
			<tr>
				<th>会員ID</th>
				<td><?php echo h($_SESSION['register_id'])?></td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td><?php echo h($masked_password)?></td>
			</tr>
			<tr>
				<th>お名前</th>
				<td><?php echo h($_SESSION['register_name'])?></td>
			</tr>
			<tr>
				<th>ご住所</th>
				<td><?php echo h($_SESSION['register_address'])?></td>
			</tr>
		</table>
		<p>
			<input type="button" value="修正" onClick="history.back()" /> <input
				type="submit" value="登録" />
		</p>
	</form>
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>