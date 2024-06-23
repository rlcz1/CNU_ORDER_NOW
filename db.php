<?php 

// 세션 연결
  session_start();

  // DB 연결
  $dsn = "mysql:host=localhost;port=3306;dbname=d202102697;charset=utf8";
  try {
    $db = new PDO($dsn, "root", "0128");
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch(PDOException $e) {
    echo $e->getMessage();
  }

  // alert 함수
  function alert ($msg) {
    echo "<script>alert('$msg');</script>";
  }

  // 페이지 이동 함수
  function move ($url) {
    echo "<script>location.href = '$url';</script>";
  }
?>