<?php
require_once '../../admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_profesional']);
    $estado_actual = intval($_POST['estado_actual']);
    
    // Toggle: if 1 then 0, if 0 then 1
    $nuevo_estado = ($estado_actual == 1) ? 0 : 1;

    $sql = "UPDATE profesionales SET activo = $nuevo_estado WHERE id_profesional = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../barberos.php?success=toggled");
    } else {
        header("Location: ../barberos.php?error=db");
    }
    exit;
} else {
    header("Location: ../barberos.php");
    exit;
}
