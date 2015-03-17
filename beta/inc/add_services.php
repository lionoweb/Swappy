<?php
require_once("mysql.php");
require_once("user.php");
require_once("services.php");
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
echo json_encode($arr);
?>