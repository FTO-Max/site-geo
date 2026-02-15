<?php
// Inclure le driver BDD
include_once "../BDD/driverConnexionBDD.php"; // ../BDD car pageMap est un sous-dossier

if(!isset($_GET['nom'])){
    echo "Aucun pays sélectionné";
    exit;
}

$nom = $_GET['nom'];

// Préparer la requête pour éviter les injections SQL
$stmt = $bdd->prepare("SELECT * FROM terri2025 WHERE accro = ?");
$stmt->execute([$nom]);
$territoire = $stmt->fetch(PDO::FETCH_ASSOC);



if($territoire){
    echo "<h2>" . htmlspecialchars($territoire['nom']) . "</h2>";
    echo "<p>Population : " . number_format($territoire['population']) . "</p>";
    echo "<p>Superficie : " . number_format($territoire['superficie']) . " km²</p>";
    echo "<p>PIB nominal : $" . number_format($territoire['pib_nominal']) . "</p>";
} else {
    echo "Aucune donnée trouvée pour " . htmlspecialchars($nom);
}
?>
