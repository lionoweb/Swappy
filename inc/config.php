<?php 
//SESSION START
session_start();
//MYSQL VARIABLE
define("HOST_PROD","swappyfraa0.mysql.db");
define("DBNAME_PROD","swappyfraa0");
define("USER_PROD","swappyfraa0");
define("PASSWORD_PROD","2Dside77");
//FOR LOCAL
define("HOST_DEV","localhost");
define("DBNAME_DEV","swappyfraa0");
define("USER_DEV","root");
define("PASSWORD_DEV","root");
//DISABLE SEND MAIL IF LOCAL DOESNT SUPPORT
define("DISABLE_MAIL", false);
//WEBSITE VARIABLE
define("URL_SITE",'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/");
define("FOLDER_", preg_replace("/([a-zA-Z0-9])$/", "$1/", preg_replace("/^\/|inc|\/$/", "", (preg_match("/\./", $_SERVER['REQUEST_URI']) ? dirname($_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'])."/")));
//CONNEXION BDD
if(preg_match("/localhost|127\.0\.0\.1/", $_SERVER['HTTP_HOST'])) {
	//FOR LOCAL
	$mysql = new PDO("mysql:host=".HOST_DEV."; dbname=".DBNAME_DEV, USER_DEV, 
	PASSWORD_DEV);
} else {
	$mysql = new PDO("mysql:host=".HOST_PROD."; dbname=".DBNAME_PROD, USER_PROD, 
	PASSWORD_PROD);
}
$mysql->query("SET NAMES 'utf8'"); 
//HIDE ERROR
ini_set('display_errors', 1);
//OVERRIDE EMPTY FUNCTION
function empty_($val){
	if(!is_array($val)) {
    	return preg_match('/^[\s]*$/', $val);
	} else {
		return empty($val);	
	}
}

//AJOUT DU CHMOD AU DOSSIER D'UPLOAD (CE CODE EST EFFACE AUTOMATIQUEMENT UNE FOIS LA MODIFICATION DES CHMOD FAITE)
@include((preg_match("/\/inc/",$_SERVER['REQUEST_URI']) ? "" : "inc/")."chmod__.php");
?>