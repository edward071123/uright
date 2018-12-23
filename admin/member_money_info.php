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
							location.href = 'member_money_info.php?getdate='+this.value+'&iden='+iden;
						}
					}
				});
				//=============================================================
				$("#list_account").jqGrid({
					url:'ajax/get_self_individual.php',
					datatype: "json",
					mtype: 'GET',
					colNames:['序','m_n','會員', '本月累積善德點數'],
					colModel:[
						{name:'no',index:'no', width:50, sortable:false,align:'center'},
						{name:'m_idn',index:'m_idn', hidden:true},
						{name:'idn_number',index:'idn_number', width:150, sortable:false,align:'center',
						formatter:function sel_member_f(cellvalue, options, rowObject){
							var getValue = cellvalue.split('/');
							return "<input style='width:150px;font-size:20px;' type='button' class='sel_member' id='m^"+getValue[1]+"' value='"+getValue[0]+"' /><a href='level/level.php?iden="+getValue[1]+"&date="+g_date+"' target='_blank'>階層圖</a><input style='width:50px;font-size:20px;' type='button' class='sel_system' id='s^"+getValue[1]+"' value='體系' />";
						}},
						{name:'money',index:'money', width:150, sortable:false,align:'center'}
					],
					postData:{g_date:g_date,member_iden:iden},
					rowNum:200000,
					height: "300px",
					autowidth:true,
					pager: '#pager_account',
					sortname: 'no',
					viewrecords: true,
					loadonce: true,
					sortorder: "asc",
					caption:"會員扶助點總數資料",
					loadComplete: function () {
						var ids = $("#list_account").jqGrid('getDataIDs');
						var cl = ids[0];
						var rowData = $(this).getRowData(cl);
						var temp= rowData['m_idn'];
						setTimeout(function() {info(temp,g_date);}, 1000);
						setTimeout(function() {$("#list_log").setGridParam({
							postData: {m_idn:temp,g_date:g_date}
						}).trigger("reloadGrid");}, 2000);
					},
					loadError: function (jqXHR, textStatus, errorThrown) {
						console.log('HTTP status code: ' + jqXHR.status + '\n' +
						'textStatus: ' + textStatus + '\n' +
						'errorThrown: ' + errorThrown);
						console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
					}
				}).navGrid("#pager_account",{edit:false,add:false,del:false,search:false});
				//=============================================================
				$("#list_log").jqGrid({
					url:'ajax/get_individual_source.php',
					datatype: "json",
					mtype: 'GET',
					colNames:['序','會員','身分證字號','配置序','本月累積扶助點數'],
					colModel:[
						{name:'no',index:'no', width:150, sortable:false,align:'center'},
						{name:'idn_name',index:'idn_name', width:150, sortable:false,align:'center'},
						{name:'idn_number',index:'idn_number', width:150, sortable:false,align:'center'},
						{name:'lv',index:'lv', width:100, sortable:false,align:'center'},
						{name:'money',index:'money', width:150, sortable:false,align:'center'}
					],
					postData:{g_date:g_date,member_iden:iden},
					footerrow: true,
					rowNum:200000,
					height: "100%",
					autowidth:true,
					pager: '#pager_log',
					pgbuttons:false,
					gridview: true,
					sortname: 'id',
					viewrecords: true,
					loadonce: true,
					sortorder: "asc",
					caption:"本月受助關懷單位資料來源",
					loadComplete: function () {
						var sum = $("#list_log").jqGrid('getCol', 'money', false, 'sum');
						$("#list_log").jqGrid('footerData','set', {lv: '總計:', money: sum});
					},
					loadError: function (jqXHR, textStatus, errorThrown) {
						$("#list_log").jqGrid('footerData','set', {lv: '總計:', money: 0});
						console.log('HTTP status code: ' + jqXHR.status + '\n' +
						'textStatus: ' + textStatus + '\n' +
						'errorThrown: ' + errorThrown);
						console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
					}
				}).navGrid("#pager_log",{edit:false,add:false,del:false,search:false});
				//=============================================================
				$(document).on("click", ".sel_system", function() {
					var get_number = $(this).prop('id').split("^")[1];
					var obj = {};
					obj['g_date'] = g_date;
					obj['member'] = get_number;
					$.ajax({
						url: 'ajax/system_count.php',
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
							alert("體系數:"+response.count);
						}
					});
				});
				//=============================================================
				$(document).on("click", ".sel_member", function() {
					var get_number = $(this).prop('id').split("^")[1];
					$("#list_log").jqGrid("clearGridData");
					$("#list_log").jqGrid('setGridParam', { datatype: 'json' ,postData: {menber_iden:get_number,g_date:g_date}}).trigger('reloadGrid');
				});
			});
			function info(iden,get_date){
				var obj = {};
				obj['member_iden'] = iden;
				obj['g_date'] = get_date;
				$.ajax({
					url: 'ajax/count_month_detail.php',
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
						$("#self_basic").html(response.self_basic);
						$("#self_o_basic").html(response.self_o_basic);
						$("#self_bo_basic").html(response.self_bo_basic);
						$("#self_10_a_basic").html(response.self_10_ta_basic);
						$("#self_10_b_basic").html(response.self_10_tb_basic);
						$("#self_80_basic").html(response.self_80_basic);
						$("#intro_count").html(response.intro_count+"人");
						$("#cla").html(response.cla);
						$("#self_70_cadre").html(response.self_70_cadre);
						$("#self_15_a_cadre").html(response.self_15_a_cadre);
						$("#self_15_b_cadre").html(response.self_15_b_cadre);
						$("#total_ch_div").html(response.self_total_cadre);
						$("#get_c_m_income").html(response.self_80_basic);
						$("#get_c_m_cadre").html(response.self_70_cadre);
						$get_c_m_total = (response.self_70_80_m_income) + (response.funeral);
						$("#get_c_m_total").html( $get_c_m_total);
						$("#get_yc_m_income").html(response.get_y_80_income);
						$("#get_yc_m_cadre").html(response.get_y_70_income);
						$("#get_yc_m_total").html(response.get_70_80_y_income);
						$("#association_funeral").html(response.funeral);
						$("#system").html(response.system);
					}
				});
			}
		</script>
	</head>
	<body>
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
					<table class="table-fill-o" style="max-width: 100%;height: 100px;">
						<thead>
							<tr >
								<th scope="col" colspan="1" class="text-center th-o"><?=$member[0]['m_name'].$g_date?>月份有捐有助&善德共管專戶明細查詢</th>
								<th scope="col" colspan="1" class="text-center th-o">選擇月份:<input  type="text" id="datepicker"/></th>
							</tr>
						</thead>
					</table>
					<!--div left start-->
					<div style="display:block;float:left;width:50%;">
						<table id="list_account"></table>
						<div id="pager_account"></div>
						<p>
						<table id="list_log"></table>
						<div id="pager_log"></div>
					</div><!--div left end-->
					<!--div right start-->
					<div style="display:block;float:left;width:50%;">
						<!--table top start-->
						<table class="table-fill-o">
							<thead>
								<tr><th scope="col" colspan="2" class="text-center th-o">有捐有助的善德共管點數計算</th></tr>
							</thead>
							<tbody class="table-hover" >
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月基本帳戶善德點數:</td>
									<td class="td-o text-left" id="self_basic">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月其他帳戶善德點數:</td>
									<td class="td-o text-left" id="self_o_basic">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">合計:</td>
									<td class="td-o text-left" id="self_bo_basic">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月挹注協會善德點數(10%):</td>
									<td class="td-o text-left" id="self_10_a_basic">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月會員預留自強點數(10%):</td>
									<td class="td-o text-left" id="self_10_b_basic">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月會員實際善德點數(80%):</td>
									<td class="td-o text-left" id="self_80_basic">0</td>
								</tr>
							</tbody>
						</table><!--table top end-->
						<!--table mid1 start-->
						<table class="table-fill-o">
							<thead>
								<tr>
									<th scope="col" colspan="4" class="text-center th-o">有勞有得幹部行政補助費計算</th>
								</tr>
							</thead>
							<tbody class="table-hover" >
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">招募扶助總人數:</td>
									<td class="td-o text-left" id="intro_count">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">體系人數:</td>
									<td class="td-o text-left" id="system" >0人</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">職稱:</td>
									<td class="td-o text-left" id="cla">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-center">組長行政作業補助點數合計:<p><(A)+(B)行政作業補助點數+組長應領基數補助點數></td>
									<td class="td-o text-left" ><span id="total_ch_div">0</span><p>
									<a href="two_level_info.php?getdate=<?=$g_date?>&iden=<?=$iden?>" target="_blank">點我查看來源</a>
									</td>
								</tr>
								<tr class="tr-o" style="height: 15px;">
									<td class="td-o text-right">增加個人轉換自強帳戶預留點數(15%):</td>
									<td class="td-o text-left" id="self_15_a_cadre">0</td>
								</tr>
								<tr class="tr-o" style="height: 15px;">
									<td class="td-o text-right">挹注協會行政作業用點數(15%):</td>
									<td class="td-o text-left" id="self_15_b_cadre">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月會員個人分配行政作業費點數(70%):</td>
									<td class="td-o text-left" id="self_70_cadre">0</td>
								</tr>
							</tbody>
						</table><!--table mid1 end-->
						<!--table mid2 start-->
						<table class="table-fill-o">
							<thead>
								<tr>
									<th scope="col" colspan="2" class="text-center th-o">本月累計</th>
								</tr>
							</thead>
							<tbody class="table-hover" >
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">累計共管善德可用點值:</td>
									<td class="td-o text-left" id="get_c_m_income">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">本月會員個人累計行政作業費有得值:</td>
									<td class="td-o text-left" id="get_c_m_cadre">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">挹注喪葬補助基金:</td>
									<td class="td-o text-left" id="association_funeral" style="color:red">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">合計:</td>
									<td class="td-o text-left" id="get_c_m_total">0</td>
								</tr>
							</tbody>
						</table><!--table mid2 end-->
						<!--table bottom start-->
						<table class="table-fill-o">
							<thead>
								<tr>
									<th scope="col" colspan="2" class="text-center th-o">年度總累計</th>
								</tr>
							</thead>
							<tbody class="table-hover" >
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">累計共管善德可用點值:</td>
									<td class="td-o text-left" id="get_yc_m_income">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">會員個人累計行政作業費總有得值:</td>
									<td class="td-o text-left" id="get_yc_m_cadre">0</td>
								</tr>
								<tr class="tr-o" style="height: 30px;">
									<td class="td-o text-right">合計:</td>
									<td class="td-o text-left" id="get_yc_m_total">0</td>
								</tr>
							</tbody>
						</table><!--table bottom end-->
					</div><!--div right end-->
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>