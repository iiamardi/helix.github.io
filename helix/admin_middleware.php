<?php
session_start();

// Kontrollo nëse përdoruesi është i loguar dhe është admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    // Ruaj mesazhin e gabimit në session
    $_SESSION['error'] = 'Ju nuk keni leje për të hyrë në këtë faqe';
    
    // Ridrejto në faqen e hyrjes
    header('Location: login.php');
    exit;
}
?>