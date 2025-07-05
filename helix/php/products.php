<?php
require 'db.php';

// Get all products grouped by category
$stmt = $pdo->query("SELECT * FROM products ORDER BY category, name");
$productsByCategory = [];
while ($product = $stmt->fetch()) {
    $productsByCategory[$product['category']][] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktet - Farmacia Jonë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container my-5">
        <h1 class="display-4 text-center mb-5">Produktet Tona</h1>
        
        <p class="lead text-center mb-5">
            Ne ofrojmë një gamë të gjerë të produkteve farmaceutike dhe shëndetësore për të gjitha nevojat tuaja.
        </p>

        <div class="row g-4">
            <?php foreach ($productsByCategory as $category => $products): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4"><?= htmlspecialchars($category) ?></h3>
                        <ul class="list-unstyled">
                            <?php foreach ($products as $product): ?>
                            <li class="mb-3 p-2 bg-light rounded">
                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                <p class="mb-0"><?= htmlspecialchars($product['description']) ?></p>
                                <p class="mb-0 text-primary"><?= number_format($product['price'], 2) ?> €</p>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-outline-primary">Shiko më shumë</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="bg-primary text-white p-5 rounded mt-5 text-center">
            <h2 class="mb-3">Nuk e gjeni atë që kërkoni?</h2>
            <p class="lead mb-4">Ne mund të porosisim produkte speciale sipas nevojave tuaja.</p>
            <a href="contact.php" class="btn btn-light btn-lg">Na Kontaktoni</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>