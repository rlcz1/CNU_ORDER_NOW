<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  $cno = $_POST['cno'];
  $name = $_POST['name'];
  $phoneno = $_POST['phoneno'];
  $passwd = $_POST['passwd'];

  $sql = "INSERT INTO customer (cno, name, phoneno, passwd) VALUES ('{$cno}', '{$name}', '{$phoneno}', '{$passwd}')";
  $result = $db->query($sql);

  if($result) {
    alert("회원가입 성공");
    move("/pages/login.php");
  } else {
    alert("회원가입 실패");
    move("/pages/join.php");
  }

?>