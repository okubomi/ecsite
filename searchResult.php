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
$req_keyword = $_GET['keyword'];
$req_category = $_GET['category'];
// カテゴリ名表示用の連想配列作成switch文(プルダウンのvalue値でカテゴリ名を取得出来る様にする)
switch ($req_category) {
    case '1':
        $category[$req_category] = '帽子';
        break;
    case '2':
        $category[$req_category] = '鞄';
        break;
    default:
        $category[$req_category] = 'すべて';
        break;
}

/**
 * * データベースから検索キーワードに一致する商品情報を取得する **
 */
// データベース接続
$conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
// 文字コード指定
mysqli_set_charset($conn, 'utf8');
// 検索に使用するSQLを作成する(tip! WHERE 1=1とすることで、条件分岐でのAND結合が楽になる!)
$sql = 'SELECT item_id, name, manufacturer, color, price FROM items WHERE 1 = 1';
// 検索キーワードの入力、選択があった場合はSQLの検索条件に追記する
if (isset($req_keyword) && $req_keyword !== "") {
    $sql .= ' AND name LIKE \'%' . addcslashes($req_keyword, '\_%') . '%\'';
    // WHERE 1 = 1 AND name LIKE '% $req_keyword %'
}
if (isset($req_category) && $req_category > 0) {
    $sql .= ' AND category_id = ' . (int) $req_category;
    // WHERE 1 = 1 AND category_id = $req_category
}
// クエリ実行
$query_result = mysqli_query($conn, $sql);
// クエリ実行結果を連想配列に格納する
$result = array();
while ($row = mysqli_fetch_assoc($query_result)) {
    array_push($result, $row);
}
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

	<h3>
		<!-- 検索キーワードが空白の場合は"なし"と表示する -->
        キーワード：<?php echo h(($req_keyword!=='')?$req_keyword:'なし')?><br />
        カテゴリ：<?php echo h($category[$req_category])?><br /> の検索結果
	</h3>
	<br />
	<!-- 検索結果が1つでもあった場合は取得した結果を表示 -->
    <?php

if (! empty($result)) {
        echo "<table>\n";
        echo "    <tr>\n";
        echo "        <th>商品名</th>\n";
        echo "        <th>商品の色</th>\n";
        echo "        <th>メーカー名</th>\n";
        echo "        <th>価格</th>\n";
        echo "    </tr>\n";
        // foreachで取得した結果を回して一覧表を作成
        foreach ($result as $row) {
            echo "    <tr>\n";
            echo "        <td><a href=\"itemDetail.php?item_id=" . h($row['item_id']) . "\">" . h($row['name']) . "</a></td>\n";
            echo "        <td>" . h($row['color']) . "</td>\n";
            echo "        <td>" . h($row['manufacturer']) . "</td>\n";
            echo "        <td>" . h($row['price']) . "円</td>\n";
            echo "    </tr>\n";
        }
        echo "</table>\n";
        // 検索結果が0の場合はメッセージを表示
    } else {
        echo "<p>検索条件に合う商品はありません。</p>\n";
    }
    ?>
    <br />
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>