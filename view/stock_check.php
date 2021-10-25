<?php
include("../func.php");
// 受け取るデータ
$img = $_GET['img'];
$pdo = pdo();
$sql  = "SELECT itemlist.itemcode, name, big_category, small_category, gender,price, img, itemlist.color, size,quantity FROM itemlist LEFT JOIN stock ON itemlist.itemcode = stock.itemcode AND itemlist.color = stock.color WHERE img = :img";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':img', $img, PDO::PARAM_STR);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}
$json = json_encode($array, JSON_UNESCAPED_UNICODE);
//作成したJSON文字列をリクエストしたファイルに返す
echo $json;
exit;
