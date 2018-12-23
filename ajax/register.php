<?php
  include "../include/init.php";
  $data_message = array();
  $table = 'member';

  $pass = substr( $_POST['mobile'], 5 , 4 );
	$data = array (
    "m_mobile" =>  $_POST['mobile'],
    "m_name" => $_POST['name'],
    "m_email" => $_POST['email'],
    "m_password" => $pass
  );
  $message = "success";
  $db->where ("m_mobile", $_POST['mobile'] );
  $result = $db->getOne ($table);
  // var_dump($result['is_check']);
  // die();
  if($result != null){
    $message = "pass";
    if($result['status'] == 0){
      $message = "process";
    }
  }else{
    $db->startTransaction();
    $id = $db->insert($table, $data);
    if ($id) {
      $db->commit();
    }else{
      $db->rollback();
      $message = "failure";
    }
  }
  // var_dump($data);
  // die();
  
  $data_message['message']   = $message;
	echo json_encode($data_message);

?>