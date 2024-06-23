<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  // 로그인 확인
  $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
  if (!$user) {
    alert("로그인이 필요합니다.");
    move("/pages/login.php");
  }

  // 장바구니 불러오기
  $cno = $_SESSION['user']['cno'];

  $sql = "
      SELECT
        od.foodName,
        FLOOR(od.totalPrice / od.quantity) AS 'price',
        od.quantity,
        od.totalPrice
      FROM
        OrderDetail od
      JOIN
        Cart c ON od.id = c.id
      WHERE
        c.cno = '{$cno}'
      AND
        c.orderDateTime IS NULL;
  ";
  $result = $db->query($sql);
  $cart = $result->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C-ON</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="../app.js"></script>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  <div class="wrapper">
    <header>
      <h1>장바구니</h1>
    </header>

    <div class="table-wrapper">
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
            foreach ($cart as $item) {
              echo "
                <tr>
                  <td>{$item['foodName']}</td>
                  <td>{$item['price']}</td>
                  <td>{$item['quantity']}</td>
                  <td>{$item['totalPrice']}</td>
                </tr>
              ";
            }
          ?>
          <!-- <tr>
            <td>짜장면</td>
            <td>8,000</td>
            <td><input type="number" min="1" value="1"></td>
            <td>8,000</td>
          </tr> -->
        </tbody>
      </table>
    </div>

    <div class="total-price">
      <?php
        $sql = "
          SELECT
            SUM(totalPrice) AS 'totalPrice'
          FROM
            OrderDetail od
          JOIN
            Cart c ON od.id = c.id
          WHERE
            c.cno = '{$cno}'
          AND
            c.orderDateTime IS NULL;
        ";
        $result = $db->query($sql);
        $totalPrice = $result->fetch(PDO::FETCH_ASSOC);

      ?>
      <p>총 주문금액</p>
      <p><?= $totalPrice['totalPrice'] ?></p>
      <button id="payment">결제하기</button>
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