<?php

require_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$nombre          = $data['nombre'];
$telefono        = $data['telefono'];
$fecha           = $data['fecha'];
$hora            = $data['hora'];
$id_profesional  = $data['id_profesional'];
$id_servicio     = $data['id_servicio'];
$estado          = $data['estado'];

$fecha_inicio = $fecha . " " . $hora . ":00";

$sql = "INSERT INTO turnos (
            nombre,
            telefono,
            fecha_inicio,
            estado,
            id_profesional,
            id_servicio
        )
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssii",
    $nombre,
    $telefono,
    $fecha_inicio,
    $estado,
    $id_profesional,
    $id_servicio
);

$ok = mysqli_stmt_execute($stmt);

echo json_encode([
    "ok" => $ok
]);