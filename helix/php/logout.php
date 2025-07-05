<?php
session_start();

// Fshi të gjitha variablat e session-it
$_SESSION = array();

// Nëse dëshironi të shkatërroni session-in plotësisht, fshini edhe cookie-n
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Shkatërro session-in
session_destroy();

// Ridrejto në faqen e hyrjes
header("Location: login.php");
exit;
?>