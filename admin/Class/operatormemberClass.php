<?php 
class OperatorMember{
	private $bank_number = '1040526032';
	public $post,$db;
	function __construct(){
		global $db,$_POST;
		$this->db =  $db; 
		$this->post =  $_POST; 
	}
	//編輯會員
	public function edit_member_show($member_iden){
		$sql = "SELECT m1.*,m3.mparent_name,m3.mparent,itro1.itro_name,
				ifo1.*
				FROM member AS m1
				LEFT JOIN
		            	(   	SELECT lp.parent AS mparent ,lp.children, mm1.mparent_name
		               		FROM level_relationship AS lp
		                		LEFT JOIN
		                		(   	SELECT m_name AS mparent_name ,m_identify
		                    			FROM member
		               		)AS mm1 ON mm1.m_identify = lp.parent
		            	)AS m3 ON m3.children = m1.m_identify
		            	LEFT JOIN
		            	(   	SELECT m_name AS itro_name ,m_identify
		               		FROM member
		            	)AS itro1 ON itro1.m_identify = m1.m_intro_idn
		            	LEFT JOIN
		            	(   	SELECT *
		               		FROM imfomation
		            	)AS ifo1 ON ifo1.m_identify = m1.m_identify
				WHERE m1.m_identify = ? ";
		return $this->db->rawQuery ($sql, array($member_iden));
	}
	//新增會員
	public function insert_member(){
		$data_message = array();
		$this->db->startTransaction();
		$m_id = $this->db->insert ('member', $this->member_array());
		if ($m_id) {
			$i_id = $this->db->insert ('imfomation', $this->imformation_array());
			if($m_id){
				$this->db->commit();
				$data_message['message']  = "success";
			}else{
				$this->db->rollback();
				$data_message['message']  = "failure";
			}
		}else{
			$this->db->rollback();
			$data_message['message']  = "failure";
		}
		return $data_message;
	}
	//修改會員
	public function edit_member($member_iden){
		$data_message = array();
		$this->db->startTransaction();
		$this->db->where ('m_identify', $member_iden);
		if ($this->db->update ('member', $this->member_array())){
			$this->db->where ('m_identify', $member_iden);
			if($this->db->update ('imfomation', $this->imformation_array())){
				$this->db->commit();
				$data_message['message']  = "success";
			}else{
				$this->db->rollback();
				$data_message['message']  = "failure";
			}
		}else{
			$this->db->rollback();
			$data_message['message']  = "failure";
		}
		return $data_message;
	}
	private function member_array(){
		$getidn = $this->post["identify"];
		$getintro_idn = $this->post["intro_idn"];
		if(strpos($this->post["identify"], "-"))
			$getintro_idn = $this->bank_number;//此為子球 招募人一率為福委會
		$data = array (
					"m_name" => $this->post["name"],
					"m_identify" => $this->post["identify"],
			               	"m_gender" => $this->post["gender"],
			              	"m_birthday" => $this->post["birthday"],
			              	"m_live_zip" => $this->post["live_zip"],
			               	"m_phone" => $this->post["phone"],
			              	"m_mobile" => $this->post["mobile"],
			              	"m_email" => $this->post["email"],
			               	"m_sign_date" => $this->post["sign_date"],
			              	"m_intro_idn" => $getintro_idn
				);
		return $data;
	}
	private function imformation_array(){
		$data = array (
					"m_identify" => $this->post["identify"],
			               	"m_bir_address" => $this->post["bir_address"],
			              	"m_live_address" => $this->post["live_address"],
			              	"m_heir_identify1" => $this->post["heir_identify1"],
			               	"m_heir_name1" => $this->post["heir_name1"],
			              	"m_heir_relship1" => $this->post["heir_relship1"],
			              	"m_heir_identify2" => $this->post["heir_identify2"],
			               	"m_heir_name2" => $this->post["heir_name2"],
			              	"m_heir_relship2" => $this->post["heir_relship2"]
				);
		return $data;
	}
}