<?php
   require_once("inc/config.php");
   require_once("inc/user.php");
   $user = new user($mysql);
   @$_GET['id'] ?: $user->onlyUsers();
   require_once("inc/chat.php");
	$user_ = @$_GET['id'] ?  (new user($mysql, $_GET['id'])) : $user;
	$chat = new chat($mysql, $user); 
	$page = new page();
?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no
">
      <title>Swappy.fr - <?php echo $user_->title; ?></title>
      <?php /* user.php | line 68 */ echo $page->meta_tag($user_->meta()); ?>
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
      <script src="js/bootstrap-tagsinput.min.js"></script>
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
                  <span class="brand-title"><?php echo $user_->title; ?></span>
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
         <div id="profil" class="container-fluid main mon_profil" role="main">
                <?php /* inc/html/profil.php || inc/html/profil-o.php */ $user_->profil_page($user->ID); ?>
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
      <?php
		 /* chat.php | line 566 */ $chat->prepare_popup($user_, false);
		 /* chat.php | line 511 */ $chat->prepare_popup_report($user_, false);
		?>
   </body>
</html>