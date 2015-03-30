<?php
	//GENERATION DU SITEMAP POUR INDEXER LES ANNONCES
  	header('Content-type: application/xml');
	require_once("inc/config.php"); 
	// configuration
  	$url_prefix = URL_SITE.FOLDER_;
  	$null_sitemap = '<urlset><url><loc></loc></url></urlset>';
	if(!isset($_GET['i'])) {
 		$i = null;
	} else {
		$i = trim($_GET['i']);
	}
	if($i != null) {
	$max = 480;
	$d = $i * $max;
	$f = ($i+1) * $max;
 	$select = $mysql->prepare("SELECT `ID` FROM `services` WHERE 1 ORDER BY `Created` DESC LIMIT ".$d.", ".$f.""); 
	$select->execute();
	if($select->rowCount() < 1 && $i > 0) {
		header("HTTP/1.0 404 Not Found");
		header("Location: 404.php");
		exit();
	}
	//HEADER
 	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <?php if($i == 0) { ?>
	<url>
  		<loc><?php echo $url_prefix; ?>index.php</loc>
  		<changefreq>weekly</changefreq>
		<priority>0.7</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>services.php</loc>
  		<changefreq>weekly</changefreq>
		<priority>1.0</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>inscription.php</loc>
  		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>ccm.php</loc>
  		<changefreq>monthly</changefreq>
		<priority>0.5</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>cgu.php</loc>
  		<changefreq>monthly</changefreq>
		<priority>0.2</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>contact.php</loc>
  		<changefreq>monthly</changefreq>
		<priority>0.4</priority>
	</url>
	<url>
  		<loc><?php echo $url_prefix; ?>apropos.php</loc>
  		<changefreq>monthly</changefreq>
		<priority>0.1</priority>
	</url>
    <?php } while($data = $select->fetch(PDO::FETCH_OBJ)) { ?>
	<url>
  		<loc><?php echo $url_prefix."annonce-".$data->ID; ?>.php</loc>
		<priority>0.7</priority>
	</url>
    <?php } ?>
</urlset><?php } else { 
$select = $mysql->prepare("SELECT COUNT(*) AS `total` FROM `services` WHERE 1"); 
	$select->execute();
	$data = $select->fetch(PDO::FETCH_OBJ);
	$max = 480;
	$total = $data->total;
	$cc = $total / $max;
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php for($i=-1;$i<round($cc);$i++) {?>
	<sitemap>
    	<loc><?php echo $url_prefix."sitemap-".($i+1); ?>.xml</loc>
	</sitemap><?php } ?>
</sitemapindex>
<?php } ?>