<?php
require_once("mysql.php");
require_once("user.php");
require_once("services.php");
require_once("chat.php");
$user = new user($mysql);
$arr = array();
if(isset($_POST['type']) && !isset($_POST['ID_EDIT'])) {
	$services = new services($mysql);
	$arr = $services->add_services($_POST, $user);
}
if(isset($_POST['type']) && isset($_POST['ID_EDIT'])) {
	$services = new services($mysql);
	$arr = $services->edit_services($_POST, $user);
}
if(isset($_POST['note'])) {
	$chat = new chat($mysql, $user);
	$services = new services($mysql);
	$arr = $services->add_note($_POST, $user, $chat);
}
if(isset($_GET['delete'])) {
	$services = new services($mysql);
	$arr = $services->delete_serv(@$_GET['delete'], $user->ID);
}
if(isset($_GET['list_coms'])) {
	$arr = $user->list_com(@$_GET['list_coms'], false);
}
if(isset($_GET['list_coms'])) {
		echo $arr;
	} else {
		echo json_encode($arr);
	}
?>