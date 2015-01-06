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
<title>Services</title>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="css/template.css" type="text/css"/>
<link rel="stylesheet" href="css/main.css">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datetimepicker.js"></script>
<script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
<script src="js/ValidationEngine/jquery.validationEngine.js"></script>
<script src="js/main.js"></script>
</head>

<body>
	<form action="inc/add_services.php" id="add_services">
    	<input type="hidden" name="ID" value="<?php echo $user->cryptID; ?>">
    	<span class="input-field">
        	<label for="type">Type de service* :</label>
            <?php echo $services->list_categories(); ?>
        </span>
        <span class="input-field">
        	<label for="desc">Description :</label>
            <textarea autocomplete="off" id="desc" name="desc"></textarea>
        </span>
        <span class="input-field">
        	<label for="distance">Rayon de déplacement* :</label>
            <input autocomplete="off" size="1" class="validate[required,custom[onlyNumberSp]]" type="text" value="1" name="distance" id="distance">km
        </span>
        <span class="input-field dispo_list">
        	<label for="dispoday">Disponibilités* :</label><br>
            <span data-IDF="1" class="dispo_field">
            	Le <select id="dispoday[1]" name="dispoday[1]">
                	<option value="all">Tous les jours</option>
                    <option value="weekend">Le week-end</option>
                	<option value="lun">Lundi</option>
                    <option value="mar">Mardi</option>
                    <option value="mer">Mercredi</option>
                    <option value="jeu">Jeudi</option>
                    <option value="ven">Vendredi</option>
                    <option value="sam">Samedi</option>
                    <option value="dim">Dimanche</option>
                </select>
                entre <input autocomplete="off" size="5" maxlength="5" name="dispostart[1]" value="19:00" class="validate[required] timepicker" id="dispostart[1]" type="text"> et <input autocomplete="off" maxlength="5" name="dispoend[1]" name="dispoend[1]" class="validate[required,timeCheck[dispostart{1}]] timepicker" value="21:00" size="5" type="text">
            </span>
            <a class="add_dispo">+ Ajouter une disponibilité</a><br><br>
            <input type="submit" value="Ajouter">
        </span>
    </form>
</body>
</html>