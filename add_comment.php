<?php
session_start();

if(isset($_POST['boutmess'])){

    require 'config.php';

    // Récupération sécurisée
    $post_id = intval($_POST['post_id']); // mieux que htmlspecialchars ici
    $comment = trim($_POST['comment']);

    $user_id = $_SESSION['idu']; // important

    // Requête préparée
    $stmt = mysqli_prepare($id, "INSERT INTO commentaires (idp, idu, texte, date_creation) VALUES (?, ?, ?, NOW())");

    mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: accueil.php");
    exit();
}
?>