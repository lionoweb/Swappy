<?php
require_once("mysql.php");
require_once("user.php");
require_once("services.php");
$arr = array();
if(isset($_POST['type'])) {
	$services = new services($mysql);
	$arr = $services->add_services($_POST);
}
echo json_encode($arr);
?>