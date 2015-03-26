<?php
   require_once("inc/config.php");
   require_once("inc/user.php");
   require("inc/services.php");
   $user = new user($mysql);
   $user->onlyUsers();
   $services = new services($mysql, @$_GET['edit']);
   $edit = $services->edit_page($user);
?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no
">
      <title>Swappy.fr - <?php echo $edit->htitle; ?></title>
      <link rel="icon" href="img/favicon.png">
      <link rel="stylesheet" href="css/jquery-ui.css">
      <link rel="stylesheet" href="css/jquery.datetimepicker.css">
      <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/main.css">
      <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
      <script src="js/jquery.js"></script>
      <script src="js/jquery-ui.js"></script>
      <script src="js/jquery.datetimepicker.js"></script>
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img alt="" width="127" height="47" src="img/logonav.png" class="max"><img alt="" width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title"><?php echo $edit->htitle; ?></span>
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
                     <li><a  href="services.php">Services <span class="sr-only">(current)</span></a></li>
                     <li class="active"><a rel="nofollow" href="propose.php">Je propose</a></li>
                     <li><a href="ccm.php">Comment ça marche ?</a></li>
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
         <div class="container main" role="main">
            <div class="header_propose">
               <p class="col-md-6 col-md-offset-3 top"><?php echo $edit->ntitle; ?></p>
               <p class="col-md-6 col-md-offset-3 bot">Faites partie de la communauté en proposant et partageant vos services à autrui.</p>
            </div>
            <div colspan="2" class="title_propose">Proposez</div>
            <div class="greyback">
               <form autocomplete="off" class="col-md-10 col-md-offset-1 col-sm-12 container" id="spec_propose" action="inc/services_.php" method="post">
               		<?php echo $edit->field; ?>
                  <input type="hidden" name="ID" value="<?php echo $user->cryptID; ?>">
                  <div class="form-group">
                     <label for="sujet" class="control-label col-xs-12 col-sm-3">Sujet</label>
                     <div class="col-xs-12 col-sm-8">
                        <input id="sujet" name="title" value="<?php echo $edit->title; ?>" type="text" class="form-control" placeholder="Exemple : Construire une étagère">
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="type" class="control-label col-xs-12 col-sm-3">Catégorie</label>
                     <div class="col-xs-12 col-sm-8">
                        <?php
                           echo $services->list_categories(true, $edit->selected);
                           ?>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="description" class="control-label col-xs-12 col-sm-3">Description</label>
                     <div class="col-xs-12 col-sm-8">
                        <textarea id="description" name="description" class="form-control" rows="8" cols="22"><?php echo $edit->description; ?></textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="zipbar" class="control-label col-xs-12 col-sm-3">Lieu</label>
                     <div class="col-xs-12 col-sm-8">
                        <input id="zipcode" value="<?php echo $edit->zipcode; ?>" name="zipcode" type="text" size="6" class="form-control validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]] zipcode" placeholder="code postal">
                        <input type="text" class="liketext" disabled readonly name="cityname" value="<?php echo $edit->city; ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="km" class="control-label col-xs-12 col-sm-3">
                     Rayon de déplacement</label>
                     <div class="col-xs-12 col-sm-8">
                        <input id="km" name="distance" type="text" size="1" value="<?php echo $edit->distance; ?>" class="form-control kilometre validate[required,custom[onlyNumberSp]]" > km
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="day" class="control-label col-xs-12 col-sm-3">Disponibilités</label>
                     <div class="col-xs-12 col-sm-8">
                        <?php echo $edit->dispo; ?>
                        <button class="add_dispo">+ Ajouter une disponibilité</button>
                     </div>
                  </div>
                  <div class="form-group col-sm-3 col-sm-offset-8">
                     <input type="submit" value="<?php echo $edit->button ; ?>">
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- END DIV ID WRAP-->
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