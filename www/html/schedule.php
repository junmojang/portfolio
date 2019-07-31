<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style_page.css">
		<script type="text/javascript" src="js/calendar.js"></script>
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
				<a class="active" href="schedule.php">SCHEDULE</a>
				<a href="board.php">BOARD</a>
				<a href="contact.php">CONTACT</a>
				<a href="members.php">MEMBERS</a>
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
			</div>
			<div class="aside-right">
				<div class="page_main">
					<div class="top_area">
                        <div class="title">
                            Schedule
                            <div class="small_title right">
                                | &nbsp;일정
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="content-area">
                        <div class="sort_area">
                            <span class="btn_area">
                                <button class="prev" onclick="onClickBtn('prev')">&#9664;</button>
                                <button class="next" onclick="onClickBtn('next')">&#9654;</button>
                                <button class="today" onclick="onClickBtn('today')">오늘</button>
                            </span>
                            <div class="date_area right">
                                <select name="year" id="year" onchange="changeOption()">
                                </select>
                                <select name="month" id="month" onchange="changeOption()">
                                </select>
                            </div>
                        </div>
                        <table id="calendar" class="diaryContent">
                            <tr id="days">
                                <th class="listHead day font-red">일</th>
                                <th class="listHead day">월</th>
                                <th class="listHead day">화</th>
                                <th class="listHead day">수</th>
                                <th class="listHead day">목</th>
                                <th class="listHead day">금</th>
                                <th class="listHead day font-blue">토</th>
                            </tr>
                            <tr id="dates">
                            </tr>
                        </table>
						<!--
						<div class="bottom">
                            <button class="schedule_register_btn" onclick="location.href='schedule_register.php'">학사일정 등록</button>
						</div>
						-->
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
