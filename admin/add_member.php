<?php
	include "includes/init.php";
	include "includes/_inc.php";
	include "includes/_chk_manager.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.4.custom/css/redmond/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" type="text/css" href="js/jQuery-Validation/css/validationEngine.jquery.css" />
		<script type="text/javascript" src="js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-migrate-1.0.0.min.js"></script>
		<script type="text/javascript" src="js/superfish/js/superfish.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/jQuery-Validation/js/jquery.validationEngine-zh_TW.js"></script>
		<script type="text/javascript" src="js/jQuery-Validation/js/jquery.validationEngine.js"></script>
		<script type="text/javascript">
			function pick_intro() {
				$("#s_name").val("");
				$("#s_mobile").val("");
				$("#s_identify").val("");
				var opt = "<option value='0'>請先搜尋會員</option>";
				$("#intro_sel").html(opt);
				$('#inline').fadeIn(200);
				$('#inline').show();
			}
			$(function(){
				$("#admin_form").validationEngine();
				$("#birthday").datepicker({
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
					yearRange: "1920:new Date()",
				});
				$("#sign_date").datepicker({
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
				});
				$(document).on("click", ".SHOW_x", function() {
					$('#inline').hide();
				});
				$(document).on("click", "#btnSearch", function() {
					var s_name = $("#s_name").val();
					var s_mobile = $("#s_mobile").val();
					var s_identify = $("#s_identify").val();
					var obj = {};
					obj['s_name'] = s_name;
					obj['s_mobile'] = s_mobile;
					obj['s_identify'] = s_identify;
					$.ajax({
						url: 'ajax/search_intro.php',
						cache: false,
						dataType: 'json',
						type: 'POST',
						data: obj,
						error: function(jqXHR, textStatus, errorThrown) {
							console.log('HTTP status code: ' + jqXHR.status + '\n' +
							'textStatus: ' + textStatus + '\n' +
							'errorThrown: ' + errorThrown);
							console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
							alert('發生錯誤');
						},
						success: function(response) {
							var opt = "";
							$.each(response, function(index, value) {
								opt += "<option value='"+value['info']+"'>"+value['info']+"</option>";
							});
							$("#intro_sel").html(opt);
							$("#intro_sel").trigger("change");
						}
					});
				});
				$(document).on("click", "#btnSave", function() {
					if($("#intro_sel").val() == 0){
						alert("請選擇招募人");
						return false;
					}
					var get_info = $("#intro_sel").val().split(' | ');
					$("#intro_name").val($("#intro_sel").val());
					$("#intro_idn").val(get_info[0]);
					$('#inline').hide();
				});
			});
		</script>
	</head>
	<body>
		<!--check popup-->
		<div id="inline" class=" TB_BG" style="display:none;">
			<div class="TB_box">
				<div class="up_win_top">
					<h3 style="float:left;"><span id="act"></span>選擇招募人</h3><p>
					<a class="SHOW_x"><img src="image/tb-close.png" width="15" height="15" title="關閉視窗" /></a></p>
				</div>
				<div style="height:300px; overflow:auto;">
					<h3 style="float:left;"><span id="act"></span>搜尋招募人</h3>
					<table class="form">
						<tr>
							<td>招募人姓名搜尋輸入:</td>
							<td><input type="text" id="s_name" name="name" /></td>
							<td>招募人手機搜尋輸入:</td>
							<td><input type="text" id="s_mobile" name="s_mobile" /></td>
						</tr>
						<tr>
							<td>招募人身分證字號搜尋輸入:</td>
							<td><input type="text" id="s_identify" name="s_identify" /></td>
							<td><a href="javascript:void(0)" id="btnSearch" class="button"><span>搜尋招募人</span></a></td>
						</tr>
						<tr>
							<td>選擇招募人:</td>
							<td>
								<select id="intro_sel"><option value="0">請先搜尋招募人</option></select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><a href="javascript:void(0)" id="btnSave" class="button"><span>確定</span></a></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!--check popup end-->
		<div id="container">
			<?php include("includes/_menu.php");?>
			<div id="content">
				<div class="breadcrumb"></div>
				<div class="box">
					<div class="left"></div>
					<div class="right"></div>
					<div class="heading">
						<h1 style="background-image: url('image/user.png');">新增會員</h1>
					</div><!--heading end-->
					<div class="content">
						    <form name="admin_form" action="ajax/member_opt.php" method="post" enctype="application/x-www-form-urlencoded" id="admin_form">
							<table class="form">
								<tr>
									<td width="200"><span class="required">*</span> 會員姓名:</td>
									<td><input type="text" name="name" id="name"   class="text-input validate[required]"/></td>
									<td width="200"><span class="required">*</span> 會員身分證字號/公司統編:<br>(格式:A123456789/A123456789-A1)</td>
									<td><input  type="text" name="identify" id="identify"  class="text-input validate[required]"/></td>
								</tr>
								<tr>
									<td width="200"><span class="required">*</span> 會員性別:</td>
									<td><select name="gender" class="validate[required] text-input"><option value="男">男</option><option value="女">女</option></select></td>
									<td width="200"><span class="required">*</span> 會員生日(格式:2016-01-01):</td>
									<td><input  type="text" name="birthday" id="birthday"  class="datepicker" readonly="true"/></td>
								</tr>
								<tr>
									<td width="200"> 會員email:</td>
									<td><input  style="width:300px"  type="text" name="email" id="email"  class="text-input"/></td>
									<td width="200"><span class="required">*</span> 會員戶籍地址:</td>
									<td><input  style="width:300px" type="text" name="bir_address" id="bir_address"  class="text-input"/></td>
								</tr>
								<tr>
									<td width="200"> 會員通訊地址郵遞區號:</td>
									<td><input  type="text" name="live_zip" id="live_zip"  class="text-input"/></td>
									<td width="200"> 會員通訊地址:</td>
									<td><input style="width:300px"  type="text" name="live_address" id="live_address"  class="text-input"/></td>
								</tr>
								<tr>
									<td width="200"> 會員住宅電話:(格式:078887777)</td>
									<td><input  type="text" name="phone" id="phone"  class="text-input"/></td>
									<td width="200"><span class="required">*</span> 會員手機(格式:0911222333):</td>
									<td><input  type="text" name="mobile" id="mobile"  class="text-input validate[required,custom[phone]]"/></td>
								</tr>
								<tr>
									<td width="200"><span class="required">*</span> 招募人:</td>
									<td><input  type="text" id="intro_name" style="width:300px" readonly="true"  class="validate[required]"/>
									<input  type="hidden" id="intro_idn" name="intro_idn" />
									<button>
									<a  href="#" onclick="pick_intro();return false;" >選擇招募人</a></button>
									</td>
									<td width="200"><span class="required">*</span> 入會日期(格式:2016-01-01):</td>
									<td><input  type="text" name="sign_date" id="sign_date"  class="datepicker validate[required]" readonly="true"/></td>
								</tr>
								<tr>
									<td width="200"><span class="required">*</span> 繼承人1姓名:</td>
									<td><input  type="text" name="heir_name1" id="heir_name1"  class="text-input"/></td>
									<td width="200"><span class="required">*</span> 繼承人1與會員關係:</td>
									<td><input  type="text" name="heir_relship1" id="heir_relship1"  class="text-input"/></td>
								</tr>
								<tr>
									<td width="200"><span class="required">*</span> 繼承人1身分證(格式:A123456789):</td>
									<td><input  type="text" name=" heir_identify1" id="heir_identify1"  class="text-input"/></td>
								</tr>
								<tr>
									<td width="200"> 繼承人2姓名:</td>
									<td><input  type="text" name="heir_name2" id="heir_name2"  class="text-input"/></td>
									<td width="200"> 繼承人2與會員關係:</td>
									<td><input  type="text" name="heir_relship2" id="heir_relship2"  class="text-input"/></td>
								</tr>
								<tr>
									<td width="200"> 繼承人2身分證(格式:A123456789):</td>
									<td><input  type="text" name="heir_identify2" id="heir_identify2"  class="text-input"/></td>
								</tr>
								<tr>
									<td></td>
									<td>
									<div class="buttons">
									<input name="subbtn" type="submit" id="subbtn" value="儲存"  class="button2" />
									<input name="cancel" type="button" id="cancel" value="取消"  class="button2" onclick="javascript:location.href='member.php'" style="margin-left: 10px;"/>
									</div>
									</td>
								</tr>
							</table><!--table end-->
						<input name="type" type="hidden" value="member"/>
						<input name="action" type="hidden" value="add_member"/>
						</form><!--form end-->
					</div><!--content(class) end-->
				</div><!--box end-->
				<ul class="pagination" id="pagination"></ul>
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>