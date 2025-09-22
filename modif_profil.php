<?php
session_start();
require 'config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idu'])) {
    header("Location: connexion.php");
    exit();
}
// Récupération des informations actuelles de l'utilisateur
$user_id = intval($_SESSION['idu']);
$result = mysqli_query($id, "SELECT pseudo, mail FROM users WHERE idu = $user_id");
if ($result) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['pseudo'] = $user['pseudo'];
    $_SESSION['email'] = $user['mail'];
} else {
    echo "Erreur lors de la récupération des informations utilisateur.";
    exit();
}
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pseudo = mysqli_real_escape_string($id, $_POST['pseudo']);
    $new_email = mysqli_real_escape_string($id, $_POST['email']);
    $avatar_path = null;

    // Gestion upload avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $dossier = 'uploads/avatars/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        $nomFichier = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $chemin = $dossier . $nomFichier;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin)) {
            $avatar_path = $chemin;
        } else {
            $_SESSION['erreur'] = "Erreur lors de l'upload de l'avatar.";
            header("Location: modif_profil.php");
            exit();
        }
    }

    // Mise à jour dans la base
    $update_query = "UPDATE users SET pseudo='$new_pseudo', mail='$new_email'";
    if ($avatar_path) {
        $update_query .= ", avatar='$avatar_path'";
    }
    $update_query .= " WHERE idu=$user_id";

    if (mysqli_query($id, $update_query)) {
        $_SESSION['pseudo'] = $new_pseudo;
        $_SESSION['email'] = $new_email;
        $_SESSION['success'] = "Profil mis à jour avec succès !";
        header("Location: accueil.php");
        exit();
    } else {
        $_SESSION['erreur'] = "Erreur SQL : " . mysqli_error($id);
        header("Location: modif_profil.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="css/modif_profil.css">
</head>
<body>
  <div class="nav-links">
    <a href="profil.php">Retour au profil</a>
    <a href="accueil.php">Retour à l'accueil</a>
</div>

    <h1>Modifier vos informations</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($_SESSION['pseudo']); ?>" required>
        <br>
        <label for="avatar">Avatar</label>
        <input type="file" name="avatar" id="avatar" placeholder="Choisir un avatar">
        <br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
        <br>
        <input type="submit" value="Mettre à jour">
    </form>
    <footer>
        <div class="footer-container">
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>