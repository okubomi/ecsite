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

// ログインしていなければ /login.php に遷移
if (! isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// リクエストメソッドがPOSTのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 受け取った商品詳細情報を配列に格納
    $cart_array = array();
    $cart_array['item_id'] = $_POST['item_id'];
    $cart_array['name'] = $_POST['name'];
    $cart_array['color'] = $_POST['color'];
    $cart_array['manufacturer'] = $_POST['manufacturer'];
    $cart_array['price'] = $_POST['price'];
    $cart_array['amount'] = $_POST['amount'];

    // 同じ商品が既にカートセッションに存在する場合
    if (isset($_SESSION['cart'][$cart_array['item_id']])) {
        // 数量の加算のみを行う
        $_SESSION['cart'][$cart_array['item_id']]['amount'] += $cart_array['amount'];
    } else {
        // カート情報をセッションに格納する
        $_SESSION['cart'][$cart_array['item_id']] = $cart_array;
    }
}

// カート内の合計値を算出
$total_price = 0;
if (! empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $row) {
        $total_price += $row['price'] * $row['amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ショッピングカート</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<img border="0" src="3_White_logo_on_color1_181x73.png" width="250"
		height="100" alt="大久保満 靴かばん店">

	<h3>ショッピングカート内の商品一覧</h3>
	<br />
	<!-- カートセッションが空でない場合 -->
		<?php

if (! empty($_SESSION['cart'])) {
    echo "<table>\n";
    echo "		<tr>\n";
    echo "			<th>商品名</th>\n";
    echo "			<th>商品の色</th>\n";
    echo "			<th>メーカー名</th>\n";
    echo "			<th>単価</th>\n";
    echo "			<th>数量</th>\n";
    // echo " <th></th>\n";
    echo "		</tr>\n";
    // カートセッションの商品情報を表示
    foreach ($_SESSION['cart'] as $row) {
        echo "		<tr>\n";
        echo "			<td>" . h($row['name']) . "</td>\n";
        echo "			<td>" . h($row['color']) . "</td>\n";
        echo "			<td>" . h($row['manufacturer']) . "</td>\n";
        echo "			<td>" . h($row['price']) . "</td>\n";
        echo "			<td>" . h($row['amount']) . "</td>\n";
        // echo " <td><a href=\"removeFromCartConfirm.html?item_id=".h($row['item_id'])."\">削除</a></td>\n";
        echo "		</tr>\n";
    }
    echo "		</table>\n";
    echo "		合計 " . h($total_price) . "円<br />\n";
    echo "		<form action=\"\" method=\"POST\">\n"; // purchaseConfirm.html 未実装
    echo "			[購入する]"; // <input type=\"submit\" value=\"購入する\" />\n
    echo "		</form>\n";
    // カートセッションが空の場合はメッセージを表示
} else {
    echo "<p>カート内に商品がありません。</p>\n";
}
?>
		<br />
	<a href="index.php">商品検索</a>へ
	<br />
</body>
</html>
