<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  $foodName = $_POST['foodname'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  $cno = $_SESSION['user']['cno'];

  $totalPrice = $price * $quantity;

  // cart 테이블에 먼저 값 추가
  $sql = "INSERT INTO cart (cno) VALUES ('{$cno}')";
  $result = $db->query($sql);

  // 위의 sql에서 insert했던 id값 가져오기
  $id_sql = "SELECT LAST_INSERT_ID() as id";
  $id = $db->query($id_sql)->fetch(PDO::FETCH_ASSOC)['id'];

  // orderDetail 테이블에 값 추가
  $sql = "INSERT INTO orderDetail (id, quantity, totalPrice, foodName) VALUES ('{$id}', '{$quantity}', '{$totalPrice}', '{$foodName}')";
  $result = $db->query($sql);

  // sql결과를 확인하여 성공하면 success, 실패하면 error를 반환하여 ajax에서 처리
  if($result) {
    echo "success";
  } else {
    echo "error";
  }

?>
