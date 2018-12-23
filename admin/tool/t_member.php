<?php
	include "../includes/init.php";
	include "../Class/operatormemberClass.php";
	$members = $db->get('member1');
	//print_r($members);
	foreach ($members as $member) {
		$getidn = $member["m_identify"];
		if(strpos($member["m_identify"], "-"))
			$getidn = explode("-",$member["m_identify"])[0];//此為子球 填入母球欄位
		$data_m = array (
			"m_name" => $member["m_name"],
			"m_identify" => $member["m_identify"],
			"m_parent" => $getidn,
	               	"m_gender" => $member["m_gender"],
	              	"m_birthday" => $member["m_birthday"],
	              	"m_live_zip" => $member["m_live_zip"],
	               	"m_phone" => $member["m_phone"],
	              	"m_mobile" => $member["m_mobile"],
	              	"m_email" => $member["m_email"],
	               	"m_sign_date" => $member["m_sign_date"],
	              	"m_intro_idn" => $member["m_intro_idn"]
		);
		$data_i = array (
			"m_identify" => $member["m_identify"],
	               	"m_bir_address" => $member["m_bir_address"],
	              	"m_live_address" => $member["m_live_address"],
	              	"m_heir_identify1" => $member["m_heir_identify1"],
	               	"m_heir_name1" => $member["m_heir_name1"],
	              	"m_heir_relship1" => $member["m_heir_relship1"],
	              	"m_heir_identify2" => $member["m_heir_identify2"],
	               	"m_heir_name2" => $member["m_heir_name2"],
	              	"m_heir_relship2" => $member["m_heir_relship2"]
		);
		$db->startTransaction();
		$m_id = $db->insert ('member', $data_m);
		if ($m_id) {
			$i_id = $db->insert ('imfomation', $data_i);
			if($m_id){
				$db->commit();
			}else{
				$db->rollback();
				echo "xx";
			}
		}else{
			$db->rollback();
			echo "xx2";
		}
	}
	echo "ok";
?>