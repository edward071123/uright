<?php 
class Individual{
	private $bank_number = '1040526032';
	public $member_iden,$db,$system_count,$chile_list_ta,$chile_list_tb;
	function __construct(){
		global $db;
		$this->db =  $db; 
	}
	//大循環
	public function get_circle($member_iden){
		//取得2015-05到目前的月份
		$start_date = new DateTime('2015-05');
		$now_date = new DateTime();
		$chile_list_tb = "";
		$iii =1;
		$fir_m = "";
		$total_ball = 0;
		$last_remain = 0;
		while($start_date <= $now_date){
			$get_date = $start_date->format('Y-m');
			$sql_self = "SELECT SUM(m1.money) AS total_m,m1.to_m_number,m2.get_ta_income,
			m3.get_tb_income
			FROM money_log AS m1
			LEFT JOIN
			(   	SELECT SUM(money) AS get_ta_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 2
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			)AS m2 ON m2.to_m_number LIKE CONCAT('%', m1.to_m_number ,'%')
			LEFT JOIN
			(   	SELECT SUM(money) AS get_tb_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 2
				AND reference = 1
				AND type = 2
				GROUP BY to_m_number
			)AS m3 ON m3.to_m_number LIKE CONCAT('%', m1.to_m_number ,'%')
			WHERE  m1.to_m_number = ? AND
			SUBSTRING(m1.log_date, 1, 7) = ?";

			$child_lists = $this->db->rawQuery ($sql_self, array($get_date,$get_date,$member_iden,$get_date));
			foreach ($child_lists as $child_list) {
				$get_ta_income = 0;
				$get_tb_income = 0;
				$self_c = 0;
				$cadre_c = 0;
				if(!empty($child_list['get_ta_income']))
					$get_ta_income = $child_list['get_ta_income'];
				if(!empty($child_list['get_tb_income']))
					$get_tb_income = $child_list['get_tb_income'];

				$self_c = $get_ta_income*2;
				$cadre_c = $get_tb_income*2;
				$tmp_count = (int)(($self_c+$cadre_c)/2);
				$get_ball = (int)(($last_remain+$tmp_count)/3600);
				$total_ball += $get_ball;
				$reamin = $tmp_count - $get_ball*3600 ;
				$reamin += $last_remain;
				$to = $last_remain + $tmp_count;
				$chile_list_tb .= '<tr>
				<td class="text-center">'.$iii.'</td>
				<td class="text-center">'.$get_date.'</td>
				<td class="text-center">'.$self_c.'</td>
				<td class="text-center">'.$cadre_c.'</td>
				<td class="text-center">'.($self_c+$cadre_c).'</td>
				<td class="text-center">'.$tmp_count.'</td>
				<td class="text-center">'.$tmp_count.'</td>
				<td class="text-center">'.$last_remain.'</td>
				<td class="text-center">'.$to.'</td>
				<td class="text-center">'.$get_ball.'</td>
				<td class="text-center">'.$total_ball.'</td>
				<td class="text-center">'.$reamin.'</td>
				</tr>';
				$iii++;
				$last_remain = $reamin;
			}
			$start_date->modify('+1 month');
		}
		return $chile_list_tb;
	}
	//取得兩層收入來源
	public function get_two_level($date,$member_iden){
		$start_day = date('Y-m-01', strtotime($date));
		$dates = $this->get_two_level_date($date);
		$month_ch1 = 0;
		$month_ch2 = 0;
		$month_div = 0;
		$this->chile_list_tb = "";
		$iii =1;
		foreach ($dates as $date) {
			$child_lists = $this->db->rawQuery ($this->get_two_level_child_sql(), $this->get_two_level_child_params($date['value'],$member_iden));
			$child_m_total = 0;
			foreach ($child_lists as $child_list) {
				$total_new_come = 0;
				$total_base = 0;
				$self_base = 0;
				$get_m_number = $child_list['m_identify'];
				$intro_count = 0;
				$ib_count = 0;
				if(!empty($child_list["intro_count"]))
					$intro_count = $child_list["intro_count"];
				if(!empty($child_list["intro_b_count"]))
					$ib_count = $child_list["intro_b_count"];
				$cla =  "贊助會員";
				$ch1_income = 0;
				$ch2_income = 0;
				$dividend = 0;
				$total_base = 0;
				$self_base = 0;
				if(!empty($child_list["base"]))
					$total_base = $child_list["base"];
				if(!empty($child_list["ssbase"]))
					$self_base = $child_list["ssbase"];
				if(!empty($child_list["get_s_ch1_income"]))
					$ch1_income = $child_list["get_s_ch1_income"];
				if(!empty($child_list["get_s_ch2_income"]))
					$ch2_income = $child_list["get_s_ch2_income"];
				if(!empty($child_list["get_s_div_income"]))
					$dividend = $child_list["get_s_div_income"];
				$month_ch1 += $ch1_income;
				$month_ch2 += $ch2_income;
				if(($intro_count>0) && ($intro_count <= 2))
					$cla = "輔導員";
				else if(($intro_count>2) && ($intro_count <= 9))
					$cla = "小組長";
				else if($intro_count>9)
					$cla = "愛心組長";
				$month_div += $dividend;
				$ch_total = $ch1_income + $ch2_income + $dividend;
				$ch_12 = $ch1_income + $ch2_income;
				$this->chile_list_tb .= '<tr>
				<td class="text-center" style="width:100px">'.$date['value'].'</td>
				<td class="text-center">'.$cla.'</td>
				<td class="text-center">'.$intro_count.'</a></td>
				<td class="text-center">'.$ch1_income.'</td>
				<td class="text-center">'.$ib_count.'</td>
				<td class="text-center">'.$ch2_income.'</td>
				<td class="text-center"><a href="two_level_souce.php?startDay='.$start_day.'&endDay='.$date['value'].'&iden='.$member_iden.'" target="_blank">'.$ch_12.'</a></td>
				<td class="text-center">'.$total_base.'</td>
				<td class="text-center">'.$self_base.'</td>
				<td class="text-center">'.$dividend.'</td>
				<td class="text-center">'.$ch_total.'</td>
				</tr>';
				$iii++;
			}
			$start_day = date ("Y-m-d", strtotime("+1 days", strtotime($date['value'])));
		}
		$month_total = $month_ch1+$month_ch2+$month_div;
		$this->chile_list_ta = "";
		if($iii > 1){
			$this->chile_list_ta .= '<tr>
			<td class="text-center">'.$month_ch1.'</td>
			<td class="text-center">'.$month_ch2.'</td>
			<td class="text-center">'.$month_div.'</td>
			<td class="text-center">'.$month_total.'</td>
			</tr>';
		}
	}
	public function get_two_level_list_ta(){
		return $this->chile_list_ta;
	}
	public function get_two_level_list_tb(){
		return $this->chile_list_tb;
	}
	//取得所有母球+子球
	public function get_all_indviaual($date,$member_iden){
		$sql = "SELECT m3.m_identify,m2.get_m_t_income ";
		if($member_iden == $this->bank_number)
			$sql = "SELECT * ,m2.get_m_t_income ";
		$sql .= "FROM member AS m3
			LEFT JOIN
			(
				SELECT SUM(money) AS get_m_t_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			) AS m2 ON m2.to_m_number = m3.m_identify
			WHERE m3.m_identify LIKE ? ORDER BY m_identify";
		$persons = $this->db->rawQuery ($sql, array($date,"%".$member_iden."%"));
		$ii=0;
		foreach ($persons as $person) {
			$idn = substr_replace($person['m_identify'],"***",3,4);
			$child_m_total = 0;
			$child_m_total = (int)$person['get_m_t_income']/80*100;
			$responce->rows[$ii]['no']=$ii;
			$responce->rows[$ii]['cell']=array($ii,$person['m_identify'],$idn.'/'.$person['m_identify'],$child_m_total);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	//取得體系
	public function get_system_count($date,$member_iden){
		$this->system_count = 0;
		$this->find_system_count($member_iden,$date);
		return $this->system_count ;
	}
	//取得每個球的收入來源
	public function get_indviaual_source($date,$member_iden){
		if($member_iden == $this->bank_number)
			return $this->get_bank_source_sql($date);
		else
			return $this->get_person_source_sql($date,$member_iden);	
	}
	//取得個人收入資訊
	public function get_self_info($date,$member_iden){
		//get_s_m_income 母球帳戶本月收入(db只能抓出 90% 要在計算出100%)
		//get_o_m_income 子球帳戶本月收入(db只能抓出 90% 要在計算出100%)
		//get_t_m_income = get_s_m_income+get_o_m_income 本月總收入
		//get_t_m_80_income = 本月總收入80%
		//get_t_m_10_a_income = 本月總收入10%自行累積
		//get_t_m_10_b_income = 本月總收入10%協會累積
		//$intro_count = 本月招募人數
		//get_s_ch1_income 個人分配ch1
		//get_s_ch2_income 個人分配ch2
		//get_s_div_income 個人分配div
		//get_c_70_income 本月組長分發可用收入70%
		//=>(get_s_ch1_income+get_s_ch2_income+get_s_div_income)*70/100
		//get_c_15_a_income 本月組長分發可用收入15%自行累積
		//=>(get_s_ch1_income+get_s_ch2_income+get_s_div_income)*15/100
		//get_c_15_b_income 本月組長分發可用收入15%協會累積
		//=>(get_s_ch1_income+get_s_ch2_income+get_s_div_income)*15/100
		//get_80_y_income 年累積可用點數80%
		//get_70_y_income 年累積可用點數70%
		$data = array();
		$persons = $this->db->rawQuery ($this->info_basic_sql(), $this->info_basic_params_array($date,$member_iden));
		$get_s_m_income = 0;
		$get_o_m_income = 0;
		$get_t_m_income = 0;
		$get_t_m_80_income = 0;
		$get_t_m_10_a_income = 0;
		$get_t_m_10_b_income = 0;
		$intro_count = 0;
		$get_s_ch1_income = 0;
		$get_s_ch2_income = 0;
		$get_s_div_income = 0;
		$get_c_total_income = 0;
		$get_c_70_income = 0;
		$get_c_15_a_income = 0;
		$get_c_15_b_income = 0;
		$get_70_80_m_income = 0;
		$get_80_y_income = 0;
		$get_70_y_income = 0;
		$get_70_80_y_income = 0;
		$get_funeral = 0;
		$cla =  "贊助會員";
		if(!empty($persons[0]["get_s_m_income"]))
			$get_s_m_income = ((int)$persons[0]["get_s_m_income"]*100/80);
		if(!empty($persons[0]["get_o_m_income"]))
			$get_o_m_income = ((int)$persons[0]["get_o_m_income"]*100/80);
		if(!empty($persons[0]["intro_num"]))
			$intro_count = $persons[0]["intro_num"];
		if(!empty($persons[0]['get_s_ch1_income']))
			$get_s_ch1_income = $persons[0]['get_s_ch1_income'];
		if(!empty($persons[0]['get_s_ch2_income']))
			$get_s_ch2_income = $persons[0]['get_s_ch2_income'];
		if(!empty($persons[0]['get_s_div_income']))
			$get_s_div_income = $persons[0]['get_s_div_income'];
		if(!empty($persons[0]['funeral']))
			$get_funeral = $persons[0]['funeral'];

		if(($intro_count>0) && ($intro_count <= 2))
			$cla = "輔導員";
		else if(($intro_count>2) && ($intro_count <= 9))
			$cla = "小組長";
		else if($intro_count>9)
			$cla = "愛心組長";

		$year_infos = $this->db->rawQuery ($this->info_year_sql(), $this->info_year_params_array ($date,$member_iden));
		if(!empty($year_infos[0]['get_80_y_income']))
			$get_80_y_income = $year_infos[0]["get_80_y_income"];
		if(!empty($year_infos[0]['get_70_y_income']))
			$get_70_y_income = $year_infos[0]['get_70_y_income'];

		$get_t_m_income = $get_s_m_income+$get_o_m_income;
		$get_t_m_80_income = $get_t_m_income*80/100;
		$get_t_m_10_a_income = $get_t_m_income*10/100;
		$get_t_m_10_b_income = $get_t_m_income*10/100;
		$get_c_total_income = $get_s_ch1_income+$get_s_ch2_income+$get_s_div_income;
		$get_c_70_income = round($get_c_total_income*70/100);
		$get_c_15_a_income = round($get_c_total_income*15/100);
		$get_c_15_b_income = round($get_c_total_income*15/100);
		$get_70_80_m_income = $get_t_m_80_income+$get_c_70_income;
		$get_70_80_y_income = $get_80_y_income+$get_70_y_income;
		$data["self_basic"] = $get_s_m_income;
		$data["self_o_basic"] = $get_o_m_income;
		$data["self_bo_basic"] =  $get_t_m_income;
		$data["self_10_ta_basic"] =  $get_t_m_10_a_income;
		$data["self_10_tb_basic"] =  $get_t_m_10_b_income;
		$data["self_80_basic"] =  $get_t_m_80_income;
		$data["self_70_cadre"] =  $get_c_70_income;
		$data["self_15_a_cadre"] =  $get_c_15_a_income;
		$data["self_15_b_cadre"] =  $get_c_15_b_income;
		$data["self_total_cadre"] =  $get_c_total_income;
		$data["self_70_80_m_income"] =  $get_70_80_m_income;
		$data['intro_count'] = $intro_count;
		$data['cla'] = $cla;
		$data["get_y_80_income"] =  $get_80_y_income;
		$data["get_y_70_income"] =  $get_70_y_income;
		$data["get_70_80_y_income"] =  $get_70_80_y_income;
		$data["funeral"] = 0 - (int)$get_funeral ;
		return $data;
	}
	private function info_year_sql(){
		$sql = "SELECT m1.m_identify ,
			SUM(m8.80_y_income) AS get_80_y_income,m9.get_70_y_income
			FROM member AS m1
			LEFT JOIN(
				SELECT SUM(money) AS 80_y_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref =1
				AND reference = 1
				AND TYPE = 1
				GROUP BY to_m_number
			) AS m8 ON m8.to_m_number LIKE CONCAT('%', m1.m_identify ,'%')
			LEFT JOIN(
				SELECT SUM(money) AS get_70_y_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND TYPE = 2
				GROUP BY to_m_number
			) AS m9 ON m9.to_m_number = m1.m_identify
			WHERE m1.m_identify = ?";
		return $sql;
	}
	private function info_year_params_array($date,$member_iden){
		return array ($date,$date,$member_iden);
	}
	private function info_basic_sql(){
		$sql = "SELECT m1.m_identify ,m2.get_s_m_income,SUM(m3.o_m_income) AS get_o_m_income,
			m4.intro_num,m5.get_s_ch1_income,m6.get_s_ch2_income,m7.get_s_div_income,m8.funeral 
			FROM member AS m1
			LEFT JOIN(
				SELECT SUM(money) AS get_s_m_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			) AS m2 ON m2.to_m_number = m1.m_identify
			LEFT JOIN(
				SELECT SUM(money) AS o_m_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND TYPE = 1
				GROUP BY to_m_number
			) AS m3 ON m3.to_m_number LIKE CONCAT('%', ? ,'%') AND m3.to_m_number <> m1.m_identify
			LEFT JOIN
			(	SELECT COUNT(id) AS intro_num ,m_intro_idn
				FROM member
				WHERE (SUBSTRING(m_sign_date,1,7) BETWEEN '2015-05' AND ?)
				GROUP BY m_intro_idn
			)AS m4 ON m4.m_intro_idn = m1.m_identify
			LEFT JOIN
			(   
				SELECT SUM(money) AS get_s_ch1_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 5
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
			)AS m5 ON m5.to_m_number = m1.m_identify
			LEFT JOIN
			(   
				SELECT SUM(money) AS get_s_ch2_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
			)AS m6 ON m6.to_m_number = m1.m_identify
			LEFT JOIN
			(   
				SELECT SUM(money) AS get_s_div_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 7
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
			)AS m7 ON m7.to_m_number = m1.m_identify
			LEFT JOIN
			(   
				SELECT SUM(money) AS funeral,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 0
				AND type = 7
				GROUP BY to_m_number
			)AS m8 ON m8.to_m_number = m1.m_identify
			WHERE m1.m_identify = ?";
		return $sql;
	}
	private function info_basic_params_array($date,$member_iden){
		return array ($date,$date,$member_iden,$date,$date,$date,$date,$date,$member_iden);
	}
	//體系數計算
	private function find_system_count($parent,$date){
		$sql = "SELECT lr.children
			FROM level_relationship AS lr
			INNER JOIN
			(
				SELECT m_identify FROM member 
				WHERE (SUBSTRING(m_sign_date,1,7) >= '2015-05' AND 
				SUBSTRING(m_sign_date,1,7) <= ?)
			) AS m1 ON m1.m_identify = lr.children
			WHERE lr.parent = ?";
		$childrens = $this->db->rawQuery ($sql, array($date,$parent));
		foreach ($childrens as $children) {
			$this->system_count++;
			$this->find_system_count($children['children'],$date);
		}
	}
	private function get_bank_source_sql($date){
		$sql = "SELECT DISTINCT money_log.from_m_number,
			member.m_name , money_log.money,m4.level
			FROM money_log,member
			LEFT JOIN
			(
				SELECT level,children
				FROM level_relationship
			) AS m4 ON m4.children = member.m_identify
			WHERE money_log.to_m_number = ?
			AND money_log.from_m_number = member.m_identify
			AND SUBSTRING(money_log.log_date, 1, 7) = ?
			AND money_log.type = 1 AND money_log.logname_ref = 1
			AND money_log.reference = 1 ORDER BY member.id";
		$persons = $this->db->rawQuery ($sql, array($this->bank_number,$date));
		$ii=0;
		foreach ($persons as $person) {
			$name = $person['m_name'];
			$get_name_len = iconv_strlen($person['m_name'], 'utf-8');
			if($get_name_len > 1){
				$get_first_w = iconv_substr($person['m_name'], 0, 1, 'utf-8');
				$get_third_w = iconv_substr($person['m_name'], 2, 1, 'utf-8');
				$name = $get_first_w."o".$get_third_w;
			}
			if(!empty($person["money"]))
				$m_money = (int)($person["money"] *100/80);
			$responce->rows[$ii]['id']=$ii+1;
			$responce->rows[$ii]['cell']=array($ii+1,$name,$person['from_m_number'],$person['level'],$m_money);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	private function get_person_source_sql($date,$member_iden){
		$sql = "SELECT DISTINCT m1.from_m_number,m3.member_name,m4.level,
			m2.money_sum,lv.top_level
			FROM money_log AS m1
			LEFT JOIN
			(
				SELECT sum(money) AS money_sum,from_m_number,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND reference = 1
				AND type = 1
				GROUP BY from_m_number,to_m_number
			) AS m2 ON (m2.from_m_number = m1.from_m_number AND m2.to_m_number = m1.to_m_number)
			LEFT JOIN
			(
				SELECT m_name AS member_name,m_identify
				FROM member
			) AS m3 ON m3.m_identify = m1.from_m_number
			LEFT JOIN
			(
				SELECT level,children
				FROM level_relationship
			) AS m4 ON m4.children = m1.from_m_number
			JOIN(
				SELECT level AS top_level
				FROM level_relationship
				WHERE children = ?
			)AS lv
			WHERE m1.reference = 1 AND  m1.type = 1
			AND SUBSTRING(m1.log_date, 1, 7) = ?
			AND m1.to_m_number = ? ORDER BY m4.level";
		$persons = $this->db->rawQuery ($sql, array($date,$member_iden,$date,$member_iden));
		$ii=0;
		foreach ($persons as $person) {
			$name = $person['member_name'];
			$get_name_len = iconv_strlen($person['member_name'], 'utf-8');
			if($get_name_len > 1){
				$get_first_w = iconv_substr($person['member_name'], 0, 1, 'utf-8');
				$get_third_w = iconv_substr($person['member_name'], 2, 1, 'utf-8');
				$name = $get_first_w."o".$get_third_w;
			}
			if(!empty($person["money_sum"]))
				$m_money = (int)($person["money_sum"] *100/90);
			$level = ( (int)$person['level'] - (int)$person['top_level']);
			$responce->rows[$ii]['id']=$ii+1;
			$responce->rows[$ii]['cell']=array($ii+1,$name,$person['from_m_number'],$level,$m_money);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	private function get_two_level_date($date){
		$sql = "SELECT value FROM operation_log
				WHERE item = 2
				AND  SUBSTRING(value, 1, 7) = ?
				ORDER BY id";
		return $this->db->rawQuery ($sql, array($date));
	}
	private function get_two_level_child_sql(){
		return "SELECT m1.m_name,m1.m_identify,m2.get_A_count,
		m5.get_s_ch1_income,m6.get_s_ch2_income,m7.get_s_div_income,m8.intro_count,
		m9.intro_b_count,sum(floor(m10.pbase/10))AS base,  floor(m11.sbase/10) AS ssbase
		FROM member AS m1
		LEFT JOIN
		(   	SELECT SUM(id) AS get_A_count ,m_intro_idn
			FROM member
			WHERE (m_sign_date BETWEEN '2015-05-01' AND ?)
		)AS m2 ON m2.m_intro_idn = m1.m_identify
		LEFT JOIN
		(   	SELECT SUM(money) AS get_s_ch1_income,to_m_number
			FROM money_log
			WHERE log_date = ?
			AND logname_ref = 5
			AND reference = 1
			AND type = 4
			GROUP BY to_m_number
		)AS m5 ON m5.to_m_number = m1.m_identify
		LEFT JOIN
		(   	SELECT SUM(money) AS get_s_ch2_income,to_m_number
			FROM money_log
			WHERE log_date = ?
			AND logname_ref = 6
			AND reference = 1
			AND type = 4
			GROUP BY to_m_number
		)AS m6 ON m6.to_m_number = m1.m_identify
		LEFT JOIN
		(   	SELECT SUM(money) AS get_s_div_income,to_m_number
			FROM money_log
			WHERE log_date = ?
			AND logname_ref = 7
			AND reference = 1
			AND type = 4
			GROUP BY to_m_number
		)AS m7 ON m7.to_m_number = m1.m_identify
		LEFT JOIN
		(	SELECT COUNT(id) AS intro_count ,m_intro_idn
			FROM member
			WHERE (m_sign_date BETWEEN '2015-05-01' AND ?)
			GROUP BY m_intro_idn
		)AS m8 ON m8.m_intro_idn = m1.m_identify
		LEFT JOIN
		(	SELECT COUNT(m10.b_count) AS intro_b_count ,m11.m_intro_idn,m11.m_identify
			FROM member AS m11
			LEFT JOIN
			(	SELECT COUNT(id) AS b_count ,m_intro_idn
				FROM member
				WHERE (m_sign_date BETWEEN '2015-05-01' AND ?)
				GROUP BY m_identify
			)AS m10 ON m10.m_intro_idn = m11.m_identify
			WHERE (m11.m_sign_date BETWEEN '2015-05-01' AND ?)
			GROUP BY m11.m_intro_idn
		)AS m9 ON m9.m_intro_idn = m1.m_identify
		JOIN
		(   	SELECT m1.m_identify, m2.pbase FROM member AS m1
			LEFT JOIN
			(	SELECT COUNT(member.id) AS pbase,member. m_intro_idn 
				FROM member,level_relationship
				WHERE member.m_identify = level_relationship.children
				AND member.m_sign_date BETWEEN '2015-05-01' AND ?
				GROUP BY member.m_intro_idn
			)AS m2 ON m2.m_intro_idn = m1.m_identify
			WHERE m1.m_identify <> ?
			AND m1.m_sign_date BETWEEN '2015-05-01' AND ?
			GROUP BY m_identify HAVING pbase >= 10
		)AS m10
		LEFT JOIN
		(	SELECT COUNT(member.id) AS sbase,member. m_intro_idn 
			FROM member,level_relationship
			WHERE member.m_identify = level_relationship.children
			AND member.m_sign_date BETWEEN '2015-05-01' AND ?
			GROUP BY member.m_intro_idn
		)AS m11 ON m11.m_intro_idn = m1.m_identify
		WHERE m1.m_identify = ?";
	}
	private function get_two_level_child_params($get_date,$iden){
		return array($get_date,$get_date,$get_date,$get_date,$get_date,$get_date,$get_date,$get_date,$this->bank_number,$get_date,$get_date,$iden);
	}
	
}
