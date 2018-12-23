<?php 
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/levelClass.php";

	$manager_id = '56';
	$bank_number = '1040526032';
	// $start_date = '2015-10-01';
	// $end_date = '2015-10-31';
	// $start_date = '2015-11-01';
	// $end_date = '2015-11-30';
	// $start_date = '2015-12-01';
	// $end_date = '2015-12-31';
	// $start_date = '2016-01-01';
	// $end_date = '2016-01-31';
	// $start_date = '2016-02-01';
	// $end_date = '2016-02-29';
	// $start_date = '2016-03-01';
	// $end_date = '2016-03-31';
	// $start_date = '2016-04-01';
	// $end_date = '2016-04-30';
	// $start_date = '2016-05-01';
	// $end_date = '2016-05-31';
	// $start_date = '2016-06-01';
	// $end_date = '2016-06-30';
	// $start_date = '2016-07-01';
	// $end_date = '2016-07-31';
	// $start_date = '2016-08-01';
	// $end_date = '2016-08-31';
	// $start_date = '2016-09-01';
	// $end_date = '2016-09-30';
	// $start_date = '2016-10-01';
	// $end_date = '2016-10-31';
	// $start_date = '2016-11-01';
	// $end_date = '2016-11-30';
	$start_date = '2016-12-01';
	$end_date = '2016-12-31';

	$sql = "SELECT  * 
		FROM level_relationship , member 
		WHERE member.m_identify = level_relationship.children
		AND level_relationship.children <> ? 
		AND (member.m_sign_date >= ? AND member.m_sign_date <= ?)";
	$members = $db->rawQuery ($sql, array($bank_number,$start_date,$end_date));
	$count = 0;
	foreach ($members as $member){
		$level = new Level($member['children'],$member['parent'],$member['position'],$member['level'],$manager_id );
		$error = $level->muti_main($member['id']);
		if(empty($error))
			echo $member['children']."安置成功！<p>";
		else
			echo $error."<p>";
		$level = null;
		$count ++;
	}
	echo $count;

?>