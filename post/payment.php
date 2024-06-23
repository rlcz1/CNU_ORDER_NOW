<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  $cno = $_SESSION['user']['cno'];

  $sql = "UPDATE cart SET orderDateTime = CURRENT_TIMESTAMP WHERE cno = '{$cno}' AND orderDateTime IS NULL;";
  $result = $db->query($sql);

  // sql결과를 확인하여 성공하면 success, 실패하면 error를 반환하여 ajax에서 처리
  if($result) {
    echo "success";
  } else {
    echo "error";
  }
?>