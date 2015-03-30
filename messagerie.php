<?php
   require_once("inc/config.php");
   require_once("inc/user.php");
   require_once("inc/chat.php");
   $user = new user($mysql);
   $user->onlyUsers();
   $chat = new chat($mysql, $user);
   $page = new page(); ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no
">
      <title>Swappy.fr - Messagerie</title>
      <link rel="icon" href="img/favicon.png">
      <link rel="stylesheet" href="css/jquery-ui.css">
      <link rel="stylesheet" href="css/jquery.datetimepicker.css">
      <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/main.css">
      <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
      <script src="js/jquery.js"></script>
      <script src="js/jquery-ui.js"></script>
      <script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
      <script src="js/ValidationEngine/jquery.validationEngine.js"></script>
      <script src="js/jquery.datetimepicker.js"></script>
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
                  <span class="brand-title">Messagerie</span>
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
			<div class="message_box col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1"><!--
			--><div id="list_m">
					<div class=" input-group">
						<span class="input-group-btn">
                     		<button class="btn btn-default bluesearch" type="submit" title="Rechercher"></button>
                     	</span>
                     	<input type="text" placeholder="Rechercher" class="form-control ui-autocomplete-input" name="searchbar" id="searchbar" autocomplete="off">
                 	</div>
				</div><!--
			--><div id="content_m" style=""><!--
				--><div class="header_m"><div title="Retour" class="return_list"><img src="img/icon/retour.png" width="50" height="50" alt="Retour"></div><span></span></div>
    				<div class="inner_m"></div>
    				<div class="form_m">
    					<form id='message_send' action="inc/msg_.php" method="post">
    						<textarea name="message_r" placeholder="Votre message" rows="4" class="form-control validate[required]"></textarea>
        					<input type="hidden" value="" name="ID_Converse">
        					<input type="submit" value="envoyer">
        					<button>Fixer un rendez-vous</button>
        				</form>
        				<div class="clear"></div>
    				</div>
				</div>
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