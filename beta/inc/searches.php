<?php
class search {
	private $mysql;
	function __construct($mysql) {
		$this->mysql = $mysql;
	}
	function clearcity_match($input) {
		$out_t = '';
		$out_n = '';
		$input = preg_replace("/ |\-|\'/", "{}" , $input);
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
				$order .= ' + (UPPER(`type`.`Name`) REGEXP "'.$w.'") + (UPPER(`categories`.`Name`) REGEXP "'.$w.'")';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.'  ORDER BY '.$order. ' DESC, `type`.`Name` DESC LIMIT 0, 5';
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
				$order .= ' + (`ZipCode` REGEXP "'.$w.'") + (`ZipCode` REGEXP "^'.$w.'")';
			} else {
				$where .= ' OR (`Real_Name` REGEXP "'.$w.'")';
				$order .= ' + (`Real_Name` REGEXP "'.$w.'") + (`Real_Name` REGEXP "^'.$w.'")';
			}
		}
		if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
		if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
		$out = 'WHERE '.$where.' ORDER BY '.$order. ' DESC LIMIT 0, 5';
		return $out;
	}
	function searchbar($input) {
		$arr = array();
		$input = strtoupper(trim($input));
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
	function searchcity($input) {
		$arr = array();
		$input = strtoupper(trim($input));
		$end_clause = $this->clause_searchcity($input);
		$select = mysqli_query($this->mysql, "SELECT `Real_Name`, `ZipCode`, `Lon`, `Lat` FROM `french_city` ".$end_clause);
		while($data = mysqli_fetch_array($select)) {
			$arr[] = array("label" => "".$data['ZipCode']." (".$data['Real_Name'].")", "lon" => $data['Lon'], "lat" => $data['Lat']);	
		}
		return $arr;
	}
}
?>