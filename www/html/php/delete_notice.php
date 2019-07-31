<?php
session_start();
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];

$query_id = "SELECT student_id from notice where number = '$id'";
$select_id = $mysqli->query($query_id);
$check_id = $select_id->fetch_array(MYSQLI_ASSOC);

$query_admin = "SELECT admin from user where id = '$user_id'";
$select_admin = $mysqli->query($query_admin);
$check_admin = $select_admin->fetch_array(MYSQLI_ASSOC);
$admin_check = $check_admin['admin'];

echo $test;


if($select_id)
{
	if($admin_check != 1)
	{
	        echo "<script>alert('권한이 없습니다!!.');";
	        echo "window.location.replace('../notice_click.php?id=$id');</script>";
		exit;
		$select_id->close();
		$mysqli->close();
	}
}

$query_comment = "DELETE FROM notice_comment WHERE notice_number = '$id'";
$delete_comment = $mysqli->query($query_comment);

if($delete_comment){
	echo "comment delete";
}

$query_board = "DELETE FROM notice WHERE number = '$id'";
$delete_board = $mysqli->query($query_board);

if($delete_board){
	echo "notice delete</br>";
}


$iplogfile = '/var/www/html/php/log.txt';
$ipaddress = $_SERVER['REMOTE_ADDR'];
$timestamp = date('d/m/Y H:i:s');
$fp = fopen($iplogfile, 'a+') or die("can't open file");
chmod($iplogfile, 0777);
fwrite($fp, '['.$timestamp.']: '.' notice 삭제 ('.$id.') '.$_SESSION['user_id'].' '.$ipaddress.' '. "\n");
fclose($fp);

echo "<script>window.location.replace('../notice.php?id=$id');</script>";
exit;

$delete_comment->close();
$delete_board->close();
$mysqli->close();

?>
