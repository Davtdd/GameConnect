<?php
session_start();
require 'config.php'; // $id = mysqli_connect(...)

header('Content-Type: application/json');

// Log pour debug (optionnel, supprime après test)
$logFile = 'like_debug.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Début like.php\n", FILE_APPEND);
file_put_contents($logFile, "POST : " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents($logFile, "SESSION : " . print_r($_SESSION, true) . "\n", FILE_APPEND);

if (!isset($_SESSION['idu'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit();
}

if (!isset($_POST['post_id'])) {
    echo json_encode(['error' => 'post_id manquant']);
    exit();
}

$postId = intval($_POST['post_id']);
$userId = intval($_SESSION['idu']);

file_put_contents($logFile, "postId=$postId, userId=$userId\n", FILE_APPEND);

// Vérifier si déjà liké
$stmt = mysqli_prepare($id, "SELECT idl FROM likes WHERE idu = ? AND idp = ?");
mysqli_stmt_bind_param($stmt, "ii", $userId, $postId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$alreadyLiked = mysqli_fetch_assoc($result); // CORRECTION : utiliser $result
mysqli_stmt_close($stmt);

file_put_contents($logFile, "alreadyLiked: " . ($alreadyLiked ? 'oui' : 'non') . "\n", FILE_APPEND);

if ($alreadyLiked) {
    // Supprimer le like
    $stmt = mysqli_prepare($id, "DELETE FROM likes WHERE idu = ? AND idp = ?");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $postId);
    $success = mysqli_stmt_execute($stmt);
    if (!$success) {
        file_put_contents($logFile, "ERREUR DELETE: " . mysqli_stmt_error($stmt) . "\n", FILE_APPEND);
    }
    mysqli_stmt_close($stmt);
    $liked = false;
} else {
    // Ajouter le like
    $stmt = mysqli_prepare($id, "INSERT INTO likes (idp, idu, date_creation) VALUES (?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "ii", $postId, $userId);
    $success = mysqli_stmt_execute($stmt);
    if (!$success) {
        file_put_contents($logFile, "ERREUR INSERT: " . mysqli_stmt_error($stmt) . "\n", FILE_APPEND);
    } else {
        file_put_contents($logFile, "INSERT réussi, ID insert: " . mysqli_insert_id($id) . "\n", FILE_APPEND);
    }
    mysqli_stmt_close($stmt);
    $liked = true;
}

// Compter les likes
$stmt = mysqli_prepare($id, "SELECT COUNT(*) AS cnt FROM likes WHERE idp = ?");
mysqli_stmt_bind_param($stmt, "i", $postId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$likeCount = $row['cnt'];
mysqli_stmt_close($stmt);

file_put_contents($logFile, "likeCount final = $likeCount, liked = " . ($liked ? 'true' : 'false') . "\n", FILE_APPEND);

echo json_encode([
    'likeCount' => $likeCount,
    'liked' => $liked
]);