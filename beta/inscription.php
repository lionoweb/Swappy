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
    <link rel="stylesheet" href="css/ajout_line_22:12.css" type="text/css"/>
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
                    <li><a  href="services.php">Services</a></li>
                    <li><a href="propose.php">Je propose</a></li>
                    <li><a href="#">Comment ça marche ?</a></li>
                    <li><a href="apropos.html">A propos</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(!$user->logged) {?>
                        <li class="active"><a href="inscription.php">Inscription  <span class="sr-only">(current)</span></a></li>
                        <?php } ?>
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


    <form id="user_add" class="col-md-6 col-md-offset-3" action="inc/add_user.php" method="post">

    <div colspan="2" class="header_inscription">Inscription</div>


        <div class="greyback">

        	<div class="form-group">
        		<label for="login" class="control-label col-xs-12 col-sm-2 col-md-4">Identifiant*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required autocomplete="off" class="validate[required,minSize[5],ajax[ajaxLoginCallPHP]] form-control" autofocus data-key="true" type="text" id="login" name="login">
                </div>
            </div>

            <div class="form-group">
        		<label for="password" class="control-label col-xs-12 col-sm-2 col-md-4">Mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required autocomplete="off" class="validate[required,minSize[6]] form-control" id="password" type="password" name="password">
                </div>
            </div>

            <div class="form-group">
            	<label for="password_r" class="control-label col-xs-12 col-sm-2 col-md-4">Retaper mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required autocomplete="off" class="validate[required,minSize[6],equalsPASS[password]] form-control" type="password" id="password_r" name="password_r">
                </div>
            </div>

            <div class="form-group">
            	<label for="email" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse e-mail*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required autocomplete="off" class="validate[required,custom[email],ajax[ajaxEmailCallPHP]] form-control" data-key="true" id="email" type="text" name="email"> 
                </div>
            </div>

            <div class="form-group">
            	<label for="lastname" class="control-label col-xs-12 col-sm-2 col-md-4">Nom*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required autocomplete="off" class="validate[required] form-control" type="text" id="lastname" name="lastname">
                </div>
            </div>

            <div class="form-group">
            	<label for="firstname" class="control-label col-xs-12 col-sm-2 col-md-4">Prénom*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required  autocomplete="off" class="validate[required form-control" type="text" id="firstname" name="firstname">
                </div>
            </div>

            <div class="form-group">
            	<label for="gender" class="control-label col-xs-12 col-sm-2 col-md-4">Sexe*</label>
                <div class="col-xs-12 col-sm-10 col-md-3">
                    <select required name="gender" class="form-control">
            		  <option value="M">Homme</option>
            		  <option value="F">Femme</option>
            	   </select>
                </div>
            </div>

            <div class="form-group">
            	<label for="phone" class="control-label col-xs-12 col-sm-2 col-md-4">Numéro de téléphone</label>
                <div class="col-xs-12 col-sm-10 col-md-4">
                    <input maxlength="10" class="validate[custom[phone],custom[onlyNumberSp],maxSize[10],minSize[10]] form-control" id="phone" autocomplete="off" type="text" name="phone">
                </div>
            </div>

            <div class="form-group">
            	<label for="day" class="control-label col-xs-12 col-sm-2 col-md-4">Date de naissance* :</label>
                <div class="col-xs-12 col-sm-10 col-md-4">
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
            	<label for="street" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input autocomplete="off" type="text" id="street" name="street" class="form-control">
                    <input autocomplete="off" type="text" id="street" name="street2" class="form-control">
                </div>
            </div>

            <div class="form-group">
            	<label for="zipcode" class="control-label col-xs-12 col-sm-2 col-md-4">Code Postal*</label>
                <div class="col-xs-12 col-sm-10 col-md-6">
                    <input required class="validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]] form-control" autocomplete="off" maxlength="5" type="text" id="zipcode" name="zipcode" placeholder="Ex : 94500 (Champigny-Sur-Marne)">
                </div>
            </div>

            <div class="form-group">
                <input id="accept" type="checkbox" name="ok" value="">
                <label for="accept" class="lu col-md-10">J'ai lu et j'accepte les <a href="cgu.php">conditions générales d'utilisation</a> et les <a href="cgu.php">mentions légales</a> du site Swappy</label>
            </div>

            <div class="form-group">
                <input type="submit" value="S'enregistrer">
            </div>
        </div>
        <!-- END GREYBACK -->
    </form>

    </div>
    <!-- END WRAP -->
<footer id="footer">
    <img src="img/footer.png">
    <div class="container-fluid">
        <a href="mentions-legales.php">Mentions légales</a> | <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
        <hr>
        <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
    </div>
</footer>
</body>
</html>