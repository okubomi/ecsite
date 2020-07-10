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

// ログインしていなければ login.php に遷移
if (! isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

/**
 * * データベースからユーザ情報を取得する **
 */
// データベース接続
$conn = mysqli_connect('localhost', 'root', 'P@ssw0rd', 'ecsite');
// 文字コード指定
mysqli_set_charset($conn, 'utf8');
// 検索に使用するSQLを作成する
$sql = 'SELECT * FROM users WHERE user_id = \'' . $_SESSION['id'] . '\'';
// クエリ実行
$query_result = mysqli_query($conn, $sql);
// クエリ実行結果を連想配列に格納する
$result = mysqli_fetch_assoc($query_result);
// データベース接続を閉じる
mysqli_close($conn);

// セッションにエラーメッセージ情報があったらローカル変数に格納して破棄する
if (! empty($_SESSION['error_list'])) {
    // ローカル変数に確認画面で格納した値を格納
    $error_message_list = $_SESSION['error_list'];
    $id = $_SESSION['register_id'];
    $name = $_SESSION['register_name'];
    $address = $_SESSION['register_address'];

    // セッションの破棄
    unset($_SESSION['error_list']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>会員情報</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3>会員情報</h3>
	<!-- エラーメッセージリストが空でない場合 -->
		<?php

if (! empty($error_message_list)) {
    echo "<p class=\"error\">\n";
    // エラーメッセージ表示
    foreach ($error_message_list as $message) {
        echo h($message) . "<br />\n";
    }
    echo "</p>\n";
}
?>
		<form name="form1" action="updateUserConfirm.php" method="POST"
		onsubmit="return formCheck()">
		<table>
			<tr>
				<th>会員ID</th>
				<td><?php echo h($result['user_id'])?></td>
			</tr>
			<tr>
				<th>パスワード(必須)</th>
				<td><input type="password" name="password1" class="password"
					title="パスワード" value="" /><br />
					<p id="notice-input-password-1" class="error"
						style="display: none;">パスワードは入力必須項目です</p></td>
			</tr>
			<tr>
				<th>パスワード(確認)(必須)</th>
				<td><input type="password" name="password2" class="password"
					title="パスワード(確認)" value="" /><br />
					<p id="notice-input-password-2" class="error"
						style="display: none;">パスワード(確認)は入力必須項目です</p></td>
			</tr>
			<tr>
				<th>お名前</th>
				<td><input type="text" name="name"
					value="<?php echo h($result['name'])?>" class="text" /></td>
				<p id="notice-input-name" class="error" style="display: none;">お名前は入力必須項目です</p>
			</tr>
			<tr>
				<th>ご住所</th>
				<td><input type="text" name="address"
					value="<?php echo h($result['address'])?>" class="text" /></td>
				<p id="notice-input-address" class="error" style="display: none;">ご住所は入力必須項目です</p>
			</tr>
		</table>
		<p>
			<input type="submit" value="変更" />
		</p>
	</form>
	<a href="withdrawConfirm.php">退会する</a>
	<br />
	<br />
	<a href="purchaseHistory.php">購入履歴（未実装）</a>へ
	<br />
	<a href="index.php">商品検索</a>へ
	<br />
</body>
<script>
		function formCheck(){
			var flag = 0;

			// パスワードが入力されているか
			if( document.form1.password1.value == "" ){
				flag = 1;
				document.getElementById( 'notice-input-password-1').style.display = "block";
			}else{
				document.getElementById( 'notice-input-password-1').style.display = "none";
			}
			// パスワード(確認)が入力されているか
			if( document.form1.password2.value == "" ){
				flag = 1;
				document.getElementById( 'notice-input-password-2').style.display = "block";
			}else{
				document.getElementById( 'notice-input-password-2').style.display = "none";
			}
			// お名前が入力されているか
			if( document.form1.name.value == "" ){
				flag = 1;
				document.getElementById( 'notice-input-name').style.display = "block";
			}else{
				document.getElementById( 'notice-input-name').style.display = "none";
			}
			// ご住所が入力されているか
			if( document.form1.address.value == "" ){
				flag = 1;
				document.getElementById( 'notice-input-address').style.display = "block";
			}else{
				document.getElementById( 'notice-input-address').style.display = "none";
			}

			if(flag){
				window . alert( '必須項目は全て入力して下さい。' );
				return false;
			}else{
				return true;
			}
		}
	</script>
</html>