<?php
	include "includes/init.php";
	include "includes/_inc.php";
	include "includes/_chk_manager.php";
	$g_date = date("Y-m");
	$iden = '1040526032';
	if(isset($_GET['getdate']))
		$g_date = $_GET['getdate'];
	if(isset($_GET['iden']))
		$iden = $_GET['iden'];
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
			$(function(){
				var iden = "<?=$iden?>";
				var g_date = "<?=$g_date?>";
				var today = new Date(g_date);
				var mm = today.getMonth()+1; //January is 0!
				if (mm < 10) 
					mm = '0' + mm;
				var yyyy = today.getFullYear();
				//=============================================================
				$("#datepicker").val(yyyy+"-"+mm);
				$("#datepicker").datepicker({
					showOn: 'button',
					buttonText: '選擇月份',
					changeMonth: true,
					changeYear: true,
					dateFormat: 'mm-yy',
					showButtonPanel: true,
					monthNamesShort: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
					isSelMon: 'true',
					closeText: '選擇',
					currentText: '本月',
					defaultDate: new Date(),
					yearRange: "2015:new Date()",
					onClose: function (dateText, inst) {
						var month = +$("#ui-datepicker-div .ui-datepicker-month :selected").val() + 1,
						year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						if (month < 10)
							month = '0' + month;
						this.value = year + '-' + month;
						if (typeof this.blur === 'function') {
							this.blur();
							location.href = 'two_level_info.php?getdate='+this.value+'&iden='+iden;
						}
					}
				});
				get_table_data(iden,g_date);
			});
			function get_table_data(iden,get_date){
				var obj = {};
				obj['member_iden'] = iden;
				obj['g_date'] = get_date;
				$.ajax({
					url: 'ajax/get_two_level_info.php',
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
						$("#list_ta").html(response.list_ta);
						$("#list_tb").html(response.list_tb);
					}
				});
			}
		</script>
	</head>
	<body>
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
					<table class="table-fill" style="max-width: 100%;height: 100px;">
						<thead>
							<tr >
								<th scope="col" colspan="4" class="text-center th-o"><?=$member[0]['m_name'].$g_date?>自強帳戶與輔導A.B資料查詢</th>
								<th scope="col" colspan="2" class="text-center th-o">選擇月份:<input  type="text" id="datepicker"/></th>
							</tr>
						</thead>
					</table>
					<table class="table-fill" style="max-width: 1200px;">
						<thead>
							<tr>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">本月行政費15%總計</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">本月行政費9%總計</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">本月組長基數配點總計</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">本月行政費(15%+9%+組長分發)總計</th>
							</tr>
						</thead>
						<tbody class="table-hover" id="list_ta">	
						</tbody>
					</table>
       					<p>
					<table class="table-fill" style="max-width: 1200px;">
						<thead>
							<tr >
								<th style="background:rgb(122, 80, 94);" scope="col" colspan="12" class="text-center">自強關懷帳戶資料查詢</th>
							</tr>
							<tr>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">結算日期</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">職位</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">(A)人數</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">單日行政費15%</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">(B)人數</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">單日行政費9%</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">單日行政費(9%+15%)</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">總組長基數</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">自身組長基數</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">組長基數配點</th>
								<th style="background:rgba(97, 120, 98, 0.98);" scope="col" colspan="1" class="text-center">行政費合計</th>
							</tr>
						</thead>
						<tbody class="table-hover" id="list_tb">
						</tbody>
					</table>
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>