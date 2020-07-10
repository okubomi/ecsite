<?php
/**
 * @author 大久保満
 */

// セッション開始
@session_start();

// セッション用Cookieの破棄
setcookie(session_name(), '', 1);

// セッションファイルの破棄
session_destroy();

// ログアウト完了後に login.php に遷移
header('Location: login.php');
?>