<?php 
	session_start(); 
	header("Content-Type:text/html; charset=utf-8");
	//將session清空
	unset($_SESSION['manager_id']);
	unset($_SESSION['manager_name']);
	unset($_SESSION['manager_type']);
	unset($_SESSION['user_excel']);
	unset($_SESSION['point_excel']);
	echo '登出中......';
	echo '<meta http-equiv=REFRESH CONTENT=1;url=index.php>';
?>