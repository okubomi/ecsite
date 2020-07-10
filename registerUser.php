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
<title>会員登録</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<h3>会員情報を入力してください。</h3>
	<!-- エラーメッセージリストが空でない場合 -->
	<?php

if (! empty($error_message_list)) {
    echo "<p class=\"error\">\n";
    // エラーメッセージを表示
    foreach ($error_message_list as $message) {
        echo h($message) . "<br />";
    }
    echo "</p>\n";
}
?>
	<form name="form1" action="registerUserConfirm.php" method="POST"
		onsubmit="return formCheck()">
		<table>
			<tr>
				<th>会員ID(必須)</th>
				<td><input type="text" name="id" class="id" title="ID"
					value="<?php echo h(isset($id)?$id:'') ?>" /><br />
					<p id="notice-input-id" class="error" style="display: none;">会員IDは入力必須項目です</p>
				</td>
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
				<th>お名前(必須)</th>
				<td><input type="text" name="name" class="text" title="お名前"
					value="<?php echo h(isset($name)?$name:'') ?>" /><br />
					<p id="notice-input-name" class="error" style="display: none;">お名前は入力必須項目です</p>
				</td>
			</tr>
			<tr>
				<th>ご住所(必須)</th>
				<td><input type="text" name="address" class="text" title="ご住所"
					value="<?php echo h(isset($address)?$address:'') ?>" /><br />
					<p id="notice-input-address" class="error" style="display: none;">ご住所は入力必須項目です</p>
				</td>
			</tr>
		</table>
		<p>
			<input type="submit" value="登録確認" />


		<p>

	</form>
	<a href="index.php">商品検索</a>へ
	<br />

	<script>
		function formCheck(){
			var flag = 0;

			// 会員IDが入力されているか
			if( document.form1.id.value == "" ){
				flag = 1;
				document.getElementById( 'notice-input-id').style.display = "block";
			}else{
				document.getElementById( 'notice-input-id').style.display = "none";
			}
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
</body>
</html>