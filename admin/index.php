<?php 
	include "includes/_inc.php";
	include "includes/_chk_manager.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle?></title>
		<meta charset="UTF-8">
		<link href="css/login.css" rel="stylesheet" type="text/css" />
		<script>
			function login(){
				if(document.getElementsByName("account")[0].value == "")
					alert("請填寫帳號");
				else if(document.getElementsByName("password")[0].value == "")
					alert("請填寫密碼");
				else
					document.form1.submit();
			}
		</script>
	</head>
	<body style="height:100%">
		<form name="form1" action="login.php" method="post" style="height:100%;">
			<div id="wrap">
				<div id="header"></div>
				<div id="login_wrap">
					<div class="login_top">
						<div class="username">
							<p>管理者帳號(Username)</p>
							<p><input name="account" type="text"  class="keyname"/></p>
						</div>
						<div class="Password">
							<p>密碼(Password)</p>
							<p><input name="password" type="password" class="keyname"/></p>
						</div>
					</div>
					<div class="login_bot">
						<div class="enter"><a href="javascript: login();">登入</a></div>
					</div>
				</div>    
			</div>
		</form>
	</body>
</html>
