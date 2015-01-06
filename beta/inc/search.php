<?php
require("mysql.php");
require("searches.php");
$search = new search($mysql);
if(isset($_GET['searchquery'])) {
	$val = $search->searchbar($_GET['searchquery']);	
	echo $_GET['callback']."(".json_encode($val).")";
}
if(isset($_GET['zipquery'])) {
	$val = $search->searchcity($_GET['zipquery']);	
	echo $_GET['callback']."(".json_encode($val).")";
}
?>