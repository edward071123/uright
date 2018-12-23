<?php
  include "../include/init.php";
  $data_message = array();
  $table = 'level_relationship';
  $message = "success";
  $db->startTransaction();
  $db->where ('parent', $_POST['m_mobile']);
  if (!$db->delete ($table)){
     $db->rollback();
  }
  
  if(!empty($_POST['left'])){
    	$data = array (
        "parent" => $_POST['m_mobile'],
        "children" => $_POST['left'],
        "position" => 1
      );
      $id = $db->insert ($table, $data);
      if (!$id) {
        $db->rollback();
        $message  = "failure";
      }
  }
  if(!empty($_POST['right'])){
    	$data = array (
        "parent" => $_POST['m_mobile'],
        "children" => $_POST['right'],
        "position" => 2
      );
      $id = $db->insert ($table, $data);
      if (!$id) {
        $db->rollback();
        $message  = "failure";
      }
  }
  $db->commit();
  $data_message['message']   = $message;
	echo json_encode($data_message);

?>