<?php
session_start();
include 'php/db.php'; // përfshin lidhjen $pdo

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['msg_error'] = "ID e produktit mungon.";
    header("Location: admin-dashboard.php");
    exit;
}

$id = intval($_GET['id']); // merr id dhe sigurohu që është numër

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['msg_success'] = "Produkti u fshi me sukses.";
    } else {
        $_SESSION['msg_error'] = "Produkti nuk u gjet ose nuk u fshi.";
    }
} catch (PDOException $e) {
    $_SESSION['msg_error'] = "Gabim gjatë fshirjes së produktit: " . $e->getMessage();
}

header("Location: admin-dashboard.php");
exit;
?>
