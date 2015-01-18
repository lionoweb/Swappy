<?php $mysql = mysqli_connect("localhost","root","","swappy") or die("Error " . mysqli_error($mysql));  
mysqli_query($mysql, "SET NAMES 'utf8'");  ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Document sans titre</title>
</head>

<body>
<?php
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
			$input = preg_replace("/ |\-|\'/", "{}" , strtoupper($GET['searchbar']));
			$l = explode("{}", $input);
			for($i=0;$i<count($l);$i++) {
				$w = preg_accent($l[$i]);
				if(strlen($w) > 2) {
					//TABLE MIXED : French_city / Categorie / Type / Services
					//ADDED : Type.Nom // Categorie.Nom // Services.Nom // Services.Description // Ville.Nom
					$where .= ' OR (UPPER(`type`.`Name`) REGEXP "'.$w.'") OR (UPPER(`categories`.`Name`) REGEXP "'.$w.'") OR (UPPER(`services`.`Name`) REGEXP "'.$w.'") OR (UPPER(`services`.`Description`) REGEXP "'.$w.'") OR (UPPER(`french_city`.`Name`) REGEXP "'.$w.'")';
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "'.$w.'" THEN 1.5 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "'.$w.'" THEN 1 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Name`) REGEXP "'.$w.'" THEN 2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Description`) REGEXP "'.$w.'" THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "'.$w.'" THEN 1.8 ELSE 0 END)';
					//EXTRA MATCH (FIRST CASE) : Ville.Nom // Ville.CodePostal // Type.Nom // Categorie.Nom // Services.Nom
					$order .= ' + (CASE WHEN UPPER(`type`.`Name`) REGEXP "^'.$w.'" THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`categories`.`Name`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Name`) REGEXP "^'.$w.'" THEN 1.4 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`Name`) REGEXP "^'.$w.'" THEN 1.2 ELSE 0 END)';
					//IF NUMERIC : ADD ZIPCODE SEARCH
					if(is_numeric($w)) {
						$where .= ' OR (UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'")';
						$order .= ' + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "'.$w.'" THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`french_city`.`ZipCode`) REGEXP "^'.$w.'" THEN 0.5 ELSE 0 END)';
					}
				}
			}
			if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
			if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
			$finalquery = "SELECT `services`.`ID`, `categories`.`Name`, `services`.`Name`, `french_city`.`Name` , `services`.`City`, `services`.`Distance`, `services`.`By`, `services`.`Type`, `type`.`Name`, `services`.`Description`, `services`.`Image`, `services`.`Disponibility` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE ".$where." ORDER BY ".$order." DESC LIMIT 0, 15";
		}
		return $finalquery;
	}
		$GET['searchbar'] = "jardin gratuit bi";
		/*$select = mysqli_query($mysql, search($GET, ''));
		while($data = mysqli_fetch_array($select)) {
			print_r($data);
		};*/
		echo search($GET, '');
?>
</body>
</html>