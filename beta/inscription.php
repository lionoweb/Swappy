<?php
session_start();
require_once("inc/user.php");
require_once("inc/mysql.php");
$user = new user($mysql);
if(isset($_GET['logout'])) {
	$user->logout();
}
$user->onlyVisitors();	?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Swappy.fr - Inscription</title>
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
                    <li><a  href="services.php">Services</a></li>
                    <li><a href="propose.php">Je propose</a></li>
                    <li><a href="ccm.php">Comment ça marche ?</a></li>
                    <li><a href="apropos.php">A propos</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php echo $user->navbar(); ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
	<div class="container main" role="main">
 		<?php if(isset($_GET['remind'])) { $mm = $user->uncrypt_remind($_GET['remind']); ?>
        	<?php if($user->prevent_ex_remind($mm[0], $mm[2])) { ?>
			<form id="user_remind" class="col-md-6 col-md-offset-3" action="inc/add_user.php" method="post">
            <div colspan="2" class="header_inscription">Changement du mot de passe</div>

            <div class="greyback">
            	<div class="form-group">
                    <label for="email" class="control-label col-xs-12 col-sm-2 col-md-4">Email :</label>
                    <div class="col-xs-12 col-sm-10 col-md-8">
                        <input autocomplete="off" value="<?php echo $mm[1]; ?>" class="liketext form-control fullwidth" disabled type="text" id="email" name="email">
                        <input autocomplete="off" value="<?php echo $_GET['remind']; ?>" type="hidden" name="hash" >
                    </div>
            	</div>
                <div class="form-group">
        		<label for="password" class="control-label col-xs-12 col-sm-2 col-md-4">Mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,minSize[6]] form-control" id="password" type="password" name="password">
                </div>
            </div>

            <div class="form-group">
            	<label for="password_r" class="control-label col-xs-12 col-sm-2 col-md-4">Retaper mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,minSize[6],equalsPASS[password]] form-control" type="password" id="password_r" name="password_r">
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="form-control" value="Modifier">
            </div>
            </div>
        
        </form>
        <?php } else { ?>
        	<div id="user_add" class="col-md-6 col-md-offset-3">
            <div colspan="2" class="header_inscription">Changement du mot de passe</div>
    
            <div class="greyback">
            	<p>Désolé, mais ce lien a déjà servis pour la modification de votre mot de passe.</p>
            </div>
        </div>
        <?php } ?>
		<?php } else if(isset($_GET['validation'])) { ?>
        <div id="user_add" class="col-md-6 col-md-offset-3">
            <div colspan="2" class="header_inscription">Validation d'inscription</div>
    
            <div class="greyback">
            	<?php echo $user->validate_account($_GET['validation']); ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="header_propose">
            <p class="col-md-6 col-md-offset-3 top">Inscrivez-vous</p>
            <p class="col-md-6 col-md-offset-3 bot">Créez votre compte afin de rentrer en contacter et échanger des services avec les utilisateurs</p>
        </div>
        <div colspan="2" class="title_propose">Inscription</div>
            <div class="greyback">
        <form id="user_add" class="col-md-6 col-md-offset-3" action="inc/add_user.php" method="post">
        
        	<div class="form-group">
        		<label for="login" class="control-label col-xs-12 col-sm-2 col-md-4">Identifiant*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,minSize[5],ajax[ajaxLoginCallPHP]] form-control" autofocus data-key="true" type="text" id="login" name="login">
                </div>
            </div>

            <div class="form-group">
        		<label for="password" class="control-label col-xs-12 col-sm-2 col-md-4">Mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,minSize[6]] form-control" id="password" type="password" name="password">
                </div>
            </div>

            <div class="form-group">
            	<label for="password_r" class="control-label col-xs-12 col-sm-2 col-md-4">Retaper mot de passe*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,minSize[6],equalsPASS[password]] form-control" type="password" id="password_r" name="password_r">
                </div>
            </div>

            <div class="form-group">
            	<label for="email" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse e-mail*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required,custom[email],ajax[ajaxEmailCallPHP]] form-control" data-key="true" id="email" type="text" name="email"> 
                </div>
            </div>

            <div class="form-group">
            	<label for="lastname" class="control-label col-xs-12 col-sm-2 col-md-4">Nom*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required] form-control" type="text" id="lastname" name="lastname">
                </div>
            </div>

            <div class="form-group">
            	<label for="firstname" class="control-label col-xs-12 col-sm-2 col-md-4">Prénom*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" class="validate[required form-control" type="text" id="firstname" name="firstname">
                </div>
            </div>

            <div class="form-group">
            	<label for="gender" class="control-label col-xs-12 col-sm-2 col-md-4">Sexe*</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <select name="gender" class="form-control">
            		  <option value="M">Homme</option>
            		  <option value="F">Femme</option>
            	   </select>
                </div>
            </div>

            <div class="form-group">
            	<label for="phone" class="control-label col-xs-12 col-sm-2 col-md-4">Numéro de téléphone</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input maxlength="10" class="validate[custom[phone],custom[onlyNumberSp],maxSize[10],minSize[10]] form-control" id="phone" autocomplete="off" type="text" name="phone">
                </div>
            </div>

            <div class="form-group">
            	<label for="day" class="control-label col-xs-12 col-sm-2 col-md-4">Date de naissance* :</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <select id="day" name="day" class="form-control birthday">
        			 <?php for($i=1;$i<32;$i++) { 
        					$o = $i; 
        					if($o < 10) $o = "0".$o;
        					echo '<option value="'.$o.'">'.$o.'</option>'; 
        				} ?>
                    </select> 
            	<select name="month" class="form-control birthmonth">
        			<?php for($i=1;$i<13;$i++) { 
        					$o = $i; 
        					if($o < 10) $o = "0".$o; 
        					echo '<option value="'.$o.'">'.$o.'</option>'; 
        				} ?></select> 
            	<select name="year" class="form-control birthyear">
        			<?php for($i=(date("Y")-18);$i>1940;$i--) { 
        					echo '<option value="'.$i.'">'.$i.'</option>'; 
        				} ?>
               	</select>
               </div>
            </div>

            <div class="form-group">
            	<label for="street" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse :</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input autocomplete="off" type="text" id="street" name="street" class="form-control">
                    <input autocomplete="off" type="text" id="street" name="street2" class="form-control">
                </div>
            </div>

            <div class="form-group">
            	<label for="zipcode" class="control-label col-xs-12 col-sm-2 col-md-4">Code Postal* :</label>
                <div class="col-xs-12 col-sm-10 col-md-8">
                    <input class="zipcode validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]] form-control" autocomplete="off" maxlength="5" type="text" id="zipcode" name="zipcode" placeholder="Ex : 94500"> <input autocomplete="off" type="text" readonly class="form-control liketext" name="cityname">
                </div>
            </div>

            <div class="form-group">
                <input id="accept" class="validate[required]" type="checkbox" name="accept">
                <label for="accept" class="col-md-10 col-md-offset-1 lu">J'ai lu et j'accepte les <a target="_blank" href="cgu.php">conditions générales d'utilisation</a> et les <a target="_blank" href="mentions-legales.php">mentions légales</a> du site Swappy</label>
            </div>

            <div class="form-group col-sm-4 col-sm-offset-8 col-xs-12">
                <input type="submit" class="form-control col-md-4" value="S'enregistrer">
            </div>
        </form>
            <?php } ?>    

    </div>
</div>
</div>
<!-- END WRAP -->
<footer id="footer">
    <img src="img/footer.png">
    <div class="container-fluid">
        <a href="mentions-legales.php">Mentions légales</a> - <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
        <hr>
        <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
    </div>
</footer>
</body>
</html>