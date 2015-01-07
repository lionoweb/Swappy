<?php
	class services {
		private $mysql;
		function __construct($mysql) {
			$this->mysql = $mysql;
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
		function preg_accent($w) {
			if(preg_match("/E|É|È|Ê|Ë/", $w)) {
				$w = preg_replace("/E|É|È|Ê|Ë/","(E|É|È|Ê|Ë)", $w);	
			}
			if(preg_match("/A|À|Á|Â|Ä/", $w)) {
				$w = preg_replace("/A|À|Á|Â|Ä/","(A|À|Á|Â|Ä)", $w);	
			}
			if(preg_match("/C|Ç/", $w)) {
				$w = preg_replace("/C|Ç/","(C|Ç)", $w);	
			}
			return $w;
		}
		function search($GET, $user) {
			$searchbar = $type = $where = $day = $input = "";
			if($GET['searchbar'] != "") {
				$where = '';
				$order = '';
				$input = preg_replace("/ |\-|\'/", "{}" , $GET['searchbar']);
				$l = explode("{}", $input);
				for($i=0;$i<count($l);$i++) {
					$w = $this->preg_accent($l[$i]);
					if(strlen($w) > 1) {
						$where_ .= ' OR (UPPER(`type`.`Name`) REGEXP "'.$w.'") OR (UPPER(`categories`.`Name`) REGEXP "'.$w.'") OR (UPPER(`services`.`Name`) REGEXP "'.$w.'") OR (UPPER(`services`.`Description`) REGEXP "'.$w.'")';
						$order .= ' + (UPPER(`type`.`Name`) REGEXP "'.$w.'") + (UPPER(`categories`.`Name`) REGEXP "'.$w.'")';
					}
				}
				if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
				if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
			}
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