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
    <title>Swappy.fr - Annonce</title>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="css/template.css" type="text/css"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
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
            		<li><a  href="services.php">Services</span></a></li>
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

    
    <div id="spec_annonce">
        <div class="profil row">
            <div class="col-md-1 col-md-offset-2 col-sm-1 col-sm-offset-1 col-xs-1 col-xs-offset-0">
                <img src="img/annonce/johndoe.png" width="130" height="130">
            </div>
            <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-1 dispo">
                <div class="name">John Doe propose</div>
                <div class="info"><img src="img/annonce/clock.png">Lundi, week-end</div>
                <div class="info loc"><img src="img/annonce/location.png" width="18" height="28">Perpignan, jusqu'à 5 km</div>
            </div>
            <div class="interesse">
                <a href=""><button>Je suis interessé(e)</button></a>
            </div>
        </div>

        <div class="servicepropose">      
            <div class="row">
                <img src="img/annonce/pic.png">
                <div class="header_annonce">Réparation de voiture</div>
            </div>
        </div>


        <div class="greyback">
            <p class="col-md-8 col-md-offset-2 description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi eros. Sed massa purus, facilisis vel tortor at, fermentum posuere lacus. Nullam a libero ut Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi eripsum dolor sit amet, consectetur adipiscing elit. Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi eros. Sed massa purus, facilisis vel tortor at, fermentum posuere lacus. Nullam a libero ut Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi er ut Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi eripsum dolor sit amet, consectetur adipiscing elit. Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices mi. Donec non mi eros. Sed massa purus, facilisis vel tortor at, fermentum posuere lacus. Nullam a libero ut Donec erat velit, eleifend vel lorem maximus, vestibulum ultrices m</p>
            <a href="" class="col-md-3"><img src="img/annonce/back.png">Retours aux résultats précédents</a>
        </div>
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
<?php $user->modal_location_c($_GET); ?>
</body>
</html>