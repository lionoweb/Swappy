<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Swappy.fr - Contact</title>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="css/template.css" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
          		<a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img src="img/logonav.png"></a>
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
            		<li><a  href="services.html">Services <span class="sr-only">(current)</span></a></li>
            		<li><a href="propose.php">Je propose</a></li>
            		<li><a href="#">Comment ça marche ?</a></li>
            		<li><a href="#">A propos</a></li>
            	</ul>
             	<ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Connexion <span class="caret"></span></a>
                        <div class="dropdown-menu login-menu">
                            <div id="login_section">
                                <form action="inc/login.php" method="post" id="login_form">
                                    <input id="login_form" name="login_form" class="validate[required,minSize[5]]" placeholder="Identifiant" type="text" size="30">
                                    <input type="password" id="password_form" name="password_form" placeholder="Mot de passe" class="validate[required,minSize[6]]" size="30">
                                    <label class="string optional" for="user_remember_me">
                                        <input id="remember_me" type="checkbox" name="remember_me" checked> Se souvenir de moi
                                    </label>
                                    <input class="btn btn-primary" type="submit" name="commit" value="Se connecter">
                                    <div class="remind"><a class="remind_link">Mot de passe perdu ?</a></div>
                                </form>		
                            </div>
                            <div id="remind_section">
                                <form action="[YOUR ACTION]" method="post" accept-charset="UTF-8">
                                    <input id="user_username" placeholder="Email" type="text" name="remind[email]" size="30">
                                    <input class="btn btn-primary" type="submit" name="commit" value="Recuperer">
                                    <div class="remind"><a class="remind_link">Je m'en souviens !</a></div>
                                </form>
                            </div>
                        </div>
                                            </li>
      			</ul>
    		</div><!-- /.navbar-collapse -->
  		</div><!-- /.container-fluid -->
	</nav>

    <form class="col-md-6 col-md-offset-3 col-sm-12 container" id="spec_contact" action="contact.php" method="get">

        <div colspan="2" class="header_propose blueback">Une envie de nous contacter ?</div>

        <div class="greyback">

            <div class="form-group">
                <label for="prenom" class="control-label col-xs-12 col-sm-2">Prénom</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="prenom" name="name" type="text" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="nom" class="control-label col-xs-12 col-sm-2">Nom</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="nom" name="lastname" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="mail" class="control-label col-xs-12 col-sm-2">Email</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="mail" name="email" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="objet" class="control-label col-xs-12 col-sm-2">Objet</label>
                <div class="col-xs-12 col-sm-10">
                    <input id="objet" name="object" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="message" class="control-label col-xs-12 col-sm-2">Message</label>
                <div class="col-xs-12 col-sm-10">
                    <textarea id="message" class="form-control" name="msg" rows="8" cols="44"></textarea>
                </div>
            </div>  

            <div class="form-group">
                    <input type="submit" id="button" name="send" value="Envoyer">
            </div>
        </div>
    </form>

</div>
<!-- END WRAP -->

<footer id="footer">
    <div class="container-fluid">
        <a href="#">Mentions légales</a> | <a href="#">CGU</a> | <a href="contact.php" class="active">Contact</a>
    </div>
</footer>
</body>
</html>