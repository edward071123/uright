<?php
include "includes/init.php";
$sql = "SELECT * FROM activity where news_id = ".$_GET['newsId'];
$rs_news = mysqli_query($link, $sql);
$news_one = mysqli_fetch_array($rs_news, MYSQL_ASSOC);

?>


<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>台灣弱勢自強協會</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<?php include "menu.php"; ?>
	<div id="contents">
        <div class="clearfix">
			<h1><?=$news_one['title']?></h1><h2><strong><?=$news_one['publicDate']?></strong></h2>
			<p>
             <?=$news_one['content']?>
		</div>
	</div>
	<div id="footer">
		<div class="clearfix">
			<div class="section">
				<h4>Contact Us</h4>
				<p>
			  台灣弱勢自強協會© Copyright all Rights</p>
				<p>住址:台南市中西區民生路二段9號</p>
				<p>電話:06-2210881</p>
				<p>傳真:06-2214963</p>
			</div>
			<div class="section contact">
				<h4>Quick Link</h4>
                <ul class="underMenu">
                 <?php include "qlink.php"; ?>
                </ul>
			</div>
			<div class="section">
				<h4>related Links</h4>
				<ul class="underMenu">
                 <li><a href="http://www.etax.nat.gov.tw">財政部稅務入口網</a></li>
                 </ul>
			</div>
		</div>
		<div id="footnote">
			<div class="clearfix">
				<div class="connect">
					<a href="#" class="facebook"></a><a href="#" class="twitter"></a><a href="#" class="googleplus"></a><a href="#" class="pinterest"></a>
				</div>
				<p>© Copyright 2015 台灣弱勢自強協會 All Rights Reserved. </p>
			</div>
		</div>
	</div>
</body>
</html>
