<?php
// driverConnexionBDD.php

// inclure le fichier avec les infos de connexion
require_once "infosConnexionsBDD.inc.php";

try {
    // créer la connexion PDO
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie !"; // juste pour tester
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
