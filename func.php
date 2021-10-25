<?php

  // --------------------------------------------------
  // htmlspecialchars
  // --------------------------------------------------
  function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
  }

  // --------------------------------------------------
  // DB接続
  // --------------------------------------------------
  function pdo(){
    try{
      $db_name = $_SERVER['SERVER_NAME'] == "localhost" ? "ec_test" : "nt-lab11-19_ec_test";   //データベース名
      $db_id   = $_SERVER['SERVER_NAME'] == "localhost" ? "root" : "nt-lab11-19";              //アカウント名
      $db_pw   = $_SERVER['SERVER_NAME'] == "localhost" ? "root" : "ec_test1234";              //パスワード：XAMPPはパスワード無しに修正してください。
      $db_host = $_SERVER['SERVER_NAME'] == "localhost" ? "localhost" : "mysql57.nt-lab11-19.sakura.ne.jp";  //DBホスト
      return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
    }catch (PDOException $e) {
      exit('DB Connection Error:'.$e->getMessage());
    }
  }
