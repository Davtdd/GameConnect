<?php
session_start();
require 'config.php';

// -----------------------------
// Vérification de connexion
// -----------------------------
if (!isset($_SESSION['idu'])) {
    header("Location: connexion.php");
    exit();
}

// ID du profil à modifier (GET ou session)
$profil_id = intval($_GET['id'] ?? $_SESSION['idu']);

// -----------------------------
// Vérification que l'utilisateur est propriétaire
// -----------------------------
if ($profil_id !== $_SESSION['idu']) {
    echo "Vous n’êtes pas autorisé à modifier ce profil.";
    exit();
}

// -----------------------------
// Récupérer les infos de l'utilisateur
// -----------------------------
$stmt = mysqli_prepare($id, "SELECT pseudo, email, bio, avatar FROM users WHERE idu = ?");
mysqli_stmt_bind_param($stmt, "i", $profil_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Utilisateur non trouvé.";
    exit();
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Initialiser les variables de session si elles n'existent pas
$_SESSION['pseudo'] = $_SESSION['pseudo'] ?? $user['pseudo'] ?? '';
$_SESSION['email'] = $_SESSION['email'] ?? $user['email'] ?? '';
$_SESSION['bio'] = $_SESSION['bio'] ?? $user['bio'] ?? '';
$_SESSION['avatar'] = $_SESSION['avatar'] ?? $user['avatar'] ?? '';

// -----------------------------
// Traitement du formulaire
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pseudo = $_POST['pseudo'] ?? '';
    $new_email = $_POST['email'] ?? '';
    $new_bio = $_POST['bio'] ?? '';
    $avatar_path = null;

    // -----------------------------
    // Gestion upload avatar
    // -----------------------------
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $dossier = __DIR__ . '/uploads/avatars/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        // Vérification du type MIME pour sécurité
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime = mime_content_type($_FILES['avatar']['tmp_name']);
        if (!in_array($file_mime, $allowed_types)) {
            $_SESSION['erreur'] = "Type de fichier non autorisé (JPEG, PNG, GIF uniquement).";
            header("Location: modif_profil.php?id=$profil_id");
            exit();
        }

        $nomFichier = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $chemin = $dossier . $nomFichier;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin)) {
            $avatar_path = 'uploads/avatars/' . $nomFichier; // chemin relatif pour HTML
        } else {
            $_SESSION['erreur'] = "Erreur lors de l'upload de l'avatar.";
            header("Location: modif_profil.php?id=$profil_id");
            exit();
        }
    }

    // -----------------------------
    // Préparer la requête UPDATE
    // -----------------------------
    if ($avatar_path) {
        $stmt = mysqli_prepare($id, "UPDATE users SET pseudo=?, email=?, bio=?, avatar=? WHERE idu=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $new_pseudo, $new_email, $new_bio, $avatar_path, $profil_id);
    } else {
        $stmt = mysqli_prepare($id, "UPDATE users SET pseudo=?, email=?, bio=? WHERE idu=?");
        mysqli_stmt_bind_param($stmt, "sssi", $new_pseudo, $new_email, $new_bio, $profil_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pseudo'] = $new_pseudo;
        $_SESSION['email'] = $new_email;
        $_SESSION['bio'] = $new_bio;
        if ($avatar_path) $_SESSION['avatar'] = $avatar_path;

        $_SESSION['success'] = "Profil mis à jour avec succès !";
        mysqli_stmt_close($stmt);
        header("Location: accueil.php");
        exit();
    } else {
        $_SESSION['erreur'] = "Erreur SQL : " . mysqli_error($id);
        mysqli_stmt_close($stmt);
        header("Location: modif_profil.php?id=$profil_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="css/globale.css">
</head>
<body>
<div class="nav-links">
    <a href="profil.php">Retour au profil</a>
    <a href="accueil.php">Retour à l'accueil</a>
</div>

<h1>Modifier vos informations</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="pseudo">Pseudo :</label>
    <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($_SESSION['pseudo'] ?? '') ?>" required>
    <br>

    <label for="avatar">Avatar :</label>
    <input type="file" name="avatar" id="avatar" placeholder="Choisir un avatar">
    <br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
    <br>

    <label for="bio">Bio :</label>
    <input type="text" id="bio" name="bio" value="<?= htmlspecialchars($_SESSION['bio'] ?? '') ?>" required>
    <br>

    <input type="submit" value="Mettre à jour">
</form>

<footer>
    <div class="footer-container">
        <p>&copy; 2026 GameConnect. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>