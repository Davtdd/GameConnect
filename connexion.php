<?php
session_start();
if(isset($_POST['bout'])){
    $pseudo = trim($_POST['pseudo']);
    $pseudo = htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8');
    $mot_de_passe = $_POST['mdp'];

    require 'config.php';

    $stmt = $id->prepare("SELECT idu, mot_de_passe FROM users WHERE pseudo = ?");
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $ligne = $res->fetch_assoc();
        $idu = $ligne['idu'];
        $mot_de_passe_hash = $ligne['mot_de_passe'];

        if(password_verify($mot_de_passe, $mot_de_passe_hash)){
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['idu'] = $idu;
            header("Location: accueil.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec ce pseudo.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/globale.css">
</head>
<body>
    <form action="" method="post">
        <input type="text" name="pseudo" placeholder="Entrer votre pseudo" required> <br>
        <input type="password" name="mdp" placeholder="Entrer votre mot de passe" required> <br>
        <input type="submit" value="Se connecter" name="bout">
    </form>
    <a href="inscription.php"><button type="button">S'inscrire</button></a>
    <footer>
        <div class="footer-container">
            <p>&copy; 2026 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>