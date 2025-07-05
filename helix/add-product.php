<?php
session_start();
require 'db.php';

// Vetëm admini ka akses
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Nëse është bërë submit formulari
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';

    if ($name && $description && $price && $category) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name, $description, $price, $category]);
        header('Location: admin-dashboard.php'); // pas shtimit kthehet në dashboard
        exit;
    } else {
        $error = "Ju lutem plotësoni të gjitha fushat.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shto Produkt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Shto Produkt të Ri</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="add-product.php" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label for="name" class="form-label">Emri i produktit</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Përshkrimi</label>
            <textarea name="description" id="description" rows="3" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Çmimi (€)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Kategoria</label>
            <select name="category" id="category" class="form-select" required>
                <option value="">Zgjidh kategori</option>
                <option value="pershkrim">Barnat e Përshkruara</option>
                <option value="pa_recete">Barnat pa Recetë</option>
                <option value="natyrale">Produkte Natyrale</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Shto Produktin</button>
        <a href="admin-dashboard.php" class="btn btn-secondary">Anulo</a>
    </form>
</div>
</body>
</html>
