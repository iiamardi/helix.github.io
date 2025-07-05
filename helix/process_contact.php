<?php
session_start();
include 'php/db.php';  // përfshin lidhjen PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Merr të dhënat nga forma dhe bëj sanitize (opcionale sepse PDO prepared statements janë më të sigurta)
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    try {
        // Përgatit query me prepared statement
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, phone, message) VALUES (:name, :email, :phone, :message)");

        // Ekzekuto query me të dhënat
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':message' => $message
        ]);

        // Vendos mesazh suksesi në sesion (opsionale)
        $_SESSION['msg_success'] = "Mesazhi u dërgua me sukses!";

        // Redirect tek admin dashboard
        header("Location: admin-dashboard.php");
        exit;

    } catch (PDOException $e) {
        // Nëse ka gabim, shfaq gabimin ose ruaje në log
        die("Gabim në ruajtjen e mesazhit: " . $e->getMessage());
    }
} else {
    // Nëse nuk është POST, ridrejto në kontakt apo faqen kryesore
    header("Location: contact.html");
    exit;
}
