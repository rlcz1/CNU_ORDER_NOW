<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<?php
  // 로그인 확인
  $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
  if (!$user) {
    alert("로그인이 필요합니다.");
    move("/pages/login.php");
  }

  // 검색 조건
  $category = isset($_GET['category']) ? $_GET['category'] : "";  // 카테고리
  $name = isset($_GET['menu_name']) ? $_GET['menu_name'] : "";  // 메뉴 이름
  $min = isset($_GET['min']) ? $_GET['min'] : "";  // 최소 금액
  $max = isset($_GET['max']) ? $_GET['max'] : "";  // 최대 금액

  // 최소금액, 최대금액 정수로 설정
  $min = $min ? (int)$min : 0;
  $max = $max ? (int)$max : 999999999;

  $sql = "";
  if ($category) {
    // 카테고리 쿼리
    $sql = "
      SELECT 
        c.categoryName AS category,
        f.foodName AS foodName,
        f.price AS price
      FROM 
        Food f
      JOIN 
        Contain co ON f.foodName = co.foodName
      JOIN 
        Category c ON co.categoryName = c.categoryName
      WHERE c.categoryName = '{$category}'
      ORDER BY 
        c.categoryName, f.foodName;
    ";

  } else {
    // 검색 쿼리
    $sql = "
      SELECT * FROM food
      WHERE foodName LIKE '%{$name}%'
      AND price BETWEEN {$min} AND {$max}
    ";
  }
  $result = $db->query($sql);
  $menus = $result->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C-ON</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="app.js"></script>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  <div class="wrapper">
    <header>
      <h1>메뉴 정보</h1>
    </header>

    <form class="search" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <p>메뉴 검색</p>
      <div class="item">
        <input type="text" name="menu_name" id="search_menu_name_input" placeholder="메뉴 이름을 검색해주세요.">
      </div>
      <p>가격 범위 검색</p>
      <div class="item">
        <input type="number" name="min" id="search_min_input" placeholder="최소 금액">
        <input type="number" name="max" id="search_max_input" placeholder="최대 금액">
      </div>
      <button>검색</button>
    </form>
    
    <?php
    // 카테고리 목록 가져오기
      $sql = "SELECT * FROM category";
      $result = $db->query($sql);
      $categories = $result->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <form class="category" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <p>카테고리</p>
      <select name="category" id="">
        <option value="">전체</option>
        <?php
          // 카테고리 목록 출력
          foreach ($categories as $category) {
            echo "<option value='{$category['categoryName']}' >{$category['categoryName']}</option>";
          }
        ?>
      </select>
      <button>선택</button>
    </form>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>메뉴명</th>
            <th>개당가격</th>
            <th>수량</th>
            <th>메뉴 담기</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // 메뉴 목록 출력
            foreach ($menus as $menu) {
          ?>
            <tr>
              <td class='foodName'><?= $menu['foodName'] ?></td>
              <td class='price'><?=  $menu['price'] ?></td>
              <td><input type='number' min='1' value='0'></td>
              <td>
                <button class='add_cart_btn'>
                  <span class='material-symbols-outlined'>shopping_cart</span>
                </button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <footer>
      <a href="./pages/order.php">
        <span class="material-symbols-outlined">
          receipt_long
        </span>
        <span class="footer_nav_title">주문목록</span>
      </a>
      <a href="">
        <span class="material-symbols-outlined">
          home
        </span>
        <span class="footer_nav_title">홈</span>
      </a>
      <a href="./pages/cart.php">
        <span class="material-symbols-outlined">
          shopping_cart
        </span>
        <span class="footer_nav_title">장바구니</span>
      </a>
      <?php 
        if ($user['cno'] == 'c0') {
      ?>
      <a href="./pages/stats.php">
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