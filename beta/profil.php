<?php
   session_start();
   require_once("inc/user.php");
   require_once("inc/mysql.php");
   $user = new user($mysql);
   if(isset($_GET['logout'])) {
   	$user->logout();
   }
   $user->onlyUsers();
   $user_ = new user($mysql);
?>
<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Swappy.fr - Profil de <?php echo $user->firstname." ".$user->lastname; ?></title>
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
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
         </nav>
         <div id="profil" class="container-fluid main" role="main">

            <div class="header_profil">
               <div class="row">
                  <div class="top">
                     <div class="col-md-2 col-md-offset-3">
                        <img src="img/user/M.jpg">
                     </div>
                     <div class="infos col-md-7">
                        <p class="nom"><?php echo $user->firstname." ".$user->lastname; ?></p>
                        <p class="">
                           <?php
                              $birthDate = $user->birthdate;
                              $birthDate = explode("-", $birthDate);
                              $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                              echo $age." ans ";
                           ?><span><img src="img/profil/location.png" alt=""><?php echo $user->city;?></span>
                        </p>
                        <div class="col-lg-8 col-md-8 tags">
                           <p>manuel</p>
                           <p>bricoleur</p>
                        </div>
                     </div>
                  </div>
                  <div class="text-left col-lg-2">
               <p class="text-justify col-md-6 col-md-offset-3">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.
               </p>
                  </div>
               </div>
               <div class="row small">
               </div>
            </div>

            <div class="profiltitle">
               <p>Services</p>
            </div>
            <div class="pictodown">
               <img src="img/profil/down.png" alt="" class="down">
            </div>

            <div class="profiltitle">
               <p>Notes et commentaires</p>
            </div>
            <div class="pictodown">
               <img src="img/profil/down.png" alt="" class="down">
            </div>
            <div class="row notes">
               <div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
                  <p>Blabla 1<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
               </div>
               <div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
                  <p>Blabla 2<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
               </div>
               <div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
                  <p>Blabla 3<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
               </div>
            </div>
         </div>
      </div>
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