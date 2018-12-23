<?php
  include "../include/init.php";
  $data_message = array();
  $table = 'member';

  if(empty($_POST['m_pwd'])){
    	$data = array (
        "m_name" => $_POST['m_name'],
        "m_email" => $_POST['m_email'],
      );
  }else{
    $data = array (
      "m_name" => $_POST['m_name'],
      "m_email" => $_POST['m_email'],
      "m_password" =>  $_POST['m_pwd']
    );
  }
	
  $message = "success";
  $db->startTransaction();
		$db->where ("id", $_POST['m_id'] );
		if ($db->update ('member', $data)){
			$db->commit();
		}else{
			$db->rollback();
			$data_message  = "failure";
		}
  $data_message['message']   = $message;
	echo json_encode($data_message);

?>