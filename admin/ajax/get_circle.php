<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/individualClass.php";
    	$individual = new Individual();
    	$data = $individual->get_circle($member_iden);
	echo json_encode(array("table_info"=>$data));
?>