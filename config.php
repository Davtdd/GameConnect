<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');       // Adresse du serveur de base de données
define('DB_USER', 'root');            // Nom d'utilisateur de la base de données
define('DB_PASS', '');                // Mot de passe de la base de données
define('DB_NAME', 'leboncoin');       // Nom de la base de données

// Connexion à la base de données
$id = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if (!$id) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
?>