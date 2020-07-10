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

// ログインユーザ名を取得(ログインされていない場合は"ゲスト")
$login_user = ! empty($_SESSION['name']) ? $_SESSION['name'] : 'ゲスト';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>商品検索</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

	<img border="0" src="3_White_logo_on_color1_181x73.png" width="250" height="100"
		alt="大久保満 靴かばん店">

	<h1>ようこそ、<?php echo h($login_user)?> さん</h1>
	<h3>商品の検索を行います。</h3>
	<br />
	<form action="searchResult.php" method="GET">
		キーワード<br /> <input type="text" name="keyword" /><br /> カテゴリ<br /> <select
			name="category">
			<option selected value="0">すべて</option>
			<option value="1">帽子</option>
			<option value="2">鞄</option>
		</select><br /> <input type="submit" value="検索" /><br />
	</form>
	<a href="cart.php">ショッピングカートを見る</a>
	<br />
	<br />

	<!-- ログイン済みかどうかで表示制御を行う -->
	<?php

if (! empty($_SESSION['id'])) {
    echo "<a href=\"updateUser.php\">会員情報の変更</a><br />\n";
    echo "<a href=\"logout.php\">ログアウト</a>\n";
} else {
    echo "<a href=\"login.php\">ログイン</a><br /><br />\n";
}
?>
</body>
</html>
