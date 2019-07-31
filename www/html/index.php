<!DOCTYPE html>
<?php session_start(); ?>

<html>
    <head>
        <link href="css/style.css" type="text/css" rel="stylesheet">
        <link href="css/style_carousel.css" type="text/css" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="js/carousel.js"></script>
        <script type="text/javascript" src="js/index.js"></script>
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
                <a class="active" href="index.php">HOME</a>
                <a href="notice.php">NOTICE</a>
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
                    <a href="index.php">한국어</a>
                    <a href="home_eng.php">English</a>
                </div> -->
            </div>

            <div class="aside-right">
                <div class="top-area">
                    <article class="photo">
                        <div class="galleryContainer">
                            <div class="slideShowContainer">
                                <div onclick="plusSlides(-1)" class="nextPrevBtn leftArrow"><span class="arrow arrowLeft"></span></div>
                                <div onclick="plusSlides(1)" class="nextPrevBtn rightArrow"><span class="arrow arrowRight"></span></div>
                                <div class="captionTextHolder"><p class="captionText slideTextFromTop"></p></div>
                                <div class="imageHolder">
                                    <img src="css/image/members_carousel.jpg">
                                    <p class="captionText">1D1S 창단원</p>
                                </div>
                                <div class="imageHolder">
                                    <img src="css/image/logo.jpg">
                                    <p class="captionText">MobileSystemEngineering</p>
                                </div>
                            </div>
                            <div id="dotsContainer"></div>
                        </div>
                        <script src="js/carousel.js"></script>
                    </article>
                </div>
                <div class="bottom-area row">
                    <div class="box three">
                        <div>
                            <div class="box_title">
                                MSE NOTICE
                                <a href="notice.php" target="_self" class="button">+더보기</a>
                            </div>
                            <div class="box_type">
                                <?php
                                $notice_sort = $_GET['notice_sort'];
                                $board_sort = $_GET['board_sort'];?>
                                <ul>
                                    <li><a class="noticeTypeList" value="all" target="_parent" href="index.php?noticea_sort=&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">전체</a></li>
                                    <li><a class="noticeTypeList" value="mse" target="_top" href="index.php?notice_sort=<?php $notice_sort = 'mse'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">학과공지</a></li>
                                    <li><a class="noticeTypeList" value="recruiting" target="_parent" href="index.php?notice_sort=<?php $notice_sort = 'recruiting'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>" style="border-right: 1px solid black;">취업공지</a></li>
                                    <li><a class="noticeTypeList" value="class" target="_parent" href="index.php?notice_sort=<?php $notice_sort = 'class'; echo $notice_sort?>&board_sort=<?php echo $board_sort?>">수업공지</a></li>
                                </ul>
                            </div>
                            <div class="box_main">
                                <article>
                                    <?php
                                        $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                                        $notice_sort = $_GET['notice_sort'];
										$board_sort = $_GET['board_sort'];

										//mysql_query("set session character_set_connection=utf-8;");
										//mysql_query("set session character_set_results=utf-8;");
										//mysql_query("set session character_set_client=utf-8;");

                                        if($notice_sort == NULL){
                                        $check = "SELECT * FROM notice";
                                        }
                                        else if($notice_sort == 'mse'){
                                        $check = "SELECT * FROM notice WHERE sort = '학과'";
                                        }
                                        else if($notice_sort == 'recruiting'){
                                        $check = "SELECT * FROM notice WHERE sort = '취업'";
                                        }
                                        else if($notice_sort == 'class'){
                                        $check = "SELECT * FROM notice WHERE sort = '수업'";
                                        }
                                        $result = $mysqli->query($check);

                                        if(!$result) die($mysqli->error);
                                        $rows = $result->num_rows;
                                        for($j=$rows; $j>$rows-10; $j--)
                                            {
                                            if($j == 0)
                                            break;
                                                $result->data_seek($j-1);
                                                $notice = $result->fetch_array(MYSQLI_BOTH);
                                                $id = $notice['number'];
                                                $sort = $notice['sort']
                                            ?>
                                            <tr>
                                                <td><a class="single_post"
                                                    href='notice_click.php?id=<?php echo $id?>'>
                                                    <?php echo "[" . $sort . "] " . $notice['title'];?>
                                                </a></br></td>
                                            </tr>
                                            <?php
                                        }
                                        $result->close();
                                        $mysqli->close();
                                    ?>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="box three">
                        <div>
                            <div class="box_title">
                                MSE BOARD
                                <a href="board.php" class="button">+더보기</a>
                            </div>
                            <div class="box_type">
                                <ul>
                                    <li><a class="boardTypeList" value="all" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=" style="border-right: 1px solid black;">전체</a></li>
                                    <li><a class="boardTypeList" value="study" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'study'; echo $board_sort?>" style="border-right: 1px solid black;">스터디</a></li>
                                    <li><a class="boardTypeList" value="class" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'class'; echo $board_sort?>" style="border-right: 1px solid black;">수업</a></li>
                                    <li><a class="boardTypeList" value="suggestion" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'suggestion'; echo $board_sort?>" style="border-right: 1px solid black;">건의사항</a></li>
                                    <li><a class="boardTypeList" value="free" href="index.php?notice_sort=<?php echo $notice_sort?>&board_sort=<?php $board_sort = 'free'; echo $board_sort?>">익명</a></li>
                                </ul>
                            </div>
                            <div class="box_main">
                                <article>
                                    <?php
                                        $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                                        $board_sort = $_GET['board_sort'];

                                        if($board_sort == NULL){
                                        $check = "SELECT * FROM board";
                                        }
                                        else if($board_sort == 'study'){
                                        $check = "SELECT * FROM board WHERE sort = '스터디'";
                                        }
                                        else if($board_sort == 'class'){
                                        $check = "SELECT * FROM board WHERE sort = '수업'";
                                        }
                                        else if($board_sort == 'suggestion'){
                                        $check = "SELECT * FROM board WHERE sort = '건의사항'";
                                        }
                                        else if($board_sort == 'free'){
                                        $check = "SELECT * FROM ann_board";
                                        }
                                        $result = $mysqli->query($check);

                                        if(!$result) die($mysqli->error);
                                        $rows = $result->num_rows;
                                        for($j=$rows; $j>$rows-10; $j--){
                                            if($j == 0)
                                            break;
                                                $result->data_seek($j-1);
                                                $board = $result->fetch_array(MYSQLI_BOTH);
                                                $id = $board['number'];
                                                if($board_sort == 'free')
                                                {
                                                  $sub_sort = $board['sub_sort'];
                                                  $ann_check = 1;
                                                }
                                                else
                                                  $sub_sort = $board['sort'];
                                            ?>
                                            <tr>
                                                <td><a class="single_post"
                                                        href='board_click.php?id=<?php echo $id?>&ann=<?php echo $ann_check?>'>
                                                        <?php echo "[" . $sub_sort . "] " . $board['title'];?>
                                                    </a></br></td>
                                            </tr>
                                            <?php
                                        }
                                        $result->close();
                                        $mysqli->close();
                                    ?>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="box three">
                        <div>
                            <div class="box_title">
                                MSE SCHEDULE
                                <a href="schedule.php" class="button">+더보기</a>
                            </div>
                            <div class="sort_area">
                                    <div class="date_area right">
                                        <select name="year" id="year" onchange="changeOption()">
                                        </select>
                                        <select name="month" id="month" onchange="changeOption()">
                                        </select>
                                    </div>
                            </div>
                            <div class="box_main">
                            <article>
                                <div class="diaryWrap">
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
                                </div>
                            </article>
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
