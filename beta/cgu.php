<?php
session_start();
require_once("inc/user.php");
require_once("inc/mysql.php");
$user = new user($mysql);
if(isset($_GET['logout'])) {
	$user->logout();
}	?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Swappy.fr - CGU & Mentions légales</title>
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

    <div id="spec_cgu" class="container main" role="main">

    <div class="cgutitle"><p>Conditions générales d'utilisation</p></div>
        
            <div class="row">
                <div class="col-md-2 col-md-offset-1">
                <img src="img/cgu/objet.png">
                <span class="cgintitle">Objet</span>
            </div>
                <p class="col-md-10 col-md-offset-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam eget libero mauris. Praesent eget tortor rhoncus, malesuada justo eget, tempus nunc. Nullam non volutpat ligula. Nulla eget lorem ac dolor auctor cursus. Curabitur non nisi dictum, sodales turpis id, tincidunt leo. Ut finibus dolor a faucibus malesuada. Duis eget massa nulla. Integer diam erat, sagittis ac ornare eget, gravida id ex. Suspendisse egestas justo eu porta tempus. Praesent vel pulvinar nisi. Duis tellus massa, viverra vitae luctus sit amet, vulputate ac tellus. Suspendisse potenti.</p>
            </div>
        

    <div class="cgutitle"><p>Mentions légales</p></div>
        <div class="col-md-10 col-md-offset-1">
            <p>
                Pellentesque viverra tincidunt mollis. Phasellus luctus finibus sem, at molestie ante consequat id. Proin pulvinar faucibus quam. Aliquam quis mauris ligula. Quisque pretium arcu quis magna tempor elementum. Vestibulum iaculis, augue at suscipit posuere, quam turpis dignissim enim, sit amet dictum augue ante vitae arcu. Vivamus lacinia a ipsum sed iaculis. Maecenas eget commodo massa.

Nulla vehicula at nulla ut elementum. Vivamus pellentesque ligula eros, ac finibus nulla volutpat a. Morbi ex leo, lobortis tempus eros vel, pretium tempor lectus. Suspendisse potenti. Aenean molestie blandit velit hendrerit commodo. Aenean vel maximus sapien. Integer vitae enim ut justo ultricies ullamcorper eu tincidunt ligula. Pellentesque et velit dictum nisl scelerisque interdum. Donec fermentum erat eu tortor malesuada, eget ultrices lorem vulputate. Quisque ac lacus augue. Nunc egestas quam in sapien semper tempor. Nulla facilisi. Fusce a pharetra sem. Sed sem tellus, eleifend sed nisi in, porttitor elementum elit.

Nam non blandit quam. Donec aliquet nisl et nisi consequat, fringilla condimentum augue sollicitudin. Donec commodo non lorem sit amet porttitor. Phasellus euismod neque id porttitor pharetra. Nulla facilisi. Maecenas tincidunt interdum euismod. Sed blandit erat non neque vestibulum viverra. Curabitur lacinia nulla id porttitor lacinia. Integer in dolor sagittis, scelerisque ex nec, ultrices urna. Vivamus dictum lacus in volutpat dignissim. Vivamus vel odio vitae justo porttitor sodales eget non purus. Praesent venenatis leo sit amet sodales ultricies. Aenean rhoncus ac risus non lacinia.
            </p>
        </div>


</div>
</div>
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

