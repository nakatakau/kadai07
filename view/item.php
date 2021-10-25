  <?php
  // sessionのスタート
  session_start();
  // メインphpの読み込み
  include('../func.php');

  // ------------------------------------------------
  // 1.PDOの開始とgetの受け取り
  // ------------------------------------------------
  $pdo = pdo();
  $item = $_GET['itemcode'];
  $sql  = "SELECT * FROM itemlist WHERE itemcode = :item";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':item', $item, PDO::PARAM_STR);
  $stmt->execute();

  // ------------------------------------------------
  // 2.取得したデータを配列に格納
  // ------------------------------------------------
  $i = 0;
  $array = array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $array[$i] = $row;
    $i++;
  }
  // ------------------------------------------------
  // 3.配列をJSONに変更
  // ------------------------------------------------
  $json = json_encode($array, JSON_UNESCAPED_UNICODE);
  // ------------------------------------------------
  // 4.imgフォルダーの切替
  // ------------------------------------------------
  $root = $_SERVER['SERVER_NAME'] == "localhost" ? "local" : "web";   //フォルダー名
  $flg  = json_encode($root, JSON_UNESCAPED_UNICODE);
  // ------------------------------------------------
  // 5.ログインチェック
  // ------------------------------------------------
  if (isset($_SESSION['ID'])) {
    $id = $_SESSION['ID'];
  } else {
    $id = null;
  }
  $ck = json_encode($id, JSON_UNESCAPED_UNICODE);
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
    <link rel="stylesheet" href="../css/item.css">
    <!-- line-awesome -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>商品ページ</title>
  </head>

  <body>
    <!-- headerの読み込み -->
    <?php
    include('../parts/header.php');
    ?>
    <!-- mainの記述 -->
    <main>
      <div id="slide">
        <i id="left" class="las la-angle-double-left"></i>
        <ul id="big_img_list">
        </ul>
        <i id="right" class="las la-angle-double-right"></i>
      </div>
      <div id="description">
      </div>
    </main>
    <!-- footerの読み込み -->
    <?php
    include('../parts/footer.php');
    ?>
    <!-- axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
      // 初期設定
      const big_img_list = document.getElementById('big_img_list');
      const description = document.getElementById('description');
      const ck_json = JSON.stringify(<?= $ck ?>);
      const ck = JSON.parse(ck_json);
      console.log(ck);
      // ---------------------------------------------
      // phpデータをjsonで受け取り（sideエリア）
      // ---------------------------------------------
      // 各SQLデータ
      const json = JSON.stringify(<?= $json ?>);
      const item = JSON.parse(json);
      // ディレクトリの切替
      const flg_json = JSON.stringify(<?= $flg ?>);
      const flg = JSON.parse(flg_json);
      // 取得したテキストの展開
      // 1.タイトル
      const title = document.createElement('p');
      title.className = "title";
      let gender;
      if (item[0].gender == "mens") {
        gender = "メンズ";
      } else if (item[0].gender == "ladies") {
        gender = "レディース";
      } else {
        gender = "ウィメンズ";
      }
      title.textContent = item[0].name + "  (" + gender + ")"
      description.appendChild(title);
      // ２.商品コード
      const code = document.createElement('p');
      code.className = "itemcode";
      code.textContent = "型番：" + item[0].itemcode;
      description.appendChild(code);
      // 3.金額
      const price = document.createElement('p');
      price.className = "price";
      const money = Number(item[0].price).toLocaleString();
      price.textContent = "¥  " + money + "  (税込)";
      description.appendChild(price);
      // 4.取得した画像データの展開
      // ディスクリプション用の画像のタイトル
      const flex_view_title = document.createElement('p');
      flex_view_title.id = "flex_view_title";
      flex_view_title.color = item[0].color;
      flex_view_title.textContent = "カラーを選択：" + item[0].color;
      description.appendChild(flex_view_title);
      // ディスクリプション用の画像を置き場を作成
      const flex_view = document.createElement('div');
      flex_view.className = "flex_view";
      description.appendChild(flex_view);
      for (let i = 0; i < item.length; i++) {
        // 画像名の取得
        const img = document.createElement('img');
        img.class = "img_name";
        let img_name = item[i].img;
        img.dataset.name = item[i].img;
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
        // liタグの作成
        const li = document.createElement('li');
        img.className = "item_img";
        if (i == 0) {
          li.className = "is-shown";
        } else {
          li.className = "hidden";
        }
        li.appendChild(img);
        big_img_list.appendChild(li);
        // ディスクリプション用の画像を置き場に挿入
        const s_img = document.createElement('img');
        s_img.src = url;
        s_img.className = "s_img";
        s_img.id = item[i].color;
        if (i == 0) {
          s_img.classList.add("img_active");
        }
        flex_view.appendChild(s_img);
      }
      // サイズと在庫
      const div = document.createElement('div');
      div.className = "stock";
      description.appendChild(div);
      // ---------------------------------------------
      // スライドショー用の関数
      // ---------------------------------------------
      const left = document.getElementById('left');
      const right = document.getElementById('right');
      const img_target = document.querySelectorAll('#big_img_list > li'); //imgの取得
      const num = img_target.length;
      // 前ページアクション
      left.addEventListener('click', () => {
        // 画像が2種類以上であれば実行
        if (img_target.length != 1) {
          for (let i = 0; i < num; i++) {
            if (img_target[i].className == "is-shown") {
              img_target[i].className = "hidden";
              // 一つ前の画像にis-shownを追加
              if (i == 0) {
                img_target[num - 1].className = 'is-shown';
                // flex_view用の変更
                for (let j = 0; j < num2; j++) {
                  if (s_img[j].className == "img_active");
                  s_img[j].classList.remove('img_active');
                }
                s_img[num - 1].classList.add('img_active');
                const flex_view_title = document.getElementById('flex_view_title');
                flex_view_title.textContent = "カラーを選択：" + s_img[num - 1].id;
                stock_add();
                break;
              } else {
                img_target[i - 1].className = 'is-shown';
                // flex_view用の変更
                for (let j = 0; j < num2; j++) {
                  if (s_img[j].className == "img_active");
                  s_img[j].classList.remove('img_active');
                }
                s_img[i - 1].classList.add('img_active');
                const flex_view_title = document.getElementById('flex_view_title');
                flex_view_title.textContent = "カラーを選択：" + s_img[i - 1].id;
                stock_add();
                break;
              }
            }
          }
        }
      })
      right.addEventListener('click', () => {
        // 画像が1種類のみであればbreak
        if (img_target.length != 1) {
          for (let i = 0; i < num; i++) {
            if (img_target[i].className == "is-shown") {
              // 一つ前の画像にis-shownを追加
              img_target[i].className = "hidden";
              if (i == num - 1) {
                img_target[0].className = 'is-shown';
                // flex_view用の変更
                for (let j = 0; j < num2; j++) {
                  if (s_img[j].className == "img_active");
                  s_img[j].classList.remove('img_active');
                }
                s_img[0].classList.add('img_active');
                const flex_view_title = document.getElementById('flex_view_title');
                flex_view_title.textContent = "カラーを選択：" + s_img[0].id;
                stock_add();
                break;
              } else {
                img_target[i + 1].className = 'is-shown';
                // flex_view用の変更
                for (let j = 0; j < num2; j++) {
                  if (s_img[j].className == "img_active");
                  s_img[j].classList.remove('img_active');
                }
                s_img[i + 1].classList.add('img_active');
                const flex_view_title = document.getElementById('flex_view_title');
                flex_view_title.textContent = "カラーを選択：" + s_img[i + 1].id;
                stock_add();
                break;
              }
            }
          }
        }
      })
      // ---------------------------------------------
      // flex_view用の関数
      // ---------------------------------------------
      const s_img = document.querySelectorAll('.s_img');
      const num2 = s_img.length;
      // s_img分の配列全てにクリックイベント
      s_img.forEach((target) => {
        target.addEventListener('click', () => {
          // s_img内のアクティブを消す
          for (let i = 0; i < s_img.length; i++) {
            if (s_img[i].className == "img_active");
            s_img[i].classList.remove('img_active');
          }
          // targetにアクティブを追加
          target.classList.add('img_active');
          const flex_view_title = document.getElementById('flex_view_title');
          flex_view_title.textContent = "カラーを選択：" + target.id;
          // slideの画像を変更
          // img_targetにis-shownを削除（hidden）を追加
          for (let i = 0; i < num; i++) {
            if (img_target[i].className == "is-shown") {
              img_target[i].className = "hidden";
            }
          }
          // img_targetにis-shownを追加
          for (let i = 0; i < num; i++) {
            let slide_src = img_target[i].children[0].src;
            if (slide_src == target.src) {
              img_target[i].className = "is-shown";
            }
          }
          stock_add();
        })
      });
      //在庫数の取得
      function stock_add() {
        setTimeout(() => {
          const stock_area = document.querySelector(".stock");
          stock_area.innerHTML = "";
          const target = document.querySelector('.is-shown');
          const item_img = target.children[0]; //data-name属性を取得
          const size_text = document.createElement('p');
          size_text.className = "size_text";
          size_text.textContent = "サイズを選択";
          stock_area.appendChild(size_text);
          const data = {
            img: item_img.dataset.name
          }
          axios.get("stock_check.php", {
            params: data
          }).then(function(response) {
            // ストックデータを展開
            const stock = response.data;
            console.log(stock);
            // 在庫確認エリアの表示
            const size_list = ["S", "M", "L", "XL", "FREE"]
            for (let h = 0; h < size_list.length; h++) {
              const stock_box = document.createElement('div');
              stock_box.className = "stock_box";
              stock_box.id = size_list[h];
              const text1 = document.createElement('p');
              text1.className = "stock_box_text";
              text1.textContent = size_list[h] + " / " + "在庫なし"
              const cart_btn = document.createElement('button');
              cart_btn.className = "cart_btn";
              cart_btn.textContent = "完売しました";
              for (let i = 0; i < stock.length; i++) {
                if (stock_box.id == stock[i].size) {
                  text1.textContent = size_list[h] + " / " + "在庫あり";
                  text1.className = "stock_box_text_active"
                  cart_btn.className = "cart_btn_active";
                  cart_btn.textContent = "カートに入れる";
                  cart_btn.dataset.img = item_img.dataset.name;
                  cart_btn.dataset.size = stock[i].size;
                  cart_btn.addEventListener('click', (e) => {
                    if (ck != null) {
                      const form = document.createElement('form');
                      form.style.display = "none";
                      form.method = "post";
                      form.action = "cart.php";
                      const img = document.createElement('input');
                      img.type = "hidden";
                      img.name = "img";
                      img.value = e.target.dataset.img;
                      const size = document.createElement('input');
                      size.type = "hidden";
                      size.name = "size";
                      size.value = e.target.dataset.size;
                      form.appendChild(img);
                      form.appendChild(size);
                      stock_box.appendChild(form);
                      form.submit();
                    } else {
                      alert('ログインをしてください。');
                    }
                  })
                }
              }
              stock_box.appendChild(text1);
              stock_box.appendChild(cart_btn);
              stock_area.appendChild(stock_box);
            }
          }).catch(function(error) {
            console.log(error);
          })
        }, 50);
      }
      // 取得した在庫データをDOM
      stock_add();
    </script>
  </body>

  </html>
