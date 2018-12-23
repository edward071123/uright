<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/individualClass.php";
	if(empty($_REQUEST['g_date']))
		return false;
    	$get_date = $_REQUEST['g_date'];
    	$get_member = $_REQUEST['member'];
    	$individual = new Individual();
    	$count = $individual->get_system_count($get_date,$get_member);
	echo json_encode(array("count"=>$count));
?>