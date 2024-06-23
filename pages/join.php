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
    <form class="form-container" method="post" action="/post/join.php">
      <div class="title">
        <h1>C-ON</h1>
        <p>CNU Order Now</p>
      </div>
      <div class="content">
        <input type="text" name="name" id="join-name" placeholder="이름을 입력해주세요." required>
        <input type="text" name="phoneno" id="join-phoneno" placeholder="전화번호를 입력해주세요." required>
        <input type="text" name="cno" id="join-cno" placeholder="고객번호를 입력해주세요." required>
        <input type="password" name="passwd" id="cno_passwd" placeholder="비밀번호를 입력해주세요." required>
        <button id="join_btn" type="submit">회원가입</button>
        <a href="login.php">로그인 ></a>
      </div>
    </form>
  </div>
</body>
</html>