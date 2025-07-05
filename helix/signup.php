<?php
header('Content-Type: application/json');
require 'db.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new Exception('Invalid JSON input');
    }

    // Validate inputs
    if (empty($data['name'])) {
        throw new Exception('Name is required');
    }
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Valid email is required');
    }
    if (empty($data['password']) || strlen($data['password']) < 6) {
        throw new Exception('Password must be at least 6 characters');
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        throw new Exception('Email already exists');
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$data['name'], $data['email'], $hashedPassword])) {
        $response = [
            'success' => true,
            'message' => 'Registration successful!'
        ];
    } else {
        throw new Exception('Failed to create user');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    if ($e->getCode() == 23000) { // SQL duplicate entry
        $response['message'] = 'Email already exists';
    }
}

echo json_encode($response);
?>