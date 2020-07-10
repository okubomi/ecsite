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

// セッション開始
@session_start();
$http_response_code = '200';

// ログインしていれば index.php に遷移
if (! empty($_SESSION['id'])) {
    header('Location: /');
    exit();
}

// リクエストメソッドがPOSTのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力されたユーザIDとパスワードを取得
    $id = $_POST['id'];
    $password = $_POST['password'];

    // データベースから入力されたIDのユーザ情報を取得する
    if (isset($id)) {
        // データベース接続
        $conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
        // 文字コード指定
        mysqli_set_charset($conn, 'utf8');
        // 検索に使用するSQLを作成する
        $sql = 'SELECT user_id, password, name FROM users WHERE user_id = \'' . $id . '\'';
        // クエリ実行
        $query_result = mysqli_query($conn, $sql);
        // クエリ実行結果を連想配列に格納する
        $result = mysqli_fetch_assoc($query_result);
        // データベース接続を閉じる
        mysqli_close($conn);
    }

    // ユーザ情報が取得出来ている 且つ
    // DBに登録されているパスワードと入力されたパスワードが一致している場合
    if (! empty($result) && $result['password'] === $password) {
        var_dump('テスト表示：' . $result, $password);
        // セッション情報にユーザIDとユーザ名をセット
        $_SESSION['id'] = $result['user_id'];
        $_SESSION['name'] = $result['name'];
        // ログイン完了後に index.php に遷移
        header('Location: index.php');
        // exit;
    } else {
        unset($_SESSION['id'], $_SESSION['name']);
    }

    // 認証が失敗したときはレスポンスコードに403を設定する
    // [403_Forbidden]
    $http_response_code = '403';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>会員ログイン</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3>ログインしてください。</h3>
	<br />
	<!-- action属性を指定しないことで、自分自身(login.php)を呼び出す -->
	<form action="" method="POST">
		<table>
			<tr>
				<th>会員ID</th>
				<td><input type="text" class="id" name="id" /></td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td><input type="password" class="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="ログイン" /></td>
			</tr>
		</table>
	</form>
	<!-- HTTPレスポンスコードが403(認証エラー)の場合はエラーメッセージを表示 -->
	<?php

if ($http_response_code === '403') {
    echo "<p style=\"color: red;\">ユーザ名またはパスワードが違います</p>";
}
?>
	<a href="registerUser.php">会員登録</a>
	<br />
	<a href="index.php">商品検索</a>
	<br />
</body>
</html>