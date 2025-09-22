<?php
session_start();
require 'config.php'; 

if(!isset($_SESSION['pseudo'])){
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['user'])) {
    header("Location: accueil.php");
    exit();
}

$user_pseudo = mysqli_real_escape_string($id, $_GET['user']);
$result = mysqli_query($id, "SELECT * FROM users WHERE pseudo = '$user_pseudo'");
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Utilisateur non trouvé.";
    exit();
}
$user = mysqli_fetch_assoc($result);
$idu = $user['idu'];
$posts_result = mysqli_query($id, "
    SELECT p.idp, p.user_id, p.contenu, p.image, p.lien, p.date_creation, p.modifie_le, u.pseudo, u.avatar
    FROM posts p
    JOIN users u ON p.user_id = u.idu
    WHERE p.user_id = $idu
    ORDER BY p.date_creation DESC
");
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
    <title><?php echo htmlspecialchars($user['pseudo']); ?> - Profil</title>
    <link rel="stylesheet" href="css/stylebio.css">
</head>
<body>
    
<div class="nav-links">
    <a href="accueil.php">Retour à l'accueil</a>
</div>

    <h1><?php echo htmlspecialchars($user['pseudo']); ?></h1>
    <img class="avatar-bio" src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar de <?php echo htmlspecialchars($user['pseudo']); ?>">
    <h2>Publications</h2>
    <?php if (mysqli_num_rows($posts_result) > 0): ?>
        <div class="cards-container">
            <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                <div class="card-post">
                    <h3><?php echo htmlspecialchars($post['contenu']); ?></h3>
                    <a href="<?php echo htmlspecialchars($post['lien']); ?>"><?php echo htmlspecialchars($post['lien']); ?></a>
                    <?php if ($post['image']): ?>
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Image de publication">
                    <?php endif; ?>
                    <p>Publié le <?php echo htmlspecialchars($post['date_creation']); ?> par <?php echo htmlspecialchars($post['pseudo']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Aucune publication trouvée.</p>
    <?php endif; ?>

    <footer>
        <div class="footer-container">
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>