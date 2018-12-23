<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/reportClass.php";
	$g_date = $_REQUEST['g_date'];
	$report = new Report();
	$responce = $report->show_company_account_use($g_date);
	echo json_encode($responce);
?>