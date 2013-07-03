<?php
$pixDumpLink = "http://www.tcmag.com/magazine/pix_dump_";

$fic = fopen("C:\wamp\www\PixDumpCheck\pixDump.txt","a+");
$lastPixDump = fgets($fic,1024);
fclose($fic);

$nbAffiche = 10;

if (isset($_GET) && !empty($_GET)) {
	extract($_GET);
	if (isset($num)) {
		$pagePixDump = file_get_contents($pixDumpLink.$num);

		$startPix = strpos($pagePixDump, "<!-- Begin Content -->");
		$endPix = strpos($pagePixDump, "<!-- End Content -->");
		$longPix = $endPix-$startPix;
		$pixDump = substr($pagePixDump, $startPix, $endPix);

		if (preg_match_all("#<center>(.*)</center>#", $pagePixDump, $matches)){
			$listPixDump = $matches[0];
		}
	}
}
if (isset($page)) {
	$nbPage = ceil($lastPixDump / $nbAffiche);
} else {
	$page = 1;
	$nbPage = ceil($lastPixDump / $nbAffiche);
} 
// Calcul le numéro du premier pixDump à afficher
$startPixDump = $lastPixDump - ($page*$nbAffiche-10);
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>PixDumpMobile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="css/bootstrap.min.css" />
  	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="apple-touch-icon" href="img/icon-114.png" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-startup-image" href="img/icon-320.png" />
	<meta name="viewport" content="user-scalable=no, width=device-width" />
	<link rel="icon" type="image/png" href="img/favicon.png" />
</head>
<body>
	<div class="container">
		<!-- Affichage d'un PixDump -->
		<?php if (isset($listPixDump)){ ?>
			<h1><a href="index.php" title="Retour à l'accueil">Pix Dump</a> #<?php echo $num; ?></h1>
			<?php
			// Affiche chaques images du PixDump
			foreach ($listPixDump as $key => $image) {
				echo $image;
			}
			?>
			<ul class="page">
				<?php if ($num > 1) { ?>
					<li><a class="button" href="?num=<?php echo $num-1; ?>">&lt; Précédent</a></li>
				<?php } ?>
				<?php if ($num < $lastPixDump) { ?>
					<li><a class="button" href="?num=<?php echo $num+1; ?>">Suivant &gt;</a></li>
				<?php } ?>
			</ul>
			<!-- Formulaire pour le vote de la meilleure image du PixDump -->
			<?php include 'form.php' ?>
			<!-- Fin du formulaire -->
		<!-- Affichage du sommaire -->
		<?php } else { ?>
			<h1><a href="index.php" title="Retour à l'accueil">Pix Dump Mobile</a></h1>
			<ul class="pixDumpLink">
				<?php for ($num = $startPixDump ; $num >= $startPixDump-10 && $num > 0 ; $num--) { ?>
					<li><a href="<?php echo "?num=".$num; ?>"><?php echo "Pix Dump #".$num; ?></a></li>
				<?php } ?>
			</ul>
			<?php 
			if ($nbPage > 1) { ?>
				<ul class="page">
					<?php if ($page > 1) { ?>
						<li><a class="button" href="?page=<?php echo $page-1; ?>">&lt; Précédent</a></li>
					<?php } ?>
					<?php
					for ($numPage=1; $numPage <= $nbPage; $numPage++) { ?>
						<li><a href="?page=<?php echo $numPage; ?>" <?php echo ($page == $numPage)? 'class="selected"' : '' ?>><?php echo $numPage; ?></a></li>
					<?php } ?>
					<?php if ($page < $nbPage) { ?>
						<li><a class="button" href="?page=<?php echo $page+1; ?>">Suivant &gt;</a></li>
					<?php } ?>
				</ul>
			<?php } 
		} ?>
		<div class="footer">
			<a class="logo" href="http://www.tcmag.com/"></a>
			<a class="letters" href="http://www.tcmag.com/"></a>
		</div>
    </div>
</body>
</html>