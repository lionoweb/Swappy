<?php
	class services {
		private $mysql;
		function __construct($mysql) {
			$this->mysql = $mysql;
		}
		function add_services($POST) {
			//DISPONIBILITE		
			$dispo = $this->dispo_crypt($POST['dispoday'], $POST['dispostart'], $POST['dispoend']);
			$user = new user($this->mysql);
			$ID = $user->uncrypt_sess($POST['ID']);
			mysqli_query($this->mysql, "INSERT INTO `services` (`ID`, `Title`, `Type`, `By`, `Description`, `Image`, `Distance`, `Disponibility`, `Created`) VALUES (NULL, '".$POST['title']."', '".$POST['type']."', '".$ID."', '".$POST['desc']."', NULL, '".$POST['distance']."', '".$dispo."', CURRENT_TIMESTAMP);");
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
		function search($GET, $user) {
			
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