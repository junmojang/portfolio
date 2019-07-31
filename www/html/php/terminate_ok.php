<?php
session_start();
$id = $_SESSION['user_id'];
session_destroy();
$pw_check = $_POST['pw'];
$reason = $_POST['reason'];
$agree = $_POST['agreebox'];
$date = date("Y-m-d H:i:s");

if($reason == NULL)
{
    echo "<script>alert('회원탈퇴 사유를 적어 주세요');";
    echo "window.location.replace('./terminate.php');</script>";
    exit;
}
else if($pw_check == NULL)
{
    echo "<script>alert('비밀번호를 확인해주세요');";
    echo "window.location.replace('./terminate.php');</script>";
    exit;
}
else if($agree != "agree")
{
    echo "<script>alert('동의 후 회원탈퇴 가능합니다');";
    echo "window.location.replace('./terminate.php');</script>";
    exit;
}
$mysqli = mysqli_connect('localhost', 'hng3412', '748596', 'MSE');
$query = "SELECT * from user where id = '$id'";
$result = $mysqli->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC);

$pw = $row['pw'];
if(!password_verify($pw_check, $pw))
{
    echo "<script>alert('비밀번호가 일치하지 않습니다.');";
    echo "window.location.replace('./terminate.php');</script>";
    exit;
}

$query = "SET FOREIGN_KEY_CHECKS = 0";
$result = $mysqli->query($query);

$query = "DELETE FROM user WHERE id = '$id'";
$result = $mysqli->query($query);

$query = "SET FOREIGN_KEY_CHECKS = 1";
$result = $mysqli->query($query);

$log_txt = $id." "."회원탈퇴"." 승인자:장준모 "."사유: ".$reason." ".$date;
$log_file = fopen("log.txt", "a");
fwrite($log_file, "$log_txt\n");
fclose($log_file);

echo "<script>window.location.replace('../index.php');</script>";
exit;
?>
