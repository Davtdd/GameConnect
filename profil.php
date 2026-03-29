<?php
session_start();
require 'config.php'; // doit définir $id = mysqli_connect(...)
// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['idu'])) {
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <title>Profil</title>
    <link rel="stylesheet" href="css/globale.css">
</head>
<body>
    <h1>Profil de <?php echo htmlspecialchars($_SESSION['pseudo']); ?></h1>
    <p>Bienvenue sur votre page de profil.</p>
    <a href="accueil.php">Retour à l'accueil</a>
    <a href="deconnexion.php">Déconnexion</a>
    <a href="modif_profil.php">Modifier vos informations</a>

    <footer>
        <div class="container">
            <p>&copy; 2026 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>