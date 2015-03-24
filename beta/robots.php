<?php
  	header('Content-type: text/plain');
 
	require_once("inc/mysql.php"); 
	
	$select = $mysql->prepare("SELECT COUNT(*) AS `total` FROM `services` WHERE 1"); 
	$select->execute();
	$data = $select->fetch(PDO::FETCH_OBJ);
	$max = 480;
	$total = $data->total;
	$cc = $total / $max;
?>User-Agent: *
Allow: /
Disallow: /css/
Disallow: /fonts/
Disallow: /img/
Allow: /img/social/
Disallow: /inc/
Disallow: /js/
Allow: /index.php
Allow: /services.php
Allow: /contact.php
Allow: /inscription.php
Allow: /ccm.php
Allow: /cgu.php
Allow: /apropos.php
Disallow: /messagerie.php
Disallow: /propose.php
Disallow: /proposition.php
Disallow: /rendez-vous.php
<?php for($i=-1;$i<round($cc);$i++) {
	echo 'Sitemap: http://swappy.fr/beta/sitemap-'.($i+1).'.xml
';
} ?>