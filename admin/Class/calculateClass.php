<?php 
include "countClass.php";
class Calculate{
	private $bank_number = '1040526032';
	private $begain_date = '2015-05-01';
	private $check_start_date = '2016-12-31';
	private $manager_id = 56;
	private $error = "";
	public $db,$member_new_base,$base_total;
	public $start_date,$end_date;
	function __construct(){
		global $db;
		$this->db =  $db; 
	}
	//計算月數
	public function diff_month($diff_date){
		$date1 = new DateTime($diff_date);
		$date2 = new DateTime();
		$diff = $date1->diff($date2);
		return (($diff->format('%y') * 12) + $diff->format('%m')) ;
	}
	//檢查繳費
	public function ckeck_pay_for_count($member_identify){
		//true:已繳費,false:未繳費
		$get_new_date =  $this->search_sub_level_date($member_identify);
		if(is_null($get_new_date))
			return false;
		$get_diff =  $this->diff_month($get_new_date);
		$get_diff1 =  (int)($get_diff/12);
		$get_diff2 =  (int)($get_diff%12);
	        	if($get_diff1 <= 0)
	        		return true;
	        	if($get_diff2 <= 1)
        			return true;
        		return false;
     	}
     	//找出所有到結算日為止入會安置母球 
     	public function find_count_member(){
     		$sql_all_member = "SELECT member.m_identify,member.m_sign_date,
					member.m_name
				FROM member, level_relationship
				where  member.m_identify = level_relationship.children
				AND member.m_identify NOT LIKE '%-%'
				AND  member.m_identify  <> ?
				AND  (member.m_sign_date >= ? AND member.m_sign_date <= ? )
				ORDER BY member.id";
		return $this->db->rawQuery ($sql_all_member, array($this->bank_number,$this->begain_date,$this->end_date));
     	}
     	//計算主體
	public function main_count(){
		$members = $this->find_count_member();
		$this->db->startTransaction();
		$i = 0;
		$money_start = "";
		$money_end = "";
		foreach($members as $member){
			// if(!$this->ckeck_pay_for_count($member['m_identify'])){
			// 	$this->error .= $member['m_identify']."--未繳款<p>";
			// 	break;
			// }
			$data_money = array();
			//取得招募人數判斷資格
			$get_inro_count = $this->find_member_intro($member['m_identify']);
			$total_income = 0;
			$ch1_income = 0;
			$ch2_income = 0;
			$dividend_income = 0;
			if(($get_inro_count>0) && ($get_inro_count <= 2)){//輔導員
				//抓下面第一層當月所得
				$ch1_income = (int)$this->find_level_children1($member['m_identify']);
			}else if(($get_inro_count>2) && ($get_inro_count <= 9)){//小組長
				//抓下面第一層當月所得
				$ch1_income = (int)$this->find_level_children1($member['m_identify']);
				//抓下面第二層當月所得
				$ch2_income = (int)$this->find_level_children2($member['m_identify']);
			}else if($get_inro_count>9){//組長
				//抓下面第一層當月所得
				$ch1_income = (int)$this->find_level_children1($member['m_identify']);
				//抓下面第二層當月所得
				$ch2_income = (int)$this->find_level_children2($member['m_identify']);
				//全國分紅
				$dividend_income = (int)$this->all_member_dividend($member['m_identify']);
			}
			if($ch1_income != 0)
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],5,9,1,4,$ch1_income,$this->end_date));
			if($ch2_income != 0)
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],6,10,1,4,$ch2_income,$this->end_date));
			if($dividend_income != 0)
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],7,11,1,4,$dividend_income,$this->end_date));
			$total_income = $ch1_income + $ch2_income + $dividend_income;
			if($total_income != 0){
				//所得要分三部分 70%,15%,15%
				$total_70_income = (int)(round($total_income/100*70));
				$total_15_income = (int)(round($total_income/100*15));
				$total_30_income = (int)($total_15_income*2);
				//本帳號行政點數收入80%
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],1,7,1,2,$total_70_income,$this->end_date));
				//本帳號行政點數收入30%
				//本帳號自強單位行政點數收入:15%
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],2,6,1,2,$total_15_income,$this->end_date));
				//協會帳號自強單行政點數收入:15%
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],2,5,1,2,$total_15_income,$this->end_date));
				//協會信託帳號支出 上述三個部分
				$money = $total_70_income+$total_15_income+$total_15_income;
				array_push($data_money,$this->insert_money_log_array($member['m_identify'],$this->bank_number,3,8,0,2,$money,$this->end_date));
			}
			//計算喪葬補助
			$count = new Count($member['m_identify']);
			$count -> count_member_use_income_expenses();
			$get_use_count = $count -> get_use();
			if($get_use_count >= 100){
				array_push($data_money,$this->insert_money_log_array($this->bank_number,$member['m_identify'],1,14,0,2,100,$this->end_date));
				array_push($data_money,$this->insert_money_log_array($member['m_identify'],$this->bank_number,8,15,1,2,100,$this->end_date));
			}
			if(count($data_money) != 0){
				$ids = $this->db->insertMulti('money_log', $data_money);
				if(!$ids) {
					$this->error .= $member['m_identify']."--月結:insert failure<p>";
					$this->db->rollback();
					return $this->error;
				}
				if($i == 0)
					$money_start = $ids[0];
				$money_end = $ids[count($ids)-1];
			} 
			$this->error .= $member['m_identify']."--ok<p>";
			$i++;
		}
		$o_id = $this->db->insert ('operation_log', $this->insert_opt_array($money_start,$money_end));
		if($o_id){
			$this->db->commit();
			$this->error .= "count over: ".$o_id;
		}else{
			$this->db->rollback();
			$this->error .= "insert failed:operation_log";
		}
		return $this->error;
	}
     	//抓下面第一層所招募的會員(A)當月所得
	public function find_level_children1($member_identify){
		$income = 0;
		$sql_chi = "SELECT SUM(m1.income) AS level_income
				FROM member AS  mm
				LEFT JOIN(
					SELECT SUM(money_fifteen) AS income,to_m_number
					FROM money_log
					WHERE (log_date >= ? AND  log_date <= ?)
					AND logname_ref = 1
					AND reference = 1
					GROUP BY to_m_number
				) AS m1 ON m1.to_m_number LIKE CONCAT('%', mm.m_identify ,'%')
				INNER JOIN
				level_relationship AS lr
				ON mm.m_identify = lr.children
				WHERE mm.m_intro_idn = ?
				AND (mm.m_sign_date >= ? AND mm.m_sign_date <= ?)";
		$chis = $this->db->rawQuery ($sql_chi, array($this->start_date,$this->end_date,$member_identify,$this->begain_date,$this->end_date));
		if(!empty( $chis[0]['level_income']))
			$income = $chis[0]['level_income'];
		return $income;
	}
	//抓(A)的下面一層(B)當月所得
	public function find_level_children2($member_identify){
		$income = 0;
		$sql_chi = "SELECT SUM(m1.s_incom) AS level_income
				FROM member AS  mm
				LEFT JOIN
				( 	SELECT m_identify,m_intro_idn,m2.income as s_incom
					FROM member
					LEFT JOIN(
						SELECT SUM(money_nine) AS income,to_m_number
						FROM money_log
						WHERE (log_date >= ? AND  log_date <= ?)
						AND logname_ref = 1
						AND reference = 1
						GROUP BY to_m_number
					) AS m2 ON m2.to_m_number LIKE CONCAT('%', m_identify ,'%')
					INNER JOIN
					level_relationship AS lr1
					ON m_identify = lr1.children
				)AS m1 ON m1.m_intro_idn = mm.m_identify 
				INNER JOIN
				level_relationship AS lr
				ON mm.m_identify = lr.children
				WHERE mm.m_intro_idn = ?
				AND (mm.m_sign_date >= ? AND mm.m_sign_date <= ?)";
		$chis = $this->db->rawQuery ($sql_chi, array($this->start_date,$this->end_date,$member_identify,$this->begain_date,$this->end_date));
		if(!empty( $chis[0]['level_income']))
			$income = $chis[0]['level_income'];
		return $income;
	}
	//全國分紅
     	public function all_member_dividend($member_identify){
		//所有組長總基數
		$get_base_count = 0;
		//本身組長基數
		$get_self_base = 0;
		$sql_base = "SELECT m1.m_identify, m2.base FROM member AS m1
				LEFT JOIN
				(
					SELECT COUNT(member.id) AS base,member. m_intro_idn 
					FROM member,level_relationship
					WHERE member.m_identify = level_relationship.children  
					AND (member.m_sign_date >= ? AND member.m_sign_date <= ?)
					GROUP BY member.m_intro_idn
				)AS m2 ON m2.m_intro_idn = m1.m_identify
				WHERE m1.m_identify <> ?
				AND (m1.m_sign_date >= ? AND m1.m_sign_date <= ?)
				GROUP BY m_identify HAVING base >= 10";
		$bases = $this->db->rawQuery ($sql_base, array($this->begain_date,$this->end_date,$this->bank_number,$this->begain_date,$this->end_date));
		foreach ($bases as $base){
			$get_base_count += (int)($base['base']/10);
			if($member_identify == $base['m_identify']){
				$get_self_base = (int)($base['base']/10);
			}
		}
		//當月新進自強總數 * 500 * 本身基數 / 所有組長的基數加總
		if($get_base_count == 0)
			return 0;
		else
			return (int)($this->member_new_base * $get_self_base / $get_base_count);
     	}
     	//組長基數計算
     	public function count_member_base($start_date,$end_date){
     		$this->start_date = $start_date;
     		$this->end_date = $end_date;
     		//上次結算日到這次結算日中間新進會員(母球+子球)總數
		$sql_member_new = "SELECT count(member.id) AS get_count
			FROM member,level_relationship
			WHERE (member.m_sign_date >= ? AND member.m_sign_date <= ?)
			AND member.m_identify = level_relationship.children";
		$member_new = $this->db->rawQuery ($sql_member_new, array($start_date,$end_date));
		//當月新進自強單位x500 = 本月組長總基數補助點數
		$this->base_total = $member_new[0]['get_count'];
		$this->member_new_base = (int)($member_new[0]['get_count'] * 500);
     	}
     	//計算自己從2015-05-01起到本結算日共招募幾個人(後台結算)
	public function find_member_intro($member_identify){
		$total = 0;
		$sql_member_intro = "SELECT member.m_identify
						FROM member , level_relationship
						WHERE member.m_intro_idn = ?
						AND member.m_identify = level_relationship.children
						AND (member.m_sign_date >= ? AND member.m_sign_date <= ?)";
		$get_counts = $this->db->rawQuery ($sql_member_intro, array($member_identify,$this->begain_date,$this->end_date));
		foreach ($get_counts as $get_count)
			if($this->ckeck_pay_for_count($get_count['m_identify']))
				$total++;
		return $total;
	}
	//計算自己從2015-05-01起到今日個人共招募幾個人(前台顯示)
	public function find_member_intro_show($member_identify){
		$total = 0;
		$sql_member_intro = "SELECT member.m_identify
						FROM member , level_relationship
						WHERE member.m_intro_idn = ?
						AND member.m_identify = level_relationship.children
						AND (member.m_sign_date >= ? AND member.m_sign_date <= ?)";
		$get_counts = $this->db->rawQuery ($sql_member_intro, array($member_identify,$this->begain_date,date('Y-m-d')));
		foreach ($get_counts as $get_count)
			$total++;
		return $total;
	}
	//計算自己從2015-05-01起到今日個人共招募幾個人之列表(前台顯示)
	public function find_member_intro_info_show($member_identify,$date){
		$sql = "SELECT m2.m_identify,m2.m_name,m2.m_gender,m2.m_live_zip,m2.m_mobile,m1.intro_count,m3.sum
		FROM member AS m2
		LEFT JOIN
		(
			SELECT COUNT(*) AS intro_count, m_intro_idn
			FROM member
			WHERE (SUBSTRING(m_sign_date, 1, 7) BETWEEN '2015-05' AND ?)
			GROUP BY m_intro_idn
		) AS m1 ON m1.m_intro_idn = m2.m_identify
		LEFT JOIN
		(
			SELECT SUM(money) AS sum, to_m_number
			FROM money_log
			WHERE logname_ref = 1
			AND SUBSTRING(log_date, 1, 7) BETWEEN '2015-05' AND ?
			GROUP BY to_m_number
		) AS m3 ON m3.to_m_number = m2.m_identify
		WHERE  m2.m_intro_idn = ?
		AND SUBSTRING(m2.m_sign_date, 1, 7) BETWEEN '2015-05' AND ? 
		ORDER BY m2.m_live_zip";

		$from_members = $this->db->rawQuery ($sql, array($date,$date,$member_identify,$date));
		$from_html = '';
		$m=1; 
		$total=0;
		foreach ($from_members as $from_member) {
			$get_inro_count = $from_member["intro_count"];
			$cla =  "贊助會員";
			$sum=0;
			if(($get_inro_count>0) && ($get_inro_count <= 2))
				$cla = "輔導員";
			else if(($get_inro_count>2) && ($get_inro_count <= 9))
				$cla = "小組長";
			else if($get_inro_count>9)
				$cla = "組長";
			if(!empty($from_member["sum"]))
				$sum=$from_member["sum"];
			$idn = substr_replace($from_member['m_identify'],"***",3,4);
	                    	$get_name_len = iconv_strlen($from_member["m_name"], 'utf-8');
			if($get_name_len > 1){
				$get_first_w = iconv_substr($from_member["m_name"], 0, 1, 'utf-8');
				$get_third_w = iconv_substr($from_member["m_name"], 2, 1, 'utf-8');
				$name = $get_first_w."o".$get_third_w;
			}else{
				$name = $from_member["m_name"];
			}
			$from_html .= '<tr style="height: 30px;">';
			$from_html .= '<td class="text-center">'.$m.'</td>';
			$from_html .= '<td class="text-center">'.$from_member["m_live_zip"].'</td>';
			$from_html .= '<td class="text-center">'.$idn.'</td>';
			$from_html .= '<td class="text-center">'.$name.'</td>';
			$from_html .= '<td class="text-center">'.$from_member["m_gender"].'</td>';
			$from_html .= '<td class="text-center">'.$cla.'</td>';
			$from_html .= '<td class="text-center">'.$from_member["m_mobile"].'</td>';
			$from_html .= '<td class="text-center">'.$sum.'</td>';
			$from_html .= '</tr>';
			$m++; 
			$total+=(int)$sum;
		}
		$m = $m-1 ;
		$from_html .= '<tr style="height: 30px;">';
		$from_html .= '<td class="text-center" style="background:rgb(122, 80, 94);" colspan="3">合計</td>';
		$from_html .= '<td class="text-center" style="background:rgb(122, 80, 94);"colspan="1">'.$m.'</td>';
		$from_html .= '<td class="text-center" style="background:rgb(122, 80, 94);"colspan="3">總扶植點數</td>';
		$from_html .= '<td class="text-center" style="background:rgb(122, 80, 94);"colspan="1">'.$total.'</td>';
		$from_html .= '</tr>';
		return $from_html;
	}
	//計算自己從2015-05-01起到今日協會會員總人數(前台顯示)
	public function find_association_total_member_show(){
		$sql = "SELECT COUNT(member.id) AS num FROM member,level_relationship
		    				WHERE member.m_identify = level_relationship.children
		   				AND member.m_identify NOT LIKE '%-%'";
		$get_counts = $this->db->rawQuery ($sql);
		return $get_counts[0]['num'];
	}
     	private function search_sub_level_date($member_identify){//搜尋會員(母球+子球)的最新安置時間
		$sql_opt = "SELECT set_level_date FROM operation_log 
	        		         WHERE item = 1 
	        		         AND status = 1 
	        		         AND member_ori_id = ? 
	        		         ORDER BY set_level_date DESC LIMIT 1";
	        	$opt = $this->db->rawQuery ($sql_opt, array($member_identify));
	        	if($opt)
			return $opt[0]['set_level_date'];
		else
			return null;
	}
	private function insert_money_log_array($from,$to,$log,$out,$ref,$type,$money,$date){
		$data = array (
			"from_m_number" => $from,
			"to_m_number" => $to,
			"logname_ref" => $log,
			"outsidename_ref" => $out,
			"reference" => $ref,
			"type" => $type,
			"money" => $money,
			"money_fifteen" => 0,
			"money_nine" => 0,
			"log_date"  => $date
		);
		return $data;
	}
	private function insert_opt_array($money_start,$money_end){
		$data = array (
			"item" =>  2,
			"money_log_start" => $money_start,
			"money_log_end" => $money_end,
			"value" =>$this->end_date,
			"manager_id" => $this->manager_id,
			"datetime" =>  date('Y-m-d H:i:s')
		);
		return $data;
	}
	//搜尋會員子球B(有資料且已安置)
	private function search_sub_account($member_identify,$year){
		$get_member_id = '';
		$params = array($member_identify."-B".$year);
		$sql_pay = "SELECT member.id FROM member, level_relationship 
	        		         WHERE member.m_identify = ? 
	        		         AND level_relationship.children = member.m_identify";
	        	$pays = $db->rawQuery ($sql_pay, $params);
		$get_member_id = $pay[0]['id'];
		return  $get_member_id;
	}
	//搜尋會員入會時間
	private function search_member_sign_date($member_identify){
		$params = array($member_identify);
		$sql_ori = "SELECT m_sign_date FROM member WHERE m_identify = ? ";
		$oris = $db->rawQuery ($sql_ori, $params);
		$get_date = $oris[0]['m_sign_date'];
		return  $get_date;
	}
	//變更會員繳費狀態
	private function update_member_pay_status($member_identify,$pay_status){
		$sql_ed_pay = "update member SET is_pay = :status where m_identify = :idn";
		$update_ed_pay = $this->db->prepare($sql_ed_pay);
		$update_ed_pay->bindParam(":idn", $member_identify);
		$update_ed_pay->bindParam(":status", $pay_status);
		$update_ed_pay->execute();
	}
     	//檢查繳費(安置)
     	public function ckeck_pay_for_set(){
     		$tmparray = explode('-',$this->member_idn);  
        		$get_member_id = $tmparray[0];
        		$get_member_sign_date = $this->search_member_sign_date($get_member_id);
        		$get_diff =  $this->diff_month($get_member_sign_date);
        		$get_diff1 =  (int)($get_diff/12);
        		$get_diff2 =  (int)($get_diff%12);
        		if($get_diff1 > 0){
        		 	if($get_diff2 > 1){
        		 		$get_pay_member_id = $this->search_sub_account($get_member_id,$get_diff1);
        		 		if(!empty($get_pay_member_id))
					$this->update_member_pay_status($get_member_id,1);
        		 	}
        		}
     	}
	//扣款
     	public function pay_for_others(){
     		$tmparray = explode('-',$this->member_idn);  
        		$get_member_id = $tmparray[0];
        		$get_member_sign_date = $this->search_member_sign_date($get_member_id);
        		$get_diff =  $this->diff_month($get_member_sign_date);
        		$get_diff1 =  (int)($get_diff/12);
        		$get_diff2 =  (int)($get_diff%12);
        		if($get_diff1 > 0){
        		 	if($get_diff2 > 1){
        		 		$get_pay_member_id = $this->search_sub_account($get_member_id,$get_diff1);
        		 		if(!empty($get_pay_member_id))
					$this->update_member_pay_status($get_member_id,1);
        		 	}
        		}
     	}
}

