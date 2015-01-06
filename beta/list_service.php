<?php 
require_once("inc/user.php");
require_once("inc/mysql.php");
require_once("inc/services.php");
$services = new services($mysql);
$user = new user($mysql);
$user->onlyUsers();
if(isset($_GET['logout'])) {
	$user->logout();
}	?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Liste des services</title>
</head>

<body>
<?php 
$select = mysqli_query($mysql, "SELECT * FROM `services` WHERE `BY` = '".$user->ID."'");
while($data = mysqli_fetch_array($select)) {
	echo $services->type_name($data['Type'])."<br> Desc : ".$data['Description']."<br> Distance : ".$data['Distance']."km<br> Disponibilité : ".$services->dispo_uncrypt($data['Disponibility'])."<hr>";	
}
?>
</body>
</html>