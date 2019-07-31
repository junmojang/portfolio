<!DOCTYPE html>
<?php session_start(); ?>
<html>

	<head>
		<link href="css/style.css" type="text/css" rel="stylesheet">
		<link href="css/style_page.css" type="text/css" rel="stylesheet">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="js/carousel.js"></script>
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
				<a class="active" href="contact.php">CONTACT</a>
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
				<!-- <div class="language">
								<a href="index.php">한국어</a>
								<a href="home_eng.php">English</a>
							</div> -->
			</div>

			<div class="aside-right">
				<div class="page_main">
					<div class="top_area">
						<div class="title">
							Contact<span class="small_title right">| &nbsp;연락처</span>
						</div>
                    </div>
                    <br>
					<div class="content-area">
                        <h3>○ 전임교원</h2>
						<div class="professors">
							<div class="row">
								<div class="professor">
                                    <div class="image_wrap">
									    <img src="css/image/professor_shin.jpg">
                                    </div>
									<div class="info">
										<h2>신원용</h2>
                                        <div class="detail">
                                            <p><i class="fas fa-phone"></i> 031 - 8005 - 3253</p>
                                            <p><i class="far fa-envelope"></i><img src="/css/image/shin_email.PNG" class="prof_email" alt="신교수님 이메일"></p>
                                            <i class="fas fa-home"></i> <a href="http://sites.google.com/site/cnldankook" target="_blank">신원용 교수님 페이지</a>
                                        </div>
									</div>
								</div>
								<div class="professor">
                                    <div class="image_wrap">
    									<img src="css/image/professor_yoo.jpg">
                                    </div>
									<div class="info">
										<h2>유시환</h2>
                                        <div class="detail">
                                            <p><i class="fas fa-phone"></i> 031 - 8005 - 3240</p>
                                            <p><i class="far fa-envelope"></i><img src="/css/image/yoo-email.PNG" class="prof_email" alt="유교수님 이메일"></p>
                                            <i class="fas fa-home"></i> <a href="https://sites.google.com/site/dkumobileos/members/seehwanyoo" target="_blank">유시환 교수님 페이지</a>
                                        </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="professor">
                                    <div class="image_wrap">
									    <img src="css/image/professor_jang.png">
                                    </div>
									<div class="info">
										<h2>장석호</h2>
                                        <div class="detail">
                                            <p><i class="fas fa-phone"></i> 031 - 8005 - 3241</p>
                                            <p><i class="far fa-envelope"></i><img src="/css/image/jang_email.PNG" class="prof_email" alt="장교수님 이메일"></p>
                                            <i class="fas fa-home"></i> <a href="https://sites.google.com/view/wmalab" target="_blank">장석호 교수님 페이지</a>
                                        </div>
									</div>
								</div>
								<div class="professor">
                                    <div class="image_wrap">
									    <img src="css/image/professor_choi.png">
                                    </div>
									<div class="info">
										<h2>최수한</h2>
                                        <div class="detail">
                                            <p><i class="fas fa-phone"></i> 031 - 8005 - 3243</p>
                                            <p><i class="far fa-envelope"></i><img src="/css/image/choi_email.PNG" class="prof_email" alt="최교수님 이메일"></p>
                                            <!-- <a href="" target="_blank">최수한 교수님 페이지</a> -->
                                        </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="professor">
                                    <div class="image_wrap">
									    <img src="css/image/professor_lee.png">
                                    </div>
									<div class="info">
										<h2>이현우</h2>
                                        <div class="detail">
                                            <p><i class="fas fa-phone"></i> 031 - 8005 - 3231</p>
                                            <p><i class="far fa-envelope"></i> <img src="/css/image/lee-email.PNG" class="prof_email" alt="이교수님 이메일"></p>
                                            <!-- <a href="" target="_blank">이현우 교수님 페이지</a> -->
                                        </div>
									</div>
								</div>
							</div>
                        </div>
                        <h3>○ 교학행정팀</h3>
                        <div class="uniAdmin_info row">
                            <div class="detail three">
                                <i class="fas fa-phone"></i> 전화번호: 031-8005-3638
                            </div>
                            <div class="detail three">
                                <i class="fas fa-fax"></i> 팩스: 031-8021-7407
                            </div>
                            <div class="detail three">
                                <i class="fas fa-map-marker-alt"></i> 위치: 국제관 302호
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
