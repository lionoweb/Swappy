<?php
	class services {
		private $mysql;
		public $ID = "";
		public $title = "";
		public $type = "";
		public $typename = "";
		public $cattype = "";
		public $by = "";
		public $description = "";
		public $image = "";
		public $distance = "";
		public $disponibility = "";
		public $created = "";
		public $city = "";
		public $lat = "";
		public $lon = "";
		public $zip = "";
		public $dispo_ = "";
		function __construct($mysql, $ids="") {
			$this->mysql = $mysql;
			if(!empty($ids)) {
				$this->load_service($ids);
			} else if(preg_match("/annonce\.php/", $_SERVER['PHP_SELF']) && !preg_match("/vote\=/", $_SERVER['QUERY_STRING'])) {
				header("Location: services.php");	
			}
		}
		function load_service($ids) {
			$select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`ID` AS `CatType`, `services`.`Title`, `services`.`Type`, `type`.`Name` AS `TypeName`, `services`.`By`, `services`.`Description`, `services`.`Distance`, `services`.`Disponibility`, `services`.`Created`, `services`.`City`, `services`.`Lat`, `services`.`Lon`, `french_city`.`ZipCode`, `french_city`.`Real_Name` AS `CityName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE `services`.`ID` = :ID");
			$select->execute(array(":ID" => $ids));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$total = $select->rowCount();
			if($total < 1) {
				if(!preg_match("/inc\//", $_SERVER['PHP_SELF'])) {
					header("Location: 404.php");
				}
			} else {
				$this->ID = $data->ID;
				if(empty($data->Title)) {
					$this->title = $data->TypeName;
				} else {
					$this->title = $data->Title;
				}
				$this->type = $data->Type;
				$this->typename = $data->TypeName;
				$this->cattype = $data->CatType;
				$this->by = $data->By;
				if(empty($data->Description)) {
					$this->description = "L'utilisateur n'a pas fourni de description...";
				} else {
					$this->description = $data->Description;
				}
				$this->image = $data->Image;
				$this->distance = $data->Distance;
				$this->disponibility = $this->dispo_uncrypt_an($data->Disponibility);
				$this->created = $data->Created;
				$this->city = $data->CityName;
				$this->zip = $data->ZipCode;
				$this->lat = $data->Lat;
				$this->lon = $data->Lon;
				$this->dispo_ = $data->Disponibility;
			}
		}
		function delete_serv($id, $user) {
			$array = array(false);
			if($this->own_s($id) != $user) {
				//PAS Proprio
			} else {
				//RDV
				//MESSAGE
				//NOTATION
				//SERVICE
				//REPORT ?
				$ss = $this->mysql->prepare("DELETE FROM `appointment` WHERE `Service` = :id");
				$ss->execute(array(":id" => $id));
				$ss = $this->mysql->prepare("DELETE FROM `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` WHERE `conversation`.`ServiceFor` = :id");
				$ss->execute(array(":id" => $id));
				$ss = $this->mysql->prepare("DELETE FROM `conversation` WHERE `ServiceFor` = :id");
				$ss->execute(array(":id" => $id));
				$ss = $this->mysql->prepare("DELETE FROM `notations` WHERE `Service` = :id");
				$ss->execute(array(":id" => $id));
				$ss = $this->mysql->prepare("DELETE FROM `services` WHERE `ID` = :id");
				$ss->execute(array(":id" => $id));
				$array = array(true);
			}
			return $array;
		}
		function decrypt_vote_h($hash) {
			$h = base64_decode($hash);
			$c = preg_split("/\/\/\//", $h);	
			$id_a = $c[0];
			$date_a = $c[1];
			return array("ID" => $id_a, "Date" => $date_a);
		}
		function has_voted($id, $owner, $user) {
			$select = $this->mysql->prepare("SELECT COUNT(*) AS `nb` FROM `notations` WHERE `By` = :id AND `Service` = :serv AND `Owner_Service` = :oserv ");
			$select->execute(array(":id" => $user, ":serv" => $id, ":oserv" => $owner));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb < 1) {
				return false;
			} else {
				return true;
			}
		}
		function add_note($POST, $user, $chat) {
			$arr = array(false);
			$h = $this->decrypt_vote_h($POST['hash']);
			$select = $this->mysql->prepare("SELECT *, COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id AND `State` = '5'");
			$select->execute(array(":id" => $h['ID']));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb < 1) {
				//PAS DE RDV ENREGISTRE
				$arr = array(false,"Vous n'avez pas eu de rendez-vous pour ce service. Vous ne pouvez donc pas le noter.");
			} else {
				if($data->User != $user->ID) {
					//PAS LE MEMBRE
					$arr = array(false, "Désolé, mais vous n'êtes pas autorisé à noter ce service.");
				} else if($data->Date != $h['Date']) {
					//SECURITE
					$arr = array(false, "Désolé, mais votre lien est incorrecte.");
				} else if($this->has_voted($data->Service,$data->Owner_Service, $user->ID)) {
					//DEJA VOTE
					$arr = array(false, "Vous avez déjà noté pour ce service.");
				} else {
					//OK
					$select = $this->mysql->prepare("INSERT INTO `notations` (`ID`, `By`, `Service`, `Owner_Service`, `Note`, `Message`, `Date`) VALUES (NULL, :by, :serv, :owner, :note, :com, :date);");
					$select->execute(array(":by" => $user->ID, ":serv" => $data->Service, ":owner" => $data->Owner_Service, ":note" => trim($POST['note']), ":com" => trim($POST['com']), ":date" => date("Y-m-d H:i:s")));
					$cc = $chat->isset_conversation($data->Owner_Service, $data->Service);
					if($cc == false) {
						$cc = $this->make_conversation($user->ID, $data->Service);
					}
					$mess = $user->login.' a noté votre service :<br>Note : '.trim($POST['note']).'/5<br>Commentaire : '.nl2br(trim($POST['com']));
					$chat->send_reply($mess, $cc, $data->Owner_Service);
					$arr = array(true);
				}
			}
			return $arr;
		}
		function page_vote($hash, $user) {
			$html = "";
			$h = $this->decrypt_vote_h($hash);
			$select = $this->mysql->prepare("SELECT *, COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id AND `State` = '5'");
			$select->execute(array(":id" => $h['ID']));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$this->load_service($data->Service);
			if($data->nb < 1) {
				//PAS DE RDV ENREGISTRE
				$html = "Vous n'avez pas eu de rendez-vous pour ce service...<br>Vous ne pouvez donc pas le noter.";
			} else {
				if($data->User != $user) {
					//PAS LE MEMBRE
					$html = "Désolé, mais vous n'êtes pas autorisé à noter ce service.";
				} else if($data->Date != $h['Date']) {
					//SECURITE
					$html = "Désolé, mais votre lien est incorrecte.";
				} else if($this->has_voted($data->Service,$data->Owner_Service, $user)) {
					//DEJA VOTE
					$html = "Vous avez déjà noté pour ce service.";
				} else {
					//OK
					$html = 'Veuillez attribuer une note à ce service ainsi qu\'un commentaire (optionel) :<br><br><form  id="note_form" action="inc/add_services.php" method="post"><label class="label-control" for="rate">Votre note :</label><input id="input-5" name="note" class="rating validate[required]" data-size="xs" data-show-clear="false" data-step="1" value="1" data-max="5" data-min="0"><label for="com" class="label-control">Votre commentaire :</label><textarea class="form-control" id="com" name="com"></textarea><input type="hidden" name="hash" value="'.$hash.'"><input type="submit" value="Envoyer"></form>';
				}
			}
			return $html;
		}
		function format_city($zipcode, $city="") {
			$return = "";
			$cityn = $city;
			if(empty($city)) {
				$select = $this->mysql->prepare("SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = :zipcode");
				$select->execute(array(":zipcode" => $zipcode));
				$data = $select->fetch(PDO::FETCH_OBJ);
				$cityn = $data->Real_Name;
			}
			$return = $zipcode." (".$cityn.")";
			return $return;	
		}
		function get_coord($zip) {
			$array = array("lon" => false, "lat" => false);
			$select = $this->mysql->prepare("SELECT `Lat`, `Lon`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = :zipcode");
			$select->execute(array(":zipcode" => $zip));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				$array['lon'] = $data->Lon;	
				$array['lat'] = $data->Lat;
			}
			return $array;
		}
		function get_cityID($zip) {
			$ID = false;
			$select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = :zipcode");
			$select->execute(array(":zipcode" => $zip));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				$ID = $data->ID;	
			}
			return $ID;
		}
		function own_s($id) {
			$select = $this->mysql->prepare("SELECT `By` FROM `services` WHERE `ID` = :id");
			$select->execute(array(":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			return $data->By;
		}
		function edit_services($POST, $user) {
			$id_s = trim($POST['ID_EDIT']);
			$prop = $this->own_s($id_s);
			if($prop != $user->ID) {
				return array(false, "Vous êtes pas le propriétaire du service.");
			} else {
			//DISPONIBILITE		
			$dispo = $this->dispo_crypt($POST['dispoday'], $POST['dispostart'], $POST['dispoend']);
			$user = new user($this->mysql);
			$ID = $user->ID;
			if($user->zipcode == $POST['zipcode']) {
				$city = $this->get_cityID($user->zipcode);
				$lat = $user->lat;
				$lon = $user->lon;	
			} else {
				$city = $this->get_cityID($POST['zipcode']);
				$ar = $this->get_coord($POST['zipcode']);
				$lat = $ar['lat'];
				$lon = $ar['lon'];
			}
			$replace = array(":title" => $POST['title'],
					":type" => $POST['type'],
					":description" => $POST['description'], 
					":distance" => $POST['distance'], 
					":dispo" => $dispo,
					":city" => $city,
					":lat" => $lat,
					":lon" => $lon,
					":ids" => $id_s
				);
			$select = $this->mysql->prepare("UPDATE `services` SET `Title` = :title, `Type` = :type, `Description` = :description, `Distance` = :distance, `Disponibility` = :dispo, `City` = :city, `Lat` = :lat, `Lon` = :lon WHERE `ID` = :ids");
			$select->execute($replace);
			return array(true);
			}
		}
		function add_services($POST, $user) {
			//DISPONIBILITE		
			$dispo = $this->dispo_crypt($POST['dispoday'], $POST['dispostart'], $POST['dispoend']);
			$user = new user($this->mysql);
			$ID = $user->uncrypt_sess($POST['ID']);
			if($user->zipcode == $POST['zipcode']) {
				$city = $this->get_cityID($user->zipcode);
				$lat = $user->lat;
				$lon = $user->lon;	
			} else {
				$city = $this->get_cityID($POST['zipcode']);
				$ar = $this->get_coord($POST['zipcode']);
				$lat = $ar['lat'];
				$lon = $ar['lon'];
			}
			$replace = array(":title" => $POST['title'],
					":type" => $POST['type'], 
					":ID" => $ID, 
					":description" => $POST['description'], 
					":distance" => $POST['distance'], 
					":dispo" => $dispo,
					":city" => $city,
					":lat" => $lat,
					":lon" => $lon
				);
			$select = $this->mysql->prepare("INSERT INTO `services` (`ID`, `Title`, `Type`, `By`, `Description`, `Image`, `Distance`, `Disponibility`, `Created`, `City`, `Lat`, `Lon`) VALUES (NULL, :title, :type, :ID, :description, NULL, :distance, :dispo, CURRENT_TIMESTAMP, :city, :lat, :lat);");
			$select->execute($replace);
			return array(true);
		}
		function dispo_crypt($day, $start, $end) {
			$txt = "";
			foreach ($day as $name => $val) {
				$txt .= $val."@".$start[$name]."-".$end[$name]."||";
			}
			return substr($txt, 0, strlen($txt)-2);
		}
		function dispo_uncrypt($txt) {
			$out = "";
			$sp = explode("||", $txt);
			$trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
			for($i=0;$i<count($sp);$i++) {
				$d = explode("@", $sp[$i]);
				$h = explode("-", $d[1]);
				$out .= $trad[$d[0]]." de ".$h[0]." à ".$h[1]."<br>";
			}
			return $out;
		}
		function dispo_uncrypt_edit($txt) {
			$html = '<span data-IDF="{ID}" class="dispo_field">
                        <select id="dispoday[{ID}]" name="dispoday[{ID}]" class="form-control days">';
        $html.= '<option value="all">Tous les jours</option>'.
                           '<option value="weekend">Le week-end</option>'.
                                                '<option value="lun">Lundi</option>'.
                                                '<option value="mar">Mardi</option>'.
                                                '<option value="mer">Mercredi</option>'.
                                                '<option value="jeu">Jeudi</option>'.
                                                '<option value="ven">Vendredi</option>'.
                                                '<option value="sam">Samedi</option>'.
                                                '<option value="dim">Dimanche</option>';
        $html .= '</select>
                        <span class="toline-xs">entre
                        <input size="5" maxlength="5" name="dispostart[{ID}]" value="{START}" class="time form-control validate[required] timepicker" id="dispostart[{ID}]" type="text">
                        et
                        <input maxlength="5" name="dispoend[{ID}]" class="validate[required,timeCheck[dispostart{{ID}}]] form-control timepicker time" value="{END}" size="5" type="text"></span>
                        </span>';
			$out = "";
			$sp = explode("||", $txt);
			$trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
			for($i=0;$i<count($sp);$i++) {
				$d = explode("@", $sp[$i]);
				$h = explode("-", $d[1]);
				$out .= preg_replace('/value\=\"'.$d[0].'\"/', 'value="'.$d[0].'" selected="selected"', preg_replace("/\{END\}/", $h[1], preg_replace("/\{START\}/", $h[0], preg_replace("/\{ID\}/", ($i+1), $html))));
				//$out .= "<span class='disponi'>".ucfirst($trad[$d[0]])." de ".$h[0]." à ".$h[1]."</span><br>";
			}
			return $out;
		}
		function dispo_uncrypt_an($txt) {
			$out = "";
			$sp = explode("||", $txt);
			$trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
			for($i=0;$i<count($sp);$i++) {
				$d = explode("@", $sp[$i]);
				$h = explode("-", $d[1]);
				$out .= "<span class='disponi'>".ucfirst($trad[$d[0]])." de ".$h[0]." à ".$h[1]."</span><br>";
			}
			return $out;
		}
		function type_name($id) {
			$select = $this->mysql->prepare("SELECT `Name` FROM `type` WHERE `ID` = :ID");
			$select->execute(array(":ID" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			return $data->Name;
		}
		function list_categories($required=false, $selected="") {
			$req = "";
			if($required) {
				$req = 'validate[required] ';
			}
			$html = '<select class="'.$req.'form-control list_type_" id="type" name="type">';
			$html .= '<option value=""></option>';
			$select = $this->mysql->query("SELECT * FROM `categories` ORDER BY `Name` ASC");
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$select_ = $this->mysql->prepare("SELECT `ID`,`Name` FROM `type` WHERE `Categorie` = :ID ORDER BY `Name` ASC");
				$select_->execute(array(":ID" => $data->ID));
				if($data->Name != "Autres") {
					$html .= '<option disabled="disabled">'.$data->Name.'</option>';
				}
				while($data_ = $select_->fetch(PDO::FETCH_OBJ)) {
					if($data->Name == "Autres") {
						$other = $data_->ID;
					} else {
						if($data_->Name == "Autres") {
							$other_ = $data_->ID;
						} else {
							$html .= '<option value="'.$data_->ID.'">&emsp;&emsp;'.$data_->Name.'</option>';
						}
					}
				}
				if($data->Name != "Autres") {
					$html .= '<option value="'.$other_.'">&emsp;&emsp;Autres services en '.$data->Name.'</option>';
				}
			} 
			$html .= '<option disabled="disabled">Autres</option><option value="'.$other.'">&emsp;&emsp;Autres...</option>';
			$html .= '</select>	';
			if($selected != "") {
				$html = preg_replace('/\<option value\=\"'.$selected.'\"/', '<option value="'.$selected.'\" selected', $html);
			}
			return $html;
		}
	}
?>