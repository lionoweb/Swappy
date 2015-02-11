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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.jpg" class="min"></a>
                  <span class="brand-title">Mentions légales - CGU</span>
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
         <div id="spec_cgu" class="container-fluid main" role="main">
            <div class="cgutitle">
               <p>Conditions générales d'utilisation</p>
            </div>
            <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
            <div class="col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1 box1 box">
               <div class="row">
                  <img src="img/cgu/objet.png">
                  <div class="header_cgu">Objet</div>
               </div>
               <p class="greyback">Les présentes Conditions d’Utilisation (CGU) établissent les conditions contractuelles entre swappy.com et ses utilisateurs.</p>
            </div>
            <div class="col-md-4 col-md-offset-0 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box2 box">
               <div class="row">
                  <img src="img/cgu/site.png" class="pic">
                  <div class="header_cgu">Accès au site</div>
               </div>
               <p class="greyback">L’inscription au site swappy.com est réservée aux personnes majeures (âgées de 18 ans ou plus). Tout accès et/ou utilisation du site swappy.com suppose l’acceptation et le respect de l’ensemble des termes des présentes Conditions d’Utilisation.Swappy.com se réserve le droit de bannir temporairement ou définitivement tout membre qui aurait violé ces Conditions d’Utilisation. En s'inscrivant au site swappy.fr, l'utilisateur s'engage à mettre en ligne une photo d'eux sous 7 jours.</p>
            </div>
            <div class="col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1 box3 box">
               <div class="row">
                  <img src="img/cgu/propriete.png" class="pic">
                  <div class="header_cgu">Propriété intellectuelle</div>
               </div>
               <p class="greyback">L’inscription au site swappy.com est réservée aux personnes majeures (âgées de 18 ans ou plus). Tout accès et/ou utilisation du site swappy.com suppose l’acceptation et le respect de l’ensemble des termes des présentes Conditions d’Utilisation.Swappy.com se réserve le droit de bannir temporairement ou définitivement tout membre qui aurait violé ces Conditions d’Utilisation. En s'inscrivant au site swappy.fr, l'utilisateur s'engage à mettre en ligne une photo d'eux sous 7 jours.</p>
            </div>
            <div class="col-md-4 col-md-offset-0 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box4 box">
               <div class="row">
                  <img src="img/cgu/perso.png" class="pic">
                  <div class="header_cgu">Données personnelles</div>
               </div>
               <p class="greyback">Les données personnelles des utilisateurs restent strictement confidentielles et sont stockées exclusivement sur nos serveurs. Elles ne sont en aucun cas vendues, données ou échangées.Les utilisateurs s’engagent à fournir des informations exactes.
                  <br>Swappy.com conserve une copie des messages échangés par l’intermédiaire de notre serveur afin de pouvoir s’assurer du respect déontologique des échanges.
               </p>
            </div>
            <div class="col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1 box5 box">
               <div class="row">
                  <img src="img/cgu/responsabilite.png" class="pic">
                  <div class="header_cgu">Responsabilité</div>
               </div>
               <p class="greyback">Swappy.com ne pourra être tenu responsable en cas de dommages directs ou indicrects subis par un utilisateur.</p>
            </div>
            <div class="col-md-4 col-md-offset-0 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box6 box">
               <div class="row">
                  <img src="img/cgu/annonce.png" class="pic">
                  <div class="header_cgu">Annonces</div>
               </div>
               <p class="greyback">Swappy.com est une plateforme d’échanges de services qui vise à faciliter la mise en relation entre particuliers. La publication des annonces est gratuite. Les annonces restent en ligne pendant une période indéterminée et peuvent être modifiées ou supprimées (lorsque ces dernières ne sont plus d’actualité) à tout moment, et ceci gratuitement.
                  En déposant une annonce, le membre déclare avoir la capacité juridique nécessaire et toutes les autorisations nécessaires pour proposer l’échange du service. Si vous jugez le contenu d’une annonce inapproprié, nous vous invitons à le signaler via le bouton situé sur la page de l’annonce ou à nous contacter via le formulaire de contact pour nous en informer.
               </p>
            </div>
            <div class="col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box7 box">
               <div class="row">
                  <img src="img/cgu/interdiction.png" class="pic">
                  <div class="header_cgu">Interdiction</div>
               </div>
               <p class="greyback">Il est interdit de proposer plusieurs services dans le texte d’une même annonce. Le langage sms et/ou l’écriture abrégée sont proscris afin d’obtenir une meilleure compréhension entre utilisateurs et un référencement optimal.</p>
            </div>
            <div class="col-md-4 col-md-offset-0 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box8 box">
               <div class="row">
                  <img src="img/cgu/messagerie.png" class="pic">
                  <div class="header_cgu">Utilisation de la messagerie</div>
               </div>
               <p class="greyback">Swappy.com met à disposition des utilisateurs un système de messagerie interne leur permettant de s’échanger des informations à propos des services. S’ils le souhaitent, les utilisateurs peuvent spécifier leurs coordonnées dans un message en vue d’une mise en relation. Les précautions et mesures de prudence prises lors de cette mise en relation doivent être définies par les utilisateurs eux-mêmes et n’engagent en aucun cas Swappy.com.</p>
            </div>
            <div class="col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-0 col-xs-10 col-xs-offset-1 box9 box">
               <div class="row">
                  <img src="img/cgu/suppression.png" class="pic">
                  <div class="header_cgu">Suppresion de compte</div>
               </div>
               <p class="greyback">En cas de non respect des Conditions Générales d’Utilisation, les administrateurs du site swappy.fr pourront envoyer des avertissements aux utilisateurs concernés. Cet avertissement pourra survenir après la signalisation d’une infraction par un autre utilisateur, après que les administrateurs aient vérifié ladite infraction.</p>
            </div>
         </div>
         <div id="spec_mention">
            <div class="cgutitle">
               <p>Mentions légales</p>
            </div>
            <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
            <div class="col-md-10 col-md-offset-1">
               <p>
                  Tous les sites internet édités à titre professionnel, qu'ils proposent des ventes en ligne ou non, doivent obligatoirement indiquer les mentions légales suivantes :</span>
               <ul>
                  <li>pour un entrepreneur individuel : nom, prénom, domicile,</li>
                  <li>pour une société : raison sociale, forme juridique, adresse de l'établissement ou du siège social (et non pas une simple boîte postale), montant du capital social,</li>
                  <li>adresse de courrier électronique et numéro de téléphone,</li>
                  <li>pour une activité commerciale : numéro d'inscription au registre du commerce et des sociétés (RCS),</li>
                  <li>pour une activité artisanale : numéro d'immatriculation au répertoire des métiers (RM),</li>
                  <li>numéro individuel d'identification fiscale (numéro de TVA intracommunautaire),</li>
                  <li>pour une profession réglementée : référence aux règles professionnelles applicables et au titre professionnel,</li>
                  <li>nom et adresse de l'autorité ayant délivré l'autorisation d'exercer quand celle-ci est nécessaire,</li>
                  <li>nom du responsable de la publication,</li>
                  <li>coordonnées de l'hébergeur du site : nom, dénomination ou raison sociale, adresse et numéro de téléphone,</li>
                  <li>pour un site marchand, conditions générales de vente (CGV) : prix (exprimé en euros et TTC), frais et date de livraison, modalité de paiement, service après-vente, droit de rétractation, durée de l'offre, coût de la technique de communication à distance,
                  <li>numéro de déclaration simplifiée Cnil, dans le cas de collecte de données sur les clients.</li>
               </ul>
               Le manquement à l'une de ces obligations peut être sanctionné jusqu'à un an d'emprisonnement, 75 000 € d'amende pour les personnes physiques et 375 000 € pour les personnes morales.
               </p>
            </div>
         </div>
      </div>
      </div>
      <!-- END WRAP -->
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="mentions-legales.php">Mentions légales</a> - <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
   </body>
</html>