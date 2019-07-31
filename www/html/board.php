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
				<a class="active" href="board.php">BOARD</a>
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
					<div class="top_area">
						<div class="title">
							Board<span class="small_title right">| &nbsp;게시판</span>
						</div>
						<div class="post_type">
							<div class="field">
								<div class="row">
                                    <a href="board.php" style="width: 20%;">전체</a>
									<a href="board.php?sort=<?php $sort = 'study'; echo $sort?>" style="width: 20%;">스터디</a>
									<a href="board.php?sort=<?php $sort = 'class'; echo $sort?>" style="width: 20%;">수업</a>
                                    <a href="board.php?sort=<?php $sort = 'suggestion'; echo $sort?>" style="width: 20%;">건의사항</a>
                                    <a href="board.php?sort=<?php $sort = 'free'; echo $sort?>" style="border: none; width: 20%;">익명</a>
								</div>
							</div>
							<div class="field">
								<a href="newboard.php" class="newpost right"><i class="fas fa-edit"></i>글쓰기
									<!-- <span class="blind">글쓰기</span> -->
								</a>
								<form action="board.php" method="get" class="right">
									<input type="text" name="search_value">
									<input type="hidden" name="sort" value="search">
									<button type="submit" name="search" class="notice_search_btn">검색</button>
								</form>
							</div>
						</div>
					</div>
					<div class="content-area">
						<?php
						$sort = $_GET['sort'];
						$search = $_GET['search_value'];
						?>
						<article id="board">
							<?php
								if($sort == NULL)
								{?>
									<h1 class="head">전체 게시판</h1><?php
								}
								else if($sort == 'study')
								{?>
									<h1 class="head">스터디 게시판</h1><?php
								}
								else if($sort == 'class')
								{?>
									<h1 class="head">수업 게시판</h1><?php
								}
								else if($sort == 'suggestion')
								{?>
									<h1 class="head">건의사항</h1><?php
								}
								else if($sort == 'free')
								{?>
									<h1 class="head">익명 게시판</h1><?php
								}
								else if($sort == 'search')
								{?>
									<?php
									if($search == NULL)
									{?>
										<h1 class="head">검색어를 입력 해주세요.</h1><?php
									}
									else
									{?>
										<h1 class="head">"<?php echo $search?>"에 대한 검색결과</h1></h1><?php
									}
							}?>
							<table class="list-table">
								<thead>
									<tr>
										<th width="70" class="table_num">번호</th>
										<th width="500" class="table_title">제목</th>
										<th width="120" class="table_writer">글쓴이</th>
										<th width="100" class="table_date">작성일</th>
										<th width="100" class="table_hits">조회수</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
									$check = "SELECT * FROM board WHERE pin = TRUE";
									$result = $mysqli->query($check);
									if(!$result) die($mysqli->error);
									$rows = $result->num_rows;
									for ($j = 0; $j < $rows; $j++) {
										$result->data_seek($j);
										$board = $result->fetch_array(MYSQLI_BOTH);
										$id = $board['number'];
										?>
										<tr>
											<td width="70" class="pin_list_num">공지</td>
											<td width="500" class="pin_list_title"><a href='board_click.php?id=<?php echo $id?>'>
												<?php if($board['sort'] == NULL)
													echo "[전체] " . $board['title'];
													else
														echo "[" . $board['sort'] . "] " . $board['title']; ?></a></td>
											<td width="120" class="pin_list_writer"><?php echo $board['writer'];?></td>
											<td width="100" class="pin_list_date"><?php echo $board['date_year'];?></td>
											<td width="100" class="pin_list_hits"><?php echo $board['hits'];?></td>
										</tr>
										<?php
									}
									if ($sort == NULL) {
										$check = "SELECT * FROM board";
									}
									else if ($sort == 'study') {
										$check = "SELECT * FROM board WHERE sort = '스터디'";
									}
									else if ($sort == 'class') {
										$check = "SELECT * FROM board WHERE sort = '수업'";
									}
									else if ($sort == 'suggestion') {
										$check = "SELECT * FROM board WHERE sort = '건의사항'";
									}
									else if ($sort == 'free') {
										$check = "SELECT * FROM ann_board";
										$ann = 1;
									}
									else if ($sort == 'search'&&$search != NULL) {
										$string = $search;
										$tok_temp = strtok($string, " ");
										$i = 0;
										for ( ;  ; $i++) {
											$tok[] = $tok_temp;
											// echo "단어 = ".$tok[$i]."<br/>";
											if(!($tok_temp = strtok(" "))) break;
										}
										$mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
										$check = "SELECT * FROM board WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";
										for ($j = 1; $i > 0; --$i, ++$j) {
											$check = $check."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
										}
									}
									$result = $mysqli->query($check);
									if(!$result) die($mysqli->error);
									$rows = $result->num_rows;
									$page_num = $rows;
									if($page_num % 15 == 0)
										$page_num = floor($rows / 15);
									else {
										$page_num = floor($rows / 15) + 1;
										$current_page = $_GET['page_number'];
									}
									if($current_page == NULL) $current_page = 0;
									for($j=$rows-15*($current_page); $j>$rows-(15*($current_page+1)); $j--) {
										if ($j == 0) break;
										$result->data_seek($j-1);
										$board = $result->fetch_array(MYSQLI_BOTH);
										$id = $board['number'];
										?>
										<tr>
											<td width="70" class="list_num"><?php echo $j; ?></td>
											<td width="500" class="list_title"><a href='board_click.php?id=<?php echo $id?>&&ann=<?php echo $ann?>'><?php
											if($sort == 'free') echo "[" . $board['sub_sort'] . "] " . $board['title'];
											else if($board['sort'] != NULL) echo "[" . $board['sort'] . "] " . $board['title'];
											else  echo "[전체] " . $board['title'];
											?></a></td>
											<td width="120" class="list_writer"><?php if($sort == 'free'){echo 익명;} else{echo $board['writer'];}?></td>
											<td width="100" class="list_date"><?php echo $board['date_year'];?></td>
											<td width="100" class="list_hits"><?php echo $board['hits'];?></td>
										</tr>
										<?php
									}
									$result->close();
									$mysqli->close();
									?>
								</tbody>
							</table>
							<div class="pagination"><?php
								if($current_page != 0){?>
									<a href="board.php?sort=<?php echo $sort?>&page_number=0&search_value=<?php echo $search?>" class="page_first">처음</a>
									<a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $current_page -1?>&search_value=<?php echo $search?>" class="page_prev">이전</a><?php
								}
								for($i = 0; $i < $page_num; $i++)
								{
									if($i == $current_page)
									{?>
									<em class="page_cur"><?php echo $i+1?></em><?php
									}
									else
									{?>
									<a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $i?>&search_value=<?php echo $search?>" class="page_num"><?php echo $i+1?></a><?php
									}
								}
								if($current_page != $page_num-1){?>
								<a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $current_page+1?>&search_value=<?php echo $search?>" class="page_next">다음</a>
								<a href="board.php?sort=<?php echo $sort?>&page_number=<?php echo $page_num-1?>&search_value=<?php echo $search?>" class="page_end">끝</a><?php
								}?>
							</div>
						</article>
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
