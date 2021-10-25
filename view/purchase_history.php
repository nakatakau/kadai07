<?php
session_start();
include('../func.php');
$id = $_SESSION['ID'];
// SQLの追加
$sql  = "SELECT * FROM purchase WHERE userid = $id ORDER BY purchase_id DESC";
$pdo = pdo();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}

$json = json_encode($array, JSON_UNESCAPED_UNICODE);
$root = $_SERVER['SERVER_NAME'] == "localhost" ? "local" : "web";   //フォルダー名
$flg  = json_encode($root, JSON_UNESCAPED_UNICODE);

// 注文番号照会用のSQL
$sql2  = "SELECT DISTINCT purchase_id, flg FROM purchase WHERE userid = $id ORDER BY purchase_id DESC";
$pdo2 = pdo();
$stmt2 = $pdo2->prepare($sql2);
$stmt2->execute();
$flg1 = array();
$flg2 = array();
$j = 0;
$k = 0;
while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  if ($row2['flg'] == 1) {
    $flg1[$j] = $row2;
    $j++;
  } else {
    $flg2[$k] = $row2;
    $k++;
  }
}
$flg1 = json_encode($flg1, JSON_UNESCAPED_UNICODE);
$flg2 = json_encode($flg2, JSON_UNESCAPED_UNICODE);

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
    <div id="history">
      <p class="main_title">注文状況照会</p>
      <div id="select_area">
        <p>発送状況</p>
        <select id="flg_select">
          <option value="1"> 未発送 </option>
          <option value="2"> 発送済 </option>
        </select>
        <p>注文番号</p>
        <select id="purchase_id">
          <option value=""></option>
        </select>
      </div>
      <div id="flg">
        <div class="circle1">
          <div id="a1" class="circle gray">✔︎</div>
          <p id="a2" class="text gray_text">注文完了</p>
        </div>
        <div id="a3" class="line1 gray"></div>
        <div class="circle2">
          <div id="a4" class="circle gray">✔︎</div>
          <p id="a5" class="text gray_text">発送処理</p>
        </div>
        <div id="a6" class="line2 gray"></div>
        <div class="circle3">
          <div id="a7" class="circle gray">✔︎</div>
          <p id="a8" class="text gray_text">発送完了</p>
        </div>
      </div>
      <div id="content">
      </div>
      <div id="total_price1">
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
    // 購入データの登録
    const json = JSON.stringify(<?= $json ?>);
    const data = JSON.parse(json);
    // 注文番号の照会
    const flg1_json = JSON.stringify(<?= $flg1 ?>);
    const flg2_json = JSON.stringify(<?= $flg2 ?>);
    const option1 = JSON.parse(flg1_json);
    const option2 = JSON.parse(flg2_json);
    const flg_select = document.getElementById('flg_select');
    const purchase_id = document.getElementById('purchase_id');
    // ページ読み込み時に実行
    option_change();

    function option_change() {
      purchase_id.innerHTML = "";
      for (let i = 0; i < option1.length; i++) {
        if (i == 0) {
          let option = document.createElement('option');
          option.value = "";
          option.textContent = "選択してください";
          purchase_id.appendChild(option);
        }
        let option = document.createElement('option');
        option.value = option1[i].purchase_id;
        option.textContent = option1[i].purchase_id;
        purchase_id.appendChild(option);
      }
    }
    console.log(data);
    // セレクトボックスに表示
    flg_select.addEventListener('change', (e) => {
      if (e.target.value == "1") {
        purchase_id.innerHTML = "";
        for (let i = 0; i < option1.length; i++) {
          if (i == 0) {
            a1.classList.add('gray');
            a2.classList.add('gray_text');
            a3.classList.add('gray')
            a4.classList.add('gray');
            a5.classList.add('gray_text');
            a6.classList.add('gray')
            a7.classList.add('gray');
            a8.classList.add('gray_text');
            let option = document.createElement('option');
            option.value = "";
            option.textContent = "選択してください";
            purchase_id.appendChild(option);
          }
          let option = document.createElement('option');
          option.value = option1[i].purchase_id;
          option.textContent = option1[i].purchase_id;
          purchase_id.appendChild(option);
        }
      } else {
        a1.classList.add('gray');
        a2.classList.add('gray_text');
        a3.classList.add('gray')
        a4.classList.add('gray');
        a5.classList.add('gray_text');
        a6.classList.add('gray')
        a7.classList.add('gray');
        a8.classList.add('gray_text');
        purchase_id.innerHTML = "";
        for (let i = 0; i < option2.length; i++) {
          if (i == 0) {
            let option = document.createElement('option');
            option.value = "";
            option.textContent = "選択してください";
            purchase_id.appendChild(option);
          }
          let option = document.createElement('option');
          option.value = option2[i].purchase_id;
          option.textContent = option2[i].purchase_id;
          purchase_id.appendChild(option);
        }
      }
    })
    // データの展開
    const a1 = document.getElementById('a1');
    const a2 = document.getElementById('a2');
    const a3 = document.getElementById('a3');
    const a4 = document.getElementById('a4');
    const a5 = document.getElementById('a5');
    const a6 = document.getElementById('a6');
    const a7 = document.getElementById('a7');
    const a8 = document.getElementById('a8');
    const content = document.getElementById('content');
    purchase_id.addEventListener('change', (e) => {
      // flgエリアの編集
      console.log(flg_select.value);
      if (flg_select.value == "1" && e.target.value != "") {
        // なければ追加
        a3.classList.add('gray')
        a4.classList.add('gray');
        a5.classList.add('gray_text');
        a6.classList.add('gray')
        a7.classList.add('gray');
        a8.classList.add('gray_text');
        //削除
        a1.classList.remove('gray');
        a2.classList.remove('gray_text');
        content.innerHTML = "";
      } else if (flg_select.value == "2" && e.target.value != "") {
        a1.classList.remove('gray');
        a2.classList.remove('gray_text');
        a3.classList.remove('gray')
        a4.classList.remove('gray');
        a5.classList.remove('gray_text');
        a6.classList.remove('gray')
        a7.classList.remove('gray');
        a8.classList.remove('gray_text');
        content.innerHTML = "";
      } else {
        a1.classList.add('gray');
        a2.classList.add('gray_text');
        a3.classList.add('gray')
        a4.classList.add('gray');
        a5.classList.add('gray_text');
        a6.classList.add('gray')
        a7.classList.add('gray');
        a8.classList.add('gray_text');
        content.innerHTML = "";
      }
      // コンテンツエリアの編集
      for (let i = 0; i < data.length; i++) {
        if (data[i].purchase_id == e.target.value) {
          const purchase_item = document.createElement('div');
          purchase_item.className = "purchase_item";
          content.appendChild(purchase_item);
          // カートエリアの作成
          const cart_box = document.createElement('div');
          cart_box.className = "purchase_box";
          purchase_item.appendChild(cart_box);
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
          cart_text1.className = "purchase_text1";
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
          cart_text2.className = "purchase_text2";
          cart_text_box.appendChild(cart_text2);
          // 金額、数量の追加
          const title2 = document.createElement('p');
          title2.textContent = "購入数";
          title2.className = "title2";
          cart_text2.appendChild(title2);
          const flex = document.createElement('div');
          flex.className = "flex";
          cart_text2.appendChild(flex);
          const price = document.createElement('p');
          price.className = "price";
          price.dataset.price = data[i].price;
          const money = Number(data[i].price).toLocaleString();
          price.textContent = "¥  " + money + "  (税込)";
          flex.appendChild(price);
          const quantity = document.createElement('p');
          quantity.dataset.quantity = data[i].quantity
          quantity.className = "quantity";
          quantity.textContent = data[i].quantity;
          flex.appendChild(quantity);
          const p = document.createElement('p');
          p.className = "ko";
          p.textContent = "個";
          flex.appendChild(p);
        }
      }
      const total_price1 = document.getElementById('total_price1');
      const sum = document.createElement('p');
      const price_sum = document.querySelectorAll('.price');
      const cart_quantity = document.querySelectorAll('.quantity');
      let total_money = 0;
      let i = 0
      price_sum.forEach(target => {
        const money = Number(target.dataset.price)
        const quantity = Number(cart_quantity[i].dataset.quantity);
        total_money = total_money + money * quantity;
        i++;
      })
      sum.className = "sum";
      sum.textContent = "合計金額：¥" + total_money.toLocaleString();
      total_price1.innerHTML = "";
      total_price1.appendChild(sum);
    })
  </script>
</body>

</html>
