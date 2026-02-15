<!doctype html>
<html lang="fr">
<?php
	include_once 'BDD/driverConnexionBDD.php'; // on inclut le fichier de connexion à la base de données
	include_once 'routes.php';  // on inclut le fichier de routage des pages
?>



	<body>




		<div class="container">
			<!-- Contenu principal -->
			<section>
				<?php include $affiche; /* commentez le code */?>
			</section>
			<!-- fin contenu principal -->
		</div>
	
	</body>
</html>
