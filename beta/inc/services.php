<?php
	class services {
		private $mysql;
		function __construct($mysql) {
			$this->mysql = $mysql;
		}
		function format_city($zipcode) {
			$return = "";
			$select = $this->mysql->prepare("SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = :zipcode");
			$select->execute(array(":zipcode" => $zipcode));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$return = $zipcode." (".$data->Real_Name.")";
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
			$html = '<select class="'.$req.'form-control" id="type" name="type">';
			$html .= '<option value=""></option>';
			$select = $this->mysql->query("SELECT * FROM `categories` ORDER BY `Name` ASC");
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$select_ = $this->mysql->prepare("SELECT `ID`,`Name` FROM `type` WHERE `Categorie` = :ID ORDER BY `Name` ASC");
				$select_->execute(array(":ID" => $data->ID));
				$html .= '<option disabled="disabled">'.$data->Name.'</option>';
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
				$html .= '<option value="'.$other_.'">&emsp;&emsp;Autres</option>';
			} 
			$html .= '<option disabled="disabled">Autres</option><option value="'.$other.'">&emsp;&emsp;Autres</option>';
			$html .= '</select>	';
			return $html;
		}
	}
?>