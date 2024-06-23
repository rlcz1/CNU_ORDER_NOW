<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
    // 로그인 확인
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    if (!$user) {
      alert("로그인이 필요합니다.");
      move("/pages/login.php");
    }

   $min = isset($_GET['min']) ? $_GET['min'] : "";  // 날짜 범위 최소
   $max = isset($_GET['max']) ? $_GET['max'] : "";  // 날짜 범위 최대
   $cno = $_SESSION['user']['cno'];  // 세션에 저장된 고객번호

  // 날짜 범위에 따라 sql문을 다르게 작성
  $sub_sql = "WHERE orderDateTime IS NOT NULL AND cno = '{$cno}'";
  if ($min && $max) {
    // 최대 날짜에 하루를 더함
    $max_date = date('Y-m-d', strtotime($max . ' +1 day'));
    $sub_sql = "WHERE orderDateTime >= '{$min}' AND orderDateTime <= '{$max_date}' AND cno = '{$cno}'";
  } else if ($min) {
    $sub_sql = "WHERE orderDateTime >= '{$min}' AND cno = '{$cno}'";
  } else if ($max) {
    $max_date = date('Y-m-d', strtotime($max . ' +1 day'));
    $sub_sql = "WHERE orderDateTime <= '{$max_date}' AND cno = '{$cno}'";
  }

   $sql = "
      SELECT GROUP_CONCAT(id) AS ids, orderDateTime
      FROM cart
      {$sub_sql}
      GROUP BY orderDateTime
    ";
    $result = $db->query($sql);
    $orderTimeIds = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C-ON</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  <div class="wrapper">
    <header>
      <h1>주문내역</h1>
    </header>

    <form class="search" method="get" action="">
      <p>내역 범위 검색</p>
      <div class="item">
        <input type="date" name="min" id="order_min_input" placeholder="최소 금액">
        <input type="date" name="max" id="order_max_input" placeholder="최대 금액">
      </div>
      <button>검색</button>
    </form>

    <div class="order_list">
      <?php
        foreach ($orderTimeIds as $order) {
      ?>
      <div class="order_item">
        <p>주문 접수 <?= $order['orderDateTime'] ?></p>
        <div class="table-wrapper" id="order-table-wrapper">
          <table>
            <thead>
              <tr>
                <th>메뉴명</th>
                <th>개당가격</th>
                <th>수량</th>
                <th>총 가격</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $item_sql = "SELECT * FROM orderDetail WHERE id IN ({$order['ids']})";
                $item_result = $db->query($item_sql);
                $items = $item_result->fetchAll(PDO::FETCH_ASSOC);
                foreach ($items as $item) {
              ?>
              <tr>
                <td><?= $item['foodName'] ?></td>
                <td>
                  <?= $price = FLOOR($item['totalPrice'] / $item['quantity']) ?>
                </td>
                <td><?= $item['quantity'] ?></td>
                <td><?= $item['totalPrice'] ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="total-price">
          <p>총 주문금액</p>
          <p>
            <?php
              $totalPrice = 0;
              foreach ($items as $item) {
                $totalPrice += $item['totalPrice'];
              }
              echo $totalPrice;
            ?>
          </p>
        </div>
      </div>
      <?php } ?>
    </div>

    <footer>
      <a href="order.php">
        <span class="material-symbols-outlined">
          receipt_long
        </span>
        <span class="footer_nav_title">주문목록</span>
      </a>
      <a href="../index.php">
        <span class="material-symbols-outlined">
          home
        </span>
        <span class="footer_nav_title">홈</span>
      </a>
      <a href="cart.php">
        <span class="material-symbols-outlined">
          shopping_cart
        </span>
        <span class="footer_nav_title">장바구니</span>
      </a>
      <?php 
        if ($user['cno'] == 'c0') {
      ?>
      <a href="stats.php">
        <span class="material-symbols-outlined">
          bar_chart
        </span>
        <span class="footer_nav_title">통계</span>
      </a>
      <?php } ?>
    </footer>
  </div>
</body>
</html>