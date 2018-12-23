<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	$g_date = $_REQUEST['g_date'];

	$sql = "SELECT m3.m_identify,m3.m_name,m7.m_address,m3.m_gender,m1.intro_count,m5.get_m_t_income
		FROM member AS m3
		LEFT JOIN
		(	SELECT COUNT(id) AS intro_count ,m_intro_idn
			FROM member
			WHERE (SUBSTRING(m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)
			GROUP BY m_intro_idn
			)AS m1 ON m1.m_intro_idn = m3.m_identify
		LEFT JOIN
		(	SELECT sum(money) AS get_m_t_income , to_m_number
			FROM money_log
			WHERE (SUBSTRING(log_date, 1, 7) BETWEEN '2015-05' AND  ?)
			AND logname_ref = 1
			GROUP BY to_m_number
		)AS m5 ON m5.to_m_number = m3.m_identify
		LEFT JOIN
		(	SELECT SUBSTRING(m_live_address, 1, 3) AS m_address ,m_identify
			FROM imfomation
		)AS m7 ON m7.m_identify = m3.m_identify
		WHERE (SUBSTRING(m3.m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)
		AND m3.m_identify NOT LIKE '%-%'
		ORDER BY m7.m_address,id";
	$params = array ($g_date,$g_date,$g_date);
	$members = $db->rawQuery ($sql , $params);
	$ii=0;
	foreach ($members as $member) {
		$get_m_number = $member['m_identify'];
		$get_m_name  = $member['m_name'];
		$m_address  = $member['m_address'];
		$get_m_gender  = $member['m_gender'];
		$idn = substr_replace($member['m_identify'],"***",3,4);
		if(empty($member["get_m_t_income"]))
			$m_total = 0;
		else
			$m_total = $member['get_m_t_income'];
		$intro_count = 0;
		$m_ac_count = 0;
		if(!empty($member["intro_count"]))
			$intro_count = $member["intro_count"];
		if(!empty($member["self_ac_count"]))
			$m_ac_count = $member["self_ac_count"];
		$cla =  "贊助會員";
		if(($intro_count>0) && ($intro_count <= 2)){
			$cla = "輔導員";
		}else if(($intro_count>2) && ($intro_count <= 9)){
			$cla = "小組長";
		}else if($intro_count>9){
			$cla = "組長";
		}
		if(empty($member["get_m_t_income"]))
			$child_m_total = 0;
		else
			$child_m_total = $member['get_m_t_income'];
		if($intro_count != 0){
			$responce->rows[$ii]['no']=$ii+1;
			$responce->rows[$ii]['cell']=array($ii+1,$idn,$m_address,$get_m_name,$cla,
			$intro_count,$get_m_gender,$m_total);
			$ii++;
		}
	}
	echo json_encode($responce);
?>