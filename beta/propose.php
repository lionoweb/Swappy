<?php
session_start();
require_once("inc/user.php");
require_once("inc/mysql.php");
require("inc/services.php");
$user = new user($mysql);
//$user->onlyUsers();
$services = new services($mysql);
if(isset($_GET['logout'])) {
	$user->logout();
}	?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Je propose</title>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/jquery.datetimepicker.css">
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="css/template.css" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.datetimepicker.js"></script>
    <script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
    <script src="js/ValidationEngine/jquery.validationEngine.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body role="document">
<div id="wrap">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img src="img/logonav.png"></a>
            </div>
            <form class="navbar-form navbar-left search_navbar" method="get" role="search">
                <div class=" input-group">
                    <input id="searchbar" name="searchbar" type="text" class="form-control" placeholder="Rechercher">
                    <span class="input-group-btn">
                        <button title="Rechercher" type="submit" class="btn btn-default"></button>
                    </span>
                </div>
                
            </form>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li><a  href="services.php">Services <span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="propose.php">Je propose</a></li>
                    <li><a href="ccm.php">Comment ça marche ?</a></li>
                    <li><a href="apropos.php">A propos</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <?php if(!$user->logged) {?>
                	<li><a href="inscription.php">Inscription</a></li>
                <?php } ?>
                    <li class="dropdown">
                    <?php if(!$user->logged) {?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Connexion <span class="caret"></span></a>
                        <div class="dropdown-menu login-menu">
                            <div id="login_section">
                                <form action="inc/login.php" method="post" id="login_form">
                                    <input id="login_form" name="login_form" class="validate[required,minSize[5]]" placeholder="Identifiant" type="text" size="30">
                                    <input type="password" id="password_form" name="password_form" placeholder="Mot de passe" class="validate[required,minSize[6]]" size="30">
                                    <label class="string optional" for="user_remember_me">
                                        <input id="remember_me" type="checkbox" name="remember_me" checked> Se souvenir de moi
                                    </label>
                                    <input class="btn btn-primary" type="submit" name="commit" value="Se connecter">
                                    <div class="remind"><a class="remind_link">Mot de passe perdu ?</a></div>
                                </form>		
                            </div>
                            <div id="remind_section">
                                <form action="[YOUR ACTION]" method="post" accept-charset="UTF-8">
                                    <input id="user_username" placeholder="Email" type="text" name="remind[email]" size="30">
                                    <input class="btn btn-primary" type="submit" name="commit" value="Recuperer">
                                    <div class="remind"><a class="remind_link">Je m'en souviens !</a></div>
                                </form>
                            </div>
                        </div>
                        <?php } else { ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bonjour <?php echo $user->firstname; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="?logout">Se deconnecter</a></li>
                            </ul>
                        <?php } ?>
                    </li>
      			</ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <form autocomplete="off" class="col-md-8 col-md-offset-2 col-sm-12 container" id="spec_propose" action="inc/add_services.php" method="post">
		<input type="hidden" name="ID" value="<?php echo $user->cryptID; ?>">
        <div colspan="2" class="header_propose">Service proposé</div>

        <div class="greyback">

            <div class="form-group">
                <label for="sujet" class="control-label col-xs-12 col-sm-2">Sujet</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="sujet" name="title" type="text" class="form-control" placeholder="Exemple : Construire une étagère">
                </div>
            </div>

            <div class="form-group">
                <label for="type" class="control-label col-xs-12 col-sm-2">Catégorie</label>
                <div class="col-xs-12 col-sm-10">
                    <?php
                        echo $services->list_categories(true);
                        ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="control-label col-xs-12 col-sm-2">Description</label>
                <div class="col-xs-12 col-sm-10">
                    <textarea id="description" name="description" class="form-control" rows="8" cols="22" name="info"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="zipbar" class="control-label col-xs-12 col-sm-2">Lieu</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="zipcode" value="<?php echo $user->zipcode; ?>" name="zipcode" type="text" size="6" class="form-control validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]] zipcode" placeholder="code postal">
                    <input type="text" class="liketext" disabled readonly name="cityname" value="<?php echo $user->city; ?>">
                 </div>
            </div>

            <div class="form-group">
                <label for="km" class="control-label col-xs-12 col-sm-2">
                Rayon de déplacement</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="km" name="distance" type="text" size="1" value="1" class="form-control kilometre validate[required,custom[onlyNumberSp]]" > km
                </div>
            </div>

            <div class="form-group">
                <label for="day" class="control-label col-xs-12 col-sm-2">Disponibilités</label>
                <div class="col-xs-12 col-sm-10">
                <span data-IDF="1" class="dispo_field">
                    <select id="dispoday[1]" name="dispoday[1]" class="form-control days">
                            <?php $list_days = '<option value="all">Tous les jours</option>'.
							'<option value="weekend">Le week-end</option>'.
                            '<option value="lun">Lundi</option>'.
                            '<option value="mar">Mardi</option>'.
                            '<option value="mer">Mercredi</option>'.
                            '<option value="jeu">Jeudi</option>'.
                            '<option value="ven">Vendredi</option>'.
                            '<option value="sam">Samedi</option>'.
                            '<option value="dim">Dimanche</option>';
                           echo $list_days;
                            ?></select>
                    </select>
                    entre
                            <input size="5" maxlength="5" name="dispostart[1]" value="19:00" class="time form-control validate[required] timepicker" id="dispostart[1]" type="text">
                            et
                            <input maxlength="5" name="dispoend[1]" name="dispoend[1]" class="validate[required,timeCheck[dispostart{1}]] form-control timepicker time" value="21:00" size="5" type="text">
                            </span>
                <button class="add_dispo">+ Ajouter une disponibilité</button>
                </div>
                
                
            </div>

            <div class="form-group">
                <input type="submit" value="Valider">
            </div>

        </div>
        <!-- END DIV GREYBLACK -->
    </form>
</div>
<!-- END DIV ID WRAP-->
<footer id="footer">
    <div class="container-fluid">
        <a href="mentions-legales.php">Mentions légales</a> | <a href="cgu.php">CGU</a> | <a href="contact.php">Contact</a>
    </div>
</footer>
</body>
</html>
