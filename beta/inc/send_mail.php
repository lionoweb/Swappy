<?php
require_once("mysql.php");
require_once("mail.php");
$mail = new mailer();
$arr = array();
if(isset($_POST['mail'])) {
	$arr = $mail->send_contact_form($_POST);
}
echo json_encode($arr);
?>