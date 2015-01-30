<?php
session_start();
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
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Swappy.fr - Contact</title>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="css/template.css" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
    <script src="js/ValidationEngine/jquery.validationEngine.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body role="document">
<div id="wrap" class="back-grey">
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
            		<li><a href="propose.php">Je propose</a></li>
            		<li><a href="ccm.php">Comment ça marche ?</a></li>
            		<li><a href="apropos.php">A propos</a></li>
            	</ul>
             	<ul class="nav navbar-nav navbar-right">
               <?php echo $user->navbar(); ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container-fluid main" id="contact" role="main">
        <div class="header_contact">
            <p class="top col-md-6 col-md-offset-3">Besoin de nous contacter ?</p>
            <p class="bot col-md-8 col-md-offset-2">Une équipe professionnelle et réactive : si vous rencontrez un problème nous saurons vous guider.</p>
            <div class="row small">
                <div class="getintouch col-md-2 col-md-offset-4 col-xs-4 col-xs-offset-1"><img src="img/contact/phone.png">
                    <p>06.27.75.49.05</p>
                </div>

                <div class="getintouch col-md-2 col-md-offset-0 col-xs-5 col-xs-offset-1"><img src="img/contact/mail.png">
                    <p>swappy@contact.fr</p>
                </div>
            </div>
        </div>

        <form id="spec_contact" action="inc/send_mail.php" method="post">
            <div class="row">
                <div class="left col-md-4 col-md-offset-2">
                    <div class="form-group">
                        <label for="prenom" class="control-label col-xs-12 col-sm-2">Prénom</label><br>
                        <div class="col-xs-12 col-sm-10 col-md-12">
                            <input id="prenom" name="name" value="<?php echo $user->firstname; ?>" type="text" class="form-control validate[required]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nom" class="control-label col-xs-12 col-sm-2">Nom</label><br>
                        <div class="col-xs-12 col-sm-10 col-md-12">
                            <input id="nom" name="lastname" value="<?php echo $user->lastname; ?>" class="form-control validate[required]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mail" class="control-label col-xs-12 col-sm-2">Email</label><br>
                        <div class="col-xs-12 col-sm-10 col-md-12">
                            <input id="mail" name="email" value="<?php echo $user->email; ?>" class="form-control validate[required,email]">
                        </div>
                    </div>
                </div>

                <div class="right col-md-4 col-md-offset-0">
                    <div class="form-group">
                        <label for="objet" class="control-label col-xs-12 col-sm-2 col-md-6">Objet (facultatif)</label><br>
                        <div class="col-xs-12 col-sm-10 col-md-12">
                            <input id="objet" name="object" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="control-label col-xs-12 col-sm-2">Message</label><br>
                        <div class="col-xs-12 col-sm-10 col-md-12">
                            <textarea id="message" class="form-control validate[required]" name="msg" rows="8" cols="44"></textarea>
                        </div>
                    </div>  

                    <div class="form-group">
                        <input type="submit" id="button" name="send" value="Envoyer">
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<footer id="footer">
    <img src="img/footer.png">
    <div class="container-fluid">
        <a href="mentions-legales.php">Mentions légales</a> | <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
        <hr>
        <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
    </div>
</footer>

</body>
</html>