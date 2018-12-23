<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/positionClass.php";
	$m_intro_number = $_POST['intro'];
	$m_identify = $_POST['identify'];
	$position = new Position();
	$get_data = $position->auto_level_position($m_identify,$m_intro_number);
	echo json_encode($get_data);
?>