<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        $response = [
            'success' => true,
            'message' => 'Login successful',
            'role' => $user['role']
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>