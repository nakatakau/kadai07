<?php
session_start();
// セッションの削除
$_SESSION = array(); //空の配列を格納
session_destroy();
// メインセッションへ戻る
header('Location: main.php');
