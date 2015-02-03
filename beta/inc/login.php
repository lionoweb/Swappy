<?php
	session_start();
	require_once("mysql.php");
	require_once("user.php");
	$arr = array();
	if(isset($_POST['login_form_'])) {
		$user = new user($mysql);
		$arr = $user->flogin($_POST);
	}
	if(isset($_POST['remind'])) {
		require_once("mail.php");
		$user = new user($mysql);
		$arr = $user->remind_mail($_POST['remind']);
	}
	echo json_encode($arr);
?>