<?php
	include "../includes/init.php";
	include "../includes/_chk_manager.php";
	include "../Class/operatormemberClass.php";
	if($_POST["type"] == "member"){
		if(empty($_POST["identify"]) || empty($_POST["name"]) || empty($_POST["mobile"]) || empty($_POST["sign_date"]) || empty($_POST["intro_idn"])){
			errMessageBack("身分證、姓名、手機、入會日期、招募人不能為空");
		}
		$optMember = new OperatorMember();
		if($_POST["action"] == "add_member"){
			$get_message = $optMember->insert_member();
			if($get_message['message'] == "success")
				choiseMessage("新增成功,如要繼續新增請按確定","../add_member.php" ,"../member.php");
			else
				errMessageBack("新增失敗");

		}else if($_POST["action"] == "edit_member"){
			$url = "../edit_member.php?iden=".$_POST["identify"];
			$get_message = $optMember->edit_member($_POST["iden"]);
			if($get_message['message'] == "success")
				userMessage("修改成功", $url);
			else
				errMessageBack("新增失敗");
		}
	}
?>