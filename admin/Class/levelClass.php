<?php 
class Level{
	private $bank_number = '1040526032';
	private $reword_lev = array(300,300,300,300,300,200,200,100,100,400);
	private $error = "無此帳號";
	public $db,$p_count,$parent_list,$member_iden,$intro_iden,$position,$intro_level,$manager_id,$inst_date;
	function __construct($member_iden,$intro_iden,$position,$intro_level,$manager_id){
		global $db;
		$this->db = $db;
		$this->member_iden = $member_iden; 
		$this->intro_iden = $intro_iden; 
		$this->position = $position; 
		$this->intro_level = $intro_level; 
		$this->manager_id = $manager_id; 
	}
	public function main(){
		$this->db->where ('m_identify', $this->member_iden);
		$cols = Array ("id", "m_sign_date");
		$member = $this->db->get ("member",null,$cols);
		if($member[0]['id']){
			$this->inst_date = $member[0]['m_sign_date'];
			$this->error = "此會員已經被安置";
			$this->db->where ('children', $this->member_iden);
			$ck_ava_v = $this->db->getOne ("level_relationship","id");
			if(count($ck_ava_v) == 0){
				$this->p_count = 0;
				$this->insert_level(0);
			}
		}
		return  $this->error;
	}
	public function muti_main($lr_id){
		$this->db->where ('m_identify', $this->member_iden);
		$cols = Array ("id", "m_sign_date");
		$member = $this->db->get ("member",null,$cols);
		if($member[0]['id']){
			$this->inst_date = $member[0]['m_sign_date'];
			$this->p_count = 0;
			$this->insert_level($lr_id);
		}
		return  $this->error;
	}
	private function insert_level($get_lr_id){//insert money_log
		// start
		$this->db->startTransaction();
		if($get_lr_id == 0){
			$lr_id = $this->db->insert ('level_relationship', $this->insert_level_array());
			if (!$lr_id) {
				$this->db->rollBack();
				$this->error = "insert failed:level";
			}else{
				$get_lr_id = $lr_id;
			}
		}
		//serach level relationship
		$this->find_level_relationship($this->member_iden);
		$get_val = $this->p_count;
		//custom money from 2500
		$custom_money = 0;
		$pl_count = 0;
		$data_money = array();
		while($get_val > 0){
			if(isset($this->reword_lev[$pl_count])){
				$money = (int)$this->reword_lev[$pl_count];
				$custom_money = $custom_money+$money;
				$money_15_income = (int)($money/100*15);
				$money_9_income = (int)($money/100*9);
				$money_80_income = (int)($money/100*80);
				$money_10_income = (int)($money/100*10);
				//本帳號扶助點數收入:本帳號下層帳號80%十層點數
				array_push($data_money,$this->insert_money_log_array($this->member_iden,$this->parent_list[$pl_count],1,1,$money_80_income,$money_15_income,$money_9_income,$this->inst_date));
				//本帳號下層帳號20%十層點數
				//本帳號3600自強單位點數收入:本帳號下層帳號10%十層點數
				array_push($data_money,$this->insert_money_log_array($this->member_iden,$this->parent_list[$pl_count],2,2,$money_10_income,0,0,$this->inst_date));
				//協會帳號3600自強單位點數收入:本帳號下層帳號10%十層點數
				array_push($data_money,$this->insert_money_log_array($this->member_iden,$this->bank_number,4,3,$money_10_income,0,0,$this->inst_date));
			}
			$pl_count++;
			$get_val --;
		}
		//本帳號3600剩餘的錢存入協會
		$money = 3600-$custom_money;
		array_push($data_money,$this->insert_money_log_array($this->member_iden,$this->bank_number,3,4,$money,0,0,$this->inst_date));
		$ids = $this->db->insertMulti('money_log', $data_money);
		if(!$ids) {
			$this->error = "insert failed:money_log";
			$this->db->rollback();
		}
		//insert operation_log
		$money_start = $ids[0];
		$money_end = $ids[count($ids)-1];
		$o_id = $this->db->insert ('operation_log', $this->insert_opt_array($get_lr_id,$money_start,$money_end));
		if($o_id){
			$this->db->commit();
			$this->error = "";
		}else{
			$this->db->rollback();
			$this->error = "insert failed:operation_log";
		}
	}
	
	private function insert_level_array(){
		$data = array (
					"parent" => $this->intro_iden,
					"children" => $this->member_iden,
					"position" => $this->position,
					"level" => $this->intro_level
				);
		return $data;
	}
	private function insert_opt_array($lr_id,$money_start,$money_end){
		$getidn = $this->member_iden;
		if(strpos($this->member_iden, "-")){
			//此為子球 填入母球欄位
			$getidn = explode("-",$this->member_iden)[0];
		}
		$data = array (
			"item" =>  1,
			"level_log" => $lr_id,
			"money_log_start" => $money_start,
			"money_log_end" => $money_end,
			"value" =>$this->member_iden,
			"member_ori_id" =>  $getidn,
			"set_level_date" => $this->inst_date,
			"manager_id" => $this->manager_id,
			"datetime" =>  date('Y-m-d H:i:s')
		);
		return $data;
	}
	private function find_level_relationship($member_id){//serach level relationship
		$this->db->where ('children', $member_id);
		$pa = $this->db->getOne ("level_relationship","parent");
		if($pa['parent'] != '0' && $pa['parent'] != null){
			$this->parent_list[$this->p_count] = $pa['parent'];
			$this->p_count++;
			$this->find_level_relationship($pa['parent']);
		}else
			return false;
	}
	private function insert_money_log_array($from,$to,$name,$ref,$money,$fifteen,$nine,$date){
		$data = array (
			"from_m_number" => $from,
			"to_m_number" => $to,
			"logname_ref" => $name,
			"outsidename_ref" => $ref,
			"reference" => 1,
			"type" => 1,
			"money" => $money,
			"money_fifteen" => $fifteen,
			"money_nine" => $nine,
			"log_date"  => $date
		);
		return $data;
	}
}