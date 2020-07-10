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

// リクエストメソッドがPOSTのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // セッションから登録情報を取得する
    $password = $_SESSION['update_password'];
    $name = $_SESSION['update_name'];
    $address = $_SESSION['update_address'];
    // データベースのユーザ情報を更新する
    // データベース接続
    $conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
    // 文字コード指定
    mysqli_set_charset($conn, 'utf8');
    // 更新するSQLを作成する
    $sql = 'UPDATE users SET password = \'' . $password . '\', name = \'' . $name . '\', address = \'' . $address . '\' WHERE user_id = \'' . $_SESSION['id'] . '\'';
    // クエリ実行
    $result = mysqli_query($conn, $sql);
    // データベース接続を閉じる
    mysqli_close($conn);

    // セッションに格納している名前を変更
    $_SESSION['name'] = $name;

    // セッション情報の削除
    unset($_SESSION['update_password'], $_SESSION['update_name'], $_SESSION['update_address']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>会員情報の更新完了</title>
<link rel='stylesheet' type='text/css' href='style.css' />
</head>
<body>
	<h3>以下の会員情報で更新しました。</h3>
	<br />
	<table>
		<tr>
			<th>会員ID</th>
			<td><?php echo h($_SESSION['id'])?></td>
		</tr>
		<tr>
			<th>お名前</th>
			<td><?php echo h($name)?></td>
		</tr>
		<tr>
			<th>ご住所</th>
			<td><?php echo h($address)?></td>
		</tr>
	</table>
	<br />
	<a href='index.php'>商品検索</a>へ
	<br />
</body>
</html>