<?php
session_start();
if(isset($_POST['bout'])){
    // Nettoyage et validation des entrées
    $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
    $mot_de_passe = $_POST['mdp'];

    require 'config.php';
    
    // Utilisation de requêtes préparées pour éviter les injections SQL
    $req2 = $id->prepare("SELECT idu FROM users WHERE pseudo = ?");
    $req2->bind_param("s", $pseudo);
    $req2->execute();
    $result = $req2->get_result();
    
    if($result->num_rows > 0){
        $ligne = $result->fetch_assoc();
        $idu = $ligne['idu'];
        
        // Vérification du mot de passe de manière sécurisée
        $stmt = $id->prepare("SELECT mdp FROM users WHERE pseudo = ?");
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $ligne = $res->fetch_assoc();
            $mot_de_passe_hash = $ligne['mdp'];
            
            if (password_verify($mot_de_passe, $mot_de_passe_hash)) {
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['idu'] = $idu;
                echo "Connexion réussie !";
                header("refresh:3; accueil.php");
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/styleconnexion.css">
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
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>