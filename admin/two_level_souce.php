<?php
	include "includes/init.php";
	include 'includes/_inc.php';
	include "includes/_chk_manager.php";
	$iden = '1040526032';
	if(isset($_GET['iden']))
		$iden = $_GET['iden'];
	$startDay = $_GET['startDay'];
    	$endDay = $_GET['endDay'];
	$cols = array ("m_name");
	$db->where ("m_identify", $iden);
	$member = $db->get ("member", null, $cols);
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="css/table.css" >
		<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.4.custom/css/redmond/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="js/grid/css/ui.jqgrid.css" />
		<script type="text/javascript" src="js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-migrate-1.0.0.min.js"></script>
		<script type="text/javascript" src="js/superfish/js/superfish.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/grid/js/i18n/grid.locale-tw.js"></script>
		<script type="text/javascript" src="js/grid/js/jquery.jqGrid.min.js"></script>
		<style>
			.ui-datepicker table {display: none;}
		</style>
		<script type="text/javascript">
			function lista(id){
				$("#list_a").html("");
				var obj = {};
				obj['member_iden'] = id;
				obj['s_date'] = "<?=$startDay?>";
				obj['e_date'] = "<?=$endDay?>";
				$.ajax({
					url: 'ajax/a_month_detail.php',
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
						//console.log(response);
						var sum = 0;
						$.each(response, function(index, value) {
							//console.log(value['m_name']);
							sum +=  parseInt(value['m_money']);
							var tr_list = '<tr>'+
							'<td class="text-center">'+value['no']+'</td>'+
							'<td class="text-center">'+value['m_name']+'</td>'+
							'<td class="text-center">'+value['m_money']+'</td></tr>';
							$("#list_a").append(tr_list);
						});
						var tr_end = '<tr>'+
						'<td  colspan="2" class="text-center">總計</td>'+
						'<td  colspan="1" class="text-center">'+sum+'</td></tr>';
						$("#list_a").append(tr_end);
						setTimeout(function() {listb('<?=$iden?>');}, 1000);
					}
				});
			}
			function listb(id){
				$("#list_b").html("");
				var obj = {};
				obj['member_iden'] = id;
				obj['s_date'] = "<?=$startDay?>";
				obj['e_date'] = "<?=$endDay?>";
				$.ajax({
					url: 'ajax/b_month_detail.php',
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
						var sum = 0;
						$.each(response, function(index, value) {
							sum +=  parseInt(value['m_money']);
							var tr_list = '<tr>'+
							'<td class="text-center">'+value['no']+'</td>'+
							'<td class="text-center">'+value['a_m_name']+'</td>'+
							'<td class="text-center">'+value['m_name']+'</td>'+
							'<td class="text-center">'+value['m_money']+'</td></tr>';
							$("#list_b").append(tr_list);
						});
						var tr_end = '<tr>'+
						'<td  colspan="3" class="text-center">總計</td>'+
						'<td  colspan="1" class="text-center">'+sum+'</td></tr>';
						$("#list_b").append(tr_end);
					}
				});
			}
		</script>
	</head>
	<body onload="lista('<?=$iden?>');">
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
				<table class="table-fill" style="max-width: 1200px;">
					<thead>
					<tr>
						<th scope="col" colspan="6" class="text-center"><?=$member[0]['m_name']?> (<?=$startDay?>~<?=$endDay?>) 自強帳戶與輔導A.B資料查詢</th>
					</tr>
					</thead>
				</table>
				<div style="display:block;float:left;width:50%;">
					<table class="table-fill">
						<thead>
							<tr>
								<th style="background:rgb(57, 56, 96);" scope="col" colspan="3" class="text-center">本日(A)行政作業費費計算明細</th>
							</tr>
							<tr>
								<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">序</th>
								<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">姓名</th>
								<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">受助點數</th>
							</tr>
						</thead>
						<tbody class="table-hover" id="list_a">
						</tbody>
					</table>
				</div>
				<div style="display:block;float:left;width:50%;">
					<table class="table-fill">
						<thead>
						<tr>
							<th style="background:rgb(57, 56, 96);" scope="col" colspan="4" class="text-center">本日(B)行政作業費費計算明細</th>
						</tr>
						<tr>
							<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">序</th>
							<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">輔導A點數</th>
							<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">B姓名</th>
							<th style="background:rgb(159, 17, 209);" scope="col" colspan="1" class="text-center">受助點數</th>
						</tr>
						</thead>
						<tbody class="table-hover" id="list_b"></tbody>
					</table>
				</div>
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>