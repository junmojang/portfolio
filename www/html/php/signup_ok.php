<?php

$id=$_POST['id'];

$pw = $_POST['pw'];
$pw_check = $_POST['pw_check'];
$name=$_POST['name'];
$date = date("Y-m-d H:i:s");

if($id==NULL||$pw==NULL||$name==NULL||$pw_check==NULL)
{
	echo "<script>alert('빈칸을 모두 입력해주세요!.');";
	echo "window.location.replace('signup.php');</script>";
	exit();
}
if($pw!=$pw_check)
{
     echo "<script>alert('비밀번호가 일치하지 않습니다!.');";
     echo "window.location.replace('signup.php');</script>";
     exit();
}

$pw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
$conn = new mysqli('localhost', 'hng3412', '748596', 'MSE');
if($conn->connect_error) die($conn->connect_error);

if(isset($_POST['id']) &&
	isset($_POST['pw']) &&
	isset($_POST['name'])){

	$query = "INSERT INTO user VALUES" . "('$id','$name','$pw', default)";
	$result = $conn->query($query);

	if(!$result){
      echo "<script>alert('이미 존재하는 아이디 입니다!');";
      echo "window.location.replace('signup.php');</script>";
      exit();
	}

	 $log_txt = $id." "."회원가입"." 승인자:장준모 ".$date;
     $log_file = fopen("log.txt", "a");
     fwrite($log_file, "$log_txt\n");
     fclose($log_file);
}
echo "<script>alert('회원가입 완료~!');";
echo "window.location.replace('../index.php');</script>";
?>
