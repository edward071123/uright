<?php 
class Position{
	private $bank_number = '1040526032';
	public $db,$tmp1,$vv;
	function __construct(){
		global $db;
		$this->db = $db; 
	}
	//搜尋自身的招募列表
	public function search_self_intro($m_identify){
		$sql = "SELECT m_identify,m_name 
			FROM member 
			WHERE m_intro_idn = ?";
		return $this->db->rawQuery ($sql,  array($m_identify));
	}
	//搜尋招募人
	public function search_intro($s_identify,$s_name,$s_mobile){
		$params = array();
		$sql_parent = "SELECT m1.m_name,m1.m_identify,m1.m_mobile,lr2.parent
				FROM member AS m1
				LEFT JOIN
				      (
				          SELECT parent,children
				          FROM
				          level_relationship
				      ) AS lr2 ON lr2.children = m1.m_identify
				WHERE m1.m_identify NOT LIKE '%-%'";
		if(!empty($s_identify)){
			$sql_parent .= " and m1.m_identify LIKE ?";
			array_push($params,"%".$s_identify."%");
		}
		if(!empty($s_name)){
			$sql_parent .= " and m1.m_name LIKE ?";
			array_push($params,"%".$s_name."%");
		}
		if(!empty($s_mobile)){
			$sql_parent .= "and m1.m_mobile LIKE ?";
			array_push($params,"%".$s_mobile."%");
		}
		$sql_parent .= " ORDER BY m1.id";
		if(count($params) > 0)
			$parents = $this->db->rawQuery ($sql_parent, $params);
		else
			$parents = $this->db->rawQuery ($sql_parent);
		$data = array();
		$i = 0;
		foreach ($parents as $parent){ 
			$tmp = 0;
			if(empty($parent['parent']))
				$data[$i]['info'] = $parent['m_identify']." | ".$parent['m_name'].' | '.'未安置';
			else
				$data[$i]['info'] = $parent['m_identify']." | ".$parent['m_name'].' | '.'已安置';
			$i++;
		}
		return $data;
	}
	//刪除安置
	public function delete_position($member_idn){
		$this->db->where ('item', 1);
		$this->db->where ('value', $member_idn);
		$this->db->where ('status', 1);
		$result = $this->db->get ('operation_log');
		$level_id = $result[0]['level_log'];
		$data_m = array();
		$this->db->startTransaction();
		$this->db->where('id', $level_id);
		if (!$this->db->delete('level_relationship')) {
			$data_m['message']  = "刪除安置失敗:level";
			$this->db->rollback();
		}else {
			$this->db->where ('id', $result[0]['money_log_start'], ">=");
			$this->db->where ('id', $result[0]['money_log_end'], "<=");
			if (!$this->db->delete('money_log')) {
				$data_m['message']  = "刪除安置失敗:money";
				$this->db->rollback();
			}else {
				$this->db->where ('item', 1);
				$this->db->where ('value', $member_idn);
				$this->db->where ('status', 1);
				$data_u = array ("status" => 2);
				if ($this->db->update ('operation_log', $data_u)){
					$data_m['message']  = "刪除安置成功";
					$this->db->commit();
				}else{
					$data_m['message']  = "刪除安置失敗:opt";
					$this->db->rollback();
				}
			}
		}
		return $data_m;
	}
	//自動排位置
	public function auto_level_position($member_idn,$m_intro_number){
		$top_number = $this->bank_number;
		//判對身分證字號 後面是否有"-" 有的話為子球 無則為母球
		if(!strpos($member_idn, "-"))
			//為母球 以招募人為頂
			$top_number = $m_intro_number;
		$data = array();
		$data_m = array();
		$fir[0] = $top_number;
		array_push($data ,  $fir);
		$i=0;//level
		$number = (int)(pow(3,$i)-count($data[$i]));
		$find_number = "";
		$find_name = "";
		$vc = 1;
		while($number == 0){
			$tmp = $data[$i];
			$this->tmp1 = array();
			$this->vv=0;
			for($f=0; $f<count($tmp); $f++){
				$tmp_count = $this->find_lc($tmp[$f]);
				if($tmp_count == 3){
					$this->find_c($tmp[$f]);
				}else{
					$find_number = $tmp[$f];
					$vc = (int)$tmp_count+1;
					break;
				}
			}
			$i++;
			array_push($data ,  $this->tmp1);
			$number = (int)(pow(3,$i)-count($data[$i]));
		}
		$position_params = array($find_number);
		$sql_m = "SELECT m_name,m_identify FROM member WHERE m_identify = ?";
		$ch_m = $this->db->rawQuery ($sql_m, $position_params);
		$data_m['number']  = $find_number;
		$data_m['level']  = $i;
		$data_m['name']  = $ch_m[0]['m_name'];
		$data_m['identify']  = $ch_m[0]['m_identify'];
		$tpd = array('左','中','右');
		$data_m['position']  = $tpd[$vc-1];
		$data_m['position_h']  = $vc;
		return $data_m;
	}
	//搜尋安置對象
	public function search_parent($s_identify,$s_name,$s_mobile){
		$params = array();
		$sql_parent = "SELECT m1.m_name,m1.m_identify,m1.m_mobile,l2.p_count,l3.level
				FROM member AS m1
				LEFT JOIN
				(   SELECT COUNT(*) AS p_count , parent,level
				    FROM level_relationship GROUP BY parent
				)AS l2 ON l2.parent = m1.m_identify
				LEFT JOIN
				(   SELECT level,children
				    FROM level_relationship
				)AS l3 ON l3.children = m1.m_identify
				WHERE  (l2.p_count < 3 OR l2.p_count IS NULL)";
		if(!empty($s_identify)){
			$sql_parent .= " AND m1.m_identify LIKE ?";
			array_push($params,"%".$s_identify."%");
		}
		if(!empty($s_name)){
			$sql_parent .= " AND m1.m_name LIKE ?";
			array_push($params,"%".$s_name."%");
		}
		if(!empty($s_mobile)){
			$sql_parent .= " AND m1.m_mobile LIKE ?";
			array_push($params,"%".$s_mobile."%");
		}
		if(count($params) > 0)
			$parents = $this->db->rawQuery ($sql_parent, $params);
		else
			$parents = $this->db->rawQuery ($sql_parent);
		$data = array();
		$i = 0;
		foreach ($parents as $parent){ 
			$data[$i]['m_identify'] = $parent['m_identify']."=>".$parent['level'];
			$data[$i]['info'] = $parent['m_identify']." | ".$parent['m_name'];
			$i++;
		}
		return $data;
	}
	//搜尋安置位置
	public function search_parent_opt($m_identify){
		$params = array($m_identify);
		$sql_position = "SELECT position FROM level_relationship WHERE parent = ?";
		$positions = $this->db->rawQuery ($sql_position, $params);
		$data = array();
		$tp = array();
		$pp = 0;
		foreach ($positions as $position){ 
			$tp[$pp] = $position['position'];
			$pp++;
		}
		$tpd = array('左','中','右');
		$opt = "";
		for($u = 1; $u <= 3; $u++)
			if(!in_array($u,$tp))
				$opt .= '<option value="'.$u.'">'.$tpd[$u-1].'</option>';
		$data['opt'] = $opt;
		return $data;
	}
	//自動排位置
	private function find_c($parent){
		$sql_chi = "SELECT lr.*, m.m_name
				FROM level_relationship AS lr, member AS m
				WHERE m.m_identify = lr.children
				AND parent = ? ORDER BY lr.position ASC";
		$chi_params = array($parent);
		$rs_chi1 = $this->db->rawQuery ($sql_chi, $chi_params);
		foreach ($rs_chi1 as $chi){ 
			$this->tmp1[$this->vv] =  $chi["children"];
			$this->vv++;
		}
	}
	//自動排位置
	private function find_lc($parent){//自動排位置
		$sql_chi = "SELECT COUNT(*) AS level_c
				FROM level_relationship
				WHERE parent = ?";
		$chi_params = array($parent);
		$rs_chi1 = $this->db->rawQuery ($sql_chi, $chi_params);
		return $rs_chi1[0]['level_c'];
	}
}

