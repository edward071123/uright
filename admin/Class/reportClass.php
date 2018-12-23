<?php

class Report{
	private $bank_number = '1040526032';
	private $error = "";
	public $db,$member_new_base,$base_total;
	public $start_date,$end_date;
	function __construct(){
		global $db;
		$this->db =  $db; 
	}
	//show 福委會所有數據
	public function show_company_all($date){
		//get_total_member_basic 母球數
		//get_total_member_add 子球數
		//get_ten_money_income_r 十層獎金剩餘
		//get_ten_money_income_e 十層獎金支出
		//get_administrative_costs_e 行政費用支出
		$company_uses = $this->get_company_all($date);
		$ii=0;
		foreach ($company_uses as  $company_use) {
			$get_total_member_basic = 0;
			$get_total_member_add = 0;
			$get_ten_money_income_e = 0;
			$get_ten_money_income_r = 0;
			$get_administrative_costs_e = 0;

			if(!empty($company_use['get_total_member_basic']))
				$get_total_member_basic = $company_use['get_total_member_basic'];
			if(!empty($company_use['get_total_member_add']))
				$get_total_member_add = $company_use['get_total_member_add'];
			$total_member_count = $get_total_member_basic + $get_total_member_add;
			$total_money_income = $total_member_count * 3600;

			if(!empty($company_use['get_ten_money_income_e']))
				$get_ten_money_income_e = $company_use['get_ten_money_income_e'];
			$get_ten_money_income_r =  ($total_member_count * 2500)-$get_ten_money_income_e;

			if(!empty($company_use['get_administrative_costs_e']))
				$get_administrative_costs_e = $company_use['get_administrative_costs_e'];
			$get_administrative_costs_e = $get_administrative_costs_e;
			$get_administrative_costs_r = 1100*$total_member_count - $get_administrative_costs_e;
			$get_total_r = $get_administrative_costs_r + $get_ten_money_income_r;
			$responce->rows[$ii]['no']=$ii+1;
			$responce->rows[$ii]['cell']=array($get_total_member_basic,$get_total_member_add,
			$total_member_count,$total_money_income,$get_ten_money_income_e,$get_ten_money_income_r,
			$get_administrative_costs_e,$get_administrative_costs_r,$get_total_r);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	public function get_company_all($date){
		return $this->db->rawQuery ($this->get_company_all_sql(), $this->company_all_array ($date));
	}
	private function company_all_array($date){
		return array ($date,$date,$date,$date);
	}
	private function get_company_all_sql(){
		$sql = " SELECT m1.m_identify,m2.get_total_member_basic,m3.get_total_member_add,
				m5.get_ten_money_income_e,
				m6.get_administrative_costs_e
			FROM member AS m1
			JOIN
			( 	SELECT COUNT(member.id) AS get_total_member_basic
				FROM member, level_relationship
				WHERE SUBSTRING(member.m_sign_date, 1, 7) = ?
				AND member.m_identify NOT LIKE '%-%'
				AND member.m_identify = level_relationship.children
			)AS m2
			JOIN
			( 	SELECT COUNT(member.id) AS get_total_member_add
				FROM member, level_relationship
				WHERE SUBSTRING(member.m_sign_date, 1, 7) = ?
				AND member.m_identify LIKE '%-%'
				AND member.m_identify = level_relationship.children
			)AS m3
			JOIN
			( 	SELECT SUM(money) AS get_ten_money_income_e
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND reference = 1
				AND logname_ref = 3
				AND type = 1
			)AS m5
			JOIN
			( 	SELECT SUM(money) AS get_administrative_costs_e
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 3
				AND reference = 0
				AND type = 2
			)AS m6
			WHERE  m1.id = 1";
		return $sql;
	}
	//show 福委會實際善款點數
	public function show_company_account_use($date){
		//get_s_eight_income 自身母球實際善款點數(80%)
		//get_o_eight_income 自身子球實際善款點數(80%)
		//get_o_eight_income 個人分配行政作業費點數(70%)
		$company_uses = $this->get_company_account_use($date);
		$ii=0;
		foreach ($company_uses as  $company_use) {
			$get_s_eight_income = 0;
			$get_o_eight_income = 0;
			$get_m_seven_income = 0;
			if(!empty($company_use['get_s_eight_income']))
				$get_s_eight_income = $company_use['get_s_eight_income'];
			if(!empty($company_use['get_o_eight_income']))
				$get_o_eight_income = $company_use['get_o_eight_income'];
			$total_eight_income = $get_s_eight_income + $get_o_eight_income;

			if(!empty($company_use['get_m_seven']))
				$get_m_seven_income = $company_use['get_m_seven'];

			$total_use_income =  $total_eight_income + $get_m_seven_income;
			$total_use_income_for = (int)($total_use_income*40/100);
			$total_use_income_tw = (int)($total_use_income*20/100);
			$total_use_income_ten = (int)($total_use_income*10/100);
			$total_use_income_tir = (int)($total_use_income*30/100);
			$responce->rows[$ii]['no']=$ii+1;
			$responce->rows[$ii]['cell']=array(
			$get_s_eight_income,$get_o_eight_income,$get_m_seven_income,
			$total_use_income,$total_use_income_for,$total_use_income_tw,
			$total_use_income_ten,$total_use_income_tir);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	public function get_company_account_use($date){
		return $this->db->rawQuery ($this->get_company_account_use_sql(), $this->company_account_use_array ($date));
	}
	private function company_account_use_array($date){
		return array ($date,$date,$date,$date);
	}
	private function get_company_account_use_sql(){
		$sql = " SELECT m1.m_identify,m2.get_s_eight_income,m3.get_m_seven,
			(   	SELECT sum(money) FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND reference = 1
				AND logname_ref = 1
				AND type = 1
				AND to_m_number IN
				(	SELECT m_identify FROM member
					WHERE m_identify LIKE CONCAT('%', m1.m_identify ,'%')
					AND m_identify <> m1.m_identify)
			) AS get_o_eight_income
			FROM member AS m1
			LEFT JOIN
			(   	SELECT SUM(money) AS get_s_eight_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			)AS m2 ON m2.to_m_number = m1.m_identify
			LEFT JOIN
			(	SELECT SUM(money) AS get_m_seven,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 2
				GROUP BY to_m_number
			) AS m3 ON m3.to_m_number = m1.m_identify
			WHERE m1.id = 1
			AND (SUBSTRING(m1.m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)";
		return $sql;
	}
	//show 福委會被挹注點數
	public function show_company_account_ad($date){
		//get_s_ten_income 自身母球十層挹注收入(10%)
		//get_s_fif_income 自身母球行政挹注收入(15%)
		//get_o_ten_income 自身子球十層挹注收入(10%)
		//get_o_fif_income 自身子球行政挹注收入(15%)
		//get_ten_money_income_ten 所有帳戶十層給福委會增加子球(10%)
		//get_ac_money_income_fifteen 所有帳戶行政給福委會增加子球(15%)
		$company_infos = $this->get_company_account_ad($date);
		$ii=0;
		foreach ($company_infos as  $company_info) {
			$get_ten_money_income_ten = 0;
			$get_ac_money_income_fifteen = 0;
			$get_s_ten_income = 0;
			$get_s_fif_income = 0;

			if(!empty($company_info['get_ten_money_income_ten']))
				$get_ten_money_income_ten = $company_info['get_ten_money_income_ten'];
			if(!empty($company_info['get_ac_money_income_fifteen']))
				$get_ac_money_income_fifteen = $company_info['get_ac_money_income_fifteen'];

			if(!empty($company_info['get_s_ten_income']))
				$get_s_ten_income = $company_info['get_s_ten_income'];
			if(!empty($company_info['get_s_fif_income']))
				$get_s_fif_income = $company_info['get_s_fif_income'];

			$responce->rows[$ii]['no']=$ii+1;
			$responce->rows[$ii]['cell']=array(
			$get_ten_money_income_ten,$get_ac_money_income_fifteen,
			$get_s_ten_income,$get_s_fif_income);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	public function get_company_account_ad($date){
		return $this->db->rawQuery ($this->get_company_account_ad_sql(), $this->company_account_ad_array ($date));
	}
	private function company_account_ad_array($date){
		return array ($date,$date,$date,$date,$date);
	}
	private function get_company_account_ad_sql(){
		$sql = " SELECT m1.m_identify,m2.get_ten_money_income_ten,
				m3.get_ac_money_income_fifteen,
				m4.get_s_ten_income,m5.get_s_fif_income
				FROM member AS m1
			JOIN
			(	SELECT sum(money) AS get_ten_money_income_ten
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND reference = 1
				AND logname_ref = 4
				AND type = 1
			) AS m2
			JOIN
			(  	SELECT sum(money) AS get_ac_money_income_fifteen
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND reference = 1
				AND logname_ref = 4
				AND type = 2
			) AS m3
			LEFT JOIN
			(	SELECT SUM(money) AS get_s_ten_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 2
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			) AS m4 ON m4.to_m_number = m1.m_identify
			LEFT JOIN
			(	SELECT SUM(money) AS get_s_fif_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 2
				AND reference = 1
				AND type = 2
				GROUP BY to_m_number
			) AS m5 ON m5.to_m_number = m1.m_identify
			WHERE m1.id = 1
			AND (SUBSTRING(m1.m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)";
		return $sql;
	}
	//show member 報表
	public function show_member_money_info($date){
		//get_s_eight_income 自身母球實際善款點數(80%)
		//get_o_eight_income 自身子球實際善款點數(80%)
		//get_s_seven_income 個人分配行政作業費點數(70%)
		//get_s_seven_income = (get_s_ch1_income+get_s_ch2_income+get_s_div_income)*70/100
		//get_s_ch1_income 個人分配ch1
		//get_s_ch2_income 個人分配ch2
		//get_s_div_income 個人分配div
		//intro_num 個人招募人數
		$money_infos = $this->get_member_money_info($date);
		$ii = 0;
		foreach ($money_infos as  $money_info) {
			$get_m_name  = $money_info['m_name'];
			$idn = $money_info['m_identify'];
			$get_s_eight_income = 0;
			$get_o_eight_income = 0;
			$get_s_seven_income = 0;
			$get_s_ch1_income = 0;
			$get_s_ch2_income = 0;
			$get_s_div_income = 0;
			$get_product_i = 0;
			$get_product_e = 0;
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
			if(!empty($money_info['product_i']))
				$get_product_i = $money_info['product_i'];
			if(!empty($money_info['product_e']))
				$get_product_e = $money_info['product_e'];

			if(($intro_num>0) && ($intro_num <= 2))
				$cla = "輔導員";
			else if(($intro_num>2) && ($intro_num <= 9))
				$cla = "小組長";
			else if($intro_num>9)
				$cla = "愛心組長";

			$total_eight_income = $get_s_eight_income + $get_o_eight_income;
			$total_use_income = $get_s_seven_income + $total_eight_income;

			$responce->rows[$ii]['no']=$ii+1;
			$responce->rows[$ii]['cell']=array($ii+1,$idn,$get_m_name,
			$get_s_eight_income,$get_o_eight_income,$total_eight_income,$intro_num,
			$get_s_ch1_income,$get_s_ch2_income,$get_s_div_income,
			$get_s_seven_income,$total_use_income,$get_product_i,$get_product_e);
			$ii++;
		}
		return !empty($responce) ? $responce : '';
	}
	public function get_member_money_info($date){
		return $this->db->rawQuery ($this->get_member_money_info_sql(), $this->money_info_params_array ($date));
	}
	private function money_info_params_array($date){
		return array ($date,$date,$date,$date,$date,$date,$date,$this->bank_number ,$date);
	}
	private function get_member_money_info_sql(){
		$sql = "SELECT m1.m_name,m1.m_identify,m2.get_s_eight_income,
			m3.get_s_seven_income,SUM(m4.o_eight_income) AS get_o_eight_income,
			m5.get_s_ch1_income,m6.get_s_ch2_income,m7.get_s_div_income,m8.intro_num
			FROM member AS m1
			LEFT JOIN
			(   	SELECT SUM(money) AS get_s_eight_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
				)AS m2 ON m2.to_m_number = m1.m_identify
			LEFT JOIN
			(	SELECT SUM(money) AS get_s_seven_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date, 1, 7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND TYPE = 2
				GROUP BY to_m_number
			) AS m3 ON m3.to_m_number = m1.m_identify
			LEFT JOIN
			(   	SELECT SUM(money) AS o_eight_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 1
				AND reference = 1
				AND type = 1
				GROUP BY to_m_number
			)AS m4 ON m4.to_m_number LIKE CONCAT('%', m1.m_identify ,'%') AND m4.to_m_number <> m1.m_identify
			LEFT JOIN
			(   	SELECT SUM(money) AS get_s_ch1_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 5
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
			)AS m5 ON m5.to_m_number = m1.m_identify
			LEFT JOIN
			(   	SELECT SUM(money) AS get_s_ch2_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 6
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
				)AS m6 ON m6.to_m_number = m1.m_identify
			LEFT JOIN
			(   	SELECT SUM(money) AS get_s_div_income,to_m_number
				FROM money_log
				WHERE SUBSTRING(log_date,1,7) = ?
				AND logname_ref = 7
				AND reference = 1
				AND type = 4
				GROUP BY to_m_number
			)AS m7 ON m7.to_m_number = m1.m_identify
			LEFT JOIN
			(	SELECT COUNT(id) AS intro_num ,m_intro_idn
				FROM member
				WHERE SUBSTRING(m_sign_date,1,7) = ?
				GROUP BY m_intro_idn
			)AS m8 ON m8.m_intro_idn = m1.m_identify
			INNER JOIN level_relationship AS lr ON m1.m_identify = lr.children
			WHERE m1.m_identify <> ?
			AND m1.m_identify NOT LIKE '%-%'
			AND (SUBSTRING(m1.m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)
			GROUP BY m1.m_identify ORDER BY m1.m_identify ";
		return $sql;
	}
}

