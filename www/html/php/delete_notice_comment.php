<?php
session_start();
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$mysqli = mysqli_connect('localhost','junmo14','mse','MSE');
$id = $_GET['id'];
$number = $_GET['number'];



$query_name = "SELECT * from notice_comment where notice_number = '$id' and number = '$number'";
$select_name = $mysqli->query($query_name);
$check_name = $select_name->fetch_array(MYSQLI_ASSOC);

$query_admin = "SELECT admin from user where id = '$user_id'";
$select_admin = $mysqli->query($query_admin);
$check_admin = $select_admin->fetch_array(MYSQLI_ASSOC);
$admin_check = $check_admin['admin'];

if($select_id)
{
	if($admin_check != 1)
	{
					echo $user_name;
					echo $check_name['writer'];
	        echo "<script>alert('권한이 없습니다!!.');";
	        echo "window.location.replace('../notice_click.php?id=$id');</script>";
		exit;
		$select_name->close();
		$mysqli->close();
	}
}

$check = "DELETE FROM notice_comment WHERE notice_number = '$id' and number = '$number'";
$result = $mysqli->query($check);

if(!$result){
	echo " fail";
}else{
	echo "comment delete</br>";
}
echo "<script>window.location.replace('../notice_click.php?id=$id');</script>";
exit;

?>
