<?php
	include "../includes/init.php";
	include '../includes/_inc.php';
	include "../includes/_chk.php";
	include "../Class/calculateClass.php";
	$calculate = new Calculate();
	//$calculate->count_member_base('2015-05-01','2015-05-31');
	echo $calculate->ckeck_pay_for_count('1040526032');
	
	//echo $calculate->all_member_dividend('H120514272');
	// echo $calculate->find_level_children1('H120514272');
	// echo "<p>";
	// echo $calculate->find_level_children2('H120514272');
	// echo "<p>";
	// echo $calculate->find_member_intro('H120514272');
	//print_r($calculate->main_count('E121106682','2015-05-26'));
?>