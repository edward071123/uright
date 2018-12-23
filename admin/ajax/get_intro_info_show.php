<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/calculateClass.php";
	$calculate = new Calculate();
	$get_person_intro_info_tab = $calculate->find_member_intro_info_show($member_iden,$_POST['g_date']);
	echo json_encode(array("intro_info"=>$get_person_intro_info_tab));
?>