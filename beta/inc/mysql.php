<?php 
if(preg_match("/localhost|127\.0\.0\.1/", $_SERVER['HTTP_HOST'])) {
	//FOR MAMP
	$mysql = mysqli_connect("localhost","root","root","swappyfraa0") or die("Error MySQL Connect..."); 
} else {
	$mysql = mysqli_connect("swappyfraa0.mysql.db","swappyfraa0","2Dside77","swappyfraa0") or die("Error " . mysqli_error($mysql)); 
}
mysqli_query($mysql, "SET NAMES 'utf8'");  ?>