<?php
   session_start();
   require_once("inc/mysql.php");
   require_once("inc/user.php");
   require_once("inc/services.php");
   require_once("inc/chat.php");
   $user = new user($mysql);
   if(isset($_GET['logout'])) {
   	$user->logout();
   }
   $ID_service = @$_GET['id'];
   $services = new	services($mysql, $ID_service);
   $user_ = new user($mysql, $services->by);
   $chat = new chat($mysql, $user); ?>
<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Swappy.fr - Annonce : <?php echo $services->title; ?></title>
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
      <div id="wrap" class="color-grey">
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.jpg" class="min"></a>
                  <span class="brand-title">Annonce : <?php echo $services->title; ?></span>
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
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
         </nav>
         <div id="spec_annonce" class="container main" role="main">
            <div class="profil row">
               <div class="col-md-1 col-md-offset-2 col-sm-1 col-sm-offset-1 col-xs-1 col-xs-offset-0">
                  <img src="<?php echo $user_->avatar; ?>" width="130" height="130">
               </div>
               <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-1 dispo">
                  <div class="name"><?php echo $user_->firstname." ".$user_->lastname; ?> (<?php echo $user_->login; ?>) propose</div>
                  <div class="info"><img src="img/annonce/clock.png"><?php echo $services->disponibility; ?></div>
                  <div class="info loc"><img src="img/annonce/location.png" width="18" height="28"><?php echo $services->city; ?>, jusqu'à <?php echo $services->distance; ?> km de déplacement</div>
               </div>
               <div class="interesse">
               		<?php if($user->ID != $user_->ID) { ?>
                  <button class="popup_message">Je suis interessé(e)</button>
                  <?php } else { ?>
                  <button disabled>Vous êtes le propriétaire de ce service.</button>
                  <?php } ?>
               </div>
            </div>
            <div class="servicepropose">
               <div class="row">
                  <img width="85" height="85" src="img/services/<?php echo $services->cattype; ?>.jpg">
                  <div class="header_annonce"><?php echo ucfirst($services->title); ?></div>
               </div>
            </div>
            <div class="greyback row">
               <?php if(isset($_GET['r']) && !empty($_GET['r'])) {
                  $r = '<a href="services.php?'.base64_decode($_GET['r']).'" class="col-md-3"><img src="img/annonce/back.png">Retours aux résultats précédents</a>';
                  } else {
                  $r = '<a href="services.php" class="col-md-3"><img src="img/annonce/back.png">Retour à la page des services</a>';
                  } ?>
               <p class="col-md-8 col-md-offset-2 description"><?php echo ucfirst($services->description); ?></p>
               <?php echo $r; ?>
            </div>
         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="mentions-legales.php">Mentions légales</a> | <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
      <?php $chat->prepare_popup($user_, $services); ?>
      <?php $user->modal_location_c($_GET); ?>

   </body>
</html>