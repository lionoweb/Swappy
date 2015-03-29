<?php 
require_once( "inc/config.php"); 
require_once( "inc/user.php"); 
require_once( "inc/services.php"); 
require_once( "inc/chat.php"); 
$user=new user($mysql); 
$services = new services($mysql, @$_GET['id'], $user); 
$user_ = new user($mysql, $services->by); 
$chat = new chat($mysql, $user); 
$page = new page(); ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no
">
	<title>Swappy.fr - Annonce : <?php echo $services->title; ?></title>
    <?php /* user.php | line 68 */ echo $page->meta_tag($services->meta()); ?>
	<link rel="icon" href="img/favicon.png">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<?php @$_GET['vote'] ? '<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">' : ''; ?>
	<link rel="stylesheet" href="css/main.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
	<script src="js/ValidationEngine/jquery.validationEngine.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<?php @$_GET['vote'] ? '<script src="js/rate.js"></script>' : ''; ?>
	<script src="js/main.js"></script>
	<!--[if lt IE 9]>
      <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
	<div id="wrap" class="color-grey">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"> <span class="sr-only">Toggle navigation</span>  <span class="icon-bar"></span>  <span class="icon-bar"></span>  <span class="icon-bar"></span> 
					</button>
					<a class="navbar-brand" href="index.php" title="Retour à l'accueil">
						<img width="127" height="47" alt="" src="img/logonav.png" class="max">
						<img alt="" width="50" height="47" src="img/logo_min.png" class="min">
					</a> <span class="brand-title">Annonce : <?php echo $services->title; ?></span> 
				</div>
				<form class="navbar-form navbar-left search_navbar" action="services.php" method="get" role="search">
					<div class="input-group">
						<input id="searchbar" name="searchbar" type="text" class="form-control" placeholder="Rechercher"> <span class="input-group-btn">
                     		<button title="Rechercher" type="submit" class="btn btn-default"></button>
                     	</span>
					</div>
				</form>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="navbar">
					<ul class="nav navbar-nav">
						<li>
                        	<a href="services.php">Services</a> 
						</li>
						<li>
                        	<a rel="nofollow" href="propose.php">Je propose</a> 
						</li>
						<li>
                        	<a href="ccm.php">Comment ça marche ?</a> 
						</li>
						<li>
                        	<a href="apropos.php">A propos</a> 
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php /* user.php | line 1125 */ echo $user->navbar(); ?></ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
		<div id="spec_annonce" class="container main" role="main">
			<div class="profil row">
				<div class="col-md-1 col-md-offset-2 col-sm-1 col-sm-offset-1 col-xs-12 avatan">
                	<a title="Voir le profil de <?php echo $user_->fullname; ?>" class="link-profil" href="profil-<?php echo $user_->ID; ?>.php">
						<img src="<?php echo $user_->avatar; ?>" alt="Avatar de <?php echo $user_->fullname; ?>" width="130" height="130">
                 	</a>
				</div>
				<div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-1 dispo">
					<div class="name">
						<a title="Voir le profil de <?php echo $user_->fullname; ?>" class="link-profil" href="profil-<?php echo $user_->ID; ?>.php"><?php echo $user_->fullname; ?></a> propose
                    </div>
					<div class="info">
						<img alt="" src="img/annonce/clock.png"><?php /* services.php | line 356 */ echo $services->disponibility; ?>
                   	</div>
					<div class="info loc">
						<img alt="" src="img/annonce/location.png" width="18" height="28"><?php echo $services->city; ?>, jusqu'à <?php echo $services->distance; ?> km de déplacement</div>
				
                	<div class="info rate">
                    	Note moyenne : 
                        <div class="star-rating rating-xs rating-active" title="<?php echo $services->globalnote; ?> étoile(s)">
                        	<div data-content="" class="rating-container rating-gly-star">
                            	<div style="width: <?php echo ($services->globalnote*20); ?>%;" data-content="" class="rating-stars">
                                </div>
                                <input id="input-1" class="rating_ form-control hide" data-min="0" data-max="5" data-step="1">
                          	</div> <span>[<?php echo $services->globalvote; ?> vote(s)]</span>
                      	</div>
                    </div>
               	</div>
				<?php /* services.php | line 406 */ echo $services->button($services->ID, $user_->ID, $user->ID); ?>
			</div>
			<div class="servicepropose">
				<div class="row">
					<img width="85" alt="<?php echo $services->catname; ?>" height="85" src="img/services/<?php echo $services->cattype; ?>.jpg">
					<div class="header_annonce">
						<?php echo ucfirst($services->title); ?>
                  	</div>
				</div>
			</div>
			<div class="greyback row">
				<?php /* services.php | line 421 */ echo $services->annonces($user->ID); ?>
            </div>
			<div class="profiltitle">
				<p>Notes et commentaires</p>
			</div>
			<div class="pictodown">
				<img src="img/annonce/down.png" alt="" class="down">
			</div>
			<div class="notes">
				<?php /* user.php | line 1197 */ echo $user_->list_com($services->ID); ?>
          	</div>
		</div>
	</div>
	<footer id="footer">
		<img src="img/footer.png" width="30" alt="" height="18">
		<div class="container-fluid"> <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php">Contact</a>
            <hr>
            <a target="_blank" class="social" title="Voir notre page Facebook" href="https://www.facebook.com/SwappyLaPlateforme"><img height="30" width="30" alt="Facebook" src="img/social/facebook.png"></a>
            <a target="_blank" class="social" title="Voir notre page Twitter" href="https://twitter.com/_Swappy"><img height="30" width="30" alt="Twitter" src="img/social/twitter.png"></a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
		</div>
	</footer>
	<?php /* chat.php | line 566 */  $chat->prepare_popup($user_, $services); 
	/* chat.php | line 511 */ $chat->prepare_popup_report($user_, $services);  ?>
</body>
</html>