<?php
	include "../includes/init.php";
	include "../includes/_chk_member.php";
	$s_date = $_POST['s_date'];
	$e_date = $_POST['e_date'];
	$sql = "SELECT mm.m_identify,mm.m_name
			FROM member as  mm,level_relationship
			WHERE mm.m_intro_idn = ?
			AND mm.m_identify = level_relationship.children
			AND (mm.m_sign_date BETWEEN '2015-05-01' AND ?)";
	$money_lists = $db->rawQuery ($sql, array($member_iden,$e_date));
	$ii = 1;
	$sum = 0;
	$data = array();
	foreach ($money_lists as $money_list) {
		$tmp_income = 0;
		$sql_chi_ac = " SELECT m1.income
				FROM member AS mm
				LEFT JOIN
				(	SELECT SUM(money_fifteen) AS income,to_m_number
					FROM money_log
					WHERE (log_date BETWEEN ? AND ?)
					and logname_ref = 1
					and reference = 1
					GROUP BY to_m_number
				) AS m1 ON m1.to_m_number = mm.m_identify
				INNER JOIN level_relationship AS lr
				ON mm.m_identify = lr.children
				WHERE mm.m_identify  LIKE ?
				AND (mm.m_sign_date BETWEEN '2015-05-01' AND ?)";
		$chi_acs = $db->rawQuery ($sql_chi_ac, array($s_date,$e_date,'%'.$money_list['m_identify'].'%',$e_date));
		foreach ($chi_acs as $chi_ac) 
			$tmp_income += (int)($chi_ac['income']*100/15);
		$sub = array();
		$sub['no']  = $ii;
		$get_name_len = iconv_strlen($money_list["m_name"], 'utf-8');
		if($get_name_len > 1){
			$get_first_w = iconv_substr($money_list["m_name"], 0, 1, 'utf-8');
			$get_third_w = iconv_substr($money_list["m_name"], 2, 1, 'utf-8');
			$name = $get_first_w."o".$get_third_w;
		}else{
			$name = $money_list["m_name"];
		}
		$sub['m_name']  = $name;
		$sub['m_money'] = $tmp_income;
		$ii ++;
		array_push($data ,  $sub);
	}
	echo json_encode($data);
?>