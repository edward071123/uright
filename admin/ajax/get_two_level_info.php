<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	include "../Class/individualClass.php";
	if(empty($_REQUEST['g_date']))
		return false;
	$get_date = $_REQUEST['g_date'];
    	$individual = new Individual();
	$individual->get_two_level($get_date,$member_iden);
	$data = array();
	$data['list_ta'] = $individual->get_two_level_list_ta() ;
	$data['list_tb'] = $individual->get_two_level_list_tb() ;
	echo json_encode($data);
?>