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
      <title>Swappy.fr - A Propos</title>
      <link rel="icon" href="img/favicon.png">
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">A propos</span>
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
                     <li><a href="propose.php">Je propose</a></li>
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
                  <li class="view view-sixth">
                     <img src="img/apropos/calypso.png" width="225" height="225">
                     <div class="mask">
                        <h2>Calypso Redor</h2>
                        <p>Chef de projet, chargée de communication et marketing. Elle mène à bien le projet et entoure son équipe dans la bonne humeur</p>
                        <a href="mailto:calypso.redor@swappy.fr" class="more">Contactez-la</a>
                     </div>
                  </li>
                  <li class="view view-sixth">
                     <img src="img/apropos/lionel.png" width="225" height="225">
                     <div class="mask">
                        <h2>Lionel Jeronimo</h2>
                        <p>Développeur en chef de l’agence. Aussitôt dit aussitôt fait, Lionel est professionnel et très réactif.</p>
                        <a href="mailto:lionel.jeronimo@swappy.fr" class="more">Contactez-le</a>
                     </div>
                  </li>
                  <li class="view view-sixth">
                     <img src="img/apropos/oceane.png" width="225" height="225">
                     <div class="mask">
                        <h2>Océane Perret</h2>
                        <p>Intégratrice et développeuse, elle est sérieuse et impliquée. Elle est toujours disponible avec des idées novatrices.</p>
                        <a href="mailto:oceane.perret@swappy.fr" class="more">Contactez-la</a>
                     </div>
                  </li>
               </ul>
               <ul class="row list_view">
                  <li class="view view-sixth">
                     <img src="img/apropos/line.png" width="225" height="225">
                     <div class="mask">
                        <h2>Line Bui</h2>
                        <p> Intégratrice et développeuse. Elle est motivée et prête à passer des heures sur un problème pour le résoudre.</p>
                        <a href="mailto:line.bui@swappy.fr" class="more">Contactez-la</a>
                     </div>
                  </li>
                  <li class="view view-sixth">
                     <img src="img/apropos/brice.png" width="225" height="225">
                     <div class="mask">
                        <h2>Brice Olivrie</h2>
                        <p>Vidéaste, il est en charge de la production complète de la vidéo. Entre le graphisme et le montage, Brice travaille sur tous les fronts.</p>
                        <a href="mailto:brice.olivrie@swappy.fr" class="more">Contactez-le</a>
                     </div>
                  </li>
                  <li class="view view-sixth">
                     <img src="img/apropos/margot.png" width="225" height="225">
                     <div class="mask">
                        <h2>Margot Gillodes</h2>
                        <p>Graphiste à plein temps. Passionnée, elle a su trouver des solutions graphiques à nos envies les plus complexes.</p>
                        <a href="mailto:margot.gillodes@swappy.fr" class="more">Contactez-le</a>
                     </div>
                  </li>
               </ul>
            </div>
            <div class="banner_title">
               <p>Notre projet</p>
            </div>
            <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
            <div class="row projet">
               <div class="col-md-4 col-md-offset-2 col-xs-10 col-xs-offset-1">
                  <p>Swappy est une plate-forme d’échanges de services entre particuliers à titre gratuit. Nous voulons ainsi promouvoir l’entraide et le sentiment de communauté. Les échanges ne concernent que des services. Cette prestation permet de mettre à disposition les capacités techniques et intellectuelles de chacun. Pour être plus précis, voici un exemple : une personne A pourra proposer son aide pour jardiner chez une personne B. En contrepartie, la personne B pourra cuisiner pour la personne A.</p>
               </div>
               <div class="col-md-4 col-md-offset-0 col-xs-10 col-xs-offset-1">
                  <p>Cependant les échanges pourront également être réalisés dans un sens unique. Nous voulons mettre en avant l’aspect de la générosité et de l’altruisme des personnes. <br>Durant l'année de préparation du projet, nous avons pu nous construire un site vitrine, détaillant les membres de l'agence et le projet envisagé. Vous pouvez trouver ce travail sur le site suivant :
 <a href="https://perso-etudiant.univ-mlv.fr/~ljeronim/2DSide/">2DSide</a></p>
               </div>
            </div>
         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
   </body>
</html>