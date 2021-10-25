  <?php
  include('../func.php');
  // セッションのスタート
  session_start();
  // ------------------------------------------------
  // 1.PDOの開始
  // ------------------------------------------------
  $pdo = pdo();
  $stmt = $pdo->prepare("SELECT * FROM registration WHERE email = ?");
  // 配列形式で ? に postされたemailを入力する
  $stmt->execute([h($_POST['email'])]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  //POSTされたメールアドレスがデータにあるか確認
  if (!isset($row['email'])) {
    header('Location: login.php');
    return false;
  }

  // パスワードのデータをsessionに格納
  if (password_verify($_POST['password'], $row['password'])) {
    session_regenerate_id(true); //session_idを新しく生成し、置き換える
    $_SESSION['NAME'] = $row['name'];
    $_SESSION['ID'] = $row['id'];
    header('Location: main.php');
  } else {
    header('Location: login.php');
    return false;
  }
  ?>
