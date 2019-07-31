<!DOCTYPE html>
<?php session_start(); ?>
<html>

	<head>
		<title>회원탈퇴</title>
		<meta charset = "utf-8">
	</head>

	<body>
		<form action = "./terminate_ok.php" method="post">
			<div class="password">
				<label for="pw">비밀번호를 확인해주세요</label>
				<input type="password" name="pw" class="inp"/>
			</div>
      <div class="reason">
        <label for="reason">회원탈퇴 사유를 적어주세요</label>
        <input type="text" name="reason"/>
      </div>
      <div class="notice"></br>
        사용자에 대한 접근권한 부여⦁변경⦁말소 내역을 기록하고 3년간 보관합니다</br>
        접근권한 신청자•신청일시•승인자•사유 등 내역을 기록 및 보관합니다.</br>
        <input type="checkbox" name="agreebox" value="agree">동의합니다<br>
      </div>
			<p><input type="submit" value="회원탈퇴" class="btn"/>
		</form>
	</body>
</html>
