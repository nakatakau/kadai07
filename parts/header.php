<!-- headerのhtml -->
<header>
  <div class="logo-img">
    <a href="../view/main.php">
      <img src="../img/logo.svg" alt="logo" id="logo">
    </a>
  </div>
      <!-- セッションの確認 -->
      <?php
      if (!isset($_SESSION['NAME'])) {
        echo "<div class='nav-area'>" ;
        echo "<ul class='nav-list'>";
        echo "<a href='../view/login.php'>";
        echo "<li><img src='../img/account.svg' alt='login'>ログイン</li>";
        echo "</a>";
        echo "</ul>";
      } else {
        echo "<div class='nav-area1'>";
        echo "<ul class='nav-list'>";
        echo "<li id='user_info'><img src='../img/account.svg' alt='login'>" . $_SESSION['NAME'] . " 様</li>";
        echo "<a href='../view/cart.php'>";
        echo "<li><img src='../img/cart.svg' alt='cart'>カート</li>";
        echo "</a>";
        echo "<a href='../view/purchase_history.php'>";
        echo "<li><img src='../img/cart.svg' alt='cart'>購入履歴</li>";
        echo "</a>";
        echo "</ul>";
      };
      ?>
  </div>
</header>
