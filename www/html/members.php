<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style_page.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </head>


    <body>
    <header>
        <div class="top_wrap">
            <a data-clk="top.logo" href="index.php">
                <img src="css/image/logo.jpg" alt="MSE" class="logo">
            </a>
        </div>
        <nav>
            <a href="index.php">HOME</a>
            <a href="notice.php">NOTICE</a>
            <a href="schedule.php">SCHEDULE</a>
            <a href="board.php">BOARD</a>
            <a href="contact.php">CONTACT</a>
            <a class="active" href="members.php">MEMBERS</a>
        </nav>
    </header>

    <div class="content">
        <div class="aside-left">
          <div class="login_box">
          <?php
          if(!isset($_SESSION['user_id'])) { ?>
              <form method="post" action="php/login_ok.php">
                  <div class="login_input">
                      <input type="text" placeholder="Username" id="username" name="user_id">
                      <input type="password" placeholder="password" id="password" name="user_pw">
                  </div>
                  <button class="login_btn" type="submit">Sign In</button>
              </form>
              <div class="register_btns">
                  <a href="php/signup.php" id="register"><i class="fas fa-user"></i>회원가입</a>
                  <a id="findid"><i class="fas fa-id-badge"></i></a>
                  <a id="findpw"><i class="fas fa-unlock"></i></a>
              </div>
              <?php
                  } else {
                      $user_id = $_SESSION['user_id'];
                      $user_name = $_SESSION['user_name'];
                      $admin_check = $_SESSION['admin_check'];
                      echo "<p><strong>$user_name</strong> 님~~ "; ?><br>
                      <a href="changepw.php" target="_self" id="register">비밀번호변경</a>
        <a href="php/logout.php" id="forgot">로그아웃</a>
        <a href="php/terminate.php" id="terminate">회원탈퇴</a>
              <?php }?>
          </div>
            <div class="counter">
                <?php include "php/count.php"; ?>
            </div>
            <div class="search">
                <form action="search_result.php" method="get">
                    <input type="text" name="search_value" placeholder="검색어 입력">
                    <input type="hidden" name="sort" value="search">
                    <button type="submit" name="search"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <!-- <div class="language">
                <a href="index.php">한국어</a>
                <a href="home_eng.php">English</a>
            </div> -->
        </div>
        <div class="aside-right">
        <div class="page_main">
            <div class="top_area">
                <div class="title">
                    Members
                    <div class="small_title right">
                        | &nbsp;
                    </div>
                </div>
            </div>
            <br>
            <div class="content-area">
                <p class="head">First Members</p>
                    <div class="left_section">
                        <ul class="members_list">
                            <li>권태완</li>
                            <li>김주호</li>
                            <li>류성미</li>
                            <li>박혜원</li>
                            <li>여승현</li>
                            <li>이대훈</li>
                            <li>이동구</li>
                            <li>이종원</li>
                            <li>장준모(♛)</li>
                            <li>주소정</li>
                            <li>하승아</li>
                            <li>한남경</li>
                        </ul>
                    </div>
                    <div class="right_section">
                        <div class="image_wrap">
                            <img src="css/image/members.jpg" alt="1D1S멤버사진">
                        </div>
                    </div>
            </div>

        </div>
    </div>
    </div>

    <footer>
        주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
        COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <1D1S><br><br>
          본 페이지는 친목단체를 위한 홈페이지로 단국대학교 모바일시스템공학과 학과 홈페이지가 아닙니다. 학과 홈페이지는 CMS를 이용해주세요.
    </footer>
    </body>
</html>
