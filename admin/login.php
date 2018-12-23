<?php
	include "includes/init.php";
	if($_POST['account'] != null || $_POST['password'] != null){
		$db->where ('account', $_POST['account']);
		$db->where ('password', $_POST['password']);
		$pro = $db->getOne ('manager');
		if($pro){
			print_r($pro);
			session_start();
			$_SESSION['account'] = $pro["account"];
			$_SESSION['manager_id'] = $pro["id"];
			$_SESSION['manager_name'] = $pro['name'];
			header("Location: profile.php");
		}else
			userMessage("帳號或密碼錯誤","index.php");
	}else
		header("Location: index.php");
?>
