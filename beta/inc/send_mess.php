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
	if(isset($_GET['list_message'])) {
		$arr = $chat->list_message();
	}
	if(isset($_GET['get_message']) && !empty($_GET['get_message'])) {
		$arr = $chat->content_conv($_GET['get_message']);
	}
	echo json_encode($arr);
?>