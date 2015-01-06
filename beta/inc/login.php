<?php
	session_start();
	require_once("mysql.php");
	if(isset($_POST['login_form'])) {
		$arr = array();
		require_once("user.php");
		$user = new user($mysql);
		$arr = $user->flogin($_POST);
		echo json_encode($arr);
	}
?>