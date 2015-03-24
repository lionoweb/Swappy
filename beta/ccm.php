<?php
session_start();
require_once("inc/user.php");
require_once("inc/mysql.php");
$user = new user($mysql);
if(isset($_GET['logout'])) { $user->logout(); }	?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no
">
      <title>Swappy.fr - Comment ça marche ?</title>
      <?php echo meta_tag("", "", "", "Comment ça marche ?", "comment, marche, tuto, utilisation, consigne"); ?>
      <link rel="icon" href="img/favicon.png">
      <link rel="stylesheet" href="css/jquery-ui.css">
      <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="css/main.css">
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
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" alt="" src="img/logonav.png" class="max"><img alt="" width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Comment ça marche ?</span>
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
                     <li><a  href="services.php">Services</a></li>
                     <li><a rel="nofollow" href="propose.php">Je propose</a></li>
                     <li  class="active"><a href="ccm.php">Comment ça marche ? <span class="sr-only">(current)</span></a></li>
                     <li><a href="apropos.php">A propos</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                     <?php echo $user->navbar(); ?>
                  </ul>
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
         </nav>
         <div id="spec_ccm" class="container-fluid main" role="main">
            <div class="banner_title">
               <p>Comment ça marche ?</p>
            </div>
            <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
               <div id="spec_tuto">
                  <ul class="row list_view">
                     <li class="view">
                        <img src="img/ccm/1etape.jpg" width="250" height="250" alt="Inscrivez-vous">
                     </li>
                     <li class="view">
                        <img src="img/ccm/2etape.jpg" width="250" height="250" alt="Proposez/demandez">
                     </li>
                     <li class="view">
                        <img src="img/ccm/3etape.jpg" width="250" height="250" alt="Echangez">
                     </li>
                     <li class="view">
                        <img src="img/ccm/4etape.jpg" width="250" height="250" alt="À vous de jouer">
                     </li>
                  </ul>
               </div>
               <div id="spec_video">
                  <div class="banner_title">
                     <p>Vidéo tutorielle</p>
                  </div>
                  <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>

					<iframe width="560" height="315" class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12" src="https://www.youtube.com/embed/_GAukqGi8qs" frameborder="0" allowfullscreen></iframe>
               </div>
         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" alt="" width="30" height="18">
         <div class="container-fluid">
            <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php">Contact</a>
            <hr>
            <a target="_blank" class="social" title="Voir notre page Facebook" href="https://www.facebook.com/SwappyLaPlateforme"><img height="30" width="30" alt="Facebook" src="img/social/facebook.png"></a>
            <a target="_blank" class="social" title="Voir notre page Twitter" href="https://twitter.com/_Swappy"><img height="30" width="30" alt="Twitter" src="img/social/twitter.png"></a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
   </body>
</html>