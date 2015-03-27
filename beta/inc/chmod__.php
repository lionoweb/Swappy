<?php
//Verification et modifications des chmods
$folder = preg_match("/\/inc/", $_SERVER['REQUEST_URI']) ? "../" : "./";
$folder_l = array("img/user/upload/", "img/social/google/user", "img/social/facebook/user", "img/social/twitter/user");
$okay = 0;
foreach($folder_l as $key => $value) {
	$chmod = substr(sprintf('%o', fileperms($folder.$value)), -4);
	if($chmod == "0777") {
		$okay++;
	} else {
		if(chmod($folder.$value, 0777)) {
			$okay++;
		} else {
		
		}
	}
} 
//Modification du fichier config
if($okay >= count($folder_l)) {
	$file = @file_get_contents($folder."inc/config.php"); 
	$file = preg_replace('/\s\/\/AJOUT DU CHMOD AU DOSSIER D\'UPLOAD \(CE CODE EST EFFACE AUTOMATIQUEMENT UNE FOIS LA MODIFICATION DES CHMOD FAITE\)\s/i', "", $file);
	$file = preg_replace('/\s\@include\(\(preg\_match\(\"\/\\\\\/inc\/\"\,\$\_SERVER\[\'REQUEST_URI\'\]\) \? \"\" \: \"inc\/\"\)\.\"chmod\_\_\.php\"\)\;\s/i', '', $file);
	@file_put_contents($folder."inc/config.php", $file);
	unlink($folder."inc/chmod__.php");
}
?>