<?php
require_once '../../admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_profesional']);
    $nombre = mysqli_real_escape_string($conn, trim($_POST['nombre']));
    $especialidad = mysqli_real_escape_string($conn, trim($_POST['especialidad']));
    
    $sql = "UPDATE profesionales SET nombre = '$nombre', especialidad = '$especialidad' WHERE id_profesional = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../barberos.php?success=edited");
    } else {
        header("Location: ../barberos.php?error=db");
    }
    exit;
} else {
    header("Location: ../barberos.php");
    exit;
}
