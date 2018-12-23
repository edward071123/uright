<?php 
class Member{
	public $sql,$db,$get_query,$where_condition;
	function __construct(){
		global $db;
		$this->db =  $db; 
		$this->sql = "*	FROM member ";
		$this->where_condition = "WHERE 1 ";
	}
	public function get_basic_member_query(){//基本會員列表查詢(sql)
		return $this->sql.$this->where_condition." ORDER BY m_sign_date DESC";
	}
	public function get_basic_member_query_show(){//基本會員列表查詢(條件)
		return $this->get_query;
	}
	public function get_condition_name($input_name){
		if(!empty($input_name)){
			$this->where_condition .= "AND m_name LIKE '%".$input_name."%'";
			$this->get_query .= '&name='.$input_name;
		}
	}
	public function get_condition_mobile($input_mobile){
		if(!empty($input_mobile)){
			$this->where_condition .= "AND m_mobile LIKE '%".$input_mobile."%'";
			$this->get_query .= '&mobile='.$input_mobile;
		}
	}
	public function get_condition_start_date($input_start_date){
		if(!empty($input_start_date)){
			$this->where_condition .= "AND m_sign_date >= '".$input_start_date."'";
			$this->get_query .= '&start_date_c='.$input_start_date;
		}
	}
	public function get_condition_end_date($input_end_date){
		if(!empty($input_end_date)){
			$this->where_condition .= "AND m_sign_date <= '".$input_end_date."'";
			$this->get_query .= '&end_date_c='.$input_end_date;
		}
	}
	// public function get_page($total){//頁數
	// 	if($total / 20 != 0)
	// 		$total = (int)($total/20) + 1;
	// 	else
	// 		$total = (int)$total/20;
	// 	return $total;
	// }
	// public function get_member_total (){//會員總數
	// 	$sql_total = "SELECT count(1) AS total FROM member as m1 ".$this->where_condition;
	// 	$rs_total = mysqli_query($this->link, $sql_total);
	// 	$member_total = mysqli_fetch_array($rs_total, MYSQL_ASSOC);
	// 	$get_member_total = $member_total['total'];
	// 	return  $get_member_total;
	// }
}