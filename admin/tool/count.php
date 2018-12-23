<?php
	include "../includes/init.php";
	$lv = 0;
	$data = array();
	find_child("1040526032");
	$yy = 0;
	//print_r($data);
	while($yy < count($data)){
		$data11 = Array ('level' => $data[$yy]['level']);
		$db->where ('children', $data[$yy]['child']);
		if ($db->update ('level_relationship', $data11))
			echo $yy;
		else
			echo 'update failed: ' . $db->getLastError();
		$yy++;
	}
	//============================
	function find_child($parent){
		global $db,$data,$lv;
		$params = array($parent);
		$sql_chi = "SELECT lr.*, m.m_name,
				(SELECT COUNT(*) FROM level_relationship WHERE lr.children = parent ) AS p_count
				FROM level_relationship AS lr, member AS m
				WHERE m.m_identify = lr.children
				and parent = ? ORDER BY lr.position";
		$chis = $db->rawQuery ($sql_chi, $params);
		$lv++;
		foreach ($chis as $chi){ 
			$sub1 = array();
			$sub1['parent']  = $parent;
			$sub1['child']  = $chi["children"];
			$sub1['name']  = $chi["m_name"];
			$sub1['level']  = $lv;
			array_push($data ,  $sub1);
			if($chi['p_count'] != 0)
				find_child($chi['children']);
		}
		$lv--;
	}
?>