<?php
require_once("inc/config.php");
require_once("inc/user.php");
$user = new user($mysql);
$page = new page();
?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no">
	<title>Swappy.fr - A Propos</title>
	<?php echo $page->meta_tag("", "", "", "A propos", "équipe, nous, propos, information, projet"); ?>
	<link rel="icon" href="img/favicon.png">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/main.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
	<script src="js/ValidationEngine/jquery.validationEngine.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
	<!--[if lt IE 9]>
      <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
<div id="wrap">
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    	<div class="container-fluid"> 
      	<!-- Brand and toggle get grouped for better mobile display -->
      		<div class="navbar-header">
        		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> 
                </button>
        		<a class="navbar-brand" href="index.php" title="Retour à l'accueil">
                	<img width="127" height="47" src="img/logonav.png" alt="" class="max">
                    <img alt="" width="50" height="47" src="img/logo_min.png" class="min">
               	</a> <span class="brand-title">A propos</span> 
          	</div>
      		<form class="navbar-form navbar-left search_navbar" action="services.php" method="get" role="search">
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
                  <li><a href="services.php">Services</a></li>
                  <li><a rel="nofollow" href="propose.php">Je propose</a></li>
                  <li><a href="ccm.php">Comment ça marche ?</a></li>
                  <li  class="active"><a href="apropos.php">A propos <span class="sr-only">(current)</span></a></li>
        		</ul>
                <ul class="nav navbar-nav navbar-right">
                  <?php echo $user->navbar(); ?>
                </ul>
      		</div>
     		 <!-- /.navbar-collapse --> 
    	</div>
    	<!-- /.container-fluid --> 
  	</nav>
  	<div id="spec_apropos" class="container-fluid main" role="main">
    	<div class="banner_title">
      		<p>L'équipe</p>
    	</div>
    	<div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
    		<div id="spec_team" class="container team">
      			<ul class="row list_view">
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/calypso.png" alt="Calypso Redor" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Calypso Redor</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Chef de projet, chargée de communication et marketing</span>. Elle mène à bien le projet et entoure son équipe dans la bonne humeur</p>
            				<a data-hash="<?php echo $page->encode_mail("calypso.redor@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-la</a>
                    	</div>
       			 	</li>
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/lionel.png" alt="Lionel Jeronimo" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Lionel Jeronimo</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Développeur</span> en chef de l’agence. Aussitôt dit aussitôt fait, Lionel est professionnel et très réactif.</p>
            				<a data-hash="<?php echo $page->encode_mail("lionel.jeronimo@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-le</a> 
                      	</div>
        			</li>
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/oceane.png" alt="Océane Perret" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Océane Perret</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Intégratrice et développeuse</span>, elle est sérieuse et impliquée. Elle est toujours disponible avec des idées novatrices.</p>
            				<a data-hash="<?php echo $page->encode_mail("oceane.perret@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-la</a> 
                    	</div>
        			</li>
      			</ul>
      			<ul class="row list_view">
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/line.png" alt="Line Bui" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Line Bui</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Intégratrice et développeuse</span>. Elle est motivée et prête à passer des heures sur un problème pour le résoudre.</p>
            				<a data-hash="<?php echo $page->encode_mail("line.bui@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-la</a> 
                   		</div>
        			</li>
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/brice.png" alt="Brice Olivrie" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Brice Olivrie</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Vidéaste</span>, il est en charge de la production complète de la vidéo. Entre le graphisme et le montage, Brice travaille sur tous les fronts.</p>
            				<a data-hash="<?php echo $page->encode_mail("mailto:brice.olivrie@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-le</a>
                     	</div>
        			</li>
        			<li itemscope itemtype="http://schema.org/Person" itemprop="employees" class="view view-sixth"> 
                    	<img src="img/apropos/margot.png" alt="Margot Gillodes" width="225" height="225">
          				<div class="mask">
            				<h2 itemprop="givenName">Margot Gillodes</h2>
            				<p itemprop="description"><span itemprop="jobTitle">Graphiste</span> à plein temps. Passionnée, elle a su trouver des solutions graphiques à nos envies les plus complexes.</p>
            				<a data-hash="<?php echo $page->encode_mail("mailto:margot.gillodes@swappy.fr", "UTF"); ?>" class="more link_mail">Contactez-le</a> </div>
        			</li>
      			</ul>
    		</div>
    		<div class="banner_title">
      			<p>Notre projet</p>
    		</div>
    		<div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
    		<div class="row projet">
      			<div itemprop="description" class="col-md-4 col-md-offset-2 col-xs-10 col-xs-offset-1">
        			<p>Swappy est une plate-forme d’échanges de services entre particuliers à titre gratuit. Nous voulons ainsi promouvoir l’entraide et le sentiment de communauté. Les échanges ne concernent que des services. Cette prestation permet de mettre à disposition les capacités techniques et intellectuelles de chacun. Pour être plus précis, voici un exemple : une personne A pourra proposer son aide pour jardiner chez une personne B. En contrepartie, la personne B pourra cuisiner pour la personne A.</p>
      			</div>
      			<div class="col-md-4 col-md-offset-0 col-xs-10 col-xs-offset-1">
        			<p>Cependant les échanges pourront également être réalisés dans un sens unique. Nous voulons mettre en avant l’aspect de la générosité et de l’altruisme des personnes. <br>
          Durant l'année de préparation du projet, nous avons pu nous construire un site vitrine, détaillant les membres de l'agence et le projet envisagé. Vous pouvez trouver ce travail sur le site suivant : <a href="https://perso-etudiant.univ-mlv.fr/~ljeronim/2DSide/">2DSide</a></p>
      			</div>
    		</div>
  		</div>
	</div>
	<footer id="footer"> <img src="img/footer.png" width="30" alt="" height="18">
  		<div class="container-fluid"> <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php">Contact</a>
            <hr>
            <a target="_blank" class="social" title="Voir notre page Facebook" href="https://www.facebook.com/SwappyLaPlateforme"><img height="30" width="30" alt="Facebook" src="img/social/facebook.png"></a>
            <a target="_blank" class="social" title="Voir notre page Twitter" href="https://twitter.com/_Swappy"><img height="30" width="30" alt="Twitter" src="img/social/twitter.png"></a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
  		</div>
	</footer>
</body>
</html>