<?php
require_once("mysql.php");
require_once("mail.php");
$mail = new mail();
$arr = array();
if(isset($_POST['email'])) {
	$arr = $mail->send_contact_form($_POST);
}
echo json_encode($arr);
?>