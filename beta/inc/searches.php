<?php
class search {
	private $mysql;
	function __construct($mysql) {
		$this->mysql = $mysql;
	}
	function clearcity_match($input) {
		$out_t = '';
		$out_n = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $this->clean_w($input));
		$l = explode("{}", $input);
		for($i=0;$i<count($l);$i++) {
			$w = $l[$i];
			if(is_numeric($w)) {
				$out_n .= ' '.$w.'';
			} else {
				if(strlen($w) > 2) {
					$out_t .= ' '.$w.'*';
				}
			}
		}
		if(!empty($out_t)) { $out_t = substr($out_t, 1, strlen($out_t)); }
		if(!empty($out_n)) { $out_n = substr($out_n, 1, strlen($out_n)); }
		return array($out_t, $out_n);
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
	function clean_w($input) {
		return preg_replace("/\(|\)/", "", $input);	
	}
	function clause_searchbar($input) {
		$out = '';
		$where = '';
		$order = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $input);
		$l = explode("{}", $input);
		for($i=0;$i<count($l);$i++) {
			$w = $this->preg_accent($l[$i]);
			if(strlen($w) > 1) {
				$where .= ' OR (UPPER(`type`.`Name`) REGEXP "'.$w.'") OR (UPPER(`categories`.`Name`) REGEXP "'.$w.'")';
				$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "'.$w.'" THEN 1 ELSE 0 END)';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.' GROUP BY `type`.`ID`  ORDER BY '.$order. ' DESC, `type`.`Name` DESC LIMIT 0, 5';
		return $out;
	}
	function clause_searchcity($input) {
		$out = '';
		$where = '';
		$order = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $input);
		$l = explode("{}", $input);
		for($i=0;$i<count($l);$i++) {
			$w = $this->preg_accent($l[$i]);
			if(is_numeric($w)) {
				$where .= ' OR (`ZipCode` REGEXP "'.$w.'")';
				$order .= ' + (CASE WHEN `ZipCode` REGEXP "'.$w.'" THEN 1 ELSE 0 END) + (CASE WHEN `ZipCode` REGEXP "^'.$w.'" THEN 1.5 ELSE 0 END) + (CASE WHEN `ZipCode` REGEXP "^'.$w.'$" THEN 1.7 ELSE 0 END)';
			} else {
				$where .= ' OR (`Real_Name` REGEXP "'.$w.'")';
				$order .= ' + (CASE WHEN `Real_Name` REGEXP "'.$w.'" THEN 1 ELSE 0 END) + (CASE WHEN `Real_Name` REGEXP "^'.$w.'" THEN 1.5 ELSE 0 END) + (CASE WHEN `Real_Name` REGEXP "^'.$w.'$" THEN 1.7 ELSE 0 END)';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.' GROUP BY `ID` ORDER BY '.$order.' DESC LIMIT 0, 5';
		return $out;
	}
	function searchbar($input) {
		$arr = array();
		$input = $this->clean_w(strtoupper(trim($input)));
		$end_clause = $this->clause_searchbar($input);
		$select = mysqli_query($this->mysql, "SELECT `type`.`Name` AS `Name_t`, `type`.`ID` AS `ID`, `categories`.`Name` AS `CatName` FROM `type` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` ".$end_clause);
		while($data = mysqli_fetch_array($select)) {
			$arr[] = array("label" => "Service : ".$data['Name_t']." (".$data['CatName'].")", "val" => $data['ID']);	
		}
		$selecte = mysqli_query($this->mysql, "SELECT `Login`, `ID` FROM `users` WHERE UPPER(`Login`) LIKE '".$input."%' LIMIT 0, 1");
		while($datae = mysqli_fetch_array($selecte)) {
			$arr[] = array("label" => "Utilisateur : ".$datae['Login'], "userID" => $datae['ID']);	
		}
		return $arr;
	}
	function search($GET, $user) {
		$searchbar = $type = $locat = $day = $zip = $input = $where = $order = $final = "";
		$position = array("lat" => false, "lon" => false);
		$searchbar = @$GET['searchbar'];
		$type = @$GET['type'];
		$locat = @$GET['where'];
		$day = @$GET['day'];
		$zip = @$GET['zip'];
		$input = preg_replace("/ |\-|\'/", "{}" , $this->clean_w(strtoupper($GET['searchbar'])));
		$l = explode("{}", $input);
		if(!empty($searchbar) && empty($type) && empty($locat) && empty($day) && empty($zip)) {
			for($i=0;$i<count($l);$i++) {
				$w = $this->preg_accent($l[$i]);
				if(strlen($w) > 2) {
					//TABLE MIXED : French_city / Categorie / Type / Services
					//ADDED : Type.Nom // Categorie.Nom // Services.Nom // Services.Description // Ville.Nom
					$where .= ' OR (UPPER(`type`.`Name`) REGEXP "'.$w.'") OR (UPPER(`categories`.`Name`) REGEXP "'.$w.'") OR (UPPER(`services`.`Title`) REGEXP "'.$w.'") OR (UPPER(`services`.`Description`) REGEXP "'.$w.'") OR (UPPER(`french_city`.`Name`) REGEXP "'.$w.'")';
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "'.$w.'" THEN 1.5 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "'.$w.'" THEN 1 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP "'.$w.'" THEN 2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Description`) REGEXP "'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "'.$w.'" THEN 1.8 ELSE 0 END)';
					//EXTRA MATCH (FIRST CASE) : Ville.Nom  // Type.Nom // Categorie.Nom // Services.Nom
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "^'.$w.'" THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP "^'.$w.'" THEN 1.4 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "^'.$w.'" THEN 1.2 ELSE 0 END)';
					//EXTRA MATCH (MOT ENTIER) : Ville.Nom  // Type.Nom // Categorie.Nom // Services.Nom
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "^'.$w.'$" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "^'.$w.'$" THEN 0.7 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP "^'.$w.'$" THEN 1.7 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "^'.$w.'$" THEN 1.4 ELSE 0 END)';
					//IF NUMERIC : ADD ZIPCODE SEARCH
					if(is_numeric($w)) {
						$where .= ' OR (UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "^'.$w.'$" THEN 1.1 ELSE 0 END)';
					}
				}
			}
		} else {
			if(!empty($type)) {
				$where .= ' AND (`services`.`Type` = "'.$type.'")';	
			} else if(!empty($searchbar)) {
				$where .= ' AND (';
				for($i=0;$i<count($l);$i++) {
					$w = $this->preg_accent($l[$i]);
					if(strlen($w) > 2) {
						$where .= ' OR (UPPER(`type`.`Name`) REGEXP "'.$w.'") OR (UPPER(`categories`.`Name`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "'.$w.'" THEN 1.5 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "'.$w.'" THEN 1 ELSE 0 END) + (CASE WHEN UPPER(`type`.`Name`) REGEXP "^'.$w.'" THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`type`.`Name`) REGEXP "^'.$w.'$" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "^'.$w.'$" THEN 0.6 ELSE 0 END)';
					}
				}
				$where .= ' OR 0)';
			}
			if(!empty($day)) {
				$where .= ' AND (`services`.`Disponibility` REGEXP "'.$day.'"';
				if(preg_match("/lun|mar|mer|jeu|ven/", $day)) {
					$where .= ' OR `services`.`Disponibility` REGEXP "all"';
				} else {
					$where .= ' OR `services`.`Disponibilityy` REGEXP "weekend"';
				}
				$where .= ')';
			}
			if(!empty($locat)) {
				$allow = 1;
				if(preg_match("/[0-9]{5}/", $locat)) {
					//MATCH WITH LOCATION/DISTANCE --> ZipCode
					$locat_ = preg_replace("/[^0-9]/", "", $locat);
					$locat_ = substr($locat_, -5); //DANS LE CAS D'UNE ADRESSE COMPLETE RENSEIGNER
					$position = $this->get_locatZip($locat_, $user);
					if($position['lat'] != false && $position['lon'] != false) {
						$allow = 0;
					} else {
						$allow = 1;
					}
				} else {
					//MATCH WITH LOCATION/DISTANCE --> CityName
					//PUTTING SPACE BETWEEN LETTERS ANS NUMBERS
					$locat_ = strtoupper(trim(preg_replace("/ |\-|\'/", "|", preg_replace("/([0-9])([a-z|A-Z])/", "$1 $2", preg_replace("/([a-z|A-Z])([0-9])/", "$1 $2", $locat)))));
					$position = $this->get_locatCity($locat_, $user);
					if($position['lat'] != false && $position['lon'] != false) {
						$allow = 0;
					} else {
						$allow = 1;
					}
				}
				if($allow == 1) {
					$where .= ' AND (';
					$input_w = preg_replace("/ |\-|\'/", "{}" , $this->clean_w(strtoupper($locat)));
					$l_w = explode("{}", $input_w);
					for($i=0;$i<count($l_w);$i++) {
						$w = $this->preg_accent($l_w[$i]);
						if(strlen($w) > 2) {
							$where .= ' OR (UPPER(`french_city`.`Name`) REGEXP "'.$w.'")';
							$order .= ' + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "'.$w.'" THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "^'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "^'.$w.'$" THEN 1.3 ELSE 0 END)';
							if(is_numeric($w)) {
								$where .= ' OR (UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'")';
								$order .= ' + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "^'.$w.'$" THEN 0.8 ELSE 0 END)';
							}
						}
					}
					$where .= ' OR 0)';
				}
			}
			if(!empty($searchbar)) {
				$where .= ' OR (';
				for($i=0;$i<count($l);$i++) {
					$w = $this->preg_accent($l[$i]);
					if(strlen($w) > 2) {
						$where .= ' OR (UPPER(`services`.`Title`) REGEXP "'.$w.'") OR (UPPER(`services`.`Description`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`services`.`Title`) REGEXP "'.$w.'" THEN 2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Description`) REGEXP "'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP "^'.$w.'" THEN 1.4 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP "^'.$w.'$" THEN 1.6 ELSE 0 END)';
					}
				}
			}
			$where .= ' OR 0)';
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$where = preg_replace("/AND \( OR/", "AND (", $where);
		$where = preg_replace("/\( OR/", "(", $where);
		$where = preg_replace("/OR 0\) OR 0\)/", " OR 0)", $where);
		if(empty($order)) { $where = preg_replace("/OR 0\)/", "", $where); } else { $order = "(".$order.") DESC, "; }
		if(empty($where) && empty($order)) {
			$final = "<tr><td>Aucun résultat trouvé</td></tr>";
		} else {
			//MATCH WITHOUT LOCATION/DISTANCE
			$finalquery = "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`, `services`.`Image`, `services`.`Disponibility`, COUNT(*) AS `total` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE ".$where." GROUP BY `services`.`ID` ORDER BY ".$order." `services`.`Created` DESC LIMIT 0, 15";

			//MATCH WITH LOCATION/DISTANCE --> ZipCode
			if($position['lat'] != false && $position['lon'] != false) {
				//Location Finded
				$finalquery = "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility`, COUNT(*) AS `total`, 111.045* DEGREES(ACOS(COS(RADIANS(latpoint))
			 * COS(RADIANS(services.Lat))
			 * COS(RADIANS(longpoint) - RADIANS(services.Lon))
			 + SIN(RADIANS(latpoint))
			 * SIN(RADIANS(services.Lat)))) AS `distance_in_km` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` JOIN (
 SELECT  ".$position['lat']."  AS latpoint,  ".$position['lon']." AS longpoint
) AS p ON 1=1 WHERE ".$where." GROUP BY `ID` HAVING `distance_in_km` <= `services`.`Distance` ORDER BY distance_in_km ASC ,".$order." `services`.`Created` DESC LIMIT 0, 15";
			}
			$select = mysqli_query($this->mysql, $finalquery);
			while($data = mysqli_fetch_array($select)) {
				if(!empty($data['ID'])) {
					$sername = $data['SerName'];
					if(empty($sername)) { $sername = $data['TypName']; }
					$final .= '	<tr class="bloc_services">
									<td class="picto picto-'.$data['Type'].'"></td>
									<td class="desc_services"><a href="#">
										<h1>'.addslashes($sername).'</h1>
										<p>
											'.addslashes($data['Description']).'
										</p>
										<div class="location">'.addslashes($data['CityName']).'</div>
									</a></td>
								</tr>';
				}
			}
			if(empty($final)) {
				$final = "<tr><td class='center'>Aucun résultat trouvé</td></tr>";
			}
		}
		return $final;
	}
	function get_locatZip($zipcode, $user) {
		$return = array("lat" => false, "lon" => false);
		if($zipcode == $user->zipcode) {
			$return = array("lat" => $user->lat, "lon" => $user->lon);
		} else {
			$city = new city($this->mysql);
			$return = $city->getPositionDB($zipcode);
		}
		return $return;
	}
	function get_locatCity($cityn, $user) {
		$return = array("lat" => false, "lon" => false);
		$city = new city($this->mysql);
		$locat = $city->getLocationByName(strtoupper($cityn));
		echo $locat['name'];
		if($locat['lon'] != false) {
			echo "ok";
			if($locat['zipcode'] == $user->zipcode) {
				$return = array("lat" => $user->lat, "lon" => $user->lon);	
			} else {
				$return = array("lat" => $data['lat'], "lon" => $data['lon']);	
			}
		}
		return $return;
	}
	function searchcity($input) {
		$arr = array();
		$input = strtoupper($this->clean_w(trim($input)));
		$end_clause = $this->clause_searchcity($input);
		$select = mysqli_query($this->mysql, "SELECT `Real_Name`, `ZipCode`, `Lon`, `Lat` FROM `french_city` ".$end_clause);
		while($data = mysqli_fetch_array($select)) {
			$arr[] = array("label" => "".$data['ZipCode']." (".$data['Real_Name'].")", "zipCode" => $data['ZipCode'], "lon" => $data['Lon'], "lat" => $data['Lat']);	
		}
		return $arr;
	}
	function recent_services($user) {
		$final = array("", "");
		$nocity = false;
		if(!empty($user->zipcode)) {
			$final[0] = "Tous les services récents près de : ".$user->city;
			$select = mysqli_query($this->mysql, "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility`, COUNT(*) AS `total`, 111.045* DEGREES(ACOS(COS(RADIANS(latpoint))
			 * COS(RADIANS(services.Lat))
			 * COS(RADIANS(longpoint) - RADIANS(services.Lon))
			 + SIN(RADIANS(latpoint))
			 * SIN(RADIANS(services.Lat)))) AS `distance_in_km` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` JOIN (
 SELECT  ".$user->lat."  AS latpoint,  ".$user->lon." AS longpoint
) AS p ON 1=1 GROUP BY `ID` HAVING `distance_in_km` <= `services`.`Distance` ORDER BY `services`.`Created` DESC, distance_in_km ASC LIMIT 0, 15");
			$i = 0;
			while($data = mysqli_fetch_array($select)) {
				if(!empty($data['ID'])) {
					$sername = $data['SerName'];
					if(empty($sername)) { $sername = $data['TypName']; }
					$final[1] .= '	<tr class="bloc_services">
									<td class="picto picto-'.$data['Type'].'"></td>
									<td class="desc_services"><a href="#">
										<h1>'.addslashes($sername).'</h1>
										<p>
											'.addslashes($data['Description']).'
										</p>
										<div class="location">'.addslashes($data['CityName']).'</div>
									</a></td>
								</tr>';
						$i++;
					}
				}
				if($i == 0) {
					$final[0] = "Tous les services récents en France";
					$nocity = true;	
				}
			} else {
				$nocity = true;	
				$final[0] = "Tous les services récents en France";
			}
			if($nocity == true) {
				$select = mysqli_query($this->mysql, "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility`, COUNT(*) AS `total` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` GROUP BY `ID` ORDER BY `services`.`Created` DESC LIMIT 0, 15");
				$i = 0;
				while($data = mysqli_fetch_array($select)) {
					if(!empty($data['ID'])) {
						$sername = $data['SerName'];
						if(empty($sername)) { $sername = $data['TypName']; }
						$final[1] .= '	<tr class="bloc_services">
										<td class="picto picto-'.$data['Type'].'"></td>
										<td class="desc_services"><a href="#">
											<h1>'.addslashes($sername).'</h1>
											<p>
												'.addslashes($data['Description']).'
											</p>
											<div class="location">'.addslashes($data['CityName']).'</div>
										</a></td>
									</tr>';
						$i++;
					}
				}
			}
			if(empty($final)) {
				$final[1] = "<tr><td>Aucun résultat trouvé</td></tr>";
			}
			return $final;
	}
}
?>