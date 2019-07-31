<!DOCTYPE html>
<?php session_start();

?>

<html>
    <head>
        <link href="css/style.css" type="text/css" rel="stylesheet">
		<link href="css/style_page.css" type="text/css" rel="stylesheet">
		<link href="css/style_post.css" type="text/css" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="js/carousel.js"></script>
        <script src="js/post.js"></script>
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
                    <a href="index.php">한국어</a>
                    <a href="home_eng.php">English</a>
                </div> -->
            </div>

            <div class="aside-right">
                <div class="page_main">
                    <div class="top_area">
                        <div class="title">
                            MSE Board
                            <!-- <span class="small_title right">| &nbsp;검색결과</span> -->
                        </div>
                    </div>
                    <div class="content-area">
						<?php
						$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
						$board_num = $_GET['id'];
						$ann_check = $_GET['ann'];

            $iplogfile = '/var/www/html/php/log.txt';
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            $timestamp = date('d/m/Y H:i:s');
            $fp = fopen($iplogfile, 'a+') or die("can't open file");
            chmod($iplogfile, 0777);
            if(isset($_SESSION['user_id']))
                fwrite($fp, '['.$timestamp.']: '.' board 조회 ('.$board_num.')  '.$_SESSION['user_id'].' '.$ipaddress.' '. "\n");
            else
                fwrite($fp, '['.$timestamp.']: '.' board 조회 ('.$board_num.')         '.$ipaddress.' '. "\n");
            fclose($fp);

						if($ann_check == NULL)
							$check = "SELECT * FROM board WHERE number = '$board_num'";
						else
							$check = "SELECT * FROM ann_board WHERE number = '$board_num'";
						$result = $mysqli->query($check);
						if(!result){
							echo " fail";
						}else{
							$board = $result->fetch_array(MYSQLI_BOTH);
							$id = $board['number'];
						}
						if($ann_check == NULL)
							$add_hits = "UPDATE board SET hits = hits+1 WHERE number = '$board_num'";
						else
							$add_hits = "UPDATE ann_board SET hits = hits+1 WHERE number = '$board_num'";
						$hits_result = $mysqli->query($add_hits);
						// echo "user_id: " . $user_id . " user_name: " . $user_name . " notice student_id: " . $board['student_id'];
						?>
						<article class="post" style="overflow: visible">
							<div class="post_title case">
								<?php
								echo $board['title'];?>
							</div>
							<div class="post_info case">
								<table>
									<tr>
										<!-- <td>
											<a href="board_click.php" class="post_info_writer .notice_writer_pic">
												<span class="blind">인</span>
											</a>
										<td> -->
										<td>
											<?php
											if($ann_check == NULL) echo $board['writer'];
											else echo "익명";?>
										</td>
										<td>
											<i class="fa fa-eye"></i>
											<?php
											echo $board['hits'];?>
										</td>
									</tr>
									<tr>
										<td>
											<?php
											echo $board['date_year'];?>
										</td>
										<td>
											<?php
											echo $board['date_hour'];?>
										</td>
									</tr>
								</table>
							</div>
							<div class="post_attachment case">
								<table>
									<tr>
										<td>
											<i class="fas fa-paperclip"></i>첨부파일
										</td>
										<td>
											<?php
											$b_file1 = $board['file1'];
											$b_file1_cut = substr($b_file1, 29);

											$b_file2 = $board['file2'];
											$b_file2_cut = substr($b_file2, 29);

											$b_file3 = $board['file3'];
											$b_file3_cut = substr($b_file3, 29);
											?>
											<?php
											if($b_file1 != NULL){?>
											<a href = "php/file_download.php?filename=<?php echo $b_file1?>&check=0" target = "_blank"><?php echo $b_file1_cut?></a><br>
											<?php
											}?>
											<?php
											if($b_file2 != NULL){?>
											<a href = "php/file_download.php?filename=<?php echo $b_file2?>&check=0" target = "_blank"><?php echo $b_file2_cut?></a><br>
											<?php
											}?>
											<?php
											if($b_file3 != NULL){?>
											<a href = "php/file_download.php?filename=<?php echo $b_file3?>&check=0" target = "_blank"><?php echo $b_file3_cut?></a><br>
											<?php
											}?>
										</td>
									</tr>
								</table>
							</div>
							<div style="overflow: auto;" class="post_detail case"><pre><?php
								echo $board['content'];
								$result->close();?></pre>
							</div>
							<div class="post_reply">
                                <?php
                                    if($ann_check == 1)
                                        $check = "SELECT * FROM ann_board_comment WHERE board_number = '$id'";
                                    else
                                        $check = "SELECT * FROM board_comment WHERE board_number = '$id'";
                                    $result = $mysqli->query($check);
                                    if(!$result) die($mysqli->error);
                                    $rows = $result->num_rows;
                                    $page_num = $rows;
                                    if($page_num % 10 == 0)
                                        $page_num = floor($rows / 10);
                                    else
                                        $page_num = floor($rows / 10) + 1;
                                    $current_page = $_GET['page_number'];
                                    if($current_page == NULL)
                                        $current_page = 0;
                                    $board_comment = $result->fetch_array(MYSQLI_BOTH);
                                    $number = $board_comment['number'];
                                    // for($j=$current_page*10+1; $j<$current_page*10+10; $j++)
                                    for ($j=$rows-10*($current_page); $j>$rows-(10*($current_page+1)); $j--) {
                                        if ($j == 0) break;
                                        $result->data_seek($j-1);
                                        $comment = $result->fetch_array(MYSQLI_BOTH);
                                        ?>
                                        <div class="reply">
                                            <i class="fas fa-user"></i>
                                            <?php
                                                if($ann_check != 1) {?>
                                                    <td><?php echo $comment['writer']; ?></td><?php
                                                } else {?>
                                                    <td>익명</td><?php
                                            }?>
											<td><?php echo $comment['date']; ?></td>
											<?php
                                            if($user_id == $comment['student_id'] | $admin_check == 1 | $ann_check == 1){ ?>
                                                <form class="reply_delete" method="post" action="php/delete_board_comment.php?id=<?php echo $id?>&number=<?php echo $comment['number']?>&ann=<?php echo $ann_check?>"  onsubmit="return showPasswordPromt('comment','delete',<?php echo $ann_check?>);"><?php
                                                      if($ann_check == 1)
                                                      { ?>
                                                         <input type = "password" id = "comment_delete_password_check" name = "comment_delete_password_check" placeholder="댓글비밀번호"/><?php
                                                      } ?>
                          												   <input id="reply_delete" type="submit" value="삭제">
                          												   <label for="reply_delete"><span> <i class="far fa-times-circle"></i> 삭제</span></label>
                                                </form><?php
                                            }?><br>
                                            <td><?php echo $comment['content']; ?></td>
                                        </div>
                                        <?php
                                    }
                                    $result->close();
                                    $mysqli->close();
								?>
								<?php
								if ($user_id) {?>
									<div class="input case">
										<form action="php/write_board_comment.php?id=<?php echo $id?>&ann=<?php echo $ann_check?>" name="comment" method="post">
											<textarea rows="5" name="comment"></textarea>
                                            <div class="submit_box">
                                              <?php
                                              if($ann_check == 1)
                                              { ?>
                                                 <input type = "password" class="show" name = "comment_write_password_check" placeholder="댓글비밀번호"/><?php
                                              } ?>
                                              <input id="comment_btn" class="btn" type="submit" value="댓글쓰기"/>
                                              <label for="comment_btn"><div><i class="fas fa-pencil-alt"></i> 댓글쓰기</div></label>
                                            </div>

										</form>
									</div><?php
								}?>
								<div class="paginate"><?php
									for ($i = 0; $i < $page_num; $i++) {
										if($i == $current_page) {?>
											<em><?php echo $i+1?></em><?php
										} else {?>
											<a class="page_mid" href="board_click.php?id=<?php echo $id?>&page_number=<?php echo $i?>"><?php echo $i+1?></a><?php
										}
									}?>
								</div>
								<br>
							</div>
						</div>
					</article>

					<span class="btn_wrap right">
						<?php
						if($user_id == $board['student_id']  | $admin_check == 1 | $ann_check == 1){?>
							<form action="php/delete_board.php?id=<?php echo $id?>&ann=<?php echo $ann_check?>" onsubmit="return showPasswordPromt('post', 'delete', <?php echo $ann_check ?>);" method="post"><?php
                 if($ann_check == 1)
                 { ?>
                    <input type = "password" id="delete_password_check" name = "delete_password_check" placeholder="게시물 비밀번호"/><?php
                 } ?>
								<input id="post_delete" type = "submit" value="게시글 삭제"/>
								<label for="post_delete"><div><i class="fas fa-trash-alt"></i>  게시글 삭제</div></label>
							</form>
							<form action="editboard.php?id=<?php echo $id?>&ann=<?php echo $ann_check?>" onsubmit="return showPasswordPromt('post', 'edit', <?php echo $ann_check ?>);" method="post"><?php
                  if($ann_check == 1)
                  { ?>
                     <input type = "password" id = "edit_password_check" name = "edit_password_check" placeholder="게시물 비밀번호"/><?php
                  } ?>
								<input id="post_edit" class="input_none" type="submit" value="게시글 수정"/>
								<label for="post_edit"><div><i class="fas fa-edit"></i>  게시글 수정</div></label>
							</form><?php
							}?>
						<div class="btn">
							<a href="board.php">목록</a>
						</div>
					</span>
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
