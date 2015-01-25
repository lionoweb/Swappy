<?php 
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
			$select = mysqli_query($this->mysql, "SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = '".mysqli_escape_string($this->mysql, $zip)."' LIMIT 0, 1");	
			$data = mysqli_fetch_array($select);
			if(isset($data['Real_Name']) && !empty($data['Real_Name'])) {
				$result = $data['Real_Name'];
			}
			return $result;
		}
		function getPositionDB($zip, $name="") {
			$name_s = "";
			if(!empty($name)) {
				$name_s = " AND `Real_Name` = '".mysqli_escape_string($this->mysql, $name)."'";	
			}
			$result = array("lat" => false, "lon" => false);
			$select = mysqli_query($this->mysql, "SELECT `Lon`, `Lat` FROM `french_city` WHERE `ZipCode` = '".mysqli_escape_string($this->mysql, $zip)."'".$name_s." LIMIT 0, 1");	
			$data = mysqli_fetch_array($select);
			if(isset($data['Lon'])) {
				$result['lat'] = $data['Lat'];
				$result['lon'] = $data['Lon'];
			}
			return $result;
		}
		function getLocationByName($name) {
			$city = preg_replace("/\|/", "-", $name);
			$result = array("name" => false, "ID" => false, "lat" => false, "lon" => false, "zipcode" => false);
			$where = $order = "";
			$l = explode("|", $name);
				for($i=0;$i<count($l);$i++) {
					$w = $this->wd_remove_accents($l[$i]);
					if(strlen($w)  > 2 && !is_numeric($w)) {
						$w = mysqli_escape_string($this->mysql, $w);
						$where .= ' OR (UPPER(`Name`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`Name`) REGEXP "'.$w.'" THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP "^'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP "^'.$w.'$" THEN 1.3 ELSE 0 END)';
					} else if(is_numeric($w)) { //DEPARTEMENT FIX
						$w = mysqli_escape_string($this->mysql, $w);
						$where .= ' OR (UPPER(`ZipCode`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`ZipCode`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`ZipCode`) REGEXP "'.$w.'$" THEN 0.5 ELSE 0 END)';
					}
				}
				$where .= '';
				if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
				if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
				$query = "SELECT `Real_Name`, `Name`, `ID`, `Lat`, `Lon`, `ZipCode` FROM `french_city` WHERE ".$where." GROUP BY `ID` ORDER BY ".$order." DESC, `ZipCode` ASC LIMIT 0, 1";
				$select = mysqli_query($this->mysql, $query);
				$data = mysqli_fetch_array($select);
				$total = mysqli_num_rows($select);
				if($total > 0) {
					similar_text(strtoupper($this->wd_remove_accents(preg_replace("/\|/", "-", $city))), strtoupper($this->wd_remove_accents($data['Name'])), $percent);	
					$length = strlen($data['Name']);
					//MATCH VERIFICATION + WITH NUMBERS LETTERS
					if($percent > 65 && strlen($city) <= ($length + 4) && strlen($city) >= ($length - 4)) {
						$result = array("Name" => $data['Real_Name'], "ID" => $data['ID'], "Lat" => $data['Lat'], "Lon" => $data['Lon'], "ZipCode" => $data['ZipCode']);
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
						$result_street = $xml->result->address_component[0]->long_name." ".$xml->result->address_component[1]->long_name;
						similar_text(strtoupper($this->wd_remove_accents($result_street)), strtoupper($this->wd_remove_accents($adresse)), $percent);
						if(number_format($percent, 0) < 35) {
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
			$mailoption, 
			$admin, 
			$gender, 
			$birthdate, 
			$city, 
			$lat,
			$logged, 
		$lon;
		private $mysql, 
			$cookies;
		function __construct($mysql) {
			$this->mysql = $mysql;
			$this->find_sess();
		}
		function onlyUsers() {
			if(!$this->logged) {
				header("Location: services.php?unlogged");	
			}
		}
		function onlyVisitors() {
			if($this->logged) {
				header("Location: services.php?needunlogged");	
			}
		}
		function onlyAdmin() {
			if($this->admin == 0) {
				header("Location: services.php?noadmin");	
			}
		}
		function modal_location_c($GET) {
			$text = "";
			if(isset($_GET['needunlogged'])) {
				$text = "Désolé, mais la page à laquelle vous souhaitiez afficher n'est pas accessible en tant qu'utilisateur connecté.<br><br>Veuillez vous déconnecté pour l'afficher.";
			}
			if(isset($_GET['unlogged'])) {
				$text = "Désolé, mais la page à laquelle vous souhaitiez afficher n'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l'afficher.";
			}
			if(isset($_GET['noadmin'])) {
				$text = "Désolé, mais la page à laquelle vous souhaitiez afficher est accessible uniquement pour les administrateurs du site.";
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
</div> <script>$(\'#modal_alert\').modal(\'show\'); $("#modal_alert").on("hidden.bs.modal", function(e) {
				$(this).remove();
			});</script>';
			if(!empty($text)) {
				echo $html;
			}
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
			$select = mysqli_query($this->mysql, "SELECT `Validation`, `Login`, COUNT(*) AS `total` FROM `users` WHERE `Email` = '".mysqli_escape_string($this->mysql, base64_decode($split[1]))."'");
			$data = mysqli_fetch_array($select);
			if($data['total'] > 0) {
				if(md5($data['Login']) == $split[0]) {
				 	if($data['Validation'] == 0) {
						if(!mysqli_query($this->mysql, "UPDATE `users` SET `Validation` = '1' WHERE `Email` = '".mysqli_escape_string($this->mysql, base64_decode($split[1]))."'")) {
							$html = 'Désolé, une erreurà eu lieu au moment de l\'activation.<br> Veuillez réessayer plus tard...';
						} else {
							$html = 'Félicitation !<br>Votre compte est activé !<br><br>Vous pouvez dès à présent vous connecter.';
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
			$select = mysqli_query($this->mysql, "SELECT `ID`,`Password`,`Validation` FROM `users` WHERE `Login` = '".mysqli_escape_string($this->mysql, strtolower($POST['login_form']))."'");
			$data = mysqli_fetch_array($select);
			$total = mysqli_num_rows($select);
			if($total > 0) {
				if($data['Password'] != md5($POST['password_form'])) {
					$arr = array(false, "Mauvais mot de passe");
				} else if($data['Validation'] == 0)  {
					//NOT VALIDATED
					$arr = array(false, "Ce compte n'a pas été validé via votre boite mail.");
				} else {
					//CONNECTED
					if(isset($POST['remember_me'])) {
						setcookie("user_swappy", $this->crypt_sess($data['ID']), time() + (60*60*60), "/");
					} else {
						$_SESSION['user_swappy'] = $this->crypt_sess($data['ID']);
					}
					$arr = array(true);
				}
			} else {
				$arr = array(false, "Mauvais login");	
			}
			return $arr;
		}
		function load_user_data($ID, $crypt) {
			$select = mysqli_query($this->mysql, "SELECT *, COUNT(*) AS `exist` FROM `users` WHERE `ID` = '".$ID."'");
			$data = mysqli_fetch_array($select);
			if($data['exist'] < 1) {
				//WRONG
				$this->logout();
			} else {
				//OK	
				$this->ID = $ID;
				$this->cryptID = $crypt;
				$this->admin = $data['Admin'];
				$this->avatar = $data['Avatar'];
				$this->login = $data['Login'];
				$this->email = $data['Email'];
				$this->firstname = $data['FirstName'];
				$this->lastname = $data['LastName'];
				$this->phone = $data['Phone'];
				$this->street = $data['Street'];
				$this->city = $data['City'];
				$this->zipcode = $data['ZipCode'];
				$this->mailoption = $data['MailOption'];
				$this->gender = $data['Gender'];
				$this->birthdate = $data['Birthdate'];
				$this->lon = $data['Lon'];
				$this->lat = $data['Lat'];
				$this->logged = true;
			}
		}
		function unload_user_data() {
			$this->ID = false;
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
		}
		function add_user($POST) {
			//prevenir le bug de Validation engine
			if(empty($POST['cityname']) || empty($POST['zipcode'])) {
				$arr = array(false);
			} else {
				$street = $POST['street'];
				if(!empty($_POST['street2'])) {
					if(empty($street)) {
						$street = $POST['street2'];
					} else {
						$street .= " ".$POST['street'];
					}
				}
				$birthdate = $POST['year']."-".$POST['month']."-".$POST['day'];
				//Creation de la position Lat/Lon
				$city = new city($this->mysql);
				$c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
				$replace = array(mysqli_escape_string($this->mysql, strtolower($POST['login'])),
					mysqli_escape_string($this->mysql, md5($POST['password'])), 
					mysqli_escape_string($this->mysql, strtolower($POST['email'])), 
					mysqli_escape_string($this->mysql, $POST['lastname']), 
					mysqli_escape_string($this->mysql, $POST['firstname']), 
					mysqli_escape_string($this->mysql, $POST['gender']),
					mysqli_escape_string($this->mysql, $birthdate),
					mysqli_escape_string($this->mysql, $street),
					mysqli_escape_string($this->mysql, $POST['zipcode']),
					mysqli_escape_string($this->mysql, $POST['cityname']),
					mysqli_escape_string($this->mysql, $c['lat']),
					mysqli_escape_string($this->mysql, $c['lon']),
					mysqli_escape_string($this->mysql, $POST['phone'])
				);
				if(!mysqli_query($this->mysql, vsprintf("INSERT INTO `users` (`ID`, `Login`, `Password`, `Email`, `Created`, `Avatar`, `LastName`, `FirstName`, `Gender`, `Birthdate`, `Street`, `ZipCode`, `City`, `Lat`, `Lon`, `Phone`, `Admin`, `MailOption`, `Validation`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP, NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '0', '0', '0');", $replace))) {
					$arr = array(false);
				} else {
					//SEND MAIL VALIDATION
					$mail = new mail();
					$hash = $this->make_link_validation($POST['email'], $POST['login']);
					$mail->send_validation(strtolower($POST['email']), strtolower($POST['firstname']), $hash);
					$arr = array(true);
				}
				return $arr;
			}
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
				$select = mysqli_query($this->mysql, "SELECT COUNT(*) AS `total` FROM `users` WHERE `Login` = '".mysqli_escape_string($this->mysql, $login)."'");
				$data = mysqli_fetch_array($select);
				if($data['total'] > 0) {
					$arr = array($id, false);
				} else {
					$arr = array($id, true);	
				}
				return $arr;
		}
		function issetEmail($email, $id) {
			$select = mysqli_query($this->mysql, "SELECT COUNT(*) AS `total` FROM `users` WHERE `Email` = '".mysqli_escape_string($this->mysql, $email)."'");
			$data = mysqli_fetch_array($select);
			if($data['total'] > 0) {
				$arr = array($id, false);
			} else {
				$arr = array($id, true);	
			}	
			return $arr;
		}
		function issetZipCode($zipcode, $id) {
			$arr = array($id, true, array());
			$select = mysqli_query($this->mysql, "SELECT `ID`,`Real_Name` FROM `french_city` WHERE `ZipCode` = '".mysqli_escape_string($this->mysql, $zipcode)."'");
			$total = mysqli_num_rows($select);
			while($data = mysqli_fetch_array($select)) {
				if($total == 1) {
					$arr = array($id, true, $data['Real_Name']);
				} else if($total > 1) {
					array_push($arr[2], $data['Real_Name']);
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
             $html .= '<li class="dropdown">';
             if(!$this->logged) {
             	$html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Connexion <span class="caret"></span></a>
                        <div class="dropdown-menu login-menu">
                            <div id="login_section">
                                <form action="inc/login.php" method="post" id="login_form">
                                    <input id="login_form" name="login_form" class="validate[required,minSize[5]]" placeholder="Identifiant" type="text" size="30">
                                    <input type="password" id="password_form" name="password_form" placeholder="Mot de passe" class="validate[required,minSize[6]]" size="30">
                                    <label class="string optional" for="user_remember_me">
                                        <input id="remember_me" type="checkbox" name="remember_me" checked> Se souvenir de moi
                                    </label>
                                    <input class="btn btn-primary" type="submit" name="commit" value="Se connecter">
                                    <div class="remind"><a class="remind_link">Mot de passe perdu ?</a></div>
                                </form>		
                            </div>
                            <div id="remind_section">
                                <form action="[YOUR ACTION]" method="post" accept-charset="UTF-8">
                                    <input id="user_username" placeholder="Email" type="text" name="remind[email]" size="30">
                                    <input class="btn btn-primary" type="submit" name="commit" value="Recuperer">
                                    <div class="remind"><a class="remind_link">Je m\'en souviens !</a></div>
                                </form>
                            </div>
                        </div>';
                 } else { 
                    $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bonjour '.$this->firstname.'<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="?logout">Se deconnecter</a></li>
                            </ul>';
                } 
			$html .= "</li>";
			return $html;
		}
	}  ?>