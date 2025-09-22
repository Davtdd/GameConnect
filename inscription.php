<?php
session_start();
require 'config.php';
// Récupération des données du formulaire
if(isset($_POST['bout'])){

$nom = mysqli_real_escape_string($id, $_POST['nom']);
    $prenom = mysqli_real_escape_string($id, $_POST['prenom']);
    $email = mysqli_real_escape_string($id, $_POST['mail']);
    $mot_de_passe = $_POST['mdp'];
    $mot_de_passe2 = $_POST['mdp2'];
    $pseudo = mysqli_real_escape_string($id, $_POST['pseudo']);

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
    $checkMail = mysqli_query($id, "SELECT idu FROM users WHERE mail='$email'");
    if (mysqli_num_rows($checkMail) > 0) {
        $_SESSION['erreur'] = "Un compte existe déjà avec cet e-mail.";
        header("Location: inscription.php");
        exit();
    }

    // Vérif si pseudo déjà utilisé
    $checkPseudo = mysqli_query($id, "SELECT idu FROM users WHERE pseudo='$pseudo'");
    if (mysqli_num_rows($checkPseudo) > 0) {
        $_SESSION['erreur'] = "Ce pseudo est déjà pris.";
        header("Location: inscription.php");
        exit();
    }



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
    $req = "INSERT INTO users (nom, prenom, mail, mdp, avatar, pseudo)
            VALUES ('$nom','$prenom','$email','$mot_de_passe_hash', '$chemin', '$pseudo')";

    if (mysqli_query($id, $req)) {
        echo "Inscription réussie !";
    } else {
        echo "Erreur SQL : " . mysqli_error($id);
    }


// $req2="select nom, prenom from users where mail='$email'";
// $res2=mysqli_query($id,$req2);


// while($ligne = mysqli_fetch_assoc($res2)){
//     $nom = $ligne['nom'];
//     $prenom = $ligne['prenom'];
        
    
//     do {
//         // Génère un nombre aléatoire entre 120000 et 999999
//         $random_number = rand(120000, 999999);
        
//         // Crée le pseudo temporaire
//         $nouveau_pseudo = strtolower(substr($nom, 0, 3) . 
//                                    substr($prenom, 0, 3) . 
//                                    substr($email, 0, 2) . 
//                                    $random_number);
        
//         // Vérifie si le pseudo existe déjà
//         $check_query = "SELECT COUNT(*) as count FROM users WHERE pseudo = '$nouveau_pseudo'";
//         $check_result = mysqli_query($id, $check_query);
//         $count = mysqli_fetch_assoc($check_result)['count'];
        
//     } while($count > 0); // Continue tant que le pseudo existe
    
//     // Met à jour avec le pseudo unique
//     $req3 = "UPDATE users SET pseudo = '$nouveau_pseudo' WHERE mail='$email'";    
//     $resultat = mysqli_query($id, $req3);
    
//     if(!$resultat) {
//         echo "Erreur lors de la mise à jour: " . mysqli_error($id);
//     }
// }

echo "Inscription réussie !";
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
    <link rel="stylesheet" href="css/styleinscription.css">

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