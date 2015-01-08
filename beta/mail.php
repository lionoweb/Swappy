<?php

$to = 'noreply@swappy.fr';

$subject = 'Message de Swappy';

$headers = "From : ".strip_tags($_POST['email']) . "\r\n";// supp balises HTML et PHP d'une chaîne
$headers .= "MIME-Version: 1.0\r\n"; // ??
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
$message .= '<img src="" alt="Message de Swappy" />';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';

// Prénom
$message .= "<tr style='background: #eee;'><td><strong>Prénom :</strong> </td><td>" . strip_tags($_POST['name']) . "</td></tr>";

// Nom
$message .= "<tr style='background: #eee;'><td><strong>Nom :</strong> </td><td>" . strip_tags($_POST['lastname']) . "</td></tr>";

// Email
$message .= "<tr><td><strong>Email :</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";

// Objet
$message .= "<tr><td><strong>Objet :</strong> </td><td>" . $_POST['object'] . "</td></tr>";

// Message
$message .= "<tr><td><strong>Message :</strong> </td><td>" . htmlentities($_POST['msg']) . "</td></tr>";

$message .= "</table>";
$message .= "</body></html>";

if(mail($to, $subject, $message, $headers)) {
	echo json_encode(array(true));
} else {
	echo json_encode(array(false, "Une erreur d'envoie à eu lieu"));
}

?>