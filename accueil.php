<?php
session_start();
require 'config.php'; // doit définir $id = mysqli_connect(...)

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["pseudo"])) {
    header("Location: connexion.php");
    exit();
}

// Récupération sécurisée du pseudo
$user_pseudo = mysqli_real_escape_string($id, $_SESSION['pseudo']);

// Récupération des informations de l'utilisateur
$result = mysqli_query($id, "SELECT pseudo, avatar, idu FROM users WHERE pseudo = '$user_pseudo'");
$user = mysqli_fetch_assoc($result);

// Récupération des posts avec infos des auteurs

$posts_result = mysqli_query($id, "
    SELECT p.idp, p.user_id, p.contenu, p.image, p.lien, p.date_creation, p.modifie_le, u.pseudo, u.avatar
    FROM posts p
    JOIN users u ON p.user_id = u.idu
    ORDER BY p.date_creation DESC
");

// Vérification simple si posts récupérés
if (!$posts_result) {
    echo "Erreur SQL : " . mysqli_error($id);
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/styleaccueil.css">
</head>
<body>
    <header>
    <a href="profil.php">Mon Profil</a>
    <a href="deconnexion.php">Déconnexion</a>
</header>

<a href="createpost.php" class="btn-creer-post">📝 Créer un post</a>


   <div class="container">
    <?php while ($post = mysqli_fetch_assoc($posts_result)) : ?>
        <div class="post">
            <!-- Header du post -->
            <div class="post-header">
                <img src="<?= htmlspecialchars($post['avatar']) ?>" alt="Avatar">
                <a href="bio.php?user=<?= htmlspecialchars($post['pseudo']) ?> " style="text-decoration: none;"><strong><?= htmlspecialchars($post['pseudo']) ?></strong></a>
            </div>

            <!-- Contenu du post -->
            <div class="post-content">
                <p><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
                <a href="<?= nl2br(htmlspecialchars($post['lien'])) ?>"><?= nl2br(htmlspecialchars($post['lien'])) ?></a>
                <?php if (!empty($post['image'])) : ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image du post">
                <?php endif; ?>
            </div>

            
            <!-- Actions -->
<div class="post-actions" style="display:flex; align-items:center; gap:15px;">

    <!-- Suppression si c'est le propriétaire du post -->
    <?php if ($post['pseudo'] === $_SESSION['pseudo']) : ?>
        <span 
            style="color:red; font-weight:bold; cursor:pointer;"
            title="Supprimer le post"
        ><a href="delete_post.php?id=<?= $post['idp'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" style="text-decoration: none; color:red;">✖</a>
    </span>
    <?php endif; ?>

    <!-- Like avec cœur -->
    <span class="like-icon" data-post-id="<?= $post['idp'] ?>" style="cursor:pointer; font-size:20px;">
        🤍 <span class="like-count" id="like-count-<?= $post['idp'] ?>">0</span>
    </span>

    <!-- Commentaire avec icône -->
    <span class="comment-icon" style="cursor:pointer; font-size:20px;" title="Commenter">
        💬
    </span>
</div>

<!-- Liste des commentaires -->
<div class="comments">
    <?php
                // Récupérer tous les commentaires du post actuel
                $comments_res = mysqli_query($id, "
                    SELECT c.texte, u.pseudo, u.avatar
                    FROM commentaires c
                    JOIN users u ON c.user_id = u.idu
                    WHERE c.post_id = " . $post['idp'] . "
                    ORDER BY c.date_creation ASC
                ");

                while ($comment = mysqli_fetch_assoc($comments_res)) :
                    // Limiter le texte à 250 caractères
                    $texte_comment = htmlspecialchars($comment['texte']);
                    if (strlen($texte_comment) > 250) {
                        $texte_comment = substr($texte_comment, 0, 247) . '...';
                    }
                ?>
                    <div class="comment">
                        <img src="<?= htmlspecialchars($comment['avatar']) ?>" alt="Avatar" 
                             style="width:25px;height:25px;border-radius:50%;margin-right:5px;vertical-align:middle;">
                        <strong><?= htmlspecialchars($comment['pseudo']) ?>:</strong> <?= nl2br($texte_comment) ?>
                    </div>
                    
                <?php endwhile; ?>

            </div>
            
        </div>
    <?php endwhile; ?>
</div>
<footer>
        <div class="container">
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="likebtn.js"></script>
</body>
</html>