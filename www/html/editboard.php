<!DOCTYPE html>
<?php session_start(); ?>

<html>
    <head>
        <link href="css/style.css" type="text/css" rel="stylesheet">
        <link href="css/style_page.css" type="text/css" rel="stylesheet">
        <link href="css/style_form.css" type="text/css" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <script type="text/javascript">
            function showSub(selected)
            {
                if(selected == '자유')
                {
                document.getElementById("show_flip").style.display = "inline-block";
                document.getElementById("file_attachment_area").style.display = "none";
                }
                else
                {
                document.getElementById("show_flip").style.display = "none";
                document.getElementById("file_attachment_area").style = null;
                }
            }
        </script>
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
                                <a href="index.php">한국어</a>
                                <a href="home_eng.php">English</a>
                            </div> -->
            </div>

            <div class="aside-right">
                <div class="page_main">
                    <?php if(!isset($_SESSION['user_id'])) {?>
                    <div>
                        <h1>Please sign in!</h1>
                    </div>
                    <?php } else { ?>
                    <div class="top_area">
                        <div class="title">
                            Edit Board<span class="small_title right">| &nbsp;게시글 수정</span>
                        </div>
                    </div>
                    <br>
                    <?php
                        $user_id = $_SESSION['user_id'];
                        $user_name = $_SESSION['user_name'];
                        $id = $_GET['id'];
                        $ann = $_GET['ann'];
                        $password_check = $_POST['edit_password_check'];
                        $mysqli = mysqli_connect('localhost','junmo14','mse','MSE');

                        if(mysqli_connect_errno($mysqli)){
                                    echo "연결실패";
                        }else{
                                    // echo "성공";
                        }
                        if($ann == 1)
                        {
                              $check_1 = "SELECT * FROM ann_board where number = '$id'";
                              $result_1 = $mysqli->query($check_1);
                              $board = $result_1->fetch_array(MYSQLI_BOTH);

                              $title = $board['title'];
                              $content = $board['content'];
                              $sort = "자유";
                              $sub_sort = $board['sub_sort'];
                              $password = $board['password'];
                              if($password != $password_check && $admin_check != 1)
                              {
                                      echo "<script>alert('익명게시물 비밀번호가 틀렸습니다!.');";
                                      echo "window.location.replace('./board_click.php?id=$id&ann=$ann');</script>";
                                      exit();
                              }
                        }
                        else
                        {
                          $check_1 = "SELECT * FROM board where number = '$id'";
                          $result_1 = $mysqli->query($check_1);
                          $board = $result_1->fetch_array(MYSQLI_BOTH);

                          $title = $board['title'];
                          $content = $board['content'];
                          $sort = $board['sort'];

                          $file1 = $board['file1'];
                          $file2 = $board['file2'];
                          $file3 = $board['file3'];
                        }
                    ?>
                    <div class="content-area">
                        <form action="php/edit_board.php?id=<?php echo $id?>" method="POST" enctype = "multipart/form-data">
                            <table class="input_form">
                                <tr>
                                    <th>게시판</th>
                                    <td>
                                        <select name="board_type" class="board_type" onChange="showSub(this.options[this.selectedIndex].value);">
                                            <option value="전체">게시판을 선택해주세요</option>
                                            <option value="스터디"<?php
                                                if($sort == "스터디"){?>
                                                selected=""<?php
                                                }?>>스터디</option>
                                            <option value="수업"<?php
                                                if($sort == "수업"){?>
                                                selected=""<?php
                                                }?>>수업</option>
                                            <option value="건의사항"<?php
                                                if($sort == "건의사항"){?>
                                                selected=""<?php
                                                }?>>건의사항</option>
                                            <option value="자유"<?php
                                                if($sort == "자유"){?>
                                                selected=""<?php
                                                }?>>자유</option>
                                        </select>
                                        <div id="show_flip" style="display:none">
                                            <div class="sub_category">
                                                <span>말머리</span>
                                                <select name="head_type" class="head_type">
                                                  <option value = "NULL">말머리를 선택해주세요</option>
                                                  <option value="전체"<?php
                                                    if($sub_sort == "전체"){?>
                                                      selected=""<?php
                                                    }?>>전체</option>
                                                  <option value="연애"<?php
                                                    if($sub_sort == "연애"){?>
                                                      selected=""<?php
                                                    }?>>연애</option>
                                                  <option value="잡담"<?php
                                                    if($sub_sort == "잡담"){?>
                                                      selected=""<?php
                                                    }?>>잡담</option>
                                                  <option value="생활"<?php
                                                    if($sub_sort == "생활"){?>
                                                      selected=""<?php
                                                    }?>>생활</option>
                                                  <option value="취미"<?php
                                                    if($sub_sort == "취미"){?>
                                                      selected=""<?php
                                                    }?>>취미</option>
                                                  <option value="패션"<?php
                                                    if($sub_sort == "패션"){?>
                                                      selected=""<?php
                                                    }?>>패션</option>
                                                  <option value="새내기"<?php
                                                    if($sub_sort == "새내기"){?>
                                                      selected=""<?php
                                                    }?>>새내기</option>
                                                  <option value="기타"<?php
                                                    if($sub_sort == "기타"){?>
                                                      selected=""<?php
                                                    }?>>기타</option>
                                                </select>
                                            </div>
                                            <div class="board_password">
                                                <span>게시물 비밀번호</span>
                                                <input type="password" name="board_pw" value = "<?php echo $password ?>"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>제목</th>
                                    <td><input type="text" name="title" value ="<?php echo $title ?>"/></td>
                                </tr>
                                <tr id="file_attachment_area">
                                    <th>파일첨부</th>
                                    <td>
                                        <input type="file" name="upfile1"><br>
                                        <input type="file" name="upfile2"><br>
                                        <input type="file" name="upfile3">
                                    </td>
                                </tr>
                                <script type="text/javascript">showSub("자유");</script>
                                <tr>
                                    <th>내용</th>
                                    <td>
                                        <textarea name="content" rows="20" cols="100"><?php echo $content ?></textarea>
                                    </td>
                                </tr>
                            </table>
                            <div class="input_btn">
                                <?php
                                if($user_id == $board['student_id'] || $ann == 1 || $admin_check == 1){?>
                                <input class="save_btn" type ="submit" value="변경" onclick="if(!confirm('변경하시겠습니까?')){return false;}"/><?php
                                unlink("../up/upfile/".$file1);
                                unlink("../up/upfile/".$file2);
                                unlink("../up/upfile/".$file3);
                                }?>
                                <input type ="button" onclick="if(!confirm('취소하시겠습니까? 작성 중이던 모든 글이 지워집니다.')){return false;} history.back();" value="취소"/>
                            </div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
            $result_1->close();
            $mysqli->close();
        ?>

        <footer>
            주소 : 경기도 용인시 수지구 죽전로 152, 단국대학교 국제관 201호 (우)16890 <br>
            COPYRIGHT 2019 BY DANKOOK UNIVERSITY, INTERNATIONAL COLLEGE MOBILE SYSTEM ENGINEERING <1D1S><br><br>
              본 페이지는 친목단체를 위한 홈페이지로 단국대학교 모바일시스템공학과 학과 홈페이지가 아닙니다. 학과 홈페이지는 CMS를 이용해주세요.
        </footer>
    </body>
</html>
