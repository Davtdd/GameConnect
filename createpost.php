<?php
session_start();
require 'config.php'; // doit définir $id = mysqli_connect(...)

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: connexion.php");
    exit();
}

// Récupération de l'id utilisateur
$user_pseudo = mysqli_real_escape_string($id, $_SESSION['pseudo']);
$result = mysqli_query($id, "SELECT idu FROM users WHERE pseudo = '$user_pseudo'");
$user = mysqli_fetch_assoc($result);
$idu = $user['idu'];

// Traitement du formulaire
if (isset($_POST['submit'])) {
    $contenu = mysqli_real_escape_string($id, $_POST['contenu']);
    $lien = mysqli_real_escape_string($id, $_POST['lien']);
    
    $image_path = NULL;

    // Gestion upload image
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

    // Insertion dans la base
    $req = "INSERT INTO posts (user_id, contenu, image, lien, date_creation) 
            VALUES ('$idu', '$contenu', ".($image_path ? "'$image_path'" : "NULL").", ".($lien ? "'$lien'" : "NULL").", NOW())";

    if (mysqli_query($id, $req)) {
        $_SESSION['success'] = "Post créé avec succès !";
        header("Location: accueil.php"); // Retour à l'accueil
        exit();
    } else {
        $erreur = "Erreur SQL : " . mysqli_error($id);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un post</title>
    <link rel="stylesheet" href="css/stylecreatepost.css">
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
