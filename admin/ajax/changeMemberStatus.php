<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";

	$data_message = array();

  $data = array (
    "status" =>  $_POST['status'],
  );
  $table = 'member';
  $message = "success";
  $db->startTransaction();
  $db->where ('m_mobile', $_POST['mobile']);
  if ($db->update ($table, $data)){
    $db->commit();
  }else{
    $db->rollback();
    $message  = "failure";
  }
  $data_message['message'] = $message;
    echo json_encode($data_message);
?>