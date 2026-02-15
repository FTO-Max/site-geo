<?php
include_once "../BDD/driverConnexionBDD.php";

$mode = $_GET['mode'] ?? '1';

if($mode == '1'){
    if(!isset($_GET['nom'])){
        echo "Aucun pays sélectionné";
        exit;
    }

    $nom = $_GET['nom'];
    $stmt = $bdd->prepare("SELECT * FROM terri2025 WHERE accro = ?");
    $stmt->execute([$nom]);
    $territoire = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$territoire){
        echo "Aucune donnée trouvée pour " . htmlspecialchars($nom);
        exit;
    }

    echo "<p>Population : " . number_format($territoire['population']) . "</p>";
    echo "<p>Superficie : " . number_format($territoire['superficie']) . " km²</p>";
    echo "<p>Densité : " . number_format($territoire['population']/$territoire['superficie']) . " hab/km²</p>";
    echo "<p>PIB nominal : $" . number_format($territoire['pib_nominal']) . "</p>";
    echo "<p>PIB par habitant : $" . number_format($territoire['pib_nominal']/$territoire['population']) . "</p>";

} else {
    // Mode multi-sélection
    if(!isset($_GET['nom']) || count($_GET['nom']) == 0){
        echo "Aucun pays sélectionné";
        exit;
    }

    $noms = $_GET['nom'];
    $in  = str_repeat('?,', count($noms) - 1) . '?';
    $stmt = $bdd->prepare("SELECT * FROM terri2025 WHERE accro IN ($in)");
    $stmt->execute($noms);
    $territoires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPop = 0;
    $totalSuperficie = 0;
    $totalPIB = 0;

    foreach($territoires as $t){
        $totalPop += $t['population'];
        $totalSuperficie += $t['superficie'];
        $totalPIB += $t['pib_nominal'];
    }

    echo "<p>Population totale : " . number_format($totalPop) . "</p>";
    echo "<p>Superficie totale : " . number_format($totalSuperficie) . " km²</p>";
    echo "<p>Densité moyenne : " . number_format($totalPop/$totalSuperficie,2) . " hab/km²</p>";
    echo "<p>PIB total : $" . number_format($totalPIB) . "</p>";
    echo "<p>PIB par habitant moyen : $" . number_format($totalPIB/$totalPop,2) . "</p>";
}
?>
