<?php
	include "includes/init.php";
	include 'includes/_inc.php';
	include "includes/_chk_manager.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pageTitle;?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css"  />
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<script type="text/javascript" src="js/jquery/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-migrate-1.0.0.min.js"></script>
		<script type="text/javascript" src="js/superfish/js/superfish.js"></script>
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
						<h1 style="background-image: url('image/setting.png');">主要選單</h1>
					</div><!--heading end-->
					<div class="content">
						<div id="tab_general">
							<div id="language1">
								<table class="table-fill">
									<tbody class="table-hover">
										<!-- <tr style="height: 60px;">
											<td class="text-center">(一)</td>
											<td class="text-center">後台新增(會員)基本自強單位</td>
											<td class="text-center">
											<a  href="add_member.php">ENTER</a></td>
										</tr> -->
										<tr style="height: 60px;">
											<!-- <td class="text-center">(二)</td> -->
											<td class="text-center">會員管理列表</td>
											<td class="text-center">
											<a  href="member.php">ENTER</a></td>
										</tr>
										<!-- <tr style="height: 60px;">
											<td class="text-center">(三)</td>
											<td class="text-center">區間結算列表</td>
											<td class="text-center">
											<a  href="calculus.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(四)</td>
											<td class="text-center">福委會後台管理總表</td>
											<td class="text-center">
											<a  href="report.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(五)</td>
											<td class="text-center">幹部列表</td>
											<td class="text-center">
											<a  href="position.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(六)</td>
											<td class="text-center">地區招募列表</td>
											<td class="text-center">
											<a  href="area.php">ENTER</a></td>
										</tr> -->
										<!-- <tr style="height: 60px;">
											<td class="text-center">(五)</td>
											<td class="text-center">基本自強單位生日列表</td>
											<td class="text-center">
											<a  href="member_birthday.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(六)</td>
											<td class="text-center">福委會後台管理總表</td>
											<td class="text-center">
											<a  href="member_report.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(七)</td>
											<td class="text-center">組長區域後台管理</td>
											<td class="text-center">
											<a  href="list_area_manager.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(八)</td>
											<td class="text-center">組長產品項目管理</td>
											<td class="text-center">
											<a  href="product_item.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(九)</td>
											<td class="text-center">前台最新消息管理</td>
											<td class="text-center">
											<a  href="news.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(十)</td>
											<td class="text-center">前台服務管理</td>
											<td class="text-center">
											<a  href="service.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(十一)</td>
											<td class="text-center">活動管理</td>
											<td class="text-center">
											<a  href="activity.php">ENTER</a></td>
										</tr>
										<tr style="height: 60px;">
											<td class="text-center">(十二)</td>
											<td class="text-center">購物報表</td>
											<td class="text-center">
											<a  href="group_buy_report.php">ENTER</a></td>
										</tr> -->
									</tbody>
								</table>
							</div><!--language1 end-->
						</div><!--tab_general end-->
					</div><!--content(class) end-->
				</div><!--box end-->
			</div><!--content(id) end-->
		</div><!--container end-->
		<?php include("includes/_footer.php");?>
	</body>
</html>