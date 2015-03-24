<?php
//AFFICHAGE DES META TAG DE REFERENCEMENT
function in_array_r($reg, $arr) {
	$return = false;
	foreach($arr as $key => $value) {
		if(preg_match($reg, trim($value))) {
			$return = true;
			break;
		}
	}
	return $return;
}
function stripAccents($str){
	$str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
	$str = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	$str = preg_replace('#&[^;]+;#', '', $str);
	return $str;
}
function meta_tag($img, $description_g="", $description="", $title_page, $more_tags="") {
$description_g = preg_replace('/[\s]+/', ' ',  $description_g);
$description = preg_replace("/[\s]+/", " ", $description);
$folder = 'beta/';
 $tag = 'absences, vacances, arroser, jardin, effectuer, rondes, nourrir, animaux, aide, menagere, nettoyage, repassage, garde, promenade, toilettage, automobile, changement, pieces,  reparations, vidange, accompagnement, ecole, activite, enfant, bricolage, monter, meuble, travaux, manuels, reparations, coaching, conseils, cuisine, decoration, jeux, sport, conseils, cours, particuliers, langues, matieres, scientifiques, musique, courses, demarches, administratives, faire, demenagement, festivites, preparation , fete, animation, musicale, bar, restauration, informatique, assistance, maintenance, reparation, redaction, documents, soins, beaute, coiffure, epilation, manucure, massage, entretien, habitat, electricite, jardinage, maconnerie, peinture, plomberie, swappy, echange, gratuit, services, sel, bricolage, baby-sitting, troc, partage, entraide, communaute, utilisateur, swappeur, non-lucratif, generosite, entre-aide, amitie, temps, rencontre, competences, particuliers, entraide, annonce, garder';
if(!empty($more_tags)) {
	$more = preg_split("/( |\'|\,|\.|\-|[\s]|[\s+])/", strtolower(stripAccents(trim($more_tags))));
	$al = preg_split("/\, /", $tag);
	foreach($more as $key => $value) {
		if(strlen($value) > 2) {
			if(!in_array_r("/".preg_replace("/(.*?)s$/", "$1", trim($value))."($|s$)/", $al)) {
				$tag .= ', '.trim($value);
			}
		}
	}
}
if(empty($img)) {
	$img="image.jpg";
}
if(preg_match("/\//", $img)) {
	$base = basename($img);
	$dir = dirname($img);
	$makef = 0;
	if($dir == "img/user/upload") {
		$dir = "img/social/{TYPE}/user/";
		$makef = 1;
	}
	$list = array("facebook", "google", "twitter");
	if($makef == 1) {
		for($i=0;$i<count($list);$i++) {
			$file = preg_replace("/\{TYPE\}/", $list[$i], $dir.$base);
			$im = $img_ = "";
			if(!file_exists($file)) {
				$im = imagecreatefromstring(file_get_contents($img));
				if ($im !== false) {
					if($list[$i] == "google") {
						$w = $h = 190;
					}
					if($list[$i] == "facebook") {
						$w = $h = 210;
					}
					if($list[$i] == "twitter") {
						$w = $h = 180;
					}
					$img_ = imagecreatetruecolor($w,$h);
					imagecopyresampled($img_,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
					imagejpeg($img_,$file,100); 
					imagedestroy($img_);
				}
			}
			${substr($list[$i],0,1)."img"} = "http://swappy.fr/".$folder.$file;
		}
	} else {
		$gimg = "http://swappy.fr/".$folder."img/social/google/".basename($img)."";
		$fimg = "http://swappy.fr/".$folder."img/social/facebook/".basename($img)."";
		$timg = "http://swappy.fr/".$folder."img/social/twitter/".basename($img)."";
	}
	
} else {
	$gimg = "http://swappy.fr/".$folder."img/social/google/".$img."";
	$fimg = "http://swappy.fr/".$folder."img/social/facebook/".$img."";
	$timg = "http://swappy.fr/".$folder."img/social/twitter/".$img."";
}
if(empty($title_page)) {
	$title_page = "Échanges de services gratuits entre particuliers";
}
if(empty($description_g)) {
	$description_g = 'Swappy, la plateforme d’échanges de services gratuits entre particuliers ! Proposez/recherchez des services sur ce nouveau site d\'annonces dédié à l\'entraide.';	
} 
if(empty($description)) {
	$description = $description_g;	
}
$ogurl = basename($_SERVER['PHP_SELF']);
if($ogurl == "annonce.php" || $ogurl == "profil.php") {
	if(isset($_GET['id'])) {
		$ogurl = preg_replace("/\.php/", "-".$_GET['id'].".php", $ogurl);
	}
}
$html = '<meta name="description" content="'.$description_g.'">
	<meta name="keywords" content="'.$tag.'">
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="Swappy.fr - '.$title_page.'">
	<meta itemprop="description" content="'.$description_g.'">
	<meta itemprop="image" content="'.$gimg.'">
	<!-- Twitter Card data -->
	<meta name="twitter:card" content="summary">
	<meta name="twitter:url" content="http://swappy.fr/'.$folder.'">
	<meta name="twitter:site" content="@_Swappy">
	<meta name="twitter:title" content="Swappy.fr - '.$title_page.'">
	<meta name="twitter:description" content="'.$description_g.'">
	<meta name="twitter:image" content="'.$timg.'">
	<!-- Open Graph data -->
	<meta property="og:locale" content="fr_FR">
	<meta property="og:title" content="Swappy.fr - '.$title_page.'">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://swappy.fr/'.$folder.$ogurl.'">
	<meta property="og:image" content="'.$fimg.'">
	<meta property="og:description" content="'.$description_g.'">
	<meta property="og:site_name" content="Swappy.fr - '.$title_page.'">
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
';
	return $html;
}