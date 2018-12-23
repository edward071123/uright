<!--A Design by W3layouts 
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php
	
include "include/_chk_member.php";

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
<!-- Calendar -->
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<script src="js/jquery-ui.js"></script>
		<script>
		  $(function() {
			$( "#datepicker" ).datepicker();
		 });
		</script>
<!-- //Calendar -->			
<link rel="stylesheet" href="css/intlTelInput.css">
</head>
<!-- //head -->
<!-- body -->
<body>

<div class="w3layouts-banner" id="home">
<div class="container">
	<div class="logo">
		<h1><a class="cd-logo link link--takiri" href="index.php">Uright-Line </a></h1>
	</div>
	<div class="clearfix"></div>
	<div class="agileits-register">
		<h3>立刻申請!</h3>
		<form action="#" method="post">
				<div class="w3_modal_body_grid w3_modal_body_grid1">
					<span>姓名:</span>
					<input type="text" id="name" placeholder="王小明" required=""/>
				</div>
			
				<div class="w3_modal_body_grid w3_modal_body_grid1">
				<span>手機(即註冊帳號):</span>
				<!-- country codes (ISO 3166) and Dial codes. -->
					<input type="text" id="mobile" placeholder="0911222333" required=""/>
				  <!-- Load jQuery from CDN so can run demo immediately -->
				</div>
				<div class="w3_modal_body_grid">
					<span>Email:</span>
					<input type="email" id="email" placeholder="aa@gmail.com" required=""/>
				</div>
				<input type="button" class="register-btn" id="register" value="送出申請" />
				<div class="clearfix"></div>
				<p class="w3ls-login">已經是會員? <a href="#" data-toggle="modal" data-target="#myModal">登入</a></p>
			</form>
		</div>
		<!-- Modal -->
				<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>		
					  </div>
					  <div class="modal-body">
						<div class="login-w3ls">
							<form id="signin" action="#" method="post">
								<label>帳號(手機) </label>
								<input type="text" id="account" placeholder="0911222333" required="">
								<label>密碼</label>
								<input type="password" id="pwd" placeholder="密碼" required="">	
								<div class="clearfix"> </div>
								<input type="button" class="login-btn" id=login value="登入">
								<div class="clearfix"> </div>
								</div>	
							</form>
						</div>
					  </div>
					</div>
				  </div>
				</div>
				<!-- //Modal -->
	</div>
</div>
<!-- Find your soulmate -->
	<div class="w3l_find-soulmate text-center">
		
	</div>
	<!-- //Find your soulmate -->
		

	
<?php include('include/footer.php')?>
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
				//申請
				$(document).on("click", "#register", function() {
					if($("#name").val().length == 0 ||$("#mobile").val().length == 0 ||$("#email").val().length == 0 ){
						alert('請確實填寫欄位');
						return false;
					}
					var obj = {};
					obj['name'] = $("#name").val();
					obj['mobile'] = $("#mobile").val();
					obj['email'] = $("#email").val();
					$.ajax({
						url: 'ajax/register.php',
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
								alert('申請成功');
							}else if(response.message == 'pass'){
								alert('已經通過申請 請直接登入');
							}else if(response.message == 'process'){
								alert('已經申請 目前檢驗中 請等候通知');
							}else {
								alert('已經失敗');
							}
							location.reload();
						}
					});
				});
					//================================================
					//登入
				$(document).on("click", "#login", function() {
					if($("#account").val().length == 0 ||$("#pwd").val().length == 0){
						alert('帳號密碼不能為空');
						return false;
					}
					var obj = {};
					obj['account'] = $("#account").val();
					obj['password'] = $("#pwd").val();
					$.ajax({
						url: 'ajax/login.php',
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
								alert('登入成功');
								location.href = "member.php";
							}else if(response.message == 'error'){
								alert('帳號密碼錯誤');
							}else{
								alert('登入失敗');
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
