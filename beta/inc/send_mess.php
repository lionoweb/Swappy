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
	if(isset($_POST['for_report']) && isset($_POST['type'])) {
		$arr = $chat->send_report($user->ID, trim($_POST['for_report']), trim($_POST['type']), trim($_POST['message']));
	}
	if(isset($_GET['list_message'])) {
		$arr = $chat->list_message(@$_GET['search']);
	}
	if(isset($_POST['date'])) {
		$arr = $chat->make_date($_POST);
	}
	if(isset($_GET['make_date'])) {
		$arr = $chat->modal_date(@$_GET['make_date']);
	}
	if(isset($_GET['valid'])) {
		$arr = $chat->valid_a(@$_GET['valid'], @$_GET['cc']);
	}
	if(isset($_GET['refuse'])) {
		$arr = $chat->refuse_a(@$_GET['refuse'], @$_GET['cc']);
	}
	if(isset($_GET['get_message']) && !empty($_GET['get_message'])) {
		$arr = $chat->content_conv($_GET['get_message']);
	}
	if(isset($_GET['delete']) && !empty($_GET['delete'])) {
		$arr = $chat->delete($_GET['delete']);
	}
	if(isset($_POST['message_r'])) {
		$arr = $chat->send_r($user, $_POST);
	}
	if(isset($_GET['make_date'])) {
		echo $arr;
	} else {
		echo json_encode($arr);
	}
?>