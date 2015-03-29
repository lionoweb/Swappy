<?php
//ENVOIE DE MAIL
require_once("config.php");
require_once("mail.php");
$mail = new mailer();
$arr = array();
if(isset($_POST['email'])) {
	$arr = $mail->send_contact_form($_POST);
}
echo json_encode($arr);
?>