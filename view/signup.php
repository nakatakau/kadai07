<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/signup.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap" rel="stylesheet">
  <title>ログイン</title>
</head>

<body>
  <!-- headerの読み込み -->
  <?php
  include('../parts/header.php')
  ?>
  <!-- ログイン画面の表示 -->
  <main>
    <div class="login">
      <form action="confirm.php" method="post" id="login_form" name="login_form">
        <table>
          <tr>
            <td colspan="2">
              <p class="title">基本情報</p>
            </td>
          </tr>
          <tr>
            <td class="left" id="check1">
              <p>性別</p>
            </td>
            <td class="gender">
              <div class="block"><label><input type="radio" name="gender" value="男">男</label></div>
              <div class="block"><label><input type="radio" name="gender" value="女">女</label></div>
              <div class="block"><label><input type="radio" name="gender" value="その他">その他</label></div>
            </td>
          </tr>
          <tr>
            <td class="left" id="check2">
              <p>お名前</p>
            </td>
            <td><input type="text" name="name" placeholder="山田太郎" class="input_text"></td>
          </tr>
          <tr>
            <td class="left" id="check3">
              <p>生年月日</p>
            </td>
            <td class="birth">
              <select name="year" id="year"></select>年
              <select name="month" id="month"></select>月
              <select name="day" id="day"></select>日
            </td>
          </tr>
          <tr>
            <td class="left" id="check4">
              <p>メールアドレス</p>
            </td>
            <td><input type="email" name="email" placeholder="xxx@zzz.ne.jp"></td>
          </tr>
          <tr>
            <td class="left" id="check5">
              <p>郵便番号</p>
            </td>
            <td><input type="text" name="post_num" placeholder="111-1111" class="input_text"></td>
          </tr>
          <tr>
            <td class="left" id="check6">
              <p>住所</p>
            </td>
            <td><input type="text" name="address" placeholder="東京都新宿区新宿１丁目１番地１１１" class="input_text"></td>
          </tr>
          <tr>
            <td class="left" id="check7">
              <p>電話番号</p>
            </td>
            <td><input type="text" name="phone" placeholder="000-0000-0000" class="input_text"></td>
          </tr>
          <tr>
            <td class="left" id="check8">
              <p>パスワード</p>
            </td>
            <td>
              <div class="password">
                <input type="password" name="password" inputmode="latin">
                <img src="../img/close_eye.svg" alt="close_eye" id="eye">
              </div>
            </td>
          </tr>
        </table>
        <input type="submit" value="会員登録" id="submit" onclick="return check();">
      </form>
    </div>
  </main>
  <!-- footerの読み込み -->
  <?php
  include('../parts/footer.php')
  ?>
  <script>
    //生年月日にoptionを差し込む
    (function() {
      const year = document.getElementById('year');
      const month = document.getElementById('month');
      const day = document.getElementById('day');
      const date = new Date();
      const last_year = date.getFullYear(); //最新の年
      let options1 = '<option value=""></option>';
      let options2 = '<option value=""></option>';
      let options3 = '<option value=""></option>';
      // 年の取得
      for (let i = last_year; i >= 1900; i--) {
        const option1 = '<option value="' + i + '">' + i + '</option>';
        options1 += option1;
      }
      // 月の取得
      for (let j = 1; j <= 12; j++) {
        const option2 = '<option value="' + j + '">' + j + '</option>';
        options2 += option2;
      }
      // 日の取得
      for (let k = 1; k <= 31; k++) {
        const option3 = '<option value="' + k + '">' + k + '</option>';
        options3 += option3;
      }
      year.innerHTML = options1;
      month.innerHTML = options2;
      day.innerHTML = options3;
    }());

    // 入力内容の確認
    const submit = document.getElementById('submit');
    const form = document.login_form;
    console.log(form.gender.value);

    function check() {
      // ローカルストレージに保存
      let i = 1;
      if (i == 1) {
        // 入力内容のチェック
        if (form.gender.value == "") {
          const check1 = document.getElementById('check1');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check1.children[1] == undefined) {
            check1.appendChild(p);
          }
          i++;
        }
        if (form.name.value == "") {
          const check2 = document.getElementById('check2');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check2.children[1] == undefined) {
            check2.appendChild(p);
          }
          i++;
        }
        if (form.year.value == "" || form.month.value == "" || form.day.value == "") {
          const check3 = document.getElementById('check3');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check3.children[1] == undefined) {
            check3.appendChild(p);
          }
          i++;
        }
        // メールアドレスは２重チェック
        if (form.email.value == "") {
          const check4 = document.getElementById('check4');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check4.children[1] == undefined) {
            check4.appendChild(p);
          }
          i++;
        } else {
          // SQLサーバーにメールアドレスがある場合
          <?php

          ?>
        }
        if (form.post_num.value == "") {
          const check5 = document.getElementById('check5');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check5.children[1] == undefined) {
            check5.appendChild(p);
          }
          i++;
        }
        if (form.address.value == "") {
          const check6 = document.getElementById('check6');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check6.children[1] == undefined) {
            check6.appendChild(p);
          }
          i++;
        }
        if (form.phone.value == "") {
          const check7 = document.getElementById('check7');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check7.children[1] == undefined) {
            check7.appendChild(p);
          }
          i++;
        }
        // パスワードチェック
        if (form.password.value == "") {
          const check8 = document.getElementById('check8');
          const p = document.createElement('p');
          p.className = 'check';
          p.textContent = '入力してください';
          if (check8.children[1] == undefined) {
            check8.appendChild(p);
          }
          i++;
        }
        //
      }
      // iが加算されてなければ処理を進める
      if (i == 1) {
        return true;
      } else {
        return false;
      }
    }
    // パスワードチェック
    const eye = document.getElementById('eye');
    let flg = 1;
    eye.addEventListener('click', (e) => {
      if (flg == 1) {
        eye.previousElementSibling.type = "text"; //テキスト型に変更
        eye.src = "../img/open_eye.svg";
        flg = 2;
      } else {
        eye.previousElementSibling.type = "password"; //password型に変更
        eye.src = "../img/close_eye.svg";
        flg = 1;
      }
    })
  </script>
  <script src="../js/main.js"></script>
</body>

</html>
