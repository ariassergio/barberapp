<?php
// admin/includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Allow logged in users with role 'admin', 'owner' or 'maintainer'
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_rol']) || !in_array($_SESSION['admin_rol'], ['admin', 'owner', 'maintainer'])) {
    header('Location: login.php');
    exit;
}
?>