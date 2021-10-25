<?php
  session_start();
  include('../func.php');
  // POST
  $id = $_POST['id1'];
  // ------------------------------------------------
  // 1.PDOの開始
  // ------------------------------------------------
  $pdo = pdo();
  // 該当のキーを削除する
  $stmt = $pdo -> prepare('DELETE FROM registration WHERE id = :id');
  $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
  // デリート実行
  $flg = $stmt -> execute();
  // セッションの削除
  $_SESSION = array(); //空の配列を格納
  session_destroy();
  // メインセッションへ戻る
  header('Location: main.php');
?>
