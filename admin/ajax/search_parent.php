<?php
	include "../includes/init.php";
	include "../includes/_chk.php";
	include "../Class/positionClass.php";
	$s_name = $_POST['s_name'];
	$s_mobile= $_POST['s_mobile'];
	$s_identify = $_POST['s_identify'];
	$position = new Position();
	$get_data = $position->search_parent($s_identify,$s_name,$s_mobile);
	echo json_encode($get_data);
	$params = array();
?>