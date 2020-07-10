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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>退会確認</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3><?php echo h($_SESSION['name'])?>さん、本当に退会しますか？</h3>
	<form action="withdrawCommit.php" method="POST">
		<input type="hidden" name="name"
			value="<?php echo h($_SESSION['name'])?>" /> <input type="submit"
			value="退会" />
	</form>
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>