<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/positionClass.php";
	include "../Class/calculateClass.php";
	$m_identify = $_POST['m_identify'];
	$position = new Position();
	$calculate = new Calculate();
	$get_intros = $position->search_self_intro($m_identify);
	$list1 = "";
	$list2 = "";
	$list3 = "";
	$count = 0;
	foreach ($get_intros as $get_intro) {
		$check_pay_status = "未繳費";
		if(!strpos($get_intro["m_identify"], "-")){
			if($calculate->ckeck_pay_for_count($get_intro["m_identify"])){
				$check_pay_status = "已繳費";
				$list1 .= '<tr><td>'.$get_intro["m_identify"].'</td><td>'.$get_intro["m_name"].'</td><td>'.$check_pay_status.'</td></tr>';
				$count++;
			}else
				$list2.= '<tr><td>'.$get_intro["m_identify"].'</td><td>'.$get_intro["m_name"].'</td><td>'.$check_pay_status.'</td></tr>';
		}
	}
	$data_message = array('html'=> $list1.$list2.$list3 , 'count'=>$count);
	echo json_encode($data_message);
?>