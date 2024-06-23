<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
  if (!$user) {
    alert("로그인이 필요합니다.");
    move("/pages/login.php");
  }
  // 관리자인지 검사
  if ($user['cno'] != 'c0') {
    alert("관리자만 접근 가능합니다.");
    move("/index.php");
  }
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
      <h1>통계정보</h1>
    </header>

    <div class="order_list">
      <div class="order_item">
        <p style="font-size: 18px;">메뉴별 판매금액 순위</p>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>순위</th>
                <th>메뉴명</th>
                <th>개당가격</th>
                <th>수량</th>
                <th>총 가격</th>
              </tr>
            </thead>
            <?php
              $sql = "
                SELECT
                  RANK() OVER (ORDER BY SUM(ORDERDETAIL.TOTALPRICE) DESC) AS 순위,
                  ORDERDETAIL.FOODNAME AS 메뉴명,
                  FLOOR(SUM(ORDERDETAIL.TOTALPRICE) / SUM(ORDERDETAIL.QUANTITY)) AS 개당가격,
                  SUM(ORDERDETAIL.QUANTITY) AS 수량,
                  SUM(ORDERDETAIL.TOTALPRICE) AS 총가격
                FROM ORDERDETAIL
                GROUP BY ORDERDETAIL.FOODNAME
                ORDER BY 총가격 DESC;
              ";
              $result = $db->query($sql);
              $menus = $result->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <tbody>
              <?php foreach ($menus as $menu) { ?>
              <tr>
                <td><?= $menu['순위'] ?></td>
                <td><?= $menu['메뉴명'] ?></td>
                <td><?= $menu['개당가격'] ?></td>
                <td><?= $menu['수량'] ?></td>
                <td><?= $menu['총가격'] ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="order_item">
        <p style="font-size: 18px;">카테고리별 주문 합계</p>
        <?php
        // 카테고리별 주문 합계 쿼리
          $sql = "
            SELECT
              RANK() OVER (ORDER BY SUM(ORDERDETAIL.TOTALPRICE) DESC) AS 순위,
              CONTAIN.CATEGORYNAME AS 카테고리명,
              SUM(ORDERDETAIL.TOTALPRICE) AS 총가격
            FROM CONTAIN
            JOIN ORDERDETAIL ON CONTAIN.FOODNAME = ORDERDETAIL.FOODNAME
            GROUP BY CONTAIN.CATEGORYNAME
            ORDER BY 총가격 DESC;
          ";
          $result = $db->query($sql);
          $categories = $result->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>순위</th>
                <th>카테고리명</th>
                <th>총 가격</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($categories as $category) { ?>
              <tr>
                <td><?= $category['순위'] ?></td>
                <td><?= $category['카테고리명'] ?></td>
                <td><?= $category['총가격'] ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      
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