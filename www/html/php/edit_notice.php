<?php
	session_start();
	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];
	$id = $_GET['id'];

	// attached file management
	$file1 = $_FILES['upfile1']['tmp_name'];
	$filename1 = $_FILES['upfile1']['name'];

	$file2 = $_FILES['upfile2']['tmp_name'];
	$filename2 = $_FILES['upfile2']['name'];

	$file3 = $_FILES['upfile3']['tmp_name'];
	$filename3 = $_FILES['upfile3']['name'];

	if($file1) {
		//$filename = basename($filename1);
		$folder = "../../up/upfile/".$user_id.$filename1;
		move_uploaded_file($file1 , $folder);
		$f1 = $user_id.$filename1;
		//echo $folder;
		//$filename = 0;
		//$folder = 0;
	}
	if($file2) {
		//$filename = basename($filename2);
		$folder = "../../up/upfile/".$user_id.$filename2;
		move_uploaded_file($file2 , $folder);
		$f2 = $user_id.$filename2;
		//$filename = 0;
		//$folder = 0;
	}
	if($file3) {
		//$filename = basename($filename3);
		$folder = "../../up/upfile/".$user_id.$filename3;
		move_uploaded_file($file3 , $folder);
		$f3 = $user_id.$filename3;
		//$filename = 0;
		//$folder = 0;
	}

	$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
	if (mysqli_connect_errno($mysqli)) {
		echo "연결실패";
	} else {
		echo "성공";
		echo $user_id;
		echo $user_name;
	}

    // $check_1 = "SELECT * FROM notice where number = '$id'";
    // $result_1 = $mysqli->query($check_1);
    // $notice = $result_1->fetch_array(MYSQLI_BOTH);

	$title = $_POST['title'];
	$content = $_POST['content'];
	$sort = $_POST['notice_type'];
	echo $title;
	echo $content;
	echo $sort;

	$date1 = date("Y-m-d");
	$date2 = date("H:i:s"); // current timestamp

	if ($title == NULL || $content == NULL) {
		echo "빈칸을 모두 입력해주세요";
		echo "<script>window.location.replace('../editnotice.php?id=$id');</script>";
		exit();
	}

	$check = "UPDATE notice SET title = '$title', file1 = '$f1', file2 = '$f2', file3 = '$f3', content = '$content', sort = '$sort' WHERE number = '$id'";
	$result = $mysqli->query($check);
	if(!$result){
    	echo "fail";
    } else {
		echo "success";

		$iplogfile = '/var/www/html/php/log.txt';
	  $ipaddress = $_SERVER['REMOTE_ADDR'];
	  $timestamp = date('d/m/Y H:i:s');
	  $fp = fopen($iplogfile, 'a+') or die("can't open file");
	  chmod($iplogfile, 0777);
	  fwrite($fp, '['.$timestamp.']: '.' notice 수정 ('.$id.') '.$_SESSION['user_id'].' '.$ipaddress.' '. "\n");
	  fclose($fp);
	}


	echo "<script>window.location.replace('../notice.php?id=$id&&ann=');</script>";
	exit();
	$result->close();
	$result_1->close();
	$mysqli->close();
?>
