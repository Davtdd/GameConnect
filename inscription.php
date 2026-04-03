<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'config.php';
// Récupération des données du formulaire
if(isset($_POST['bout'])){

$nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['mail']);
    $bio = trim($_POST['bio']);
    $mot_de_passe = $_POST['mdp'];
    $mot_de_passe2 = $_POST['mdp2'];
    $pseudo = trim($_POST['pseudo']);

if($mot_de_passe != $mot_de_passe2){

    // die("Les mots de passe ne sont pas identiques");

    $_SESSION['erreur'] = "Les mots de passe ne sont pas identiques.";
    header("Location: inscription.php");
    exit();
}


// Validation du mot de passe (au moins 10 caractères)

if (strlen($mot_de_passe) < 10) {
    // die("Le mot de passe doit contenir au moins 10 caractères.");

    $_SESSION['erreur'] = "Le mot de passe doit contenir au moins 10 caractères.";
    header("Location: inscription.php");
    exit();


}

 // Vérif si email déjà utilisé
    $stmt = mysqli_prepare($id, "SELECT idu FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['erreur'] = "Un compte existe déjà avec cet e-mail.";
    header("Location: inscription.php");
    exit();
}
mysqli_stmt_close($stmt);

    // Vérif si pseudo déjà utilisé
$stmt = mysqli_prepare($id, "SELECT idu FROM users WHERE pseudo = ?");
mysqli_stmt_bind_param($stmt, "s", $pseudo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['erreur'] = "Ce pseudo est déjà pris.";
    header("Location: inscription.php");
    exit();
}
mysqli_stmt_close($stmt);



// Hachage du mot de passe
$mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

// Traitement de l'avatar (upload fichier)
    $chemin = null;

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $dossier = 'uploads/avatars/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        $nomFichier = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $chemin = $dossier . $nomFichier;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin)) {
            $_SESSION['erreur'] = "Erreur lors de l'upload de l'avatar.";
            header("Location: inscription.php");
            exit();
        }
    }

// Insertion dans la base
$stmt = mysqli_prepare($id, "
    INSERT INTO users (nom, prenom, email, mot_de_passe, avatar, pseudo, bio)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

mysqli_stmt_bind_param($stmt, "sssssss",
    $nom,
    $prenom,
    $email,
    $mot_de_passe_hash,
    $chemin,
    $pseudo,
    $bio
);

if (mysqli_stmt_execute($stmt)) {
    header('Location: connexion.php');
    exit();
} else {
    echo "Erreur SQL";
}



header('Location: connexion.php');
exit();

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/globale.css">

    <script>
        function verif() {
             const mdp = document.getElementsByName("mdp")[0];
             const mdp2 = document.getElementsByName("mdp2")[0];
             const valmdp = mdp.value;
             const valmdp2 = mdp2.value;
             if (valmdp != valmdp2) {
                alert('les mots de passe ne sont pas identiques');
                mdp.style.borderColor = "red";
                mdp2.style.borderColor = "red";
                
                }
                if(valmdp == ""){
                alert('Il faut entrez un mots de passe ');
                mdp.style.borderColor = "red";
                }
                if(valmdp2 == ""){
                alert('Il faut entrez un mots de passe');
                mdp2.style.borderColor = "red";
                }
                if(valmdp.length < 10){
                alert('Il faut entrez un mots de passe de 10 caractères minimum');

             }
             // Vérifier si la chaîne contient des lettres et des chiffres
                const regex = /^[a-zA-Z0-9]+$/;
                const isValid = regex.test(valmdp);

                // Vérifier si la chaîne contient au moins une lettre majuscule, une minuscule et un chiffre
                const regexComplex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
                const isValidComplex = regexComplex.test(valmdp);

                if (!isValid) {
                    alert('Le mot de passe doit contenir des lettres et des chiffres.');
                    mdp.style.borderColor = "red";
                }
                if (!isValidComplex) {
                    alert('Le mot de passe doit contenir au moins une lettre majuscule, une minuscule et un chiffre.');
                    mdp.style.borderColor = "red";
                }
                return isValid && isValidComplex && valmdp == valmdp2 && valmdp.length >= 10;


            }
            
    </script>
</head>
<body>

    <!-- Afficher l'erreur depuis la session -->
    <?php if (isset($_SESSION['erreur'])) : ?>
        <p style="color: red;"><?= $_SESSION['erreur'] ?></p>
        <?php unset($_SESSION['erreur']); // Supprimer le message après affichage ?>
    <?php endif; ?>

    <form action="" method="post" onsubmit="return verif()" onsubmit="return verifmail()" enctype="multipart/form-data">

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required>
        <br>
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" required>
        <br>
        <input type="text" name="bio" id="bio" required placeholder="Entrez votre bio de profil">
        <br>
        <label for="email" name="mail">Email</label>
        <input type="email" name="mail" id="email" required>
        <br>
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required>
        <br>
        <label for="avatar">Avatar</label>
        <input type="file" name="avatar" id="avatar" required placeholder="Choisir un avatar">
        <br>
        <label for="password">Mot de passe</label>
        <input type="password" name="mdp" id="password" required>
        <br>
        <label for="password2">Confirmer le mot de passe</label>
        <input type="password" name="mdp2" id="password2" required>
        <br>
        <input type="submit" value="S'inscrire" name="bout" >

    </form>
    <a href="connexion.php"><button type="button">Se connecter</button></a>

     <footer>
        <div class="footer-container">
            <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>