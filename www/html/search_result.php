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
                    <!-- <span class="mse_logo">MSE</span> -->
                    <img src="css/image/logo.jpg" alt="MSE" class="logo">
                </a>
            </div>
            <nav>
                <a href="index.php">HOME</a>
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
                <div class="page_main">
                    <div class="top_area">
                        <div class="title">
                            Search<span class="small_title right">| &nbsp;검색결과</span>
                        </div>
                        <div class="post_type">
                            <div class="field">
                                <?php
                                $search = $_GET['search_value'];
                                ?>
                                "<?php echo $search;
                                $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                                $string = $search;
                                $tok_temp = strtok($string, " ");
                                $i = 0;
                                for( ;  ; $i++){
                                    $tok[] = $tok_temp;
                                    // echo "단어 = ".$tok[$i]."<br/>";
                                    if(!($tok_temp = strtok(" "))) break;
                                }
                                $mysqli = mysqli_connect('localhost', 'junmo14', 'mse','MSE');
                                $check = "SELECT * FROM notice WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";
                                for( $j = 1 ; $i > 0 ; --$i,++$j) {
                                    $check = $check."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
                                }
                                $result = $mysqli->query($check);
                                $rows = $result->num_rows;
                                $check_board = "SELECT * FROM board WHERE title LIKE '%$tok[0]%' or writer LIKE '%$tok[0]%'";
                                for( $j = 1 ; $i > 0 ; --$i,++$j) {
                                    $check_board = $check_board."or title LIKE '%".$tok[$j]."%' or writer LIKE '%".$tok[$j]."%'";
                                }
                                $result_board = $mysqli->query($check_board);
                                $rows_board = $result_board->num_rows;
                                $totalrows = $rows + $rows_board;
                                ?>"에 대한 검색결과(<?php echo $totalrows;?>건)
                            </div>
                        </div>
                    </div>
                    <div class="content-area">
                        <div class="search">
                            <div class="search_result">
                                <div class="search_title">
                                    <?php if($rows != 0){?>
                                        Notice(<?php echo $rows;?>건)<?php
                                    } ?>
                                    <?php
                                    if($rows == 0){
                                    echo "공지사항에 검색 결과가 없습니다.";
                                    } ?>
                                </div>
                                <div class="search_list">
                                    <!-- php로 list 띄우기-->
                                    <?php
                                        for($k=0; $k<$rows; $k++){
                                        if($rows >= 15) $rows = 15;
                                        $notice = $result->fetch_array(MYSQLI_BOTH);
                                        $id = $notice['number'];?>
                                        <tr>
                                            <td width="70" class="list_num"><?php echo $k+1; ?></td>
                                            <td width="500" class="list_title"><a href='notice_click.php?id=<?php echo $id?>'><?php echo $notice['title'];?></a></td>
                                            <td width="120" class="list_writer"><?php echo $notice['writer'];?></td>
                                            <td width="100" class="list_date"><?php echo $notice['date_year'];?></td>
                                            <td width="100" class="list_hits"><?php echo $notice['hits']."</br>";?></td>
                                        </tr><?php
                                    }?>
                                </div>
                                <a class="show_more right" href="notice.php?search_value=<?php echo $search;?>&sort=<?php echo "search"?>&search=">더보기</a>
                            </div>
                            <div class="search_result">
                                <div class="search_title">
                                    <?php  if($rows_board != 0){?>
                                    Board(<?php echo $rows_board;?>건)<?php
                                    } ?>
                                    <?php
                                    if($rows_board == 0){
                                    echo "게시판에 검색 결과가 없습니다.";
                                    } ?>
                                </div>
                                <div class="search_list">
                                    <!-- php로 list 띄우기-->
                                    <?php
                                        for($k=0; $k<$rows_board; $k++){
                                        if($rows_board >= 15){
                                            $rows_board = 15;
                                        }
                                        $board = $result_board->fetch_array(MYSQLI_BOTH);
                                        $id = $board['number'];?>
                                        <tr>
                                            <td width="70" class="list_num"><?php echo $k+1; ?></td>
                                            <td width="500" class="list_title"><a href='board_click.php?id=<?php echo $id?>'><?php echo $board['title'];?></a></td>
                                            <td width="120" class="list_writer"><?php echo $board['writer'];?></td>
                                            <td width="100" class="list_date"><?php echo $board['date_year'];?></td>
                                            <td width="100" class="list_hits"><?php echo $board['hits']."</br>";?></td>
                                        </tr><?php
                                    }?>
                                </div>
                                <a class="show_more right" href="notice.php?search_value=<?php echo $search;?>&sort=<?php echo "search"?>&search=">더보기</a>
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
