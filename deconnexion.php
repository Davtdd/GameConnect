<?php
session_start();
if (!isset($_SESSION['idu'])) {
    header("Location: connexion.php");
    exit();
}
session_destroy();
echo "Vous allez être deconnecté.....";
header("refresh:2; url=connexion.php");
// header("location:connexion.php");
?>