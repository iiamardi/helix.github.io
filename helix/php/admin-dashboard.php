<?php
session_start();
require 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    header('Location: admin-dashboard.php');
    exit;
}

// Get all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Farmacia Jonë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Admin Dashboard</h2>
            <div>
                <a href="add-product.php" class="btn btn-primary me-2">Add New Product</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Products</h3>
                
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">No products found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['id']) ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['description']) ?></td>
                                    <td><?= number_format($product['price'], 2) ?> €</td>
                                    <td><?= htmlspecialchars($product['category']) ?></td>
                                    <td>
                                        <a href="edit-product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <button type="submit" name="delete_product" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>