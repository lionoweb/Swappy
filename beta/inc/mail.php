<?php
ini_set("SMTP", "ssl0.ovh.net"); 
require('class.html2text.inc');
require('swift/swift_required.php');
class mailer {
	public $noreply = "no-reply@swappy.fr";
	public $contact = "contact@swappy.fr";
	private $folderb = "beta/";
	function txt_mail($msg) {
		$txt = "";
		
		return $txt;
	}
	function bodytext($htmli, $norep, $title) {
		$rep = "";
		if($norep == true) {
			$rep = '<br><br><center><span style="font-size:12px;">Ce mail a été envoyé automatiquement, veuillez ne pas y répondre.</span></center>';
		}
		$htmli = preg_replace("/\<a /i", '<a style="color: #218cb9;" ', $htmli);
		$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>'.$title.'</title><style type="text/css"> a { color: #218cb9; } </style></head>
<body style="width:100% !important; background-color:#FFFFFF; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; height:100%;">';
		$html .= '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="font-family: Arial, Helvetica, sans-serif;" align="center"><tr><td align="center"><table border="0" cellspacing="0" cellpadding="0" width="100%" align="center"><tr style="background-color:#df755c;"><td align="left" width="126"><img src="http://swappy.fr/'.$this->folderb.'img/mail/logo.jpg" height="60" width="126"></td><td align="right" width="264"><img src="http://swappy.fr/'.$this->folderb.'img/mail/header.jpg" height="60" width="264"></td></tr>';
		$html .= '<tr style="background-color:#FFFFFF;"><td colspan="2" style="word-break:break-all; word-wrap:break-word;  padding:25px 20px 0px 20px; text-align:left;" align="left">'.$htmli.'<br><br>L\'équipe Swappy.'.$rep.'</td></tr>';
		$html .= '<tr style="background-color:#FFFFFF;"><td style=" padding:5px 0px 0px 0px;" colspan="2" align="center"><hr><img src="http://swappy.fr/'.$this->folderb.'img/mail/follow.jpg" height="26" width="248"></td></tr><tr style="background-color:#FFFFFF; padding:10px 0px 0px 0px;"><td align="center" colspan="2"><a href="https://www.facebook.com/SwappyLaPlateforme" style="color: #218cb9; "><img border="0" src="http://swappy.fr/'.$this->folderb.'img/mail/fb.jpg" height="40" width="55"></a><a href="https://twitter.com/_Swappy" style="color: #218cb9;"><img src="http://swappy.fr/'.$this->folderb.'img/mail/tw.jpg" border="0" height="40" width="55"></a></td></tr>';
		$html .= '</table></td></tr></table></body></html>';
		return $html;
	}
	function sendmail($to, $from, $fromname="Swappy.fr", $subject, $html) {
		if(!preg_match("/\@swappy\.fr/", $from)) {
			$h2t = @new html2text($html);
			// Simply call the get_text() method for the class to convert
			// the HTML to the plain text. Store it into the variable.
			$text = $h2t->get_text();
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
			if(preg_match('/no\-reply/', $from)) {
				$html = $this->bodytext($html, true, $subject);
			} else {
				$html = $this->bodytext($html, false, $subject);
			}
			$h2t = @new html2text($html);
			// Simply call the get_text() method for the class to convert
			// the HTML to the plain text. Store it into the variable.
			$text = $h2t->get_text();
			//$to = "check-auth2@verifier.port25.com";
			$transport = Swift_SmtpTransport::newInstance('ssl0.ovh.net', 465, 'ssl');
			$transport->setAuthMode('login');
  			$transport->setUsername($from);
  			$transport->setPassword('2Dside770');		
			$mailer = Swift_Mailer::newInstance($transport);
			$privateKey = file_get_contents('swappy.fr.pem');
			$domainName = 'swappy.fr';
			$selector = 'swappy';
			$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector); 
			$signer->setHashAlgorithm('rsa-sha256');
			$signer->ignoreHeader('Return-Path');
			$message = Swift_Message::newInstance();
			$message->attachSigner($signer);
			$message->setSubject($subject);
			$message->setReturnPath($from);
			$message->setFrom(array($from => $fromname));
			$message->setSender(array($from => $fromname));
			$message->setTo($to);
			$message->setCharset("utf-8");
			$message->setBody($html, 'text/html');
			$message->addPart($text, 'text/plain');
			if (!$mailer->send($message)) {
				return false;
			} else {
				return true;
			}
		}
	}
	function send_remind($hash, $mail, $name, $cname) {
		
		$to = $mail;
		$subject = 'Changement du mot de passe sur Swappy.fr';
		
		$html .= '';
		$html .= 'Bonjour '.$cname.' ('.$name.')<br><br>Merci de bien vouloir changer le mot de passe de votre compte en cliquant sur ce lien :<br><a href="http://swappy.fr/'.$this->folderb.'inscription.php?remind='.$hash.'" target="_blank">http://swappy.fr/'.$this->folderb.'inscription.php?remind='.$hash.'</a><br><br><i>Si vous ne pouvez pas cliquer sur ce lien, copiez le et collez-le lien dans votre navigateur.</i></p>';
		
		if($this->sendmail($to, $this->noreply, "Swappy.fr", $subject, $html)) {
			return array(true);
		} else {
			return array(false, "Une erreur à eu lieu lors de l'envoie du mail... Veuillez réessayer plus tard.");
		}
	}
	function send_validation($email, $name, $cname, $hash) {
		$to = $email;
		$subject = 'Inscription sur Swappy.fr';
		
		$message = '<h4>Bonjour '.$cname.'</h4>';
		$message .= '<p>Nous vous remercions pour votre inscription et vous souhaitons la bienvenue au sein de la communauté Swappy. Vous pouvez dorénavant échanger des services avec d\'autres utilisateurs qui, comme vous, ont l\'envie de trouver un contact sincère d’entraide.<br><br>Cliquez dès maintenant sur le lien suivant afin de confirmer votre adresse mail ainsi que votre inscription :<br><a href="http://swappy.fr/'.$this->folderb.'inscription.php?validation='.$hash.'" target="_blank">http://swappy.fr/'.$this->folderb.'inscription.php?validation='.$hash.'</a><br><br>Une fois votre compte activé vous pourrez vous connecter sur notre site !<br><br>Identifiant : '.$name.'<br>Mot de passe : *******<br><br><i>Si vous ne pouvez pas cliquer sur ce lien, copiez le et collez-le lien dans votre navigateur.</i></p><br><br>À tout de suite sur Swappy !';
		
		if($this->sendmail($to, $this->noreply, "Swappy.fr", $subject, $message)) {
			return true;
		} else {
			return false;
		}
	}
	function newmessage($email, $date, $idc, $cname, $oname, $oid) {
		$subject = 'Nouveau message à '.date("H:i",$date);
		$message = '<h4>Bonjour {CNAME}</h4>';
		$message .= '<p>Vous avez reçu un nouveau message de la part de <a href="http://swappy.fr/'.$this->folderb.'profil-{OID}.php">{ONAME}</a> le {DATE}<br><br><a href="http://swappy.fr/'.$this->folderb.'messagerie.php#select-{CID}">Cliquez ici pour le consulter</a>.<br><br><i>Vous pouvez à tout moment désactiver les notations par mail de vos messages sur votre <a href="http://swappy.fr/'.$this->folderb.'profil.php">page profil</a>.</i>';
		$first = preg_replace("/\{CNAME\}/", $cname, $message);
		$first = preg_replace("/\{OID\}/", $oid, $first);
		$first = preg_replace("/\{ONAME\}/", $oname, $first);
		$first = preg_replace("/\{DATE\}/", date("d/m/Y \à H:i", $date), $first);
		$first = preg_replace("/\{CID\}/", $idc, $first);
		$this->sendmail($email, $this->noreply, "Swappy.fr", $subject, $first);
	}
	function send_validation_rdv($owner, $user, $servid, $servname, $date, $cc) {
		$to = $email;
		$subject = 'Votre rendez-vous pour le '.date("d/m/Y \à H:i", strtotime($date));
		
		$message = '<h4>Bonjour {CNAME}</h4>';
		$message .= '<p>Votre rendez-vous a été validé auprès de vous et de votre interlocuteur.<br><br><u>Voici donc le récupitulatif :</u><br>Il s\'agit d\'un rendez-vous avec <a href="http://swappy.fr/'.$this->folderb.'profil-{OID}.php">{ONAME}</a>  pour {YOUR} service \'<a href="http://swappy.fr/'.$this->folderb.'annonce-{SERVID}.php">{SERVNAME}</a>\'.<br> Ce rendez-vous est prévu pour le {DATE}.<br><br><i>Vous pouvez toujour annuler ce rendez-vous en cliquant sur lien envoyé dans votre <a href="http://swappy.fr/'.$this->folderb.'messagerie.php#select-{IDCONV}">messagerie</a></i>';
		$first = preg_replace("/\{CNAME\}/", $owner->firstname." ".$owner->lastname, $message);
		$first = preg_replace("/\{OID\}/", $user->ID, $first);
		$first = preg_replace("/\{ONAME\}/", $user->firstname." ".$user->lastname, $first);
		$first = preg_replace("/\{YOUR\}/", "votre", $first);
		$first = preg_replace("/\{SERVID\}/", $servid, $first);
		$first = preg_replace("/\{SERVNAME\}/", $servname, $first);
		$first = preg_replace("/\{DATE\}/", date("d/m/Y \à H:i", strtotime($date)), $first);
		$first = preg_replace("/\{IDCONV\}/", $cc, $first);
		$this->sendmail($owner->email, $this->noreply, "Swappy.fr", $subject, $first);
		
		$first = preg_replace("/\{CNAME\}/", $user->firstname." ".$user->lastname, $message);
		$first = preg_replace("/\{OID\}/", $owner->ID, $first);
		$first = preg_replace("/\{ONAME\}/", $owner->firstname." ".$owner->lastname, $first);
		$first = preg_replace("/\{YOUR\}/", "le", $first);
		$first = preg_replace("/\{SERVID\}/", $servid, $first);
		$first = preg_replace("/\{SERVNAME\}/", $servname, $first);
		$first = preg_replace("/\{DATE\}/", date("d/m/Y \à H:i", strtotime($date)), $first);
		$first = preg_replace("/\{IDCONV\}/", $cc, $first);
		$this->sendmail($user->email, $this->noreply, "Swappy.fr", $subject, $first);
	}
	function send_contact_form($POST) {
		$to = $this->contact;
		$subject = 'Message de Swappy';
		
		$message = '<html><head><title>'.$subject.'</title></head><body>';
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