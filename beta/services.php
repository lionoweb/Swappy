<?php
   session_start();
   require_once("inc/user.php");
   require_once("inc/mysql.php");
   require("inc/services.php");
   require("inc/searches.php");
   $user = new user($mysql);
   $services = new services($mysql);
   $search = new search($mysql);
   if(isset($_GET['logout'])) {
   	$user->logout();
   }	?>
<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Swappy.fr - Services</title>
      <link rel="icon" href="img/favicon.png">
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Services</span>
               </div>
               <form class="navbar-form navbar-left search_navbar" method="get" role="search">
                  <div class="input-group">
                     <input id="searchbar" name="searchbar" type="text" class="form-control" placeholder="Rechercher">
                     <span class="input-group-btn">
                     <button title="Rechercher" type="submit" class="btn btn-default"></button>
                     </span>
                  </div>
               </form>
               <!-- Collect the nav links, forms, and other content for toggling -->
               <div class="collapse navbar-collapse" id="navbar">
                  <ul class="nav navbar-nav">
                     <li class="active"><a  href="services.php">Services <span class="sr-only">(current)</span></a></li>
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
         <div class="container main" role="main">
            <?php
               //INIT SEARCH FIELD VALUE
               $title = "";
               $type_s = "";
               $day_s = "";
               $where_s = "";
               if($user->logged && !empty($user->zipcode)) {
               	$where_s = $services->format_city($user->zipcode, $user->city);
               }
               if(isset($_GET['searchbar'])) {
               	$title = $_GET['searchbar'];
               }
               if(isset($_GET['type'])) {
               	$type_s = $_GET['type'];
               }
               if(isset($_GET['day'])) {
               	$day_s = $_GET['day'];
               }
               if(isset($_GET['where'])) {
               	$where_s = $_GET['where'];
               } else if(isset($_GET['searchbar']) && $user->logged && !empty($user->zipcode)) {
               	$where_s = $services->format_city($user->zipcode, $user->city);
               	$_GET['where'] = $where_s;
               }
               ?>
            <div id="spec_services">
               <form class="col-md-6 col-md-offset-3 form-horizontal nonullpad" id="spec_search" action="services.php" method="get">
                  <div class="form-group">
                     <label for="searchbar_" class="control-label col-xs-12 col-sm-2 left-text grey_text">Rechercher</label>
                     <div class="col-xs-12 col-sm-10 input-group">
                        <input id="searchbar_" name="searchbar" value="<?php echo $title; ?>" class="form-control" type="text">
                     </div>
                  </div>
                  <div class="blueback">
                     <div class="form-group">
                        <label for="type" class="control-label col-xs-12 col-sm-2">Catégorie</label>
                        <div class="col-xs-12 col-sm-10">
                           <?php
                              echo preg_replace('/value\=\"'.$type_s.'\"/', 'value="'.$type_s.'" selected', $services->list_categories(false));
                              ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="zipbar" class="control-label col-xs-12 col-sm-2">Où ?</label>
                        <div class="col-xs-12 col-sm-10">
                           <input id="zipbar" name="where" type="text" value="<?php echo $where_s; ?>" class="form-control" placeholder="Exemple : 75001 (Paris)">
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="day" class="control-label col-xs-12 col-sm-2">Quand ?</label>
                        <div class="col-xs-12 col-sm-10">
                           <select id="day" name="day" class="form-control">
                           <?php $list_days = '<option value="all">Tous les jours</option>'.
                              '<option value="weekend">Week-end</option>'.
                                               	'<option value="lun">Lundi</option>'.
                                                   '<option value="mar">Mardi</option>'.
                                                   '<option value="mer">Mercredi</option>'.
                                                   '<option value="jeu">Jeudi</option>'.
                                                   '<option value="ven">Vendredi</option>'.
                                                   '<option value="sam">Samedi</option>'.
                                                   '<option value="dim">Dimanche</option>';
                              echo preg_replace('/value\=\"'.$day_s.'\"/', 'value="'.$day_s.'" selected', $list_days);
                              ?>
                           </select>
                        </div>
                        <div class="send col-xs-12 col-sm-3 col-sm-offset-9">
                           <input type="submit" value="envoyer" class="form-control">
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <?php if(isset($_GET['searchbar'])) {
               ?>
            <?php $result = $search->search($_GET, $user); ?>
            <div class="col-md-10 col-md-offset-1 col-sm-12 table-responsive noborder">
               <table class="fulltable table">
                  <thead>
                     <tr>
                        <td colspan="2" class="header_search">Tous les services</td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo $result[0]; ?>
                  </tbody>
               </table>
               <?php echo $result[1]; ?>
            </div>
            <?php
               } else { //SERVICES RECENTS?>
            <?php $result = $search->recent_services($user); ?>
            <div class="col-md-10 col-md-offset-1 col-sm-12  table-responsive noborder">
               <table class="fulltable table">
                  <thead>
                     <tr>
                        <td colspan="2" class="header_search"><?php echo $result[0]; ?></td>
                     </tr>
                  </thead>
                  <tbody>
                     <?php echo $result[1]; ?>
                  </tbody>
               </table>
            </div>
            <?php } ?>
         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="mentions-legales.php">Mentions légales</a> - <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
      <?php $user->modal_location_c($_GET); ?>
   </body>
</html>