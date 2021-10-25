<?php
session_start();
require('../func.php');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/login.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap" rel="stylesheet">
  <title>ログイン</title>
</head>

<body>
  <!-- header -->
  <?php
  include('../parts/header.php');
  ?>
  <!-- main -->
  <main>
    <div class="login_check">
      <h2 class="login_check_title">ログイン</h2>
      <form action="login_success.php" method="post" id="login_check_area">
        <input type="text" placeholder="登録したメールアドレス" class="login_check_input" name="email">
        <div class="password">
          <input type="password" placeholder="パスワード" name="password">
          <img src="../img/close_eye.svg" alt="close_eye" id="eye">
        </div>
        <input type="submit" value="送信">
      </form>
    </div>
    <div id="goto_sianup">
      <p><a href="signup.php"> 新規登録はこちら </a></p>
    </div>
  </main>
  <!-- footer -->
  <?php
  include('../parts/footer.php');
  ?>
  <!-- js -->
  <script>
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
