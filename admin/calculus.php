<?php
	include "includes/init.php";
	include 'includes/_inc.php';
	include "includes/_chk_manager.php";

	$last_count_date = '2015-04-30';
	$sql = "SELECT value FROM operation_log
		WHERE item = 2 AND  status = 1 ORDER BY value ASC";
	$get_dates = $db->rawQuery ($sql);
	$allRows = count($get_dates);
	$i = 1;
	$fir_day = '2015-05-01';
	$tmp_html = '';
	foreach($get_dates as $get_date){
		$get_day = $get_date["value"];
		if ($allRows == $i) {
			$tmp_html = '<tr>
						<td class="left">'.$i.'</td>
						<td class="left">'.$fir_day.'~'.$get_day.'</td>
						<td class="left"><a href="delete_calculus_month.php?date='.$get_day.'">刪除</a></td>
					</tr>'.$tmp_html;
		} else {
			$tmp_html = '<tr>
						<td class="left">'.$i.'</td>
						<td class="left">'.$fir_day.'~'.$get_day.'</td>
					</tr>'.$tmp_html;
		}
		$fir_day = $get_day;
		$fir_day = date('Y-m-d', strtotime("+1 day", strtotime("$get_day")));
		$last_count_date = $get_day;
		$i++;
	}
	$min_date =  date('Y-m-d', strtotime("$fir_day"));
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.4.custom/css/redmond/jquery-ui-1.10.4.custom.css" />
		<script type="text/javascript" src="js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-migrate-1.0.0.min.js"></script>
		<script type="text/javascript" src="js/superfish/js/superfish.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$("#datepicker").datepicker({
					showOn: 'button',
					buttonText: '選擇日期',
					changeMonth: true,
					changeYear: true,
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					monthNamesShort: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
					isSelMon: 'true',
					closeText: '結束',
					currentText: '本月',
					defaultDate: new Date(),
					yearRange: "2015:new Date()",
					minDate:'<?=$min_date?>'
				});
				$(document).on("click", "#calculus_btn", function() {
					var obj = {};
					obj['start_date'] = "<?=$min_date?>";
					obj['end_date'] = $("#datepicker").val();
					$.ajax({
						url: 'ajax/caculation.php',
						cache: false,
						dataType: 'json',
						type: 'POST',
						data: obj,
						error: function(jqXHR, textStatus, errorThrown) {
							console.log('HTTP status code: ' + jqXHR.status + '\n' +
							'textStatus: ' + textStatus + '\n' +
							'errorThrown: ' + errorThrown);
							console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
						},
						success: function(response) {
							alert(response.message);
							location.href = 'calculus.php';
						}
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
				<div class="breadcrumb"></div>
				<div class="box">
					<div class="left"></div>
					<div class="right"></div>
					<div class="heading">
						<h1 style="background-image: url('image/user.png');">區間結算列表</h1>
					</div><!--heading end-->
					<div class="content">
						<table class="form">
							<tr>
								<td style="width: 500px;">上次結算日期:
									<?=$last_count_date?>～本次結算日期:
									<input type="text" id="datepicker"/>
								</td>
								<td>
									<input type="button" id="calculus_btn" value="結算"/>
								</td>
							</tr>
						</table>
						<table class="list">
							<thead>
								<tr>
									<td class="left">排序</td>
									<td class="left">結算區間</td>
									<td class="left">動作</td>
								</tr>
							</thead>
							<tbody><?=$tmp_html?></tbody>
						</table><!--table(list) end-->
					</div><!--content(class) end-->
				</div><!--box end-->
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>