<?php
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
echo json_encode($arr);
?>