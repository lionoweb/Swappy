<?php
	require_once("mysql.php");
	$arr = array();
	require_once("mail.php");
	require_once("user.php");
	require_once("services.php");
	require_once("chat.php");
	$user = new user($mysql);
	$chat = new chat($mysql, $user);
	if(isset($_POST['for']) && isset($_POST['to'])) {
		if(is_numeric($_POST['for'])) {
			$service = new services($mysql, $_POST['for']);	
		} else {
			$service = 	$_POST['for'];
		}
		$user_ = new user($mysql, $user->uncrypt_sess($_POST['to']));
		$arr = $chat->send($user_, $_POST, $service);
	}
	echo json_encode($arr);
?>