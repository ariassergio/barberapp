<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Only allow logged in users with role 'maintainer'
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_rol']) || $_SESSION['admin_rol'] !== 'maintainer') {
    header('Location: login.php');
    exit;
}
?>
