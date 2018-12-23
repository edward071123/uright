<?php
	include "includes/init.php";
	include 'includes/_inc.php';
	include "includes/_chk_manager.php";
	isset($_GET['getdate']) ? $g_date = $_GET['getdate'] : $g_date = date("Y-m");
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="css/table1.css" >
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
					buttonText: '選擇日期',
					changeMonth: true,
					changeYear: true,
					dateFormat: "yy-mm-dd",
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
							location.href = 'area.php?getdate='+this.value;
						}
					}
				});
				//================================================
				$("#list_level").jqGrid({
					url:'ajax/get_area.php',
					datatype: "json",
					mtype: 'GET',
					colNames:['序','登入編號','地區','會員姓名','職稱','招募人數','性別','累計儲值點數'],
					colModel:[
						{name:'no',index:'no', width:100, sortable:false,align:'center'},
						{name:'idn',index:'idn', width:100, sortable:false,align:'center'},
						{name:'zip',index:'zip', width:100, sortable:false,align:'center'},
						{name:'m_name',index:'m_name', width:100, sortable:false,align:'center'},
						{name:'cla',index:'cla', width:100, sortable:false,align:'center'},
						{name:'intro_count',index:'intro_count', width:100, sortable:false,align:'center'},
						{name:'m_gender',index:'m_gender', width:100, sortable:false,align:'center'},
						{name:'m_total_money',index:'m_total_money', width:150, sortable:false,align:'center'}
					],
					postData:{g_date:g_date},
					rowNum:100000,
					pgbuttons:false,
					grouping:true,
					groupingView : {
						groupField : ['zip'],
						groupText : ['<b>{0} - {1} 人</b>'],
						groupCollapse : true,
					},
					height: "1000px",
					autowidth:true,
					pager: '#pager_level',
					sortname: 'no',
					viewrecords: true,
					loadonce: true,
					sortorder: "asc",
					caption:"地區招募列表",
					loadError: function (jqXHR, textStatus, errorThrown) {
						console.log('HTTP status code: ' + jqXHR.status + '\n' +
						'textStatus: ' + textStatus + '\n' +
						'errorThrown: ' + errorThrown);
						console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
					}
				}).navGrid("#pager_account",{edit:false,add:false,del:false,search:false});
				
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
						<h1 style="background-image: url('image/user.png');">地區招募列表</h1>
						<div class="buttons">
							<input type="text" id="datepicker"/>
						</div>
					</div><!--heading end-->
					<div class="content">
						<!--table  start-->
						<table id="list_level"></table>
						<div id="pager_level"></div>
						<!--table end-->
					</div><!--content(class) end-->
				</div><!--box end-->
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>