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
		function __construct($mysql, $ids="") {
			$this->mysql = $mysql;
			if(!empty($ids)) {
				$this->load_service($ids);
			} else if(preg_match("/annonce\.php/", $_SERVER['PHP_SELF'])) {
				header("Location: services.php");	
			}
		}
		function load_service($ids) {
			$select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`ID` AS `CatType`, `services`.`Title`, `services`.`Type`, `type`.`Name` AS `TypeName`, `services`.`By`, `services`.`Description`, `services`.`Distance`, `services`.`Disponibility`, `services`.`Created`, `services`.`City`, `services`.`Lat`, `services`.`Lon`, `french_city`.`Real_Name` AS `CityName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE `services`.`ID` = :ID");
			$select->execute(array(":ID" => $ids));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$total = $select->rowCount();
			if($total < 1) {
				header("Location: 404.php");
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
				$this->lat = $data->Lat;
				$this->lon = $data->Lon;
			}
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
		function list_categories($required=false) {
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
			return $html;
		}
	}
?>