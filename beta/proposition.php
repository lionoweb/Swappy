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
 ?>
<html>
	<head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Swappy.fr - Mes propositions</title>
    	<link rel="icon" href="img/favicon.png">
    	<link rel="stylesheet" href="css/jquery-ui.css">
    	<link rel="stylesheet" href="css/jquery.datetimepicker.css">
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Mes propositions</span>
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
         <div class="container-fluid main" role="main" id="proposition">
         	<div class="col-md-10 col-md-offset-1 col-sm-12 table-responsive noborder">
         		<table class="fulltable table">
                  <thead>
                     <tr>
                        <td colspan="4" class="header_search">Mes propositions</td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $boucle = $user->list_services_edit();
					 for($i=0;$i<count($boucle);$i++) {
						 // A PARTIR D'EN DESSOUS C'EST LE HTML  ?>
                    <tr class="bloc_services">
                      <td class="picto"><img class="fullfit" src="<?php echo $boucle[$i]['Image']; ?>"></td>
                      <td class="desc_services">
                        <a href="annonce.php?id=<?php echo $boucle[$i]['ID']; ?>">
                          <div class="fullfit">
                            <h1><?php echo $boucle[$i]['Title']; ?></h1>
                            <p>
                              <?php if($boucle[$i]['Description'] != "Pas de description...") {echo $boucle[$i]['Description'];} ?></p>
                            <div class="location"><?php echo $boucle[$i]['CityName']; ?></div>
                          </div>
                        </a>
                      </td>
                      <td class="delete">
                        <a href="#">
                          <img src="img/proposition/delete.png" width="25">
                        </a>
                      </td>
                      <td class="edit">
                        <a href="propose.php?edit=<?php echo $boucle[$i]['ID']; ?>">
                          <img src="img/proposition/edit.png" width="25">
                        </a>
                      </td>
                    </tr>
                    
                     <?php //FIN
					  } ?>
                  </tbody>
               </table>
         	</div>
         	
         </div>
      </div>
      <!-- ### END WRAP ### -->
    <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
  </body>
</html>