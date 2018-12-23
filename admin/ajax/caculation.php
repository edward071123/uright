<?php
	include "../includes/init.php";
	include "../includes/_chk.php";
	include "../Class/calculateClass.php";
	$data_message = array();
	$calculate = new Calculate();
	$calculate->count_member_base($_POST['start_date'],$_POST['end_date']);
	$data_message['message'] =  $calculate->main_count();
	echo json_encode($data_message);
?>