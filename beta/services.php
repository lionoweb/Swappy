<?php
	require_once("inc/config.php");
   require_once("inc/user.php");
   require("inc/services.php");
   require("inc/searches.php");
   $user = new user($mysql);
   $services = new services($mysql);
   $search = new search($mysql);
   $page = new page();
   $rsearch = $search->fill_search($user);
   $result = $search->search($_GET, $user);
?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no">
      <title>Swappy.fr - Services</title>
      <?php /* user.php | line 68 */ echo $page->meta_tag("", "", "", "Services", "liste, recherche, chercher"); ?>
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
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img alt="" width="127" height="47" src="img/logonav.png" class="max"><img alt="" width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Services</span>
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
                     <li class="active"><a  href="services.php">Services <span class="sr-only">(current)</span></a></li>
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
         <div class="container main" role="main">
            <div id="spec_services">
               <form class="col-md-6 col-md-offset-3 form-horizontal nonullpad" id="spec_search" action="services.php" method="get">
                  <div  class="form-group">
                     <label for="searchbar_" class="control-label col-xs-12 col-sm-2 left-text grey_text">Rechercher</label>
                     <div class="col-xs-12 col-sm-10 input-group">
                        <input id="searchbar_" name="searchbar" value="<?php echo $rsearch->title; ?>" class="form-control" type="text">
                     </div>
                  </div>
                  <div class="blueback">
                     <div  class="form-group">
                        <label for="type" class="control-label col-xs-12 col-sm-2">Catégorie</label>
                        <div class="col-xs-12 col-sm-10">
                           <?php
                             /* services.php | line 368 */ echo $services->list_categories(false, $rsearch->type_s);
                              ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="zipbar" class="control-label col-xs-12 col-sm-2">Où ?</label>
                        <div class="col-xs-12 col-sm-10">
                           <input id="zipbar" name="where" type="text" value="<?php echo $rsearch->where_s; ?>" class="form-control" placeholder="Exemple : 75001 (Paris)">
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="day" class="control-label col-xs-12 col-sm-2">Quand ?</label>
                        <div class="col-xs-12 col-sm-10">
                           <select id="day" name="day" class="form-control">
                           <?php /* searches.php | line 561 */ echo $rsearch->day_s; ?>
                           </select>
                        </div>
                        <div class="send col-xs-12 col-sm-3 col-sm-offset-9">
                           <input type="submit" value="envoyer" class="form-control">
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-10 col-md-offset-1 col-sm-12 table-responsive noborder">
               <table class="fulltable table">
                  <thead>
                     <tr>
                        <td colspan="2" class="header_search"><?php echo $result[2]; ?></td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php /* searches.php | line 527 - line 410 */ echo $result[0]; ?>
                  </tbody>
               </table>
               <?php /* searches.php | line 444 */ echo $result[1]; ?>
            </div>
            <div class="col-md-6 col-md-offset-3">
               <img src="img/pub.jpg" height="120" alt="" width="750" class="img-responsive">
            </div>

         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18" alt="">
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