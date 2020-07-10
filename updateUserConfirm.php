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

// 入力値を取得する(ついでにSQL文が見やすくなるように''で囲う)
// 入力値をセッションに格納する
$_SESSION['update_password'] = $_POST['password1'];
$_SESSION['update_name'] = $_POST['name'];
$_SESSION['update_address'] = $_POST['address'];
// パスワードはアスタリスクでマスクする
$masked_password = str_repeat('*', strlen($_POST['password1']));

// リクエストメソッドがPOSTのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
<title>会員情報の更新</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3>以下の会員情報で更新しますか？</h3>
	<br />
	<!-- action属性指定なしで自分(updateUserConfirm.php)を呼び、入力値のチェックを行う -->
	<form action="updateUserCommit.php" method="POST">
		<table>
			<tr>
				<th>会員ID</th>
				<td><?php echo h($_SESSION['id'])?></td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td><?php echo h($masked_password)?></td>
			</tr>
			<tr>
				<th>お名前</th>
				<td><?php echo h($_POST['name'])?></td>
			</tr>
			<tr>
				<th>ご住所</th>
				<td><?php echo h($_POST['address'])?></td>
			</tr>
		</table>
		<p>
			<input type="submit" value="変更" />
		</p>
	</form>
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>