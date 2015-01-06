<?php 
require_once("inc/user.php");
require_once("inc/mysql.php");
$user = new user($mysql);
if(isset($_GET['logout'])) {
	$user->logout();
}	?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="css/template.css" type="text/css"/>
<link rel="stylesheet" href="css/main.css">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
<script src="js/ValidationEngine/jquery.validationEngine.js"></script>
<script src="js/main.js"></script>
</head>

<body>
<?php if(!$user->logged) {?>
<form action="inc/login.php" method="post" id="login_form">
	<span class="input-field">
		<label for="login_form">Login :</label>
        <input type="text" id="login_form" name="login_form" class="validate[required,minSize[5]]">
    </span>
    <span class="input-field">
		<label for="password_form">Mot de passe :</label>
        <input type="password" id="password_form" name="password_form" class="validate[required,minSize[6]]">
    </span>
    <input type="submit" value="Se connecter">
</form>
<?php } else { 
	echo "Bonjour ".$user->firstname." ".$user->lastname." <a href='?logout'>Se deconnecter</a>"; ?>
    <br><br>
<?php
require("inc/services.php");
$services = new services($mysql);
echo $services->list_categories();
 } ?>
</body>
</html>