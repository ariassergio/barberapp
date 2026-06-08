<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../servicios.php");
    exit;
}

$nombre   = trim($_POST['nombre'] ?? '');
$precio   = floatval($_POST['precio'] ?? 0);
$duracion = intval($_POST['duracion'] ?? 0);

if (empty($nombre) || $precio <= 0 || $duracion <= 0) {
    header("Location: ../servicios.php?error=datos_invalidos");
    exit;
}

$stmt = $conn->prepare("INSERT INTO servicios (nombre, precio, duracion_minutos) VALUES (?, ?, ?)");
$stmt->bind_param("sdi", $nombre, $precio, $duracion);
$stmt->execute();

header("Location: ../servicios.php?success=1");
exit;