<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/calculateClass.php";
	$calculate = new Calculate();
	$get_person_intro = $calculate->find_member_intro_show($member_iden);
	$get_association_intro = $calculate->find_association_total_member_show();
	echo json_encode(array("pintro"=>$get_person_intro,"aintro"=>$get_association_intro));
?>