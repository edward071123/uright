<?php
	include "../includes/init.php";
	include "../includes/_chk.php";
	include("../includes/PHPExcel.php");
	include("../includes/PHPExcel/IOFactory.php");
	include "../Class/reportClass.php";
	if(isset($_GET['getdate']))
		$g_date = $_GET['getdate'];
	else
		$g_date = date("Y-m");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', '項次');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', '會員身分字號');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', '會員姓名');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', '母球實際善款點數(80%)');
    	$objPHPExcel->getActiveSheet()->SetCellValue('E1', '子球實際善款點數(80%)');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', '實際善款總點數(80%)');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', '本月招募人數');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', '會員身份');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', '本月行政費A來源15%總計');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', '本月行政費B來源9%總計');
   	$objPHPExcel->getActiveSheet()->SetCellValue('K1', '本月組長基數配點總計');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', '分配行政作業費點數(70%)');
	$objPHPExcel->getActiveSheet()->SetCellValue('M1', '本月可用點數');

	$report = new Report();
	$money_infos = $report->get_member_money_info($g_date);
	$cnt = 2;
	foreach ($money_infos as  $money_info) {
		$get_m_name  = $money_info['m_name'];
		$idn = $money_info['m_identify'];
		$get_s_eight_income = 0;
		$get_o_eight_income = 0;
		$get_s_seven_income = 0;
		$get_s_ch1_income = 0;
		$get_s_ch2_income = 0;
		$get_s_div_income = 0;
		$intro_num = 0;
		$cla = "贊助會員";

		if(!empty($money_info['get_s_eight_income']))
			$get_s_eight_income = $money_info['get_s_eight_income'];
		if(!empty($money_info['get_o_eight_income']))
			$get_o_eight_income = $money_info['get_o_eight_income'];
		if(!empty($money_info['get_s_seven_income']))
			$get_s_seven_income = $money_info['get_s_seven_income'];
		if(!empty($money_info['get_s_ch1_income']))
			$get_s_ch1_income = $money_info['get_s_ch1_income'];
		if(!empty($money_info['get_s_ch2_income']))
			$get_s_ch2_income = $money_info['get_s_ch2_income'];
		if(!empty($money_info['get_s_div_income']))
			$get_s_div_income = $money_info['get_s_div_income'];
		if(!empty($money_info['intro_num']))
			$intro_num = $money_info['intro_num'];

		if(($intro_num>0) && ($intro_num <= 2))
			$cla = "輔導員";
		else if(($intro_num>2) && ($intro_num <= 9))
			$cla = "小組長";
		else if($intro_num>9)
			$cla = "愛心組長";

		$total_eight_income = $get_s_eight_income + $get_o_eight_income;
		$total_use_income = $get_s_seven_income + $total_eight_income;
		$total_use_income = $get_s_seven_income + $total_eight_income;
		if($total_use_income != 0){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$cnt, $cnt-1);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$cnt, $idn);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$cnt, $get_m_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$cnt, $get_s_eight_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$cnt, $get_o_eight_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$cnt, $total_eight_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$cnt, $intro_num);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$cnt, $cla);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$cnt, $get_s_ch1_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$cnt, $get_s_ch2_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$cnt, $get_s_div_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$cnt, $get_s_seven_income);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$cnt, $total_use_income);
			$cnt++;
		}
	}
	$PHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$PHPExcelWriter->save($g_date.'月結簡表.xls');
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=\"".$g_date."月結簡表.xls\"");
	header("Content-Length: ".filesize($g_date.'月結簡表.xls'));
	@readfile($g_date.'月結簡表.xls');
?>