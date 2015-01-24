<?php
class mail {
	function send_validation($email, $name, $hash) {
		$to = $email;
		$subject = 'Inscription sur Swappy.fr';
		
		$headers = "From : noreply@swappy.fr\r\n";// supp balises HTML et PHP d'une chaîne
		$headers .= "MIME-Version: 1.0\r\n"; // ??
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message = '<html><body>';
		$message .= '<h2>Bienvenue dans la communauté swappy !</h2>';
		$message .= '<p>Merci '.$name.' pour ton inscription sur notre site.<br><br>Merci de bien vouloir activer ton compte en cliquant sur ce lien :<br><a href="http://swappy.fr/beta/inscription.php?validation='.$hash.'" target="_blank">http://swappy.fr/beta/inscription.php?validation='.$hash.'</a><br><br><i>Si tu ne peut pas cliquer sur ce lien, copie et colle le lien dans ton navigateur.</i></p>';
		$message .= "</body></html>";
		
		if(mail($to, $subject, $message, $headers)) {
			return true;
		} else {
			return false;
		}
	}
	function send_contact_form($POST) {
		$to = 'contact@swappy.fr';
		$subject = 'Message de Swappy';
		
		$headers = "From : ".strip_tags($POST['email']) . "\r\n";// supp balises HTML et PHP d'une chaîne
		$headers .= "MIME-Version: 1.0\r\n"; // ??
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message = '<html><body>';
		$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
		
		// Prénom
		$message .= "<tr style='background: #eee;'><td><strong>Prénom :</strong> </td><td>" . strip_tags($POST['name']) . "</td></tr>";
		
		// Nom
		$message .= "<tr style='background: #eee;'><td><strong>Nom :</strong> </td><td>" . strip_tags($POST['lastname']) . "</td></tr>";
		
		// Email
		$message .= "<tr><td><strong>Email :</strong> </td><td>" . strip_tags($POST['email']) . "</td></tr>";
		
		// Objet
		$message .= "<tr><td><strong>Objet :</strong> </td><td>" . $POST['object'] . "</td></tr>";
		
		// Message
		$message .= "<tr><td><strong>Message :</strong> </td><td>" . htmlentities($POST['msg']) . "</td></tr>";
		
		$message .= "</table>";
		$message .= "</body></html>";
		
		if(mail($to, $subject, $message, $headers)) {
			return array(true);
		} else {
			return array(false, "Une erreur d'envoie à eu lieu");
		}
	}
}

?>