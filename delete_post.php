<?php
session_start();
require 'config.php'; // doit définir $id = mysqli_connect(...)

if (!isset($_SESSION["pseudo"])) {
    header("Location: connexion.php");
    exit();
}

// Récupération sécurisée du pseudo
$user_pseudo = mysqli_real_escape_string($id, $_SESSION['pseudo']);
// Récupération des informations de l'utilisateur
$result = mysqli_query($id, "SELECT idu FROM users WHERE pseudo = '$user_pseudo'");
$user = mysqli_fetch_assoc($result);
$_SESSION['user_id'] = $user['idu'];
// Récupération du post à supprimer
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $post_result = mysqli_query($id, "SELECT * FROM posts WHERE idp = $post_id");
    $post = mysqli_fetch_assoc($post_result);
}

// Vérification de l'ID du post à supprimer
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);

    if ($post && $post['user_id'] === $_SESSION['user_id']) {
        // Suppression du post
        mysqli_query($id, "DELETE FROM posts WHERE idp = $post_id");
        header("Location: accueil.php");
        exit();
    } else {
        echo "Vous n'êtes pas autorisé à supprimer ce post.";
    }
} else {
    echo "Post non spécifié.";
    header("Location: accueil.php");
}


