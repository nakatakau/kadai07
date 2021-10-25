<?php
  include('../func.php');
  session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/signup.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap" rel="stylesheet">
  <title>登録完了</title>
</head>

<body>
  <!-- headerの読み込み -->
  <?php
  include('../parts/header.php');
  ?>
  <!-- 登録完了部分 -->
  <main>
    <!-- POSTデータの受け取り -->
    <?php
    // POSTされたデータ
    $email = $_POST['email'];
    // ------------------------------------------------
    // 1.PDOの開始
    // ------------------------------------------------
    $pdo = pdo();
    $stmt = $pdo->prepare("SELECT * FROM registration WHERE email = :email");
    // 配列形式で ? に postされたemailを入力する
    $stmt->bindValue(':email', h($email), PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //POSTされたメールアドレスがデータにあるか確認
    if (isset($row['email']) && !($row['id'] == $_SESSION['ID'])) {
        header('Location: fix.php');
        return false;
    } else {
        // メールが登録されてなければ更新処理を実行
        date_default_timezone_set("Asia/Tokyo");
        $name     = $_POST['name'];
        $gender   = $_POST['gender'];
        $email    = $_POST['email'];
        $post_num = $_POST['post_num'];
        $address  = $_POST['address'];
        $phone    = $_POST['phone'];
        $birth    = $_POST['year'] . sprintf('%02d', $_POST['month']) . sprintf('%02d', $_POST['day']); //YYYYMMDDの形で送信する
        $id       = $_POST['id'];

        // パスワードのハッシュ化
        $password = password_hash(h($_POST['password']), PASSWORD_DEFAULT);
        // 実行処理
        $stmt = $pdo->prepare("UPDATE registration SET name = :name, gender = :gender, birthday = :birth, post_num = :post_num, address = :address, phone = :phone, email = :email, password = :password WHERE id = :id");
        // $stmt = $pdo->prepare("UPDATE registration SET name = :name, gender = :gender",);
        $stmt->bindValue(':name', h($name), PDO::PARAM_STR);
        $stmt->bindValue(':gender', h($gender), PDO::PARAM_STR);
        $stmt->bindValue(':birth', h($birth), PDO::PARAM_INT);
        $stmt->bindValue(':post_num', h($post_num), PDO::PARAM_STR);
        $stmt->bindValue(':address', h($address), PDO::PARAM_STR);
        $stmt->bindValue(':phone', h($phone), PDO::PARAM_STR);
        $stmt->bindValue(':email', h($email), PDO::PARAM_STR);
        $stmt->bindValue(':password', h($password), PDO::PARAM_STR);
        $stmt->bindValue(':id', h($id), PDO::PARAM_INT);
        // 実行
        $flag = $stmt->execute();
        // セッションに再登録
        try {
          $stmt = $pdo->prepare("SELECT * FROM registration WHERE email = ?");
          // 配列形式で ? に postされたemailを入力する
          $stmt->execute([h($_POST['email'])]);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          exit('DbConnectError' . $e->getMessage());
        }
        // セッションを登録
        $_SESSION['NAME'] = $row['name'];
        $_SESSION['ID'] = $row['id'];
    }
    ?>
    <!-- ログイン部分 -->
    <div class="sign-up">
      <?php
      echo "<h2>" . $name . " 様</h2>";
      ?>
      <h2>更新処理が完了致しました。</h2>
      <p>引き続き、お買い物をお楽しみください</p>
    </div>
  </main>
  <!-- footerの読み込み -->
  <?php
  include('../parts/footer.php')
  ?>
</body>

</html>
