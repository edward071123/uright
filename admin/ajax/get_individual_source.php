<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/individualClass.php";
	if(empty($_REQUEST['g_date']))
		return false;
	$get_date = $_REQUEST['g_date'];
    	$individual = new Individual();
	$responce = $individual->get_indviaual_source($get_date,$member_iden);
	echo json_encode($responce);
?>