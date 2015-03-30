<?php
require_once("inc/config.php");
require_once("inc/user.php");
$page = new page();
?>
<!doctype html>
<html itemscope itemtype="http://schema.org/Corporation" class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=no"
>
    <title>Swappy.fr - Page introuvable</title>
    <?php /* user.php | line 68 */ echo $page->meta_tag("404.jpg", "", "", "Page introuvable", "erreur, 404, introuvable"); ?>
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!--[if lt IE 9]>
      <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
	<?php /* user.php | line 13 */ echo $page->add_tracking(); ?>
    <div id="error">
        <h1 class="logo_error"><img alt="Erreur 404" src="img/logo_error.png" width="75" height="81"></h1>
        <div class="text_error">
            <h2>Page introuvable</h2>
            <p>Il semblerait que le lien que vous avez saisi est incorrect.</p>
            <p>Voici le lien vers la <a href="index.php">page d'accueil</a></p>
        </div>
	</div>
</body>
</html>