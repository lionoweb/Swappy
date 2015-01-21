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
    <title>A Propos</title>
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
            		<li><a  href="services.php">Services</a></li>
            		<li><a href="propose.php">Je propose</a></li>
            		<li><a href="ccm.php">Comment ça marche ?</a></li>
            		<li  class="active"><a href="apropos.php">A propos <span class="sr-only">(current)</span></a></li>
            	</ul>
             	<ul class="nav navbar-nav navbar-right">
                    <?php echo $user->navbar(); ?>
      			</ul>
    		</div><!-- /.navbar-collapse -->
  		</div><!-- /.container-fluid -->
	</nav>

    <div id="spec_apropos" role="main">

    <div class="teamtitle"><p>L'équipe</p></div>

        <div id="spec_team" class="team">
            <div class="row">
                <div class="col-md-3 col-md-offset-2">
                <a href="#"><img src="img/apropos/brice.png" width="225" height="225"></a>
                </div>

                <div class="col-md-3">
                <a href="#"><img src="img/apropos/calypso.png" width="225" height="225"></a>
                </div>

                <div class="col-md-3">
                <a href="#"><img src="img/apropos/line.png" width="225" height="225"></a>
                </div>
            </div>

        <div class="row">
                <div class="col-md-3 col-md-offset-2">
                <a href="#"><img src="img/apropos/margot.png" width="225" height="225"></a>
                </div>

                <div class="col-md-3">
                <a href="#"><img src="img/apropos/oceane.png" width="225" height="225"></a>
                </div>

                <div class="col-md-3">
                <a href="#"><img src="img/apropos/lionel.png" width="225" height="225"></a>
                </div>
            </div>
        </div>

    <div class="teamtitle"><p>Notre projet</p></div>

        <div class="projet greyback col-md-8 col-md-offset-2">

            <div class="col-md-6">
                <p>Swappy est une plate-forme d’échanges de services entre particuliers à titre gratuit. Nous voulons ainsi promouvoir l’entraide et le sentiment de communauté.Les échanges ne concerne que des services. Cette prestation permet de mettre à disposition les capacités techniques et intellectuelles de chacun. Pour être plus précis, voici un exemple : une personne A pourra proposer son aide pour jardiner à une personne B. En contrepartie, la personne B pourra cuisiner pour la personne A.</p>
            </div>

            <div class="col-md-6">
                <p>Cependant les échanges pourront également être réalisés dans un sens unique. Nous voulons mettre en avant l’aspect de la générosité et de l’altruisme des personnes. Il y aura plusieurs catégories (filtres) afin que tout le monde puisse trouver le service qui lui convient. Chaque personne inscrite sur la plate-forme pourra de même poster des annonces.Durant l'année de préparation du projet, nous avons pu nous construire un site vitrine, détaillant les membres de l'agence et le projet envisagé. Vous pouvez trouver ce travail sur le site suivant : <a href="https://perso-etudiant.univ-mlv.fr/~ljeronim/2DSide/">2DSide</a></p>
            </div>

        </div>

    </div>

</div>

<footer id="footer">
    <div class="container-fluid">
        <a href="mentions-legales.php">Mentions légales</a> | <a href="cgu.php">CGU</a> | <a href="contact.php">Contact</a>
    </div>
</footer>
</body>
</html>
