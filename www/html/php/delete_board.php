<?php
session_start();
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];
$ann = $_GET['ann'];
$password = $_POST['delete_password_check'];

$query_id = "SELECT student_id from board where number = '$id'";
$select_id = $mysqli->query($query_id);
$check_id = $select_id->fetch_array(MYSQLI_ASSOC);

$query_admin = "SELECT admin from user where id = '$user_id'";
$select_admin = $mysqli->query($query_admin);
$check_admin = $select_admin->fetch_array(MYSQLI_ASSOC);
$admin_check = $check_admin['admin'];

if($ann == 1)
{
	$password_check_query_line = "SELECT password from ann_board where number = '$id'";
	$password_check_query = $mysqli->query($password_check_query_line);
	$password_check_result = $password_check_query->fetch_array(MYSQLI_ASSOC);
	$password_check = $password_check_result['password'];
	if($password == $password_check | $_SESSION['admin_check'] == 1)
	{
		$query_comment = "DELETE FROM ann_board_comment WHERE board_number = '$id'";
		$delete_comment = $mysqli->query($query_comment);
		$query_board = "DELETE FROM ann_board WHERE number = '$id'";
		$delete_board = $mysqli->query($query_board);
		echo "<script>window.location.replace('../board.php?sort=free');</script>";
		exit;
		$password_check_result->close();
		$delete_comment->close();
		$delete_board->close();
		$mysqli->close();
	}
	else
	{
		echo"<script>alert('익명게시판 비밀번호가 틀렸습니다!!.');";
		echo "window.location.replace('../board_click.php?id=$id&ann=1');</script>";
	}
}
if($select_id)
{
	if($user_id != $check_id['student_id'] & $admin_check == 0)
	{
	        echo "<script>alert('권한이 없습니다!!.');";
	        echo "window.location.replace('../board_click.php?id=$id');</script>";
		exit;
		$select_name->close();
		$mysqli->close();
	}
}

$query_comment = "DELETE FROM board_comment WHERE board_number = '$id'";
$delete_comment = $mysqli->query($query_comment);

if($delete_comment){
	echo "comment delete";
}

$query_board = "DELETE FROM board WHERE number = '$id'";
$delete_board = $mysqli->query($query_board);

if($delete_board){
	echo "board delete</br>";
}


$iplogfile = '/var/www/html/php/log.txt';
$ipaddress = $_SERVER['REMOTE_ADDR'];
$timestamp = date('d/m/Y H:i:s');
$fp = fopen($iplogfile, 'a+') or die("can't open file");
chmod($iplogfile, 0777);
fwrite($fp, '['.$timestamp.']: '.' board 삭제 ('.$id.')  '.$_SESSION['user_id'].' '.$ipaddress.' '. "\n");
fclose($fp);

echo "<script>window.location.replace('../board.php?id=$id');</script>";
exit;

$delete_comment->close();
$delete_board->close();
$mysqli->close();
?>
