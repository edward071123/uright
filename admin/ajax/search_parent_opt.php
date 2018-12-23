<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/positionClass.php";
	$m_identify = $_POST['so_identify'];
	$position = new Position();
	$get_data = $position->search_parent_opt($m_identify);
	echo json_encode($get_data);
?>