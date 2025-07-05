<?php
session_start();
require 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get product details
$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: admin-dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: admin-dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $category, $productId]);
    
    header('Location: admin-dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Farmacia Jonë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Edit Product</h2>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (€)</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                       value="<?= htmlspecialchars($product['price']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="Barnat e Përshkruara" <?= $product['category'] === 'Barnat e Përshkruara' ? 'selected' : '' ?>>Barnat e Përshkruara</option>
                                    <option value="Barnat pa Recetë" <?= $product['category'] === 'Barnat pa Recetë' ? 'selected' : '' ?>>Barnat pa Recetë</option>
                                    <option value="Produkte Natyrale" <?= $product['category'] === 'Produkte Natyrale' ? 'selected' : '' ?>>Produkte Natyrale</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>