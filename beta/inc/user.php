<?php 
	function encode_mail($mail, $n) {
		$r = array("ASC" => "", "UTF" => "");
		for($i=0;$i<strlen($mail);$i++) {
			$r['ASC'] .= "&#".ord($mail[$i]).";";
			$r['UTF'] .= "%".dechex(ord($mail[$i]));	
		}
		return $r[$n];
	}
	//CITY CLASS
	class city {
		private $mysql;
		function __construct($mysql) {
			$this->mysql = $mysql;
		}
		function wd_remove_accents($str, $charset='utf-8')
		{
			$str = htmlentities($str, ENT_NOQUOTES, $charset);
			$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
			$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
			$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
			return $str;
		}
		function getCity($zip) {
			$result = "";
			$select = $this->mysql->prepare("SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = :zip LIMIT 0, 1");	
			$select->execute(array(":zip" => $zip));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if(isset($data->Real_Name) && !empty($data->Real_Name)) {
				$result = $data->Real_Name;
			}
			return $result;
		}
		function getPositionDB($zip, $name="") {
			$name_s = "";
			$replace = array(":zipcode" => $zip);
			if(!empty($name)) {
				$name_s = " AND `Real_Name` = :name";	
				$replace[":name"] = $name;
			}
			$result = array("lat" => false, "lon" => false);
			$select = $this->mysql->prepare("SELECT `Lon`, `Lat` FROM `french_city` WHERE `ZipCode` = :zipcode".$name_s." LIMIT 0, 1");	
			$select->execute($replace);
			$data = $select->fetch(PDO::FETCH_OBJ);
			if(isset($data->Lon)) {
				$result['lat'] = $data->Lat;
				$result['lon'] = $data->Lon;
			}
			return $result;
		}
		function getLocationByName($name) {
			$replace = array();
			$city = preg_replace("/\|| /", "-", $name);
			$result = array("name" => false, "ID" => false, "lat" => false, "lon" => false, "zipcode" => false);
			$where = $order = "";
			$l = explode("|", $name);
				for($i=0;$i<count($l);$i++) {
					$prpn = ":value".$i;
					$w = $this->wd_remove_accents($l[$i]);
					if(strlen($w)  > 2 && !is_numeric($w)) {
						//NORMAL
						$replace[$prpn] = $w;
						//FIRST CASE
						$replace[$prpn."f"] = "^".$w;
						//FIRST AND LAST
						$replace[$prpn."l"] = "^".$w."$";
						$where .= ' OR (UPPER(`Name`) REGEXP '.$prpn.')';
						$order .= ' + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.' THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.'f THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.'l THEN 1.3 ELSE 0 END)';
					} else if(is_numeric($w)) { //DEPARTEMENT FIX
						//NORMAL
						$replace[$prpn] = $w;
						//LAST CASE
						$replace[$prpn."l"] = $w."$";
						$where .= ' OR (UPPER(`ZipCode`) REGEXP '.$prpn.')';
						$order .= ' + (CASE WHEN UPPER(`ZipCode`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`ZipCode`) REGEXP '.$prpn.'l THEN 0.5 ELSE 0 END)';
					}
				}
				$where .= '';
				if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
				if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
				$query = "SELECT `Real_Name`, `Name`, `ID`, `Lat`, `Lon`, `ZipCode` FROM `french_city` WHERE ".$where." GROUP BY `ID` ORDER BY ".$order." DESC, `ZipCode` ASC LIMIT 0, 1";
				$select = $this->mysql->prepare($query);
				$select->execute($replace);
				$data = $select->fetch(PDO::FETCH_OBJ);
				$total = $select->rowCount();
				if($total > 0) {
					similar_text(strtoupper($this->wd_remove_accents(preg_replace("/\|/", "-", $city))), strtoupper($this->wd_remove_accents($data->Name)), $percent);	
					$length = strlen($data->Name);
					//MATCH VERIFICATION + WITH NUMBERS LETTERS
					if($percent > 65 && strlen($city) <= ($length + 4) && strlen($city) >= ($length - 4)) {
						$result = array("Name" => $data->Real_Name, "ID" => $data->ID, "Lat" => $data->Lat, "Lon" => $data->Lon, "ZipCode" => $data->ZipCode);
					}
				}
			return $result;	
		}
		function getPosition($adresse, $zip, $city="") {
			//Initiation des variables de sortie
			$coords['lat'] = $coords['lon'] = '';
			if($city == "") {
				$city_ = trim($zip." ".$this->getCity($zip));
			} else {
				$city_ = trim($zip." ".$city);
			}
			$addr = $city_;
			if(!empty($adresse)) {
				//Si on a une adresse complete on va utiliser GOOGLE API pour une meilleur localisation
				$addr = $adresse." ".$city_;
				
				$url='http://maps.googleapis.com/maps/api/geocode/xml?region=FR&address='.$addr.', France&sensor=false';
				$xml = @simplexml_load_file($url);
				$coords['status'] = @$xml->status;
				//On verifie que GOOGlE a bien trouver quelque chose ou si le quota de recherche est depasser...
				if($coords['status'] == 'OK') {	
				
					//On verifie que GOOGLE a bien trouver une rue "route"
					if($xml->result->address_component[1]->type == "route") {
						
						//On verifie la similarité entre le resultat et la recherche
						$result_street = $xml->result->address_component[0]->long_name." ".$xml->result->address_component[1]->long_name." ".$xml->result->address_component[6]->long_name." ".$xml->result->address_component[2]->long_name;
						similar_text(strtoupper($this->wd_remove_accents($result_street)), strtoupper($this->wd_remove_accents($adresse)), $percent);
						if(number_format($percent, 0) < 42) {
							//Si la similitude des adresse est inférieur à 35% par sécurité on va juste ce basé sur le code postal
							$coords = $this->getPosition("", $zip, $city);
						} else {
							//Si c'est OK on renvoie les informations
							$coords['lat'] = $xml->result->geometry->location->lat;
							$coords['lon'] = $xml->result->geometry->location->lng;
						}
					} else {
						//Si le resultat ne signale pas avoir trouver une rue, on va utiliser la BDD
						$coords = $this->getPositionDB($zip, $city);
					}
				} else {
					// Si GOOGLE n'a rien trouvé, on va utiliser la BDD
					$coords = $this->getPositionDB($zip, $city);
				}
			} else {
				//Si on a pas une adresse complete on va utiliser la BDD
				$coords = $this->getPositionDB($zip, $city);
			}
			//On renvoie
			return $coords;
		}
	}
	//USER CLASS
	class user {
		public $ID, 
			$cryptID,
			$login, 
			$lastname, 
			$firstname, 
			$email, 
			$phone, 
			$avatar, 
			$zipcode, 
			$street, 
			$street2,
			$mailoption, 
			$admin, 
			$gender, 
			$birthdate, 
			$city, 
			$lat,
			$logged, 
			$age,
			$description,
			$tags,
			$globalnote,
			$globalvote,
		$lon;
		public $chat_;
		private $mysql, 
			$cookies,
			$password;
		function __construct($mysql, $id="") {
			$this->mysql = $mysql;
			if(empty($id)) {
				$this->find_sess();
			} else {
				$this->load_user_data($id, $this->crypt_sess($id), false);
			}
			$this->auto_();
		}
		function auto_() {
			//RDV
			$select = @$this->mysql->prepare("SELECT * FROM `appointment` WHERE `Date` <= '".date("Y-m-d H:i:s", strtotime("-1 hour"))."' AND `State` = '1' LIMIT 0, 8");
			@$select->execute();
			$r = @$select->rowCount();
			if(!is_numeric($r)) { $r = 0; }
			if($r > 0) {
				if(file_exists("inc/chat.php")) {
				@require_once("inc/chat.php");	
				} else if(file_exists("chat.php")) {
				@require_once("chat.php");	
				} else if(file_exists("../inc/chat.php")) {
				@require_once("../inc/chat.php");
				}
				$chat = new chat($this->mysql, $this);
			}
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$other = $data->User;
				if($other == $this->ID) {
					$other = $data->Owner_Service;
				}
				$cc = $chat->isset_conversation($other, $data->Service);
				if($cc != false) {
					$this->mysql->query("UPDATE `appointment` SET `State` = '2' WHERE `ID` = '".$data->ID."'");
					$mess = 'La date du rendez-vous est passé. Confirmez-vous que votre rendez-vous à eu lieu ?<br><i><a data-id="'.$data->ID.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$data->ID.'" class="refuse-this-date">Non</a>';
					$chat->send_reply($mess, $cc, $data->User);
					$this->mysql->query("UPDATE `conversation` SET `Status` = '2' WHERE `ID` = '".$cc."'");
				}
			}
			//USER NON ACTIVE DEPUIS 4 MOIS
			$select = @$this->mysql->prepare("DELETE FROM `users` WHERE `Created` <= '".date("Y-m-d H:i:s", strtotime("-4 month"))."' AND `Validation` = '0' ORDER BY `Created` ASC LIMIT 20");
			@$select->execute();
			//RENDEZ-VOUS OUBLIE
			$select = @$this->mysql->prepare("SELECT * FROM `appointment` WHERE `Date` <= '".date("Y-m-d H:i:s", strtotime("-2 month"))."' AND `State` != '5' LIMIT 0, 8");
			@$select->execute();
			$r = @$select->rowCount();
			if(!is_numeric($r)) { $r = 0; }
			if($r > 0) {
				if(file_exists("inc/chat.php")) {
				@require_once("inc/chat.php");	
				} else if(file_exists("chat.php")) {
				@require_once("chat.php");	
				} else if(file_exists("../inc/chat.php")) {
				@require_once("../inc/chat.php");
				}
				$chat = new chat($this->mysql, $this);
			}
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$other = $data->User;
				if($other == $this->ID) {
					$other = $data->Owner_Service;
				}
				$cc = $chat->isset_conversation($other, $data->Service);
				if($cc != false) {
					$this->mysql->query("DELETE `appointment` WHERE `ID` = '".$data->ID."'");
					$mess = 'Suite a aucune réponse sur ce rendez-vous depuis 2mois... Nous l\'annulons.';
					$chat->send_reply($mess, $cc, $data->User);
					$chat->send_reply($mess, $cc, $data->Owner_Service);
					$this->mysql->query("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = '".$cc."'");
				}
			}
		}
		function getAge($date) {
  			return (int) ((time() - strtotime($date)) / 3600 / 24 / 365);
		}
		function getglnote($id) {
			$select = $this->mysql->query("SELECT SUM(`Note`) AS `total`, COUNT(*) AS `nb` FROM `notations` WHERE `Owner_Service` = '".$id."'");
			$data = $select->fetch(PDO::FETCH_OBJ);
			$total = @round($data->total/$data->nb);
			return array($total, $data->nb);
		}
		function list_com($for="",$limit=true) {
			if($limit != true) {
				$lim =	' LIMIT 0, 7';
			} else {
				$lim = '';
			}
			if($for == "") {
				$ad = " ORDER BY `notations`.`Date` DESC".$lim;	
			} else {
				$ad = " AND `Service` = '".$for."' ORDER BY `notations`.`Date` DESC".$lim;
			}
			$html = '';
			$select = $this->mysql->query("SELECT `users`.`ID` AS `UserID`, `users`.`LastName`, `users`.`FirstName`, `services`.`ID`, `notations`.`Date`, `services`.`Title`, `type`.`Name`, `notations`.`Message`, `notations`.`Note` FROM `notations` INNER JOIN `users` ON `notations`.`By` = `users`.`ID` INNER JOIN `services` ON `notations`.`Service` = `services`.`ID` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` WHERE `Owner_Service` = '".$this->ID."'".$ad);
			$i = 0;
			$class = "col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1";
			if($limit == false) {
				$class = "";
			}
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				if($i<6) {
				$title = $com = "";
				$title = $data->Title;
				$com = $data->Message;
				if($com == "") {
					$com = "Pas de commentaire...";
				} 
				if($title == "") {
					$title = $data->Name;
				}
				$html .= '<div class="'.$class.' note">';
				$html .= '<span class="title">Critique de <a href="profil.php?id='.$data->UserID.'">'.ucfirst($data->FirstName).' '.ucfirst($data->LastName).'</a> ';
				if($for == "") {
					$html .= 'pour <a href="annonce.php?id='.$data->ID.'">'.$title.'</a>';
				}
				$html .= '</span>';
				$c = 20 * $data->Note;
				$html .= '<br><div title="'.$data->Note.' étoile(s)" class="star-rating rating-xs rating-active"><div class="rating-container rating-gly-star" data-content=""><div class="rating-stars" data-content="" style="width: '.$c.'%;"></div><input data-step="1" data-max="5" data-min="0" class="rating form-control hide" id="input-1"></div></div>';
				$html .= '<p>'.ucfirst($com).'</p>';
				$html .= '<i>le '.date("d/m/Y \à H:i", strtotime($data->Date)).'</i>';
				$html .= '<div class="clear"></div></div>';
				} else {
					break;	
				}
				$i++;
			}
			if($html == "") {
				$html = "<div class='col-xs-12 black-text text-center'>Pas de notes & commentaires...</div>";
			} else {
			if($select->rowCount() == 7 && $limit == true) {
				$html .= '<center><a class="open-all-com col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">Voir tous les commentaires</a></center>';
			}
			}
			return $html;
		}
		function onlyUsers() {
			if(!$this->logged && !isset($_GET['logout'])) {
				header("Location: index.php?unlogged&p=".basename($_SERVER['PHP_SELF'])."");	
			} else if (!$this->logged && isset($_GET['logout'])) {
				header("Location: index.php");	
			}
		}
		function onlyVisitors() {
			if($this->logged) {
				if(preg_match("/inscription\.php/", $_SERVER['PHP_SELF']) || isset($_GET['logout'])) {
					header("Location: index.php");	
				} else {
					header("Location: index.php?needunlogged");	
				}
			}
		}
		function onlyAdmin() {
			$this->onlyUsers();
			if($this->admin == 0) {
				header("Location: index.php?noadmin");	
			}
		}
		function modal_location_c($GET) {
			$title = "";
			$text = "";
			$extra = '';
			if(isset($_GET['needunlogged'])) {
				$title = "Se deconnecter pour y accéder";
				$text = "Désolé, mais la page à laquelle vous souhaitiez accéder n'est pas accessible en tant qu'utilisateur connecté.<br><br>Veuillez vous déconnecté pour l'afficher.";
			}
			if(isset($_GET['unlogged'])) {
				$title = "Se connecter pour y accéder";
				$text = "Désolé, mais la page à laquelle vous souhaitiez accéder n'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l'afficher.<div id='clone_login'></div>";
				$extra = " $('#login_section').clone(true).appendTo('#clone_login');
			$('#remind_section').clone(true).appendTo('#clone_login');
			$(\"#clone_login .login_form\").append(\"<input type='hidden' name='to_url' value='".@basename(@$_GET['p'])."'>\");
			$('#clone_login').append('<a href=\"inscription.php\" class=\"hidden_ notsigned\">Pas encore inscrit ?</a><div class=\"clear\"></div>');";
			}
			if(isset($_GET['noadmin'])) {
				$title = "Être administrateur pour y accéder";
				$text = "Désolé, mais la page à laquelle vous souhaitiez accéder est accessible uniquement pour les administrateurs du site.";
			}
			$html = '<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Page inaccessible</h4>
      </div>
      <div class="modal-body">
      '.$text.'
	  </div>
    </div>
  </div>
</div> <script>$(\'#modal_alert\').modal(\'show\');'.$extra.' $("#modal_alert").on("hidden.bs.modal", function(e) {
				$(this).remove();
			});</script>';
			if(!empty($text)) {
				echo $html;
			}
		}
		function list_messages() {
			$t = 0;
			$select = $this->mysql->prepare("SELECT `conversation_reply`.`ID` FROM `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` WHERE (`conversation`.`User_One` = :id OR `conversation`.`User_Two` = :id) AND `conversation_reply`.`Author` != :id AND `conversation_reply`.`Seen` = '0' AND (`conversation_reply`.`BotTo` = '0' OR `conversation_reply`.`BotTo` = '".$this->ID."')");	
			$select->execute(array(":id" => $this->ID));
			$t = $select->rowCount();
			return $t;
		}
		function update($variable, $field, $value) {
			$select = $this->mysql-prepare("UPDATE FROM `users` SET `".$field."` = :value WHERE `ID` = :ID ");
			$select->execute(array(":value" => $value, ":id" => $this->ID));
			$this->{"".$variable.""} = $value;
			return array(true);
		}
		function crypt_sess($ID) {
			$step = base64_encode($ID."__SWAP");
			$total = strlen($step);
			$hs = round($total/4);
			$firstpart = substr($step, 0, $hs);
			$secondpart = substr($step, $hs, $hs);
			$thirdpart = substr($step, $hs*2, $hs);
			$fourthpart = substr($step, $hs*3, $hs);
			$mixed = $thirdpart.$secondpart.$fourthpart.$firstpart;
			$mixed = preg_replace("/\=/", "_", $mixed);
			$output = base64_encode($mixed);
			return $output;
		}
		function uncrypt_sess($sess) {
			$step = base64_decode($sess);
			$step = preg_replace("/\_/", "=", $step);
			$total = strlen($step);
			$hs = round($total/4);
			$firstpart = substr($step, 0, $hs);
			$secondpart = substr($step, $hs, $hs);
			$thirdpart = substr($step, $hs*2, $hs);
			$fourthpart = substr($step, $hs*3, $hs);
			$mixed = $fourthpart.$secondpart.$firstpart.$thirdpart;
			$output = base64_decode($mixed);
			$output = preg_replace("/\_\_SWAP/", "", $output);
			return $output;
		}
		function crypt_remind($mail, $ID, $pass) {
			$step = base64_encode($ID."__SWAP".$mail."__SWAP".$pass);
			$total = strlen($step);
			$hs = round($total/4);
			$firstpart = substr($step, 0, $hs);
			$secondpart = substr($step, $hs, $hs);
			$thirdpart = substr($step, $hs*2, $hs);
			$fourthpart = substr($step, $hs*3, $hs);
			$mixed = $thirdpart.$secondpart.$fourthpart.$firstpart;
			$mixed = preg_replace("/\=/", "_", $mixed);
			$output = base64_encode($mixed);
			return $output;
		}
		function uncrypt_remind($sess) {
			$step = base64_decode($sess);
			$step = preg_replace("/\_/", "=", $step);
			$total = strlen($step);
			$hs = round($total/4);
			$firstpart = substr($step, 0, $hs);
			$secondpart = substr($step, $hs, $hs);
			$thirdpart = substr($step, $hs*2, $hs);
			$fourthpart = substr($step, $hs*3, $hs);
			$mixed = $fourthpart.$secondpart.$firstpart.$thirdpart;
			$output = base64_decode($mixed);
			$output = explode("__SWAP", $output);
			return $output;
		}
		function find_sess() {
			if(isset($_COOKIE['user_swappy']) && !empty($_COOKIE['user_swappy'])) {
				$this->load_user_data($this->uncrypt_sess($_COOKIE['user_swappy']), $_COOKIE['user_swappy']);
			} else if(isset($_SESSION['user_swappy']) && !empty($_SESSION['user_swappy'])) {
				$this->load_user_data($this->uncrypt_sess($_SESSION['user_swappy']), $_SESSION['user_swappy']);
			} else {
				$this->logged = false;	
			}
		}
		function logout() {
			setcookie("user_swappy", "", time() - (60*60*60), "/");
			$_SESSION['user_swappy'] = "";
			unset($_COOKIE['user_swappy']);
			unset($_SESSION['user_swappy']);
			$this->unload_user_data();
		}
		function validate_account($hash) {
			$html = "";
			$hash = base64_decode(trim($hash));
			$split = explode("-==-", $hash);
			$select = $this->mysql->prepare("SELECT `Validation`, `Login`, COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
			$select->execute(array(":email" => base64_decode($split[1])));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				if(md5($data->Login) == $split[0]) {
				 	if($data->Validation == 0) {
						$select = $this->mysql->prepare("UPDATE `users` SET `Validation` = '1' WHERE `Email` = :email");
						if(!$select->execute(array(":email" => base64_decode($split[1])))) {
							$html = 'Désolé, une erreur à eu lieu au moment de l\'activation.<br> Veuillez réessayer plus tard...';
						} else {
							$html = 'Vous êtes bien enregistré comme nouvel utilisateur de Swappy. À vous d\'échanger !';
						}
					} else {
						$html = 'Votre compte à déjà été activé !';	
					}
				} else {
					$html = 'Désolé, il semblerait que le lien soit incorrecte.<br> Veuillez vous inscrire à nouveau.';	
				}
			} else {
				$html = 'Désolé, il semblerait que le lien soit incorrecte.<br> Veuillez vous inscrire à nouveau.';	
			}
			return $html;
			
		}
		function flogin($POST) {
			$arr = array();
			$select = $this->mysql->prepare("SELECT `ID`,`Password`,`Validation` FROM `users` WHERE `Login` = :login");
			$select->execute(array(":login" =>  strtolower($POST['login_form_'])));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$total = $select->rowCount();
			if($total > 0) {
				if($data->Password != md5($POST['password_form'])) {
					$arr = array(false, "Mauvais mot de passe");
				} else if($data->Validation == 0)  {
					//NOT VALIDATED
					$arr = array(false, "Ce compte n'a pas été validé via votre boite mail.");
				} else {
					//CONNECTED
					if(isset($POST['remember_me'])) {
						setcookie("user_swappy", $this->crypt_sess($data->ID), time() + (60*60*60), "/");
					} else {
						$_SESSION['user_swappy'] = $this->crypt_sess($data->ID);
					}
					$arr = array(true);
				}
			} else {
				$arr = array(false, "Mauvais login");	
			}
			return $arr;
		}
		function tags_uncrypt($tags) {
			$html = "";
			$l = preg_split('/\,/', $tags);	
			for($i=0;$i<count($l);$i++) {
				$c = trim($l[$i]);
				if(!empty($c)) {
					$html .= "<span class='tag label label-info'>".ucfirst(strtolower($c))."</span>";
				}
			}
			if($html == "" && !empty($tags)) {
				$html = "<span class='tag label label-info'>".ucfirst(strtolower($tags))."</span>";
			}
			return $html;
		}
		function load_user_data($ID, $crypt, $me=true) {			
			$select = $this->mysql->prepare("SELECT *, COUNT(*) AS `exist` FROM `users` WHERE `ID` = :ID");
			$select->execute(array(":ID" => $ID));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->exist < 1) {
				//WRONG
				if($me == true) {
					$this->logout();
				} else {
					if(!preg_match("/inc\//", $_SERVER['PHP_SELF'])) {
						header("Location: 404.php");
					}
				}
			} else {
				//OK	
				$this->ID = $ID;
				$this->age = $this->getAge($data->Birthdate);
				$this->cryptID = $crypt;
				$this->admin = $data->Admin;
				$this->password = $data->Password;
				$this->avatar = $data->Avatar;
				$this->login = $data->Login;
				$this->email = $data->Email;
				$this->firstname = $data->FirstName;
				$this->lastname = $data->LastName;
				$this->phone = $data->Phone;
				$this->street = $data->Street;
				$this->city = $data->City;
				$this->zipcode = $data->ZipCode;
				$this->mailoption = $data->MailOption;
				$this->gender = $data->Gender;
				$this->birthdate = $data->Birthdate;
				$this->lon = $data->Lon;
				$this->lat = $data->Lat;
				$this->tags = $data->Tags;
				$this->description = $data->Desc;
				$vote = $this->getglnote($ID);
				$this->globalnote = $vote[0];
				$this->globalvote = $vote[1];
				if($me == true) {
					$this->logged = true;
				} else {
					$this->logged = false;
				}
			}
		}
		function unload_user_data() {
			$this->ID = false;
			$this->age = false;
			$this->password = false;
			$this->cryptID = false;
			$this->admin = false;
			$this->avatar = false;
			$this->login = false;
			$this->email = false;
			$this->firstname = false;
			$this->lastname = false;
			$this->phone = false;
			$this->street = false;
			$this->city = false;
			$this->zipcode = false;
			$this->mailoption = false;
			$this->gender = false;
			$this->birthdate = false;
			$this->lon = false;
			$this->lat = false;
			$this->logged = false;
			$this->description = false;
			$this->tags = false;
			$this->globalnote = false;
			$this->globalvote = false;
		}
		function edit_user($POST) {
			$avatar = $this->avatar;
			$return = array(false);
			$allow = 1;
			$set = "";
			$replace = array();
			if(trim($POST['nom']) != $this->lastname) {
				$set .= ' `LastName` = :nom,';
				$replace[':nom'] = trim($POST['nom']);
			}
			if(trim($POST['prenom']) != $this->firstname) {
				$set .= ' `FirstName` = :prenom,';
				$replace[':prenom'] = trim($POST['prenom']);
			}
			if(isset($_POST['mail'])) {
				$mailo = trim($_POST['mail']);
				if($mailo == "on") { $mailo = 1; } else { $mailo = 0; }
			} else {
				$mailo = 0;
			}
			if($mailo != $this->mailoption) {
				$set .= ' `MailOption` = :mailopt,';
				$replace[':mailopt'] = trim($mailo);
			}
			if(trim($POST['gender']) != $this->gender) {
				$set .= ' `Gender` = :gender,';
				$replace[':gender'] = trim($POST['gender']);
				if(preg_match('/\/(user\/M|user\/F)\.jpg/', $avatar)) {
					$avatar = "img/user/".trim($POST['gender']).".jpg";
					$set .= ' `Avatar` = :avatar,';
					$replace[':avatar'] = $avatar;
				}
			}
			$birth = $POST['year']."-".$POST['month']."-".$POST['day'];
			if($birth != $this->birthdate) {
				$set .= ' `Birthdate` = :birth,';
				$replace[':birth'] = $birth;
			}
			if(trim($POST['street']) != $this->street || trim($POST['zipcode']) != $this->zipcode) {				
				$city = new city($this->mysql);
				$c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
				$set .= ' `Street` = :street,';
				$replace[':street'] = trim($POST['street']);
				$set .= ' `ZipCode` = :zipcode,';
				$replace[':zipcode'] = trim($POST['zipcode']);
				$set .= ' `City` = :city,';
				$replace[':city'] = trim($POST['cityname']);
				$set .= ' `Lat` = :lat, `Lon` = :lon,';
				$replace[':lat'] = $c['lat'];
				$replace[':lon'] = $c['lon'];
			}
			if(trim($POST['tags']) != $this->tags) {
				$set .= ' `Tags` = :tags,';
				$replace[':tags'] = trim($POST['tags']);
			}
			if(trim($POST['description']) != $this->description) {
				$set .= ' `Desc` = :desc,';
				$replace[':desc'] = trim($POST['description']);
			}
			if(trim($POST['phone']) != $this->phone) {
				$set .= ' `Phone` = :phone,';
				$replace[':phone'] = trim($POST['phone']);
			}
			$mdp = trim($POST['mdp']);
			$rmdp = trim($POST['r_mdp']);
			$mail_ = trim(strtolower($POST['email']));
			if($mail_ != $this->email || (!empty($mdp) && !empty($rmdp))) {			
				$amdp = trim($POST['a_mdp']);
				if(empty($amdp)) {
					$allow = 0;
					$return = array(false, "Pour changer email et/ou mot de passe, vous devez entrer votre mot de passe actuel !", "a_mdp");
				} else if(md5($amdp) != $this->password) {
					$allow = 0;
					$return = array(false, "Mauvais mot de passe", "a_mdp");
				}
				if($allow == 1 && $mail_ != $this->email) {
					$mm = $this->issetEmail($mail_, "");
					if($mm[1] == true) {
						$allow = 0;
						$return = array(false, "Cette adresse email est déjà utilisée !", "email");
					} else {
						$set .= ' `Email` = :email,';
						$replace[':email'] = $mail_;
					}
				}
				if($allow == 1 && (!empty($mdp) && !empty($rmdp))) {
					if($mdp == $rmdp) {
						$set .= ' `Password` = :password,';
						$replace[':password'] = md5($mdp);
					} else {
						$allow = 0;
						$return = array(false, "Les mots de passe ne sont pas identique", "mdp");
					}
				}
			}
			if($allow == 1) {
				if(!empty($set)) {
					$q = substr($set, 0, strlen($set)-1);
					$select = $this->mysql->prepare("UPDATE `users` SET".$q." WHERE `ID` = '".$this->ID."'");
					$select->execute($replace);
				}
				$return = array(true, $avatar);
			}
			return $return;
		}
		function listing_badge_s() {
			$list = array();
			$html = "";
			$htm = array();
			$select = $this->mysql->query("SELECT `categories`.`ID` AS `CatID`, `categories`.`Name`, `services`.`ID`, `services`.`Title`, `type`.`Name` AS `TypeName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` WHERE `By` = '".$this->ID."' ORDER BY `categories`.`ID` ASC , `services`.`Created` DESC");
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				if(!in_array($data->CatID, $list)) {
					$list[] = $data->CatID;	
					$html .= '<div title="Voir les annonces pour ce type de service" data-id="'.$data->CatID.'" class="badge_"><img src="img/services/'.$data->CatID.'.jpg" alt="'.$data->Name.'" ></div>';
					$htm[$data->CatID.""] = '';
				}
				$title = $data->Title;
				if($title == "") {
					$title = $data->TypeName;
				}
				$htm[$data->CatID.""] .= '- <a title="Voir ce service" href="annonce.php?id='.$data->ID.'">'.$title.'</a><br>';
			}
			foreach(array_keys($htm) as $key){
    			$html .= '<div class="listing-s" data-s="'.$key.'">'.$htm[$key].'</div>';
			}
			if($html == "") {
				$html = '<div class="black-text text-center nobadge">Cet utilisateur ne propose pas de service...</div>';	
			}
			return $html;
		}
		function add_user($POST) {
			//prevenir le bug de Validation engine
			$arr = array(false);
			if(empty($POST['cityname']) || empty($POST['zipcode']) || $this->issetEmail(strtolower($POST['email']), false) == false || $this->issetLogin(strtolower($POST['login']), false) == false) {
				$arr = array(false);
			} else {
				$street = $POST['street'];
				$birthdate = $POST['year']."-".$POST['month']."-".$POST['day'];
				//Creation de la position Lat/Lon
				$city = new city($this->mysql);
				$c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
				$select = $this->mysql->prepare("INSERT INTO `users` (`ID`, `Login`, `Password`, `Email`, `Created`, `Avatar`, `LastName`, `FirstName`, `Gender`, `Birthdate`, `Street`, `ZipCode`, `City`, `Lat`, `Lon`, `Phone`, `Desc`, `Tags`, `Admin`, `MailOption`, `Validation`) VALUES (NULL, :login, :password, :email, CURRENT_TIMESTAMP, :avatar, :lastname, :firstname, :gender, :birthdate, :street, :zipcode, :city, :lat, :lon, :phone, '', '', '0', '0', '0');");
				$replace = array(":login" => strtolower($POST['login']),
					":password" => md5($POST['password']), 
					":email" => strtolower($POST['email']), 
					":lastname" => ucfirst(strtolower($POST['lastname'])), 
					":firstname" => ucfirst(strtolower($POST['firstname'])), 
					":gender" => $POST['gender'],
					":birthdate" => $birthdate,
					":street" => $street,
					":zipcode" => $POST['zipcode'],
					":city" => $POST['cityname'],
					":lat" => $c['lat'],
					":lon" => $c['lon'],
					":phone" => $POST['phone'],
					":avatar" => "img/user/".strtoupper($POST['gender']).".jpg"
				);
				if(!$select->execute($replace)) {
					$arr = array(false);
				} else {
					//SEND MAIL VALIDATION
					$mail = new mailer();
					$hash = $this->make_link_validation($replace[':email'], $replace[':login']);
					$mail->send_validation($replace[':email'], $replace[':login'], $replace[':firstname']." ".$replace[':lastname'], $hash);
					$arr = array(true);
				}
			}
			return $arr;
			} 
			function make_link_validation($email, $login) {
				$email = strtolower($email);
				$login = strtolower($login);
				$c_login = md5($login);
				$c_email = base64_encode($email);
				$hash = base64_encode($c_login."-==-".$c_email);
				return $hash;
			}
			function issetLogin($login, $id) {
				$arr = array (false, false);
				$select = $this->mysql->prepare("SELECT COUNT(*) AS `total` FROM `users` WHERE `Login` = :login");
				$select->execute(array(":login" => $login));
				$data = $select->fetch(PDO::FETCH_OBJ);
				if($id == false) {
					if($data->total > 0) {
						$arr = false;
					} else {
						$arr = true;	
					}
				} else {
					if($data->total > 0) {
						$arr = array($id, false);
					} else {
						$arr = array($id, true);	
					}
				}
				return $arr;
		}
		function issetEmail($email, $id) {
			$select = $this->mysql->prepare("SELECT COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
			$select->execute(array(":email" => $email));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($id == false) {
				if($data->total > 0) {
					$arr = false;
				} else {
					$arr = true;	
				}	

			} else {
				if($data->total > 0) {
					$arr = array($id, false);
				} else {
					$arr = array($id, true);	
				}	
			}
			return $arr;
		}
		function issetZipCode($zipcode, $id) {
			$arr = array($id, true, array());
			$select = $this->mysql->prepare("SELECT `ID`,`Real_Name` FROM `french_city` WHERE `ZipCode` = :zipcode");
			$select->execute(array(":zipcode" => $zipcode));
			$total = $select->rowCount();
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				if($total == 1) {
					$arr = array($id, true, $data->Real_Name);
				} else if($total > 1) {
					array_push($arr[2], $data->Real_Name);
				}
			}
			if($total == 0) {
				$arr = array($id, false, "Ville inconnu");
			}
			return $arr;
		}
		function navbar() {
			$html = '';
			if(!$this->logged) {
				if(preg_match("/inscription\.php/", $_SERVER['PHP_SELF'])) {
					$html .= '<li class="active"><a href="inscription.php">Inscription <span class="sr-only">(current)</span></a></li>';
				} else {
                	$html .= '<li><a href="inscription.php">Inscription</a></li>';
				}
             } 
             if(!$this->logged) {
				$html .= '<li class="dropdown">';
             	$html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Connexion <span class="caret"></span></a>
                        <div class="dropdown-menu login-menu">
                            <div id="login_section">
                                <form action="inc/login.php" method="post" class="login_form">
									<span class="hidden_">Se connecter :</span>
                                    <input id="login_form_" name="login_form_" class="validate[required,minSize[5]] form-control" placeholder="Identifiant" type="text" size="30">
                                    <input type="password" id="password_form" name="password_form" placeholder="Mot de passe" class="validate[required,minSize[6]] form-control" size="30">
                                    <label class="string optional" for="remember_me">
                                        <input id="remember_me" type="checkbox" name="remember_me" checked> Se souvenir de moi
                                    </label>
                                    <input class="btn btn-primary" type="submit" name="commit" value="Se connecter">
                                    <div class="remind"><a class="remind_link">Mot de passe perdu ?</a></div>
                                </form>		
                            </div>
                            <div id="remind_section">
                                <form class="remind_form" action="inc/login.php" method="post" accept-charset="UTF-8">
									<span class="hidden_">Mot de passe perdu :</span>
                                    <input id="user_username" placeholder="Email" class="form-control validate[required,email]" type="text" name="remind[email]" size="30">
                                    <input class="btn btn-primary" type="submit" name="commit" value="Recuperer">
                                    <div class="remind"><a class="remind_link">Je m\'en souviens !</a></div><div class="clear"></div>
                                </form>
                            </div>
                        </div>';
                 } else { 
				 	$p_bl = array("profil", "messagerie", "rendez-vous", "proposition");
					$fixed_n = array("false", "", "");
					if(preg_grep("/".preg_replace("/\.php/", "", basename($_SERVER['PHP_SELF']))."/", $p_bl)) {
						if(preg_match('/profil\.php(.*?)id\=(.*?)/', basename($_SERVER['PHP_SELF']).$_SERVER['QUERY_STRING'])) {
							$fixed_n = array("false", "","");
						} else {
							$fixed_n = array("true", " visible"," open");
						}
					}
				 	$html .= '<li class="dropdown hf'.$fixed_n[2].'">';
					 $get = @$_SERVER['QUERY_STRING'];
					 if(empty($get)) {
						$logout = "?logout"; 
					 } else {
						$logout = "?".$get."&logout";	 
					 }
					 $total_m = $this->list_messages();
					 if($total_m > 0) {
						 $message_name = '<span class="mess_count red">'.$total_m.'</span> ';
						 $message_list = '<span class="mess_count red">'.$total_m.'</span>';
					 } else {
						$message_name = '';
						$message_list = '<span class="mess_count">0</span>'; 
					 }
					 $logout = preg_replace("/\&\&/", "&", $logout);
                    $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="'.$fixed_n[0].'"><img src="'.$this->avatar.'" height="40" width="40"> '.$this->firstname.' '.$message_name.'<span class="caret"></span></a>
                            <ul class="dropdown-menu nav-h'.$fixed_n[1].'"><!--
								--><li><a href="profil.php">Mon profil</a></li><!--
								--><li><a href="proposition.php">Mes propositions</a></li><!--
								--><li><a href="rendez-vous.php">Mes rendez-vous</a></li><!--
								--><li><a href="messagerie.php">Messagerie '.$message_list.'</a></li><!--
                                --><li><a href="'.$logout.'">Se deconnecter</a></li><!--
                            --></ul>';
							if($fixed_n[0] == "true") {
								$html = preg_replace('/\<li\>\<a href\=\"'.basename($_SERVER['PHP_SELF']).'/', '<li class="active"><a href="'.basename($_SERVER['PHP_SELF']).'', $html);	
							}
                } 
			$html .= "</li>";
			return $html;
		}
		function remind_mail($POST) {
			$arr = array();
			$mail = strtolower(trim($POST['email']));
			$select = $this->mysql->prepare("SELECT `Validation`, `Login`, `FirstName`, `LastName`, `Password`, `Email`, `ID`, COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
			$select->execute(array(":email" => $mail));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total == 1) {
				if($data->Validation == 1) {
					$mail_ = new mailer();
					$hash = $this->crypt_remind($mail, $data->ID, $data->Password);
					$arr = $mail_->send_remind($hash, $mail, $data->Login, $data->FirstName." ".$data->LastName);
				} else {
					$arr = array(false, "Ce compte n'a pas été validé via votre boite mail.");
				}
			} else {
				$arr = array(false, "Cette adresse email n'est pas relié à un compte.");
			}
			return $arr;
		}
		function remind_account($POST) {
			$arr = array();
			$info = $this->uncrypt_remind(trim($POST['hash']));
			$select = $this->mysql->prepare("UPDATE `users` SET `Password` = :password WHERE `ID` = :ID AND `Email` = :email");
			if($select->execute(array(":password" => md5(trim($POST['password'])), ":ID" => $info[0], ":email" => $info[1]))) {
				$arr = array(true);
			} else {
				$arr = array(false, "Une erreur à eu lieu au moment du changement du mot de passe... Veuillez réessayer.");
			}
			return $arr;	
		}
		function prevent_ex_remind($id, $pass) {
			$select = $this->mysql->query("SELECT `Password` FROM `users` WHERE `ID` = '".$id."'");	
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->Password == $pass) {
				return true;
			} else {
				return false;
			}
		}
		function list_services_edit() {
			$return = array();
			$select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`ID` AS `CatType`, `services`.`Title`, `services`.`Type`, `type`.`Name` AS `TypeName`, `services`.`By`, `services`.`Description`, `services`.`Distance`, `services`.`Disponibility`, `services`.`Created`, `services`.`City`, `services`.`Lat`, `services`.`Lon`, `french_city`.`Real_Name` AS `CityName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` INNER JOIN `users` ON `services`.`By` = `users`.`ID` WHERE `users`.`ID` = :ID ORDER BY `services`.`Created` DESC");
			$select->execute(array(":ID" => $this->ID));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$id = $data->ID;
				if(empty($data->Title)) {
					$title = $data->TypeName;
				} else {
					$title = $data->Title;
				}
				$type = $data->Type;
				$typename = $data->TypeName;
				$catype = $data->CatType;
				$by = $data->By;
				if(empty($data->Description)) {
					$description = "Pas de description...";
				} else {
					$description = $data->Description;
				}
				$image = "img/services/".$catype.".jpg";
				$distance = $data->Distance;
				//$disponibility = $this->dispo_uncrypt_an($data->Disponibility);
				$creation = $data->Created;
				$city = $data->CityName;
				$lat = $data->Lat;
				$lon = $data->Lon;
				$return[] = array("ID" => $id, "Title" => $title, "Image" => $image, "Created" => $creation, "CityName" => $city, "Description" => $description);
			}
			return $return;
		}
		function change_avatar($file) {
			$arr = array(false,"Une erreur a eu lieu...");
			$handle = new Upload($file);

			if ($handle->uploaded) {
				$handle->image_resize            = true;
				$handle->image_x                 = 130;
				$handle->image_y                 = 130;
				$handle->image_ratio_crop      = true;
				$dir_dest = '../img/user/upload/';
				$dir_pics = 'img/user/upload/';
				$handle->Process($dir_dest);
		
				if ($handle->processed) {
					$arr = array(true, ''.$dir_pics.'' . $handle->file_dst_name . '');
					//
					if(!preg_match("/(user\/M|user\/F)\.jpg/", $this->avatar)) {
						unlink("../".$this->avatar);
					}
					$this->mysql->query("UPDATE `users` SET `avatar` = '".$dir_pics.'' . $handle->file_dst_name . "' WHERE `ID` = '".$this->ID."'");
				} else {
					$arr = array(false, 'Une erreur a eu lieu...');
					
				}
			}
			return $arr;
		} 
		function get_name($id) {
			$name = "";
			$select = $this->mysql->prepare("SELECT `FirstName`, `LastName` FROM `users` WHERE `ID`= :id");
			$select->execute(array(":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$name = $data->FirstName." ".$data->LastName;
			return $name;
		}
		function state_m($state,$name,$whoami) {
			$ret = "";
			if($whoami == 0) {
				$b = " votre";
				$c = "";	
			} else {
				$b = "";
				$c = " de ".$name;	
			}
			if($state == "1") {
				$ret = "En attente du rendez-vous.";	
			}
			if($state == "2") {
				$ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";	
			}
			if($state == "3") {
				$ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";	
			}
			if($state == "4") {
				$ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";	
			}
			if($state == "5") {
				$ret = "Rendez-vous terminé et finalisé.";	
			}
			return $ret;
		}
		function list_rdv($past) {
			if($past == true) {
				$sh = "= '1'";	
			} else {
				$sh = "> 1";	
			}
			if(file_exists("inc/chat.php")) {
				@require_once("inc/chat.php");	
				} else if(file_exists("chat.php")) {
				@require_once("chat.php");	
				} else if(file_exists("../inc/chat.php")) {
				@require_once("../inc/chat.php");
				}
				$this->chat_ = new chat($this->mysql, $this);
				$month = date("m", strtotime($date));
			$year = date("Y", strtotime($date));
			$day = date("d", strtotime($date));
			$html = '';
			$select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE (`User` = :id OR `Owner_Service` = :id) AND `State` ".$sh."");
			$select->execute(array( ":id" => $this->ID));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$serv = $this->chat_->service_title($data->Service);
				$other = $data->User; 
				$whoask = 'C\'est vous qui proposez ce service.';
				$who = 1;
				if($other == $this->ID) {
					$other = $data->Owner_Service;
					$whoask = 'Vous avez demandez ce service.';
					$who = 0;
				}
				$ss = $this->mysql->prepare("SELECT `french_city`.`Real_Name` FROM `services` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE `services`.`ID` = '".$data->Service."'");
				$ss->execute();
				$city = $ss->fetch(PDO::FETCH_OBJ);
				$nom = $this->get_name($other);
				$state = $this->state_m($data->State,$nom,$who);
				if($data->State < 5) {
					$conv = '<a href="messagerie.php#select-'.$this->chat_->isset_conversation($other, $data->Service).'">'.$state.'</a>';
				} else {
					$conv = $state;
				}
				$this->state_m($data->Service, $this->ID, $other);
				$html .= '<tr><td ><a href="annonce.php?id='.$data->Service.'">'.$serv.'</a><br><i>'.$whoask.'</i></td><td> avec <a href="profil.php?id='.$other.'">'.$nom.'</a><br><i>'.$conv.'</i></td><td>'.date("d/m/Y \à H:i", strtotime($data->Date)).'</td></tr>';
			}
			if($html == "") {
				$html = "<tr><td class='nordv' colspan='3'><center>Pas de rendez-vous...</center></td></tr>";	
			}
			return $html;
		}
		function list_mod_cal($date) {
			if(file_exists("inc/chat.php")) {
				@require_once("inc/chat.php");	
				} else if(file_exists("chat.php")) {
				@require_once("chat.php");	
				} else if(file_exists("../inc/chat.php")) {
				@require_once("../inc/chat.php");
				}
				$this->chat_ = new chat($this->mysql,$this);
			$month = date("m", strtotime($date));
			$year = date("Y", strtotime($date));
			$day = date("d", strtotime($date));
			$html = '';
			$select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE EXTRACT(MONTH FROM `Date`) = :month AND EXTRACT(DAY FROM `Date`) = :day AND EXTRACT(YEAR FROM `Date`) = :year AND (`User` = :id OR `Owner_Service` = :id) AND `State` > 0");
			$select->execute(array(":month" => $month, ":day" => $day, ":year" => $year, ":id" => $this->ID));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$serv = $this->chat_->service_title($data->Service);
				$other = $data->User; 
				$whoask = 'C\'est vous qui proposez ce service.';
				$who = 1;
				if($other == $this->ID) {
					$other = $data->Owner_Service;
					$whoask = 'Vous avez demandez ce service.';
					$who = 0;
				}
				$nom = $this->get_name($other);
				$state = $this->state_m($data->State,$nom,$who);
				$conv = $this->chat_->isset_conversation($other, $data->Service);
				$this->state_m($data->Service, $this->ID, $other);
				if($html != "") {
					$html .= "<hr>";
				}
				$html .= 'Le '.date("d/m/Y \à H:i", strtotime($data->Date))." avec <a href='profil.php?id=".$other."'>".$nom."</a> pour <a href='annonce.php?id=".$data->Service."'>".$serv."</a><br><i>".$whoask."</i><br><b>Statut :</b> ".$state." - <a href='messagerie.php#select-".$conv."'>Voir la conversation</a>";
			}
			return $html;
		}
		function json_calendar($year, $month) {
			$arr = array();
			if($month<10) {
				$month = "0".$month;	
			}
			
			$select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE EXTRACT(MONTH FROM `Date`) = :month AND EXTRACT(YEAR FROM `Date`) = :year AND (`User` = :id OR `Owner_Service` = :id) AND `State` > 0");
			$select->execute(array(":month" => $month, ":year" => $year, ":id" => $this->ID));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$arr[] = array("date"=> date("Y-m-d", strtotime($data->Date)), "badge" => false, "title" => "Mes rendez-vous pour le ".date("d/m/Y", strtotime($data->Date)), "body"=>$this->list_mod_cal($data->Date));	
			}
			return $arr;
		}
	}  ?>