<?php
   session_start();
   require_once("inc/user.php");
   require_once("inc/mysql.php");
   $user = new user($mysql);
   if(isset($_GET['logout'])) {
   	$user->logout();
   }	?>
<!doctype html>
<html lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Swappy.fr - CGU & Mentions légales</title>
      <link rel="icon" href="img/favicon.png">
      <link rel="stylesheet" href="css/jquery-ui.css">
      <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="css/main.css">
      <script src="js/jquery.js"></script>
      <script src="js/jquery-ui.js"></script>
      <script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
      <script src="js/ValidationEngine/jquery.validationEngine.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/main.js"></script>
      <!--[if lt IE 9]>
      <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img alt="" width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" alt="" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Mentions légales - CGU</span>
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
         <div id="spec_cgu_mention" class="container-fluid main" role="main">
            <div class="cgutitle">
               <p>Conditions générales d'utilisation</p>
               <span class="shrink">CGU</span>
            </div>
            <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"></div>
               <section class="cgu_wrap">
                  <ul class="box">
                     <li>
                        <div class="row">
                           <img src="img/cgu/objet.png" alt="">
                           <div class="header_cgu"><p class="short">Acceptation des Conditions Générales d'Utilisation</p>
                              <span class="shortage">Acceptation des CGU</span>
                           </div>
                           
                        </div>
                        <p class="greyback">Les présentes Conditions d’Utilisation (CGU) établissent les conditions contractuelles entre swappy.fr et ses utilisateurs. <br>En acceptant ces Conditions Générales d’Utilisation, l’utilisateur s’engage à respecter les règles d’utilisation que les modérateurs auront précédemment établi. Accepter ces conditions correspond à une signature électronique qui engage un contrat entre l’utilisateur et swappy.fr. <br>En contractant, l’utilisateur a pris connaissance de l’intégralité des conditions qui lui sont imposées pour pouvoir utiliser la plateforme.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/site.png" alt="" class="pic">
                           <div class="header_cgu"><p>Accès au site</p></div>
                        </div>
                        <p class="greyback">L’inscription au site swappy.fr est réservé aux personnes majeures (âgées de 18 ans ou plus). Tout accès et/ou utilisation du site swappy.fr suppose l’acceptation et le respect de l’ensemble des termes des présentes Conditions d’Utilisation. <br>Swappy.fr se réserve le droit de bannir temporairement ou définitivement tout membre qui aurait violé ces Conditions d’Utilisation. <br>Le site est accessible 7j/7 et 24h/24. Il pourrait être temporairement inaccessible pour cause de maintenance ou de problèmes techniques qui relèveraient de notre entière responsabilité.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/use.png" alt="" class="pic">
                           <div class="header_cgu"><p>Utilisation du site</p></div>
                        </div>
                        <p class="greyback">L’utilisateur est seul maître de son utilisation. Il est responsable des informations qu’il transmet à travers ce site. Swappy.fr s’engage à ne pas diffuser les informations que l’utilisateur transmet à la plateforme. Il est proscrit aux utilisateurs de transmettre des informations erronées. Swappy.fr veut garantir l’exactitude des informations qu’il transmet. De ce fait, les utilisateurs prennent l’engagement de ne pas rédiger de messages comportant des termes injurieux, obscènes, illicites ou grossiers envers autrui. De même, il s’engage à ne pas rédiger d'éléments publicitaires d’un site concurrent ou de tout autre site afin de ne pas spammer les autres utilisateurs. Le fait de rédiger des éléments répréhensibles par la loi et tout autre comportement qui porterait préjudice au site est passible de poursuite. <br><strong>En cas de non-respect des conditions de rédaction des annonces ou des messages destinés à d’autres personnes</strong>, le compte de l’utilisateur sera supprimé sans préavis.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/propriete.png" alt="" class="pic">
                           <div class="header_cgu"><p>Propriété intellectuelle</p></div>
                        </div>
                        <p class="greyback">Swappy.fr se couvre de toute reproduction malveillante de son nom de domaine, de son logo et de son graphisme. Le Copyright indique aux utilisateurs que Swappy.fr est la propriété des administrateurs. Toute infraction à ces droits sera considérée comme un préjudice engageant la responsabilité civile et/ou pénale de son auteur.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/perso.png" alt="" class="pic">
                           <div class="header_cgu"><p>Données personnelles</p></div>
                        </div>
                        <p class="greyback">Les données personnelles des utilisateurs restent strictement confidentielles et sont stockées exclusivement sur nos serveurs. Elles ne sont en aucun cas vendues, données ou échangées. Les utilisateurs s’engagent à fournir des informations exactes. <br>Swappy.fr s’engage à ne conserver aucune conversation que les utilisateurs auraient échangé dans un souci de respect de la vie privée. Cependant, en cas de litige, l’utilisateur  pourra nous faire parvenir des copies des conversations afin que l’exploitant puisse juger de la situation.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/responsabilite.png" alt="" class="pic">
                           <div class="header_cgu"><p>Responsabilité</p></div>
                        </div>
                        <p class="greyback">Swappy.fr est une plateforme mettant en lien les utilisateurs. Le bon déroulement des rendez-vous n’est pas la responsabilité des administrateurs du site. Nous mettons en place un système de mise en lien. Toute personne s’engageant en prenant un rendez-vous s’engage à maximiser la prudence lors d’un déplacement chez l’interlocuteur ainsi que lors de la réalisation d’un service. <br>Les utilisateurs doivent être mutuellement couverts par la sécurité sociale et doivent avoir un logement assuré. En effet, en cas de problème, swappy.fr ne sera tenu pour responsable. Nous mettons en place un système de notation des profils qui permet de guider l’utilisateur vers l’interlocuteur qui lui parait le plus fiable. Dans le cas d’un litige durant un service rendu, nous vous prions de nous prévenir à l’aide de notre formulaire de contact pour que nous puissions agir en conséquence.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/annonce.png" alt="" class="pic">
                           <div class="header_cgu"><p>Annonces</p></div>
                        </div>
                        <p class="greyback">Swappy.fr est une plateforme d’échanges de services qui vise à faciliter la mise en relation entre particuliers. <br>La publication des annonces est gratuite. <br>Les annonces restent en ligne pendant une période indéterminée et peuvent être modifiées ou supprimées (lorsque ces dernières ne sont plus d’actualité) à tout moment, et ceci gratuitement. <br>En déposant une annonce, le membre déclare avoir la capacité juridique nécessaire et toutes les autorisations nécessaires pour proposer l’échange du service. Si vous jugez le contenu d’une annonce inapproprié, nous vous invitons à le signaler via le bouton situé sur la page de l’annonce ou à nous contacter via le formulaire de contact pour nous en informer.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/interdiction.png" alt="" class="pic">
                           <div class="header_cgu"><p>Interdiction</p></div>
                        </div>
                        <p class="greyback">Il est interdit de proposer plusieurs services au sein d’une même annonce. Le langage sms et/ou l’écriture abrégée sont proscrits afin d’obtenir une meilleure compréhension, un meilleur confort aux utilisateurs et un référencement optimal. <br>Nous rappelons aux utilisateurs que swappy.fr est une plateforme d’entraide. Celle-ci a pour but de mettre en lien des personnes voulant partager une expérience. Swappy permet donc d’échanger des services et uniquement des services. <br>Toute proposition de service inapproprié entraînera une suppression de l’annonce ainsi qu’une exclusion temporaire ou définitive de l’utilisateur. <br>De même, tout contenu inapproprié ou abus de langage entraînera une suppression de l’annonce sans préavis ni consentement de l’utilisateur ayant posté ce contenu. Un mail lui sera adressé pour lui faire part des mesures prises par les administrateurs.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/messagerie.png" alt="" class="pic">
                           <div class="header_cgu"><p>Utilisation de la messagerie</p></div>
                        </div>
                        <p class="greyback">Swappy.fr met à disposition des utilisateurs un système de messagerie interne leur permettant de s’échanger des informations à propos des services. S’ils le souhaitent, les utilisateurs peuvent spécifier leurs coordonnées dans un message en vue d’une mise en relation. Les précautions et mesures de prudence prises lors de cette mise en relation doivent être définies par les utilisateurs eux-mêmes et n’engagent en aucun cas swappy.fr. <br>La prise de rendez-vous se fait par le biais de cette messagerie. Les deux parties se mettent d’accord sur la date et l’heure du rendez-vous et rentrent ensuite ces informations dans le calendrier ce qui permet d’enregistrer ces informations.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/suppression.png" alt="" class="pic">
                           <div class="header_cgu"><p>Suppresion de compte</p></div>
                        </div>
                        <p class="greyback">En cas de non-respect des Conditions Générales d’Utilisation, les administrateurs du site swappy.fr pourront envoyer des avertissements aux utilisateurs concernés. Cet avertissement pourra survenir après la signalisation d’une infraction par un autre utilisateur, après que les administrateurs aient vérifié ladite infraction. <br>Ils se réservent tout droit de suspension temporaire du compte ou de suppression définitive du compte. L’utilisateur sera banni sans préavis dans le cas de non-respect grave des Conditions Générales d’Utilisation.</p>
                     </li>
                     <li>
                        <div class="row">
                           <img src="img/cgu/editer.png" alt="" class="pic">
                           <div class="header_cgu"><p>Contenu édité par les utilisateurs</p></div>
                        </div>
                        <p class="greyback">L’utilisateur est le rédacteur des propositions. Par conséquent, il est responsable de son contenu rédactionnel. Comme dit précédemment dans les Conditions d’utilisation du site, tout contenu considéré comme répréhensible entraînera la suppression permanente et irréversible de son compte.</p>
                     </li>
                  </ul>
               </section>
         
            <div id="spec_mention">
               <div class="mentiontitle">
                  <p>Mentions légales</p>
               </div>
               <div class="pictodown"><img src="img/apropos/down.png" alt="" class="down"/></div>
                  <div class="mention_wrap col-md-4 col-md-offset-4">
                     <p>
                        Tous les sites internet édités à titre professionnel, qu'ils proposent des ventes en ligne ou non, doivent obligatoirement indiquer les mentions légales suivantes :
                     </p>
                        <ul>
                           <li>Swappy.fr, 2 rue Albert Einstein 77420, Champs-sur-Marne</li>
                           <li><a class="link_mail" data-hash="<?php echo encode_mail("contact@swappy.fr", "UTF"); ?>"><?php echo encode_mail("contact@swappy.fr", "ASC"); ?></a><br>06.27.75.49.05</li>
                           <li>Calypso Redor</li>
                        </ul>
                  </div>
            </div>
         </div>
      </div>
      <footer id="footer">
         <img src="img/footer.png" alt="" width="30" height="18">
         <div class="container-fluid">
            <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
   </body>
</html>