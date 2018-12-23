<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/positionClass.php";
	$get_identify = $_POST['identify'];
	$position = new Position();
	$get_data = $position->delete_position($get_identify);
     	echo json_encode($get_data);
?>