<?php
include 'php/db.php'; // përfshin $pdo

// Merr mesazhet
try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gabim në marrjen e mesazheve: " . $e->getMessage());
}

// Merr përdoruesit
try {
    $stmtUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gabim në marrjen e përdoruesve: " . $e->getMessage());
}

try {
    $stmtProducts = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gabim në marrjen e produkteve: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Paneli i Adminit - Përdoruesit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Paneli i Adminit</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-light">Dil</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista e Përdoruesve</h2>
           <a href="login.html" class="btn btn-success">Shto Përdorues</a>
            <a href="add-product.php" class="btn btn-success">Shto Produkte</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Emri</th>
                        <th>Email</th>
                        <th>Roli</th>
                        <th>Veprime</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <a href="edit-user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edito</a>
                               <a href="delete_account.php?id=<?= $user['id'] ?>" onclick="return confirm('A jeni i sigurt?');" class="btn btn-sm btn-danger">Fshi</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="4" class="text-center">Asnjë përdorues i regjistruar.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
      <h1>Mesazhet e Marra</h1>
    
    <?php if (isset($_SESSION['msg_success'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?></div>
    <?php endif; ?>

  <table class="table table-striped">
    <thead>
        <tr>
            <th>Emri</th>
            <th>Email</th>
            <th>Telefoni</th>
            <th>Mesazhi</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($messages as $msg): ?>
        <tr>
            <td><?= htmlspecialchars($msg['name']) ?></td>
            <td><?= htmlspecialchars($msg['email']) ?></td>
            <td><?= htmlspecialchars($msg['phone']) ?></td>
            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
            <td><?= htmlspecialchars($msg['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h2>Lista e Produkteve</h2>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Emri i Produktit</th>
            <th>Pershkrimi</th>
            <th>Cmimi</th>
             
            <th>Veprime</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?> €</td>
                     
                    <td>
                        <a href="edit-product.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">Edito</a>
                        <a href="delete-product.php?id=<?= $product['id'] ?>" onclick="return confirm('A jeni i sigurt?');" class="btn btn-danger btn-sm">Fshi</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">Asnjë produkt i shtuar.</td></tr>
        <?php endif; ?>
    </tbody>
</table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
