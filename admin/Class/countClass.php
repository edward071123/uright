<?php 
class Count{
	private $bank_number = '1040526032';
	public $member_iden, $db;
	public $get_use_total_income = 0;
	public $get_use_total_expenses = 0;
	public $get_account_total_income = 0;
	public $get_account_total_expenses = 0;
	function __construct($input_member_identify){
		global $db;
		$this->member_iden = $input_member_identify; 
		$this->db =  $db; 
	}
	//計算member_account支出跟收入(母球+子球)
	public function count_member_account_income_expenses(){
		$sql_money = "SELECT m1.m_identify ,SUM(m2.get_income) AS get_total_income,
					SUM(m3.get_expenses) AS get_total_expenses
					FROM member AS m1
					LEFT JOIN(
						SELECT SUM(money) AS get_income,to_m_number
						FROM money_log
						WHERE  logname_ref = 2
						AND reference = 1 
						GROUP BY to_m_number
					) AS m2 ON m2.to_m_number = m1.m_identify 
					LEFT JOIN(
						SELECT SUM(money) AS get_expenses,to_m_number
						FROM money_log
						WHERE  logname_ref = 2
						AND reference = 0
						GROUP BY to_m_number
					) AS m3 ON m3.to_m_number = m1.m_identify
					WHERE m1.m_identify LIKE ?";
		$money_list = $this->db->rawQuery ($sql_member, array("%".$this->member_iden."%"));
		if(!empty($money_list[0]["get_total_income"]))
			$this->get_account_total_income = ((int)$money_list[0]["get_total_income"]);
		if(!empty($money_list[0]["get_total_expenses"]))
			$this->get_account_total_expenses = ((int)$money_list[0]["get_total_expenses"]);
	}
	//顯示所有可用點數
	public function get_account(){
		return $this->get_account_total_income-$this->get_account_total_expenses;
	}
	//顯示所有收入
	public function get_account_income(){
		return $this->get_account_total_expenses;
	}
	//顯示所有支出
	public function get_account_expenses(){
		return $this->get_account_total_expenses;
	}
	//計算member_use支出跟收入(母球+子球)
	public function count_member_use_income_expenses(){
		$sql_money = "SELECT m1.m_identify ,SUM(m2.get_income) AS get_total_income,
					SUM(m3.get_expenses) AS get_total_expenses
					FROM member AS m1
					LEFT JOIN(
						SELECT SUM(money) AS get_income,to_m_number
						FROM money_log
						WHERE  logname_ref = 1
						AND reference = 1 
						GROUP BY to_m_number
					) AS m2 ON m2.to_m_number = m1.m_identify 
					LEFT JOIN(
						SELECT SUM(money) AS get_expenses,to_m_number
						FROM money_log
						WHERE  logname_ref = 1
						AND reference = 0 
						GROUP BY to_m_number
					) AS m3 ON m3.to_m_number = m1.m_identify
					WHERE m1.m_identify LIKE ?";
		$money_list = $this->db->rawQuery ($sql_money, array("%".$this->member_iden."%"));
		if(!empty($money_list[0]["get_total_income"]))
			$this->get_use_total_income = ((int)$money_list[0]["get_total_income"]);
		if(!empty($money_list[0]["get_total_expenses"]))
			$this->get_use_total_expenses = ((int)$money_list[0]["get_total_expenses"]);
	}
	//顯示所有可用點數
	public function get_use(){
		return $this->get_use_total_income - $this->get_use_total_expenses;
	}
	//顯示所有收入
	public function get_use_income(){
		return $this->get_use_total_income;
	}
	//顯示所有支出
	public function get_use_expenses(){
		return $this->get_use_total_expenses;
	}
}
