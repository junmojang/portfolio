<!DOCTYPE html>
<?php session_start(); ?>
<html>

	<head>
		<link href="css/style.css" type="text/css" rel="stylesheet">
		<link href="css/style_page.css" type="text/css" rel="stylesheet">
		<link href="css/style_form.css" type="text/css" rel="stylesheet">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="js/carousel.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>
        <script src="js/editor.js"></script>
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
				<a class="active" href="notice.php">NOTICE</a>
				<a href="schedule.php">SCHEDULE</a>
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
				<!-- <div class="language">
								<a href="index.html">한국어</a>
								<a href="home_eng.html">English</a>
							</div> -->
			</div>

			<div class="aside-right">
				<div class="page_main">
                    <?php if(!isset($_SESSION['user_id'])) {?>
                    <div>
                        <h1>Please sign in!</h1>
                    </div>
                    <?php } else { ?>
                    <div>
                        <div class="top_area">
                            <div class="title">
                                New Notice<span class="small_title right">| &nbsp;공지사항 쓰기</span>
                            </div>
                        </div>
                        <br>
                        <div class="content-area">
                            <form action="php/write_notice.php" method="POST" enctype = "multipart/form-data">
                                <table class="input_form">
                                    <tr>
                                        <th>게시판</th>
                                        <td>
                                            <select name="notice_type">
                                                <option value="전체">공지사항을 선택해주세요</option>
                                                <option value="학과">학과</option>
                                                <option value="취업">취업</option>
                                                <option value="수업">수업</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>제목</th>
                                        <td><input type="text" name="title"/></td>
                                    </tr>
                                    <tr>
                                        <th>파일첨부</th>
                                        <td>
                                            <input type="file" name="upfile1"><br>
                                            <input type="file" name="upfile2"><br>
                                            <input type="file" name="upfile3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>내용</th>
                                        <td>
                                            <form action="write_notice.php" name="content" method="get">
                                                <textarea name="content" rows="20" id="editor"></textarea>
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                                <div class="input_btn">
																	<?php
	                                if ($admin_check == 1){?>
                                    <input type ="submit" onclick="if(!confirm('등록하시겠습니까?')){return false;}" value="등록"/><?php
																		}?>
                                    <input type ="button"  onclick="if(!confirm('취소하시겠습니까? 작성 중이던 모든 글이 지워집니다.')){return false;} history.back();" value="취소"/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
				</div>
			</div>
		</div>

		<footer>
			<br />주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
			COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <1D1S>
		</footer>
	</body>
</html>
