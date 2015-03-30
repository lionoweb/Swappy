<?php
require_once("inc/user.php");
require_once("inc/config.php");
$user = new user($mysql);
$page = new page();
 ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
	<head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no">
      <title>Swappy.fr - Échanges de services gratuits entre particuliers</title>
      <?php /* user.php | line 68 */ echo $page->meta_tag("", "", "", "", ""); ?>
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
      <?php /* user.php | line 13 */ echo $page->add_tracking(); ?>
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img alt="" width="127" height="47" src="img/logonav.png" class="max"><img alt="" width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Accueil</span>
               </div>
               <form class="navbar-form navbar-left search_navbar" action="services.php" method="get" role="search">
                  <div class="input-group">
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
                     <li><a href="apropos.php">A propos</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                     <?php /* user.php | line 1125 */  echo $user->navbar(); ?>
                  </ul>
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
          <div id="spec_accueil" class="container-fluid main" role="main">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
              </ol>
              <div class="carousel-inner" role="listbox">
                <div class="item active first">
                  <img src="img/accueil/slide1.jpg" alt="Bienvenue">
                  <div class="carousel-title col-xs-6 col-xs-offset-1">
                      <h1>Bienvenue sur<br>Swappy</h1>
                      <p>Une plateforme d'échanges de services<br>gratuits entre particuliers</p>
                  </div>
                </div>

                <div class="item second">
                  <img src="img/accueil/slide2.jpg" alt="Communauté">
                  <div class="carousel-title">
                      <h1>la communauté <br>Swappy</h1>
                      <p>Rejoignez notre communauté d'entraide <br>et rencontrez d'autres swappeurs !</p>
                  </div>
                </div>

                <div class="item third">
                    <img src="img/accueil/slide3.jpg" alt="Rejoindre">
                      <div class="carousel-title">
                        <h1>et maintenant,<br>rejoignez-nous !</h1>
                        <p>Inscrivez-vous dès maintenant et partagez<br>l'expérience Swappy avec la communauté</p>
                      </div>
                </div>
              </div>
               <a class="left carousel-control" title="Précedent" href="#carousel-example-generic" role="button" data-slide="prev">
                 <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                 <span class="sr-only">Précedent</span>
               </a>
               <a title="Suivant" class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                 <span class="sr-only">Suivant</span>
               </a>
             </div>
             <iframe src="https://player.vimeo.com/video/123107648" class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
      </div>
    <footer id="footer">
      <img src="img/footer.png" width="30" alt="" height="18">
        <div class="container-fluid">
          <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php">Contact</a>
            <hr>
            <a target="_blank" class="social" title="Voir notre page Facebook" href="https://www.facebook.com/SwappyLaPlateforme"><img height="30" width="30" alt="Facebook" src="img/social/facebook.png"></a>
            <a target="_blank" class="social" title="Voir notre page Twitter" href="https://twitter.com/_Swappy"><img height="30" width="30" alt="Twitter" src="img/social/twitter.png"></a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
        </div>
    </footer>
    <?php $user->modal_location_c($_GET); ?>
    </body>
</html>