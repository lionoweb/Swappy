<?php
require('class.html2text.inc');
require('PHPMailerAutoload.php');
class mailer {
	public $noreply = "no-reply@swappy.fr";
	public $contact = "contact@swappy.fr";
	
	function txt_mail($msg) {
		$txt = "";
		
		return $txt;
	}
	function sendmail($to, $from, $fromname="Swappy.fr", $subject, $html) {
		
		$h2t =& new html2text($html);
		// Simply call the get_text() method for the class to convert
		// the HTML to the plain text. Store it into the variable.
		$text = $h2t->get_text();
		if(!preg_match("/\@swappy\.fr/", $from)) {
			$boundary = md5(uniqid(rand()));
			
			$headers = "Reply-To: ".$fromname." <".$from.">\n";
			$headers = "From: ".$fromname." <".$from.">\n";// supp balises HTML et PHP d'une chaîne
			$headers .= "MIME-Version: 1.0\n"; // ??
			$headers .= "Content-Type: multipart/alternative; boundary=".$boundary."\n";
			$headers .= "X-Mailer: PHP/".phpversion()."\n"; 
			$headers .= "Return-Path: <".$from.">\n";
			
			
			
			$message .= "\n--".$boundary."\n";
			$message .= "Content-Type: text/plain;charset=UTF-8\n";
			$message .= "Content-Transfer-Encoding: 8bit\n";
			$message .= "\n".$text."\n";
			
			$message .= "\n--".$boundary."\n";
			$message .= "Content-Type: text/html;charset=UTF-8\n";
			$message .= "Content-Transfer-Encoding: 8bit\n\n";
			$message .= ''.stripslashes($html).'';
	
			$message .= "\n--".$boundary."--\n";
			
			return mail($to, $subject, $message, $headers);
		} else {
			//$to = "autorespond+dkim@dk.elandsys.com";
			//$to = "check-auth2@verifier.port25.com";
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'ssl0.ovh.net';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = $from;                 // SMTP username
			$mail->Password = '2Dside770';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to
			
			$mail->From = $from;
			$mail->Sender=$from;
			$mail->ReturnPath=$from;
			$mail->FromName = $fromname;
			$mail->addAddress($to);               // Name is optional
			$mail->addReplyTo($from, $fromname);
			$mail->DKIM_domain = 'swappy.fr';
			$mail->DKIM_identity = $from;
			$mail->DKIM_private = 'swappy.fr.pem';
			$mail->DKIM_selector = 'swappy'; //this effects what you put in your DNS record

			$mail->CharSet = "UTF-8";
			$mail->isHTML(true);                                  // Set email format to HTML
			
			$mail->Subject = $subject;
			$mail->Body    = $html;
			$mail->AltBody = $text;
			
			if(!$mail->send()) {
				return false;
			} else {
				return true;
			}
		
		}
		
	}
	function send_remind($hash, $mail, $name) {
		
		$to = $mail;
		$subject = 'Changement du mot de passe sur Swappy.fr';
		
		$html = '<html><body>';
		$html .= '<h2>Mot de passe perdu ?</h2>';
		$html .= 'Bonjour '.$cname.' ('.$name.')<br><br>Merci de bien vouloir changer le mot de passe de votre compte en cliquant sur ce lien :<br><a href="http://swappy.fr/beta/inscription.php?remind='.$hash.'" target="_blank">http://swappy.fr/beta/inscription.php?remind='.$hash.'</a><br><br><i>Si vous ne pouvez pas cliquer sur ce lien, copiez le et collez-le lien dans votre navigateur.</i></p><br><br><br>Ce mail à été envoyer automatiquement, veuillez ne pas y répondre.<br><br>Swappy.fr';
		$html .= "</body></html>";
		
		if($this->sendmail($to, $this->noreply, "Swappy.fr", $subject, $html)) {
			return array(true);
		} else {
			return array(false, "Une erreur à eu lieu lors de l'envoie du mail... Veuillez réessayer plus tard.");
		}
	}
	function send_validation($email, $name, $hash) {
		$to = $email;
		$subject = 'Inscription sur Swappy.fr';
		
		$message = '<html><body>';
		$message .= '<h2>Bienvenue dans la communauté swappy !</h2>';
		$message .= '<p>Merci '.$name.' pour votre inscription sur notre site.<br><br>Merci de bien vouloir activer votre compte en cliquant sur ce lien :<br><a href="http://swappy.fr/beta/inscription.php?validation='.$hash.'" target="_blank">http://swappy.fr/beta/inscription.php?validation='.$hash.'</a><br><br><i>Si vous ne pouvez pas cliquer sur ce lien, copiez le et collez-le lien dans votre navigateur.</i></p><br><br><br>Ce mail à été envoyer automatiquement, veuillez ne pas y répondre.<br><br>Swappy.fr';
		$message .= "</body></html>";
		
		if($this->sendmail($to, $this->noreply, "Swappy.fr", $subject, $message)) {
			return true;
		} else {
			return false;
		}
	}
	function send_contact_form($POST) {
		$to = $this->contact;
		$subject = 'Message de Swappy';
		
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
		
		if($this->sendmail($to, strip_tags($POST['email']), strip_tags($POST['name'])." ".strip_tags($POST['lastname']), $subject, $message)) {
			return array(true);
		} else {
			return array(false, "Une erreur d'envoie à eu lieu");
		}
	}
}

?>