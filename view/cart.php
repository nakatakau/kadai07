<?php
session_start();
include('../func.php');
//POSTデータの受信
if (isset($_POST['img']) && isset($_POST['size'])) {
  $img = $_POST['img'];
  $size = $_POST['size'];
  $key = $size . $img;
  // セッションカートにidを追加し、配列を登録
  $array = [];
  $i = 0;
  $_SESSION['cart'][$key] = array($img, $size);
}
// SQLの追加
$sql  = "SELECT itemlist.itemcode, name, big_category, small_category, gender,price, img, itemlist.color, size,quantity
            FROM itemlist LEFT JOIN stock ON itemlist.itemcode = stock.itemcode AND itemlist.color = stock.color WHERE img = :a AND size = :b";
$cart = [];
$i = 0;
// 配列の展開
foreach ($_SESSION['cart'] as $a) {
  $pdo = pdo();
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':a', $a[0], PDO::PARAM_INT);
  $stmt->bindValue(':b', $a[1], PDO::PARAM_INT);
  $result = $stmt->execute();
  while ($row =  $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['img'] == $a[0] && $row['size'] == $a[1]) {
      $cart[$i] = $row;
      $i++;
    }
  };
}

$json = json_encode($cart, JSON_UNESCAPED_UNICODE);
$root = $_SERVER['SERVER_NAME'] == "localhost" ? "local" : "web";   //フォルダー名
$flg  = json_encode($root, JSON_UNESCAPED_UNICODE);
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
    <div class="cart" id="cart">
      <p class="cart_title">ショッピングカート</p>
    </div>
    <div class="flex1">
      <div id="total_price">
      </div>
      <div id="purchase">
      </div>
    </div>
  </main>
  <!-- footerの読み込み -->
  <?php
  include('../parts/footer.php');
  ?>
  <!-- axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    // ディレクトリの切替
    const flg_json = JSON.stringify(<?= $flg ?>);
    const flg = JSON.parse(flg_json);
    // カートデータの登録
    const json = JSON.stringify(<?= $json ?>);
    const data = JSON.parse(json);
    console.log(data);
    const cart = document.getElementById('cart');
    if (!data.length == 0) {
      for (let i = 0; i < data.length; i++) {
        // カートエリアの作成
        const cart_box = document.createElement('div');
        cart_box.className = "cart_box";
        cart.appendChild(cart_box);
        // 画像の表示
        const img = document.createElement('img');
        img.className = "cart_img";
        // imgのタイトルを取得
        let img_name = data[i].img;
        // 空白は%20に変換
        img_name = img_name.replace(" ", "%20");
        // severによってルートの切替
        let root;
        if (flg == "local") {
          root = "../../kadai02-1/img/";
        } else {
          root = "../../kadai07-1/img/";
        }
        const url = root + img_name;
        img.src = url;
        img.dataset.fname = data[i].img;
        cart_box.appendChild(img);
        // テキストメニュー
        const cart_text_box = document.createElement('div');
        cart_text_box.className = "cart_text_box";
        cart_box.appendChild(cart_text_box);
        // テキスト（商品名、カラー、サイズ）
        const cart_text1 = document.createElement('div');
        cart_text1.className = "cart_text1";
        cart_text_box.appendChild(cart_text1);
        // 商品名、カラー、サイズの追加
        const title1 = document.createElement('p');
        title1.textContent = "商品情報";
        title1.className = "title1";
        cart_text1.appendChild(title1);
        const cart_text_title = document.createElement('p');
        cart_text_title.className = "cart_text_title";
        cart_text_title.dataset.name = data[i].name;
        cart_text_title.dataset.itemcode = data[i].itemcode;
        cart_text_title.textContent = data[i].name + "/" + data[i].itemcode;
        cart_text1.appendChild(cart_text_title);
        const color = document.createElement('p');
        color.className = "color";
        color.dataset.color = data[i].color;
        color.textContent = "カラー：" + data[i].color;
        cart_text1.appendChild(color);
        const size = document.createElement('p');
        size.className = "size";
        size.dataset.size = data[i].size;
        size.textContent = "サイズ：" + data[i].size;
        cart_text1.appendChild(size);
        // テキスト（金額、数量）
        const cart_text2 = document.createElement('div');
        cart_text2.className = "cart_text2";
        cart_text_box.appendChild(cart_text2);
        // 金額、数量の追加
        const title2 = document.createElement('p');
        title2.textContent = "購入数";
        title2.className = "title2";
        cart_text2.appendChild(title2);
        const flex = document.createElement('div');
        flex.className = "flex";
        cart_text2.appendChild(flex);
        const stock_text = document.createElement('p');
        stock_text.className = "stock_text";
        stock_text.textContent = "(現在の在庫数：" + data[i].quantity + "個)";
        cart_text2.appendChild(stock_text);
        const price = document.createElement('p');
        price.className = "price";
        price.dataset.price = data[i].price;
        const money = Number(data[i].price).toLocaleString();
        price.textContent = "¥  " + money + "  (税込)";
        flex.appendChild(price);
        const quantity = document.createElement('input');
        quantity.type = "number";
        quantity.name = "number";
        quantity.className = "quantity";
        quantity.min = "1";
        quantity.max = data[i].quantity;
        quantity.value = "1";
        quantity.addEventListener('change', () => {
          if (!(Number(quantity.value) > Number(quantity.max))) {
            total();
          } else {
            alert('在庫数を超えています。')
          }
        })
        flex.appendChild(quantity);
        const p = document.createElement('p');
        p.className = "ko";
        p.textContent = "個";
        flex.appendChild(p);
        // 削除ボタン
        const cart_text3 = document.createElement('div');
        cart_text3.className = "cart_text3";
        cart_text_box.appendChild(cart_text3);
        const delete_btn = document.createElement('button');
        delete_btn.className = "delete_btn";
        delete_btn.textContent = "削除";
        delete_btn.dataset.id = data[i].size + data[i].img;
        delete_btn.addEventListener('click', (e) => {
          if (confirm('この商品をカートから削除しますか?')) {
            const key_name = e.target.dataset.id;
            const data = {
              key: key_name
            }
            axios.get("delete_cart.php", {
              params: data
            }).then(function(response) {
              const message = response.data;
              alert(message);
              window.location.href = 'cart.php';
            }).catch(function(error) {
              alert(error);
            })
          }
        })
        cart_text3.appendChild(delete_btn);
      }
    } else {
      const nothing = document.createElement('p');
      nothing.className = "nothing";
      nothing.textContent = "現在カートに商品はございません。"
      cart.appendChild(nothing);
    }
    // 合計金額、購入ボタンを追加
    // 購入ボタン
    const purchase = document.getElementById('purchase');
    const purchase_btn = document.createElement('button');
    purchase_btn.id = "purchase_btn";
    purchase_btn.textContent = "購入";
    purchase_btn.addEventListener('click', () => {
      if (data.length == 0) {
        alert('カートに商品がありません')
      } else {
        if (confirm('カートにある商品を購入しますか？')) {
          const img_name = document.querySelectorAll('.cart_img');
          const size = document.querySelectorAll('.size');
          const quantity = document.querySelectorAll('.quantity');
          const itemcode = document.querySelectorAll('.cart_text_title');
          console.log(itemcode);
          const price = document.querySelectorAll('.price');
          const color = document.querySelectorAll('.color');
          let array = [];
          for (let x = 0; x < img_name.length; x++) {
            array[x] = [
              itemcode[x].dataset.name,
              itemcode[x].dataset.itemcode,
              img_name[x].dataset.fname,
              size[x].dataset.size,
              quantity[x].value,
              price[x].dataset.price,
              color[x].dataset.color
            ]
          }
          console.log(array);
          const json = JSON.stringify(array);
          console.log(json);
          const form = document.createElement('form');
          form.style.display = "none";
          form.action = "purchase.php";
          form.method = "post";
          const input = document.createElement('input');
          input.type = "hidden";
          input.name = "json";
          input.value = json;
          form.appendChild(input);
          purchase.appendChild(form);
          form.submit();
        }
      }
    })
    purchase.appendChild(purchase_btn);
    // トータルを算出
    const total_price = document.getElementById('total_price');

    function total() {
      const sum = document.createElement('p');
      const price_sum = document.querySelectorAll('.price');
      const cart_quantity = document.querySelectorAll('.quantity');
      let total_money = 0;
      let i = 0
      price_sum.forEach(target => {
        const money = Number(target.dataset.price)
        const quantity = Number(cart_quantity[i].value);
        total_money = total_money + money * quantity;
        i++;
      })
      sum.className = "sum";
      sum.textContent = "合計金額：¥" + total_money.toLocaleString();
      total_price.innerHTML = "";
      total_price.appendChild(sum);
    }
    total();
  </script>
</body>

</html>
