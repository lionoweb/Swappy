<?php 
//CONNEXION BDD
//ini_set('display_errors', 1);
if(preg_match("/localhost|127\.0\.0\.1/", $_SERVER['HTTP_HOST'])) {
	//FOR MAMP
	$mysql = new PDO("mysql:host=localhost; dbname=swappyfraa0", "root", "root");
} else {
	$mysql = new PDO("mysql:host=swappyfraa0.mysql.db; dbname=swappyfraa0", "swappyfraa0", "2Dside77");
}
$mysql->query("SET NAMES 'utf8'");  ?>