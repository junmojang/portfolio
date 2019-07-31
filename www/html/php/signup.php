<!DOCTYPE html>
<html>

	<head>
		<title> 회원가입</title>
		<link href="../css/signup_css.css" type="text/css" rel="stylesheet">
		<meta charset = "utf-8">
	</head>

	<body>
		<form action = "./signup_ok.php" method="post">
			<div class="student_id">
				<label for="id"> 학번 </label>
				<input type="text" name="id" class="inp"/>
				*ID는 학번과 동일합니다. 다른 사람의 학번을 도용하시면 통보없이 삭제 조치 됩니다.
			</div>
			<div class="password">
				<label for="pw">비밀번호 </label>
				<input type="password" name="pw" class="inp"/>
				*비밀번호는 영어 대/소문자, 특수문자, 숫자 중 2조합으로 8자리 이상을 권고드립니다.
			</div>
			<div class="check_password">
				<label for"pw_check">비밀번호 확인</label>
				<input type="password" name="pw_check" class="inp"/>
			</div>
			<div class="name">
				<label for="name"> 이름 </label>
				<input type="text" name="name" class="inp"/>
				*이름은 실명이 아닐 경우 통보없이 삭제 조치 됩니다.
			</div>
			<p><input type="submit" value="회원가입" class="btn"/>
		</form>
	</body>
</html>
