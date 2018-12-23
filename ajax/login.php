<?php
	include "../include/init.php";
	if(!isset($_SESSION)) { 
			session_start(); 
	} 
	$data_message = array();
	$message = 'success';
	if($_POST['account'] != null || $_POST['password'] != null){
		$db->where ('m_mobile', $_POST['account']);
		$db->where ('m_password', $_POST['password']);
		$pro = $db->getOne ('member');
		if($pro){
			$_SESSION['m_mobile'] = $pro["m_mobile"];
			$_SESSION['m_id'] = $pro["id"];
		}else{
				$message = 'error';
		}
	}else{
		$message = 'error';
	}
		
	$data_message['message']   = $message;
	echo json_encode($data_message);
?>
