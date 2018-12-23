<!--A Design by W3layouts 
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<?php
	include "include/init.php";
	include "include/_chk_member.php";
	if(!isset($_SESSION)) { 
			session_start(); 
	} 
	$statment = "SELECT * FROM member WHERE id = ? ";
	$get_one = $db->rawQuery($statment,array($_SESSION['m_id']));

	$statment1 = "SELECT * FROM level_relationship WHERE parent = ? ";
	$get_childs = $db->rawQuery($statment1,array($_SESSION['m_mobile']));
	$left = '';
	$right = '';
	for($x=0;  $x < count($get_childs); $x++){
		if($get_childs[$x]['position'] == 1){
			$left = $get_childs[$x]['children'];
		}else{
			$right = $get_childs[$x]['children'];
		}
	}
?>
<!DOCTYPE html>
<!-- html -->
<html>
<!-- head -->
<head>
<title>Uright-Line</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" /><!-- bootstrap-CSS -->
<link href="css/font-awesome.css" rel="stylesheet" type="text/css" media="all" /><!-- Fontawesome-CSS -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type='text/javascript' src='js/jquery-2.2.3.min.js'></script>
<!-- Custom Theme files -->
<link href="css/menu.css" rel="stylesheet" type="text/css" media="all" /> <!-- menu style --> 
<!--theme-style-->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />	
<!--//theme-style-->
 <link rel="stylesheet" type="text/css" href="css/easy-responsive-tabs.css " />
<!--meta data-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta name="keywords" content="Match Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--//meta data-->
<!-- online fonts -->
<link href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;subset=devanagari,latin-ext" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
<!-- /online fonts -->
<!-- nav smooth scroll -->
<script>
$(document).ready(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
        }
    );
});
</script>
<!-- //nav smooth scroll -->
</head>
<!-- //head -->
<!-- body -->
<body >
<!-- header -->

	<!-- inner banner -->	
	<div class="w3layouts-inner-banner">
	<div class="container">
		<div class="logo">
			<h1><a class="cd-logo link link--takiri" href="index.php">Uright-Line </a></h1>
		</div>
		<div class="clearfix"></div>
		</div>
	</div>
	<!-- //inner banner -->	
	



	<!-- Search form -->
	<div class="w3ls-list">
		<div class="container">
		<h2>會員資訊</h2>
		
		<a href="admin/level/level.php?iden=<?php echo $get_one[0]['m_mobile']?>">點我看會員階層圖</a>
		<p>
		<a href="ajax/logout.php">登出</a>
		<div class="col-md-9 profiles-list-agileits">
		<!--Horizontal Tab-->
			<div id="parentHorizontalTab">
				<ul class="resp-tabs-list hor_1">
					<li>會員基本資料修改</li>
					<li>會員下線編輯</li>
				</ul>
				<div class="resp-tabs-container hor_1">
					<div>	
						<div class="w3_regular_search">
							<form action="#" method="post">	
							   <!-- <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">性別 : </label>
								<div class="col-sm-7 form_radios">
									<input type="radio" name="gender" value="male" checked> 男性 &nbsp;&nbsp;
									<input type="radio" name="gender" value="female"> 女性<br>
								
								</div>
								<div class="clearfix"> </div>
							</div> -->
							  <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">手機 : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<?php echo $get_one[0]['m_mobile']?>
								  </div>
								</div>
								<div class="clearfix"> </div>
							  </div>
							  <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">會員姓名 : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<input type="text" style="width:100%" id="m_name"  value="<?php echo $get_one[0]['m_name']?>">
								  </div>
								</div>
								<div class="clearfix"> </div>
							  </div>
							  <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">email : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<input type="text" style="width:100%"  id="m_email" value="<?php echo $get_one[0]['m_email']?>">
								  </div>
								</div>
								<div class="clearfix"> </div>
								</div>
								 <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">會員新密碼 : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<input type="password" style="width:100%" id="m_pwd" >
								  </div>
								</div>
								<div class="clearfix"> </div>
								</div>
							
							  <input type="button" id="editBasic" class="edit_basic" value="儲存" />
							</form>
						</div>
					</div>
					<div>
						<div class="w3_regular_search">
							  <div class="agileits_advanced_Search">
							 	  <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">左邊下線 : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<input type="text" style="width:100%" id="left" value="<?php echo $left?>" placeholder="請填寫下線會員手機">
								  </div>
								</div>
								<div class="clearfix"> </div>
							  </div>
							  <div class="form_but1">
								<label class="col-sm-5 control-label1" for="sex">右邊下線 : </label>
								<div class="col-sm-7 form_radios">
								  <div class="select-block1">
									<input type="text" style="width:100%" id="right" value="<?php echo $right?>" placeholder="請填寫下線會員手機">
								  </div>
								</div>
								<div class="clearfix"> </div>
							  </div>
								</div>
								  <input type="button" id="editChild" class="edit_basic" value="儲存" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="clearfix"></div>
	</div>
	</div>
	<script src="js/easyResponsiveTabs.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {

			$('#parentHorizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion
				width: 'auto', //auto or any width like 600px
				fit: true, // 100% fit in a container
				closed: 'accordion', // Start closed if in accordion view
				tabidentify: 'hor_1', // The tab groups identifier
				activate: function (event) { // Callback function if tab is switched
					var $tab = $(this);
					var $info = $('#nested-tabInfo');
					var $name = $('span', $info);
		
					$name.text($tab.text());
		
					$info.show();
				}
			});
	 
		});
	</script>
	<!-- //Search form -->
	
	
<!-- footer -->
<footer>
	
	<div class="copy-right"> 
		<div class="container">
			<p>© 2017 Match. All rights reserved | Design by <a href="http://w3layouts.com"> W3layouts.</a></p>
		</div>
	</div> 
</footer>
<!-- //footer -->	
<!-- menu js aim -->
	<script src="js/jquery.menu-aim.js"> </script>
	<script src="js/main.js"></script> <!-- Resource jQuery -->
	<!-- //menu js aim -->
	<!-- for bootstrap working -->
		<script src="js/bootstrap.js"></script>
<!-- //for bootstrap working -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
	  			containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
	 		};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
							
		});
	</script>
	<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
	<!-- for smooth scrolling -->
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
	//================================================
					//基本資料
				$(document).on("click", "#editBasic", function() {
					if($("#m_name").val().length == 0 ||$("#m_email").val().length == 0){
						alert('姓名 email不能為空');
						return false;
					}
					var obj = {};
					obj['m_name'] = $("#m_name").val();
					obj['m_email'] = $("#m_email").val();
					obj['m_pwd'] = $("#m_pwd").val();
					obj['m_id'] = "<?php echo $_SESSION['m_id']?>"
					$.ajax({
						url: 'ajax/edit_member.php',
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
								alert('修改成功');
								location.reload();
							}else{
								alert('修改失敗');
							}
						}
					});
				});
				//================================================
					//安排下線
				$(document).on("click", "#editChild", function() {
					var obj = {};
					obj['left'] = $("#left").val();
					obj['right'] = $("#right").val();
					obj['m_mobile'] = "<?php echo $_SESSION['m_mobile']?>"
					$.ajax({
						url: 'ajax/edit_child.php',
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
								alert('修改成功');
								location.reload();
							}else{
								alert('修改失敗');
							}
						}
					});
				});
	</script>
	<!-- //for smooth scrolling -->
</body>
<!-- //body -->
</html>
<!-- //html -->
