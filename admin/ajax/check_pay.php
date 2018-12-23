<?php 
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/levelClass.php";

	$single_member = $_POST['member_iden'];
	$manager_id = $_SESSION['manager_id'];
	$member_intro_iden = $_POST['member_intro_iden'];
	$member_intro_level = $_POST['member_intro_level'];
	$position = $_POST['position'];

	$level = new Level($single_member,$member_intro_iden,$position,$member_intro_level,$manager_id );
	$error = $level->main();
	$data_message = array();
	if(empty($error))
		$data_message['status']  = "安置成功！！ 此會員自動排列到階層中";
	else
		$data_message['status']  = $error;
	echo json_encode($data_message);

?>