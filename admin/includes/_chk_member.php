<?php
	session_start();
	if(!(isset($_SESSION['manager_id']))){
		$iden = $_REQUEST['iden'];
		$code =$_REQUEST['code'];
		$decode_iden = trim(decrypt($encode_string,$iden));
		$decode_code = trim(decrypt($encode_string,$code));
		$db->where ("m_identify", $decode_iden);
		$db->where ("m_mobile", $decode_code);
		$user = $db->getOne ("member");
		if($user)
			$member_iden = $decode_iden;
		else
			return false;
	}else
		$member_iden = $_REQUEST['member_iden'];
?>	

