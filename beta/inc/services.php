<?php
	class services {
		private $mysql;
		function __construct($mysql) {
			$this->mysql = $mysql;
		}
		function format_city($zipcode) {
			$return = "";
			$select = mysqli_query($this->mysql, "SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = '".mysqli_real_escape_string($this->mysql, $zipcode)."'");
			$data = mysqli_fetch_array($select);
			$return = $zipcode." (".$data['Real_Name'].")";
			return $return;	
		}
		function my_services($user) {
			$html = "";
			$select = mysqli_query($this->mysql, "SELECT *, COUNT(*) AS `nb` FROM `services` WHERE `By` = '".$user->ID."' ORDER BY `Created` DESC");
			while($data = mysqli_fetch_array($select)) {
				$name = $data['Title'];
				if($data['Title'] == "") {
					$name = $this->type_name($data['Type']);	
				}
				$html .= '<tr class="bloc_services">
            			<td class="picto picto-'.$data['Type'].'">
                        	
                        </td>
                		<td class="desc_services">
                            <h1>'.$name.'</h1>
                            <p>
                                '.$data['Description'].'
                            </p>
                            <div class="location">
                                Champigny sur Marne
                            </div>
                         </td>
                     </tr>';
			}
			if($html == "") {
				$html = "rien";
			}
			return $html;
		}
		function get_coord($zip) {
			$array = array("lon" => false, "lat" => false);
			$select = mysqli_query($this->mysql, "SELECT `Lat`, `Lon`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = '".mysqli_real_escape_string($this->mysql, $zip)."'");
			$data = mysqli_fetch_array($select);
			if($data['total'] > 0) {
				$array['lon'] = $data['Lon'];	
				$array['lat'] = $data['Lat'];
			}
			return $array;
		}
		function get_cityID($zip) {
			$ID = false;
			$select = mysqli_query($this->mysql, "SELECT `ID`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = '".mysqli_real_escape_string($this->mysql, $zip)."'");
			$data = mysqli_fetch_array($select);
			if($data['total'] > 0) {
				$ID = $data['ID'];	
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
			$replace = array(mysqli_escape_string($this->mysql, $POST['title']),
					mysqli_escape_string($this->mysql, $POST['type']), 
					mysqli_escape_string($this->mysql, $ID), 
					mysqli_escape_string($this->mysql, $POST['description']), 
					mysqli_escape_string($this->mysql, $POST['distance']), 
					mysqli_escape_string($this->mysql, $dispo),
					mysqli_escape_string($this->mysql, $city),
					mysqli_escape_string($this->mysql, $lat),
					mysqli_escape_string($this->mysql, $lon)
				);
			mysqli_query($this->mysql, vsprintf("INSERT INTO `services` (`ID`, `Title`, `Type`, `By`, `Description`, `Image`, `Distance`, `Disponibility`, `Created`, `City`, `Lat`, `Lon`) VALUES (NULL, '%s', '%s', '%s', '%s', NULL, '%s', '%s', CURRENT_TIMESTAMP, '%s', '%s', '%s');", $replace));
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
			$select = mysqli_query($this->mysql, "SELECT `Name` FROM `type` WHERE `ID` = '".$id."'");
			$data = mysqli_fetch_array($select);
			return $data['Name'];
		}
		function list_categories($required=false) {
			$req = "";
			if($required) {
				$req = 'validate[required] ';
			}
			$html = '<select class="'.$req.'form-control" id="type" name="type">';
			$html .= '<option value=""></option>';
			$select = mysqli_query($this->mysql, "SELECT * FROM `categories` ORDER BY `Name` ASC");
			while($data = mysqli_fetch_array($select)) {
				$select_ = mysqli_query($this->mysql, "SELECT `ID`,`Name` FROM `type` WHERE `Categorie` = '".$data['ID']."' ORDER BY `Name` ASC");
				$html .= '<option disabled="disabled">'.$data['Name'].'</option>';
				while($data_ = mysqli_fetch_array($select_)) {
					if($data['Name'] == "Autres") {
						$other = $data_['ID'];
					} else {
						if($data_['Name'] == "Autres") {
							$other_ = $data_['ID'];
						} else {
							$html .= '<option value="'.$data_['ID'].'">&emsp;&emsp;'.$data_['Name'].'</option>';
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