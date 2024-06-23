<?php include_once $_SERVER['DOCUMENT_ROOT']."/db.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>C-ON</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="form-wrapper">
    <form class="form-container" method="post" action="/post/login.php">
      <div class="title">
        <h1>C-ON</h1>
        <p>CNU Order Now</p>
      </div>
      <div class="content">
        <input type="text" name="cno" id="login-cno" placeholder="고객번호를 입력해주세요." required>
        <input type="password" name="passwd" id="login-passwd" placeholder="비밀번호를 입력해주세요." required>
        <button>로그인</button>
        <a href="join.php">회원가입 ></a>
      </div>
    </form>
  </div>
</body>
</html>