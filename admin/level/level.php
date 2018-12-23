<?php
	include "../includes/init.php";
	$m_number = $_GET['iden'];

	$db->join("level_relationship lr", "lr.children=m.m_mobile", "LEFT");
	$db->where('m_mobile', $m_number);
	$get_member = $db->get ("member m", null, "m.m_name");
	$lv = 0;
	$left = 0;
	$right = 0;
	$list = "<li>".$m_number."<br>".$get_member[0]['m_name'];
	find_child($m_number,0);
	$list .= "</li>";
    	//============================
	function find_child($parent,$type){
		global $db,$list,$lv,$left,$right;
		$params = array($parent);
		$sql_chi = "SELECT lr.*, m.m_name,
				(SELECT COUNT(*) FROM level_relationship WHERE lr.children = parent ) AS p_count
				FROM level_relationship AS lr, member AS m
				WHERE m.m_mobile = lr.children
				AND lr.parent = ?
				ORDER BY lr.position";
		
		$chis = $db->rawQuery ($sql_chi, $params);
		$list .= "<ul>";
		$lv++;
		foreach ($chis as $chi){ 
				$list .= "<li>".$chi['children']."<br>".$chi["m_name"];
				if($chi['p_count'] != 0 && $lv == 1){
					find_child($chi['children'],$chi['position']);
				}else if($chi['p_count'] != 0 && $lv > 1){
					find_child($chi['children'],$type);
				}
				if($lv == 1 && $chi['position'] ==1){
					$left++;
				}else	if($lv == 1 && $chi['position'] ==2){
					$right++;
				}else	if($lv > 1 && $type ==1){
					$left++;
				}else	if($lv > 1 && $type ==2){
					$right++;
				}
				$list .="</li>";
		}
		$lv--;
		$list .= "</ul>";
	}
	echo '左邊:'.$left;
	echo "<p>";
	echo '右邊:'.$right;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>會員排序圖</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.jOrgChart.css" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />
		<link rel="stylesheet" type="text/css" href="css/prettify.css" />
		<link rel="stylesheet" type="text/css" href="css/table1.css" />
		<script type="text/javascript" src="../js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="prettify.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="jquery.jOrgChart.js"></script>
		<script>
			jQuery(document).ready(function() {
				$("#org").jOrgChart({
					chartElement: '#chart',
					dragAndDrop: true
				});
			});
		</script>
	</head>
	<body onload="prettyPrint();">
		<div style="display:block;float:left;width:80%;">
			<ul id="org" style="display:none"><?=$list?></ul>
			<div id="chart" class="orgChart"></div>
			<script>
				jQuery(document).ready(function() {
					/* Custom jQuery for the example */
					$("#show-list").click(function(e) {
						e.preventDefault();
						$('#list-html').toggle('fast', function() {
							if ($(this).is(':visible')) {
								$('#show-list').text('Hide underlying list.');
								$(".topbar").fadeTo('fast', 0.9);
							} else {
								$('#show-list').text('Show underlying list.');
								$(".topbar").fadeTo('fast', 1);
							}
						});
					});
					$('#list-html').text($('#org').html());
					$("#org").bind("DOMSubtreeModified", function() {
						$('#list-html').text('');
						$('#list-html').text($('#org').html());
						prettyPrint();
					});
				});
			</script>
		</div>
	</body>
</html>