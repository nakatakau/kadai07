<?php
// sessionのスタート
session_start();
// 検索数
$def = 20;
$_SESSION['color']  = "";
$_SESSION['search'] = "";
$_SESSION['page']   = "";
$_SESSION['cate']   = "";
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
  <link rel="stylesheet" href="../css/main.css">
  <!-- line-awesome -->
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <title>メインページ</title>
</head>

<body>
  <!-- ハック防止 -->
  <?php
  include('../func.php');
  ?>
  <!-- headerの読み込み -->
  <?php
  include('../parts/header.php');
  ?>
  <!-- main部分 -->
  <?php
  // ------------------------------------------------
  // 1.PDOの開始
  // ------------------------------------------------
  $pdo = pdo();
  // ------------------------------------------------
  // 2.SQLの呼び出し（全データ）
  // ------------------------------------------------
  // アイテムリストの呼び出し
  $stmt = $pdo->prepare("SELECT * FROM itemlist ORDER BY itemcode DESC");
  $stmt->execute();
  $i = 0;
  $array = array();
  // 全データを抽出
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $array[$i] = $row;
    $i++;
  }
  //json化
  $json_dataAll = json_encode($array, JSON_UNESCAPED_UNICODE);

  // ------------------------------------------------
  // 3.SQLの呼び出し（カテゴリー数のカウント）
  // ------------------------------------------------
  $stmt = $pdo->prepare("SELECT DISTINCT big_category, small_category,num FROM itemlist
                          INNER JOIN (SELECT small_category as cate, COUNT(name) as num
                          FROM itemlist GROUP BY small_category) as a
                          ON itemlist.small_category = a.cate");
  $stmt->execute();
  $i = 0;
  $array = array();
  // 全データを抽出
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $array[$i] = $row;
    $i++;
  }
  //json化
  $json_data_cate = json_encode($array, JSON_UNESCAPED_UNICODE);
  // --------------------------------------------------
  // 4.色の選択
  // --------------------------------------------------
  $stmt = $pdo->prepare("SELECT DISTINCT color FROM itemlist");
  $stmt->execute();
  $i = 0;
  $array = array();
  // 全データを抽出
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $array[$i] = $row;
    $i++;
  }
  //json化
  $color = json_encode($array, JSON_UNESCAPED_UNICODE);
  // --------------------------------------------------
  // 5.imgフォルダーの切替
  // --------------------------------------------------
  $root = $_SERVER['SERVER_NAME'] == "localhost" ? "local" : "web";   //フォルダー名
  $flg  = json_encode($root, JSON_UNESCAPED_UNICODE);
  ?>

  <div class="main_cont_flex">
    <aside id="item_catt_list">
      <p>カテゴリー</p>
    </aside>
    <main>
      <form action="main_1.php" method="get" id="form">
        <p>商品一覧</p>
        <div id="input">
          <i class="las la-search"></i>
          <p>条件絞り込み</p>
          <div id="search_num">
            <p>表示件数</p>
            <select id="search_num_select" name="search_num_select">
              <option value="20" selected>20件</option>
              <option value="40">40件</option>
              <option value="60">60件</option>
            </select>
          </div>
          <div id="color_select">
            <p>カラー</p>
            <select id="color_select_select" name="color_select_select">
              <option value="">全色</option>
            </select>
          </div>
        </div>
        <div></div>
        <div id="item_all_box">
        </div>
        <div id="item_list_num">
        </div>
        <div id="page">
          <ol id="page_count">
          </ol>
        </div>
        <input type="hidden" name="page" id="input_page">
        <input type="hidden" name="cate" id="input_cate">
      </form>
    </main>
  </div>
  <!-- footerの読み込み -->
  <?php
  include('../parts/footer.php');
  ?>
  <script src="../js/main.js"></script>
  <script>
    // ---------------------------------------------
    // phpデータをjsonで受け取り（color_selectエリア）
    // ---------------------------------------------
    const color_select_select = document.getElementById('color_select_select');
    const color_json = JSON.stringify(<?= $color ?>);
    const color = JSON.parse(color_json);
    let op = "<option value=''>全色</option>";
    for (let i = 0; i < color.length; i++) {
      const op1 = "<option value='" + color[i].color + "'>" + color[i].color + "</option>";
      op += op1;
    }
    color_select_select.innerHTML = op;
    // ---------------------------------------------
    // phpデータをjsonで受け取り（sideエリア）
    // ---------------------------------------------
    const json_cate = JSON.stringify(<?= $json_data_cate ?>);
    const cate_data = JSON.parse(json_cate);
    // ---------------------------------------------
    // JSONデータの展開とDOM
    // ---------------------------------------------
    // ulタグとタイトルの作成
    const ul1 = document.createElement('ul');
    ul1.id = "tops"
    const li_title1 = document.createElement('p');
    li_title1.style.pointerEvents = "none";
    li_title1.textContent = "tops";
    ul1.appendChild(li_title1);
    const ul2 = document.createElement('ul');
    ul2.id = "bottoms";
    const li_title2 = document.createElement('p');
    li_title2.style.pointerEvents = "none";
    li_title2.textContent = "bottoms";
    ul2.appendChild(li_title2);
    const ul3 = document.createElement('ul');
    ul3.id = "accessories";
    const li_title3 = document.createElement('p');
    li_title3.style.pointerEvents = "none";
    li_title3.textContent = "accessories";
    ul3.appendChild(li_title3);
    const ul4 = document.createElement('ul');
    ul4.id = "bag";
    const li_title4 = document.createElement('p');
    li_title4.style.pointerEvents = "none";
    li_title4.textContent = "bag";
    ul4.appendChild(li_title4);
    const item_catt_list = document.getElementById('item_catt_list');
    // asideに挿入
    item_catt_list.appendChild(ul1);
    item_catt_list.appendChild(ul2);
    item_catt_list.appendChild(ul3);
    item_catt_list.appendChild(ul4);
    // jsonデータの取得
    for (let i = 0; i < cate_data.length; i++) {
      const b_cate = cate_data[i].big_category;
      const s_cate = cate_data[i].small_category;
      const num = cate_data[i].num;
      const li = document.createElement('li');
      li.textContent = s_cate + "(" + num + ")";
      const target = document.getElementById(b_cate);
      target.appendChild(li);
    }
    // ---------------------------------------------
    // phpデータをjsonで受け取り（mainエリア）
    // ---------------------------------------------
    // ディレクトリの切替
    const flg_json = JSON.stringify(<?= $flg ?>);
    const flg = JSON.parse(flg_json);
    // phpよりjsonデータの受け取り
    const json = JSON.stringify(<?= $json_dataAll ?>);
    const data = JSON.parse(json);
    // ターゲットの取得
    const item_all_box = document.getElementById('item_all_box');
    const num = <?= $def ?>;
    for (let i = 0; i < num; i++) {
      // ------------------------------------------
      // divの作成
      // ------------------------------------------
      const div = document.createElement('div');
      div.className = "item_list_box";
      item_all_box.appendChild(div);
      // ------------------------------------------
      // formの作成
      // ------------------------------------------
      const form = document.createElement('form');
      form.method = "get";
      form.style.display = "none";
      form.action = "item.php";
      const input = document.createElement('input');
      input.type = "hidden";
      input.name = "itemcode";
      input.value = data[i].itemcode;
      form.appendChild(input);
      item_all_box.appendChild(form);
      // クリックイベント
      div.onclick = function() {
        form.submit();
      }
      // ------------------------------------------
      // imgの作成
      // ------------------------------------------
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
      const img = document.createElement('img');
      img.src = url;
      img.className = "item_img"
      div.appendChild(img);
      // ------------------------------------------
      // アイテム名の作成
      // ------------------------------------------
      const title = document.createElement('p');
      title.textContent = data[i].itemcode + "/" + data[i].name;
      title.className = "itemlist_title"
      div.appendChild(title);
      // ------------------------------------------
      // 値段の作成
      // ------------------------------------------
      const price = document.createElement('p');
      let money = Number(data[i].price);
      let money1 = money.toLocaleString();
      price.textContent = "¥" + money1;
      price.className = "price";
      div.appendChild(price);
    }
    // ---------------------------------------------
    // ページ送りの設定
    // ---------------------------------------------
    const page_count = document.getElementById("page_count");
    const page_list = Math.ceil(Number(data.length) / Number(num));
    let count;
    for (count = 1; count <= page_list; count++) {
      if (!(count > 3 && count < page_list)) {
        const page = document.createElement('li');
        page.textContent = count;
        page_count.appendChild(page);
      }
    }
    if (count > 4 && page_list > 4) {
      const dot = document.createElement('p');
      dot.style.pointerEvents = "none";
      dot.textContent = "‥"
      const last_second = page_count.children[2];
      last_second.after(dot);
    }
    // ---------------------------------------------
    // formの操作
    // ---------------------------------------------
    const form = document.getElementById('form');
    let input_page = document.getElementById('input_page');
    let search_num_select = document.getElementById('search_num_select');
    console.log(form);
    // カラーの送信
    color_select_select.addEventListener('change', () => {
      input_page.value = 1;
      form.submit();
    })
    //検索数の送信
    search_num_select.addEventListener('change', () => {
      input_page.value = 1;
      form.submit();
    })
    // カテゴリーの送信
    const parent = item_catt_list.childNodes;
    const input_cate = document.getElementById('input_cate');
    parent.forEach(target => {
      target.childNodes.forEach(child => {
        child.addEventListener('click', () => {
          const cate = child.textContent.split("(");
          input_cate.value = cate[0];
          input_page.value = 1;
          form.submit();
        })
      })
    });
    // ページの送信
    page_count.childNodes.forEach(target => {
      target.addEventListener('click', () => {
        input_page.value = target.textContent;
        form.submit();
      })
    })
  </script>
</body>

</html>
