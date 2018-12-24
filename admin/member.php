<?php 
	include "includes/init.php";
	include "includes/_inc.php";
	include "includes/_chk_manager.php";
	include "Class/memberClass.php";
	include "Class/calculateClass.php";
	$memberList = new Member();
	$calculate = new Calculate();
          $page = "1";
	if(isset($_GET['name']))
		$memberList->get_condition_name($_GET['name']);
	if(isset($_GET['mobile']))
                     $memberList->get_condition_mobile($_GET['mobile']);
	if(isset($_GET['start_date_c']))
           	$memberList->get_condition_start_date($_GET['start_date_c']); 
	if(isset($_GET['end_date_c']))
           	$memberList->get_condition_end_date($_GET['end_date_c']);
	if (isset($_GET['page']))
		$page = $_GET['page'];
	$sql = $memberList->get_basic_member_query();
	$get_query =  $memberList->get_basic_member_query_show();
	$db->pageLimit = 10;
	$members = $db->paginate_query($sql, $page);
	$totalpage = (int)($db->totalPages);
	$user_total = $db->totalCount;
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.4.custom/css/redmond/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" type="text/css" href="js/twbsPagination/bootstrap.min.css" />
		<script type="text/javascript" src="js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-migrate-1.0.0.min.js"></script>
		<script type="text/javascript" src="js/superfish/js/superfish.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/twbsPagination/jquery.twbsPagination.min.js"></script>
		<script type="text/javascript">
			$(function(){
				//================================================
				window.pagObj = $('#pagination').twbsPagination({
					totalPages: <?=$totalpage?>,
					visiblePages: 10,
					startPage: <?=$page?>,
					first:'第一頁',
					prev:'前一頁',
					next:'下一頁',
					last:'最後一頁'
				}).on('page', function (event, page) {
					var query = "<?=$get_query?>";
					location.href = "member.php?page="+page+query;
				});
				//================================================
				$("#start_date_c").datepicker({
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
					onClose: function( selectedDate ){
						$("#end_date_c" ).datepicker("option", "minDate", selectedDate);
					}
				});
				$("#end_date_c").datepicker({
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
					onClose: function( selectedDate ){
						$("#start_date_c").datepicker("option", "maxDate", selectedDate);
					}
				});
				//================================================
				//招募人一覽視窗
				$(document).on("click", ".intro_list", function() {
					$("#self_intro_list").html("");
					$("#self_intro_list_act").html();
					var value_array = $(this).prop("id").split("&&");
					$('#inline4').fadeIn(200);
					$('#inline4').show();
					var obj = {};
					obj['m_identify'] = value_array[1];
					$.ajax({
						url: 'ajax/search_self_intro.php',
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
							$("#self_intro_list").html(response.html);
							$("#self_intro_list_act").html(value_array[1]+" | "+value_array[2]+" | 招募表一覽 | 實際招募人數:"+response.count);
						}
					});
				});
				//================================================
				//改變狀態
				$(document).on("click", ".changeToSataus", function() {
					var value_array = $(this).prop("id").split("_");
					var obj = {};
					obj['mobile'] = value_array[1];
					obj['status'] = value_array[2];
					console.log(obj);
					$.ajax({
						url: 'ajax/changeMemberStatus.php',
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
							console.log(response);
							if(response.message == 'success'){
									var query = "<?=$get_query?>";
									var page = "<?=$page?>";
									location.href = "member.php?page="+page+query;
							}else{
								alert('出錯');
							}
						
						}
					});
				});
				//================================================
				//關閉視窗
				$(document).on("click", ".SHOW_x4", function() {
					$('#inline4').hide();
				});
			});
		</script>
	</head>
	<body>
		<!--check popup-->
		<!--招募圖1-->
		<div id="inline4" class=" TB_BG" style="display:none;">
			<div class="TB_box">
				<div class="up_win_top">
					<h3 style="float:left;"><span id="self_intro_list_act">招募表一覽</span></h3><p>
					<a class="SHOW_x4"><img src="image/tb-close.png" width="15" height="15" title="關閉視窗" /></a></p>
				</div>
				<div style="height:300px; overflow:auto;">
					<table class="form">
						<thead>
							<tr>
								<td>身分證字號</td>
								<td>姓名</td>
								<td>繳費狀態</td>
							</tr>
						</thead>
						<tbody id="self_intro_list">
						</tbody>
					</table>
				</div>
			</div><!--TB_box end-->
		</div><!--inline3 end-->
		<!--自動安置 end-->
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
				<div class="breadcrumb"></div>
				<div class="box">
					<div class="left"></div>
					<div class="right"></div>
					<div class="heading">
						<h1 style="background-image: url('image/user.png');">會員資料查詢列表</h1>
						<!-- <div class="buttons">
							<a href="add_member.php" class="button"><span>後台新增會員</span></a>
						</div> -->
					</div><!--heading end-->
					<div class="content">
						<form action="member.php"  method="get">
							<table class="form">
								<tr>
									<td width="200"> 會員姓名:</td>
									<td><input type="text" name="name"/></td>
									<td width="200">行動電話:</td>
									<td><input type="text" name="mobile"/></td>
								</tr>
								<tr>
									<td width="200">入會日期:</td>
									<td><input type="text" name="start_date_c" id="start_date_c" class="datepicker"/>~<input type="text" name="end_date_c" id="end_date_c" class="datepicker"/></td>
								</tr>
								<tr>
									<td>
										<div class="buttons">
											<input name="subbtn" type="submit" id="subbtn" value="查詢"  class="button2" />
										</div>
									</td>
								</tr>
							</table>
						</form><!--form end-->
						<table class="list">
							<thead>
								<tr>
									<td class="left">會員姓名</td>
									<td class="left">會員編號</td>
									<td class="left">入會日期</td>
									<td class="left">行動電話(帳號)</td>
									<td class="left">推薦人(姓名)</td>
									<td class="left">推薦人(手機)</td>
									<td class="left">狀態</td>
									<td class="left">動作</td>
									<!-- <td class="left">詳細基本資料</td> -->
								</tr>
							</thead>
							<tbody id="list">
							<?php 
								foreach ($members as $member){ 
									$status = '尚未審核';
									if($member['status'] == 1){
										$status = '上線';
									}else if($member['status'] == 2){
										$status = '停權';
									}
									echo "<tr>";
									echo "<td class='left'>".$member['m_name']."</td>";
									echo "<td class='left'>".$member['m_ori_id']."</td>";
									echo "<td class='left'>".$member['m_sign_date']."</td>";
									echo "<td class='left'>".$member['m_mobile']."</td>";
									echo "<td class='left'>".$member['m_ref_name']."</td>";
									echo "<td class='left'>".$member['m_ref_mobile']."</td>";
									echo "<td class='left'>".$status."</td>";
									echo "<td class='left'>";
									if($member['status'] == 0){
											echo "<button class='changeToSataus' id='ctp_".$member['m_mobile']."_1'>通過審核</button>";
									}else if($member['status'] == 1){
											echo "<button class='changeToSataus' id='cts_".$member['m_mobile']."_2'>停權</button>";
									}else if($member['status'] == 2){
											echo "<button class='changeToSataus' id='ctr_".$member['m_mobile']."_1'>恢復上線</button>";
									}
									echo "<button><a target='_blank' href='level/level.php?iden=".$member['m_mobile']."'>階層圖</a></button>";
									echo "</td>";
									// echo "<td class='left'>";
									// echo "<button><a target='_blank' href='edit_member.php?iden=".$member['m_mobile']."'>編輯基本資料</a></button>";
									// echo "</td>";
									echo "</tr>";
								} 
							?>
							</tbody>
						</table><!--table(list) end-->
						 會員人數:<?=$user_total; ?>
					</div><!--content(class) end-->
				</div><!--box end-->
				<ul class="pagination" id="pagination"></ul>
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>