<?php
	session_start();
	if(!(isset($_SESSION['manager_id']))){
		if( basename($_SERVER["SCRIPT_FILENAME"], '.php') != 'index'){
			header("Location: index.php");
		}
	}else{
		if( basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'index'){
			header("Location: profile.php");
		}
	}
?>
