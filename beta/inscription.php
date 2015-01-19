<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
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
                    <li><a href="services.php">Services</a></li>
                    <li><a href="propose.php">Je propose</a></li>
                    <li><a href="ccm.php">Comment ça marche ?</a></li>
                    <li><a href="apropos.php">A propos</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                    <?php if(!$user->logged) {?>
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
                        <?php } else { ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bonjour <?php echo $user->firstname; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="?logout">Se deconnecter</a></li>
                            </ul>
                        <?php } ?>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>


    <div class="container" role="main">

    <form id="user_add" class="col-md-6 col-md-offset-3 form-horizontal" action="inc/add_user.php" method="post">

    <div class="greyback">
	<div class="result_form"></div>
    <div class="form-group">
        <label for="mon_fichier">Ajouter une photo de profil (max. 1 Mo)</label><br>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
        <input type="file" name="mon_fichier" id="mon_fichier"><br>
    </div>
	<div class="form-group">
		<label for="login" class="control-label col-xs-12 col-sm-2">Login*</label>
        <div class="col-xs-12 col-sm-10">
            <input required autocomplete="off" class="validate[required,minSize[5],ajax[ajaxLoginCallPHP]]" autofocus data-key="true" type="text" id="login" name="login">
        </div>
    </div>
    <div class="form-group">
		<label for="password" class="control-label col-xs-12 col-sm-2">Mot de passe*</label>
        <div class="col-xs-12 col-sm-10">
            <input required autocomplete="off" class="validate[required,minSize[6]]" id="password" type="password" name="password">
        </div>
    </div>
    <div class="form-group">
    	<label for="password_r" class="control-label col-xs-12 col-sm-2">Retaper mot de passe*</label>
        <div class="col-xs-12 col-sm-10">
            <input required autocomplete="off" class="validate[required,minSize[6],equalsPASS[password]]" type="password" id="password_r" name="password_r">
        </div>
    </div>
    <div class="form-group">
    	<label for="email" class="control-label col-xs-12 col-sm-2">Adresse e-mail*</label>
        <div class="col-xs-12 col-sm-10">
            <input required autocomplete="off" class="validate[required,custom[email],ajax[ajaxEmailCallPHP]]" data-key="true" id="email" type="text" name="email">
        </div>
    </div>
    <div class="form-group">
    	<label for="lastname" class="control-label col-xs-12 col-sm-2">Nom*</label>
        <div class="col-xs-12 col-sm-10">
            <input required autocomplete="off" class="validate[required]" type="text" id="lastname" name="lastname">
        </div>
    </div>
    <div class="form-group">
    	<label for="firstname" class="control-label col-xs-12 col-sm-2">Prénom*</label>
        <div class="col-xs-12 col-sm-10">
            <input required  autocomplete="off" class="validate[required]" type="text" id="firstname" name="firstname">
        </div>
    </div>
    <div class="form-group">
    	<label for="gender" class="control-label col-xs-12 col-sm-2">Sexe*</label>
        <div class="col-xs-12 col-sm-10">
            <select required name="gender">
    		  <option value="M">Homme</option>
    		  <option value="F">Femme</option>
    	   </select>
        </div>
    </div>
    <div class="form-group">
    	<label for="phone" class="control-label col-xs-12 col-sm-2">Numéro de téléphone</label>
        <div class="col-xs-12 col-sm-10">
            <input maxlength="10" class="validate[custom[phone],custom[onlyNumberSp],maxSize[10],minSize[10]]" id="phone" autocomplete="off" type="text" name="phone">
        </div>
    </div>
    <div class="form-group">
    	<label for="day" class="control-label col-xs-12 col-sm-2">Date de naissance* :</label>
        <div class="col-xs-12 col-sm-10">
            <select id="day" name="day">
			 <?php for($i=1;$i<32;$i++) { 
					$o = $i; 
					if($o < 10) $o = "0".$o;
					echo '<option value="'.$o.'">'.$o.'</option>'; 
				} ?>
            </select> 
    	<select name="month">
			<?php for($i=1;$i<13;$i++) { 
					$o = $i; 
					if($o < 10) $o = "0".$o; 
					echo '<option value="'.$o.'">'.$o.'</option>'; 
				} ?></select> 
    	<select name="year">
			<?php for($i=(date("Y")-17);$i>1940;$i--) { 
					echo '<option value="'.$i.'">'.$i.'</option>'; 
				} ?>
       	</select>
       </div>
    </div>
    <div class="form-group">
    	<label for="street" class="control-label col-xs-12 col-sm-2">Adresse</label>
        <div class="col-xs-12 col-sm-10">
            <input autocomplete="off" type="text" id="street" name="street">
        </div>
    </div>
    <div class="form-group">
    	<label for="zipcode" class="control-label col-xs-12 col-sm-2">Code Postal*</label>
        <div class="col-xs-12 col-sm-10">
            <input required class="validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]]" autocomplete="off" maxlength="5" type="text" id="zipcode" name="zipcode">
        </div>
    </div>
    <div class="form-group">
        <label for="cityname" class="control-label col-xs-12 col-sm-2">Ville*</label>
        <div class="col-xs-12 col-sm-10">
    	   <input type="text" autocomplete="off" class="html_like" contenteditable="false" readonly id="cityname" name="cityname">
        </div>
    </div>
    <div class="form-group">
        <input type="checkbox" name="accept" value="">J'ai lu et j'accepte les conditions générales d'utilisation et les mentions légales du site Swappy
    </div>

    <input type="submit" value="S'enregistrer">
</div>
</div>
</form>
</body>
</html>