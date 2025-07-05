<?php
session_start();
require 'db.php';

// Kontrollo rolin e përdoruesit
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin-dashboard.php');
    exit;
}

$userId = (int) $_GET['id'];

// Mos e lejo adminin të fshijë vetveten (opsionale)
if ($userId === $_SESSION['user_id']) {
    // Nuk mund ta fshijë vetveten
    header('Location: admin-dashboard.php?error=Fshirja_e_vetes_nuk_lejohet');
    exit;
}

// Fshi përdoruesin
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);

// Redirect prapë në admin dashboard me sukses
header('Location: admin-dashboard.php?success=Perdoruesi_u_fshi_me_sukses');
exit;
?>
