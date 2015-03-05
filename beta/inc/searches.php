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
		  if(preg_match("/E|É|È|Ê|Ë|e|é|è|ê|ë/", $w)) {
			  $w = preg_replace("/E|É|È|Ê|Ë|e|é|è|ê|ë/","(E|É|È|Ê|Ë)", $w);	
		  }
		  if(preg_match("/A|À|Á|Â|Ä|a|à|á|â|ä/", $w)) {
			  $w = preg_replace("/A|À|Á|Â|Ä|a|à|á|â|ä/","(A|À|Á|Â|Ä)", $w);	
		  }
		  if(preg_match("/C|Ç|c|ç/", $w)) {
			  $w = preg_replace("/C|Ç|c|ç/","(C|Ç)", $w);	
		  }
		  return $w;
	  }
	function clean_w($input) {
		return preg_replace("/\(|\)/", "", $input);	
	}
	function clause_searchbar($input) {
		$replace = array();
		$out = '';
		$where = '';
		$order = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $input);
		$l = explode("{}", $input);
		for($i=0;$i<count($l);$i++) {
			$prpn = ":value".$i;
			$w = $this->preg_accent($l[$i]);
			if(strlen($w) > 1) {
				$replace[$prpn] = $w;
				$where .= ' OR (UPPER(`type`.`Name`) REGEXP '.$prpn.') OR (UPPER(`categories`.`Name`) REGEXP '.$prpn.')';
				$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.' THEN 1 ELSE 0 END)';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.' GROUP BY `type`.`ID`  ORDER BY '.$order. ' DESC, `type`.`Name` DESC LIMIT 0, 5';
		return array($out, $replace);
	}
	function clause_searchcity($input) {
		$replace = array();
		$out = '';
		$where = '';
		$order = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $input);
		$l = explode("{}", $input);
		for($i=0;$i<count($l);$i++) {
			$prpn = ":value".$i;
			$w = $this->preg_accent($l[$i]);
			if(is_numeric($w)) {
				//NORMAL
				$replace[$prpn] = $w;
				//FIRST CASE
				$replace[$prpn."f"] = "^".$w;
				//ENTIER
				$replace[$prpn."l"] = "^".$w."$";
				$where .= ' OR (`ZipCode` REGEXP '.$prpn.')';
				$order .= ' + (CASE WHEN `ZipCode` REGEXP '.$prpn.' THEN 1 ELSE 0 END) + (CASE WHEN `ZipCode` REGEXP '.$prpn.'f THEN 1.5 ELSE 0 END) + (CASE WHEN `ZipCode` REGEXP '.$prpn.'l THEN 1.7 ELSE 0 END)';
			} else if(strlen($w) > 1) {
				//NORMAL
				$replace[$prpn] = $w;
				//FIRST CASE
				$replace[$prpn."f"] = "^".$w;
				//ENTIER
				$replace[$prpn."l"] = "^".$w."$";
				$where .= ' OR (`Real_Name` REGEXP '.$prpn.')';
				$order .= ' + (CASE WHEN `Real_Name` REGEXP '.$prpn.' THEN 1 ELSE 0 END) + (CASE WHEN `Real_Name` REGEXP '.$prpn.'f THEN 1.5 ELSE 0 END) + (CASE WHEN `Real_Name` REGEXP '.$prpn.'l THEN 1.5 ELSE 0 END)';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.' GROUP BY `ID` ORDER BY '.$order.' DESC LIMIT 0, 5';
		return array($out, $replace);
	}
	function searchbar($input) {
		$replace = array();
		$arr = array();
		$input = $this->clean_w(strtoupper(trim($input)));
		$end_clause = $this->clause_searchbar($input);
		$select = $this->mysql->prepare("SELECT `type`.`Name` AS `Name_t`, `type`.`ID` AS `ID`, `categories`.`Name` AS `CatName` FROM `type` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` ".$end_clause[0]);
		$select->execute($end_clause[1]);
		while($data = $select->fetch(PDO::FETCH_OBJ)) {
			$arr[] = array("label" => "Service : ".$data->Name_t." (".$data->CatName.")", "val" => $data->ID);	
		}
		$selecte = $this->mysql->prepare("SELECT `Login`, `ID` FROM `users` WHERE UPPER(`Login`) LIKE :input LIMIT 0, 1");
		$selecte->execute(array(":input" => $input."%"));
		while($datae = $selecte->fetch(PDO::FETCH_OBJ)) {
			$arr[] = array("label" => "Utilisateur : ".$datae->Login, "userID" => $datae->ID);	
		}
		return $arr;
	}
	function search($GET, $user) {
		$replace = array();
		$searchbar = $type = $locat = $day = $zip = $input = $where = $order = $final = $type_c = "";
		$page = @$GET['p'];
		if(empty($page)) {
			$page = 1;	
		}
		$query_s = $_SERVER['QUERY_STRING'];
		$limit = 15;
		$start = ($page * $limit) - $limit;
		$end = $start + $limit;
		$pagination = "";
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
				$prpn = ":value".$i;
				$w = $this->preg_accent($l[$i]);
				if(strlen($w) > 2) {
					//NORMAL
					$replace[$prpn] = $w;
					//FIRST CASE
					$replace[$prpn."f"] = "^".$w;
					//ENTIER
					$replace[$prpn."l"] = "^".$w."$";
					//TABLE MIXED : French_city / Categorie / Type / Services
					//ADDED : Type.Nom // Categorie.Nom // Services.Nom // Services.Description // Ville.Nom
					$where .= ' OR (UPPER(`type`.`Name`) REGEXP '.$prpn.') OR (UPPER(`categories`.`Name`) REGEXP '.$prpn.') OR (UPPER(`services`.`Title`) REGEXP '.$prpn.') OR (UPPER(`services`.`Description`) REGEXP '.$prpn.') OR (UPPER(`french_city`.`Name`) REGEXP '.$prpn.')';
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.' THEN 1.5 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.' THEN 1 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.' THEN 2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Description`) REGEXP '.$prpn.' THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.' THEN 1.8 ELSE 0 END)';
					//EXTRA MATCH (FIRST CASE) : Ville.Nom  // Type.Nom // Categorie.Nom // Services.Nom
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.'f THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.'f THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.'f THEN 1.4 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.'f THEN 1.2 ELSE 0 END)';
					//EXTRA MATCH (MOT ENTIER) : Ville.Nom  // Type.Nom // Categorie.Nom // Services.Nom
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.'l THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.'l THEN 0.7 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.'l THEN 1.7 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.'l THEN 1.4 ELSE 0 END)';
					//IF NUMERIC : ADD ZIPCODE SEARCH
					if(is_numeric($w)) {
						$where .= ' OR (UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.')';
						$order .= ' + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.'f THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.'l THEN 1.1 ELSE 0 END)';
					}
				}
			}
		} else {
			if(!empty($type)) {
				$type_c = ' AND (`services`.`Type` = :type0)';	
				$replace[":type0"] = $type;
			} else if(!empty($searchbar)) {
				$where .= ' AND (';
				for($i=0;$i<count($l);$i++) {
					$prpn = ":valuea".$i;
					$w = $this->preg_accent($l[$i]);
					if(strlen($w) > 2) {
						//NORMAL
						$replace[$prpn] = $w;
						//FIRST CASE
						$replace[$prpn."f"] = "^".$w;
						//ENTIER
						$replace[$prpn."l"] = "^".$w."$";
						$where .= ' OR (UPPER(`type`.`Name`) REGEXP '.$prpn.') OR (UPPER(`categories`.`Name`) REGEXP '.$prpn.')';
						$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.' THEN 1.5 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.' THEN 1 ELSE 0 END) + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.'f THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.'f THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.'l THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP '.$prpn.'l THEN 0.6 ELSE 0 END)';
					}
				}
				$where .= ' OR 0)';
			}
			if(!empty($day)) {
				$where .= ' AND (`services`.`Disponibility` REGEXP :day0';
				$replace[":day0"] = $day;
				if(preg_match("/lun|mar|mer|jeu|ven/", $day)) {
					$where .= ' OR `services`.`Disponibility` REGEXP "all"';
				} else if(preg_match("/sam|dim/", $day)) {
					$where .= ' OR `services`.`Disponibilityy` REGEXP "weekend"';
				} else if($day == "all") {
					$where .= ' OR `services`.`Disponibility` REGEXP "lun"  OR `services`.`Disponibility` REGEXP "mar"  OR `services`.`Disponibility` REGEXP "mer"  OR `services`.`Disponibility` REGEXP "jeu"  OR `services`.`Disponibility` REGEXP "ven"  OR `services`.`Disponibility` REGEXP "sam"  OR `services`.`Disponibility` REGEXP "dim"';
				} else if($day == "weekend") {
					$where .= ' OR `services`.`Disponibility` REGEXP "sam" OR `services`.`Disponibility` REGEXP "dim"';
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
						$prpn = ":valuee".$i;
						$w = $this->preg_accent($l_w[$i]);
						if(strlen($w) > 2) {
							//NORMAL
							$replace[$prpn] = $w;
							//FIRST CASE
							$replace[$prpn."f"] = "^".$w;
							//ENTIER
							$replace[$prpn."l"] = "^".$w."$";
							$where .= ' OR (UPPER(`french_city`.`Name`) REGEXP '.$prpn.')';
							$order .= ' + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.' THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.'f THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP '.$prpn.'l THEN 1.3 ELSE 0 END)';
							if(is_numeric($w)) {
								//NORMAL
								$replace[$prpn] = $w;
								//FIRST CASE
								$replace[$prpn."f"] = "^".$w;
								//ENTIER
								$replace[$prpn."l"] = "^".$w."$";
								$where .= ' OR (UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.')';
								$order .= ' + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.'f THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP '.$prpn.'l THEN 0.8 ELSE 0 END)';
							}
						}
					}
					$where .= ' OR 0)';
				}
			}
			if(!empty($searchbar)) {
				$where .= ' OR (';
				for($i=0;$i<count($l);$i++) {
					$prpn = ":valuei".$i;
					$w = $this->preg_accent($l[$i]);
					if(strlen($w) > 2) {
						//NORMAL
						$replace[$prpn] = $w;
						//FIRST CASE
						$replace[$prpn."f"] = "^".$w;
						//ENTIER
						$replace[$prpn."l"] = "^".$w."$";
						$where .= ' OR (UPPER(`services`.`Title`) REGEXP '.$prpn.') OR (UPPER(`services`.`Description`) REGEXP '.$prpn.')';
						$order .= ' + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.' THEN 2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Description`) REGEXP '.$prpn.' THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.'f THEN 1.4 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.'l THEN 1.6 ELSE 0 END)';
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
			if(!empty($type_c)) {
				$where = "(".$where.") ".$type_c;
			}
			//MATCH WITHOUT LOCATION/DISTANCE
			$finalquery = "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `categories`.`ID` AS `CatID`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`, `services`.`Image`, `services`.`Disponibility` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE ".$where." GROUP BY `services`.`ID` ORDER BY ".$order." `services`.`Created` DESC";

			//MATCH WITH LOCATION/DISTANCE --> ZipCode
			if($position['lat'] != false && $position['lon'] != false) {
				//Location Finded
				$finalquery = "SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `categories`.`ID` AS `CatID`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility`,  111.045* DEGREES(ACOS(COS(RADIANS(latpoint))
			 * COS(RADIANS(services.Lat))
			 * COS(RADIANS(longpoint) - RADIANS(services.Lon))
			 + SIN(RADIANS(latpoint))
			 * SIN(RADIANS(services.Lat)))) AS `distance_in_km` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` JOIN (
 SELECT  :lat0  AS latpoint, :lon0 AS longpoint
) AS p ON 1=1 WHERE ".$where." GROUP BY `ID` HAVING `distance_in_km` <= (`services`.`Distance` + 1) ORDER BY ".$order." distance_in_km ASC , `services`.`Created` DESC";
				$replace[":lat0"] = $position['lat'];
				$replace[":lon0"] = $position['lon'];
			}
			$select = $this->mysql->prepare($finalquery);
			$select->execute($replace);
			$total = $select->rowCount();
			$restant = $total - $end;
			$count_line = 0;
			$data = $select->fetchAll(PDO::FETCH_CLASS);
			$ist = $start;
			while($ist < $end) {
				if(!empty($data[$ist]->ID)) {
					$sername = $data[$ist]->SerName;
					if(empty($sername)) { $sername = $data[$ist]->TypName; }
					$final .= '	<tr class="bloc_services">
									<td class="picto"><a href="annonce.php?id='.$data[$ist]->ID.'"><img class="fullfit" src="img/services/'.$data[$ist]->CatID.'.jpg"></a></td>
									<td class="desc_services"><a href="annonce.php?id='.$data[$ist]->ID.'&r='.base64_encode($query_s).'"><div class="fullfit">
										<h1>'.ucfirst($sername).'</h1>
										<p>
											'.ucfirst($data[$ist]->Description).'
										</p>
										<div class="location">'.$data[$ist]->CityName.'</div></div>
									</a></td>
								</tr>';
					$count_line++;
				}
				$ist++;
			}
			if($total > $limit) {
				$prev = $next = $l_next = $l_prev = "";
				$pages_list = '';
				$nb_page = $total / $limit;
				if($nb_page > 1) {
					$query_sp = preg_replace("/\&$/", "", preg_replace("/p\=(.*?)\&|p\=(.*?)$/", "", $query_s));
					if($page == 1) {
						$prev = ' class="disabled"';	
					} else {
						$l_prev = ' href="?'.$query_sp.'&p='.($page-1).'"';
					}
					if($page >= $nb_page) {
						$next = ' class="disabled"';	
					} else {
						$l_next = ' href="?'.$query_sp.'&p='.($page+1).'"';
					}
					for($o=0;$o<$nb_page;$o++) {
						$pp = $o + 1;
						if($pp == $page) {
							$pages_list .= '<li class="active"><a href="?'.$query_sp.'&p='.$pp.'">'.$pp.' <span class="sr-only">(current)</span></a></li>';
						} else {
							$pages_list .= '<li><a href="?'.$query_sp.'&p='.$pp.'">'.$pp.'</a></li>';
						}
					}
					$pagination = '<nav id="pagination">
  <ul class="pagination">
    <li'.$prev.'>
      <a'.$l_prev.' aria-label="Precedent">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    '.$pages_list.'
    <li'.$next.'>
      <a'.$l_next.' aria-label="Suivant">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>';	
				}
			}
			if(empty($final)) {
				if(empty($searchbar) && empty($locat) && empty($day) && empty($type) && empty($zip)) {
					$final = "<tr><td class='center'>Pour une recherche optimale, veuillez remplir au moins un champ.</td></tr>";
				} else {
					$final = "<tr><td class='center'>Aucun résultat trouvé</td></tr>";
				}
			}
		}
		return array($final, $pagination);
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
		$locat = $city->getLocationByName(preg_replace("/ |\-|\'/", "|" , strtoupper($cityn)));
		if($locat['Lon'] != false) {
			if($locat['ZipCode'] == $user->zipcode) {
				$return = array("lat" => $user->lat, "lon" => $user->lon);	
			} else {
				$return = array("lat" => $locat['Lat'], "lon" => $locat['Lon']);	
			}
		}
		return $return;
	}
	function searchcity($input) {
		$arr = array();
		$input = strtoupper($this->clean_w(trim($input)));
		$end_clause = $this->clause_searchcity($input);
		$select = $this->mysql->prepare("SELECT `Real_Name`, `ZipCode`, `Lon`, `Lat` FROM `french_city` ".$end_clause[0]);
		$select->execute($end_clause[1]);
		while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$arr[] = array("label" => "".$data->ZipCode." (".$data->Real_Name.")", "zipCode" => $data->ZipCode, "lon" => $data->Lon, "lat" => $data->Lat);
		}
		return $arr;
	}
	function recent_services($user) {
		$final = array("", "");
		$nocity = false;
		if(!empty($user->zipcode)) {
			$final[0] = "Tous les services récents près de : ".$user->city;
			$select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`Name` AS `CatName`, `categories`.`ID` AS `CatID`, `services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility`, 111.045* DEGREES(ACOS(COS(RADIANS(latpoint))
			 * COS(RADIANS(services.Lat))
			 * COS(RADIANS(longpoint) - RADIANS(services.Lon))
			 + SIN(RADIANS(latpoint))
			 * SIN(RADIANS(services.Lat)))) AS `distance_in_km` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` JOIN (
 SELECT  :lat  AS latpoint,  :lon AS longpoint
) AS p ON 1=1 GROUP BY `ID` HAVING `distance_in_km` <= (`services`.`Distance` + 1) ORDER BY `services`.`Created` DESC, distance_in_km ASC LIMIT 0, 15");
			$select->execute(array(":lat" => $user->lat, ":lon" => $user->lon));
			$i = 0;
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				if(!empty($data->ID)) {
					$sername = $data->SerName;
					if(empty($sername)) { $sername = $data->TypName; }
					$final[1] .= '	<tr class="bloc_services">
									<td class="picto"><a href="annonce.php?id='.$data->ID.'"><img class="fullfit" src="img/services/'.$data->CatID.'.jpg"></a></td>
									<td class="desc_services"><a href="annonce.php?id='.$data->ID.'"><div class="fullfit">
										<h1>'.ucfirst($sername).'</h1>
										<p>
											'.ucfirst($data->Description).'
										</p>
										<div class="location">'.$data->CityName.'</div></div>
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
				$select = $this->mysql->query("SELECT `services`.`ID`, `categories`.`Name` AS `CatName`,  `categories`.`ID` AS `CatID`,`services`.`Title` AS `SerName`, `french_city`.`Real_Name` AS `CityName`, `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name` AS `TypName`, `services`.`Description`,  `services`.`Lat`, `services`.`Lon` , `services`.`Image`, `services`.`Disponibility` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` GROUP BY `ID` ORDER BY `services`.`Created` DESC LIMIT 0, 15");
				$i = 0;
				while($data = $select->fetch(PDO::FETCH_OBJ)) {
					if(!empty($data->ID)) {
						$sername = $data->SerName;
						if(empty($sername)) { $sername = $data->TypName; }
						$final[1] .= '	<tr class="bloc_services">
										<td class="picto"><a href="annonce.php?id='.$data->ID.'"><img class="fullfit" src="img/services/'.$data->CatID.'.jpg"></a></td>
										<td class="desc_services"><a href="annonce.php?id='.$data->ID.'"><div class="fullfit">
											<h1>'.ucfirst($sername).'</h1>
											<p>
												'.ucfirst($data->Description).'
											</p>
											<div class="location">'.$data->CityName.'</div></div>
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