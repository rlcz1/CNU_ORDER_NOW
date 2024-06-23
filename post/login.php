<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  $cno = $_POST['cno'];
  $passwd = $_POST['passwd'];

  $log = $db->prepare("SELECT * FROM customer WHERE cno = '{$cno}' AND passwd = '{$passwd}'");
  $log->execute();
  $row = $log->fetch(PDO::FETCH_ASSOC);

  if($row) {
    $_SESSION['user'] = $row;
    alert("로그인 성공");
    move("/index.php");
  } else {
    alert("로그인 실패");
    move("/pages/login.php");
  }

?>