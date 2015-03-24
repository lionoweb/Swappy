<?php
//ENVOIE ET RECEPTION JSON POUR UTILISATEURS
require_once("mysql.php");
$arr = array();
require_once("mail.php");
require_once("user.php");
$user = new user($mysql);
if(isset($_POST['login'])) {
	$arr = $user->add_user($_POST);
}
if(isset($_POST['a_mdp'])) {
	$arr = $user->edit_user($_POST);
}
if(isset($_POST['hash'])) {
	$arr = $user->remind_account($_POST);
}
if(isset($_POST['update'])) {
	$arr = $user->update($_POST['variable'], $_POST['field'], $_POST['value']);
}
if(isset($_POST['fieldId'])) {
	if($_POST['fieldId'] == "login") {
		$arr = $user->issetLogin($_POST['fieldValue'], $_POST['fieldId']);
	}
	if($_POST['fieldId'] == "email") {
		$arr = $user->issetEmail($_POST['fieldValue'], $_POST['fieldId']);
	}
	if($_POST['fieldId'] == "zipcode") {
		$arr = $user->issetZipCode($_POST['fieldValue'], $_POST['fieldId']);
	}
}
if(isset($_FILES['file-avatar'])) {
	require_once("class.upload.php");
	$arr = $user->change_avatar($_FILES['file-avatar']);
}
if(isset($_GET['count_mess'])) {
	$arr = $user->list_messages();
}
if(isset($_GET['json_cal'])) {
	$arr = $user->json_calendar($_GET['year'], $_GET['month']);
}
echo json_encode($arr);
?>