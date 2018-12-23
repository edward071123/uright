<?php 
	session_start(); 
	header("Content-Type:text/html; charset=utf-8");
	//將session清空
	unset($_SESSION['m_mobile']);
	unset($_SESSION['m_id']);
	echo '登出中......';
	echo '<meta http-equiv=REFRESH CONTENT=1;url=../index.php>';
?>