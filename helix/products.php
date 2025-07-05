<?php
require 'db.php';

// Merr të gjitha produktet nga databaza dhe i organizon sipas kategorisë
$stmt = $pdo->query("SELECT * FROM products ORDER BY category, created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grupi i kategorive me emra të shfaqshëm
$categories = [
    'pershkrim' => 'Barnat e Përshkruara',
    'pa_recete' => 'Barnat pa Recetë',
    'natyrale' => 'Produkte Natyrale'
];

// Organizim i produkteve sipas kategorive
$grouped = [
    'pershkrim' => [],
    'pa_recete' => [],
    'natyrale' => []
];

foreach ($products as $product) {
    if (array_key_exists($product['category'], $grouped)) {
        $grouped[$product['category']][] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktet - Farmacia Jonë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">
                <span class="brand-text">Helix Pharm</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-2">
                        <a class="nav-link " href="index.html">Faqja Kryesore</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="about.html">Rreth Nesh</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="services.html">Shërbimet</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link active" href="products.php">Produktet</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="contact.html">Kontakt</a>
                    </li>
                      <li class="nav-item mx-2">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container my-5">
    <section class="animated-section">
        <h1 class="display-4 text-center mb-5">Produktet Tona</h1>
        <p class="lead text-center mb-5">Ne ofrojmë një gamë të gjerë të produkteve farmaceutike dhe shëndetësore për të gjitha nevojat tuaja.</p>
    </section>

    <div class="row g-4">
        <?php foreach ($categories as $key => $title): ?>
            <div class="col-md-4">
                <section class="animated-section">
                    <div class="hover-up">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title text-center mb-4"><?= htmlspecialchars($title) ?></h3>
                                <ul class="list-unstyled">
                                    <?php if (!empty($grouped[$key])): ?>
                                        <?php foreach ($grouped[$key] as $product): ?>
                                            <li class="mb-3 p-2 bg-light rounded hover-scale">
                                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                                <p class="mb-0"><?= htmlspecialchars($product['description']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="text-muted">Asnjë produkt në këtë kategori.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="products-category.php?category=<?= $key ?>" class="btn btn-outline-primary">Shiko më shumë</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        <?php endforeach; ?>
    </div>

    <section class="animated-section" style="transition-delay: 0.9s;">
        <div class="bg-primary text-white p-5 rounded mt-5 text-center">
            <h2 class="mb-3">Nuk e gjeni atë që kërkoni?</h2>
            <p class="lead mb-4">Ne mund të porosisim produkte speciale sipas nevojave tuaja.</p>
            <a href="contact.html" class="btn btn-light btn-lg">Na Kontaktoni</a>
        </div>
    </section>
</div>

 <footer class="  py-4 mt-4" id="mainFooter">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="footer-heading">Farmacia Jonë</h5>
                    <p>Shëndeti juaj është prioriteti ynë.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Kontakt</h5>
                    <p>Rr Driton Islami</p>
                    <p>Ferizaj, Kosove</p>
                    <p>Tel: +383 48 889 066</p>
                </div>
                <div class="col-md-4">
                    <h5>Orari i Punës</h5>
                    <p>E Hënë - E Shtune: 8:00 - 22:00</p>
                    <p>E Diel: 9:00 - 18:00</p>
                     
                </div>
            </div>
            <div class="row mt-3">
                <div class="col text-center">
                    <p>&copy; <span id="currentYear"></span> Farmacia Jonë. Të gjitha të drejtat e rezervuara.</p>
                </div>
            </div>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts.js"></script>
<script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
</script>
</body>
</html>
