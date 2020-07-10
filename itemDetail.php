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

// HTTPリクエストデータの取得
$req_item_id = $_GET['item_id'];

/**
 * * DBから商品詳細情報を取得する **
 */
// データベース接続
$conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
// 文字コード指定
mysqli_set_charset($conn, 'utf8');
// 検索に使用するSQLを作成する
$sql = 'SELECT * FROM items WHERE item_id = \'' . $req_item_id . '\'';
// クエリ実行
$query_result = mysqli_query($conn, $sql);
// クエリ実行結果を連想配列に格納する
$result = mysqli_fetch_assoc($query_result);
// データベース接続を閉じる
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>検索結果</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<img border="0" src="3_White_logo_on_color1_181x73.png" width="250"
		height="100" alt="大久保満 靴かばん店">

	<h3>商品の詳細表示</h3>
	<br />
	<table>
		<tr>
			<th>商品名</th>
			<td><?php echo h($result['name'])?></td>
		</tr>
		<tr>
			<th>商品の色</th>

			<td><?php echo h($result['color'])?></td>
		</tr>
		<tr>
			<th>メーカー名</th>
			<td><?php echo h($result['manufacturer'])?></td>
		</tr>
		<tr>
			<th>価格</th>
			<td><?php echo h($result['price'])?>円</td>
		</tr>
		<tr>
			<th>在庫数</th>
			<td><?php echo h($result['stock'])?>個</td>
		</tr>
	</table>

	<form action="cart.php" method="POST">
		数量 <select name="amount">
			<option selected value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select><br />
		<!-- hidden属性で商品詳細情報をカート画面に送る -->
		<input type="hidden" name="item_id"
			value="<?php echo h($req_item_id)?>" /> <input type="hidden"
			name="name" value="<?php echo h($result['name'])?>" /> <input
			type="hidden" name="color" value="<?php echo h($result['color'])?>" />
		<input type="hidden" name="manufacturer"
			value="<?php echo h($result['manufacturer'])?>" /> <input
			type="hidden" name="price" value="<?php echo h($result['price'])?>" />
		<input type="submit" value="ショッピングカートに入れる" /><br />
	</form>
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>