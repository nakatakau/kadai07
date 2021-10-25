<?php
session_start();
include('../func.php');
date_default_timezone_set('Asia/Tokyo');
// 取得したjsonデータを格納
$json = $_POST['json'];
// jsonをデコード
$json = json_decode($json, true);
// 会員IDとpurchase_idを取得
$id   = $_SESSION['ID']; //会員ID
$purchase_id = date("Ymdhis") . $id; //購入ID
$purchase_date = date("Ymd");
// PDOの開始
$pdo = pdo();
$sql = "INSERT INTO purchase(purchase_id,userid,itemcode,itemname,size,quantity,price,color,img,flg,purchase_date)
            VALUES(:purchase_id,:userid,:itemcode,:itemname,:size,:quantity,:price,:color,:img,1,:purchase_date)";
$sql1 = "UPDATE stock SET quantity = quantity - :quantity WHERE keyname = :keyname";
// purchaseに情報をインサート
foreach ($json as $array) {
  // purchaseテーブルに追加
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':purchase_id', $purchase_id, PDO::PARAM_STR);
  $stmt->bindValue(':userid', $id, PDO::PARAM_INT);
  $stmt->bindValue(':itemcode', $array[1], PDO::PARAM_STR);
  $stmt->bindValue(':itemname', $array[0], PDO::PARAM_STR);
  $stmt->bindValue(':size', $array[3], PDO::PARAM_STR);
  $stmt->bindValue(':quantity', $array[4], PDO::PARAM_INT);
  $stmt->bindValue(':price', $array[5], PDO::PARAM_INT);
  $stmt->bindValue(':color', $array[6], PDO::PARAM_STR);
  $stmt->bindValue(':img', $array[2], PDO::PARAM_STR);
  $stmt->bindValue(':purchase_date', $purchase_date, PDO::PARAM_INT);
  $status = $stmt->execute();
  // stockテーブルから在庫を減算
  $keyname = $array[1] . $array[6] . $array[3];
  $stmt1 = $pdo->prepare($sql1);
  $stmt1->bindValue(':quantity', $array[4], PDO::PARAM_INT);
  $stmt1->bindValue(':keyname', $keyname, PDO::PARAM_STR);
  $status1 = $stmt1->execute();
}
$_SESSION['cart'] = [];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/cart.css">
  <!-- line-awesome -->
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <title>購入確認</title>
</head>

<body>
  <!-- headerの読み込み -->
  <?php
  include('../parts/header.php');
  ?>
  <!-- mainの記述 -->
  <main>
    <div class="thanks_message">
      <p>ご購入ありがとうございました。</p>
      <p>引き続き、お買い物をお楽しみください。</p>
    </div>
  </main>
  <!-- footerの読み込み -->
  <?php
  include('../parts/footer.php');
  ?>

</body>

</html>
