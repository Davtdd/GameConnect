<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


require 'config.php';// doit définir $id = mysqli_connect(...)


// Vérifier si l'utilisateur est connecté

// Vérifier si connecté
if (!isset($_SESSION["idu"])) {
    header("Location: connexion.php");
    exit();
}

// Utiliser la session existante
$user_id = $_SESSION['idu'];

$stmt = mysqli_prepare($id, "SELECT pseudo, avatar FROM users WHERE idu = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Utilisateur introuvable.";
    exit();
}



$user = mysqli_fetch_assoc($result);
$_SESSION['pseudo'] = $user['pseudo'];

// Récupération des posts avec infos des auteurs

$posts_result = mysqli_query($id, "
    SELECT p.idp, p.idu, p.contenu, p.image, p.lien, p.date_creation, u.pseudo, u.avatar
    FROM posts p
    JOIN users u ON p.idu = u.idu
    ORDER BY p.date_creation DESC
");


// while ($post = mysqli_fetch_assoc($posts_result)) {
//     var_dump($post);
// }
// exit();

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

    <link rel="stylesheet" href="css/globale.css">
</head>
<body>
    <header>
    <a href="profil.php">Mon Profil</a>
    <a href="deconnexion.php">Déconnexion</a>
</header>

<a href="createpost.php" class="btn-creer-post">📝 Créer un post</a>



<div class="container">
    <?php while ($post = mysqli_fetch_assoc($posts_result)) : ?>
        
        
        <div class="post-row">
            <!-- Colonne gauche : la carte du post -->
            <div class="post-card">
                <!-- Header du post -->
                <div class="post-header">
                    <img src="<?= htmlspecialchars($post['avatar']) ?>" alt="Avatar">
                    <a href="bio.php?user=<?= htmlspecialchars($post['pseudo']) ?>" style="text-decoration: none;">
                        <strong><?= htmlspecialchars($post['pseudo']) ?></strong>
                    </a>
                </div>

                <!-- Contenu du post -->
                <div class="post-content">
                    <p><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
                    <?php if (!empty($post['lien'])) : ?>
                        <a href="<?= htmlspecialchars($post['lien']) ?>"><?= htmlspecialchars($post['lien']) ?></a>
                    <?php endif; ?>
                    <?php if (!empty($post['image'])) : ?>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image du post">
                    <?php endif; ?>
                </div>

                <!-- Actions : like, suppression (si auteur) -->
                <div class="post-actions">
                    <?php if ($post['pseudo'] === $_SESSION['pseudo']) : ?>
                        <span class="delete-icon">
                            <a href="delete_post.php?id=<?= $post['idp'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')" style="text-decoration: none; color:red;">✖</a>
                        </span>
                    <?php endif; ?>

                    <span class="like-icon" data-post-id="<?= $post['idp'] ?>" style="cursor:pointer; font-size:20px;">
                        🤍 <span class="like-count" id="like-count-<?= $post['idp'] ?>">0</span>
                    </span>
                </div>
            </div>

            <!-- Colonne droite : zone commentaire -->
            <div class="comment-area">
                <!-- Formulaire pour ajouter un commentaire -->
                <form action="add_comment.php" method="post" class="comment-form">
                    <textarea name="comment" rows="2" placeholder="Écrire un commentaire..." required></textarea>
                    <input type="hidden" name="post_id" value="<?= $post['idp'] ?>">
                    <button type="submit">Envoyer</button>
                </form>

                <!-- Liste des commentaires existants -->
                <div class="comments-list">
                    <?php
                    $comments_res = mysqli_query($id, "
                        SELECT c.texte, u.pseudo, u.avatar
                        FROM commentaires c
                        JOIN users u ON c.idu = u.idu
                        WHERE c.idp = " . $post['idp'] . "
                        ORDER BY c.date_creation ASC
                    ");

                    while ($comment = mysqli_fetch_assoc($comments_res)) :
                        $texte_comment = htmlspecialchars($comment['texte']);
                        if (strlen($texte_comment) > 250) {
                            $texte_comment = substr($texte_comment, 0, 247) . '...';
                        }
                    ?>
                        <div class="comment">
                            <img src="<?= htmlspecialchars($comment['avatar']) ?>" alt="Avatar">
                            <strong><?= htmlspecialchars($comment['pseudo']) ?> :</strong>
                            <span><?= nl2br($texte_comment) ?></span>
                        </div>
                        mysqli_free_result($comments_res);
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<footer>
        <div class="container">
            <p>&copy; 2026 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="likebtn.js"></script>
</body>
</html>
