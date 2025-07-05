<?php
session_start();
require 'db.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Merr përdoruesin nga DB sipas email-it
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($user && password_verify($password, $user['password'])) {
    // Ruaj të dhënat e përdoruesit në sesion
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['logged_in'] = true;

    // Ridrejto sipas role
    if ($user['role'] === 'admin') {
        header('Location: admin-dashboard.php');  // Faqja për admin
        exit;
    } else {
        header('Location: profile.php');  // Faqja për user normal
        exit;
    }
}

} else {
    // Nëse dikush hap login.php me GET, ridrejto te login.html
    header('Location: login.html');
    exit;
}
