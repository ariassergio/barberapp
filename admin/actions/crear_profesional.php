<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../barberos.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$especialidades = is_array($_POST['especialidades'])
    ? implode(", ", array_map('htmlspecialchars', $_POST['especialidades']))
    : '';

if (empty($nombre)) {
    header("Location: ../barberos.php?error=nombre_requerido");
    exit;
}

$stmt = $conn->prepare("INSERT INTO profesionales (nombre, especialidad) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $especialidades);
$stmt->execute();

header("Location: ../barberos.php?success=1");
exit;