<?php
	include "../includes/init.php";
	$sql = "SELECT m_identify from member";
	//print_r($data);
	$persons = $db->rawQuery ($sql);
	foreach($persons as $person){
		$getidn = $person["m_identify"];
		if(strpos($person["m_identify"], "-"))
			$getidn = explode("-",$person["m_identify"])[0];
		$db->where ('m_identify', $person["m_identify"]);
		$db->update ('member', array('m_parent'=>$getidn));
	}

?>