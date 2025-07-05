<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Merr të dhënat e përdoruesit nga baza
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Profile - Farmacia Jonë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
                        <a class="nav-link" href="products.php">Produktet</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="contact.html">Kontakt</a>
                    </li>
                      <li class="nav-item mx-2">
                        <a class="nav-link active" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
  

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow p-4">
                    <h3 class="mb-3 text-center">My Profile</h3>
                    
                    <form id="updateProfileForm" method="post" action="update_profile.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" required
                                   value="<?= htmlspecialchars($user['name']) ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required
                                   value="<?= htmlspecialchars($user['email']) ?>">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Changes</button><br><br>
                         <a href="logout.php"  class="btn btn-primary w-100" style="text-decoration:none; color:white;">Log Out</a>
                    </form>
                    
                    <div id="updateMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
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
    <script>
       
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const msgDiv = document.getElementById('updateMessage');
                msgDiv.textContent = data.message;
                msgDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
            })
            .catch(err => {
                console.error(err);
            });
        });
    </script>
</body>
</html>
