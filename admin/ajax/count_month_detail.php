<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/individualClass.php";
	$g_date = $_POST['g_date'];
	$individual = new Individual();
	$get_data = $individual->get_self_info($g_date,$member_iden);
	echo json_encode($get_data);
?>