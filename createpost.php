<?php
session_start();
require 'config.php'; // doit définir $id = mysqli_connect(...)

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['idu'])) {
    header("Location: connexion.php");
    exit();
}

$idu = $_SESSION['idu'];
// Traitement du formulaire
if (isset($_POST['submit'])) {

    $contenu = trim($_POST['contenu']);
    $lien = !empty($_POST['lien']) ? trim($_POST['lien']) : null;
    
    $image_path = NULL;

    // Upload image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier = 'uploads/posts/';
        if (!is_dir($dossier)) mkdir($dossier, 0777, true);

        $nomFichier = uniqid() . '_' . basename($_FILES['image']['name']);
        $chemin = $dossier . $nomFichier;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $chemin)) {
            $image_path = $chemin;
        } else {
            $erreur = "Erreur lors de l'upload de l'image.";
        }
    }

    // Requête préparée
    $stmt = mysqli_prepare($id, "
        INSERT INTO posts (idu, contenu, image, lien, date_creation)
        VALUES (?, ?, ?, ?, NOW())
    ");

    mysqli_stmt_bind_param($stmt, "isss", $idu, $contenu, $image_path, $lien);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Post créé avec succès !";
        header("Location: accueil.php");
        exit();
    } else {
        $erreur = "Erreur SQL";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un post</title>
    <link rel="stylesheet" href="css/globale.css">
</head>
<body>
 <div class="nav-links">
    <a href="accueil.php">Retour à l'accueil</a>
</div>
<h2>Créer un post</h2>

<?php if (isset($erreur)) echo "<p class='erreur'>$erreur</p>"; ?>
<?php if (isset($_SESSION['success'])) { echo "<p class='success'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>

<form method="post" enctype="multipart/form-data">
    <label>Texte :</label>
    <textarea name="contenu" required></textarea>

    <label>Lien :</label>
    <input type="text" name="lien" placeholder="https://...">

    <label>Image :</label>
    <input type="file" name="image" accept="image/*" >

    <button type="submit" name="submit">Publier</button>
</form>

    <footer>
        <div class="footer-container">
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
