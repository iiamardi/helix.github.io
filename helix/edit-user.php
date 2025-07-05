<?php
session_start();
require 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header('Location: admin-dashboard.php');
    exit;
}

$userId = (int)$_GET['id'];

// Fetch user info from DB
$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found
    header('Location: admin-dashboard.php?error=User_not_found');
    exit;
}

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // password can be empty
    $role = $_POST['role'];

    // Basic validation
    if (empty($name)) {
        $errors[] = 'Emri është i detyrueshëm.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email-i është i pavlefshëm.';
    }
    if (!in_array($role, ['admin', 'user'])) {
        $errors[] = 'Roli është i pavlefshëm.';
    }

    // Check if email already exists for another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        $errors[] = 'Ky email është marrë tashmë nga një përdorues tjetër.';
    }

    if (empty($errors)) {
        // Update query with or without password
        if (!empty($password)) {
            // Hash password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            $updateStmt->execute([$name, $email, $hashedPassword, $role, $userId]);
        } else {
            // No password change
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $updateStmt->execute([$name, $email, $role, $userId]);
        }

        $success = 'Përdoruesi u përditësua me sukses!';
        // Refresh user data after update
        $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edito Përdoruesin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1>Edito Përdoruesin</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="name" class="form-label">Emri</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($user['name']) ?>" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Fjalëkalimi (lini bosh për të mos ndryshuar)</label>
            <input type="password" id="password" name="password" class="form-control" />
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Roli</label>
            <select id="role" name="role" class="form-select" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ruaj Ndryshimet</button>
        <a href="admin-dashboard.php" class="btn btn-secondary ms-2">Anulo</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
