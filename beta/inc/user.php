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
			$select = mysqli_query($this->mysql, "SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = '".$zip."'");	
			$data = mysqli_fetch_array($select);
			if(isset($data['Real_Name'])) {
				$result = $data['Real_Name'];
			}
			return $result;
		}
		function getPositionDB($zip) {
			$result = array("lat" => false, "lon" => false);
			$select = mysqli_query($this->mysql, "SELECT `Lon`, `Lat` FROM `french_city` WHERE `ZipCode` = '".$zip."'");	
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
						$where .= ' OR (UPPER(`Name`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`Name`) REGEXP "'.$w.'" THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP "^'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP "^'.$w.'$" THEN 1.3 ELSE 0 END)';
					} else if(is_numeric($w)) { //DEPARTEMENT FIX
						$where .= ' OR (UPPER(`ZipCode`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`ZipCode`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`ZipCode`) REGEXP "'.$w.'$" THEN 0.5 ELSE 0 END)';
					}
				}
				$where .= '';
				if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
				if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
				$query = "SELECT `Real_Name`, COUNT(*) AS `total`, `Name`, `ID`, `Lat`, `Lon`, `ZipCode` FROM `french_city` WHERE ".$where." GROUP BY `ID` ORDER BY ".$order." DESC, `ZipCode` ASC LIMIT 0, 1";
				$select = mysqli_query($this->mysql, $query);
				$data = mysqli_fetch_array($select);
				if($data['total'] > 0) {
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
						$coords = $this->getPositionDB($zip);
					}
				} else {
					// Si GOOGLE n'a rien trouvé, on va utiliser la BDD
					$coords = $this->getPositionDB($zip);
				}
			} else {
				//Si on a pas une adresse complete on va utiliser la BDD
				$coords = $this->getPositionDB($zip);
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
				header("Location: services.php?needlogged");	
			}
		}
		function onlyAdmin() {
			if($this->admin == 0) {
				header("Location: services.php?noadmin");	
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
		function flogin($POST) {
			$arr = array();
			$select = mysqli_query($this->mysql, "SELECT `ID`,`Password`, COUNT(*) AS `total` FROM `users` WHERE `Login` = '".mysqli_escape_string($this->mysql, $POST['login_form'])."'");
			$data = mysqli_fetch_array($select);
			if($data['total'] > 0) {
				if($data['Password'] != md5($POST['password_form'])) {
					$arr = array(false, "Mauvais mot de passe");
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
			if(empty($POST['city']) || empty($POST['zipcode'])) {
				$arr = array(false);
			} else {
				$birthdate = $POST['year']."-".$POST['month']."-".$POST['day'];
				//Creation de la position Lat/Lon
				$city = new city($this->mysql);
				$c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
				$replace = array(mysqli_escape_string($this->mysql, $POST['login']),
					mysqli_escape_string($this->mysql, md5($POST['password'])), 
					mysqli_escape_string($this->mysql, $POST['email']), 
					mysqli_escape_string($this->mysql, $POST['lastname']), 
					mysqli_escape_string($this->mysql, $POST['firstname']), 
					mysqli_escape_string($this->mysql, $POST['gender']),
					mysqli_escape_string($this->mysql, $birthdate),
					mysqli_escape_string($this->mysql, $POST['street']),
					mysqli_escape_string($this->mysql, $POST['zipcode']),
					mysqli_escape_string($this->mysql, $POST['cityname']),
					mysqli_escape_string($this->mysql, $c['lat']),
					mysqli_escape_string($this->mysql, $c['lon']),
					mysqli_escape_string($this->mysql, $POST['phone'])
				);
				if(!mysqli_query($this->mysql, vsprintf("INSERT INTO `users` (`ID`, `Login`, `Password`, `Email`, `Created`, `Avatar`, `LastName`, `FirstName`, `Gender`, `Birthdate`, `Street`, `ZipCode`, `City`, `Lat`, `Lon`, `Phone`, `Admin`, `MailOption`, `Validation`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP, NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '0', '0', '0');", $replace))) {
					$arr = array(false);
				} else {
					$arr = array(true);
				}
				return $arr;
			}
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
			$select = mysqli_query($this->mysql, "SELECT `Real_Name`, COUNT(*) AS `match` FROM `french_city` WHERE `ZipCode` = '".mysqli_escape_string($this->mysql, $zipcode)."'");
			$data = mysqli_fetch_array($select);
			if($data['match'] > 0) {
				$arr = array($id, true, $data['Real_Name']);
			} else {
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