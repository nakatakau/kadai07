<?php
  session_start();
  $key = $_GET['key'];
  unset($_SESSION['cart'][$key]);
  $message = "商品を削除しました";
  echo $message;
?>
